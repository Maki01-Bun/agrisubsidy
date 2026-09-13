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

    $this->loadComponent('Flash');

    $this->loadModel('Farmers');

    // Keep your existing models/components here
}   
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

    /*
     * ============================================================
     * GET UPLOADED FILE
     * ============================================================
     */

    $file = $this->request->getData('excel_file');

    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {

        $this->Flash->error(
            'Please select a valid Excel file.'
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * VALIDATE FILE EXTENSION
     * ============================================================
     */

    $extension = strtolower(
        pathinfo(
            $file->getClientFilename(),
            PATHINFO_EXTENSION
        )
    );

    if (!in_array(
        $extension,
        ['xlsx', 'xls'],
        true
    )) {

        $this->Flash->error(
            'Only XLSX and XLS files are allowed.'
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * GET TEMPORARY FILE PATH
     * ============================================================
     */

    $tempPath = $file
        ->getStream()
        ->getMetadata('uri');


    /*
     * ============================================================
     * LOAD EXCEL FILE
     * ============================================================
     */

    try {

        $spreadsheet = IOFactory::load($tempPath);

        $worksheet = $spreadsheet->getActiveSheet();

    } catch (\Throwable $e) {

        $this->Flash->error(
            'The Excel file could not be read.'
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * FIND LATEST SEED SUBSIDY SCHEDULE
     *
     * IMPORTANT:
     * We search the Schedules table directly.
     * ============================================================
     */

   $schedule = $this->Records
    ->find()
    ->where([
        'Records.subsidy_item' => 'Seed Subsidy'
    ])
    ->contain([
        'Schedules'
    ])
    ->order([
        'Records.id' => 'DESC'
    ])
    ->first();


    /*
     * ============================================================
     * NO SEED SUBSIDY SCHEDULE
     * ============================================================
     */

    if (!$schedule) {

        $this->Flash->error(
            'No Seed Subsidy schedule was found.'
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /*
     * ============================================================
     * IMPORT COUNTERS
     * ============================================================
     */

    $successCount = 0;

    $skipCount = 0;

    $errorCount = 0;

    $errors = [];


    /*
     * ============================================================
     * GET HIGHEST EXCEL ROW
     * ============================================================
     */

    $highestRow =
        $worksheet->getHighestRow();


    /*
     * ============================================================
     * STATUS NORMALIZATION MAP
     * ============================================================
     */

    $statusMap = [

        'received' =>
            'Received',

        'not received' =>
            'Not Received',

        'not_received' =>
            'Not Received',

        'not-received' =>
            'Not Received',

        're-scheduled' =>
            'Re-Scheduled',

        'rescheduled' =>
            'Re-Scheduled',

        're_scheduled' =>
            'Re-Scheduled',

        're scheduled' =>
            'Re-Scheduled',

        'cancelled' =>
            'Cancelled',

        'canceled' =>
            'Cancelled'
    ];


    /*
     * ============================================================
     * PROCESS EXCEL ROWS
     *
     * Excel structure:
     *
     * A = Farmer Name
     * B = Quantity
     * C = Received Date
     * D = Status
     * ============================================================
     */

    for ($row = 2; $row <= $highestRow; $row++) {

        /*
         * --------------------------------------------------------
         * READ FARMER NAME
         * --------------------------------------------------------
         */

        $farmerName = trim(
            (string)$worksheet
                ->getCell("A{$row}")
                ->getValue()
        );


        /*
         * --------------------------------------------------------
         * READ QUANTITY
         * --------------------------------------------------------
         */

        $quantity = $worksheet
            ->getCell("B{$row}")
            ->getValue();


        /*
         * --------------------------------------------------------
         * READ RECEIVED DATE
         * --------------------------------------------------------
         */

        $receivedDateValue =
            $worksheet
                ->getCell("C{$row}")
                ->getValue();


        /*
         * --------------------------------------------------------
         * READ STATUS
         * --------------------------------------------------------
         */

        $status = trim(
            (string)$worksheet
                ->getCell("D{$row}")
                ->getValue()
        );


        /*
         * ========================================================
         * SKIP COMPLETELY BLANK ROW
         * ========================================================
         */

        if (
            $farmerName === '' &&
            ($quantity === null || $quantity === '') &&
            ($receivedDateValue === null || $receivedDateValue === '') &&
            $status === ''
        ) {

            continue;
        }


        /*
         * ========================================================
         * FARMER NAME IS REQUIRED
         * ========================================================
         */

        if ($farmerName === '') {

            $errorCount++;

            $errors[] =
                "Row {$row}: Farmer name is required.";

            continue;
        }


        /*
         * ========================================================
         * FIND FARMER
         *
         * Excel:
         * "Juan Dela Cruz"
         *
         * Database:
         * first_name = Juan
         * last_name  = Dela Cruz
         *
         * We compare the complete name in PHP.
         * ========================================================
         */

       $farmer = $this->Farmers
    ->find()
    ->where([
        'Farmers.first_name IS NOT' => null,
        'Farmers.last_name IS NOT' => null
    ])
    ->all()
    ->filter(
        function ($farmer) use ($farmerName) {

            $fullName = trim(
                $farmer->first_name . ' ' . $farmer->last_name
            );

            return strcasecmp(
                $fullName,
                $farmerName
            ) === 0;
        }
    )
    ->first();


        /*
         * ========================================================
         * FARMER NOT FOUND
         * ========================================================
         */

        if (!$farmer) {

            $errorCount++;

            $errors[] =
                "Row {$row}: Farmer '{$farmerName}' was not found.";

            continue;
        }


        /*
         * ========================================================
         * CHECK DUPLICATE
         *
         * One farmer can only have one record
         * for the same schedule.
         *
         * farmer_id + schedule_id
         * ========================================================
         */

        $existing = $this->Records
            ->find()
            ->where([
                'Records.farmer_id' =>
                    $farmer->id,

                'Records.schedule_id' =>
                    $schedule->id
            ])
            ->first();


        /*
         * ========================================================
         * DUPLICATE FOUND
         * ========================================================
         */

        if ($existing) {

            $skipCount++;

            continue;
        }


        /*
         * ========================================================
         * NORMALIZE STATUS
         * ========================================================
         */

        $statusKey = strtolower(
            trim($status)
        );


        if (isset($statusMap[$statusKey])) {

            $normalizedStatus =
                $statusMap[$statusKey];

        } elseif ($status === '') {

            /*
             * Blank status defaults to Not Received.
             */

            $normalizedStatus =
                'Not Received';

        } else {

            /*
             * Preserve unknown status,
             * but format it nicely.
             */

            $normalizedStatus =
                ucwords(
                    strtolower($status)
                );
        }


        /*
         * ========================================================
         * PARSE RECEIVED DATE
         *
         * Blank dates ARE ALLOWED.
         * ========================================================
         */

        $receivedDate = null;


        /*
         * Check whether Excel date is actually populated.
         */

        if (
            $receivedDateValue !== null &&
            trim((string)$receivedDateValue) !== ''
        ) {

            try {

                /*
                 * ------------------------------------------------
                 * EXCEL SERIAL DATE
                 *
                 * Example:
                 * 45900
                 * ------------------------------------------------
                 */

                if (is_numeric($receivedDateValue)) {

                    $dateObj =
                        ExcelDate::excelToDateTimeObject(
                            (float)$receivedDateValue
                        );


                    $receivedDate =
                        FrozenDate::createFromDate(
                            (int)$dateObj->format('Y'),
                            (int)$dateObj->format('m'),
                            (int)$dateObj->format('d')
                        );


                } else {

                    /*
                     * --------------------------------------------
                     * TEXT DATE
                     *
                     * Example:
                     * 09/20/2026
                     * 2026-09-20
                     * September 20, 2026
                     * --------------------------------------------
                     */

                    $dateString = trim(
                        (string)$receivedDateValue
                    );


                    $receivedDate =
                        FrozenDate::parseDate(
                            $dateString
                        );
                }

            } catch (\Throwable $e) {

                $errorCount++;

                $errors[] =
                    "Row {$row}: Invalid received date.";

                continue;
            }
        }


        /*
         * ========================================================
         * VALIDATE QUANTITY
         * ========================================================
         */

        $quantityValue = 0;


        if (
            $quantity !== null &&
            trim((string)$quantity) !== ''
        ) {

            if (!is_numeric($quantity)) {

                $errorCount++;

                $errors[] =
                    "Row {$row}: Quantity must be a valid number.";

                continue;
            }


            $quantityValue =
                (float)$quantity;


            if ($quantityValue < 0) {

                $errorCount++;

                $errors[] =
                    "Row {$row}: Quantity cannot be negative.";

                continue;
            }
        }


        /*
         * ========================================================
         * CREATE NEW RECORD
         * ========================================================
         */

        $record =
            $this->Records->newEmptyEntity();


        /*
         * ========================================================
         * RECORD DATA
         * ========================================================
         */

        $recordData = [

            /*
             * Farmer foreign key
             */
            'farmer_id' =>
                $farmer->id,


            /*
             * Schedule foreign key
             */
            'schedule_id' =>
                $schedule->id,


            /*
             * Fixed subsidy item
             */
            'subsidy_item' =>
                'Seed Subsidy',


            /*
             * Quantity
             */
            'quantity' =>
                $quantityValue,


            /*
             * Received date
             *
             * NULL if Excel date is blank.
             */
            'received_date' =>
                $receivedDate,


            /*
             * Normalized status
             */
            'status' =>
                $normalizedStatus
        ];


        /*
         * ========================================================
         * PATCH ENTITY
         * ========================================================
         */

        $record =
            $this->Records->patchEntity(
                $record,
                $recordData
            );


        /*
         * ========================================================
         * SAVE RECORD
         * ========================================================
         */

        if ($this->Records->save($record)) {

            $successCount++;

            $this->AuditLogger->logActivity(
                'created',
                'Seed Subsidy record imported from Excel',
                $record,
                [
                    'source' => 'Excel import',
                    'excel_row' => $row,
                    'farmer_id' => $farmer->id,
                    'farmer_name' => $farmerName,
                    'schedule_id' => $schedule->id,
                    'quantity' => $record->quantity,
                    'received_date' =>
                        $receivedDate
                            ? $receivedDate->format('Y-m-d')
                            : null,
                    'status' => $normalizedStatus
                ]
            );

        } else {

            $errorCount++;

            $validationErrors = $record->getErrors();

            $errorMessage = "Row {$row}: Record could not be saved.";

            if (!empty($validationErrors)) {

                $details = [];

                foreach ($validationErrors as $field => $fieldErrors) {

                    if (is_array($fieldErrors)) {
                        foreach ($fieldErrors as $message) {
                            $details[] = "{$field}: {$message}";
                        }
                    } else {
                        $details[] = "{$field}: {$fieldErrors}";
                    }
                }

                if (!empty($details)) {
                    $errorMessage .= ' ' . implode(' | ', $details);
                }
            }

            $errors[] = $errorMessage;
        }
    }


    /*
     * ============================================================
     * STORE IMPORT RESULT IN SESSION
     * ============================================================
     */

    $this->getRequest()
        ->getSession()
        ->write(
            'ExcelImportResult',
            [

                'success' =>
                    $successCount,

                'skipped' =>
                    $skipCount,

                'errors' =>
                    $errorCount,

                'error_details' =>
                    $errors
            ]
        );


    /*
     * ============================================================
     * FLASH SUCCESS MESSAGE
     * ============================================================
     */

    if ($successCount > 0) {

        $this->Flash->success(
            "{$successCount} record(s) imported successfully."
        );
    }


    /*
     * ============================================================
     * FLASH DUPLICATE MESSAGE
     * ============================================================
     */

    if ($skipCount > 0) {

        $this->Flash->warning(
            "{$skipCount} duplicate record(s) were skipped."
        );
    }


    /*
     * ============================================================
     * FLASH ERROR MESSAGE
     * ============================================================
     */

    if ($errorCount > 0) {

        $this->Flash->error(
            "{$errorCount} record(s) could not be imported."
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
