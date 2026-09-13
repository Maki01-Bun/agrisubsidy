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
    /**
     * Index
     */
    public function index()
    {
        $records = $this->Records->newEmptyEntity();

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
         * IMPORTANT:
         * Your schedules table does NOT have distribution_date.
         *
         * We use start_date as the distribution date.
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
                'start_date' => 'DESC'
            ])
            ->all()
            ->toArray();

        /*
         * Schedule dropdown
         */
        $schedules = [];

        foreach ($scheduleData as $schedule) {
            $schedules[$schedule->id] =
                $schedule->program_code;
        }

        /*
         * Excel import result.
         *
         * consume() removes it after reading so the same
         * alert will not appear again on page refresh.
         */
        $excelResult = null;

        $session = $this->request->getSession();

        if ($session->check('ExcelImportResult')) {
            $excelResult = $session->consume(
                'ExcelImportResult'
            );
        }

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

    $session = $this->request->getSession();

    $result = [
        'success' => false,
        'message' => '',
        'imported' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => []
    ];

    $tempPath = null;

    try {

        /* =========================================================
         * 1. CHECK FILE
         * ========================================================= */

        $file = $this->request->getData('excel_file');

        if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(
                'Please select a valid Excel file.'
            );
        }

        $extension = strtolower(
            pathinfo(
                $file->getClientFilename(),
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($extension, ['xlsx', 'xls'], true)) {
            throw new \RuntimeException(
                'Invalid file type. Please upload an .xlsx or .xls file.'
            );
        }


        /* =========================================================
         * 2. SAVE TEMPORARY FILE
         * ========================================================= */

        $tempPath =
            TMP .
            uniqid('records_', true) .
            '.' .
            $extension;

        $file->moveTo($tempPath);


        /* =========================================================
         * 3. LOAD EXCEL
         * ========================================================= */

        $spreadsheet =
            \PhpOffice\PhpSpreadsheet\IOFactory::load(
                $tempPath
            );

        $worksheet =
            $spreadsheet->getActiveSheet();

        $highestRow =
            $worksheet->getHighestRow();


        /* =========================================================
         * 4. VALIDATE HEADERS
         *
         * A = Farmer
         * B = Subsidy Item
         * C = Quantity
         * D = Received Date
         * E = Status
         * F = Schedule ID
         * ========================================================= */

        $headers = [];

        for ($column = 1; $column <= 6; $column++) {

            $headers[$column] = strtolower(
                trim(
                    (string)$worksheet
                        ->getCellByColumnAndRow(
                            $column,
                            1
                        )
                        ->getValue()
                )
            );
        }

        $expectedHeaders = [
            1 => 'farmer',
            2 => 'subsidy item',
            3 => 'quantity',
            4 => 'received date',
            5 => 'status',
            6 => 'schedule id'
        ];

        foreach ($expectedHeaders as $column => $expected) {

            if (($headers[$column] ?? '') !== $expected) {

                throw new \RuntimeException(
                    'Invalid Excel format. Column ' .
                    $column .
                    ' must be "' .
                    $expected .
                    '".'
                );
            }
        }


        /* =========================================================
         * 5. GET TABLES
         * ========================================================= */

        $farmersTable =
            $this->fetchTable('Farmers');

        $schedulesTable =
            $this->fetchTable('Schedules');

        $recordsTable =
            $this->fetchTable('Records');


        /* =========================================================
         * 6. TRACK DUPLICATES IN EXCEL
         * ========================================================= */

        $seen = [];


        /* =========================================================
         * 7. PROCESS ROWS
         * ========================================================= */

        for ($row = 2; $row <= $highestRow; $row++) {

            /* -----------------------------------------------------
             * Read values
             * ----------------------------------------------------- */

            $farmerValue = trim(
                (string)$worksheet
                    ->getCellByColumnAndRow(1, $row)
                    ->getFormattedValue()
            );

            $subsidyItem = trim(
                (string)$worksheet
                    ->getCellByColumnAndRow(2, $row)
                    ->getFormattedValue()
            );

            $quantityValue =
                $worksheet
                    ->getCellByColumnAndRow(3, $row)
                    ->getValue();

            $receivedDateCell =
                $worksheet
                    ->getCellByColumnAndRow(4, $row);

            $receivedDateValue =
                $receivedDateCell->getValue();

            $statusValue = trim(
                (string)$worksheet
                    ->getCellByColumnAndRow(5, $row)
                    ->getFormattedValue()
            );

            $scheduleIdValue =
                $worksheet
                    ->getCellByColumnAndRow(6, $row)
                    ->getValue();


            /* -----------------------------------------------------
             * Skip completely empty rows
             * ----------------------------------------------------- */

            if (
                $farmerValue === '' &&
                ($quantityValue === null ||
                    trim((string)$quantityValue) === '') &&
                ($receivedDateValue === null ||
                    trim((string)$receivedDateValue) === '') &&
                $statusValue === '' &&
                ($scheduleIdValue === null ||
                    trim((string)$scheduleIdValue) === '')
            ) {
                continue;
            }


            /* =====================================================
             * FARMER
             * ===================================================== */

            if ($farmerValue === '') {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Farmer is required.";

                continue;
            }


            /*
             * First try farmer_no
             */

            $farmer =
                $farmersTable
                    ->find()
                    ->where([
                        'farmer_no' => $farmerValue
                    ])
                    ->first();


            /*
             * If not found, try farmer_name
             */

            if (!$farmer) {

                $farmer =
                    $farmersTable
                        ->find()
                        ->where([
                            'farmer_name' => $farmerValue
                        ])
                        ->first();
            }


            if (!$farmer) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Farmer " .
                    "'{$farmerValue}' was not found.";

                continue;
            }

            $farmerId = (int)$farmer->id;


            /* =====================================================
             * SUBSIDY ITEM
             * ===================================================== */

            if (
                $subsidyItem === '' ||
                strcasecmp(
                    $subsidyItem,
                    'Seed Subsidy'
                ) !== 0
            ) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Subsidy Item must be " .
                    "'Seed Subsidy'.";

                continue;
            }

            /*
             * Always save Seed Subsidy.
             */

            $subsidyItem = 'Seed Subsidy';


            /* =====================================================
             * QUANTITY
             * ===================================================== */

            if (
                $quantityValue === null ||
                trim((string)$quantityValue) === ''
            ) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Quantity is required.";

                continue;
            }

            if (!is_numeric($quantityValue)) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Quantity must be numeric.";

                continue;
            }

            $quantity = (float)$quantityValue;

            if ($quantity <= 0) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Quantity must be greater than zero.";

                continue;
            }


            /* =====================================================
             * SCHEDULE ID
             * ===================================================== */

            if (
                $scheduleIdValue === null ||
                trim((string)$scheduleIdValue) === ''
            ) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Schedule ID is required.";

                continue;
            }

            if (!is_numeric($scheduleIdValue)) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Schedule ID must be numeric.";

                continue;
            }

            $scheduleId = (int)$scheduleIdValue;


            /* =====================================================
             * FIND SCHEDULE
             *
             * ONLY:
             * id
             * program_code
             * start_date
             *
             * NO program_name
             * NO subsidy_item
             * ===================================================== */

            $schedule =
                $schedulesTable
                    ->find()
                    ->select([
                        'id',
                        'program_code',
                        'start_date'
                    ])
                    ->where([
                        'id' => $scheduleId
                    ])
                    ->first();


            if (!$schedule) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Schedule ID " .
                    "{$scheduleId} does not exist.";

                continue;
            }


            /* =====================================================
             * CHECK PROGRAM CODE
             *
             * Seed Subsidy should use the appropriate
             * program code stored in schedules.program_code.
             *
             * Change SD- if your Seed Subsidy codes use
             * another format.
             * ===================================================== */

            $programCode = trim(
                (string)$schedule->program_code
            );

            if ($programCode === '') {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Schedule ID " .
                    "{$scheduleId} has no program code.";

                continue;
            }


            /* =====================================================
             * RECEIVED DATE
             * ===================================================== */

            $receivedDate = null;

            /*
             * Blank and N/A are allowed.
             */

            if (
                $receivedDateValue !== null &&
                trim((string)$receivedDateValue) !== '' &&
                strtoupper(
                    trim((string)$receivedDateValue)
                ) !== 'N/A'
            ) {

                try {

                    /*
                     * Excel numeric date
                     */

                    if (
                        is_numeric($receivedDateValue) &&
                        \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime(
                            $receivedDateCell
                        )
                    ) {

                        $dateTime =
                            \PhpOffice\PhpSpreadsheet\Shared\Date
                                ::excelToDateTimeObject(
                                    $receivedDateValue
                                );

                        $receivedDate =
                            new \Cake\I18n\FrozenDate(
                                $dateTime->format('Y-m-d')
                            );

                    } else {

                        /*
                         * Text date
                         */

                        $dateString =
                            trim((string)$receivedDateValue);

                        $formats = [
                            'Y-m-d',
                            'm/d/Y',
                            'd/m/Y',
                            'm-d-Y',
                            'd-m-Y',
                            'Y/m/d',
                            'F j, Y',
                            'M j, Y',
                            'Y-m-d H:i:s',
                            'Y-m-d H:i'
                        ];

                        $parsedDate = false;

                        foreach ($formats as $format) {

                            $dateTime =
                                \DateTime::createFromFormat(
                                    $format,
                                    $dateString
                                );

                            if ($dateTime !== false) {
                                $parsedDate = $dateTime;
                                break;
                            }
                        }


                        /*
                         * Fallback
                         */

                        if ($parsedDate === false) {

                            try {

                                $parsedDate =
                                    new \DateTime(
                                        $dateString
                                    );

                            } catch (\Exception $e) {

                                $parsedDate = false;
                            }
                        }


                        if ($parsedDate === false) {

                            throw new \Exception(
                                'Invalid date format.'
                            );
                        }

                        $receivedDate =
                            new \Cake\I18n\FrozenDate(
                                $parsedDate->format('Y-m-d')
                            );
                    }

                } catch (\Throwable $e) {

                    $result['failed']++;

                    $result['errors'][] =
                        "Row {$row}: Invalid Received Date.";

                    continue;
                }
            }


            /* =====================================================
             * STATUS
             * ===================================================== */

            $statusKey = strtolower(
                preg_replace(
                    '/[\s_-]+/',
                    ' ',
                    trim($statusValue)
                )
            );

            $statusMap = [

                'received' =>
                    'Received',

                'not received' =>
                    'Not Received',

                'rescheduled' =>
                    'Re-Scheduled',

                're scheduled' =>
                    'Re-Scheduled',

                're-scheduled' =>
                    'Re-Scheduled',

                'cancelled' =>
                    'Cancelled',

                'canceled' =>
                    'Cancelled'
            ];


            if (
                $statusKey === '' ||
                !isset($statusMap[$statusKey])
            ) {

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: Invalid Status " .
                    "'{$statusValue}'.";

                continue;
            }

            $status =
                $statusMap[$statusKey];


            /* =====================================================
             * DUPLICATE KEY
             *
             * farmer_id + schedule_id
             * ===================================================== */

            $duplicateKey =
                $farmerId . '_' . $scheduleId;


            /* -----------------------------------------------------
             * Duplicate within Excel
             * ----------------------------------------------------- */

            if (isset($seen[$duplicateKey])) {

                $result['skipped']++;

                $result['errors'][] =
                    "Row {$row}: Duplicate record for " .
                    "farmer '{$farmerValue}' and " .
                    "Schedule ID {$scheduleId}. Skipped.";

                continue;
            }


            /* -----------------------------------------------------
             * Duplicate already in database
             * ----------------------------------------------------- */

            $existingRecord =
                $recordsTable
                    ->find()
                    ->where([
                        'farmer_id' => $farmerId,
                        'schedule_id' => $scheduleId
                    ])
                    ->first();


            if ($existingRecord) {

                $result['skipped']++;

                $result['errors'][] =
                    "Row {$row}: Record already exists for " .
                    "farmer '{$farmerValue}' and " .
                    "Schedule ID {$scheduleId}. Skipped.";

                $seen[$duplicateKey] = true;

                continue;
            }


            /* =====================================================
             * CREATE RECORD
             * ===================================================== */

            $record =
                $recordsTable->newEmptyEntity();

            $record->farmer_id =
                $farmerId;

            /*
             * Always Seed Subsidy.
             */

            $record->subsidy_item =
                'Seed Subsidy';

            $record->quantity =
                $quantity;

            /*
             * Blank/N/A = NULL
             */

            $record->received_date =
                $receivedDate;

            $record->status =
                $status;

            /*
             * Schedule relationship.
             */

            $record->schedule_id =
                $scheduleId;


            /*
             * Confirmed timestamp.
             */

            if ($status === 'Received') {

                $record->confirmed_at =
                    \Cake\I18n\FrozenTime::now();

            } else {

                $record->confirmed_at =
                    null;
            }


            /* =====================================================
             * SAVE
             * ===================================================== */

            if (!$recordsTable->save($record)) {

                $errors =
                    $record->getErrors();

                $errorMessage =
                    'Unable to save record.';

                if (!empty($errors)) {

                    $errorMessage .=
                        ' ' .
                        json_encode($errors);
                }

                $result['failed']++;

                $result['errors'][] =
                    "Row {$row}: {$errorMessage}";

                continue;
            }


            /*
             * Mark combination as processed.
             */

            $seen[$duplicateKey] = true;

            $result['imported']++;
        }


        /* =========================================================
         * 8. DELETE TEMP FILE
         * ========================================================= */

        if (
            $tempPath !== null &&
            file_exists($tempPath)
        ) {
            unlink($tempPath);
        }


        /* =========================================================
         * 9. FINAL MESSAGE
         * ========================================================= */

        $result['success'] = true;

        if ($result['imported'] > 0) {

            $result['message'] =
                'Excel import completed successfully. ' .
                $result['imported'] .
                ' record(s) imported.';

        } elseif ($result['skipped'] > 0) {

            $result['message'] =
                'Excel import completed. ' .
                'No new records were imported. ' .
                'Existing or duplicate records were skipped.';

        } else {

            $result['message'] =
                'Excel import completed, but no records were imported.';
        }


    } catch (\Throwable $e) {

        if (
            $tempPath !== null &&
            file_exists($tempPath)
        ) {
            unlink($tempPath);
        }

        $result['success'] = false;

        $result['message'] =
            'Unable to import the Excel file: ' .
            $e->getMessage();
    }


    /* =============================================================
     * 10. SAVE RESULT
     * ============================================================= */

    $session->write(
        'ExcelImportResult',
        $result
    );


    /* =============================================================
     * 11. REDIRECT
     * ============================================================= */

    return $this->redirect([
        'action' => 'index'
    ]);
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
