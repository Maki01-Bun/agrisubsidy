<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Schedules Controller
 *
 * @property \App\Model\Table\SchedulesTable $Schedules
 * @method \App\Model\Entity\Schedule[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchedulesController extends AppController
{
/**
     * Initialize
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Schedules');
    }

    /**
     * Index
     *
     * GET /api/Schedules
     */
    public function index()
    {
        $schedules = $this->Schedules
            ->find()
            ->order([
                'Schedules.start_date' => 'ASC',
                'Schedules.start_time' => 'ASC'
            ])
            ->all();

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'status' => 'success',
                    'data' => $schedules
                ])
            );
    }

    /**
     * View
     *
     * GET /api/Schedules/view/{id}
     */
    public function view($id = null)
    {
        try {

            if (empty($id)) {

                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'status' => 'error',
                            'message' => 'Schedule ID is required.'
                        ])
                    );
            }

            $schedule = $this->Schedules->get($id);

            return $this->response
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'success',
                        'data' => $schedule
                    ])
                );

        } catch (\Throwable $e) {

            Log::error(
                'View Schedule Error: ' .
                $e->getMessage()
            );

            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' => 'Unable to retrieve schedule.'
                    ])
                );
        }
    }

    /**
     * Get Schedules
     *
     * GET /api/Schedules/getSchedules
     */
    public function getSchedules()
    {
        try {

            $schedules = $this->Schedules
                ->find()
                ->order([
                    'Schedules.start_date' => 'ASC',
                    'Schedules.start_time' => 'ASC'
                ])
                ->all();

            return $this->response
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'success',
                        'data' => $schedules
                    ])
                );

        } catch (\Throwable $e) {

            Log::error(
                'Get Schedules Error: ' .
                $e->getMessage()
            );

            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' => 'Unable to retrieve schedules.'
                    ])
                );
        }
    }

    /**
     * Add Schedule
     *
     * POST /api/Schedules/add
     */
    public function add()
    {
        if (!$this->request->is('post')) {

            return $this->response
                ->withStatus(405)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' => 'Invalid request method.'
                    ])
                );
        }

        try {

            $data = $this->request->getData();

            /*
             * =====================================================
             * VALIDATE DATE AND TIME
             * =====================================================
             */

            $validationResult =
                $this->validateScheduleDateTime($data);

            if ($validationResult !== true) {

                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'status' => 'error',
                            'message' => $validationResult
                        ])
                    );
            }

            /*
             * =====================================================
             * NEW ENTITY
             * =====================================================
             */

            $schedule =
                $this->Schedules->newEmptyEntity();

            /*
             * =====================================================
             * DEFAULT STATUS
             * =====================================================
             *
             * New schedules should always begin as Scheduled.
             */

            if (
                !isset($data['status']) ||
                empty($data['status'])
            ) {

                $data['status'] = 'Scheduled';

            }

            /*
             * =====================================================
             * PATCH ENTITY
             * =====================================================
             */

            $schedule =
                $this->Schedules->patchEntity(
                    $schedule,
                    $data
                );

            /*
             * =====================================================
             * SAVE
             * =====================================================
             */

            if (
                $this->Schedules->save(
                    $schedule
                )
            ) {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'status' => 'success',
                            'message' =>
                                'The schedule has been saved.',
                            'data' => $schedule
                        ])
                    );
            }

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'The schedule could not be saved.',
                        'errors' =>
                            $schedule->getErrors()
                    ])
                );

        } catch (\Throwable $e) {

            Log::error(
                'Add Schedule Error: ' .
                $e->getMessage()
            );

            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'Server error while saving schedule.',
                        'error' =>
                            $e->getMessage()
                    ])
                );
        }
    }

    /**
     * Edit Schedule
     *
     * PATCH|POST|PUT /api/Schedules/edit/{id}
     */
    public function edit($id = null)
{
    if (!$this->request->is(['patch', 'post', 'put'])) {

        return $this->response
            ->withStatus(405)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'status' => 'error',
                    'message' => 'Invalid request method.'
                ])
            );
    }

    try {

        /*
         * =====================================================
         * GET SCHEDULE ID
         * =====================================================
         */

        if (empty($id)) {

            $id =
                $this->request->getData('id');
        }

        if (empty($id)) {

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'Schedule ID is required.'
                    ])
                );
        }


        /*
         * =====================================================
         * FIND SCHEDULE
         * =====================================================
         */

        $schedule =
            $this->Schedules->get($id);


        /*
         * =====================================================
         * CURRENT STATUS
         * =====================================================
         */

        $currentStatus =
            strtolower(
                trim(
                    (string)(
                        $schedule->status ?? ''
                    )
                )
            );


        /*
         * =====================================================
         * PREVENT EDITING CANCELLED
         * =====================================================
         */

        if ($currentStatus === 'cancelled') {

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'Cancelled schedules cannot be edited.'
                    ])
                );
        }


        /*
         * =====================================================
         * PREVENT RESCHEDULING COMPLETED
         * =====================================================
         */

        if ($currentStatus === 'completed') {

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'Completed schedules cannot be re-scheduled.'
                    ])
                );
        }


        /*
         * =====================================================
         * GET REQUEST DATA
         * =====================================================
         */

        $data =
            $this->request->getData();


        /*
         * =====================================================
         * VALIDATE DATE / TIME
         * =====================================================
         */

        $validationResult =
            $this->validateScheduleDateTime(
                $data
            );

        if ($validationResult !== true) {

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' => $validationResult
                    ])
                );
        }


        /*
         * =====================================================
         * SAVE ORIGINAL DATES
         * =====================================================
         *
         * IMPORTANT:
         * We must get these BEFORE patchEntity().
         */

        $originalStartDate = '';
        $originalEndDate = '';


        /*
         * ORIGINAL START DATE
         */

        if (
            $schedule->start_date
            instanceof \DateTimeInterface
        ) {

            $originalStartDate =
                $schedule->start_date
                    ->format('Y-m-d');

        } elseif (
            !empty($schedule->start_date)
        ) {

            $originalStartDate =
                substr(
                    (string)$schedule->start_date,
                    0,
                    10
                );
        }


        /*
         * ORIGINAL END DATE
         */

        if (
            $schedule->end_date
            instanceof \DateTimeInterface
        ) {

            $originalEndDate =
                $schedule->end_date
                    ->format('Y-m-d');

        } elseif (
            !empty($schedule->end_date)
        ) {

            $originalEndDate =
                substr(
                    (string)$schedule->end_date,
                    0,
                    10
                );
        }


        /*
         * =====================================================
         * DO NOT ALLOW REQUEST TO DIRECTLY CHANGE STATUS
         * =====================================================
         *
         * Status will be controlled by this method.
         */

        unset($data['status']);


        /*
         * =====================================================
         * PATCH ENTITY
         * =====================================================
         */

        $schedule =
            $this->Schedules->patchEntity(
                $schedule,
                $data
            );


        /*
         * =====================================================
         * GET NEW START DATE
         * =====================================================
         */

        $newStartDate = '';

        if (
            $schedule->start_date
            instanceof \DateTimeInterface
        ) {

            $newStartDate =
                $schedule->start_date
                    ->format('Y-m-d');

        } elseif (
            !empty($schedule->start_date)
        ) {

            $newStartDate =
                substr(
                    (string)$schedule->start_date,
                    0,
                    10
                );
        }


        /*
         * =====================================================
         * GET NEW END DATE
         * =====================================================
         */

        $newEndDate = '';

        if (
            $schedule->end_date
            instanceof \DateTimeInterface
        ) {

            $newEndDate =
                $schedule->end_date
                    ->format('Y-m-d');

        } elseif (
            !empty($schedule->end_date)
        ) {

            $newEndDate =
                substr(
                    (string)$schedule->end_date,
                    0,
                    10
                );
        }


        /*
         * =====================================================
         * CHECK IF DATE CHANGED
         * =====================================================
         */

        $dateChanged =
            (
                $originalStartDate !== $newStartDate ||
                $originalEndDate !== $newEndDate
            );


        /*
         * =====================================================
         * HANDLE RE-SCHEDULING
         * =====================================================
         */

        if ($dateChanged) {

            /*
             * Save the schedule as Re-Scheduled.
             */

            $schedule->status =
                'Re-Scheduled';
        }


        /*
         * =====================================================
         * SAVE
         * =====================================================
         */

        if (
            $this->Schedules->save(
                $schedule
            )
        ) {

            /*
             * =================================================
             * AUDIT LOG FOR RE-SCHEDULING
             * =================================================
             */

            if (
                $dateChanged &&
                isset($this->AuditLogger) &&
                $this->AuditLogger
            ) {

                try {

                    $this->AuditLogger->logActivity(
                        'Re-Scheduled Schedule',
                        'Schedules',
                        $schedule->id,
                        [
                            'program_code' =>
                                $schedule->program_code,

                            'program_name' =>
                                $schedule->program_name,

                            'old_start_date' =>
                                $originalStartDate,

                            'old_end_date' =>
                                $originalEndDate,

                            'new_start_date' =>
                                $newStartDate,

                            'new_end_date' =>
                                $newEndDate,

                            'old_status' =>
                                $currentStatus,

                            'new_status' =>
                                'Re-Scheduled'
                        ]
                    );

                } catch (\Throwable $auditError) {

                    /*
                     * The schedule was already saved.
                     *
                     * Audit failure should NOT make the
                     * rescheduling operation fail.
                     */

                    Log::error(
                        'Schedule re-scheduling audit log failed: ' .
                        $auditError->getMessage()
                    );
                }
            }


            /*
             * =================================================
             * SUCCESS MESSAGE
             * =================================================
             */

            $message =
                $dateChanged
                    ? 'Schedule re-scheduled successfully.'
                    : 'Schedule updated successfully.';


            return $this->response
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'success',
                        'message' => $message,
                        'rescheduled' => $dateChanged,
                        'date_changed' => $dateChanged,
                        'data' => $schedule
                    ])
                );
        }


        /*
         * =====================================================
         * SAVE FAILED
         * =====================================================
         */

        return $this->response
            ->withStatus(400)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'status' => 'error',
                    'message' =>
                        'The schedule could not be updated.',
                    'errors' =>
                        $schedule->getErrors()
                ])
            );


    } catch (\Throwable $e) {

        Log::error(
            'Edit/Re-Schedule Schedule Error: ' .
            $e->getMessage()
        );

        return $this->response
            ->withStatus(500)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'status' => 'error',
                    'message' =>
                        'Server error while updating schedule.',
                    'error' =>
                        $e->getMessage()
                ])
            );
    }
}

    /**
     * Delete Schedule
     *
     * POST|DELETE /api/Schedules/delete/{id}
     */
    public function delete($id = null)
    {
        $this->request->allowMethod([
            'post',
            'delete'
        ]);

        try {

            if (empty($id)) {

                $id =
                    $this->request->getData('id');

            }

            if (empty($id)) {

                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'status' => 'error',
                            'message' =>
                                'Schedule ID is required.'
                        ])
                    );
            }

            $schedule =
                $this->Schedules->get($id);

            if (
                $this->Schedules->delete(
                    $schedule
                )
            ) {

                return $this->response
                    ->withType('application/json')
                    ->withStringBody(
                        json_encode([
                            'status' => 'success',
                            'message' =>
                                'The schedule has been deleted.'
                        ])
                    );
            }

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'The schedule could not be deleted.',
                        'errors' =>
                            $schedule->getErrors()
                    ])
                );

        } catch (\Throwable $e) {

            Log::error(
                'Delete Schedule Error: ' .
                $e->getMessage()
            );

            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'status' => 'error',
                        'message' =>
                            'Server error while deleting schedule.',
                        'error' =>
                            $e->getMessage()
                    ])
                );
        }
    }

    /**
     * =========================================================
     * CANCEL SCHEDULE
     * =========================================================
     *
     * POST /api/Schedules/cancel/{id}
     *
     * This is the function used by the Cancel button
     * in the schedules page.
     */
    public function cancel($id = null)
    {
        $this->request->allowMethod([
            'post'
        ]);

        $this->response =
            $this->response
                ->withType('application/json');

        try {

            /*
             * =====================================================
             * GET SCHEDULE ID
             * =====================================================
             */

            if (empty($id)) {

                $id =
                    $this->request->getData('id');

            }

            /*
             * =====================================================
             * GET ID FROM JSON BODY
             * =====================================================
             */

            if (empty($id)) {

                $rawBody =
                    (string)$this->request->getBody();

                if (!empty($rawBody)) {

                    $json =
                        json_decode(
                            $rawBody,
                            true
                        );

                    if (
                        is_array($json) &&
                        !empty($json['id'])
                    ) {

                        $id =
                            $json['id'];

                    }
                }
            }

            /*
             * =====================================================
             * VALIDATE ID
             * =====================================================
             */

            if (empty($id)) {

                return $this->response
                    ->withStatus(400)
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'status' => 'error',
                            'message' =>
                                'Schedule ID is required.'
                        ])
                    );
            }

            /*
             * =====================================================
             * FIND SCHEDULE
             * =====================================================
             */

            $schedule =
                $this->Schedules
                    ->find()
                    ->where([
                        'Schedules.id' => $id
                    ])
                    ->first();

            /*
             * =====================================================
             * NOT FOUND
             * =====================================================
             */

            if (!$schedule) {

                return $this->response
                    ->withStatus(404)
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'status' => 'error',
                            'message' =>
                                'Schedule not found.'
                        ])
                    );
            }

            /*
             * =====================================================
             * CURRENT STATUS
             * =====================================================
             */

            $currentStatus =
                strtolower(
                    trim(
                        (string)(
                            $schedule->status ?? ''
                        )
                    )
                );

            /*
             * =====================================================
             * COMPLETED
             * =====================================================
             *
             * A completed schedule cannot be cancelled.
             */

            if ($currentStatus === 'completed') {

                return $this->response
                    ->withStatus(400)
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'status' => 'error',
                            'message' =>
                                'Completed schedules cannot be cancelled.'
                        ])
                    );
            }

            /*
             * =====================================================
             * ALREADY CANCELLED
             * =====================================================
             */

            if ($currentStatus === 'cancelled') {

                return $this->response
                    ->withStatus(400)
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'status' => 'error',
                            'message' =>
                                'This schedule is already cancelled.'
                        ])
                    );
            }

            /*
             * =====================================================
             * SAVE OLD VALUES
             * =====================================================
             */

            $oldStatus =
                $schedule->status ?? null;

            $programCode =
                $schedule->program_code ?? null;

            $programName =
                $schedule->program_name ?? null;

            /*
             * =====================================================
             * CHANGE STATUS
             * =====================================================
             */

            $schedule->status =
                'Cancelled';

            /*
             * =====================================================
             * SAVE TO DATABASE
             * =====================================================
             */

            if (
                !$this->Schedules->save(
                    $schedule
                )
            ) {

                Log::error(
                    'Failed to cancel schedule ID ' .
                    $id .
                    ': ' .
                    json_encode(
                        $schedule->getErrors()
                    )
                );

                return $this->response
                    ->withStatus(400)
                    ->withStringBody(
                        json_encode([
                            'success' => false,
                            'status' => 'error',
                            'message' =>
                                'Failed to save cancelled status.',
                            'errors' =>
                                $schedule->getErrors()
                        ])
                    );
            }

            /*
             * =====================================================
             * AUDIT LOG
             * =====================================================
             *
             * IMPORTANT:
             *
             * logActivity() argument #4 MUST BE AN ARRAY.
             *
             * DO NOT USE:
             *
             * 'Schedule was cancelled.'
             *
             * as argument #4.
             */

            if (
                isset($this->AuditLogger) &&
                $this->AuditLogger
            ) {

                try {

                    $this->AuditLogger->logActivity(
                        'Cancelled Schedule',
                        'Schedules',
                        $schedule->id,
                        [
                            'program_code' =>
                                $programCode,

                            'program_name' =>
                                $programName,

                            'old_status' =>
                                $oldStatus,

                            'new_status' =>
                                'Cancelled'
                        ]
                    );

                } catch (\Throwable $auditError) {

                    /*
                     * The database update already succeeded.
                     *
                     * Therefore an AuditLogger error must NOT
                     * make the cancellation fail.
                     */

                    Log::error(
                        'Schedule cancellation audit log failed: ' .
                        $auditError->getMessage()
                    );
                }
            }

            /*
             * =====================================================
             * SUCCESS
             * =====================================================
             */

            return $this->response
                ->withStatus(200)
                ->withStringBody(
                    json_encode([
                        'success' => true,
                        'status' => 'success',
                        'message' =>
                            'Schedule cancelled successfully.',
                        'id' =>
                            $schedule->id,
                        'schedule_status' =>
                            $schedule->status
                    ])
                );

        } catch (\Throwable $e) {

            Log::error(
                'Cancel Schedule Error: ' .
                $e->getMessage()
            );

            return $this->response
                ->withStatus(500)
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'status' => 'error',
                        'message' =>
                            'Server error while cancelling schedule.',
                        'error' =>
                            $e->getMessage()
                    ])
                );
        }
    }

    /**
     * =========================================================
     * VALIDATE SCHEDULE DATE / TIME
     * =========================================================
     */
    private function validateScheduleDateTime(array $data)
    {
        $startDate =
            $data['start_date'] ?? '';

        $endDate =
            $data['end_date'] ?? '';

        $startTime =
            $data['start_time'] ?? '';

        $endTime =
            $data['end_time'] ?? '';

        /*
         * =====================================================
         * START DATE
         * =====================================================
         */

        if (empty($startDate)) {

            return 'Start date is required.';
        }

        /*
         * =====================================================
         * END DATE
         * =====================================================
         */

        if (empty($endDate)) {

            return 'End date is required.';
        }

        /*
         * =====================================================
         * NORMALIZE DATE
         * =====================================================
         */

        if (
            $startDate instanceof
            \DateTimeInterface
        ) {

            $startDate =
                $startDate->format(
                    'Y-m-d'
                );

        } else {

            $startDate =
                trim(
                    (string)$startDate
                );

        }

        if (
            $endDate instanceof
            \DateTimeInterface
        ) {

            $endDate =
                $endDate->format(
                    'Y-m-d'
                );

        } else {

            $endDate =
                trim(
                    (string)$endDate
                );

        }

        /*
         * =====================================================
         * PARSE START DATE
         * =====================================================
         */

        $startDateObj =
            \DateTime::createFromFormat(
                '!Y-m-d',
                $startDate
            );

        /*
         * =====================================================
         * PARSE END DATE
         * =====================================================
         */

        $endDateObj =
            \DateTime::createFromFormat(
                '!Y-m-d',
                $endDate
            );

        /*
         * =====================================================
         * FALLBACK START DATE
         * =====================================================
         */

        if (!$startDateObj) {

            $timestamp =
                strtotime(
                    $startDate
                );

            if ($timestamp === false) {

                return 'Invalid start date.';
            }

            $startDateObj =
                new \DateTime(
                    date(
                        'Y-m-d',
                        $timestamp
                    )
                );
        }

        /*
         * =====================================================
         * FALLBACK END DATE
         * =====================================================
         */

        if (!$endDateObj) {

            $timestamp =
                strtotime(
                    $endDate
                );

            if ($timestamp === false) {

                return 'Invalid end date.';
            }

            $endDateObj =
                new \DateTime(
                    date(
                        'Y-m-d',
                        $timestamp
                    )
                );
        }

        /*
         * =====================================================
         * NORMALIZE
         * =====================================================
         */

        $startDateString =
            $startDateObj->format(
                'Y-m-d'
            );

        $endDateString =
            $endDateObj->format(
                'Y-m-d'
            );

        /*
         * =====================================================
         * END DATE CANNOT BE BEFORE START DATE
         * =====================================================
         */

        if (
            $endDateObj->getTimestamp() <
            $startDateObj->getTimestamp()
        ) {

            return
                'Invalid schedule: End date (' .
                $endDateString .
                ') cannot be earlier than start date (' .
                $startDateString .
                ').';
        }

        /*
         * =====================================================
         * SAME DATE
         * =====================================================
         */

        if (
            $startDateString ===
            $endDateString
        ) {

            /*
             * Start time required
             */

            if (empty($startTime)) {

                return 'Start time is required.';
            }

            /*
             * End time required
             */

            if (empty($endTime)) {

                return 'End time is required.';
            }

            /*
             * Normalize start time
             */

            if (
                $startTime instanceof
                \DateTimeInterface
            ) {

                $startTime =
                    $startTime->format(
                        'H:i:s'
                    );

            } else {

                $startTime =
                    trim(
                        (string)$startTime
                    );
            }

            /*
             * Normalize end time
             */

            if (
                $endTime instanceof
                \DateTimeInterface
            ) {

                $endTime =
                    $endTime->format(
                        'H:i:s'
                    );

            } else {

                $endTime =
                    trim(
                        (string)$endTime
                    );
            }

            /*
             * Parse start time
             */

            $startTimeObj =
                \DateTime::createFromFormat(
                    '!H:i:s',
                    $startTime
                );

            /*
             * Parse end time
             */

            $endTimeObj =
                \DateTime::createFromFormat(
                    '!H:i:s',
                    $endTime
                );

            /*
             * Try H:i
             */

            if (!$startTimeObj) {

                $startTimeObj =
                    \DateTime::createFromFormat(
                        '!H:i',
                        $startTime
                    );
            }

            if (!$endTimeObj) {

                $endTimeObj =
                    \DateTime::createFromFormat(
                        '!H:i',
                        $endTime
                    );
            }

            /*
             * Invalid start time
             */

            if (!$startTimeObj) {

                return 'Invalid start time.';
            }

            /*
             * Invalid end time
             */

            if (!$endTimeObj) {

                return 'Invalid end time.';
            }

            /*
             * End time cannot be earlier
             */

            if (
                $endTimeObj->getTimestamp() <
                $startTimeObj->getTimestamp()
            ) {

                return
                    'Invalid schedule: End time (' .
                    $endTimeObj->format('H:i:s') .
                    ') cannot be earlier than start time (' .
                    $startTimeObj->format('H:i:s') .
                    ') when the dates are the same.';
            }
        }

        return true;
    }
}
