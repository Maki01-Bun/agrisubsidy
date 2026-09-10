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
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();

        $success = 0;
        $failed = 0;
        $errors = [];

        /*
         * ============================================================
         * 1. GET UPLOADED FILE
         * ============================================================
         */

        $file = $this->request->getData('excel_file');

        if (!$file || !is_object($file)) {

            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'No Excel file was uploaded.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * 2. CHECK UPLOAD ERROR
         * ============================================================
         */

        if ($file->getError() !== UPLOAD_ERR_OK) {

            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'The Excel file could not be uploaded.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * 3. CHECK FILE EXTENSION
         * ============================================================
         */

        $extension = strtolower(
            pathinfo(
                $file->getClientFilename(),
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($extension, ['xlsx', 'xls'], true)) {

            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'Invalid file format. Please upload an XLS or XLSX file.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * 4. LOAD REQUIRED TABLES
         * ============================================================
         */

        $this->loadModel('Farmers');
        $this->loadModel('Schedules');

        /*
         * ============================================================
         * 5. FIND THE SCHEDULE
         *
         * IMPORTANT:
         *
         * We DO NOT use:
         *
         * Schedules.subsidy_type
         *
         * because that field has already been removed.
         *
         * We only use program_name.
         * ============================================================
         */

        $schedule = $this->Schedules
            ->find()
            ->where([
                'Schedules.program_name' => 'Seed Subsidy'
            ])
            ->order([
                'Schedules.start_date' => 'DESC'
            ])
            ->first();

        if (!$schedule) {

            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'No Seed Subsidy schedule was found.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * 6. LOAD EXCEL
         * ============================================================
         */

        try {

            $spreadsheet = IOFactory::load(
                $file->getStream()->getMetadata('uri')
            );

        } catch (\Throwable $e) {

            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'Unable to read the Excel file: ' . $e->getMessage()
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * 7. GET ACTIVE SHEET
         * ============================================================
         */

        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();

        /*
         * ============================================================
         * 8. DISTRIBUTION DATE
         *
         * Get the date from the SCHEDULE.
         *
         * We do NOT get subsidy type from schedule.
         * ============================================================
         */

        $distributionDate = null;

        if (!empty($schedule->distribution_date)) {

            $distributionDate = $schedule->distribution_date;

        } elseif (!empty($schedule->start_date)) {

            $distributionDate = $schedule->start_date;
        }

        /*
         * ============================================================
         * 9. PROCESS EACH EXCEL ROW
         * ============================================================
         */

        for ($row = 2; $row <= $highestRow; $row++) {

            /*
             * --------------------------------------------------------
             * GET EXCEL VALUES
             * --------------------------------------------------------
             */

            $farmerName = trim(
                (string)$sheet->getCell("A{$row}")->getValue()
            );

            $quantityValue = $sheet
                ->getCell("B{$row}")
                ->getValue();

            $receivedDateValue = $sheet
                ->getCell("C{$row}")
                ->getValue();

            $statusValue = trim(
                (string)$sheet->getCell("D{$row}")->getValue()
            );

            /*
             * --------------------------------------------------------
             * SKIP COMPLETELY EMPTY ROW
             * --------------------------------------------------------
             */

            if (
                $farmerName === '' &&
                ($quantityValue === null || $quantityValue === '') &&
                ($receivedDateValue === null || $receivedDateValue === '') &&
                $statusValue === ''
            ) {
                continue;
            }

            /*
             * --------------------------------------------------------
             * VALIDATE FARMER NAME
             * --------------------------------------------------------
             */

            if ($farmerName === '') {

                $failed++;

                $errors[] =
                    "Row {$row}: Farmer name is required.";

                continue;
            }

            /*
             * --------------------------------------------------------
             * VALIDATE QUANTITY
             * --------------------------------------------------------
             */

            if (
                $quantityValue === null ||
                $quantityValue === '' ||
                !is_numeric($quantityValue)
            ) {

                $failed++;

                $errors[] =
                    "Row {$row}: Quantity must be a valid number.";

                continue;
            }

            $quantity = (float)$quantityValue;

            if ($quantity <= 0) {

                $failed++;

                $errors[] =
                    "Row {$row}: Quantity must be greater than zero.";

                continue;
            }

            /*
             * --------------------------------------------------------
             * SPLIT FARMER NAME
             *
             * Example:
             *
             * Juan Dela Cruz
             *
             * first_name = Juan
             * last_name  = Dela Cruz
             * --------------------------------------------------------
             */

            $nameParts = preg_split(
                '/\s+/',
                $farmerName
            );

            $firstName = $nameParts[0] ?? '';

            $lastName = '';

            if (count($nameParts) > 1) {

                $lastName = implode(
                    ' ',
                    array_slice($nameParts, 1)
                );
            }

            /*
             * --------------------------------------------------------
             * FIND FARMER
             * --------------------------------------------------------
             */

            $farmer = $this->Farmers
                ->find()
                ->where([
                    'LOWER(Farmers.first_name)' =>
                        strtolower($firstName),
                    'LOWER(Farmers.last_name)' =>
                        strtolower($lastName)
                ])
                ->first();

            if (!$farmer) {

                $failed++;

                $errors[] =
                    "Row {$row}: Farmer '{$farmerName}' was not found.";

                continue;
            }

            /*
             * ========================================================
             * RECEIVED DATE
             * ========================================================
             */

            $receivedDate = null;

            if (
                $receivedDateValue !== null &&
                $receivedDateValue !== ''
            ) {

                try {

                    /*
                     * Excel numeric date
                     */

                    if (is_numeric($receivedDateValue)) {

                        $unixTimestamp =
                            \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp(
                                (float)$receivedDateValue
                            );

                        $receivedDate = new FrozenDate(
                            date(
                                'Y-m-d',
                                $unixTimestamp
                            )
                        );

                    } else {

                        /*
                         * Normal date string
                         */

                        $receivedDate = new FrozenDate(
                            date(
                                'Y-m-d',
                                strtotime((string)$receivedDateValue)
                            )
                        );
                    }

                } catch (\Throwable $e) {

                    $failed++;

                    $errors[] =
                        "Row {$row}: Invalid received date.";

                    continue;
                }
            }

            /*
             * ========================================================
             * STATUS
             * ========================================================
             */

            $status = $statusValue;

            /*
             * Normalize status
             */

            $statusLower = strtolower(
                trim($statusValue)
            );

            switch ($statusLower) {

                case 'received':
                    $status = 'Received';
                    break;

                case 'not received':
                case 'not_received':
                case 'notreceived':
                    $status = 'Not Received';
                    break;

                case 're-scheduled':
                case 'rescheduled':
                case 're scheduled':
                    $status = 'Re-Scheduled';
                    break;

                case 'cancelled':
                case 'canceled':
                    $status = 'Cancelled';
                    break;

                default:

                    if ($status === '') {
                        $status = 'Not Received';
                    }

                    break;
            }

            /*
             * ========================================================
             * DUPLICATE CHECK
             * ========================================================
             *
             * IMPORTANT:
             *
             * We use:
             *
             * farmer_id
             * schedule_id
             *
             * We DO NOT use:
             *
             * subsidy_type
             *
             * ========================================================
             */

            $existingRecord = $this->Records
                ->find()
                ->where([
                    'Records.farmer_id' => $farmer->id,
                    'Records.schedule_id' => $schedule->id
                ])
                ->first();

            /*
             * --------------------------------------------------------
             * IF RECORD ALREADY EXISTS
             * --------------------------------------------------------
             *
             * If you want Excel uploads to UPDATE existing records,
             * this is the section to modify.
             *
             * For now, existing records are skipped to prevent
             * duplicates.
             * --------------------------------------------------------
             */

            if ($existingRecord) {

                $failed++;

                $errors[] =
                    "Row {$row}: Distribution record for '{$farmerName}' already exists.";

                continue;
            }

            /*
             * ========================================================
             * CREATE NEW RECORD
             * ========================================================
             *
             * subsidy_item is completely independent.
             *
             * It is NOT:
             *
             * Schedules.subsidy_type
             *
             * ========================================================
             */

            $record = $this->Records->newEntity([
                'farmer_id' => $farmer->id,

                /*
                 * Schedule relationship
                 */
                'schedule_id' => $schedule->id,

                /*
                 * Program information
                 *
                 * Comes from Schedules.
                 */
                'program_name' => $schedule->program_name,

                /*
                 * subsidy_item is an independent field.
                 *
                 * It is NOT connected to subsidy_type.
                 *
                 * Change this value if your item has another name.
                 */
                'subsidy_item' => 'Seed Subsidy',

                /*
                 * Quantity from Excel
                 */
                'quantity' => $quantity,

                /*
                 * Distribution date comes from Schedule
                 */
                'distribution_date' => $distributionDate,

                /*
                 * Received date comes from Excel
                 */
                'received_date' => $receivedDate,

                /*
                 * Status comes from Excel
                 */
                'status' => $status
            ]);

            /*
             * ========================================================
             * SAVE RECORD
             * ========================================================
             */

            if ($this->Records->save($record)) {

                $success++;

            } else {

                $failed++;

                $validationErrors =
                    $record->getErrors();

                $errorMessage =
                    "Row {$row}: Unable to save record.";

                if (!empty($validationErrors)) {

                    $messages = [];

                    foreach (
                        $validationErrors as $field => $fieldErrors
                    ) {

                        if (is_array($fieldErrors)) {

                            foreach ($fieldErrors as $message) {

                                $messages[] =
                                    "{$field}: {$message}";
                            }
                        }
                    }

                    if (!empty($messages)) {

                        $errorMessage .=
                            ' ' .
                            implode(
                                ' ',
                                $messages
                            );
                    }
                }

                $errors[] = $errorMessage;
            }
        }

        /*
         * ============================================================
         * 10. STORE RESULT IN SESSION
         * ============================================================
         */

        if ($success > 0 && $failed === 0) {

            $type = 'success';

        } elseif ($success > 0 && $failed > 0) {

            $type = 'partial';

        } else {

            $type = 'failed';
        }

        $session->write(
            'ExcelImportResult',
            [
                'type' => $type,
                'success' => $success,
                'failed' => $failed,
                'errors' => $errors
            ]
        );

        /*
         * ============================================================
         * 11. REDIRECT
         * ============================================================
         */

        return $this->redirect([
            'action' => 'index'
        ]);
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
    public function getFarmerRecords($farmerId = null)
    {
        $this->request->allowMethod(['get']);

        try {

            if ($farmerId === null || !is_numeric($farmerId)) {

                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Invalid farmer ID.'
                    ]));
            }

            $farmerId = (int)$farmerId;

            $this->loadModel('Farmers');

            $farmer = $this->Farmers->find()
                ->select([
                    'id',
                    'first_name',
                    'last_name'
                ])
                ->where([
                    'Farmers.id' => $farmerId
                ])
                ->first();

            if (!$farmer) {

                return $this->response
                    ->withStatus(404)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Farmer not found.'
                    ]));
            }

            $records = $this->Records->find()
                ->select([
                    'record_id' => 'Records.id',
                    'farmer_id' => 'Records.farmer_id',
                    'schedule_id' => 'Records.schedule_id',

                    'subsidy_item' => 'Records.subsidy_item',
                    'quantity' => 'Records.quantity',
                    'received_date' => 'Records.received_date',
                    'status' => 'Records.status',

                    // Schedule information
                    'program_name' => 'Schedules.program_name',
                    'distribution_date' => 'Schedules.start_date'
                ])
                ->innerJoin(
                    ['Schedules' => 'schedules'],
                    [
                        'Schedules.id = Records.schedule_id'
                    ]
                )
                ->where([
                    'Records.farmer_id' => $farmerId
                ])
                ->order([
                    'Schedules.start_date' => 'DESC',
                    'Records.id' => 'DESC'
                ])
                ->enableHydration(false)
                ->toArray();

            foreach ($records as &$record) {

                if (
                    isset($record['distribution_date']) &&
                    $record['distribution_date'] instanceof \DateTimeInterface
                ) {
                    $record['distribution_date'] =
                        $record['distribution_date']->format('Y-m-d');
                }

                if (
                    isset($record['received_date']) &&
                    $record['received_date'] instanceof \DateTimeInterface
                ) {
                    $record['received_date'] =
                        $record['received_date']->format('Y-m-d');
                }
            }

            unset($record);

            $farmerData = [
                'id' => $farmer->id,
                'first_name' => $farmer->first_name ?? '',
                'last_name' => $farmer->last_name ?? ''
            ];

            return $this->response->withStatus(200)->withType('application/json')
            ->withStringBody(json_encode([
                    'success' => true,
                    'farmer' => $farmerData,
                    'records' => $records
                ], JSON_UNESCAPED_UNICODE));


        } catch (\Throwable $e) {
            \Cake\Log\Log::error(
                'RecordsController::getFarmerRecords(): ' .
                $e->getMessage() .
                "\n" .
                $e->getTraceAsString()
            );
            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]));
        }
    }
}
