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
                'program_name',
                'start_date',
                'start_time'
            ])
            ->order([
                'program_name' => 'ASC',
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
                $schedule->program_name;
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

    /**
     * ================================================================
     * UPLOAD EXCEL
     * ================================================================
     *
     * Excel format:
     *
     * A = Farmer
     * B = Subsidy Item
     * C = Quantity
     * D = Received Date
     * E = Status
     * F = Schedule ID
     *
     * Duplicate:
     *
     * farmer_id + schedule_id
     */
    public function uploadExcel()
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();

        /*
         * Remove previous result
         */
        $session->delete(
            'ExcelImportResult'
        );

        /*
         * ============================================================
         * GET UPLOADED FILE
         * ============================================================
         */
        $uploadedFile =
            $this->request->getData('excel_file');

        if (
            !$uploadedFile ||
            !is_object($uploadedFile)
        ) {
            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'Please select an Excel file.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * UPLOAD ERROR
         * ============================================================
         */
        if (
            method_exists(
                $uploadedFile,
                'getError'
            ) &&
            $uploadedFile->getError() !==
                UPLOAD_ERR_OK
        ) {
            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'There was a problem uploading the Excel file.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * FILE EXTENSION
         * ============================================================
         */
        $originalName = method_exists(
            $uploadedFile,
            'getClientFilename'
        )
            ? $uploadedFile->getClientFilename()
            : '';

        $extension = strtolower(
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
            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'Invalid file type. Please upload an .xlsx or .xls file.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        /*
         * ============================================================
         * TEMPORARY FILE
         * ============================================================
         */
        $tmpPath = null;

        if (
            method_exists(
                $uploadedFile,
                'getStream'
            )
        ) {
            $stream = $uploadedFile->getStream();

            $tmpPath = $stream->getMetadata(
                'uri'
            );
        }

        if (
            !$tmpPath &&
            method_exists(
                $uploadedFile,
                'getTmpName'
            )
        ) {
            $tmpPath =
                $uploadedFile->getTmpName();
        }

        if (
            !$tmpPath ||
            !file_exists($tmpPath)
        ) {
            $session->write(
                'ExcelImportResult',
                [
                    'type' => 'error',
                    'success' => 0,
                    'failed' => 0,
                    'errors' => [
                        'Unable to access the uploaded Excel file.'
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        try {
            $this->loadModel('Farmers');
            $this->loadModel('Schedules');

            /*
             * ========================================================
             * LOAD EXCEL
             * ========================================================
             */
            $spreadsheet =
                IOFactory::load($tmpPath);

            $worksheet =
                $spreadsheet->getActiveSheet();

            $rows =
                $worksheet->toArray(
                    null,
                    true,
                    true,
                    true
                );

            /*
             * Remove header
             */
            if (!empty($rows)) {
                array_shift($rows);
            }

            /*
             * Remove completely empty rows
             */
            $rows = array_values(
                array_filter(
                    $rows,
                    function ($row) {
                        foreach ($row as $value) {
                            if (
                                $value !== null &&
                                trim((string)$value) !== ''
                            ) {
                                return true;
                            }
                        }

                        return false;
                    }
                )
            );

            if (empty($rows)) {
                $session->write(
                    'ExcelImportResult',
                    [
                        'type' => 'error',
                        'success' => 0,
                        'failed' => 0,
                        'errors' => [
                            'The Excel file does not contain any records.'
                        ]
                    ]
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            /*
             * ========================================================
             * FIRST PASS
             *
             * Validate all rows before inserting.
             * ========================================================
             */
            $validRows = [];
            $errors = [];
            $duplicateRows = [];

            foreach (
                $rows as $excelRowNumber => $row
            ) {
                /*
                 * Header = row 1
                 * First data row = row 2
                 */
                $rowNumber =
                    $excelRowNumber + 2;

                /*
                 * ====================================================
                 * A = FARMER
                 * ====================================================
                 */
                $farmerName =
                    isset($row['A'])
                        ? trim((string)$row['A'])
                        : '';

                if ($farmerName === '') {
                    $errors[] =
                        "Row {$rowNumber}: Farmer name is required.";

                    continue;
                }

                /*
                 * ====================================================
                 * B = SUBSIDY ITEM
                 * ====================================================
                 */
                $subsidyItem =
                    isset($row['B'])
                        ? trim((string)$row['B'])
                        : '';

                if ($subsidyItem === '') {
                    $errors[] =
                        "Row {$rowNumber}: Subsidy Item is required.";

                    continue;
                }

                /*
                 * Only Seed Subsidy
                 */
                if (
                    strtolower($subsidyItem) !==
                    strtolower('Seed Subsidy')
                ) {
                    $errors[] =
                        "Row {$rowNumber}: Only Seed Subsidy records can be uploaded.";

                    continue;
                }

                /*
                 * ====================================================
                 * C = QUANTITY
                 * ====================================================
                 */
                $quantityValue =
                    $row['C'] ?? null;

                if (
                    $quantityValue === null ||
                    trim((string)$quantityValue) === ''
                ) {
                    $errors[] =
                        "Row {$rowNumber}: Quantity is required.";

                    continue;
                }

                if (!is_numeric($quantityValue)) {
                    $errors[] =
                        "Row {$rowNumber}: Quantity must be numeric.";

                    continue;
                }

                $quantity =
                    (float)$quantityValue;

                if ($quantity <= 0) {
                    $errors[] =
                        "Row {$rowNumber}: Quantity must be greater than zero.";

                    continue;
                }

                /*
                 * ====================================================
                 * D = RECEIVED DATE
                 * ====================================================
                 */
                $receivedDateValue =
                    $row['D'] ?? null;

                $receivedDate = null;

                if (
                    $receivedDateValue !== null &&
                    trim((string)$receivedDateValue) !== ''
                ) {
                    try {
                        /*
                         * If Excel numeric date
                         */
                        if (
                            is_numeric(
                                $receivedDateValue
                            )
                        ) {
                            $receivedDate =
                                FrozenDate::createFromTimestamp(
                                    ExcelDate::excelToTimestamp(
                                        (float)$receivedDateValue
                                    )
                                );
                        } else {
                            /*
                             * Normal date string
                             */
                            $receivedDate =
                                new FrozenDate(
                                    trim(
                                        (string)$receivedDateValue
                                    )
                                );
                        }
                    } catch (\Throwable $e) {
                        $errors[] =
                            "Row {$rowNumber}: Invalid received date.";

                        continue;
                    }
                }

                /*
                 * ====================================================
                 * E = STATUS
                 * ====================================================
                 */
                $statusValue =
                    isset($row['E'])
                        ? trim((string)$row['E'])
                        : '';

                $status =
                    strtolower($statusValue);

                /*
                 * Normalize separators
                 */
                $status = str_replace(
                    [
                        '-',
                        '_'
                    ],
                    ' ',
                    $status
                );

                $status =
                    preg_replace(
                        '/\s+/',
                        ' ',
                        $status
                    );

                switch ($status) {
                    case 'received':

                        $normalizedStatus =
                            'Received';

                        break;

                    case 'not received':
                    case 'notreceived':

                        $normalizedStatus =
                            'Not Received';

                        break;

                    case 're scheduled':
                    case 'rescheduled':

                        $normalizedStatus =
                            'Re-Scheduled';

                        break;

                    case 'cancelled':
                    case 'canceled':

                        $normalizedStatus =
                            'Cancelled';

                        break;

                    case '':

                        $normalizedStatus =
                            'Not Received';

                        break;

                    default:

                        $errors[] =
                            "Row {$rowNumber}: Invalid status '{$statusValue}'.";

                        continue 2;
                }

                /*
                 * ====================================================
                 * F = SCHEDULE ID
                 * ====================================================
                 */
                $scheduleIdValue =
                    $row['F'] ?? null;

                if (
                    $scheduleIdValue === null ||
                    trim((string)$scheduleIdValue) === ''
                ) {
                    $errors[] =
                        "Row {$rowNumber}: Schedule ID is required.";

                    continue;
                }

                if (!is_numeric($scheduleIdValue)) {
                    $errors[] =
                        "Row {$rowNumber}: Schedule ID must be numeric.";

                    continue;
                }

                $scheduleId =
                    (int)$scheduleIdValue;

                /*
                 * ====================================================
                 * FIND SCHEDULE
                 * ====================================================
                 */
                $schedule =
                    $this->Schedules->find()
                        ->where([
                            'Schedules.id' =>
                                $scheduleId
                        ])
                        ->first();

                if (!$schedule) {
                    $errors[] =
                        "Row {$rowNumber}: Schedule ID {$scheduleId} was not found.";

                    continue;
                }

                /*
                 * Verify Seed Subsidy schedule
                 */
                if (
                    empty($schedule->program_name) ||
                    strtolower(
                        trim(
                            (string)$schedule->program_name
                        )
                    ) !==
                    strtolower('Seed Subsidy')
                ) {
                    $errors[] =
                        "Row {$rowNumber}: Schedule ID {$scheduleId} is not a Seed Subsidy schedule.";

                    continue;
                }

                /*
                 * ====================================================
                 * FIND FARMER
                 * ====================================================
                 */
                $nameParts =
                    preg_split(
                        '/\s+/',
                        $farmerName
                    );

                $firstName = '';
                $lastName = '';

                if (!empty($nameParts)) {
                    $firstName =
                        trim(
                            (string)$nameParts[0]
                        );

                    if (
                        count($nameParts) > 1
                    ) {
                        $lastName =
                            trim(
                                implode(
                                    ' ',
                                    array_slice(
                                        $nameParts,
                                        1
                                    )
                                )
                            );
                    }
                }

                if ($lastName === '') {
                    $errors[] =
                        "Row {$rowNumber}: Farmer name '{$farmerName}' must contain a last name.";

                    continue;
                }

                /*
                 * Case-insensitive search
                 */
                $farmer =
                    $this->Farmers->find()
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

                if (!$farmer) {
                    $errors[] =
                        "Row {$rowNumber}: Farmer '{$farmerName}' was not found.";

                    continue;
                }

                /*
                 * ====================================================
                 * DUPLICATE CHECK
                 *
                 * Same farmer + same schedule
                 * = duplicate.
                 * ====================================================
                 */
                $existingRecord =
                    $this->Records->find()
                        ->where([
                            'Records.farmer_id' =>
                                $farmer->id,

                            'Records.schedule_id' =>
                                $scheduleId
                        ])
                        ->first();

                if ($existingRecord) {
                    $duplicateRows[] = [
                        'row' =>
                            $rowNumber,

                        'farmer' =>
                            $farmerName,

                        'schedule_id' =>
                            $scheduleId
                    ];

                    continue;
                }

                /*
                 * ====================================================
                 * STORE VALID DATA
                 * ====================================================
                 */
                $validRows[] = [
                    'row_number' =>
                        $rowNumber,

                    'farmer_id' =>
                        $farmer->id,

                    'schedule_id' =>
                        $scheduleId,

                    'subsidy_item' =>
                        $subsidyItem,

                    'quantity' =>
                        $quantity,

                    'received_date' =>
                        $receivedDate,

                    'status' =>
                        $normalizedStatus
                ];
            }

            /*
             * ========================================================
             * ALL ROWS ARE DUPLICATES
             * ========================================================
             */
            if (
                empty($validRows) &&
                !empty($duplicateRows) &&
                empty($errors)
            ) {
                $duplicateCount =
                    count($duplicateRows);

                $session->write(
                    'ExcelImportResult',
                    [
                        'type' =>
                            'duplicate',

                        'success' =>
                            0,

                        'failed' =>
                            $duplicateCount,

                        'errors' => [
                            'The data in this Excel are already uploaded.'
                        ]
                    ]
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            /*
             * ========================================================
             * SECOND PASS
             *
             * Save only new records.
             * ========================================================
             */
            $successCount = 0;

            $failedCount =
                count($errors);

            foreach (
                $validRows as $data
            ) {
                try {
                    $record =
                        $this->Records
                            ->newEmptyEntity();

                    $record =
                        $this->Records
                            ->patchEntity(
                                $record,
                                [
                                    'farmer_id' =>
                                        $data['farmer_id'],

                                    'schedule_id' =>
                                        $data['schedule_id'],

                                    'subsidy_item' =>
                                        $data['subsidy_item'],

                                    'quantity' =>
                                        $data['quantity'],

                                    'received_date' =>
                                        $data['received_date'],

                                    'status' =>
                                        $data['status']
                                ]
                            );

                    if (
                        $this->Records
                            ->save($record)
                    ) {
                        $successCount++;
                    } else {
                        $failedCount++;

                        $errors[] =
                            "Row {$data['row_number']}: Record could not be saved.";
                    }
                } catch (\Throwable $e) {
                    $failedCount++;

                    $errors[] =
                        "Row {$data['row_number']}: " .
                        $e->getMessage();
                }
            }

            /*
             * ========================================================
             * DUPLICATES IN MIXED FILE
             * ========================================================
             */
            if (!empty($duplicateRows)) {
                $failedCount +=
                    count($duplicateRows);

                foreach (
                    $duplicateRows as $duplicate
                ) {
                    $errors[] =
                        "Row {$duplicate['row']}: " .
                        "{$duplicate['farmer']} already has a record " .
                        "for Schedule ID {$duplicate['schedule_id']}.";
                }
            }

            /*
             * ========================================================
             * RESULT TYPE
             * ========================================================
             */
            if (
                $successCount > 0 &&
                $failedCount === 0
            ) {
                $resultType =
                    'success';
            } elseif (
                $successCount > 0 &&
                $failedCount > 0
            ) {
                $resultType =
                    'partial';
            } else {
                $resultType =
                    'error';
            }

            /*
             * ========================================================
             * SAVE RESULT
             * ========================================================
             */
            $session->write(
                'ExcelImportResult',
                [
                    'type' =>
                        $resultType,

                    'success' =>
                        $successCount,

                    'failed' =>
                        $failedCount,

                    'errors' =>
                        $errors
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        } catch (\Throwable $e) {
            /*
             * Log detailed error
             */
            \Cake\Log\Log::error(
                'RecordsController::uploadExcel(): ' .
                $e->getMessage() .
                "\n" .
                $e->getTraceAsString()
            );

            /*
             * User-friendly result
             */
            $session->write(
                'ExcelImportResult',
                [
                    'type' =>
                        'error',

                    'success' =>
                        0,

                    'failed' =>
                        0,

                    'errors' => [
                        'Unable to import the Excel file: ' .
                        $e->getMessage()
                    ]
                ]
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }
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

            $programName =
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
                        $schedule->program_name
                    )
                ) {
                    $programName =
                        $schedule->program_name;
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

                                'program_name' =>
                                    $programName,

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
     *
     * URL:
     *
     * /Records/getFarmerRecords/{farmerId}
     *
     * Returns:
     *
     * farmer
     * records
     *
     * The schedule table is joined using:
     *
     * Records.schedule_id = Schedules.id
     *
     * Distribution date comes from:
     *
     * Schedules.start_date
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

            /*
             * ========================================================
             * FIND ALL RECORDS FOR FARMER
             *
             * JOIN SCHEDULES
             * ========================================================
             */
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
                        'program_name' =>
                            'Schedules.program_name',

                        /*
                         * IMPORTANT:
                         *
                         * There is NO
                         * Schedules.distribution_date.
                         *
                         * start_date is the distribution date.
                         */
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
