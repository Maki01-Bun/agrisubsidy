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
        $this->loadModel('Farmers');

        $user = $this->request->getSession()->read('Auth.User');

        if (!$user) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }
        $farms = $this->Farms->find()
            ->contain(['Farmers'])
            ->all();
        $farmerList = $this->Farmers->find()
            ->select([
                'id',
                'farmer_no'
            ])
            ->where([
                'farmer_no IS NOT' => null,
                'farmer_no !=' => ''
            ])
            ->order([
                'farmer_no' => 'ASC'
            ])
            ->all();

        $farmers = [];

        foreach ($farmerList as $farmer) {

            $farmers[$farmer->id] = $farmer->farmer_no;
        }
        $this->set([
            'farms' => $farms,
            'farmers' => $farmers
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void
     */
    public function view($id = null)
    {
        $farm = $this->Farms->get($id, [
            'contain' => ['Farmers'],
        ]);

        $this->set(compact('farm'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void
     */
   public function add()
{
    $this->loadModel('Farmers');

    /*
     * =========================================================
     * GET LOGGED-IN USER
     * =========================================================
     */

    $user = $this->request
        ->getSession()
        ->read('Auth.User');


    /*
     * =========================================================
     * CHECK LOGIN
     * =========================================================
     */

    if (
        empty($user) ||
        empty($user['id'])
    ) {
        $this->Flash->error(
            'You must be logged in to add a farm.'
        );

        return $this->redirect([
            'controller' => 'Pages',
            'action' => 'display',
            'home'
        ]);
    }


    $userId = (int)$user['id'];


    /*
     * =========================================================
     * GET USER ROLE
     * =========================================================
     */

    $role = strtolower(
        trim((string)($user['role'] ?? ''))
    );


    /*
     * =========================================================
     * FIND FARMER
     * =========================================================
     *
     * Used for farmer accounts.
     *
     */

    $farmer = $this->Farmers->find()
        ->where([
            'Farmers.user_id' => $userId
        ])
        ->first();


    /*
     * =========================================================
     * FARMER SIDE
     * =========================================================
     */

    if ($role === 'farmer') {

        /*
         * -----------------------------------------------------
         * CHECK FARMER RECORD
         * -----------------------------------------------------
         */

        if (!$farmer) {

            $this->Flash->error(
                'Your farmer record could not be found.'
            );

            return $this->redirect([
                'controller' => 'Profile',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * GET RSBSA NUMBER
         * -----------------------------------------------------
         */

        $rsbsaNumber = trim(
            (string)($farmer->farmer_no ?? '')
        );


        /*
         * -----------------------------------------------------
         * ONLY ACCEPT POST
         * -----------------------------------------------------
         */

        if (!$this->request->is('post')) {

            return $this->redirect([
                'controller' => 'Profile',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * GET FORM DATA
         * -----------------------------------------------------
         */

        $data = $this->request->getData();


        /*
         * -----------------------------------------------------
         * FORCE FARMER ID
         * -----------------------------------------------------
         *
         * Never trust farmer_id from the browser.
         *
         * The farmer_id is taken directly from the
         * authenticated farmer record.
         *
         */

        $data['farmer_id'] = (int)$farmer->id;


        /*
         * -----------------------------------------------------
         * VALIDATE LOCATION
         * -----------------------------------------------------
         */

        $location = trim(
            (string)($data['location'] ?? '')
        );


        if ($location === '') {

            $this->Flash->error(
                'Please enter the farm location.'
            );

            return $this->redirect([
                'controller' => 'Profile',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * VALIDATE FARM SIZE
         * -----------------------------------------------------
         */

        $farmSize = $data['farm_size'] ?? null;


        if (
            $farmSize === null ||
            $farmSize === '' ||
            !is_numeric($farmSize) ||
            (float)$farmSize <= 0
        ) {

            $this->Flash->error(
                'Please enter a farm size greater than 0 hectares.'
            );

            return $this->redirect([
                'controller' => 'Profile',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * NORMALIZE VALUES
         * -----------------------------------------------------
         */

        $data['location'] = $location;
        $data['farm_size'] = (float)$farmSize;


        /*
         * -----------------------------------------------------
         * CREATE FARM
         * -----------------------------------------------------
         */

        $farm = $this->Farms->newEmptyEntity();


        $farm = $this->Farms->patchEntity(
            $farm,
            $data
        );


        /*
         * -----------------------------------------------------
         * SAVE FARM
         * -----------------------------------------------------
         */

        if ($this->Farms->save($farm)) {

            $this->Flash->success(
                'Farm added successfully for LGU RSBSA No. '
                . $rsbsaNumber
                . '.'
            );


            /*
             * -------------------------------------------------
             * FARMER → PROFILE INDEX
             * -------------------------------------------------
             */

            return $this->redirect([
                'controller' => 'Profile',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * GET VALIDATION ERRORS
         * -----------------------------------------------------
         */

        $errors = $farm->getErrors();


        if (!empty($errors)) {

            foreach ($errors as $field => $messages) {

                foreach ($messages as $message) {

                    $this->Flash->error(
                        ucfirst((string)$field)
                        . ': '
                        . $message
                    );
                }
            }

        } else {

            $this->Flash->error(
                'The farm could not be saved. Please try again.'
            );
        }


        /*
         * -----------------------------------------------------
         * FARMER → PROFILE INDEX
         * -----------------------------------------------------
         */

        return $this->redirect([
            'controller' => 'Profile',
            'action' => 'index'
        ]);
    }


    /*
     * =========================================================
     * STAFF SIDE
     * =========================================================
     *
     * Staff selects the farmer from the Farms page.
     *
     * Expected POST fields:
     *
     * farmer_id
     * location
     * farm_size
     *
     */

    if ($role === 'staff') {

        /*
         * -----------------------------------------------------
         * ONLY ACCEPT POST
         * -----------------------------------------------------
         */

        if (!$this->request->is('post')) {

            return $this->redirect([
                'controller' => 'Farms',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * GET FORM DATA
         * -----------------------------------------------------
         */

        $data = $this->request->getData();


        /*
         * -----------------------------------------------------
         * GET SELECTED FARMER
         * -----------------------------------------------------
         */

        $farmerId = $data['farmer_id'] ?? null;


        if (
            empty($farmerId) ||
            !is_numeric($farmerId)
        ) {

            $this->Flash->error(
                'Please select a farmer.'
            );

            return $this->redirect([
                'controller' => 'Farms',
                'action' => 'index'
            ]);
        }


        $farmerId = (int)$farmerId;


        /*
         * -----------------------------------------------------
         * VERIFY FARMER EXISTS
         * -----------------------------------------------------
         */

        $selectedFarmer = $this->Farmers->find()
            ->where([
                'Farmers.id' => $farmerId
            ])
            ->first();


        if (!$selectedFarmer) {

            $this->Flash->error(
                'The selected farmer does not exist.'
            );

            return $this->redirect([
                'controller' => 'Farms',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * VALIDATE LOCATION
         * -----------------------------------------------------
         */

        $location = trim(
            (string)($data['location'] ?? '')
        );


        if ($location === '') {

            $this->Flash->error(
                'Please enter the farm location.'
            );

            return $this->redirect([
                'controller' => 'Farms',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * VALIDATE FARM SIZE
         * -----------------------------------------------------
         */

        $farmSize = $data['farm_size'] ?? null;


        if (
            $farmSize === null ||
            $farmSize === '' ||
            !is_numeric($farmSize) ||
            (float)$farmSize <= 0
        ) {

            $this->Flash->error(
                'Please enter a farm size greater than 0 hectares.'
            );

            return $this->redirect([
                'controller' => 'Farms',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * NORMALIZE STAFF DATA
         * -----------------------------------------------------
         */

        $data['farmer_id'] = $farmerId;
        $data['location'] = $location;
        $data['farm_size'] = (float)$farmSize;


        /*
         * -----------------------------------------------------
         * CREATE FARM
         * -----------------------------------------------------
         */

        $farm = $this->Farms->newEmptyEntity();


        $farm = $this->Farms->patchEntity(
            $farm,
            $data
        );


        /*
         * -----------------------------------------------------
         * SAVE FARM
         * -----------------------------------------------------
         */

        if ($this->Farms->save($farm)) {

            $this->Flash->success(
                'Farm added successfully for LGU RSBSA No. '
                . trim(
                    (string)(
                        $selectedFarmer->farmer_no ?? ''
                    )
                )
                . '.'
            );


            /*
             * -------------------------------------------------
             * STAFF → FARMS INDEX
             * -------------------------------------------------
             */

            return $this->redirect([
                'controller' => 'Farms',
                'action' => 'index'
            ]);
        }


        /*
         * -----------------------------------------------------
         * GET VALIDATION ERRORS
         * -----------------------------------------------------
         */

        $errors = $farm->getErrors();


        if (!empty($errors)) {

            foreach ($errors as $field => $messages) {

                foreach ($messages as $message) {

                    $this->Flash->error(
                        ucfirst((string)$field)
                        . ': '
                        . $message
                    );
                }
            }

        } else {

            $this->Flash->error(
                'The farm could not be saved. Please try again.'
            );
        }


        /*
         * -----------------------------------------------------
         * STAFF → FARMS INDEX
         * -----------------------------------------------------
         */

        return $this->redirect([
            'controller' => 'Farms',
            'action' => 'index'
        ]);
    }


    /*
     * =========================================================
     * OTHER ROLES
     * =========================================================
     */

    $this->Flash->error(
        'You are not authorized to add a farm.'
    );


    return $this->redirect([
        'controller' => 'Farms',
        'action' => 'index'
    ]);
}

    /**
     * Edit method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void
     */
    public function edit($id = null)
    {
        $farm = $this->Farms->get($id, [
            'contain' => ['Farmers'],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $farm = $this->Farms->patchEntity(
                $farm,
                $this->request->getData()
            );

            if ($this->Farms->save($farm)) {

                /*
                 * ==========================================
                 * AUDIT LOG - FARM UPDATED
                 * ==========================================
                 */
                $this->AuditLogger->logActivity(
                    'updated',
                    'Farm record updated',
                    $farm,
                    [
                        'farmer_id' => $farm->farmer_id,
                        'farm_size' => $farm->farm_size,
                        'location' => $farm->location,
                    ]
                );

                $this->Flash->success(
                    __('The farm has been saved.')
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            $this->Flash->error(
                __('The farm could not be saved. Please, try again.')
            );
        }

        $this->set(compact('farm'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void
     */
    public function delete($id = null)
    {
        $this->request->allowMethod([
            'post',
            'delete'
        ]);

        $farm = $this->Farms->get($id, [
            'contain' => ['Farmers'],
        ]);

        /*
         * Store information BEFORE deleting.
         *
         * We need the entity because after deletion
         * the record will no longer exist in the database.
         */
        $farmerId = $farm->farmer_id;
        $farmSize = $farm->farm_size;
        $location = $farm->location;

        if ($this->Farms->delete($farm)) {

            /*
             * ==========================================
             * AUDIT LOG - FARM DELETED
             * ==========================================
             */
            $this->AuditLogger->logActivity(
                'deleted',
                'Farm record deleted',
                $farm,
                [
                    'farmer_id' => $farmerId,
                    'farm_size' => $farmSize,
                    'location' => $location,
                ]
            );

            $this->Flash->success(
                __('The farm has been deleted.')
            );

        } else {

            $this->Flash->error(
                __('The farm could not be deleted. Please, try again.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }
  /**
     * Upload Farms Excel
     */
    public function uploadExcel()
    {
        /*
         * Only POST is allowed.
         */
        $this->request->allowMethod(['post']);


        /*
         * Disable normal view rendering.
         */
        $this->autoRender = false;


        /*
         * Load Farmers model.
         */
        $this->loadModel('Farmers');


        /*
         * Load Farms table.
         */
        $this->loadModel('Farms');


        /*
         * ========================================================
         * GET UPLOADED FILE
         * ========================================================
         */

        $uploadedFile =
            $this->request->getData('excel_file');


        /*
         * Result values.
         */

        $success =
            0;

        $failed =
            0;

        $errors =
            [];


        /*
         * ========================================================
         * CHECK FILE
         * ========================================================
         */

        if (
            empty($uploadedFile)
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            1,

                        'errors' => [
                            'No Excel file was uploaded.'
                        ]
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * CHECK UPLOAD ERROR
         * ========================================================
         */

        $uploadError =
            $uploadedFile->getError();


        if (
            $uploadError !== UPLOAD_ERR_OK
        ) {

            $errors[] =
                'The uploaded file could not be processed. Upload error code: '
                . $uploadError;


            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            1,

                        'errors' =>
                            $errors
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * FILE EXTENSION
         * ========================================================
         */

        $originalName =
            $uploadedFile->getClientFilename();


        $extension =
            strtolower(
                pathinfo(
                    $originalName,
                    PATHINFO_EXTENSION
                )
            );


        if (
            !in_array(
                $extension,
                [
                    'xlsx',
                    'xls'
                ],
                true
            )
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            1,

                        'errors' => [
                            'Invalid file type. Please upload an .xlsx or .xls file.'
                        ]
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * GET TEMPORARY FILE
         * ========================================================
         */

        $tmpFile =
            $uploadedFile->getStream()
                ->getMetadata('uri');


        if (
            empty($tmpFile) ||
            !file_exists($tmpFile)
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            1,

                        'errors' => [
                            'The uploaded Excel file could not be accessed.'
                        ]
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * LOAD EXCEL
         * ========================================================
         */

        try {

            $spreadsheet =
                IOFactory::load(
                    $tmpFile
                );

        } catch (
            \Throwable $e
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            1,

                        'errors' => [
                            'Unable to read the Excel file: '
                            . $e->getMessage()
                        ]
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * GET ACTIVE SHEET
         * ========================================================
         */

        $sheet =
            $spreadsheet
                ->getActiveSheet();


        $rows =
            $sheet
                ->toArray(
                    null,
                    true,
                    true,
                    true
                );


        /*
         * ========================================================
         * CHECK EMPTY FILE
         * ========================================================
         */

        if (
            count($rows) <= 1
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            0,

                        'errors' => [
                            'The Excel file does not contain any farm records.'
                        ]
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * EXPECTED HEADER
         * ========================================================
         *
         * A = Farmer Name
         * B = Farm Name
         * C = Farm Size
         * D = Location
         * E = Average Yield
         *
         */

        $header =
            $rows[1] ?? [];


        $headerA =
            strtolower(
                trim(
                    (string)(
                        $header['A']
                        ?? ''
                    )
                )
            );


        $headerB =
            strtolower(
                trim(
                    (string)(
                        $header['B']
                        ?? ''
                    )
                )
            );


        $headerC =
            strtolower(
                trim(
                    (string)(
                        $header['C']
                        ?? ''
                    )
                )
            );


        $headerD =
            strtolower(
                trim(
                    (string)(
                        $header['D']
                        ?? ''
                    )
                )
            );


        $headerE =
            strtolower(
                trim(
                    (string)(
                        $header['E']
                        ?? ''
                    )
                )
            );


        /*
         * Normalize headers.
         */

        $headerA =
            preg_replace(
                '/\s+/',
                ' ',
                $headerA
            );


        $headerB =
            preg_replace(
                '/\s+/',
                ' ',
                $headerB
            );


        $headerC =
            preg_replace(
                '/\s+/',
                ' ',
                $headerC
            );


        $headerD =
            preg_replace(
                '/\s+/',
                ' ',
                $headerD
            );


        $headerE =
            preg_replace(
                '/\s+/',
                ' ',
                $headerE
            );


        /*
         * ========================================================
         * HEADER VALIDATION
         * ========================================================
         */

        if (
            $headerA !== 'farmer name' ||
            $headerB !== 'farm name' ||
            $headerC !== 'farm size' ||
            $headerD !== 'location' ||
            $headerE !== 'average yield'
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',

                        'success' =>
                            0,

                        'failed' =>
                            1,

                        'errors' => [
                            'Invalid Excel format.',
                            'Required columns are:',
                            'Farmer Name',
                            'Farm Name',
                            'Farm Size',
                            'Location',
                            'Average Yield'
                        ]
                    ]
                );


            return $this->redirect(
                [
                    'action' =>
                        'index'
                ]
            );
        }


        /*
         * ========================================================
         * PROCESS DATA ROWS
         * ========================================================
         */

        foreach (
            $rows as $rowNumber => $row
        ) {

            /*
             * Skip header.
             */
            if (
                $rowNumber === 1
            ) {
                continue;
            }


            /*
             * Check completely empty row.
             */

            $farmerName =
                trim(
                    (string)(
                        $row['A']
                        ?? ''
                    )
                );


            $farmName =
                trim(
                    (string)(
                        $row['B']
                        ?? ''
                    )
                );


            $farmSize =
                trim(
                    (string)(
                        $row['C']
                        ?? ''
                    )
                );


            $location =
                trim(
                    (string)(
                        $row['D']
                        ?? ''
                    )
                );


            $averageYield =
                trim(
                    (string)(
                        $row['E']
                        ?? ''
                    )
                );


            if (
                $farmerName === '' &&
                $farmName === '' &&
                $farmSize === '' &&
                $location === '' &&
                $averageYield === ''
            ) {

                continue;
            }


            /*
             * ====================================================
             * VALIDATION
             * ====================================================
             */

            $rowErrors =
                [];


            if (
                $farmerName === ''
            ) {

                $rowErrors[] =
                    'Farmer Name is required.';

            }


            if (
                $farmName === ''
            ) {

                $rowErrors[] =
                    'Farm Name is required.';

            }


            if (
                $farmSize === ''
            ) {

                $rowErrors[] =
                    'Farm Size is required.';

            } elseif (
                !is_numeric($farmSize)
                ||
                (float)$farmSize <= 0
            ) {

                $rowErrors[] =
                    'Farm Size must be a number greater than 0.';

            }


            if (
                $location === ''
            ) {

                $rowErrors[] =
                    'Location is required.';

            }


            if (
                $averageYield === ''
            ) {

                $rowErrors[] =
                    'Average Yield is required.';

            } elseif (
                !is_numeric($averageYield)
                ||
                (float)$averageYield < 0
            ) {

                $rowErrors[] =
                    'Average Yield must be a number greater than or equal to 0.';

            }


            /*
             * ====================================================
             * FIND FARMER
             * ====================================================
             */

            $farmer =
                null;


            if (
                empty($rowErrors)
            ) {

                $nameParts =
                    preg_split(
                        '/\s+/',
                        $farmerName
                    );


                if (
                    count($nameParts) >= 2
                ) {

                    $firstName =
                        array_shift(
                            $nameParts
                        );


                    $lastName =
                        implode(
                            ' ',
                            $nameParts
                        );


                    $farmer =
                        $this->Farmers
                            ->find()
                            ->where([
                                'LOWER(Farmers.first_name)' =>
                                    strtolower(
                                        $firstName
                                    ),

                                'LOWER(Farmers.last_name)' =>
                                    strtolower(
                                        $lastName
                                    )
                            ])
                            ->first();

                }


                /*
                 * Try full_name if available.
                 */

                if (
                    !$farmer
                ) {

                    $farmer =
                        $this->Farmers
                            ->find()
                            ->where([
                                'LOWER(Farmers.full_name)' =>
                                    strtolower(
                                        $farmerName
                                    )
                            ])
                            ->first();

                }


                if (
                    !$farmer
                ) {

                    $rowErrors[] =
                        'Farmer "' .
                        $farmerName .
                        '" was not found.';
                }

            }


            /*
             * ====================================================
             * REPORT ROW ERROR
             * ====================================================
             */

            if (
                !empty($rowErrors)
            ) {

                $failed++;


                $errors[] =
                    'Row ' .
                    $rowNumber .
                    ': ' .
                    implode(
                        ' ',
                        $rowErrors
                    );


                continue;
            }


            /*
             * ====================================================
             * DUPLICATE CHECK
             * ====================================================
             */

            $existingFarm =
                $this->Farms
                    ->find()
                    ->where([
                        'farmer_id' =>
                            $farmer->id,

                        'LOWER(farm_name)' =>
                            strtolower(
                                $farmName
                            )
                    ])
                    ->first();


            if (
                $existingFarm
            ) {

                $failed++;


                $errors[] =
                    'Row ' .
                    $rowNumber .
                    ': Farm "' .
                    $farmName .
                    '" already exists for farmer "' .
                    $farmerName .
                    '".';


                continue;
            }


            /*
             * ====================================================
             * CREATE FARM ENTITY
             * ====================================================
             */

            $farm =
                $this->Farms
                    ->newEmptyEntity();


            $farm->farmer_id =
                $farmer->id;


            $farm->farm_name =
                $farmName;


            $farm->farm_size =
                (float)$farmSize;


            $farm->location =
                $location;


            $farm->average_yield =
                (float)$averageYield;


            /*
             * ====================================================
             * SAVE
             * ====================================================
             */

            if (
                $this->Farms
                    ->save($farm)
            ) {

                $success++;

            } else {

                $failed++;


                $errors[] =
                    'Row ' .
                    $rowNumber .
                    ': Unable to save farm "' .
                    $farmName .
                    '".';

            }

        }


        /*
         * ========================================================
         * RESULT TYPE
         * ========================================================
         */

        if (
            $success > 0 &&
            $failed === 0
        ) {

            $resultType =
                'success';

        } elseif (
            $success > 0 &&
            $failed > 0
        ) {

            $resultType =
                'partial';

        } else {

            $resultType =
                'error';
        }


        /*
         * ========================================================
         * SAVE RESULT TO SESSION
         * ========================================================
         */

        $this->request
            ->getSession()
            ->write(
                'ExcelImportResult',
                [
                    'type' =>
                        $resultType,

                    'success' =>
                        $success,

                    'failed' =>
                        $failed,

                    'errors' =>
                        $errors
                ]
            );


        /*
         * ========================================================
         * REDIRECT
         * ========================================================
         */

        return $this->redirect(
            [
                'action' =>
                    'index'
            ]
        );
    }
    public function downloadFarmExcelTemplate()
    {
        $spreadsheet =
            new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    
        $sheet =
            $spreadsheet->getActiveSheet();
    
        $sheet->setTitle('Farms');
    
        /*
        |--------------------------------------------------------------------------
        | HEADERS
        |--------------------------------------------------------------------------
        */
        $headers = [
            'LGU RSBSA Number',
            'FARM SIZE (ha)',
            'LOCATION',
        ];
    
        foreach ($headers as $index => $header) {
    
            $column =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $index + 1
                );
    
            $sheet->setCellValue(
                $column . '1',
                $header
            );
        }
    
        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTHS
        |--------------------------------------------------------------------------
        */
        $widths = [
            'A' => 28,
            'B' => 20,
            'C' => 35
        ];
    
        foreach ($widths as $column => $width) {
    
            $sheet
                ->getColumnDimension($column)
                ->setWidth($width);
        }
    
        /*
        |--------------------------------------------------------------------------
        | BLANK DATA ROWS
        |--------------------------------------------------------------------------
        */
        $lastTemplateRow = 11;
    
        /*
        |--------------------------------------------------------------------------
        | BORDERS
        |--------------------------------------------------------------------------
        */
        $sheet
            ->getStyle("A1:C{$lastTemplateRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
            ->getColor()
            ->setARGB('D9D9D9');
    
        /*
        |--------------------------------------------------------------------------
        | HEADER STYLE
        |--------------------------------------------------------------------------
        */
        $headerStyle =
            $sheet->getStyle('A1:C1');
    
        $headerStyle
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            )
            ->getStartColor()
            ->setARGB('FFFF00');
    
        $headerStyle
            ->getFont()
            ->setBold(true)
            ->setSize(11);
    
        $headerStyle
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            )
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            )
            ->setWrapText(true);
    
        /*
        |--------------------------------------------------------------------------
        | HEADER BORDER
        |--------------------------------------------------------------------------
        */
        $headerStyle
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
            ->getColor()
            ->setARGB('808080');
    
        /*
        |--------------------------------------------------------------------------
        | ROW HEIGHT
        |--------------------------------------------------------------------------
        */
        $sheet
            ->getRowDimension(1)
            ->setRowHeight(32);
    
        for ($row = 2; $row <= $lastTemplateRow; $row++) {
    
            $sheet
                ->getRowDimension($row)
                ->setRowHeight(22);
        }
    
        /*
        |--------------------------------------------------------------------------
        | RSBSA NUMBER AS TEXT
        |--------------------------------------------------------------------------
        */
        $sheet
            ->getStyle("A2:A{$lastTemplateRow}")
            ->getNumberFormat()
            ->setFormatCode('@');
    
        /*
        |--------------------------------------------------------------------------
        | VERTICAL ALIGNMENT
        |--------------------------------------------------------------------------
        */
        $sheet
            ->getStyle("A2:C{$lastTemplateRow}")
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );
    
        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */
        $sheet->freezePane('A2');
    
        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */
        $sheet->setAutoFilter(
            "A1:C{$lastTemplateRow}"
        );
    
        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */
        $sheet
            ->getPageSetup()
            ->setOrientation(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
            );
    
        $sheet
            ->getPageSetup()
            ->setPaperSize(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
            );
    
        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */
        $filename = 'farms_import_template.xlsx';
    
        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );
    
        $tempFile =
            tempnam(
                sys_get_temp_dir(),
                'farm_template_'
            );
    
        $writer->save($tempFile);
    
        return $this->response
            ->withType(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            )
            ->withDownload($filename)
            ->withFile(
                $tempFile,
                [
                    'download' => true,
                    'name' => $filename
                ]
            );
    }
    public function downloadFarmsExcel()
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL FARMS
    |--------------------------------------------------------------------------
    */

    $farms = $this->Farms
        ->find()
        ->contain([
            'Farmers'
        ])
        ->order([
            'Farms.id' => 'ASC'
        ])
        ->all();


    /*
    |--------------------------------------------------------------------------
    | CREATE SPREADSHEET
    |--------------------------------------------------------------------------
    */

    $spreadsheet =
        new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet =
        $spreadsheet->getActiveSheet();

    $sheet->setTitle('Farms');


    /*
    |--------------------------------------------------------------------------
    | HEADERS
    |--------------------------------------------------------------------------
    */

    $headers = [
        'LGU RSBSA Number',
        'FARM SIZE (ha)',
        'LOCATION'
    ];


    foreach ($headers as $index => $header) {

        $column =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INSERT FARM DATA
    |--------------------------------------------------------------------------
    */

    $rowNumber = 2;


    foreach ($farms as $farm) {

        /*
        |--------------------------------------------------------------------------
        | RSBSA NUMBER
        |--------------------------------------------------------------------------
        */

        $farmerNo = '';

        if (!empty($farm->farmer)) {

            $farmerNo = trim(
                (string)($farm->farmer->farmer_no ?? '')
            );
        }


        $sheet->setCellValueExplicit(
            'A' . $rowNumber,
            $farmerNo,
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );


        /*
        |--------------------------------------------------------------------------
        | FARM SIZE
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'B' . $rowNumber,
            $farm->farm_size ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'C' . $rowNumber,
            $farm->location ?? ''
        );


        $rowNumber++;
    }


    /*
    |--------------------------------------------------------------------------
    | LAST ROW
    |--------------------------------------------------------------------------
    */

    $lastRow = max(
        1,
        $rowNumber - 1
    );


    /*
    |--------------------------------------------------------------------------
    | COLUMN WIDTHS
    |--------------------------------------------------------------------------
    */

    $widths = [
        'A' => 28,
        'B' => 20,
        'C' => 35
    ];


    foreach ($widths as $column => $width) {

        $sheet
            ->getColumnDimension($column)
            ->setWidth($width);
    }


    /*
    |--------------------------------------------------------------------------
    | UNUSED COLUMN D
    |--------------------------------------------------------------------------
    |
    | The export only uses columns A-C.
    | Explicitly remove any fill/border from column D.
    |
    */

    $sheet
        ->getStyle("D1:D{$lastRow}")
        ->getFill()
        ->setFillType(
            \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE
        );


    $sheet
        ->getStyle("D1:D{$lastRow}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
        );


    /*
    |--------------------------------------------------------------------------
    | HEADER STYLE
    |--------------------------------------------------------------------------
    */

    $headerStyle =
        $sheet->getStyle('A1:C1');


    /*
    |--------------------------------------------------------------------------
    | HEADER BACKGROUND
    |--------------------------------------------------------------------------
    */

    $headerStyle
        ->getFill()
        ->setFillType(
            \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
        )
        ->getStartColor()
        ->setARGB('FFFFFF00');


    /*
    |--------------------------------------------------------------------------
    | HEADER FONT
    |--------------------------------------------------------------------------
    */

    $headerStyle
        ->getFont()
        ->setBold(true)
        ->setSize(11);


    /*
    |--------------------------------------------------------------------------
    | HEADER ALIGNMENT
    |--------------------------------------------------------------------------
    */

    $headerStyle
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        )
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        )
        ->setWrapText(true);


    /*
    |--------------------------------------------------------------------------
    | HEADER BORDER
    |--------------------------------------------------------------------------
    */

    $headerStyle
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        )
        ->getColor()
        ->setARGB('808080');


    /*
    |--------------------------------------------------------------------------
    | DATA BORDERS
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        $sheet
            ->getStyle("A2:C{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
            ->getColor()
            ->setARGB('D9D9D9');
    }


    /*
    |--------------------------------------------------------------------------
    | DATA ALIGNMENT
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        $sheet
            ->getStyle("A2:C{$lastRow}")
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RSBSA NUMBER AS TEXT
    |--------------------------------------------------------------------------
    |
    | Prevent Excel from changing:
    |
    | 02-31-35-003-000414
    |
    | into a date or another format.
    |
    */

    $sheet
        ->getStyle("A2:A{$lastRow}")
        ->getNumberFormat()
        ->setFormatCode('@');


    /*
    |--------------------------------------------------------------------------
    | HEADER ROW HEIGHT
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getRowDimension(1)
        ->setRowHeight(32);


    /*
    |--------------------------------------------------------------------------
    | DATA ROW HEIGHT
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        for (
            $row = 2;
            $row <= $lastRow;
            $row++
        ) {

            $sheet
                ->getRowDimension($row)
                ->setRowHeight(22);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FREEZE HEADER
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');


    /*
    |--------------------------------------------------------------------------
    | AUTO FILTER
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Only A-C are part of the filter.
    |
    */

    $sheet->setAutoFilter(
        "A1:C{$lastRow}"
    );


    /*
    |--------------------------------------------------------------------------
    | PAGE SETUP
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getPageSetup()
        ->setOrientation(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
        );


    $sheet
        ->getPageSetup()
        ->setPaperSize(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
        );


    /*
    |--------------------------------------------------------------------------
    | PRINT AREA
    |--------------------------------------------------------------------------
    |
    | Only A-C will be considered part of the printable export.
    |
    */

    $sheet->getPageSetup()->setPrintArea(
        "A1:C{$lastRow}"
    );


    /*
    |--------------------------------------------------------------------------
    | ACTIVE CELL
    |--------------------------------------------------------------------------
    */

    $sheet->setSelectedCell('A1');


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

    $filename =
        'farms_' .
        date('Y-m-d_H-i-s') .
        '.xlsx';


    /*
    |--------------------------------------------------------------------------
    | CREATE XLSX WRITER
    |--------------------------------------------------------------------------
    */

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );


    /*
    |--------------------------------------------------------------------------
    | TEMPORARY FILE
    |--------------------------------------------------------------------------
    */

    $tempFile =
        tempnam(
            sys_get_temp_dir(),
            'farms_'
        );


    /*
    |--------------------------------------------------------------------------
    | SAVE FILE
    |--------------------------------------------------------------------------
    */

    $writer->save(
        $tempFile
    );


    /*
    |--------------------------------------------------------------------------
    | RETURN DOWNLOAD
    |--------------------------------------------------------------------------
    */

    return $this->response
        ->withType(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        )
        ->withDownload(
            $filename
        )
        ->withFile(
            $tempFile,
            [
                'download' => true,
                'name' => $filename
            ]
        );
}
}
