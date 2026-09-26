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
     * ============================================================
     * ONLY ALLOW POST
     * ============================================================
     */
    if (!$this->request->is('post')) {

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * GET UPLOADED FILE
     * ============================================================
     */
    $file = $this->request->getData('excel_file');


    /*
     * ============================================================
     * CHECK FILE EXISTS
     * ============================================================
     */
    if (!$file) {

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
                        'Please select the official Farmer Excel file.'
                ]
            );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * CHECK UPLOAD ERROR
     * ============================================================
     */
    if ($file->getError() !== UPLOAD_ERR_OK) {

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
                        'The Farmer Excel file could not be uploaded. Please try again.'
                ]
            );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * GET ORIGINAL FILE NAME
     * ============================================================
     */
    $originalFilename =
        (string)$file->getClientFilename();


    /*
     * ============================================================
     * CHECK FILE EXTENSION
     * ============================================================
     */
    $extension = strtolower(
        pathinfo(
            $originalFilename,
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
                        'Invalid file type. Only the official Farmer Excel file (.xlsx or .xls) is allowed.'
                ]
            );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * EXPECTED FARMER EXCEL FORMAT
     *
     * SHEET NAME:
     * Farmers
     *
     * COLUMN A = LGU RSBSA Number
     * COLUMN B = FIRST NAME
     * COLUMN C = LAST NAME
     * COLUMN D = MNAME
     * COLUMN E = SUFFIX
     * COLUMN F = BIRTHDATE
     * COLUMN G = GENDER
     * COLUMN H = ADDRESS
     * COLUMN I = CONTACT NUMBER
     *
     * ============================================================
     */
    $expectedHeaders = [
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


    /*
     * ============================================================
     * HEADER NORMALIZATION FUNCTION
     * ============================================================
     */
    $normalizeHeader = function ($value): string {

        $value = (string)$value;

        /*
         * Remove UTF-8 BOM.
         */
        $value = preg_replace(
            '/^\xEF\xBB\xBF/',
            '',
            $value
        );


        /*
         * Remove leading/trailing spaces.
         */
        $value = trim($value);


        /*
         * Convert multiple spaces into one.
         */
        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        );


        /*
         * Compare without case sensitivity.
         */
        return strtolower($value);
    };


    /*
     * ============================================================
     * NORMALIZED EXPECTED HEADERS
     * ============================================================
     */
    $normalizedExpectedHeaders =
        array_map(
            $normalizeHeader,
            $expectedHeaders
        );


    /*
     * ============================================================
     * COUNTERS
     * ============================================================
     */
    $success = 0;
    $failed = 0;
    $duplicate = 0;


    /*
     * ============================================================
     * TRACK FARMER NUMBERS INSIDE EXCEL
     * ============================================================
     */
    $uploadedFarmerNumbers = [];


    try {

        /*
         * ========================================================
         * LOAD EXCEL
         * ========================================================
         */
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
            $file->getStream()->getMetadata('uri')
        );


        /*
         * ========================================================
         * GET ACTIVE SHEET
         * ========================================================
         */
        $sheet = $spreadsheet->getActiveSheet();


        /*
         * ========================================================
         * CHECK SHEET NAME
         *
         * Official Farmer template uses:
         * Farmers
         * ========================================================
         */
        if (
            strtolower(trim($sheet->getTitle())) !==
            'farmers'
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' => 'error',
                        'title' => 'Wrong Excel Format',
                        'success' => 0,
                        'duplicate' => 0,
                        'failed' => 0,
                        'message' =>
                            'Upload rejected. Please use the official Farmer Excel template. ' .
                            'The worksheet must be named "Farmers".'
                    ]
                );

            return $this->redirect([
                'action' => 'index'
            ]);
        }


        /*
         * ========================================================
         * READ HEADER ROW
         * ========================================================
         */
        $headerRow = $sheet->rangeToArray(
            'A1:I1',
            null,
            true,
            true,
            true
        );


        $headers =
            $headerRow[1] ?? [];


        /*
         * ========================================================
         * CHECK EXACT NINE HEADERS
         * ========================================================
         */
        $actualHeaders = [];


        for (
            $i = 1;
            $i <= 9;
            $i++
        ) {

            $column =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $i
                );


            $actualHeaders[] =
                $normalizeHeader(
                    $headers[$column] ?? ''
                );
        }


        /*
         * ========================================================
         * CHECK HEADER COUNT
         * ========================================================
         */
        if (
            count($actualHeaders) !==
            count($normalizedExpectedHeaders)
        ) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' => 'error',
                        'title' => 'Wrong Excel Format',
                        'success' => 0,
                        'duplicate' => 0,
                        'failed' => 0,
                        'message' =>
                            'Upload rejected. Only the official Farmer Excel format is accepted. ' .
                            'The file must contain exactly 9 columns.'
                    ]
                );

            return $this->redirect([
                'action' => 'index'
            ]);
        }


        /*
         * ========================================================
         * COMPARE EACH HEADER
         * ========================================================
         */
        $headerIsValid = true;
        $wrongColumn = null;
        $expectedHeader = null;
        $actualHeader = null;


        for (
            $i = 0;
            $i < count($normalizedExpectedHeaders);
            $i++
        ) {

            if (
                $actualHeaders[$i] !==
                $normalizedExpectedHeaders[$i]
            ) {

                $headerIsValid = false;

                $wrongColumn =
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                        $i + 1
                    );

                $expectedHeader =
                    $expectedHeaders[$i];

                $actualHeader =
                    $headers[$wrongColumn] ?? '';

                break;
            }
        }


        /*
         * ========================================================
         * REJECT WRONG HEADER
         * ========================================================
         */
        if (!$headerIsValid) {

            $this->request
                ->getSession()
                ->write(
                    'ExcelImportResult',
                    [
                        'type' => 'error',
                        'title' => 'Wrong Excel Format',
                        'success' => 0,
                        'duplicate' => 0,
                        'failed' => 0,
                        'message' =>
                            'Upload rejected. This is not the official Farmer Excel format. ' .
                            'Column ' .
                            $wrongColumn .
                            ' must be "' .
                            $expectedHeader .
                            '" but "' .
                            trim((string)$actualHeader) .
                            '" was found.'
                    ]
                );

            return $this->redirect([
                'action' => 'index'
            ]);
        }


        /*
         * ========================================================
         * REJECT EXTRA COLUMNS
         *
         * Example:
         *
         * A-I = correct Farmer columns
         * J = FARM SIZE
         *
         * This must NOT be accepted.
         * ========================================================
         */
        $highestColumnIndex =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );


        if ($highestColumnIndex > 9) {

            $extraColumns = [];


            for (
                $columnIndex = 10;
                $columnIndex <= $highestColumnIndex;
                $columnIndex++
            ) {

                $columnLetter =
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                        $columnIndex
                    );


                $highestRow =
                    $sheet->getHighestDataRow();


                $hasData = false;


                for (
                    $rowIndex = 1;
                    $rowIndex <= $highestRow;
                    $rowIndex++
                ) {

                    $cellValue =
                        $sheet
                            ->getCell(
                                $columnLetter . $rowIndex
                            )
                            ->getValue();


                    if (
                        $cellValue !== null &&
                        trim((string)$cellValue) !== ''
                    ) {

                        $hasData = true;

                        break;
                    }
                }


                if ($hasData) {

                    $extraColumns[] =
                        $columnLetter;
                }
            }


            if (!empty($extraColumns)) {

                $this->request
                    ->getSession()
                    ->write(
                        'ExcelImportResult',
                        [
                            'type' => 'error',
                            'title' => 'Wrong Excel Format',
                            'success' => 0,
                            'duplicate' => 0,
                            'failed' => 0,
                            'message' =>
                                'Upload rejected. The Farmer Excel file must contain only the required 9 columns. ' .
                                'Extra data was found in column(s): ' .
                                implode(', ', $extraColumns) .
                                '. Please use the official Farmer Excel template.'
                        ]
                    );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }
        }


        /*
         * ========================================================
         * READ ALL ROWS
         * ========================================================
         */
        $rows = $sheet->toArray(
            null,
            true,
            true,
            true
        );


        /*
         * ========================================================
         * PROCESS EACH ROW
         * ========================================================
         */
        foreach (
            $rows as $index => $row
        ) {

            /*
             * Skip header.
             */
            if ((int)$index === 1) {
                continue;
            }


            /*
             * ====================================================
             * READ FARMER COLUMNS
             *
             * A = LGU RSBSA Number
             * B = FIRST NAME
             * C = LAST NAME
             * D = MNAME
             * E = SUFFIX
             * F = BIRTHDATE
             * G = GENDER
             * H = ADDRESS
             * I = CONTACT NUMBER
             * ====================================================
             */

            $farmerNo =
                trim(
                    (string)($row['A'] ?? '')
                );


            $firstName =
                trim(
                    (string)($row['B'] ?? '')
                );


            $lastName =
                trim(
                    (string)($row['C'] ?? '')
                );


            $middleName =
                trim(
                    (string)($row['D'] ?? '')
                );


            $suffix =
                trim(
                    (string)($row['E'] ?? '')
                );


            $birthdateValue =
                $row['F'] ?? '';


            $gender =
                trim(
                    (string)($row['G'] ?? '')
                );


            $address =
                trim(
                    (string)($row['H'] ?? '')
                );


            $contactNo =
                trim(
                    (string)($row['I'] ?? '')
                );


            /*
             * ====================================================
             * SKIP COMPLETELY EMPTY ROW
             * ====================================================
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
             * ====================================================
             * REQUIRED FIELDS
             * ====================================================
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
             * ====================================================
             * NORMALIZE FARMER NUMBER
             * ====================================================
             */
            $normalizedFarmerNo =
                strtolower(
                    preg_replace(
                        '/\s+/',
                        '',
                        $farmerNo
                    )
                );


            /*
             * ====================================================
             * DUPLICATE INSIDE CURRENT EXCEL
             * ====================================================
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
             * ====================================================
             * REMEMBER FARMER NUMBER
             * ====================================================
             */
            $uploadedFarmerNumbers[
                $normalizedFarmerNo
            ] = true;


            /*
             * ====================================================
             * PROCESS BIRTHDATE
             * ====================================================
             */
            $birthdate = null;


            if (
                $birthdateValue !== null &&
                trim((string)$birthdateValue) !== ''
            ) {

                /*
                 * Excel numeric date.
                 */
                if (
                    is_numeric(
                        $birthdateValue
                    )
                ) {

                    try {

                        $dateTime =
                            \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                                (float)$birthdateValue
                            );


                        $birthdate =
                            \Cake\I18n\FrozenDate::createFromMutable(
                                $dateTime
                            );

                    } catch (\Throwable $e) {

                        $failed++;

                        continue;
                    }

                }

                /*
                 * Text date.
                 */
                else {

                    $dateValue =
                        trim(
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


                    foreach (
                        $formats as $format
                    ) {

                        $dateTime =
                            \DateTime::createFromFormat(
                                $format,
                                $dateValue
                            );


                        if (
                            $dateTime !== false
                        ) {

                            break;
                        }
                    }


                    /*
                     * Fallback.
                     */
                    if (
                        $dateTime === false
                    ) {

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
                     * Invalid date.
                     */
                    if (
                        $dateTime === false
                    ) {

                        $failed++;

                        continue;
                    }


                    $birthdate =
                        \Cake\I18n\FrozenDate::createFromMutable(
                            $dateTime
                        );
                }
            }


            /*
             * ====================================================
             * NORMALIZE CONTACT NUMBER
             * ====================================================
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
             * ====================================================
             * CHECK DATABASE DUPLICATE
             *
             * Farmer Number is the unique identifier.
             * ====================================================
             */
            $existingFarmer =
                $this->Farmers
                    ->find()
                    ->where([
                        'farmer_no' => $farmerNo
                    ])
                    ->first();


            if ($existingFarmer) {

                $duplicate++;

                continue;
            }


            /*
             * ====================================================
             * CREATE FARMER ENTITY
             * ====================================================
             */
            $farmer =
                $this->Farmers->newEmptyEntity();


            /*
             * ====================================================
             * ASSIGN DATA
             * ====================================================
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
             * ====================================================
             * SAVE
             * ====================================================
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
         * ============================================================
         * DETERMINE IMPORT RESULT
         * ============================================================
         */

        if (
            $success > 0 &&
            $duplicate > 0 &&
            $failed === 0
        ) {

            $message =
                "Upload completed successfully. " .
                "{$success} new farmer(s) were uploaded. " .
                "{$duplicate} farmer(s) already existed " .
                "and were skipped to prevent duplicates.";

            $resultType =
                'success';

            $resultTitle =
                'Upload Completed';

        }

        elseif (
            $success > 0 &&
            $duplicate === 0 &&
            $failed === 0
        ) {

            $message =
                "Upload completed successfully. " .
                "{$success} farmer(s) were uploaded.";

            $resultType =
                'success';

            $resultTitle =
                'Upload Successful';

        }

        elseif (
            $success === 0 &&
            $duplicate > 0 &&
            $failed === 0
        ) {

            $message =
                "No new farmers were uploaded. " .
                "{$duplicate} farmer(s) already exist in the system.";

            $resultType =
                'warning';

            $resultTitle =
                'Data Already Uploaded';

        }

        elseif (
            $success > 0 ||
            $duplicate > 0 ||
            $failed > 0
        ) {

            $message =
                "Upload completed with some issues. " .
                "{$success} new farmer(s) uploaded, " .
                "{$duplicate} duplicate(s) skipped, " .
                "{$failed} row(s) failed.";

            $resultType =
                'warning';

            $resultTitle =
                'Upload Completed with Warnings';

        }

        else {

            $message =
                'No valid farmer data were found in the Excel file.';

            $resultType =
                'warning';

            $resultTitle =
                'No Data Found';
        }


        /*
         * ============================================================
         * SAVE RESULT TO SESSION
         * ============================================================
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
         * ========================================================
         * EXCEL PROCESSING ERROR
         * ========================================================
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
                        'Unable to process the Farmer Excel file. ' .
                        $e->getMessage()
                ]
            );
    }


    /*
     * ============================================================
     * REDIRECT
     * ============================================================
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
     public function viewRecord()
{
    $this->request->allowMethod(['get']);

    $this->autoRender = false;

    try {

        // =========================================================
        // GET FARMER ID
        // =========================================================

        $farmerId = $this->request->getQuery('id');

        if (
            empty($farmerId) ||
            !is_numeric($farmerId)
        ) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'Invalid farmer ID.'
                    ])
                );
        }

        $farmerId = (int)$farmerId;


        // =========================================================
        // FIND FARMER
        // =========================================================

        $farmer = $this->Farmers
            ->find()
            ->where([
                'Farmers.id' => $farmerId
            ])
            ->first();

        if (!$farmer) {

            return $this->response
                ->withStatus(404)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'Farmer not found.'
                    ])
                );
        }


        // =========================================================
        // LOAD RECORDS
        // =========================================================

        $this->loadModel('Records');

        $records = $this->Records
            ->find()
            ->contain([
                'Schedules'
            ])
            ->where([
                'Records.farmer_id' => $farmerId
            ])
            ->order([
                'Records.id' => 'DESC'
            ])
            ->all();


        // =========================================================
        // BUILD RECORD DATA
        // =========================================================

        $data = [];

        foreach ($records as $record) {

            $schedule = $record->schedule ?? null;


            // -----------------------------------------------------
            // PROGRAM CODE
            // -----------------------------------------------------

            $programCode = 'N/A';

            if (
                $schedule &&
                !empty($schedule->program_code)
            ) {
                $programCode =
                    trim((string)$schedule->program_code);
            }


            // -----------------------------------------------------
            // BARANGAY
            // -----------------------------------------------------

            $barangay = 'N/A';

            if (
                $schedule &&
                !empty($schedule->barangay)
            ) {
                $barangay =
                    trim((string)$schedule->barangay);
            }


            // -----------------------------------------------------
            // DISTRIBUTION DATE
            // From Schedules.start_date
            // -----------------------------------------------------

            $distributionDate = null;

            if (
                $schedule &&
                !empty($schedule->start_date)
            ) {

                if (
                    $schedule->start_date
                    instanceof \DateTimeInterface
                ) {

                    $distributionDate =
                        $schedule->start_date
                            ->format('Y-m-d');

                } else {

                    $distributionDate =
                        (string)$schedule->start_date;
                }
            }


            // -----------------------------------------------------
            // RECEIVED DATE
            // -----------------------------------------------------

            $receivedDate = null;

            if (!empty($record->received_date)) {

                if (
                    $record->received_date
                    instanceof \DateTimeInterface
                ) {

                    $receivedDate =
                        $record->received_date
                            ->format('Y-m-d');

                } else {

                    $receivedDate =
                        (string)$record->received_date;
                }
            }


            // -----------------------------------------------------
            // STATUS
            // -----------------------------------------------------

            $status =
                !empty($record->status)
                    ? trim((string)$record->status)
                    : 'Not Received';


            // -----------------------------------------------------
            // SUBSIDY ITEM
            // -----------------------------------------------------

            $subsidyItem =
                !empty($record->subsidy_item)
                    ? trim((string)$record->subsidy_item)
                    : 'Seed Subsidy';


            // -----------------------------------------------------
            // ADD RECORD
            // -----------------------------------------------------

            $data[] = [

                'id' =>
                    $record->id,

                'schedule_id' =>
                    $record->schedule_id,

                'program_code' =>
                    $programCode,

                'barangay' =>
                    $barangay,

                'subsidy_item' =>
                    $subsidyItem,

                'quantity' =>
                    $record->quantity,

                'distribution_date' =>
                    $distributionDate,

                'received_date' =>
                    $receivedDate,

                'status' =>
                    $status
            ];
        }


        // =========================================================
        // FARMER NAME
        // =========================================================

        $firstName =
            !empty($farmer->first_name)
                ? trim((string)$farmer->first_name)
                : '';

        $middleName =
            !empty($farmer->middle_name)
                ? trim((string)$farmer->middle_name)
                : '';

        $lastName =
            !empty($farmer->last_name)
                ? trim((string)$farmer->last_name)
                : '';

        $suffix =
            !empty($farmer->suffix)
                ? trim((string)$farmer->suffix)
                : '';


        // =========================================================
        // RESPONSE
        // =========================================================

        return $this->response
            ->withStatus(200)
            ->withType('application/json')
            ->withStringBody(
                json_encode(
                    [
                        'success' => true,

                        'farmer' => [

                            'id' =>
                                $farmer->id,

                            'farmer_no' =>
                                $farmer->farmer_no,

                            'first_name' =>
                                $firstName,

                            'middle_name' =>
                                $middleName,

                            'last_name' =>
                                $lastName,

                            'suffix' =>
                                $suffix,

                            'address' =>
                                $farmer->address ?? ''
                        ],

                        'total_records' =>
                            count($data),

                        'records' =>
                            $data
                    ],
                    JSON_UNESCAPED_UNICODE
                )
            );

    } catch (\Throwable $e) {

        \Cake\Log\Log::error(
            'Farmers::viewRecord error: ' .
            $e->getMessage()
        );

        return $this->response
            ->withStatus(500)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'message' =>
                        'Unable to load farmer records.',
                    'error' =>
                        $e->getMessage()
                ])
            );
    }
}
}
