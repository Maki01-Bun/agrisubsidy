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
    
            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' => 'error',
                        'title' => 'Upload Failed',
                        'success' => 0,
                        'duplicate' => 0,
                        'failed' => 0,
                        'message' =>
                            'Please select a valid Excel file.'
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
    
            $this->request
                ->getSession()
                ->write(
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
        | TRACK FARMER NUMBERS INSIDE EXCEL
        |--------------------------------------------------------------------------
        */
    
        $uploadedFarmerNumbers = [];
    
    
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
    
    
            /*
            |--------------------------------------------------------------------------
            | CONVERT SHEET TO ARRAY
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
                | READ EXCEL COLUMNS
                |--------------------------------------------------------------------------
                |
                | A = LGU RSBSA Number
                | B = FIRST NAME
                | C = LAST NAME
                | D = MNAME
                | E = SUFFIX
                | F = BIRTHDATE
                | G = GENDER
                | H = ADDRESS
                | I = CONTACT NUMBER
                |
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
    
                $suffix = trim(
                    (string)($row['E'] ?? '')
                );
    
                $birthdateValue = $row['F'] ?? '';
    
                $gender = trim(
                    (string)($row['G'] ?? '')
                );
    
                $address = trim(
                    (string)($row['H'] ?? '')
                );
    
                $contactNo = trim(
                    (string)($row['I'] ?? '')
                );
    
    
                /*
                |--------------------------------------------------------------------------
                | CHECK EMPTY ROW
                |--------------------------------------------------------------------------
                */
    
                if (
                    $farmerNo === '' &&
                    $firstName === '' &&
                    $lastName === '' &&
                    $middleName === '' &&
                    $suffix === '' &&
                    trim((string)$birthdateValue) === '' &&
                    $gender === '' &&
                    $address === '' &&
                    $contactNo === ''
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
                    $firstName === '' ||
                    $lastName === ''
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
                        $uploadedFarmerNumbers[
                            $normalizedFarmerNo
                        ]
                    )
                ) {
    
                    $duplicate++;
    
                    continue;
                }
    
    
                /*
                | Remember RSBSA number
                */
    
                $uploadedFarmerNumbers[
                    $normalizedFarmerNo
                ] = true;
    
    
                /*
                |--------------------------------------------------------------------------
                | PROCESS BIRTHDATE
                |--------------------------------------------------------------------------
                */
    
                $birthdate = null;
    
    
                if (
                    $birthdateValue !== null &&
                    trim((string)$birthdateValue) !== ''
                ) {
    
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
                | NORMALIZE CONTACT NUMBER
                |--------------------------------------------------------------------------
                */
    
                if (
                    $contactNo !== '' &&
                    strlen($contactNo) === 10 &&
                    $contactNo[0] === '9'
                ) {
    
                    $contactNo =
                        '0' . $contactNo;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | CHECK DATABASE
                |--------------------------------------------------------------------------
                |
                | LGU RSBSA Number is the unique identifier.
                |
                */
    
                $existingFarmer =
                    $this->Farmers
                        ->find()
                        ->where([
                            'farmer_no' => $farmerNo
                        ])
                        ->first();
    
    
                /*
                |--------------------------------------------------------------------------
                | ALREADY UPLOADED
                |--------------------------------------------------------------------------
                */
    
                if ($existingFarmer) {
    
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
    
    
                /*
                |--------------------------------------------------------------------------
                | ASSIGN DATA
                |--------------------------------------------------------------------------
                */
    
                $farmer->farmer_no =
                    $farmerNo;
    
                $farmer->first_name =
                    $firstName;
    
                $farmer->last_name =
                    $lastName;
    
                $farmer->middle_name =
                    $middleName;
    
                $farmer->suffix =
                    $suffix;
    
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
                | SAVE
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
                |--------------------------------------------------------------------------
                | NEW + ALREADY UPLOADED
                |--------------------------------------------------------------------------
                */
    
                $message =
                    "Upload completed successfully. " .
                    "{$success} new beneficiary(s) were uploaded. " .
                    "{$duplicate} beneficiary(s) were already uploaded " .
                    "and were skipped to prevent duplicate records.";
    
                $resultType = 'success';
    
                $resultTitle =
                    'Upload Completed';
    
    
            } elseif (
                $success > 0 &&
                $duplicate === 0 &&
                $failed === 0
            ) {
    
                /*
                |--------------------------------------------------------------------------
                | ALL NEW
                |--------------------------------------------------------------------------
                */
    
                $message =
                    "Upload completed successfully. " .
                    "{$success} beneficiary(s) were uploaded.";
    
                $resultType = 'success';
    
                $resultTitle =
                    'Upload Successful';
    
    
            } elseif (
                $success === 0 &&
                $duplicate > 0 &&
                $failed === 0
            ) {
    
                /*
                |--------------------------------------------------------------------------
                | ALL ALREADY UPLOADED
                |--------------------------------------------------------------------------
                */
    
                $message =
                    "The beneficiary data are already uploaded. " .
                    "{$duplicate} beneficiary(s) already exist in the system. " .
                    "No duplicate records were created.";
    
                $resultType = 'warning';
    
                $resultTitle =
                    'Data Already Uploaded';
    
    
            } elseif (
                $success > 0 ||
                $duplicate > 0 ||
                $failed > 0
            ) {
    
                /*
                |--------------------------------------------------------------------------
                | MIXED RESULT
                |--------------------------------------------------------------------------
                */
    
                $message =
                    "Upload completed with some issues. " .
                    "{$success} new beneficiary(s) uploaded, " .
                    "{$duplicate} already uploaded/skipped, " .
                    "{$failed} row(s) failed.";
    
                $resultType = 'warning';
    
                $resultTitle =
                    'Upload Completed with Warnings';
    
    
            } else {
    
                /*
                |--------------------------------------------------------------------------
                | NO DATA
                |--------------------------------------------------------------------------
                */
    
                $message =
                    'No valid beneficiary data were found in the Excel file.';
    
                $resultType = 'warning';
    
                $resultTitle =
                    'No Data Found';
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | STORE RESULT IN SESSION
            |--------------------------------------------------------------------------
            */
    
            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            $resultType,
    
                        'title' =>
                            $resultTitle,
    
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
            | EXCEL PROCESSING ERROR
            |--------------------------------------------------------------------------
            */
    
            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'error',
    
                        'title' =>
                            'Excel Import Failed',
    
                        'success' =>
                            0,
    
                        'duplicate' =>
                            0,
    
                        'failed' =>
                            0,
    
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
        
       /**
        * Download blank Excel template for Farmers import.
        */
    public function downloadExcelTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Farmers');

        /*
        |--------------------------------------------------------------------------
        | HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [
            'LGU RSBSA Number',
            'FIRST NAME',
            'LAST NAME',
            'MNAME',
            'SUFFIX',
            'BIRTHDATE',
            'GENDER',
            'ADDRESS',
            'CONTACT NUMBER'
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
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 35,
            'I' => 20
        ];

        foreach ($widths as $column => $width) {

            $sheet
                ->getColumnDimension($column)
                ->setWidth($width);
        }

        /*
        |--------------------------------------------------------------------------
        | TEMPLATE ROWS
        |--------------------------------------------------------------------------
        */

        $lastTemplateRow = 11;

        /*
        |--------------------------------------------------------------------------
        | BORDERS
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle("A1:I{$lastTemplateRow}")
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

        $headerStyle = $sheet->getStyle('A1:I1');

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
        | KEEP RSBSA AND CONTACT NUMBER AS TEXT
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle("A2:A{$lastTemplateRow}")
            ->getNumberFormat()
            ->setFormatCode('@');

        $sheet
            ->getStyle("I2:I{$lastTemplateRow}")
            ->getNumberFormat()
            ->setFormatCode('@');

        /*
        |--------------------------------------------------------------------------
        | ALIGNMENT
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle("A2:I{$lastTemplateRow}")
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
            "A1:I{$lastTemplateRow}"
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

        $filename = 'farmers_import_template.xlsx';

        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );

        $tempFile =
            tempnam(
                sys_get_temp_dir(),
                'farmer_template_'
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


    /**
     * Download all Farmers as Excel.
     */
    public function downloadFarmersExcel()
    {
        /*
        |--------------------------------------------------------------------------
        | GET ALL FARMERS
        |--------------------------------------------------------------------------
        */

        $farmers = $this->Farmers
            ->find()
            ->order([
                'Farmers.farmer_no' => 'ASC'
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

        $sheet->setTitle('Beneficiaries');

        /*
        |--------------------------------------------------------------------------
        | HEADERS
        |--------------------------------------------------------------------------
        */

        $headers = [
            'LGU RSBSA Number',
            'FIRST NAME',
            'LAST NAME',
            'MNAME',
            'SUFFIX',
            'BIRTHDATE',
            'GENDER',
            'ADDRESS',
            'CONTACT NUMBER'
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
        | INSERT FARMER DATA
        |--------------------------------------------------------------------------
        */

        $rowNumber = 2;

        foreach ($farmers as $farmer) {

            /*
            | RSBSA NUMBER
            */

            $farmerNo = trim(
                (string)($farmer->farmer_no ?? '')
            );

            $sheet->setCellValueExplicit(
                'A' . $rowNumber,
                $farmerNo,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            /*
            | FIRST NAME
            */

            $sheet->setCellValue(
                'B' . $rowNumber,
                $farmer->first_name ?? ''
            );

            /*
            | LAST NAME
            */

            $sheet->setCellValue(
                'C' . $rowNumber,
                $farmer->last_name ?? ''
            );

            /*
            | MIDDLE NAME
            */

            $sheet->setCellValue(
                'D' . $rowNumber,
                $farmer->middle_name ?? ''
            );

            /*
            | SUFFIX
            */

            $sheet->setCellValue(
                'E' . $rowNumber,
                $farmer->suffix ?? ''
            );

            /*
            | BIRTHDATE
            */

            if (!empty($farmer->birthdate)) {

                if ($farmer->birthdate instanceof \DateTimeInterface) {

                    $birthdate =
                        $farmer->birthdate->format('m/d/Y');

                } else {

                    $birthdate =
                        date(
                            'm/d/Y',
                            strtotime((string)$farmer->birthdate)
                        );
                }

                $sheet->setCellValue(
                    'F' . $rowNumber,
                    $birthdate
                );

            } else {

                $sheet->setCellValue(
                    'F' . $rowNumber,
                    ''
                );
            }

            /*
            | GENDER
            */

            $sheet->setCellValue(
                'G' . $rowNumber,
                $farmer->gender ?? ''
            );

            /*
            | ADDRESS
            */

            $sheet->setCellValue(
                'H' . $rowNumber,
                $farmer->address ?? ''
            );

            /*
            | CONTACT NUMBER
            */

            $contactNo = trim(
                (string)($farmer->contact_no ?? '')
            );

            if (
                $contactNo !== '' &&
                strlen($contactNo) === 10 &&
                $contactNo[0] === '9'
            ) {
                $contactNo = '0' . $contactNo;
            }

            $sheet->setCellValueExplicit(
                'I' . $rowNumber,
                $contactNo,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
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
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 35,
            'I' => 20
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
            $sheet->getStyle('A1:I1');

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
        | BORDERS
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle("A1:I{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
            ->getColor()
            ->setARGB('D9D9D9');

        /*
        |--------------------------------------------------------------------------
        | TEXT FORMAT
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle("A2:A{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('@');

        $sheet
            ->getStyle("I2:I{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('@');

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
            "A1:I{$lastRow}"
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
            'farmers_' .
            date('Y-m-d_H-i-s') .
            '.xlsx';

        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );

        $tempFile =
            tempnam(
                sys_get_temp_dir(),
                'farmers_'
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
