<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Feedback Controller
 *
 * @method \App\Model\Entity\Feedback[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FeedbacksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

 public function index()
{
    // =========================================================
    // LOAD MODELS
    // =========================================================

    $this->loadModel('Farmers');
    $this->loadModel('Farms');
    $this->loadModel('Schedules');
    $this->loadModel('Records');
    $this->loadModel('Evaluations');


    // =========================================================
    // INITIALIZE
    // =========================================================

    $farms = [];
    $farmSizes = [];
    $schedules = [];
    $scheduleFarmOptions = [];
    $farmerName = '';


    // =========================================================
    // GET LOGGED-IN USER
    // =========================================================

    $user = $this->request
        ->getSession()
        ->read('Auth.User');

    if (
        empty($user) ||
        empty($user['id'])
    ) {

        $this->Flash->error(
            'Unable to identify the logged-in user.'
        );

        return $this->redirect([
            'controller' => 'Pages',
            'action' => 'display',
            'home'
        ]);
    }

    $userId = (int)$user['id'];


    // =========================================================
    // FIND FARMER
    // =========================================================

    $farmer = $this->Farmers->find()
        ->where([
            'Farmers.user_id' => $userId
        ])
        ->first();

    if (!$farmer) {

        $this->Flash->error(
            'No farmer record found for the logged-in user.'
        );

        $this->set([
            'farms' => [],
            'farmSizes' => [],
            'farmerName' => '',
            'schedules' => [],
            'scheduleFarmOptions' => [],
            'rice_type' => [
                0 => 'Hybrid',
                1 => 'Inbred'
            ]
        ]);

        return;
    }


    // =========================================================
    // FARMER NAME
    // =========================================================

    $firstName = trim(
        (string)($farmer->first_name ?? '')
    );

    $middleName = trim(
        (string)($farmer->middle_name ?? '')
    );

    $lastName = trim(
        (string)($farmer->last_name ?? '')
    );

    $farmerName = trim(
        implode(
            ' ',
            array_filter([
                $firstName,
                $middleName,
                $lastName
            ])
        )
    );


    // =========================================================
    // FARMER ADDRESS
    // =========================================================

    $farmerAddress = trim(
        (string)($farmer->address ?? '')
    );


    // =========================================================
    // LOCATION NORMALIZATION
    // =========================================================

    $normalizeLocation = function ($value): string {

        $value = strtolower(
            trim((string)$value)
        );

        // Replace punctuation with spaces
        $value = str_replace(
            [
                ',',
                '.',
                '-',
                '_'
            ],
            ' ',
            $value
        );

        // Remove "barangay"
        $value = preg_replace(
            '/\bbarangay\b/',
            ' ',
            $value
        );

        // Remove "brgy"
        $value = preg_replace(
            '/\bbrgy\b/',
            ' ',
            $value
        );

        // Normalize spaces
        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        );

        return trim($value);
    };


    $normalizedFarmerAddress =
        $normalizeLocation(
            $farmerAddress
        );


    // =========================================================
    // GET FARMS
    // =========================================================

    $farmsData = $this->Farms->find()
        ->select([
            'id',
            'farmer_id',
            'farm_size'
        ])
        ->where([
            'Farms.farmer_id' => $farmer->id
        ])
        ->order([
            'Farms.id' => 'ASC'
        ])
        ->all();


    // =========================================================
    // GET CANCELLED SCHEDULES
    //
    // Schedules does not have a status column.
    // Cancellation is determined from Records.status.
    // =========================================================

    $cancelledScheduleIds = [];

    $records = $this->Records->find()
        ->select([
            'schedule_id',
            'status'
        ])
        ->where([
            'Records.farmer_id' => $farmer->id
        ])
        ->all();

    foreach ($records as $record) {

        $status = strtolower(
            trim(
                (string)($record->status ?? '')
            )
        );

        if (
            in_array(
                $status,
                [
                    'cancelled',
                    'canceled'
                ],
                true
            )
        ) {

            if (
                !empty(
                    $record->schedule_id
                )
            ) {

                $cancelledScheduleIds[
                    (int)$record->schedule_id
                ] = true;
            }
        }
    }


    // =========================================================
    // GET ALL SCHEDULES
    // =========================================================

    $allSchedules = $this->Schedules->find()
        ->order([
            'Schedules.start_date' => 'ASC'
        ])
        ->all();


    // =========================================================
    // PHILIPPINE TIMEZONE
    // =========================================================

    $timezone = new \DateTimeZone(
        'Asia/Manila'
    );

    $now = new \DateTime(
        'now',
        $timezone
    );


    // =========================================================
    // FARM NUMBER
    // =========================================================

    $farmNumber = 1;


    // =========================================================
    // LOOP THROUGH FARMERS' FARMS
    // =========================================================

    foreach ($farmsData as $farm) {

        $farmId = (int)$farm->id;


        // =====================================================
        // FARM SIZE
        // =====================================================

        $farmSize = (float)(
            $farm->farm_size ?? 0
        );


        // =====================================================
        // PREPARE ELIGIBLE SCHEDULES FOR THIS FARM
        // =====================================================

        $farmSchedules = [];


        // =====================================================
        // LOOP THROUGH SCHEDULES
        // =====================================================

        foreach ($allSchedules as $schedule) {

            $scheduleId = (int)$schedule->id;

            // Exclude schedules whose own status is still Scheduled or cancelled.
            $scheduleStatus = strtolower(trim((string)($schedule->status ?? '')));
            if (in_array($scheduleStatus, ['scheduled', 'cancelled', 'canceled'], true)) {
                continue;
            }

            // =================================================
            // CHECK CANCELLED
            // =================================================

            if (
                isset(
                    $cancelledScheduleIds[
                        $scheduleId
                    ]
                )
            ) {

                continue;
            }


            // =================================================
            // SCHEDULE BARANGAY
            // =================================================

            $scheduleBarangay = trim(
                (string)(
                    $schedule->barangay ?? ''
                )
            );

            if (
                $scheduleBarangay === ''
            ) {

                continue;
            }


            // =================================================
            // NORMALIZE BARANGAY
            // =================================================

            $normalizedScheduleBarangay =
                $normalizeLocation(
                    $scheduleBarangay
                );


            if (
                $normalizedFarmerAddress === '' ||
                $normalizedScheduleBarangay === ''
            ) {

                continue;
            }


            // =================================================
            // CHECK BARANGAY MATCH
            // =================================================

            if (
                !str_contains(
                    $normalizedFarmerAddress,
                    $normalizedScheduleBarangay
                )
            ) {

                continue;
            }


            // =================================================
            // END DATE
            // =================================================

            $endDate =
                $schedule->end_date ?? null;

            if (
                empty($endDate)
            ) {

                continue;
            }


            // =================================================
            // BUILD END DATE/TIME
            // =================================================

            try {

                // ---------------------------------------------
                // END DATE
                // ---------------------------------------------

                if (
                    $endDate instanceof
                    \DateTimeInterface
                ) {

                    $endDateTime =
                        new \DateTime(
                            $endDate->format(
                                'Y-m-d'
                            ),
                            $timezone
                        );

                } else {

                    $endDateTime =
                        new \DateTime(
                            (string)$endDate,
                            $timezone
                        );
                }


                // ---------------------------------------------
                // END TIME
                // ---------------------------------------------

                $endTime =
                    $schedule->end_time ?? null;


                if (
                    $endTime instanceof
                    \DateTimeInterface
                ) {

                    $endDateTime->setTime(
                        (int)$endTime->format('H'),
                        (int)$endTime->format('i'),
                        (int)$endTime->format('s')
                    );

                } elseif (
                    !empty($endTime)
                ) {

                    $timeString =
                        trim(
                            (string)$endTime
                        );


                    if (
                        $timeString !== ''
                    ) {

                        $parsedTime =
                            new \DateTime(
                                $timeString,
                                $timezone
                            );

                        $endDateTime->setTime(
                            (int)$parsedTime->format('H'),
                            (int)$parsedTime->format('i'),
                            (int)$parsedTime->format('s')
                        );

                    } else {

                        $endDateTime->setTime(
                            23,
                            59,
                            59
                        );
                    }

                } else {

                    $endDateTime->setTime(
                        23,
                        59,
                        59
                    );
                }

            } catch (\Throwable $e) {

                continue;
            }


            // =================================================
            // SCHEDULE MUST ALREADY BE COMPLETED
            // =================================================

            if (
                $endDateTime > $now
            ) {

                continue;
            }


            // =================================================
            // CHECK FARM + SCHEDULE DUPLICATE
            //
            // IMPORTANT:
            // Same farmer can evaluate:
            //
            // Farm 1 + SD-1
            // Farm 1 + SD-2
            // Farm 2 + SD-1
            //
            // But cannot evaluate the exact same pair twice.
            // =================================================

            $alreadyEvaluated =
                $this->Evaluations->find()
                    ->where([
                        'Evaluations.farmer_id' =>
                            $farmer->id,

                        'Evaluations.farm_id' =>
                            $farmId,

                        'Evaluations.schedule_id' =>
                            $scheduleId
                    ])
                    ->first();

            if (
                $alreadyEvaluated
            ) {

                continue;
            }


            // =================================================
            // PROGRAM CODE
            // =================================================

            $programCode = trim(
                (string)(
                    $schedule->program_code ?? ''
                )
            );


            // =================================================
            // PROGRAM NAME
            // =================================================

            $programName = trim(
                (string)(
                    $schedule->program_name ?? ''
                )
            );


            // =================================================
            // DISPLAY DATE
            // =================================================

            $displayDate =
                $endDateTime->format(
                    'M d, Y'
                );


            // =================================================
            // DISPLAY TIME
            // =================================================

            $displayTime =
                $endDateTime->format(
                    'h:i A'
                );


            // =================================================
            // BUILD LABEL
            // =================================================

            $label = '';


            if (
                $programCode !== ''
            ) {

                $label =
                    $programCode;
            }


            if (
                $programName !== ''
            ) {

                if (
                    $label !== ''
                ) {

                    $label .= ' - ';
                }

                $label .=
                    $programName;
            }


            $label .=
                ' | ' .
                $scheduleBarangay .
                ' | Completed ' .
                $displayDate .
                ' ' .
                $displayTime;


            // =================================================
            // ADD SCHEDULE TO FARM
            // =================================================

            $farmSchedules[
                $scheduleId
            ] = $label;
        }


        // =====================================================
        // IF NO VALID SCHEDULES REMAIN
        //
        // HIDE THE FARM
        // =====================================================

        if (
            empty($farmSchedules)
        ) {

            continue;
        }


        // =====================================================
        // ADD FARM
        // =====================================================

        $farms[$farmId] =
            'Farm ' .
            $farmNumber .
            ': ' .
            number_format(
                $farmSize,
                2
            ) .
            ' ha';


        // =====================================================
        // STORE FARM SIZE
        //
        // IMPORTANT FOR JAVASCRIPT
        // =====================================================

        $farmSizes[$farmId] =
            (float)$farmSize;


        // =====================================================
        // STORE GLOBAL SCHEDULES AND VALID FARM/SCHEDULE PAIRS
        // =====================================================

        foreach ($farmSchedules as $eligibleScheduleId => $eligibleLabel) {
            if (!isset($schedules[$eligibleScheduleId])) {
                $schedules[$eligibleScheduleId] = $eligibleLabel;
            }

            $scheduleFarmOptions[$eligibleScheduleId][$farmId] = true;
        }


        $farmNumber++;
    }


    // =========================================================
    // RICE TYPE
    //
    // IMPORTANT NUMERICAL MAPPING:
    //
    // 0 = HYBRID
    // 1 = INBRED
    // =========================================================

    $rice_type = [
        0 => 'Hybrid',
        1 => 'Inbred'
    ];


    // =========================================================
    // SEND TO VIEW
    // =========================================================

    $this->set([
        'farms' =>
            $farms,

        'farmSizes' =>
            $farmSizes,

        'farmerName' =>
            $farmerName,

        'schedules' =>
            $schedules,

        'scheduleFarmOptions' =>
            $scheduleFarmOptions,

        'rice_type' =>
            $rice_type
    ]);
}
public function survey()
{
    // =====================================================
    // ONLY ALLOW POST
    // =====================================================

    if (!$this->request->is('post')) {

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    // =====================================================
    // LOAD MODELS
    // =====================================================

    $this->loadModel('Evaluations');
    $this->loadModel('Feedbacks');
    $this->loadModel('Farmers');
    $this->loadModel('Farms');
    $this->loadModel('Schedules');
    $this->loadModel('Records');

    // =====================================================
    // GET LOGGED-IN USER
    // =====================================================

    $user = $this->request
        ->getSession()
        ->read('Auth.User');

    if (
        empty($user) ||
        empty($user['id'])
    ) {

        $this->Flash->error(
            'User session not found.'
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    // =====================================================
    // FIND FARMER
    // =====================================================

    $farmer = $this->Farmers->find()
        ->where([
            'Farmers.user_id' => $user['id']
        ])
        ->first();

    if (!$farmer) {

        $this->Flash->error(
            'Farmer record not found.'
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    // =====================================================
    // FORM DATA
    // =====================================================

    $formData =
        $this->request->getData();

    // =====================================================
    // SCHEDULE IS REQUIRED
    // =====================================================

    $scheduleId =
        $formData['schedule_id'] ?? null;

    if (
        $scheduleId === null ||
        $scheduleId === ''
    ) {

        $this->Flash->error(
            'Please select a completed distribution schedule.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    if (is_array($scheduleId)) {

        $this->Flash->error(
            'Invalid schedule.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    $scheduleId =
        (int)$scheduleId;

    if ($scheduleId <= 0) {

        $this->Flash->error(
            'Please select a valid distribution schedule.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // FIND SCHEDULE
    // =====================================================

    $schedule =
        $this->Schedules->find()
            ->where([
                'Schedules.id' =>
                    $scheduleId
            ])
            ->first();

    if (!$schedule) {

        $this->Flash->error(
            'Selected schedule was not found.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // CHECK SCHEDULE STATUS
    // =====================================================

    $scheduleStatus =
        strtolower(
            trim(
                (string)(
                    $schedule->status ?? ''
                )
            )
        );

    if (
        in_array($scheduleStatus, ['scheduled', 'cancelled', 'canceled'], true)
    ) {

        $this->Flash->error(
            'This schedule is not completed or has been cancelled and cannot be evaluated.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // CHECK CANCELLATION FROM RECORDS
    // =====================================================

    $cancelledRecord =
        $this->Records->find()
            ->where([
                'Records.farmer_id' =>
                    $farmer->id,

                'Records.schedule_id' =>
                    $scheduleId
            ])
            ->where(function ($exp) {

                return $exp->or([
                    $exp->eq(
                        $this->Records->aliasField('status'),
                        'Cancelled'
                    ),
                    $exp->eq(
                        $this->Records->aliasField('status'),
                        'Canceled'
                    )
                ]);
            })
            ->first();

    if ($cancelledRecord) {

        $this->Flash->error(
            'This schedule has been cancelled and cannot be evaluated.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // CHECK BARANGAY
    // =====================================================

    $farmerAddress =
        trim(
            (string)(
                $farmer->address ?? ''
            )
        );

    if ($farmerAddress === '') {

        $this->Flash->error(
            'Your address is not available.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // NORMALIZE LOCATION
    // =====================================================

    $normalizeLocation =
        function ($value) {

            $value =
                strtolower(
                    trim(
                        (string)$value
                    )
                );

            // Replace punctuation
            $value =
                preg_replace(
                    '/[,.\-_]+/',
                    ' ',
                    $value
                );

            // Remove barangay prefixes
            $value =
                preg_replace(
                    '/\bbarangay\b/',
                    ' ',
                    $value
                );

            $value =
                preg_replace(
                    '/\bbrgy\b/',
                    ' ',
                    $value
                );

            // Normalize spaces
            $value =
                preg_replace(
                    '/\s+/',
                    ' ',
                    $value
                );

            return trim($value);
        };

    $farmerAddressNormalized =
        $normalizeLocation(
            $farmerAddress
        );

    $scheduleBarangay =
        trim(
            (string)(
                $schedule->barangay ?? ''
            )
        );

    if (
        $scheduleBarangay === ''
    ) {

        $this->Flash->error(
            'The selected schedule has no barangay assigned.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    $scheduleBarangayNormalized =
        $normalizeLocation(
            $scheduleBarangay
        );

    // =====================================================
    // BARANGAY MATCH
    // =====================================================

    $barangayMatched =
        strpos(
            $farmerAddressNormalized,
            $scheduleBarangayNormalized
        ) !== false;

    if (!$barangayMatched) {

        $this->Flash->error(
            'This schedule is not available for your barangay.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // CHECK END DATE/TIME
    // =====================================================

    if (
        empty($schedule->end_date) ||
        empty($schedule->end_time)
    ) {

        $this->Flash->error(
            'This schedule does not have a valid ending date and time.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // PHILIPPINE TIMEZONE
    // =====================================================

    $timezone =
        new \DateTimeZone(
            'Asia/Manila'
        );

    $now =
        new \DateTime(
            'now',
            $timezone
        );

    // =====================================================
    // BUILD SCHEDULE END DATETIME
    // =====================================================

    try {

        if (
            is_object($schedule->end_date) &&
            method_exists(
                $schedule->end_date,
                'format'
            )
        ) {

            $endDate =
                $schedule->end_date->format(
                    'Y-m-d'
                );

        } else {

            $endDate =
                date(
                    'Y-m-d',
                    strtotime(
                        (string)$schedule->end_date
                    )
                );
        }

        if (
            is_object($schedule->end_time) &&
            method_exists(
                $schedule->end_time,
                'format'
            )
        ) {

            $endTime =
                $schedule->end_time->format(
                    'H:i:s'
                );

        } else {

            $endTime =
                date(
                    'H:i:s',
                    strtotime(
                        (string)$schedule->end_time
                    )
                );
        }

        $scheduleEnd =
            new \DateTime(
                $endDate . ' ' . $endTime,
                $timezone
            );

    } catch (\Throwable $e) {

        $this->Flash->error(
            'Unable to determine the schedule ending time.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // SCHEDULE MUST BE COMPLETED
    // =====================================================

    if (
        $scheduleEnd > $now
    ) {

        $this->Flash->error(
            'This schedule has not been completed yet. You can submit feedback after the schedule has ended.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // FARM IS REQUIRED
    // =====================================================

    $farmId =
        $formData['farm_id'] ?? null;

    if (
        $farmId === null ||
        $farmId === ''
    ) {

        $this->Flash->error(
            'Please select a farm.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    if (is_array($farmId)) {

        $this->Flash->error(
            'Invalid farm.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    $farmId =
        (int)$farmId;

    if ($farmId <= 0) {

        $this->Flash->error(
            'Please select a valid farm.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // FIND FARM
    // =====================================================

    $farm =
        $this->Farms->find()
            ->where([
                'Farms.id' =>
                    $farmId,

                'Farms.farmer_id' =>
                    $farmer->id
            ])
            ->first();

    if (!$farm) {

        $this->Flash->error(
            'Selected farm was not found or does not belong to you.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // FARM SIZE
    // =====================================================

    $farmSize =
        (float)(
            $farm->farm_size ?? 0
        );

    if ($farmSize <= 0) {

        $this->Flash->error(
            'Farm size must be greater than zero.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // CHECK FARM + SCHEDULE DUPLICATE
    // =====================================================

    $existingEvaluation =
        $this->Evaluations->find()
            ->where([
                'Evaluations.farmer_id' =>
                    $farmer->id,

                'Evaluations.farm_id' =>
                    $farmId,

                'Evaluations.schedule_id' =>
                    $scheduleId
            ])
            ->first();

    if ($existingEvaluation) {

        $this->Flash->error(
            'You have already submitted feedback for this farm and distribution schedule.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // RICE TYPE / SEED TYPE
    //
    // 0 = HYBRID
    // 1 = INBRED
    //
    // HYBRID = FARM SIZE × 5.5
    // INBRED = FARM SIZE × 6.0
    // =====================================================

    $riceTypeInput =
        $formData['rice_type']
        ?? $formData['seed_type']
        ?? $formData['planting_type']
        ?? null;

    if (
        $riceTypeInput === null ||
        $riceTypeInput === ''
    ) {

        $this->Flash->error(
            'Please select a rice type.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    if (is_array($riceTypeInput)) {

        $this->Flash->error(
            'Invalid rice type.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    $riceTypeValue =
        strtolower(
            trim(
                (string)$riceTypeInput
            )
        );

    // =====================================================
    // CALCULATE AVERAGE YIELD
    // =====================================================

    switch ($riceTypeValue) {

        // -------------------------------------------------
        // 0 = HYBRID
        // -------------------------------------------------

        case '0':
        case 'hybrid':

            $riceType =
                'Hybrid';

            $averageYield =
                $farmSize * 5.5;

            break;


        // -------------------------------------------------
        // 1 = INBRED
        // -------------------------------------------------

        case '1':
        case 'inbred':

            $riceType =
                'Inbred';

            $averageYield =
                $farmSize * 6.0;

            break;


        // -------------------------------------------------
        // INVALID
        // -------------------------------------------------

        default:

            $this->Flash->error(
                'Please select a valid rice type.'
            );

            return $this->redirect(
                $this->referer()
            );
    }

    // =====================================================
    // VALIDATE AVERAGE YIELD
    // =====================================================

    $averageYield =
        (float)$averageYield;

    if ($averageYield <= 0) {

        $this->Flash->error(
            'Unable to calculate average yield.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // CROP YIELD
    // =====================================================

    $cropYieldAfter =
        (float)(
            $formData['crop_yield_after']
            ?? 0
        );

    if ($cropYieldAfter < 0) {

        $this->Flash->error(
            'Crop yield cannot be negative.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // SELLING PRICE
    // =====================================================

    $sellingPrice =
        (float)(
            $formData['selling_price']
            ?? 0
        );

    if ($sellingPrice < 0) {

        $this->Flash->error(
            'Selling price cannot be negative.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // SUBSIDY RECEIVED
    // =====================================================

    $subsidyInput =
        $formData['subsidy_received']
        ?? 'No';

    if (is_array($subsidyInput)) {

        $this->Flash->error(
            'Invalid subsidy value.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    if (is_string($subsidyInput)) {

        $normalizedSubsidy =
            strtolower(
                trim(
                    $subsidyInput
                )
            );

        if (
            $normalizedSubsidy === 'yes' ||
            $normalizedSubsidy === '1'
        ) {

            $subsidyReceived = 1;

        } else {

            $subsidyReceived = 0;
        }

    } else {

        $subsidyReceived =
            ((int)$subsidyInput === 1)
                ? 1
                : 0;
    }

    // =====================================================
    // Q1-Q10
    // =====================================================

    $questionAnswers = [];

    for (
        $i = 1;
        $i <= 10;
        $i++
    ) {

        $question =
            'q' . $i;

        $answer =
            $formData[$question]
            ?? null;

        if (
            $answer === null ||
            $answer === ''
        ) {

            $this->Flash->error(
                'Please answer all survey questions.'
            );

            return $this->redirect(
                $this->referer()
            );
        }

        if (is_array($answer)) {

            $this->Flash->error(
                'Invalid survey answer.'
            );

            return $this->redirect(
                $this->referer()
            );
        }

        $answer =
            (int)$answer;

        if (
            $answer < 1 ||
            $answer > 5
        ) {

            $this->Flash->error(
                'Each survey answer must be between 1 and 5.'
            );

            return $this->redirect(
                $this->referer()
            );
        }

        $questionAnswers[
            $question
        ] = $answer;
    }

    // =====================================================
    // JSON ANSWERS
    // =====================================================

    $answersJson =
        json_encode(
            $questionAnswers,
            JSON_UNESCAPED_UNICODE
        );

    if (
        $answersJson === false
    ) {

        $this->Flash->error(
            'Unable to prepare survey answers.'
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // COMMENT
    // =====================================================

    $comment =
        trim(
            (string)(
                $formData['comment']
                ?? ''
            )
        );

    if ($comment === '') {

        $comment = null;
    }

    // =====================================================
    // DATABASE CONNECTION
    // =====================================================

    $connection =
        $this->Evaluations
            ->getConnection();

    // =====================================================
    // TRANSACTION
    // =====================================================

    try {

        $connection->begin();

        // =================================================
        // DUPLICATE CHECK AGAIN
        // =================================================

        $existingEvaluation =
            $this->Evaluations->find()
                ->where([
                    'Evaluations.farmer_id' =>
                        $farmer->id,

                    'Evaluations.farm_id' =>
                        $farmId,

                    'Evaluations.schedule_id' =>
                        $scheduleId
                ])
                ->first();

        if ($existingEvaluation) {

            throw new \RuntimeException(
                'You have already submitted feedback for this farm and distribution schedule.'
            );
        }

        // =================================================
        // SAVE FEEDBACK
        // =================================================

        $feedback =
            $this->Feedbacks
                ->newEmptyEntity();

        $feedback->farmer_id =
            $farmer->id;

        $feedback->answer =
            $answersJson;

        $feedback->comment =
            $comment;

        $feedback->feedback_date =
            date(
                'Y-m-d H:i:s'
            );

        $savedFeedback =
            $this->Feedbacks->save(
                $feedback,
                [
                    'validate' =>
                        false,

                    'checkRules' =>
                        false,

                    'checkExisting' =>
                        false
                ]
            );

        if (!$savedFeedback) {

            throw new \RuntimeException(
                'Unable to save the feedback.'
            );
        }

        $feedbackId =
            $feedback->id;

        if (empty($feedbackId)) {

            throw new \RuntimeException(
                'Feedback was saved but no feedback ID was generated.'
            );
        }

        // =================================================
        // ML API
        // =================================================

        $mlApiUrl =
            'https://effectiveness.onrender.com/predict';

        $mlPayloadData = array_merge(
            [
                'farm_size' =>
                    $farmSize,

                'rice_type' =>
                    $riceType,

                'planting_type' =>
                    $riceType,

                'average_yield' =>
                    $averageYield,

                'crop_yield_after' =>
                    $cropYieldAfter,

                'subsidy_received' =>
                    $subsidyReceived,

                'selling_price' =>
                    $sellingPrice,

                'answers' =>
                    $questionAnswers
            ],
            $questionAnswers
        );

        $mlPayload =
            json_encode(
                $mlPayloadData,
                JSON_UNESCAPED_UNICODE
            );

        if ($mlPayload === false) {

            throw new \RuntimeException(
                'Unable to prepare ML request.'
            );
        }

        // =================================================
        // CURL REQUEST
        // =================================================

        $ch =
            curl_init(
                $mlApiUrl
            );

        if ($ch === false) {

            throw new \RuntimeException(
                'Unable to connect to the ML API.'
            );
        }

        curl_setopt(
            $ch,
            CURLOPT_RETURNTRANSFER,
            true
        );

        curl_setopt(
            $ch,
            CURLOPT_POST,
            true
        );

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $mlPayload
        );

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' .
                strlen($mlPayload)
            ]
        );

        curl_setopt(
            $ch,
            CURLOPT_CONNECTTIMEOUT,
            30
        );

        curl_setopt(
            $ch,
            CURLOPT_TIMEOUT,
            60
        );

        $mlResponse =
            curl_exec($ch);

        $curlError =
            curl_error($ch);

        $httpCode =
            curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );

        curl_close($ch);

        // =================================================
        // EFFECTIVENESS LABEL
        // =================================================

        $effectivenessLabel =
            null;

        if (
            $mlResponse !== false &&
            $httpCode === 200
        ) {

            $mlData =
                json_decode(
                    $mlResponse,
                    true
                );

            $this->log(
                'ML API Response: ' .
                $mlResponse,
                'debug'
            );

            if (
                is_array($mlData)
            ) {

                $effectivenessLabel =
                    $mlData['prediction']
                    ?? $mlData['label']
                    ?? $mlData['effectiveness']
                    ?? $mlData['result']
                    ?? null;
            }

        } else {

            $this->log(
                sprintf(
                    'ML API Failed [HTTP %d]: %s | Raw Response: %s',
                    $httpCode,
                    $curlError,
                    (string)$mlResponse
                ),
                'error'
            );
        }

        // =================================================
        // RULE-BASED FALLBACK
        // =================================================

        if (
            empty($effectivenessLabel)
        ) {

            $yieldIncreasePercent =
                ($averageYield > 0)
                    ? (
                        (
                            $cropYieldAfter -
                            $averageYield
                        )
                        /
                        $averageYield
                    ) * 100
                    : 0;

            if (
                $yieldIncreasePercent >= 15
            ) {

                $effectivenessLabel =
                    'Effective';

            } elseif (
                $yieldIncreasePercent >= 0
            ) {

                $effectivenessLabel =
                    'Moderately Effective';

            } else {

                $effectivenessLabel =
                    'Not Effective';
            }

            $this->log(
                'Applied Rule-Based Fallback Label: ' .
                $effectivenessLabel,
                'warning'
            );
        }

        // =================================================
        // SAVE EVALUATION
        // =================================================

        $evaluation =
            $this->Evaluations
                ->newEmptyEntity();

        $evaluation->farmer_id =
            $farmer->id;

        $evaluation->farm_id =
            $farmId;

        $evaluation->schedule_id =
            $scheduleId;

        $evaluation->average_yield =
            $averageYield;

        $evaluation->rice_type =
            $riceType;

        $evaluation->crop_yield_after =
            $cropYieldAfter;

        $evaluation->subsidy_received =
            $subsidyReceived;

        $evaluation->selling_price =
            $sellingPrice;

        $evaluation->effectiveness_label =
            $effectivenessLabel;

        $evaluation->feedback_id =
            (int)$feedbackId;

        // =================================================
        // SAVE EVALUATION
        // =================================================

        $savedEvaluation =
            $this->Evaluations->save(
                $evaluation,
                [
                    'validate' =>
                        false,

                    'checkRules' =>
                        false,

                    'checkExisting' =>
                        false
                ]
            );

        if (!$savedEvaluation) {

            throw new \RuntimeException(
                'Unable to save the evaluation.'
            );
        }

        // =================================================
        // COMMIT
        // =================================================

        $connection->commit();

    } catch (\Throwable $e) {

        // =================================================
        // ROLLBACK
        // =================================================

        if (
            $connection->inTransaction()
        ) {

            $connection->rollback();
        }

        // =================================================
        // LOG
        // =================================================

        $this->log(
            'Survey save failed: ' .
            $e->getMessage(),
            'error'
        );

        // =================================================
        // ERROR
        // =================================================

        $this->Flash->error(
            $e->getMessage()
        );

        return $this->redirect(
            $this->referer()
        );
    }

    // =====================================================
    // SUCCESS
    // =====================================================

    $this->Flash->success(
        'Evaluation and feedback submitted successfully.'
    );

    return $this->redirect([
        'action' => 'index',
        '?' => [
            'submitted' => 1
        ]
    ]);
}
}

