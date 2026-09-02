<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenDate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Record Controller
 *
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $records = $this->Records->newEmptyEntity();

        $this->loadModel('Farmers');
        $this->loadModel('Schedules');

        // Farmers
        $farmers = $this->Farmers->find()
            ->all()
            ->combine(
                'id',
                function ($farmer) {
                    return trim(
                        ($farmer->first_name ?? '') . ' ' .
                        ($farmer->last_name ?? '')
                    );
                }
            )
            ->toArray();

        // Schedules
        $scheduleData = $this->Schedules->find()
            ->select([
                'id',
                'program_name',
                'start_date'
            ])
            ->order([
                'program_name' => 'ASC'
            ])
            ->all()
            ->toArray();

        // Schedule dropdown
        $schedules = [];

        foreach ($scheduleData as $schedule) {
            $schedules[$schedule->id] = $schedule->program_name;
        }

        $this->set(compact(
            'records',
            'farmers',
            'schedules',
            'scheduleData'
        ));
    }
    /**
     * View method
     *
     * @param string|null $id Distribution id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $record = $this->Records->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('record'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $record = $this->Records->newEmptyEntity();
        if ($this->request->is('post')) {
            $record = $this->Records->patchEntity($record, $this->request->getData());
            if ($this->Records->save($record)) {
                $this->Flash->success(__('The record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The record could not be saved. Please, try again.'));
        }

        $this->set(compact('record'));

    }

    /**
     * Edit method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $record = $this->Records->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $record = $this->Records->patchEntity($record, $this->request->getData());
            if ($this->Records->save($record)) {
                $this->Flash->success(__('The record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The record could not be saved. Please, try again.'));
        }
        $this->set(compact('record'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $record = $this->Records->get($id);

        if ($this->Records->delete($record)) {

            $this->Flash->success(
                __('The record has been deleted.')
            );

        } else {

            $this->Flash->error(
                __('The record could not be deleted. Please, try again.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    public function cancel($id = null)
    {
        $this->request->allowMethod(['post']);

        $record = $this->Records->get($id);

        $record->status = 'Cancelled';

        if ($this->Records->save($record)) {

            $this->Flash->success(
                __('The record has been cancelled.')
            );

        } else {

            $this->Flash->error(
                __('The record could not be cancelled.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    public function notReceived($id = null)
    {
        $this->request->allowMethod(['post']);

        $record = $this->Records->get($id);

        $record->status = 'Not Received';
        $record->confirmed_at = FrozenTime::now();

        // There is no received date because
        // the farmer did not receive the subsidy.
        $record->received_date = null;

        if ($this->Records->save($record)) {

            $this->Flash->success(
                __('The subsidy has been marked as not received.')
            );

        } else {

            $this->Flash->error(
                __('The record could not be updated.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    public function uploadExcel()
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'index']);
        }
        $file = $this->request->getData('excel_file');
        // Validate uploaded file
        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
            $this->Flash->error('Please select a valid Excel file.');
            return $this->redirect(['action' => 'index']);
        }
        // Validate extension
        $extension = strtolower(
            pathinfo(
                $file->getClientFilename(),
                PATHINFO_EXTENSION
            )
        );
        if (!in_array($extension, ['xlsx', 'xls'])) {
            $this->Flash->error(
                'Only Excel files (.xlsx or .xls) are allowed.'
            );
            return $this->redirect(['action' => 'index']);
        }
        try {

            $this->loadModel('Farmers');
            $this->loadModel('Schedules');
            // Load Excel
            $spreadsheet = IOFactory::load(
                $file->getStream()->getMetadata('uri')
            );
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(
                null,
                true,
                true,
                true
            );

            $success = 0;
            $failed = 0;
            $errors = [];

            foreach ($rows as $index => $row) {

                // Skip header
                if ($index == 1) {
                    continue;
                }

                // Skip empty rows
                if (
                    empty($row['A']) &&
                    empty($row['B']) &&
                    empty($row['C']) &&
                    empty($row['D']) &&
                    empty($row['E'])
                ) {
                    continue;
                }

                $farmerName = trim((string)($row['A'] ?? ''));
                $subsidyItem = trim((string)($row['B'] ?? ''));
                $quantity = (float)($row['C'] ?? 0);
                $receivedDate = trim((string)($row['D'] ?? ''));
                $status = trim((string)($row['E'] ?? ''));


                // Validate farmer
                if ($farmerName === '') {
                    $failed++;
                    $errors[] =
                        "Row {$index}: Farmer name is required.";
                    continue;
                }

                // Validate subsidy item
                if ($subsidyItem === '') {
                    $failed++;
                    $errors[] =
                        "Row {$index}: Subsidy item is required.";

                    continue;
                }

                // Validate quantity
                if ($quantity <= 0) {
                    $failed++;
                    $errors[] =
                        "Row {$index}: Quantity must be greater than 0.";

                    continue;
                }

                /*
                 * Find farmer
                 */

                $nameParts = preg_split(
                    '/\s+/',
                    $farmerName
                );
                $firstName = $nameParts[0] ?? '';
                $lastName = '';
                if (count($nameParts) > 1) {
                    $lastName = end($nameParts);
                }
                $farmer = $this->Farmers->find()
                    ->where([
                        'Farmers.first_name' => $firstName,
                        'Farmers.last_name' => $lastName
                    ])
                    ->first();


                if (!$farmer) {
                    $failed++;
                    $errors[] =
                        "Row {$index}: Farmer not found: " .
                        $farmerName;
                    continue;
                }


                /*
                 * Find schedule
                 *
                 * We are no longer comparing
                 * program_name with farmer name.
                 *
                 * We use subsidy_type.
                 */
                $schedule = $this->Schedules->find()
                    ->where([
                        'Schedules.subsidy_type' => $subsidyItem
                    ])
                    ->order([
                        'Schedules.start_date' => 'DESC'
                    ])
                    ->first();
                if (!$schedule) {
                    $failed++;
                    $errors[] =
                        "Row {$index}: Schedule not found for " .
                        "subsidy item: {$subsidyItem}";

                    continue;
                }

                /*
                 * Distribution date comes from schedule
                 */
                $distributionDate = $schedule->start_date;

                /*
                 * Convert received date
                 */
                $formattedReceivedDate = null;
                if ($receivedDate !== '') {
                    try {
                        if (is_numeric($receivedDate)) {
                            $formattedReceivedDate =
                                \PhpOffice\PhpSpreadsheet\Shared\Date
                                    ::excelToDateTimeObject(
                                        $receivedDate
                                    )
                                    ->format('Y-m-d');
                        } else {
                            $formattedReceivedDate =
                                (new \DateTime($receivedDate))
                                    ->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        $failed++;
                        $errors[] =
                            "Row {$index}: Invalid received date: " .
                            $receivedDate;

                        continue;
                    }
                }

                /*
                 * Create record
                 */
                $record = $this->Records->newEmptyEntity();
                $record->farmer_id = $farmer->id;
                $record->schedule_id = $schedule->id;
                // Get program name from schedule
                $record->program_name = $schedule->program_name;
                // Get subsidy item from Excel
                $record->subsidy_item = $subsidyItem;
                $record->quantity = $quantity;
                // Get distribution date from schedule
                $record->distribution_date = $distributionDate;
                // Get received date from Excel
                $record->received_date = $formattedReceivedDate;
                $record->status = $status;

                /*
                 * Save
                 */
                if ($this->Records->save($record)) {
                    $success++;
                } else {
                    $failed++;
                    $errors[] =
                        "Row {$index}: Failed to save record: " .
                        json_encode(
                            $record->getErrors()
                        );
                }
            }

            if ($success > 0) {
                $this->Flash->success(
                    "Excel import completed. " .
                    "{$success} record(s) imported."
                );
            }
            if ($failed > 0) {
                $message =
                    "{$failed} row(s) could not be imported.";
                if (!empty($errors)) {
                $message .= '<br>' . implode( '<br>', $errors );
                }
                $this->Flash->warning($message);
            }
        } catch (\Exception $e) {
            $this->Flash->error('Unable to read the Excel file: ' . $e->getMessage()
            );
        }
        return $this->redirect(['action' => 'index']);
    }

    public function viewRecord($id = null)
    {
        if (!$id) {
            $this->Flash->error('Invalid record ID.');
            return $this->redirect(['action' => 'index']);
        }

        try {

            $record = $this->Records->get($id, [
                'contain' => [
                    'Farmers',
                    'Schedules'
                ]
                ]);

            $this->set([
                'record' => $record
            ]);

        } catch (\Exception $e) {

            $this->Flash->error('Record not found.');

            return $this->redirect([
                'action' => 'index'
            ]);
        }
    }

    public function getRecord($recordId = null)
    {
        $this->request->allowMethod(['get']);
    
        $this->autoRender = false;
    
        if (!$recordId) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Invalid record ID.'
                ]));
        }
    
        try {
    
            /*
             * Get Record
             * Including Farmer and Schedule
             */
            $record = $this->Records->get(
                $recordId,
                [
                    'contain' => [
                        'Farmers',
                        'Schedules'
                    ]
                ]
            );
    
            /*
             * ==========================================
             * FARMER INFORMATION
             * ==========================================
             */
    
            $farmerName = 'N/A';
    
            if (!empty($record->farmer)) {
    
                $farmerName = trim(
                    ($record->farmer->first_name ?? '') . ' ' .
                    ($record->farmer->last_name ?? '')
                );
            }
    
            /*
             * ==========================================
             * SCHEDULE INFORMATION
             * ==========================================
             */
    
            $distributionDate = 'N/A';
            $distributionTime = 'N/A';
            $programName = 'N/A';
    
            if (!empty($record->schedule)) {
    
                $schedule = $record->schedule;
    
                /*
                 * Date
                 */
                if (!empty($schedule->start_date)) {
                    $distributionDate = $schedule->start_date;
                }
    
                /*
                 * Time
                 */
                if (!empty($schedule->start_time)) {
                    $distributionTime = $schedule->start_time;
                }
    
                /*
                 * Program
                 */
                if (!empty($schedule->program_name)) {
                    $programName = $schedule->program_name;
                }
            }
    
            /*
             * ==========================================
             * RECORD INFORMATION
             * ==========================================
             */
    
            $subsidyItem =
                !empty($record->subsidy_item)
                    ? $record->subsidy_item
                    : 'N/A';
    
            $quantity =
                isset($record->quantity)
                    ? $record->quantity
                    : 'N/A';
    
            $receivedDate =
                !empty($record->received_date)
                    ? $record->received_date
                    : 'N/A';
    
            $status =
                !empty($record->status)
                    ? $record->status
                    : 'N/A';
    
            /*
             * ==========================================
             * JSON RESPONSE
             * ==========================================
             */
    
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
    
                    'data' => [
    
                        'id' =>
                            $record->id,
    
                        'farmer_id' =>
                            $record->farmer_id,
    
                        'schedule_id' =>
                            $record->schedule_id,
    
                        'farmer_name' =>
                            $farmerName,
    
                        'program_name' =>
                            $programName,
    
                        'subsidy_item' =>
                            $subsidyItem,
    
                        'quantity' =>
                            $quantity,
    
                        'distribution_date' =>
                            $distributionDate,
    
                        'received_date' =>
                            $receivedDate,
    
                        'status' =>
                            $status,
    
                    ]
                ]));
    
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false,
            'message' => $e->getMessage()]));
        }
    }
}
