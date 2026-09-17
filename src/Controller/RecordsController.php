<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Records Controller
 *
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        /*
        * ============================================================
        * LOAD TABLES
        * ============================================================
        */

        $this->loadModel('Records');
        $this->loadModel('Farmers');
        $this->loadModel('Schedules');


        /*
        * ============================================================
        * LOAD COMPONENTS
        * ============================================================
        */

        $this->loadComponent('Flash');
        $this->loadComponent('AuditLogger');
    }
 
    /**
     * Index
     */

    public function index()
    {
        /*
        * ============================================================
        * CREATE EMPTY RECORD ENTITY
        * ============================================================
        */

        $records = $this->Records->newEmptyEntity();


        /*
        * ============================================================
        * LOAD REQUIRED MODELS
        * ============================================================
        */

        $this->loadModel('Farmers');
        $this->loadModel('Schedules');


        /*
        * ============================================================
        * FARMERS
        * ============================================================
        */

        $farmers = $this->Farmers->find()
            ->all()
            ->combine(
                'id',
                function ($farmer) {

                    return trim(
                        ($farmer->first_name ?? '') .
                        ' ' .
                        ($farmer->last_name ?? '')
                    );
                }
            )
            ->toArray();


        /*
        * ============================================================
        * SCHEDULES
        *
        * Only program_code is needed.
        *
        * start_date is used as the distribution date.
        * ============================================================
        */

        $scheduleData = $this->Schedules->find()
            ->select([
                'id',
                'program_code',
                'start_date',
                'start_time'
            ])
            ->order([
                'program_code' => 'ASC',
                'start_date' => 'DESC',
                'id' => 'DESC'
            ])
            ->all()
            ->toArray();


        /*
        * ============================================================
        * SCHEDULE DROPDOWN
        * ============================================================
        */

        $schedules = [];

        foreach ($scheduleData as $schedule) {

            $label = trim(
                (string)($schedule->program_code ?? '')
            );


            /*
            * Add start date
            */

            if (!empty($schedule->start_date)) {

                $label .=
                    ' - ' .
                    $schedule->start_date->format('Y-m-d');
            }


            /*
            * Add start time if available
            */

            if (!empty($schedule->start_time)) {
                $label .= ' ' . date('H:i', strtotime((string)$schedule->start_time));
            }


            $schedules[$schedule->id] = $label;
        }


        /*
        * ============================================================
        * EXCEL IMPORT RESULT
        * ============================================================
        */

        $excelResult = null;

        $session = $this->request->getSession();

        if ($session->check('ExcelImportResult')) {

            $excelResult = $session->consume(
                'ExcelImportResult'
            );
        }


        /*
        * ============================================================
        * SEND DATA TO VIEW
        * ============================================================
        */

        $this->set(compact(
            'records',
            'farmers',
            'schedules',
            'scheduleData',
            'excelResult'
        ));
    }
    /**
     * View
     */
    public function view($id = null)
    {
        $record = $this->Records->get(
            $id,
            [
                'contain' => []
            ]
        );

        $this->set(compact('record'));
    }

    /**
     * Add
     */
    public function add()
    {
        $record = $this->Records->newEmptyEntity();

        if ($this->request->is('post')) {
            $record = $this->Records->patchEntity(
                $record,
                $this->request->getData()
            );

            if ($this->Records->save($record)) {
                $this->Flash->success(
                    __('The record has been saved.')
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            $this->Flash->error(
                __(
                    'The record could not be saved. Please, try again.'
                )
            );
        }

        $this->set(compact('record'));
    }

    /**
     * Edit
     */
    public function edit($id = null)
    {
        $record = $this->Records->get(
            $id,
            [
                'contain' => []
            ]
        );

        if ($this->request->is([
            'patch',
            'post',
            'put'
        ])) {
            $record = $this->Records->patchEntity(
                $record,
                $this->request->getData()
            );

            if ($this->Records->save($record)) {
                $this->Flash->success(
                    __('The record has been saved.')
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            $this->Flash->error(
                __(
                    'The record could not be saved. Please, try again.'
                )
            );
        }

        $this->set(compact('record'));
    }

    /**
     * Delete
     */
    public function delete($id = null)
    {
        $this->request->allowMethod([
            'post',
            'delete'
        ]);

        $record = $this->Records->get($id);

        if ($this->Records->delete($record)) {
            $this->Flash->success(
                __('The record has been deleted.')
            );
        } else {
            $this->Flash->error(
                __(
                    'The record could not be deleted. Please, try again.'
                )
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /**
     * Cancel
     */
    public function cancel($id = null)
    {
        $this->request->allowMethod(['post']);

        $record = $this->Records->get($id);

        $record->status = 'Cancelled';

        if ($this->Records->save($record)) {
            $this->Flash->success(
                __('The record has been cancelled.')
            );
        } else {
            $this->Flash->error(
                __('The record could not be cancelled.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /**
     * Mark as Not Received
     */
    public function notReceived($id = null)
    {
        $this->request->allowMethod(['post']);

        $record = $this->Records->get($id);

        $record->status = 'Not Received';
        $record->confirmed_at = FrozenTime::now();
        $record->received_date = null;

        if ($this->Records->save($record)) {
            $this->Flash->success(
                __('The subsidy has been marked as not received.')
            );
        } else {
            $this->Flash->error(
                __('The record could not be updated.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }
    public function uploadExcel()
    {
        $this->request->allowMethod(['post']);
    
        $file = $this->request->getData('excel_file');
    
        $successCount = 0;
        $skippedCount = 0;
        $errors = [];
    
        /*
         * ============================================================
         * 1. VALIDATE UPLOADED FILE
         * ============================================================
         */
    
        if (!$file) {
            $this->Flash->error('Please select an Excel file.');
    
            return $this->redirect([
                'action' => 'index'
            ]);
        }
    
        if ($file->getError() !== UPLOAD_ERR_OK) {
            $this->Flash->error(
                'File upload failed. Upload error code: ' .
                $file->getError()
            );
    
            return $this->redirect([
                'action' => 'index'
            ]);
        }
    
        $fileName = $file->getClientFilename();
    
        $extension = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );
    
        if (!in_array($extension, ['xlsx', 'xls'], true)) {
            $this->Flash->error(
                'Invalid file type. Please upload an XLSX or XLS file.'
            );
    
            return $this->redirect([
                'action' => 'index'
            ]);
        }
    
    
        /*
         * ============================================================
         * 2. LOAD EXCEL FILE
         * ============================================================
         */
    
        try {
    
            $filePath = $file->getStream()->getMetadata('uri');
    
            $spreadsheet =
                \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    
        } catch (\Throwable $e) {
    
            $this->Flash->error(
                'Unable to read the Excel file: ' .
                $e->getMessage()
            );
    
            return $this->redirect([
                'action' => 'index'
            ]);
        }
    
    
        /*
         * ============================================================
         * 3. GET ACTIVE WORKSHEET
         * ============================================================
         */
    
        $worksheet = $spreadsheet->getActiveSheet();
    
        $highestRow = $worksheet->getHighestRow();
    
        if ($highestRow < 2) {
    
            $this->Flash->warning(
                'The Excel file contains no data rows.'
            );
    
            return $this->redirect([
                'action' => 'index'
            ]);
        }
    
    
        /*
         * ============================================================
         * 4. PROCESS EACH EXCEL ROW
         *
         * Excel structure:
         *
         * A = Farmer
         * B = Subsidy Item
         * C = Quantity
         * D = Received Date
         * E = Status
         * F = Schdule ID
         * ============================================================
         */
    
        for ($row = 2; $row <= $highestRow; $row++) {
    
            /*
             * --------------------------------------------------------
             * READ EXCEL VALUES
             * --------------------------------------------------------
             */
    
            $farmerName = trim(
                (string)$worksheet
                    ->getCell("A{$row}")
                    ->getValue()
            );
    
            $subsidyItem = trim(
                (string)$worksheet
                    ->getCell("B{$row}")
                    ->getValue()
            );
    
            $quantity =
                $worksheet
                    ->getCell("C{$row}")
                    ->getValue();
    
            $receivedDateValue =
                $worksheet
                    ->getCell("D{$row}")
                    ->getValue();
    
            $status = trim(
                (string)$worksheet
                    ->getCell("E{$row}")
                    ->getValue()
            );
    
            $scheduleIdValue =
                $worksheet
                    ->getCell("F{$row}")
                    ->getValue();
    
    
            /*
             * --------------------------------------------------------
             * SKIP COMPLETELY EMPTY ROWS
             * --------------------------------------------------------
             */
    
            if (
                $farmerName === '' &&
                $subsidyItem === '' &&
                ($quantity === null || $quantity === '') &&
                ($receivedDateValue === null ||
                    $receivedDateValue === '') &&
                $status === '' &&
                ($scheduleIdValue === null ||
                    $scheduleIdValue === '')
            ) {
                continue;
            }
    
    
            /*
             * ========================================================
             * 5. VALIDATE FARMER
             * ========================================================
             */
    
            if ($farmerName === '') {
    
                $errors[] =
                    "Row {$row}: Farmer name is required.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 6. VALIDATE SUBSIDY ITEM
             *
             * Only Seed Subsidy is allowed.
             * ========================================================
             */
    
            if (
                strtolower(trim($subsidyItem)) !==
                strtolower('Seed Subsidy')
            ) {
    
                $errors[] =
                    "Row {$row}: Subsidy Item must be "
                    . "'Seed Subsidy'.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 7. VALIDATE QUANTITY
             * ========================================================
             */
    
            if (
                $quantity === null ||
                $quantity === ''
            ) {
    
                $errors[] =
                    "Row {$row}: Quantity is required.";
    
                continue;
            }
    
            if (!is_numeric($quantity)) {
    
                $errors[] =
                    "Row {$row}: Quantity must be a valid number.";
    
                continue;
            }
    
            $quantity = (float)$quantity;
    
            if ($quantity <= 0) {
    
                $errors[] =
                    "Row {$row}: Quantity must be greater than zero.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 8. VALIDATE SCHEDULE ID
             * ========================================================
             */
    
            if (
                $scheduleIdValue === null ||
                trim((string)$scheduleIdValue) === ''
            ) {
    
                $errors[] =
                    "Row {$row}: Schedule ID is required.";
    
                continue;
            }
    
    
            /*
             * Excel may contain:
             *
             * 31
             * 31.0
             *
             * Convert safely to integer.
             */
    
            if (
                is_numeric($scheduleIdValue) &&
                (float)$scheduleIdValue ==
                (int)$scheduleIdValue
            ) {
    
                $scheduleId =
                    (int)$scheduleIdValue;
    
            } else {
    
                $scheduleId =
                    (int)trim((string)$scheduleIdValue);
            }
    
    
            if ($scheduleId <= 0) {
    
                $errors[] =
                    "Row {$row}: Invalid Schedule ID '" .
                    $scheduleIdValue .
                    "'.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 9. FIND SCHEDULE
             *
             * IMPORTANT:
             *
             * We DO NOT use program_name.
             *
             * We DO NOT use distribution_date.
             *
             * We only use Schedule ID.
             * ========================================================
             */
    
            $schedule = $this->Schedules
                ->find()
                ->where([
                    'Schedules.id' => $scheduleId
                ])
                ->first();
    
    
            if (!$schedule) {
    
                $errors[] =
                    "Row {$row}: Schedule ID {$scheduleId} "
                    . "does not exist.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 10. VALIDATE PROGRAM CODE
             *
             * Seed Subsidy schedule codes:
             *
             * SD-1
             * SD-2
             * ========================================================
             */
    
            $programCode = strtoupper(
                trim((string)$schedule->program_code)
            );
    
    
            if (
                !in_array(
                    $programCode,
                    ['SD-1', 'SD-2'],
                    true
                )
            ) {
    
                $errors[] =
                    "Row {$row}: Schedule ID {$scheduleId} "
                    . "has unsupported program code '" .
                    $programCode .
                    "'. Only SD-1 and SD-2 are allowed "
                    . "for Seed Subsidy.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 11. NORMALIZE STATUS
             * ========================================================
             */
    
            $normalizedStatus =
                strtolower(trim($status));
    
    
            /*
             * Convert:
             *
             * Not Received
             * not_received
             * not-received
             *
             * into:
             *
             * not received
             */
    
            $normalizedStatus = preg_replace(
                '/[\s_-]+/',
                ' ',
                $normalizedStatus
            );
    
    
            /*
             * ========================================================
             * 12. CONVERT STATUS
             *
             * IMPORTANT:
             *
             * Do NOT use "continue" inside the switch.
             * ========================================================
             */
    
            $finalStatus = null;
    
    
            switch ($normalizedStatus) {
    
                case 'received':
    
                    $finalStatus = 'Received';
    
                    break;
    
    
                case 'not received':
    
                    $finalStatus = 'Not Received';
    
                    break;
    
    
                case 're scheduled':
    
                    $finalStatus = 'Re-Scheduled';
    
                    break;
    
    
                case 'cancelled':
    
                    $finalStatus = 'Cancelled';
    
                    break;
    
    
                case 'canceled':
    
                    $finalStatus = 'Cancelled';
    
                    break;
    
    
                case '':
    
                    /*
                     * Blank status defaults to Not Received.
                     */
    
                    $finalStatus = 'Not Received';
    
                    break;
    
    
                default:
    
                    $errors[] =
                        "Row {$row}: Invalid status '" .
                        $status .
                        "'. Allowed values are "
                        . "Received, Not Received, "
                        . "Re-Scheduled, or Cancelled.";
    
                    $finalStatus = null;
    
                    break;
            }
    
    
            /*
             * --------------------------------------------------------
             * IMPORTANT:
             *
             * This "continue" is OUTSIDE the switch.
             *
             * Therefore it correctly continues the FOR loop.
             * --------------------------------------------------------
             */
    
            if ($finalStatus === null) {
                continue;
            }
    
    
            /*
             * ========================================================
             * 13. FIND FARMER
             * ========================================================
             *
             * Excel:
             *
             * Farmer 1
             *
             * Database:
             *
             * first_name + last_name
             *
             * Comparison is case-insensitive.
             * ========================================================
             */
    
            $normalizedExcelFarmerName =
                strtolower(
                    trim(
                        preg_replace(
                            '/\s+/',
                            ' ',
                            $farmerName
                        )
                    )
                );
    
    
            $farmers = $this->Farmers
                ->find()
                ->all();
    
    
            $farmer = null;
    
    
            foreach ($farmers as $candidate) {
    
                $databaseFarmerName =
                    trim(
                        $candidate->first_name .
                        ' ' .
                        $candidate->last_name
                    );
    
    
                $normalizedDatabaseFarmerName =
                    strtolower(
                        preg_replace(
                            '/\s+/',
                            ' ',
                            $databaseFarmerName
                        )
                    );
    
    
                if (
                    $normalizedExcelFarmerName ===
                    $normalizedDatabaseFarmerName
                ) {
    
                    $farmer = $candidate;
    
                    break;
                }
            }
    
    
            if (!$farmer) {
    
                $errors[] =
                    "Row {$row}: Farmer '" .
                    $farmerName .
                    "' was not found in the database.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 14. CHECK DUPLICATE
             *
             * Duplicate rule:
             *
             * farmer_id + schedule_id
             * ========================================================
             */
    
            $existingRecord =
                $this->Records
                    ->find()
                    ->where([
                        'Records.farmer_id' =>
                            $farmer->id,
    
                        'Records.schedule_id' =>
                            $scheduleId
                    ])
                    ->first();
    
    
            if ($existingRecord) {
    
                $skippedCount++;
    
                $errors[] =
                    "Row {$row}: Record already exists "
                    . "for farmer '" .
                    $farmerName .
                    "' and Schedule ID " .
                    $scheduleId .
                    ". Skipped.";
    
                continue;
            }
    
    
            /*
             * ========================================================
             * 15. PROCESS RECEIVED DATE
             *
             * BUSINESS RULE:
             *
             * Received:
             *     Use Excel Received Date.
             *
             * Not Received:
             *     NULL
             *
             * Re-Scheduled:
             *     NULL
             *
             * Cancelled:
             *     NULL
             *
             * Blank date:
             *     NULL
             * ========================================================
             */
    
            $receivedDate = null;
    
    
            /*
             * Only process the Excel date if status is Received.
             */
    
            if ($finalStatus === 'Received') {
    
                /*
                 * ----------------------------------------------------
                 * Blank Received Date
                 * ----------------------------------------------------
                 */
    
                if (
                    $receivedDateValue !== null &&
                    trim((string)$receivedDateValue) !== ''
                ) {
    
                    try {
    
                        /*
                         * ------------------------------------------------
                         * Excel numeric date
                         * ------------------------------------------------
                         *
                         * Example:
                         *
                         * 45889
                         */
    
                        if (
                            is_numeric($receivedDateValue) &&
                            (float)$receivedDateValue > 0
                        ) {
    
                            $receivedDate =
                                \PhpOffice\PhpSpreadsheet\Shared\Date
                                    ::excelToDateTimeObject(
                                        (float)$receivedDateValue
                                    );
    
                        } else {
    
                            /*
                             * ------------------------------------------------
                             * Text date
                             * ------------------------------------------------
                             */
    
                            $dateString =
                                trim(
                                    (string)$receivedDateValue
                                );
    
    
                            $timestamp =
                                strtotime($dateString);
    
    
                            if ($timestamp === false) {
    
                                throw new \Exception(
                                    'Invalid date format'
                                );
                            }
    
    
                            $receivedDate =
                                new \DateTime($dateString);
                        }
    
                    } catch (\Throwable $e) {
    
                        $errors[] =
                            "Row {$row}: Invalid Received Date '" .
                            $receivedDateValue .
                            "'.";
    
                        continue;
                    }
                }
    
            } else {
    
                /*
                 * ----------------------------------------------------
                 * IMPORTANT:
                 *
                 * Ignore the Excel Received Date for:
                 *
                 * Not Received
                 * Re-Scheduled
                 * Cancelled
                 *
                 * The database receives NULL.
                 * ----------------------------------------------------
                 */
    
                $receivedDate = null;
            }
    
    
            /*
             * ========================================================
             * 16. CREATE RECORD
             * ========================================================
             */
    
            $record =
                $this->Records->newEmptyEntity();
    
    
            /*
             * Farmer
             */
    
            $record->farmer_id =
                $farmer->id;
    
    
            /*
             * Schedule
             */
    
            $record->schedule_id =
                $scheduleId;
    
    
            /*
             * IMPORTANT:
             *
             * Always save Seed Subsidy.
             */
    
            $record->subsidy_item =
                'Seed Subsidy';
    
    
            /*
             * Quantity
             */
    
            $record->quantity =
                $quantity;
    
    
            /*
             * Received Date
             */
    
            $record->received_date =
                $receivedDate;
    
    
            /*
             * Status
             */
    
            $record->status =
                $finalStatus;
    
    
            /*
             * ========================================================
             * 17. SAVE RECORD
             * ========================================================
             */
    
            if ($this->Records->save($record)) {
    
                $successCount++;
    
    
                /*
                 * ====================================================
                 * AUDIT LOG
                 * ====================================================
                 */
    
                try {
    
                    if (
                        isset($this->AuditLogger) &&
                        $this->AuditLogger
                    ) {
    
                        $auditMessage =
                            "Imported Seed Subsidy record for farmer '" .
                            $farmerName .
                            "' " .
                            "(Schedule ID: " .
                            $scheduleId .
                            ", Program Code: " .
                            $programCode .
                            ", Quantity: " .
                            $quantity .
                            ", Status: " .
                            $finalStatus .
                            ")";
    
    
                        $this->AuditLogger->logActivity(
                            'Import Record',
                            $auditMessage
                        );
                    }
    
                } catch (\Throwable $auditException) {
    
                    /*
                     * Audit logging failure should not
                     * cancel a successful import.
                     */
                }
    
            } else {
    
                /*
                 * ====================================================
                 * SAVE VALIDATION ERRORS
                 * ====================================================
                 */
    
                $entityErrors =
                    $record->getErrors();
    
    
                $errorDetails = [];
    
    
                foreach (
                    $entityErrors
                    as $field => $fieldErrors
                ) {
    
                    if (is_array($fieldErrors)) {
    
                        foreach (
                            $fieldErrors
                            as $message
                        ) {
    
                            $errorDetails[] =
                                "{$field}: {$message}";
                        }
    
                    } else {
    
                        $errorDetails[] =
                            "{$field}: {$fieldErrors}";
                    }
                }
    
    
                if (!empty($errorDetails)) {
    
                    $errors[] =
                        "Row {$row}: Could not save record. " .
                        implode(
                            ', ',
                            $errorDetails
                        );
    
                } else {
    
                    $errors[] =
                        "Row {$row}: Could not save record.";
                }
            }
        }
    
    
        /*
         * ============================================================
         * 18. SAVE IMPORT RESULT TO SESSION
         * ============================================================
         */
    
        $this->request
            ->getSession()
            ->write(
                'ExcelImportResult',
                [
                    'success' => $successCount,
                    'skipped' => $skippedCount,
                    'errors' => $errors
                ]
            );
    
    
        /*
         * ============================================================
         * 19. FLASH MESSAGES
         * ============================================================
         */
    
        if ($successCount > 0) {
    
            $this->Flash->success(
                $successCount .
                ' record(s) imported successfully.'
            );
        }
    
    
        if ($skippedCount > 0) {
    
            $this->Flash->warning(
                $skippedCount .
                ' duplicate record(s) were skipped.'
            );
        }
    
    
        if (!empty($errors)) {
    
            $this->Flash->error(
                count($errors) .
                ' record(s) could not be imported or were skipped '
                . 'due to validation issues.'
            );
        }
    
    
        /*
         * ============================================================
         * 20. REDIRECT
         * ============================================================
         */
    
        return $this->redirect([
            'action' => 'index'
        ]);
    }
    
    /**
 * Download blank Excel template for Records import.
 */
public function downloadExcelTemplate()
{
    $spreadsheet =
        new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet =
        $spreadsheet->getActiveSheet();

    $sheet->setTitle('Records');

    /*
    |--------------------------------------------------------------------------
    | HEADERS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | These MUST match uploadExcel().
    |
    | A = Farmer
    | B = Subsidy Item
    | C = Quantity
    | D = Received Date
    | E = Status
    | F = Schedule ID
    |
    */

    $headers = [
        'Farmer',
        'Subsidy Item',
        'Quantity',
        'Received Date',
        'Status',
        'Schedule ID'
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
        'A' => 30,
        'B' => 22,
        'C' => 15,
        'D' => 20,
        'E' => 20,
        'F' => 15
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
        ->getStyle("A1:F{$lastTemplateRow}")
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
        $sheet->getStyle('A1:F1');

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
    | TEXT FORMATTING
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle("A2:A{$lastTemplateRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    $sheet
        ->getStyle("B2:B{$lastTemplateRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    $sheet
        ->getStyle("E2:E{$lastTemplateRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    $sheet
        ->getStyle("F2:F{$lastTemplateRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    /*
    |--------------------------------------------------------------------------
    | QUANTITY
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle("C2:C{$lastTemplateRow}")
        ->getNumberFormat()
        ->setFormatCode('0.00');

    /*
    |--------------------------------------------------------------------------
    | RECEIVED DATE
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle("D2:D{$lastTemplateRow}")
        ->getNumberFormat()
        ->setFormatCode('m/d/Y');

    /*
    |--------------------------------------------------------------------------
    | ALIGNMENT
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle("A2:F{$lastTemplateRow}")
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
        "A1:F{$lastTemplateRow}"
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
        'records_import_template.xlsx';

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    $tempFile =
        tempnam(
            sys_get_temp_dir(),
            'records_template_'
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
    
    public function downloadRecordsExcel()
{
    /*
    |--------------------------------------------------------------------------
    | GET SELECTED LOCATION
    |--------------------------------------------------------------------------
    |
    | Empty location = download ALL records
    | Otherwise filter by Farmer address.
    |
    */

    $location = trim(
        (string)$this->request->getQuery('location')
    );


    /*
    |--------------------------------------------------------------------------
    | GET RECORDS
    |--------------------------------------------------------------------------
    */

    $query = $this->Records->find()
        ->contain([
            'Farmers',
            'Schedules'
        ])
        ->order([
            'Records.id' => 'ASC'
        ]);


    /*
    |--------------------------------------------------------------------------
    | FILTER BY LOCATION
    |--------------------------------------------------------------------------
    |
    | The farmer's ADDRESS is used as the location.
    |
    */

    if ($location !== '') {

        $query->matching('Farmers', function ($q) use ($location) {

            return $q->where([
                'Farmers.address' => $location
            ]);

        });
    }


    $records = $query->all();


    /*
    |--------------------------------------------------------------------------
    | CREATE SPREADSHEET
    |--------------------------------------------------------------------------
    */

    $spreadsheet =
        new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet =
        $spreadsheet->getActiveSheet();

    $sheet->setTitle('Records');


    /*
    |--------------------------------------------------------------------------
    | HEADERS
    |--------------------------------------------------------------------------
    */

    $headers = [
        'LGU RSBSA Number',
        'Distribution Code',
        'Subsidy Item',
        'Quantity',
        'Received Date',
        'Status'
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
    | INSERT RECORD DATA
    |--------------------------------------------------------------------------
    */

    $rowNumber = 2;

    foreach ($records as $record) {

        /*
        |----------------------------------------------------------------------
        | LGU RSBSA NUMBER
        |----------------------------------------------------------------------
        */

        $farmerNo = '';

        if (!empty($record->farmer)) {

            $farmerNo =
                trim(
                    (string)($record->farmer->farmer_no ?? '')
                );
        }

        $sheet->setCellValueExplicit(
            'A' . $rowNumber,
            $farmerNo,
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );


        /*
        |----------------------------------------------------------------------
        | DISTRIBUTION CODE
        |----------------------------------------------------------------------
        */

        $distributionCode = '';

        if (!empty($record->schedule)) {

            $distributionCode =
                trim(
                    (string)($record->schedule->program_code ?? '')
                );
        }

        if (
            $distributionCode === '' &&
            isset($record->program_code)
        ) {

            $distributionCode =
                trim(
                    (string)$record->program_code
                );
        }

        $sheet->setCellValueExplicit(
            'B' . $rowNumber,
            $distributionCode,
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );


        /*
        |----------------------------------------------------------------------
        | SUBSIDY ITEM
        |----------------------------------------------------------------------
        */

        $subsidyItem =
            trim(
                (string)($record->subsidy_item ?? '')
            );

        $sheet->setCellValue(
            'C' . $rowNumber,
            $subsidyItem
        );


        /*
        |----------------------------------------------------------------------
        | QUANTITY
        |----------------------------------------------------------------------
        */

        $quantity =
            $record->quantity ?? '';

        if ($quantity !== '') {

            $sheet->setCellValue(
                'D' . $rowNumber,
                (float)$quantity
            );

        } else {

            $sheet->setCellValue(
                'D' . $rowNumber,
                ''
            );
        }


        /*
        |----------------------------------------------------------------------
        | RECEIVED DATE
        |----------------------------------------------------------------------
        */

        $receivedDate =
            $record->received_date ?? null;

        if (!empty($receivedDate)) {

            if (
                $receivedDate instanceof
                \DateTimeInterface
            ) {

                $date =
                    $receivedDate->format('m/d/Y');

            } else {

                $timestamp =
                    strtotime(
                        (string)$receivedDate
                    );

                $date =
                    $timestamp !== false
                        ? date(
                            'm/d/Y',
                            $timestamp
                        )
                        : '';
            }

            $sheet->setCellValue(
                'E' . $rowNumber,
                $date
            );

        } else {

            $sheet->setCellValue(
                'E' . $rowNumber,
                ''
            );
        }


        /*
        |----------------------------------------------------------------------
        | STATUS
        |----------------------------------------------------------------------
        */

        $status =
            trim(
                (string)($record->status ?? '')
            );

        $sheet->setCellValue(
            'F' . $rowNumber,
            $status
        );


        $rowNumber++;
    }


    /*
    |--------------------------------------------------------------------------
    | LAST ROW
    |--------------------------------------------------------------------------
    */

    $lastRow =
        max(
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
        'B' => 22,
        'C' => 22,
        'D' => 15,
        'E' => 20,
        'F' => 20
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
        $sheet->getStyle('A1:F1');

    $headerStyle
        ->getFill()
        ->setFillType(
            \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
        )
        ->getStartColor()
        ->setARGB('FFFF00');

    $headerStyle
        ->getFont()
        ->setBold(true);

    $headerStyle
        ->getFont()
        ->setSize(11);

    $headerStyle
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $headerStyle
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    $headerStyle
        ->getAlignment()
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
            ->getStyle("A2:F{$lastRow}")
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
    | ROW HEIGHT
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getRowDimension(1)
        ->setRowHeight(32);

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
    | TEXT FORMATTING
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle("A2:A{$lastRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    $sheet
        ->getStyle("B2:B{$lastRow}")
        ->getNumberFormat()
        ->setFormatCode('@');

    $sheet
        ->getStyle("F2:F{$lastRow}")
        ->getNumberFormat()
        ->setFormatCode('@');


    /*
    |--------------------------------------------------------------------------
    | QUANTITY FORMAT
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        $sheet
            ->getStyle("D2:D{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('0.00');
    }


    /*
    |--------------------------------------------------------------------------
    | VERTICAL ALIGNMENT
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        $sheet
            ->getStyle("A2:F{$lastRow}")
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
        "A1:F{$lastRow}"
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
    | FILE NAME
    |--------------------------------------------------------------------------
    */

    if ($location !== '') {

        // Clean location for filename
        $safeLocation =
            preg_replace(
                '/[^A-Za-z0-9_\-]+/',
                '_',
                $location
            );

        $filename =
            'records_' .
            $safeLocation .
            '_' .
            date('Y-m-d_H-i-s') .
            '.xlsx';

    } else {

        $filename =
            'records_all_' .
            date('Y-m-d_H-i-s') .
            '.xlsx';
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE EXCEL
    |--------------------------------------------------------------------------
    */

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    $tempFile =
        tempnam(
            sys_get_temp_dir(),
            'records_'
        );

    $writer->save($tempFile);


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

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
     * ================================================================
     * VIEW SINGLE RECORD
     * ================================================================
     */
    public function viewRecord($id = null)
    {
        if (!$id) {
            $this->Flash->error(
                'Invalid record ID.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        try {
            $record =
                $this->Records->get(
                    $id,
                    [
                        'contain' => [
                            'Farmers',
                            'Schedules'
                        ]
                    ]
                );

            $this->set([
                'record' => $record
            ]);
        } catch (\Throwable $e) {
            $this->Flash->error(
                'Record not found.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }
    }

    /**
     * ================================================================
     * GET SINGLE RECORD JSON
     * ================================================================
     */
    public function getRecord($recordId = null)
    {
        $this->request->allowMethod(['get']);

        $this->autoRender = false;

        if (!$recordId) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' =>
                            'Invalid record ID.'
                    ])
                );
        }

        try {
            $record =
                $this->Records->get(
                    $recordId,
                    [
                        'contain' => [
                            'Farmers',
                            'Schedules'
                        ]
                    ]
                );

            /*
             * Farmer
             */
            $farmerName = 'N/A';

            if (!empty($record->farmer)) {
                $farmerName =
                    trim(
                        ($record->farmer->first_name ?? '') .
                        ' ' .
                        ($record->farmer->last_name ?? '')
                    );
            }

            /*
             * Schedule
             */
            $distributionDate =
                'N/A';

            $distributionTime =
                'N/A';

            $programCode =
                'N/A';

            if (!empty($record->schedule)) {
                $schedule =
                    $record->schedule;

                /*
                 * IMPORTANT:
                 * schedules table uses start_date.
                 */
                if (
                    !empty(
                        $schedule->start_date
                    )
                ) {
                    $distributionDate =
                        $schedule->start_date;
                }

                if (
                    !empty(
                        $schedule->start_time
                    )
                ) {
                    $distributionTime =
                        $schedule->start_time;
                }

                if (
                    !empty(
                        $schedule->program_code
                    )
                ) {
                    $programCode =
                        $schedule->program_code;
                }
            }

            /*
             * Subsidy item
             */
            $subsidyItem =
                !empty(
                    $record->subsidy_item
                )
                    ? $record->subsidy_item
                    : 'N/A';

            /*
             * Quantity
             */
            $quantity =
                isset($record->quantity)
                    ? $record->quantity
                    : 'N/A';

            /*
             * Received date
             */
            $receivedDate =
                !empty(
                    $record->received_date
                )
                    ? $record->received_date
                    : 'N/A';

            /*
             * Status
             */
            $status =
                !empty($record->status)
                    ? $record->status
                    : 'N/A';

            return $this->response
                ->withStatus(200)
                ->withType('application/json')
                ->withStringBody(
                    json_encode(
                        [
                            'success' => true,

                            'data' => [
                                'id' =>
                                    $record->id,

                                'farmer_id' =>
                                    $record->farmer_id,

                                'schedule_id' =>
                                    $record->schedule_id,

                                'farmer_name' =>
                                    $farmerName,

                                'program_code' =>
                                    $programCode,

                                'subsidy_item' =>
                                    $subsidyItem,

                                'quantity' =>
                                    $quantity,

                                'distribution_date' =>
                                    $distributionDate,

                                'distribution_time' =>
                                    $distributionTime,

                                'received_date' =>
                                    $receivedDate,

                                'status' =>
                                    $status
                            ]
                        ],
                        JSON_UNESCAPED_UNICODE
                    )
                );
        } catch (\Throwable $e) {
            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' =>
                            $e->getMessage()
                    ])
                );
        }
    }

    /**
     * ================================================================
     * GET ALL FARMER DISTRIBUTION RECORDS
     * ================================================================
     */
    public function getFarmerRecords($farmerId = null)
    {
        $this->request->allowMethod(['get']);

        try {
            /*
             * ========================================================
             * VALIDATE FARMER ID
             * ========================================================
             */
            if (
                $farmerId === null ||
                !is_numeric($farmerId)
            ) {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'message' =>
                                'Invalid farmer ID.'
                        ])
                    );
            }

            $farmerId =
                (int)$farmerId;

            /*
             * ========================================================
             * FARMERS MODEL
             * ========================================================
             */
            $this->loadModel(
                'Farmers'
            );

            /*
             * ========================================================
             * FIND FARMER
             * ========================================================
             */
            $farmer =
                $this->Farmers->find()
                    ->select([
                        'id',
                        'first_name',
                        'last_name'
                    ])
                    ->where([
                        'Farmers.id' =>
                            $farmerId
                    ])
                    ->first();

            if (!$farmer) {
                return $this->response
                    ->withStatus(404)
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'message' =>
                                'Farmer not found.'
                        ])
                    );
            }

            $records =
                $this->Records->find()
                    ->select([
                        'record_id' =>
                            'Records.id',

                        'farmer_id' =>
                            'Records.farmer_id',

                        'schedule_id' =>
                            'Records.schedule_id',

                        'subsidy_item' =>
                            'Records.subsidy_item',

                        'quantity' =>
                            'Records.quantity',

                        'received_date' =>
                            'Records.received_date',

                        'status' =>
                            'Records.status',

                        /*
                         * Program comes from Schedules
                         */
                        'program_code' =>
                            'Schedules.program_code',
                        'distribution_date' =>
                            'Schedules.start_date'
                    ])
                    ->innerJoin(
                        [
                            'Schedules' =>
                                'schedules'
                        ],
                        [
                            'Schedules.id = Records.schedule_id'
                        ]
                    )
                    ->where([
                        'Records.farmer_id' =>
                            $farmerId
                    ])
                    ->order([
                        'Schedules.start_date' =>
                            'DESC',

                        'Records.id' =>
                            'DESC'
                    ])
                    ->enableHydration(false)
                    ->toArray();

            /*
             * ========================================================
             * FORMAT DATES
             * ========================================================
             */
            foreach (
                $records as &$record
            ) {
                if (
                    isset(
                        $record[
                            'distribution_date'
                        ]
                    ) &&
                    $record[
                        'distribution_date'
                    ] instanceof
                        \DateTimeInterface
                ) {
                    $record[
                        'distribution_date'
                    ] =
                        $record[
                            'distribution_date'
                        ]->format(
                            'Y-m-d'
                        );
                }

                if (
                    isset(
                        $record[
                            'received_date'
                        ]
                    ) &&
                    $record[
                        'received_date'
                    ] instanceof
                        \DateTimeInterface
                ) {
                    $record[
                        'received_date'
                    ] =
                        $record[
                            'received_date'
                        ]->format(
                            'Y-m-d'
                        );
                }
            }

            unset($record);

            /*
             * ========================================================
             * FARMER RESPONSE
             * ========================================================
             */
            $farmerData = [
                'id' =>
                    $farmer->id,

                'first_name' =>
                    $farmer->first_name ?? '',

                'last_name' =>
                    $farmer->last_name ?? ''
            ];

            /*
             * ========================================================
             * JSON RESPONSE
             * ========================================================
             */
            return $this->response
                ->withStatus(200)
                ->withType('application/json')
                ->withStringBody(
                    json_encode(
                        [
                            'success' =>
                                true,

                            'farmer' =>
                                $farmerData,

                            'records' =>
                                $records
                        ],
                        JSON_UNESCAPED_UNICODE
                    )
                );
        } catch (\Throwable $e) {
            /*
             * ========================================================
             * LOG ERROR
             * ========================================================
             */
            \Cake\Log\Log::error(
                'RecordsController::getFarmerRecords(): ' .
                $e->getMessage() .
                "\n" .
                $e->getTraceAsString()
            );

            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' =>
                            $e->getMessage()
                    ])
                );
        }
    }
}
