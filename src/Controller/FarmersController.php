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
        return $this->redirect(['action' => 'index']);
    }

    $file = $this->request->getData('excel_file');

    /*
     * =========================================================
     * VALIDATE FILE
     * =========================================================
     */

    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
        $this->Flash->error(
            'Please select a valid Excel file.'
        );

        return $this->redirect(['action' => 'index']);
    }


    $extension = strtolower(
        pathinfo(
            $file->getClientFilename(),
            PATHINFO_EXTENSION
        )
    );


    if (!in_array($extension, ['xlsx', 'xls'], true)) {

        $this->Flash->error(
            'Only Excel files (.xlsx or .xls) are allowed.'
        );

        return $this->redirect(['action' => 'index']);
    }


    /*
     * =========================================================
     * COUNTERS
     * =========================================================
     */

    $success = 0;
    $failed = 0;
    $duplicate = 0;

    /*
     * Store hashes of rows already processed in this Excel file.
     *
     * This prevents the same row from appearing twice in
     * the same Excel file.
     */

    $uploadedRows = [];


    try {

        /*
         * =====================================================
         * LOAD EXCEL
         * =====================================================
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
         * =====================================================
         * PROCESS EACH ROW
         * =====================================================
         */

        foreach ($rows as $index => $row) {

            /*
             * Skip header
             */

            if ($index == 1) {
                continue;
            }


            /*
             * Skip completely empty rows
             */

            if (
                empty(trim((string)($row['A'] ?? ''))) &&
                empty(trim((string)($row['B'] ?? ''))) &&
                empty(trim((string)($row['C'] ?? '')))
            ) {
                continue;
            }


            /*
             * =================================================
             * GET BASIC DATA
             * =================================================
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
             * =================================================
             * PROCESS BIRTHDATE
             * =================================================
             */

            $birthdate = null;


            if (!empty($row['E'])) {

                $birthdateValue = $row['E'];


                /*
                 * Excel numeric date
                 */

                if (is_numeric($birthdateValue)) {

                    $dateTime = Date::excelToDateTimeObject(
                        (float)$birthdateValue
                    );

                    $birthdate =
                        FrozenDate::createFromMutable(
                            $dateTime
                        );

                } else {

                    /*
                     * Normal text date
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
                     * Try normal DateTime parsing
                     */

                    if ($dateTime === false) {

                        try {

                            $dateTime =
                                new \DateTime($dateValue);

                        } catch (\Throwable $e) {

                            $dateTime = false;
                        }
                    }


                    /*
                     * Invalid date
                     */

                    if ($dateTime === false) {

                        throw new \Exception(
                            'Invalid birthdate: ' .
                            $dateValue
                        );
                    }


                    $birthdate =
                        FrozenDate::createFromMutable(
                            $dateTime
                        );
                }
            }


            /*
             * =================================================
             * CREATE A UNIQUE DATA SIGNATURE
             * =================================================
             *
             * This is based on the actual data.
             *
             * Therefore:
             *
             * SAME FILE + DIFFERENT DATA = UPLOAD
             *
             * DIFFERENT FILE + SAME DATA = SKIP
             */

            $birthdateString = '';

            if ($birthdate !== null) {
                $birthdateString =
                    $birthdate->format('Y-m-d');
            }


            $dataSignature = implode('|', [
                strtolower($farmerNo),
                strtolower($firstName),
                strtolower($lastName),
                strtolower($middleName),
                $birthdateString,
                strtolower($gender),
                strtolower($address),
                strtolower($contactNo)
            ]);


            /*
             * Create hash
             */

            $rowHash = hash(
                'sha256',
                $dataSignature
            );


            /*
             * =================================================
             * CHECK DUPLICATE INSIDE CURRENT EXCEL
             * =================================================
             */

            if (isset($uploadedRows[$rowHash])) {

                $duplicate++;

                continue;
            }


            /*
             * Remember this row
             */

            $uploadedRows[$rowHash] = true;


            /*
             * =================================================
             * CHECK DATABASE FOR EXISTING DATA
             * =================================================
             *
             * We compare ALL farmer information.
             *
             * The Excel filename is NOT checked.
             */

            $existingFarmers = $this->Farmers->find()
                ->where([
                    'farmer_no' => $farmerNo
                ])
                ->all();


            $alreadyExists = false;


            foreach ($existingFarmers as $existingFarmer) {

                /*
                 * Existing birthdate
                 */

                $existingBirthdate = '';

                if (!empty($existingFarmer->birthdate)) {

                    $existingBirthdate =
                        $existingFarmer->birthdate->format(
                            'Y-m-d'
                        );
                }


                /*
                 * Create signature for database record
                 */

                $existingSignature = implode('|', [
                    strtolower(
                        trim((string)$existingFarmer->farmer_no)
                    ),

                    strtolower(
                        trim((string)$existingFarmer->first_name)
                    ),

                    strtolower(
                        trim((string)$existingFarmer->last_name)
                    ),

                    strtolower(
                        trim((string)$existingFarmer->middle_name)
                    ),

                    $existingBirthdate,

                    strtolower(
                        trim((string)$existingFarmer->gender)
                    ),

                    strtolower(
                        trim((string)$existingFarmer->address)
                    ),

                    strtolower(
                        trim((string)$existingFarmer->contact_no)
                    )
                ]);


                $existingHash = hash(
                    'sha256',
                    $existingSignature
                );


                /*
                 * Exact same data found
                 */

                if ($existingHash === $rowHash) {

                    $alreadyExists = true;

                    break;
                }
            }


            /*
             * =================================================
             * SKIP DUPLICATE
             * =================================================
             */

            if ($alreadyExists) {

                $duplicate++;

                continue;
            }


            /*
             * =================================================
             * CREATE NEW FARMER
             * =================================================
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
             * =================================================
             * SAVE
             * =================================================
             */

            if ($this->Farmers->save($farmer)) {

                $success++;

            } else {

                $failed++;
            }
        }


        /*
         * =====================================================
         * DISPLAY RESULTS
         * =====================================================
         */

        /*
         * Nothing was imported because everything already exists
         */

        if (
            $success === 0 &&
            $duplicate > 0 &&
            $failed === 0
        ) {

            $this->Flash->warning(
                'The data inside the Excel file are already uploaded. ' .
                "{$duplicate} existing row(s) were skipped. " .
                'No duplicate data were uploaded.'
            );

        } else {

            /*
             * Some new data were uploaded
             */

            if ($success > 0) {

                $this->Flash->success(
                    "Excel import completed. " .
                    "{$success} new farmer(s) imported."
                );
            }


            /*
             * Existing records were skipped
             */

            if ($duplicate > 0) {

                $this->Flash->warning(
                    "{$duplicate} row(s) were already uploaded " .
                    "and were skipped to prevent duplicate data."
                );
            }


            /*
             * Failed records
             */

            if ($failed > 0) {

                $this->Flash->error(
                    "{$failed} row(s) could not be imported."
                );
            }
        }


    } catch (\Throwable $e) {

        /*
         * =====================================================
         * ERROR
         * =====================================================
         */

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
