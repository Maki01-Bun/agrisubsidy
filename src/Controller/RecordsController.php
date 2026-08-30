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

        $farmers = $this->Farmers->find()->all()->combine(
        'id',
        function ($farmer) {
            return $farmer->first_name . ' ' . $farmer->last_name;})->toArray();
        $this->set(compact('records','farmers'));
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
        if ($this->request->is('post')) {
            $file = $this->request->getData('excel_file');
            if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
                $this->Flash->error('Please select a valid Excel file.');
                return $this->redirect(['action' => 'index']);
            }
            $extension = strtolower(
                pathinfo($file->getClientFilename(), PATHINFO_EXTENSION)
            );
            if (!in_array($extension, ['xlsx', 'xls'])) {
                $this->Flash->error('Only Excel files (.xlsx or .xls) are allowed.');
                return $this->redirect(['action' => 'index']);
            }

            try {
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

                foreach ($rows as $index => $row) {

                    // Skip header
                    if ($index == 1) {
                        continue;
                    }

                    // Skip completely empty rows
                    if (
                        empty($row['A']) &&
                        empty($row['B']) &&
                        empty($row['C'])
                    ) {
                        continue;
                    }

                    $programName = trim((string)($row['A'] ?? ''));
                    $subsidyItem = trim((string)($row['B'] ?? ''));
                    $quantity = (float)($row['C'] ?? 0);
                    $receivedDate = trim((string)($row['D'] ?? ''));
                    $status = trim((string)($row['E'] ?? ''));

                    $schedule = $this->Records->Schedules->find()
                        ->where([
                            'Schedules.program_name' => $programName,
                            'Schedules.start_date' => $distributionDate,
                        ])
                        ->first();

                    if (!$schedule) {
                        throw new \RuntimeException(
                            "Schedule not found: {$programName} / {$subsidyItem} / {$distributionDate}"
                        );
                    }

                    $record = $this->Records->newEmptyEntity();

                    $record->program_name = $programName;
                    $record->subsidy_item = $subsidyItem;
                    $record->quantity = $quantity;
                    $record->received_date = $received_date;
                    $record->status = $status;
                    $record->schedule_id = $schedule->id;

                    if (!$this->Records->save($record)) {
                        throw new \RuntimeException(
                            'Failed to save record: ' .
                            json_encode($record->getErrors())
                        );
                    }
                    if ($this->Records->save($record)) {
                        $success++;
                    } else {
                        $failed++;
                    }
                }

                $this->Flash->success(
                    "Excel import completed. {$success} record(s) imported."
                );

                if ($failed > 0) {
                    $this->Flash->warning("{$failed} row(s) could not be imported.");
                }
            } catch (\Exception $e) {
                $this->Flash->error(
                    'Unable to read the Excel file: ' . $e->getMessage()
                );
            }
        }
        return $this->redirect(['action' => 'index']);
    }

}
