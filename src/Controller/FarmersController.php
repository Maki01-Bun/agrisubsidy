<?php
declare(strict_types=1);

namespace App\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Farmer Controller
 * @property \App\Model\Table\FarmersTable $Farmers
 * @method \App\Model\Entity\Farmer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FarmersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
     public function index()
    {
        $farmer = $this->Farmers->newEmptyEntity();

        $this->set(compact('farmer'));
    }

    /**
     * View method
     *
     * @param string|null $id Farmer id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $farmer = $this->Farmers->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('farmer'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $farmer = $this->Farmers->newEmptyEntity();
        if ($this->request->is('post')) {
            $farmer = $this->Farmers->patchEntity($farmer, $this->request->getData());
            if ($this->Farmers->save($farmer)) {
                $this->Flash->success(__('The farmer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The farmer could not be saved. Please, try again.'));
        }
        $this->set(compact('farmer'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Farmer id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $farmer = $this->Farmer->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $farmer = $this->Farmers->patchEntity($farmer, $this->request->getData());
            if ($this->Farmers->save($farmer)) {
                $this->Flash->success(__('The farmer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The farmer could not be saved. Please, try again.'));
        }
        $this->set(compact('farmer'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Farmer id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $farmer = $this->Farmers->get($id);
        if ($this->Farmers->delete($farmer)) {
            $this->Flash->success(__('The farmer has been deleted.'));
        } else {
            $this->Flash->error(__('The farmer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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

                    $farmer = $this->Farmers->newEmptyEntity();

                    $farmer->farmer_no = trim($row['A'] ?? '');
                    $farmer->first_name = trim($row['B'] ?? '');
                    $farmer->last_name = trim($row['C'] ?? '');
                    $farmer->middle_name = trim($row['D'] ?? '');
                    $farmer->gender = trim($row['E'] ?? '');
                    $farmer->birthdate = !empty($row['F'])
                        ? $row['F']
                        : null;
                    $farmer->contact_no = trim($row['G'] ?? '');
                    $farmer->address = trim($row['H'] ?? '');

                    if ($this->Farmers->save($farmer)) {
                        $success++;
                    } else {
                        $failed++;
                    }
                }

                $this->Flash->success(
                    "Excel import completed. {$success} farmer(s) imported."
                );

                if ($failed > 0) {
                    $this->Flash->warning(
                        "{$failed} row(s) could not be imported."
                    );
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
