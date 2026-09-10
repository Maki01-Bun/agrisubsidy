<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenDate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * Farms Controller
 *
 * @method \App\Model\Entity\Farm[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FarmsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

    }

    /**
     * View method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $farm = $this->Farms->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('farm'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $farm = $this->Farms->newEmptyEntity();
        if ($this->request->is('post')) {
            $farm = $this->Farms->patchEntity($farm, $this->request->getData());
            if ($this->Farms->save($farm)) {
                $this->Flash->success(__('The farm has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The farm could not be saved. Please, try again.'));
        }
        $this->set(compact('farm'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $farm = $this->Farms->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $farm = $this->Farms->patchEntity($farm, $this->request->getData());
            if ($this->Farms->save($farm)) {
                $this->Flash->success(__('The farm has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The farm could not be saved. Please, try again.'));
        }
        $this->set(compact('farm'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $farm = $this->Farms->get($id);
        if ($this->Farms->delete($farm)) {
            $this->Flash->success(__('The farm has been deleted.'));
        } else {
            $this->Flash->error(__('The farm could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function uploadExcel()
{
    /*
     * =========================================================
     * ONLY ALLOW POST REQUEST
     * =========================================================
     */
    if (!$this->request->is('post')) {
        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /*
     * =========================================================
     * GET UPLOADED FILE
     * =========================================================
     */
    $file = $this->request->getData('excel_file');

    /*
     * =========================================================
     * VALIDATE UPLOADED FILE
     * =========================================================
     */
    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {

        $this->request->getSession()->write(
            'ExcelImportResult',
            [
                'type' => 'error',
                'success' => 0,
                'failed' => 0,
                'errors' => [
                    'Please select a valid Excel file.'
                ]
            ]
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /*
     * =========================================================
     * VALIDATE FILE EXTENSION
     * =========================================================
     */
    $extension = strtolower(
        pathinfo(
            $file->getClientFilename(),
            PATHINFO_EXTENSION
        )
    );

    if (!in_array($extension, ['xlsx', 'xls'])) {

        $this->request->getSession()->write(
            'ExcelImportResult',
            [
                'type' => 'error',
                'success' => 0,
                'failed' => 0,
                'errors' => [
                    'Only Excel files (.xlsx or .xls) are allowed.'
                ]
            ]
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    try {

        /*
         * =====================================================
         * LOAD TABLES
         * =====================================================
         */
        $this->loadModel('Farms');
        $this->loadModel('Farmers');

        /*
         * =====================================================
         * LOAD EXCEL FILE
         * =====================================================
         */
        $spreadsheet =
            \PhpOffice\PhpSpreadsheet\IOFactory::load(
                $file->getStream()->getMetadata('uri')
            );

        $sheet = $spreadsheet->getActiveSheet();

        /*
         * =====================================================
         * CONVERT EXCEL TO ARRAY
         * =====================================================
         */
        $rows = $sheet->toArray(
            null,
            true,
            true,
            true
        );

        /*
         * =====================================================
         * INITIALIZE COUNTERS
         * =====================================================
         */
        $success = 0;
        $failed = 0;
        $errors = [];

        /*
         * =====================================================
         * TRACK DUPLICATES INSIDE CURRENT EXCEL FILE
         *
         * Key:
         * farmer_id + farm_name
         * =====================================================
         */
        $uploadedRows = [];

        /*
         * =====================================================
         * PROCESS EACH ROW
         * =====================================================
         */
        foreach ($rows as $index => $row) {

            /*
             * -------------------------------------------------
             * SKIP HEADER
             * -------------------------------------------------
             */
            if ($index == 1) {
                continue;
            }

            /*
             * -------------------------------------------------
             * SKIP COMPLETELY EMPTY ROWS
             * -------------------------------------------------
             */
            if (
                empty($row['A']) &&
                empty($row['B']) &&
                empty($row['C']) &&
                empty($row['D']) &&
                empty($row['E'])
            ) {
                continue;
            }

            /*
             * -------------------------------------------------
             * EXCEL COLUMNS
             *
             * A = Farmer Name
             * B = Farm Name
             * C = Farm Size
             * D = Location
             * E = Crop Yield
             * -------------------------------------------------
             */

            $farmerName = trim(
                (string)($row['A'] ?? '')
            );

            $farmName = trim(
                (string)($row['B'] ?? '')
            );

            $farmSize = trim(
                (string)($row['C'] ?? '')
            );

            $location = trim(
                (string)($row['D'] ?? '')
            );

            $cropYield = trim(
                (string)($row['E'] ?? '')
            );

            /*
             * =================================================
             * VALIDATE FARMER NAME
             * =================================================
             */
            if ($farmerName === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Farmer name is required.";

                continue;
            }

            /*
             * =================================================
             * VALIDATE FARM NAME
             * =================================================
             */
            if ($farmName === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Farm name is required.";

                continue;
            }

            /*
             * =================================================
             * VALIDATE FARM SIZE
             * =================================================
             */
            if ($farmSize === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Farm size is required.";

                continue;
            }

            if (
                !is_numeric($farmSize) ||
                (float)$farmSize <= 0
            ) {

                $failed++;

                $errors[] =
                    "Row {$index}: Farm size must be greater than 0.";

                continue;
            }

            /*
             * =================================================
             * VALIDATE LOCATION
             * =================================================
             */
            if ($location === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Location is required.";

                continue;
            }

            /*
             * =================================================
             * VALIDATE CROP YIELD
             * =================================================
             */
            if ($cropYield === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Crop yield is required.";

                continue;
            }

            if (
                !is_numeric($cropYield) ||
                (float)$cropYield < 0
            ) {

                $failed++;

                $errors[] =
                    "Row {$index}: Crop yield must be a valid number.";

                continue;
            }

            /*
             * =================================================
             * SPLIT FARMER FULL NAME
             *
             * Example:
             *
             * Emma Tan
             *
             * First Name = Emma
             * Last Name  = Tan
             * =================================================
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

            /*
             * =================================================
             * FIND FARMER
             * =================================================
             */
            $farmer = $this->Farmers->find()
                ->where([
                    'Farmers.first_name' => $firstName,
                    'Farmers.last_name' => $lastName
                ])
                ->first();

            /*
             * =================================================
             * FARMER NOT FOUND
             * =================================================
             */
            if (!$farmer) {

                $failed++;

                $errors[] =
                    "Row {$index}: Farmer not found: " .
                    $farmerName;

                continue;
            }

            /*
             * =================================================
             * CREATE UNIQUE DUPLICATE KEY
             *
             * Same farmer + same farm name
             * =================================================
             */
            $duplicateKey =
                strtolower(
                    trim((string)$farmer->id)
                )
                . '|'
                .
                strtolower(
                    trim($farmName)
                );

            /*
             * =================================================
             * CHECK DUPLICATE INSIDE EXCEL
             * =================================================
             */
            if (isset($uploadedRows[$duplicateKey])) {

                $previousRow =
                    $uploadedRows[$duplicateKey];

                $failed++;

                $errors[] =
                    "Row {$index}: Duplicate data in Excel. " .
                    "The farm '{$farmName}' for farmer " .
                    "'{$farmerName}' was already listed " .
                    "in row {$previousRow}.";

                continue;
            }

            /*
             * =================================================
             * CHECK DUPLICATE IN DATABASE
             * =================================================
             */
            $existingFarm = $this->Farms->find()
                ->where([
                    'Farms.farmer_id' => $farmer->id,
                    'Farms.farm_name' => $farmName
                ])
                ->first();

            if ($existingFarm) {

                $failed++;

                $errors[] =
                    "Row {$index}: Duplicate data. " .
                    "Farm '{$farmName}' already exists " .
                    "for farmer '{$farmerName}'.";

                /*
                 * Mark as encountered too.
                 *
                 * This prevents another identical row
                 * later in the same Excel file.
                 */
                $uploadedRows[$duplicateKey] = $index;

                continue;
            }

            /*
             * =================================================
             * MARK THIS ROW AS PROCESSED
             * =================================================
             */
            $uploadedRows[$duplicateKey] = $index;

            /*
             * =================================================
             * CREATE FARM ENTITY
             * =================================================
             */
            $farm = $this->Farms->newEmptyEntity();

            /*
             * Connect farm to farmer
             */
            $farm->farmer_id = $farmer->id;

            /*
             * Farm information
             */
            $farm->farm_name =
                $farmName;

            $farm->farm_size =
                (float)$farmSize;

            $farm->location =
                $location;

            $farm->crop_yield =
                (float)$cropYield;

            /*
             * =================================================
             * SAVE FARM
             * =================================================
             */
            if ($this->Farms->save($farm)) {

                $success++;

            } else {

                $failed++;

                $errors[] =
                    "Row {$index}: Failed to save farm: " .
                    json_encode(
                        $farm->getErrors()
                    );
            }
        }

        /*
         * =====================================================
         * ALL SUCCESSFUL
         * =====================================================
         */
        if ($success > 0 && $failed === 0) {

            $this->Flash->success(
                "Excel import completed successfully. " .
                "{$success} farm(s) imported."
            );
        }

        /*
         * =====================================================
         * PARTIAL SUCCESS
         *
         * Some records imported,
         * some records failed/duplicated.
         * =====================================================
         */
        elseif ($success > 0 && $failed > 0) {

            $this->request->getSession()->write(
                'ExcelImportResult',
                [
                    'type' => 'partial',
                    'success' => $success,
                    'failed' => $failed,
                    'errors' => $errors
                ]
            );
        }

        /*
         * =====================================================
         * ALL FAILED
         * =====================================================
         */
        elseif ($success === 0 && $failed > 0) {

            $this->request->getSession()->write(
                'ExcelImportResult',
                [
                    'type' => 'failed',
                    'success' => 0,
                    'failed' => $failed,
                    'errors' => $errors
                ]
            );
        }

        /*
         * =====================================================
         * NO DATA
         * =====================================================
         */
        else {

            $this->Flash->warning(
                'No farm records were found in the Excel file.'
            );
        }

    } catch (\Exception $e) {

        /*
         * =====================================================
         * EXCEL PROCESSING ERROR
         * =====================================================
         */
        $this->request->getSession()->write(
            'ExcelImportResult',
            [
                'type' => 'error',
                'success' => 0,
                'failed' => 0,
                'errors' => [
                    'Unable to read the Excel file: ' .
                    $e->getMessage()
                ]
            ]
        );
    }

    /*
     * =========================================================
     * RETURN TO FARM INDEX
     * =========================================================
     */
    return $this->redirect([
        'action' => 'index'
    ]);
}
}
