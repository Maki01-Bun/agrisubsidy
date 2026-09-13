<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenDate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            return $this->redirect([
                'action' => 'index'
            ]);
        }

        $file = $this->request->getData('excel_file');

        /*
        |--------------------------------------------------------------------------
        | VALIDATE FILE
        |--------------------------------------------------------------------------
        */

        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {

            $this->request->getSession()->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'title' => 'Upload Failed',
                    'success' => 0,
                    'duplicate' => 0,
                    'failed' => 0,
                    'message' => 'Please select a valid Excel file.'
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK FILE EXTENSION
        |--------------------------------------------------------------------------
        */

        $extension = strtolower(
            pathinfo(
                $file->getClientFilename(),
                PATHINFO_EXTENSION
            )
        );


        if (!in_array($extension, ['xlsx', 'xls'], true)) {

            $this->request->getSession()->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'title' => 'Invalid File',
                    'success' => 0,
                    'duplicate' => 0,
                    'failed' => 0,
                    'message' =>
                        'Only Excel files (.xlsx or .xls) are allowed.'
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | COUNTERS
        |--------------------------------------------------------------------------
        */

        $success = 0;
        $failed = 0;
        $duplicate = 0;


        /*
        |--------------------------------------------------------------------------
        | STORE ROW HASHES
        |--------------------------------------------------------------------------
        |
        | Used to detect duplicate rows inside the SAME Excel file.
        |
        */

        $uploadedRows = [];


        try {

            /*
            |--------------------------------------------------------------------------
            | LOAD EXCEL FILE
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | PROCESS EACH ROW
            |--------------------------------------------------------------------------
            */

            foreach ($rows as $index => $row) {

                /*
                |--------------------------------------------------------------------------
                | SKIP HEADER
                |--------------------------------------------------------------------------
                */

                if ($index == 1) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | SKIP COMPLETELY EMPTY ROWS
                |--------------------------------------------------------------------------
                */

                if (
                    empty(trim((string)($row['A'] ?? ''))) &&
                    empty(trim((string)($row['B'] ?? ''))) &&
                    empty(trim((string)($row['C'] ?? ''))) &&
                    empty(trim((string)($row['D'] ?? ''))) &&
                    empty(trim((string)($row['E'] ?? ''))) &&
                    empty(trim((string)($row['F'] ?? ''))) &&
                    empty(trim((string)($row['G'] ?? ''))) &&
                    empty(trim((string)($row['H'] ?? '')))
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | GET FARMER DATA
                |--------------------------------------------------------------------------
                */

                $farmerNo = trim(
                    (string)($row['A'] ?? '')
                );


                $firstName = trim(
                    (string)($row['B'] ?? '')
                );


                $lastName = trim(
                    (string)($row['C'] ?? '')
                );


                $middleName = trim(
                    (string)($row['D'] ?? '')
                );


                $gender = trim(
                    (string)($row['F'] ?? '')
                );


                $address = trim(
                    (string)($row['G'] ?? '')
                );


                $contactNo = trim(
                    (string)($row['H'] ?? '')
                );


                /*
                |--------------------------------------------------------------------------
                | VALIDATE FARMER NUMBER
                |--------------------------------------------------------------------------
                */

                if ($farmerNo === '') {

                    $failed++;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | PROCESS BIRTHDATE
                |--------------------------------------------------------------------------
                */

                $birthdate = null;


                if (
                    isset($row['E']) &&
                    trim((string)$row['E']) !== ''
                ) {

                    $birthdateValue = $row['E'];


                    /*
                    |--------------------------------------------------------------------------
                    | EXCEL NUMERIC DATE
                    |--------------------------------------------------------------------------
                    */

                    if (is_numeric($birthdateValue)) {

                        try {

                            $dateTime =
                                Date::excelToDateTimeObject(
                                    (float)$birthdateValue
                                );


                            $birthdate =
                                FrozenDate::createFromMutable(
                                    $dateTime
                                );

                        } catch (\Throwable $e) {

                            $failed++;

                            continue;
                        }

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | TEXT DATE
                        |--------------------------------------------------------------------------
                        */

                        $dateValue = trim(
                            (string)$birthdateValue
                        );


                        $dateTime = false;


                        $formats = [
                            'Y-m-d',
                            'm/d/Y',
                            'd/m/Y',
                            'm-d-Y',
                            'd-m-Y',
                            'Y/m/d',
                            'F j, Y',
                            'M j, Y'
                        ];


                        foreach ($formats as $format) {

                            $dateTime =
                                \DateTime::createFromFormat(
                                    $format,
                                    $dateValue
                                );


                            if ($dateTime !== false) {
                                break;
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | FALLBACK DATE PARSING
                        |--------------------------------------------------------------------------
                        */

                        if ($dateTime === false) {

                            try {

                                $dateTime =
                                    new \DateTime(
                                        $dateValue
                                    );

                            } catch (\Throwable $e) {

                                $dateTime = false;
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | INVALID DATE
                        |--------------------------------------------------------------------------
                        */

                        if ($dateTime === false) {

                            $failed++;

                            continue;
                        }


                        $birthdate =
                            FrozenDate::createFromMutable(
                                $dateTime
                            );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | CREATE BIRTHDATE STRING
                |--------------------------------------------------------------------------
                */

                $birthdateString = '';


                if ($birthdate !== null) {

                    $birthdateString =
                        $birthdate->format('Y-m-d');
                }


                /*
                |--------------------------------------------------------------------------
                | CREATE DATA SIGNATURE
                |--------------------------------------------------------------------------
                |
                | Filename is NOT included.
                |
                | Only actual farmer data are compared.
                |
                */

                $dataSignature = implode('|', [

                    strtolower(
                        trim($farmerNo)
                    ),

                    strtolower(
                        trim($firstName)
                    ),

                    strtolower(
                        trim($lastName)
                    ),

                    strtolower(
                        trim($middleName)
                    ),

                    $birthdateString,

                    strtolower(
                        trim($gender)
                    ),

                    strtolower(
                        trim($address)
                    ),

                    strtolower(
                        trim($contactNo)
                    )
                ]);


                /*
                |--------------------------------------------------------------------------
                | CREATE HASH
                |--------------------------------------------------------------------------
                */

                $rowHash = hash(
                    'sha256',
                    $dataSignature
                );


                /*
                |--------------------------------------------------------------------------
                | CHECK DUPLICATE INSIDE EXCEL
                |--------------------------------------------------------------------------
                */

                if (isset($uploadedRows[$rowHash])) {

                    $duplicate++;

                    continue;
                }


                /*
                | Remember row
                */

                $uploadedRows[$rowHash] = true;


                /*
                |--------------------------------------------------------------------------
                | CHECK DATABASE
                |--------------------------------------------------------------------------
                |
                | First search using farmer_no.
                |
                */

                $existingFarmers =
                    $this->Farmers
                        ->find()
                        ->where([
                            'farmer_no' => $farmerNo
                        ])
                        ->all();


                $alreadyExists = false;


                /*
                |--------------------------------------------------------------------------
                | COMPARE EXISTING FARMERS
                |--------------------------------------------------------------------------
                */

                foreach (
                    $existingFarmers
                    as $existingFarmer
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | EXISTING BIRTHDATE
                    |--------------------------------------------------------------------------
                    */

                    $existingBirthdate = '';


                    if (!empty($existingFarmer->birthdate)) {

                        $existingBirthdate =
                            $existingFarmer
                                ->birthdate
                                ->format('Y-m-d');
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | EXISTING DATA SIGNATURE
                    |--------------------------------------------------------------------------
                    */

                    $existingSignature = implode('|', [

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->farmer_no
                            )
                        ),

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->first_name
                            )
                        ),

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->last_name
                            )
                        ),

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->middle_name
                            )
                        ),

                        $existingBirthdate,

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->gender
                            )
                        ),

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->address
                            )
                        ),

                        strtolower(
                            trim(
                                (string)
                                $existingFarmer->contact_no
                            )
                        )
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | EXISTING HASH
                    |--------------------------------------------------------------------------
                    */

                    $existingHash = hash(
                        'sha256',
                        $existingSignature
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | EXACT MATCH FOUND
                    |--------------------------------------------------------------------------
                    */

                    if ($existingHash === $rowHash) {

                        $alreadyExists = true;

                        break;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | SKIP DUPLICATE
                |--------------------------------------------------------------------------
                */

                if ($alreadyExists) {

                    $duplicate++;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | CREATE FARMER ENTITY
                |--------------------------------------------------------------------------
                */

                $farmer =
                    $this->Farmers->newEmptyEntity();


                $farmer->farmer_no =
                    $farmerNo;


                $farmer->first_name =
                    $firstName;


                $farmer->last_name =
                    $lastName;


                $farmer->middle_name =
                    $middleName;


                $farmer->birthdate =
                    $birthdate;


                $farmer->gender =
                    $gender;


                $farmer->address =
                    $address;


                $farmer->contact_no =
                    $contactNo;


                /*
                |--------------------------------------------------------------------------
                | SAVE FARMER
                |--------------------------------------------------------------------------
                */

                if (
                    $this->Farmers->save(
                        $farmer
                    )
                ) {

                    $success++;

                } else {

                    $failed++;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | DETERMINE RESULT
            |--------------------------------------------------------------------------
            */

            if (
                $success > 0 &&
                $duplicate > 0 &&
                $failed === 0
            ) {

                /*
                | New records + duplicates
                */

                $message =
                    "{$success} new farmer(s) were imported successfully. " .
                    "{$duplicate} row(s) were already uploaded and were skipped " .
                    "to prevent duplicate data.";

                $resultType = 'success';

            } elseif (
                $success > 0 &&
                $duplicate === 0 &&
                $failed === 0
            ) {

                /*
                | All new
                */

                $message =
                    "Excel import completed successfully. " .
                    "{$success} new farmer(s) were imported.";

                $resultType = 'success';

            } elseif (
                $success === 0 &&
                $duplicate > 0 &&
                $failed === 0
            ) {

                /*
                | Everything was duplicate
                */

                $message =
                    "No new data were uploaded. " .
                    "{$duplicate} row(s) were already uploaded " .
                    "and were skipped to prevent duplicate data.";

                $resultType = 'warning';

            } elseif (
                $failed > 0
            ) {

                /*
                | Some failed
                */

                $message =
                    "{$success} new farmer(s) imported, " .
                    "{$duplicate} duplicate row(s) skipped, " .
                    "{$failed} row(s) failed.";

                $resultType = 'warning';

            } else {

                /*
                | Nothing found
                */

                $message =
                    'No valid farmer data were found in the Excel file.';

                $resultType = 'warning';
            }


            /*
            |--------------------------------------------------------------------------
            | STORE RESULT FOR MODAL
            |--------------------------------------------------------------------------
            */

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' => $resultType,

                        'title' =>
                            'Excel Import Completed',

                        'success' =>
                            $success,

                        'duplicate' =>
                            $duplicate,

                        'failed' =>
                            $failed,

                        'message' =>
                            $message
                    ]
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | EXCEL ERROR
            |--------------------------------------------------------------------------
            */

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' => 'error',

                        'title' =>
                            'Excel Import Failed',

                        'success' => 0,

                        'duplicate' => 0,

                        'failed' => 0,

                        'message' =>
                            'Unable to read the Excel file: ' .
                            $e->getMessage()
                    ]
                );
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO RECORDS INDEX
        |--------------------------------------------------------------------------
        */

        return $this->redirect([
            'action' => 'index'
        ]);
    }
    
}
