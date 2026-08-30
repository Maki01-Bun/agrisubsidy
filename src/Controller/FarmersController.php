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
    if (!$this->request->is('post')) {
        return $this->redirect(['action' => 'index']);
    }

    // Get uploaded file
    $file = $this->request->getData('excel_file');

    // Check if file exists and uploaded successfully
    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {

        $this->Flash->error(
            'Please select a valid Excel file.'
        );

        return $this->redirect(['action' => 'index']);
    }

    // Check file extension
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

        /*
         * Load Excel file
         */
        $spreadsheet = IOFactory::load(
            $file->getStream()->getMetadata('uri')
        );

        /*
         * Get active worksheet
         */
        $sheet = $spreadsheet->getActiveSheet();

        /*
         * Convert worksheet to array
         *
         * true  = return formulas calculated values
         * true  = calculate formulas
         * true  = preserve cell formatting
         * true  = use column letters
         */
        $rows = $sheet->toArray(
            null,
            true,
            true,
            true
        );

        $success = 0;
        $failed = 0;

        $errors = [];

        /*
         * Loop through Excel rows
         */
        foreach ($rows as $index => $row) {

            /*
             * Skip header row
             */
            if ($index == 1) {
                continue;
            }

            /*
             * Skip completely empty rows
             */
            if (
                empty($row['A']) &&
                empty($row['B']) &&
                empty($row['C']) &&
                empty($row['D']) &&
                empty($row['E']) &&
                empty($row['F']) &&
                empty($row['G']) &&
                empty($row['H'])
            ) {
                continue;
            }

            try {

                /*
                 * Create new Farmer entity
                 */
                $farmer = $this->Farmers->newEmptyEntity();

                /*
                 * Farmer Number
                 *
                 * IMPORTANT:
                 * Treat this as a string.
                 */
                $farmer->farmer_no = trim(
                    (string)($row['A'] ?? '')
                );

                /*
                 * First Name
                 */
                $farmer->first_name = trim(
                    (string)($row['B'] ?? '')
                );

                /*
                 * Last Name
                 */
                $farmer->last_name = trim(
                    (string)($row['C'] ?? '')
                );

                /*
                 * Middle Name
                 */
                $farmer->middle_name = trim(
                    (string)($row['D'] ?? '')
                );

                /*
                 * Gender
                 */
                $farmer->gender = trim(
                    (string)($row['E'] ?? '')
                );

                /*
                 * Birthdate
                 */
                if (!empty($row['F'])) {

                    /*
                     * Excel stores dates as numbers.
                     */
                    if (is_numeric($row['F'])) {

                        $farmer->birthdate =
                            Date::excelToDateTimeObject(
                                $row['F']
                            );

                    } else {

                        /*
                         * Handle normal date strings
                         */
                        $farmer->birthdate =
                            date_create(
                                (string)$row['F']
                            );
                    }

                } else {

                    $farmer->birthdate = null;
                }

                /*
                 * Contact Number
                 *
                 * Treat as STRING to preserve
                 * leading zero.
                 */
                $farmer->contact_no = trim(
                    (string)($row['G'] ?? '')
                );

                /*
                 * Address
                 */
                $farmer->address = trim(
                    (string)($row['H'] ?? '')
                );

                /*
                 * Save Farmer
                 */
                if ($this->Farmers->save($farmer)) {

                    $success++;

                } else {

                    $failed++;

                    /*
                     * Get validation errors
                     */
                    $validationErrors =
                        $farmer->getErrors();

                    $errorText =
                        "Row {$index}: Could not save farmer.";

                    if (!empty($validationErrors)) {

                        $errorText .= ' ' .
                            json_encode(
                                $validationErrors
                            );
                    }

                    $errors[] = $errorText;
                }

            } catch (\Throwable $e) {

                /*
                 * Catch errors for individual rows
                 * so one bad row doesn't stop
                 * the entire Excel import.
                 */
                $failed++;

                $errors[] =
                    "Row {$index}: " .
                    $e->getMessage();
            }
        }

        /*
         * ==========================================
         * DISPLAY IMPORT RESULT
         * ==========================================
         */

        /*
         * Successful imports
         */
        if ($success > 0) {

            $this->Flash->success(
                "Excel import completed successfully. " .
                "{$success} farmer(s) imported."
            );
        }

        /*
         * Failed rows
         */
        if ($failed > 0) {

            $errorMessage =
                "{$failed} row(s) could not be imported.";

            /*
             * Display first 3 errors only
             * so the alert doesn't become too large.
             */
            if (!empty($errors)) {

                $errorMessage .= ' ' .
                    implode(
                        ' | ',
                        array_slice($errors, 0, 3)
                    );
            }

            $this->Flash->error(
                $errorMessage
            );
        }

        /*
         * No records found
         */
        if ($success === 0 && $failed === 0) {

            $this->Flash->warning(
                'The Excel file contains no farmer records.'
            );
        }

    } catch (\Throwable $e) {

        /*
         * Excel itself could not be read.
         */
        $this->Flash->error(
            'Unable to read the Excel file: ' .
            $e->getMessage()
        );
    }

    /*
     * Return to Farmers page
     */
    return $this->redirect([
        'action' => 'index'
    ]);
}
}
