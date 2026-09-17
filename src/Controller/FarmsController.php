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
        $farmers = $this->Farmers->find('list', [
            'keyField' => 'id',
            'valueField' => 'farmer_no'
        ])
        ->order([
            'farmer_no' => 'ASC'
        ])
        ->toArray();

        $farm = $this->Farms->newEmptyEntity();

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            /*
            * Validate farmer_id before saving.
            * farms.farmer_id must reference farmers.id.
            */
            $farmerId = $data['farmer_id'] ?? null;

            if (empty($farmerId)) {

                $this->Flash->error(
                    'Please select a farmer.'
                );

            } else {

                $farmer = $this->Farmers->find()
                    ->where([
                        'Farmers.id' => $farmerId
                    ])
                    ->first();

                if (!$farmer) {

                    $this->Flash->error(
                        'The selected farmer does not exist.'
                    );

                } else {

                    // Farmer exists, so it is safe to save the farm.
                    $farm = $this->Farms->patchEntity(
                        $farm,
                        $data
                    );

                    if ($this->Farms->save($farm)) {

                        $this->Flash->success(
                            'Farm added successfully.'
                        );

                        return $this->redirect([
                            'action' => 'index'
                        ]);
                    }

                    // Display validation errors while debugging.
                    $errors = $farm->getErrors();

                    if (!empty($errors)) {
                        foreach ($errors as $field => $messages) {
                            foreach ($messages as $message) {
                                $this->Flash->error(
                                    ucfirst($field) . ': ' . $message
                                );
                            }
                        }
                    } else {
                        $this->Flash->error(
                            'The farm could not be saved.'
                        );
                    }
                }
            }
        }

        $this->set([
            'farm' => $farm,
            'farmers' => $farmers
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
                        'average_yield' => $farm->average_yield,
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
        $averageYield = $farm->average_yield;

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
                    'average_yield' => $averageYield,
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
     * Upload Excel method
     *
     * Imports farm records from Excel.
     *
     * @return \Cake\Http\Response
     */
    public function uploadExcel()
{
    /*
    |--------------------------------------------------------------------------
    | ONLY ALLOW POST
    |--------------------------------------------------------------------------
    */
    if (!$this->request->is('post')) {
        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET UPLOADED FILE
    |--------------------------------------------------------------------------
    */
    $file = $this->request->getData('excel_file');

    /*
    |--------------------------------------------------------------------------
    | VALIDATE FILE
    |--------------------------------------------------------------------------
    */
    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {

        $this->request->getSession()->write(
            'FarmExcelImportResult',
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
            'FarmExcelImportResult',
            [
                'type' => 'error',
                'title' => 'Invalid File',
                'success' => 0,
                'duplicate' => 0,
                'failed' => 0,
                'message' => 'Only Excel files (.xlsx or .xls) are allowed.'
            ]
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD FARMERS MODEL
    |--------------------------------------------------------------------------
    */
    $this->loadModel('Farmers');

    /*
    |--------------------------------------------------------------------------
    | COUNTERS
    |--------------------------------------------------------------------------
    */
    $success = 0;
    $duplicate = 0;
    $failed = 0;

    /*
    |--------------------------------------------------------------------------
    | TRACK DUPLICATES INSIDE EXCEL
    |--------------------------------------------------------------------------
    */
    $uploadedFarmNumbers = [];

    try {

        /*
        |--------------------------------------------------------------------------
        | LOAD EXCEL
        |--------------------------------------------------------------------------
        */
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
            $file->getStream()->getMetadata('uri')
        );

        $sheet = $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | CONVERT TO ARRAY
        |--------------------------------------------------------------------------
        */
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
            | EXCEL COLUMNS
            |--------------------------------------------------------------------------
            |
            | A = LGU RSBSA Number
            | B = FARM SIZE
            | C = LOCATION
            | D = AVERAGE YIELD
            |
            */

            $farmerNo = trim(
                (string)($row['A'] ?? '')
            );

            $farmSize = trim(
                (string)($row['B'] ?? '')
            );

            $location = trim(
                (string)($row['C'] ?? '')
            );

            $averageYield = trim(
                (string)($row['D'] ?? '')
            );

            /*
            |--------------------------------------------------------------------------
            | CHECK EMPTY ROW
            |--------------------------------------------------------------------------
            */
            if (
                $farmerNo === '' &&
                $farmSize === '' &&
                $location === '' &&
                $averageYield === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | REQUIRED FIELDS
            |--------------------------------------------------------------------------
            */
            if (
                $farmerNo === '' ||
                $farmSize === '' ||
                $location === ''
            ) {
                $failed++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | NORMALIZE RSBSA NUMBER
            |--------------------------------------------------------------------------
            */
            $normalizedFarmerNo = strtolower(
                preg_replace(
                    '/\s+/',
                    '',
                    $farmerNo
                )
            );

            /*
            |--------------------------------------------------------------------------
            | CHECK DUPLICATE INSIDE CURRENT EXCEL FILE
            |--------------------------------------------------------------------------
            */
            if (
                isset(
                    $uploadedFarmNumbers[$normalizedFarmerNo]
                )
            ) {
                $duplicate++;
                continue;
            }

            $uploadedFarmNumbers[$normalizedFarmerNo] = true;

            /*
            |--------------------------------------------------------------------------
            | FIND FARMER USING RSBSA NUMBER
            |--------------------------------------------------------------------------
            */
            $farmer = $this->Farmers
                ->find()
                ->where([
                    'Farmers.farmer_no' => $farmerNo
                ])
                ->first();

            /*
            |--------------------------------------------------------------------------
            | FARMER DOES NOT EXIST
            |--------------------------------------------------------------------------
            */
            if (!$farmer) {
                $failed++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK IF FARM ALREADY EXISTS
            |--------------------------------------------------------------------------
            |
            | If your database allows multiple farms for one farmer,
            | remove this duplicate check.
            |
            | This version considers farmer_no + farm details as duplicate.
            |
            */

            $existingFarm = $this->Farms
                ->find()
                ->where([
                    'Farms.farmer_id' => $farmer->id,
                    'Farms.farm_size' => (float)$farmSize,
                    'Farms.location' => $location
                ])
                ->first();

            if ($existingFarm) {
                $duplicate++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE FARM ENTITY
            |--------------------------------------------------------------------------
            */
            $farm = $this->Farms->newEmptyEntity();

            /*
            |--------------------------------------------------------------------------
            | ASSIGN DATA
            |--------------------------------------------------------------------------
            */
            $farm->farmer_id = $farmer->id;

            $farm->farm_size = is_numeric($farmSize)
                ? (float)$farmSize
                : null;

            $farm->location = $location;

            $farm->average_yield = (
                $averageYield !== '' &&
                is_numeric($averageYield)
            )
                ? (float)$averageYield
                : null;

            /*
            |--------------------------------------------------------------------------
            | SAVE
            |--------------------------------------------------------------------------
            */
            if ($this->Farms->save($farm)) {

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

            $message =
                "Upload completed successfully. " .
                "{$success} new farm record(s) were uploaded. " .
                "{$duplicate} farm record(s) were already uploaded " .
                "and were skipped to prevent duplicate records.";

            $resultType = 'success';
            $resultTitle = 'Upload Completed';

        } elseif (
            $success > 0 &&
            $duplicate === 0 &&
            $failed === 0
        ) {

            $message =
                "Upload completed successfully. " .
                "{$success} farm record(s) were uploaded.";

            $resultType = 'success';
            $resultTitle = 'Upload Successful';

        } elseif (
            $success === 0 &&
            $duplicate > 0 &&
            $failed === 0
        ) {

            $message =
                "The farm data are already uploaded. " .
                "{$duplicate} farm record(s) already exist in the system. " .
                "No duplicate records were created.";

            $resultType = 'warning';
            $resultTitle = 'Data Already Uploaded';

        } elseif (
            $success > 0 ||
            $duplicate > 0 ||
            $failed > 0
        ) {

            $message =
                "Upload completed with some issues. " .
                "{$success} new farm record(s) uploaded, " .
                "{$duplicate} already uploaded/skipped, " .
                "{$failed} row(s) failed.";

            $resultType = 'warning';
            $resultTitle = 'Upload Completed with Warnings';

        } else {

            $message =
                'No valid farm data were found in the Excel file.';

            $resultType = 'warning';
            $resultTitle = 'No Data Found';
        }

        /*
        |--------------------------------------------------------------------------
        | STORE RESULT
        |--------------------------------------------------------------------------
        */
        $this->request->getSession()->write(
            'FarmExcelImportResult',
            [
                'type' => $resultType,
                'title' => $resultTitle,
                'success' => $success,
                'duplicate' => $duplicate,
                'failed' => $failed,
                'message' => $message
            ]
        );

    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | EXCEL PROCESSING ERROR
        |--------------------------------------------------------------------------
        */
        $this->request->getSession()->write(
            'FarmExcelImportResult',
            [
                'type' => 'error',
                'title' => 'Excel Import Failed',
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
    | REDIRECT
    |--------------------------------------------------------------------------
    */
    return $this->redirect([
        'action' => 'index'
    ]);
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
        'AVERAGE YIELD (bags/ha)',
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
        'C' => 35,
        'D' => 25
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
        ->getStyle("A1:E{$lastTemplateRow}")
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
        $sheet->getStyle('A1:D1');

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
        ->getStyle("A2:E{$lastTemplateRow}")
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
        "A1:E{$lastTemplateRow}"
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
        'LOCATION',
        'AVERAGE YIELD (bags/ha)'
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

        /*
        |--------------------------------------------------------------------------
        | AVERAGE YIELD
        |--------------------------------------------------------------------------
        */
        $sheet->setCellValue(
            'D' . $rowNumber,
            $farm->average_yield ?? ''
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
        'C' => 35,
        'D' => 25
    ];

    foreach ($widths as $column => $width) {

        $sheet
            ->getColumnDimension($column)
            ->setWidth($width);
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER STYLE
    |--------------------------------------------------------------------------
    */
    $headerStyle =
        $sheet->getStyle('A1:D1');

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
    | DATA BORDERS
    |--------------------------------------------------------------------------
    */
    if ($lastRow >= 2) {

        $sheet
            ->getStyle("A2:D{$lastRow}")
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

        for ($row = 2; $row <= $lastRow; $row++) {

            $sheet
                ->getRowDimension($row)
                ->setRowHeight(22);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RSBSA AS TEXT
    |--------------------------------------------------------------------------
    */
    $sheet
        ->getStyle("A2:A{$lastRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    /*
    |--------------------------------------------------------------------------
    | VERTICAL ALIGNMENT
    |--------------------------------------------------------------------------
    */
    if ($lastRow >= 2) {

        $sheet
            ->getStyle("A2:D{$lastRow}")
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );
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
    */
    $sheet->setAutoFilter(
        "A1:D{$lastRow}"
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
    $filename =
        'farms_' .
        date('Y-m-d_H-i-s') .
        '.xlsx';

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    $tempFile =
        tempnam(
            sys_get_temp_dir(),
            'farms_'
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
}
