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
    if (!$this->request->is('post')) {
        return $this->redirect(['action' => 'index']);
    }

    $file = $this->request->getData('excel_file');

    /*
     * Validate uploaded file
     */
    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
        $this->Flash->error('Please select a valid Excel file.');
        return $this->redirect(['action' => 'index']);
    }

    /*
     * Validate extension
     */
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
         * Load required tables
         */
        $this->loadModel('Farms');
        $this->loadModel('Farmers');

        /*
         * Load Excel
         */
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
            $file->getStream()->getMetadata('uri')
        );

        $sheet = $spreadsheet->getActiveSheet();

        /*
         * Convert Excel to array
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
         * Process rows
         */
        foreach ($rows as $index => $row) {

            /*
             * Skip header
             */
            if ($index == 1) {
                continue;
            }

            /*
             * Skip empty rows
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
             * Excel columns:
             *
             * A = Farmer Name
             * B = Farm Name
             * C = Farm Size
             * D = Location
             * E = Crop Yield
             */

            $farmerName = trim((string)($row['A'] ?? ''));
            $farmName   = trim((string)($row['B'] ?? ''));
            $farmSize   = trim((string)($row['C'] ?? ''));
            $location   = trim((string)($row['D'] ?? ''));
            $cropYield  = trim((string)($row['E'] ?? ''));

            /*
             * Validate Farmer Name
             */
            if ($farmerName === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Farmer name is required.";

                continue;
            }

            /*
             * Validate Farm Name
             */
            if ($farmName === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Farm name is required.";

                continue;
            }

            /*
             * Validate Farm Size
             */
            if ($farmSize === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Farm size is required.";

                continue;
            }

            if (!is_numeric($farmSize) || (float)$farmSize <= 0) {

                $failed++;

                $errors[] =
                    "Row {$index}: Farm size must be greater than 0.";

                continue;
            }

            /*
             * Validate Location
             */
            if ($location === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Location is required.";

                continue;
            }

            /*
             * Validate Crop Yield
             */
            if ($cropYield === '') {

                $failed++;

                $errors[] =
                    "Row {$index}: Crop yield is required.";

                continue;
            }

            if (!is_numeric($cropYield) || (float)$cropYield < 0) {

                $failed++;

                $errors[] =
                    "Row {$index}: Crop yield must be a valid number.";

                continue;
            }

            /*
             * Find farmer by full name
             *
             * Example:
             * Emma Tan
             * Caezar Abalos
             * Marc Nudo
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
             * Find farmer
             */
            $farmer = $this->Farmers->find()
                ->where([
                    'Farmers.first_name' => $firstName,
                    'Farmers.last_name' => $lastName
                ])
                ->first();

            /*
             * Farmer not found
             */
            if (!$farmer) {

                $failed++;

                $errors[] =
                    "Row {$index}: Farmer not found: " .
                    $farmerName;

                continue;
            }

            /*
             * Check if farm already exists
             * for this farmer
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
                    "Row {$index}: Farm already exists: " .
                    $farmName .
                    " for farmer " .
                    $farmerName;

                continue;
            }

            /*
             * Create Farm
             */
            $farm = $this->Farms->newEmptyEntity();

            /*
             * Connect farm to farmer
             */
            $farm->farmer_id = $farmer->id;

            /*
             * Farm information
             */
            $farm->farm_name = $farmName;
            $farm->farm_size = (float)$farmSize;
            $farm->location = $location;
            $farm->crop_yield = (float)$cropYield;

            /*
             * Save
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
         * Success message
         */
        if ($success > 0) {

            $this->Flash->success(
                "Excel import completed. " .
                "{$success} farm(s) imported successfully."
            );
        }

        /*
         * Failed rows
         */
        if ($failed > 0) {

            $message =
                "{$failed} row(s) could not be imported.";

            if (!empty($errors)) {

                $message .= '<br>' .
                    implode('<br>', $errors);
            }

            $this->Flash->warning($message);
        }

        /*
         * No data
         */
        if ($success === 0 && $failed === 0) {

            $this->Flash->warning(
                'No farm records were found in the Excel file.'
            );
        }

    } catch (\Exception $e) {

        $this->Flash->error(
            'Unable to read the Excel file: ' .
            $e->getMessage()
        );
    }

    return $this->redirect([
        'action' => 'index'
    ]);
}

}
