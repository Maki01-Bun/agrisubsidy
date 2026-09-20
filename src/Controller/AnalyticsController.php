<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Analytics Controller
 *
 * @method \App\Model\Entity\Analytics[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnalyticsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void
     */
       public function index()
    {
        /*
         * ============================================================
         * LOAD REQUIRED MODELS
         * ============================================================
         */

        $this->loadModel('Evaluations');
        $this->loadModel('Farms');
        $this->loadModel('Feedbacks');
        $this->loadModel('Schedules');
        $this->loadModel('Records');


        /*
         * ============================================================
         * OVERALL EVALUATION COUNTS
         * ============================================================
         */

        $totalEvaluations = $this->Evaluations
            ->find()
            ->where([
                'Evaluations.effectiveness_label IS NOT' => null
            ])
            ->count();


        /*
         * ============================================================
         * EFFECTIVENESS LABEL COUNTS
         * ============================================================
         */

        $effective = $this->Evaluations
            ->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Effective'
            ])
            ->count();


        $moderatelyEffective = $this->Evaluations
            ->find()
            ->where([
                'Evaluations.effectiveness_label' =>
                    'Moderately Effective'
            ])
            ->count();


        $notEffective = $this->Evaluations
            ->find()
            ->where([
                'Evaluations.effectiveness_label' =>
                    'Not Effective'
            ])
            ->count();


        /*
         * ============================================================
         * OVERALL EFFECTIVENESS RATES
         * ============================================================
         */

        $effectiveRate = 0;
        $moderatelyEffectiveRate = 0;
        $notEffectiveRate = 0;

        if ($totalEvaluations > 0) {

            $effectiveRate =
                round(
                    (
                        $effective /
                        $totalEvaluations
                    ) * 100,
                    2
                );

            $moderatelyEffectiveRate =
                round(
                    (
                        $moderatelyEffective /
                        $totalEvaluations
                    ) * 100,
                    2
                );

            $notEffectiveRate =
                round(
                    (
                        $notEffective /
                        $totalEvaluations
                    ) * 100,
                    2
                );
        }


        /*
         * ============================================================
         * PROGRAM EFFECTIVENESS
         * ============================================================
         */

        $programFeedbackQuery =
            $this->Evaluations->find();


        $programFeedbacks =
            $programFeedbackQuery
                ->select([

                    'evaluation_id' =>
                        'Evaluations.id',

                    'feedback_id' =>
                        'Evaluations.feedback_id',

                    'schedule_id' =>
                        'Evaluations.schedule_id',

                    'effectiveness_label' =>
                        'Evaluations.effectiveness_label',

                    'program_code' =>
                        'Schedules.program_code',

                    'program_name' =>
                        'Schedules.program_name',

                    'feedback_rating' =>
                        'Feedbacks.rating'
                ])

                ->leftJoin(
                    ['Feedbacks' => 'feedbacks'],
                    [
                        'Feedbacks.id = Evaluations.feedback_id'
                    ]
                )

                ->innerJoin(
                    ['Schedules' => 'schedules'],
                    [
                        'Schedules.id = Evaluations.schedule_id'
                    ]
                )

                ->enableHydration(false)

                ->toArray();


        /*
         * ============================================================
         * GROUP EVALUATIONS BY PROGRAM
         * ============================================================
         */

        $programGroups = [];


        foreach (
            $programFeedbacks as $evaluation
        ) {

            $programCode =
                trim(
                    (string)(
                        $evaluation['program_code']
                        ?? ''
                    )
                );


            $programName =
                trim(
                    (string)(
                        $evaluation['program_name']
                        ?? ''
                    )
                );


            if ($programCode === '') {
                continue;
            }


            if ($programName === '') {
                $programName = 'N/A';
            }


            $programKey =
                $programCode;


            if (
                !isset(
                    $programGroups[$programKey]
                )
            ) {

                $programGroups[$programKey] = [

                    'program_code' =>
                        $programCode,

                    'program_name' =>
                        $programName,

                    'total_evaluations' =>
                        0,

                    'total_feedbacks' =>
                        0,

                    'total_rating' =>
                        0,

                    'effective_count' =>
                        0,

                    'moderately_effective_count' =>
                        0,

                    'not_effective_count' =>
                        0
                ];
            }


            /*
             * Count evaluation.
             */

            $programGroups[$programKey]
                ['total_evaluations']++;


            /*
             * Effectiveness label.
             */

            $label =
                strtolower(
                    trim(
                        (string)(
                            $evaluation[
                                'effectiveness_label'
                            ]
                            ?? ''
                        )
                    )
                );


            $label =
                preg_replace(
                    '/\s+/',
                    ' ',
                    $label
                );


            if (
                $label === 'effective'
            ) {

                $programGroups[$programKey]
                    ['effective_count']++;

            } elseif (
                $label === 'moderately effective'
            ) {

                $programGroups[$programKey]
                    ['moderately_effective_count']++;

            } elseif (
                $label === 'not effective'
            ) {

                $programGroups[$programKey]
                    ['not_effective_count']++;
            }


            /*
             * Feedback rating.
             */

            $ratingValue =
                $evaluation[
                    'feedback_rating'
                ]
                ?? null;


            if (
                $ratingValue === null ||
                $ratingValue === ''
            ) {
                continue;
            }


            $rating =
                (float)$ratingValue;


            if (
                $rating < 1 ||
                $rating > 5
            ) {
                continue;
            }


            $programGroups[$programKey]
                ['total_feedbacks']++;


            $programGroups[$programKey]
                ['total_rating'] += $rating;
        }


        /*
         * ============================================================
         * BUILD PROGRAM EFFECTIVENESS ARRAY
         * ============================================================
         */

        $programEffectiveness = [];


        foreach (
            $programGroups as $program
        ) {

            $totalProgramEvaluations =
                (int)(
                    $program[
                        'total_evaluations'
                    ]
                );


            $totalFeedbacks =
                (int)(
                    $program[
                        'total_feedbacks'
                    ]
                );


            $totalRating =
                (float)(
                    $program[
                        'total_rating'
                    ]
                );


            $averageRating = 0;


            if (
                $totalFeedbacks > 0
            ) {

                $averageRating =
                    round(
                        $totalRating /
                        $totalFeedbacks,
                        2
                    );
            }


            $effectiveCount =
                (int)(
                    $program[
                        'effective_count'
                    ]
                );


            $moderatelyEffectiveCount =
                (int)(
                    $program[
                        'moderately_effective_count'
                    ]
                );


            $notEffectiveCount =
                (int)(
                    $program[
                        'not_effective_count'
                    ]
                );


            $effectivePercentage = 0;


            if (
                $totalProgramEvaluations > 0
            ) {

                $effectivePercentage =
                    round(
                        (
                            $effectiveCount /
                            $totalProgramEvaluations
                        ) * 100,
                        2
                    );
            }


            $programEffectiveness[] = [

                'program_code' =>
                    $program[
                        'program_code'
                    ],

                'program_name' =>
                    $program[
                        'program_name'
                    ],

                'total_evaluations' =>
                    $totalProgramEvaluations,

                'total_feedbacks' =>
                    $totalFeedbacks,

                'total_rating' =>
                    $totalRating,

                'effectiveness_rating' =>
                    $averageRating,

                'effective_count' =>
                    $effectiveCount,

                'moderately_effective_count' =>
                    $moderatelyEffectiveCount,

                'not_effective_count' =>
                    $notEffectiveCount,

                'effective_percentage' =>
                    $effectivePercentage
            ];
        }


        /*
         * ============================================================
         * SORT PROGRAMS
         * ============================================================
         */

        usort(
            $programEffectiveness,
            function (
                $a,
                $b
            ) {

                $ratingA =
                    (float)(
                        $a[
                            'effectiveness_rating'
                        ]
                        ?? 0
                    );


                $ratingB =
                    (float)(
                        $b[
                            'effectiveness_rating'
                        ]
                        ?? 0
                    );


                if (
                    $ratingA != $ratingB
                ) {

                    return
                        $ratingB
                        <=>
                        $ratingA;
                }


                $effectiveA =
                    (int)(
                        $a[
                            'effective_count'
                        ]
                        ?? 0
                    );


                $effectiveB =
                    (int)(
                        $b[
                            'effective_count'
                        ]
                        ?? 0
                    );


                if (
                    $effectiveA != $effectiveB
                ) {

                    return
                        $effectiveB
                        <=>
                        $effectiveA;
                }


                $effectivePercentageA =
                    (float)(
                        $a[
                            'effective_percentage'
                        ]
                        ?? 0
                    );


                $effectivePercentageB =
                    (float)(
                        $b[
                            'effective_percentage'
                        ]
                        ?? 0
                    );


                if (
                    $effectivePercentageA
                    !=
                    $effectivePercentageB
                ) {

                    return
                        $effectivePercentageB
                        <=>
                        $effectivePercentageA;
                }


                $notEffectiveA =
                    (int)(
                        $a[
                            'not_effective_count'
                        ]
                        ?? 0
                    );


                $notEffectiveB =
                    (int)(
                        $b[
                            'not_effective_count'
                        ]
                        ?? 0
                    );


                if (
                    $notEffectiveA
                    !=
                    $notEffectiveB
                ) {

                    return
                        $notEffectiveA
                        <=>
                        $notEffectiveB;
                }


                $feedbackA =
                    (int)(
                        $a[
                            'total_feedbacks'
                        ]
                        ?? 0
                    );


                $feedbackB =
                    (int)(
                        $b[
                            'total_feedbacks'
                        ]
                        ?? 0
                    );


                if (
                    $feedbackA
                    !=
                    $feedbackB
                ) {

                    return
                        $feedbackB
                        <=>
                        $feedbackA;
                }


                $evaluationsA =
                    (int)(
                        $a[
                            'total_evaluations'
                        ]
                        ?? 0
                    );


                $evaluationsB =
                    (int)(
                        $b[
                            'total_evaluations'
                        ]
                        ?? 0
                    );


                return
                    $evaluationsB
                    <=>
                    $evaluationsA;
            }
        );


        /*
         * ============================================================
         * MOST EFFECTIVE PROGRAM
         * ============================================================
         */

        $mostEffectiveProgram =
            !empty(
                $programEffectiveness
            )
                ? $programEffectiveness[0]
                : null;


        /*
         * ============================================================
         * FIND SEED SUBSIDY PROGRAM
         * ============================================================
         */

        $seedProgram = null;


        foreach (
            $programEffectiveness as $program
        ) {

            $programName =
                strtolower(
                    trim(
                        (string)(
                            $program[
                                'program_name'
                            ]
                            ?? ''
                        )
                    )
                );


            $programCode =
                strtolower(
                    trim(
                        (string)(
                            $program[
                                'program_code'
                            ]
                            ?? ''
                        )
                    )
                );


            if (
                str_contains(
                    $programName,
                    'seed subsidy'
                )
                ||
                str_contains(
                    $programName,
                    'seed'
                )
            ) {

                $seedProgram =
                    $program;

                break;
            }


            if (
                str_contains(
                    $programCode,
                    'seed'
                )
            ) {

                $seedProgram =
                    $program;

                break;
            }
        }


        /*
         * ============================================================
         * SEED SUBSIDY STATISTICS
         * ============================================================
         */

        $seedTotalEvaluations = 0;
        $seedTotalFeedbacks = 0;
        $seedFeedbackRating = 0;
        $seedEffective = 0;
        $seedModeratelyEffective = 0;
        $seedNotEffective = 0;
        $seedEffectivenessRate = 0;


        if (
            $seedProgram !== null
        ) {

            $seedTotalEvaluations =
                (int)(
                    $seedProgram[
                        'total_evaluations'
                    ]
                    ?? 0
                );


            $seedTotalFeedbacks =
                (int)(
                    $seedProgram[
                        'total_feedbacks'
                    ]
                    ?? 0
                );


            $seedFeedbackRating =
                (float)(
                    $seedProgram[
                        'effectiveness_rating'
                    ]
                    ?? 0
                );


            $seedEffective =
                (int)(
                    $seedProgram[
                        'effective_count'
                    ]
                    ?? 0
                );


            $seedModeratelyEffective =
                (int)(
                    $seedProgram[
                        'moderately_effective_count'
                    ]
                    ?? 0
                );


            $seedNotEffective =
                (int)(
                    $seedProgram[
                        'not_effective_count'
                    ]
                    ?? 0
                );


            if (
                $seedTotalEvaluations > 0
            ) {

                $seedEffectivenessRate =
                    round(
                        (
                            $seedEffective /
                            $seedTotalEvaluations
                        ) * 100,
                        2
                    );
            }
        }


        /*
         * ============================================================
         * OVERALL FEEDBACK AVERAGE
         * ============================================================
         */

        $feedbackQuery =
            $this->Feedbacks->find();


        $feedbackAverageResult =
            $feedbackQuery
                ->select([
                    'average_rating' =>
                        $feedbackQuery
                            ->func()
                            ->avg(
                                'Feedbacks.rating'
                            )
                ])
                ->where([
                    'Feedbacks.rating IS NOT' => null
                ])
                ->first();


        $feedbackAverage = 0;


        if (
            $feedbackAverageResult
        ) {

            $feedbackAverage =
                round(
                    (float)(
                        $feedbackAverageResult
                            ->average_rating
                    ),
                    2
                );
        }


        /*
         * ============================================================
         * AVERAGE YIELD
         * ============================================================
         */

        $yieldQuery =
            $this->Evaluations->find();


        $yieldData =
            $yieldQuery
                ->select([

                    'avg_yield_before' =>
                        $yieldQuery
                            ->func()
                            ->avg(
                                'Evaluations.average_yield'
                            ),

                    'avg_yield_after' =>
                        $yieldQuery
                            ->func()
                            ->avg(
                                'Evaluations.crop_yield_after'
                            )
                ])
                ->innerJoin(
                    ['Farms' => 'farms'],
                    [
                        'Farms.id = Evaluations.farm_id'
                    ]
                )
                ->first();


        $avgYieldBefore = 0;
        $avgYieldAfter = 0;


        if (
            $yieldData
        ) {

            $avgYieldBefore =
                (float)(
                    $yieldData
                        ->avg_yield_before
                    ?? 0
                );


            $avgYieldAfter =
                (float)(
                    $yieldData
                        ->avg_yield_after
                    ?? 0
                );
        }


        /*
         * ============================================================
         * YIELD IMPROVEMENT
         * ============================================================
         */

        $yieldImprovement = 0;


        if (
            $avgYieldBefore > 0
        ) {

            $yieldImprovement =
                (
                    (
                        $avgYieldAfter -
                        $avgYieldBefore
                    )
                    /
                    $avgYieldBefore
                )
                * 100;
        }


        $yieldImprovement =
            round(
                $yieldImprovement,
                2
            );


        /*
         * ============================================================
         * AVERAGE SELLING PRICE
         * ============================================================
         */

        $sellingPriceQuery =
            $this->Evaluations->find();


        $sellingPriceData =
            $sellingPriceQuery
                ->select([
                    'avg_selling_price' =>
                        $sellingPriceQuery
                            ->func()
                            ->avg(
                                'Evaluations.selling_price'
                            )
                ])
                ->first();


        $avgSellingPrice = 0;


        if (
            $sellingPriceData
        ) {

            $avgSellingPrice =
                (float)(
                    $sellingPriceData
                        ->avg_selling_price
                    ?? 0
                );
        }


        /*
         * ============================================================
         * SEED SUBSIDY YIELD DATA
         * ============================================================
         */

        $seedAvgYieldBefore = 0;
        $seedAvgYieldAfter = 0;
        $seedAvgSellingPrice = 0;
        $seedYieldImprovement = 0;


        if (
            $seedProgram !== null
        ) {

            $seedProgramCode =
                $seedProgram[
                    'program_code'
                ];


            $programYieldQuery =
                $this->Evaluations->find();


            $programYieldData =
                $programYieldQuery
                    ->select([

                        'avg_yield_before' =>
                            $programYieldQuery
                                ->func()
                                ->avg(
                                    'Evaluations.average_yield'
                                ),

                        'avg_yield_after' =>
                            $programYieldQuery
                                ->func()
                                ->avg(
                                    'Evaluations.crop_yield_after'
                                ),

                        'avg_selling_price' =>
                            $programYieldQuery
                                ->func()
                                ->avg(
                                    'Evaluations.selling_price'
                                )
                    ])

                    ->innerJoin(
                        ['Farms' => 'farms'],
                        [
                            'Farms.id = Evaluations.farm_id'
                        ]
                    )

                    ->innerJoin(
                        ['Schedules' => 'schedules'],
                        [
                            'Schedules.id = Evaluations.schedule_id'
                        ]
                    )

                    ->where([
                        'Schedules.program_code' =>
                            $seedProgramCode
                    ])

                    ->first();


            if (
                $programYieldData
            ) {

                $seedAvgYieldBefore =
                    (float)(
                        $programYieldData
                            ->avg_yield_before
                        ?? 0
                    );


                $seedAvgYieldAfter =
                    (float)(
                        $programYieldData
                            ->avg_yield_after
                        ?? 0
                    );


                $seedAvgSellingPrice =
                    (float)(
                        $programYieldData
                            ->avg_selling_price
                        ?? 0
                    );
            }


            if (
                $seedAvgYieldBefore > 0
            ) {

                $seedYieldImprovement =
                    (
                        (
                            $seedAvgYieldAfter -
                            $seedAvgYieldBefore
                        )
                        /
                        $seedAvgYieldBefore
                    )
                    * 100;
            }


            $seedYieldImprovement =
                round(
                    $seedYieldImprovement,
                    2
                );
        }


        /*
         * ============================================================
         * MACHINE LEARNING MODEL
         * ============================================================
         */

        $modelStatus =
            'Active';

        $modelName =
            'Random Forest Classifier';


        /*
         * ============================================================
         * RECEIVED SUBSIDY DISTRIBUTION RECORDS
         * ============================================================
         *
         * IMPORTANT:
         *
         * Records.status = Received
         *
         * Farmer information comes from Farmers.
         *
         * Program/location/date/time come from Schedules.
         *
         * Received date/status come from Records.
         *
         * ============================================================
         */

        $receivedRecordsQuery =
            $this->Records
                ->find()
                ->select([

                    /*
                     * Record
                     */
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
                     * Farmer
                     */
                    'farmer_no' =>
                        'Farmers.farmer_no',

                    'first_name' =>
                        'Farmers.first_name',

                    'last_name' =>
                        'Farmers.last_name',


                    /*
                     * Schedule
                     */
                    'program_code' =>
                        'Schedules.program_code',

                    'barangay' =>
                        'Schedules.barangay',

                    'distribution_date' =>
                        'Schedules.start_date',

                    'distribution_time' =>
                        'Schedules.start_time'
                ])


                /*
                 * ====================================================
                 * JOIN FARMERS
                 * ====================================================
                 */

                ->leftJoin(
                    ['Farmers' => 'farmers'],
                    [
                        'Farmers.id = Records.farmer_id'
                    ]
                )


                /*
                 * ====================================================
                 * JOIN SCHEDULES
                 * ====================================================
                 */

                ->leftJoin(
                    ['Schedules' => 'schedules'],
                    [
                        'Schedules.id = Records.schedule_id'
                    ]
                )


                /*
                 * ====================================================
                 * RECEIVED ONLY
                 * ====================================================
                 */

                ->where([
                    'Records.status' => 'Received'
                ])


                /*
                 * ====================================================
                 * ORDER
                 * ====================================================
                 */

                ->order([
                    'Schedules.start_date' =>
                        'DESC',

                    'Schedules.start_time' =>
                        'DESC',

                    'Records.id' =>
                        'DESC'
                ])


                ->enableHydration(false)

                ->all();


        /*
         * ============================================================
         * FORMAT RECEIVED RECORDS
         * ============================================================
         */

        $receivedRecords = [];


        foreach (
            $receivedRecordsQuery as $record
        ) {

            /*
             * --------------------------------------------------------
             * FARMER NAME
             * --------------------------------------------------------
             */

            $farmerName =
                trim(
                    ($record['first_name'] ?? '') .
                    ' ' .
                    ($record['last_name'] ?? '')
                );


            if (
                $farmerName === ''
            ) {

                $farmerName = '-';
            }


            /*
             * --------------------------------------------------------
             * DISTRIBUTION DATE
             * --------------------------------------------------------
             */

            $distributionDate = '-';


            if (
                !empty(
                    $record['distribution_date']
                )
            ) {

                try {

                    $date =
                        $record[
                            'distribution_date'
                        ];


                    if (
                        $date instanceof
                        \Cake\I18n\FrozenDate
                        ||
                        $date instanceof
                        \Cake\I18n\FrozenTime
                    ) {

                        $distributionDate =
                            $date->format(
                                'M d, Y'
                            );

                    } else {

                        $timestamp =
                            strtotime(
                                (string)$date
                            );


                        if (
                            $timestamp !== false
                        ) {

                            $distributionDate =
                                date(
                                    'M d, Y',
                                    $timestamp
                                );
                        }
                    }

                } catch (
                    \Throwable $e
                ) {

                    $distributionDate =
                        '-';
                }
            }


            /*
             * --------------------------------------------------------
             * DISTRIBUTION TIME
             * --------------------------------------------------------
             */

            $distributionTime = '-';


            if (
                !empty(
                    $record['distribution_time']
                )
            ) {

                try {

                    $time =
                        $record[
                            'distribution_time'
                        ];


                    if (
                        $time instanceof
                        \Cake\I18n\FrozenTime
                    ) {

                        $distributionTime =
                            $time->format(
                                'h:i A'
                            );

                    } else {

                        $timestamp =
                            strtotime(
                                (string)$time
                            );


                        if (
                            $timestamp !== false
                        ) {

                            $distributionTime =
                                date(
                                    'h:i A',
                                    $timestamp
                                );
                        }
                    }

                } catch (
                    \Throwable $e
                ) {

                    $distributionTime =
                        '-';
                }
            }


            /*
             * --------------------------------------------------------
             * RECEIVED DATE
             * --------------------------------------------------------
             */

            $receivedDate = '-';


            if (
                !empty(
                    $record['received_date']
                )
            ) {

                try {

                    $date =
                        $record[
                            'received_date'
                        ];


                    if (
                        $date instanceof
                        \Cake\I18n\FrozenDate
                        ||
                        $date instanceof
                        \Cake\I18n\FrozenTime
                    ) {

                        $receivedDate =
                            $date->format(
                                'M d, Y'
                            );

                    } else {

                        $timestamp =
                            strtotime(
                                (string)$date
                            );


                        if (
                            $timestamp !== false
                        ) {

                            $receivedDate =
                                date(
                                    'M d, Y',
                                    $timestamp
                                );
                        }
                    }

                } catch (
                    \Throwable $e
                ) {

                    $receivedDate =
                        '-';
                }
            }


            /*
             * --------------------------------------------------------
             * LOCATION
             * --------------------------------------------------------
             */

            $barangay =
                trim(
                    (string)(
                        $record['barangay']
                        ?? ''
                    )
                );


            if (
                $barangay === ''
            ) {

                $barangay =
                    'N/A';
            }


            /*
             * --------------------------------------------------------
             * QUANTITY
             * --------------------------------------------------------
             */

            $quantity =
                $record['quantity']
                ?? 0;


            /*
             * --------------------------------------------------------
             * SAVE RECORD
             * --------------------------------------------------------
             */

            $receivedRecords[] = [

                'record_id' =>
                    $record['record_id']
                    ?? null,

                'farmer_id' =>
                    $record['farmer_id']
                    ?? null,

                'schedule_id' =>
                    $record['schedule_id']
                    ?? null,

                'farmer_no' =>
                    $record['farmer_no']
                    ?? '-',

                'farmer_name' =>
                    $farmerName,

                'program_code' =>
                    $record['program_code']
                    ?? '-',

                'subsidy_item' =>
                    $record['subsidy_item']
                    ?? 'Seed Subsidy',

                'quantity' =>
                    $quantity,

                'barangay' =>
                    $barangay,

                'distribution_date' =>
                    $distributionDate,

                'distribution_time' =>
                    $distributionTime,

                'received_date' =>
                    $receivedDate,

                'status' =>
                    $record['status']
                    ?? 'Received'
            ];
        }


        /*
         * ============================================================
         * GET UNIQUE RECEIVED LOCATIONS
         * ============================================================
         */

        $receivedLocations = [];


        foreach (
            $receivedRecords as $record
        ) {

            $location =
                trim(
                    (string)(
                        $record['barangay']
                        ?? ''
                    )
                );


            if (
                $location !== ''
                &&
                $location !== 'N/A'
            ) {

                $receivedLocations[] =
                    $location;
            }
        }


        /*
         * Remove duplicates.
         */

        $receivedLocations =
            array_values(
                array_unique(
                    $receivedLocations
                )
            );


        /*
         * Sort alphabetically.
         */

        natcasesort(
            $receivedLocations
        );


        $receivedLocations =
            array_values(
                $receivedLocations
            );


        /*
         * ============================================================
         * SEND DATA TO VIEW
         * ============================================================
         */

        $this->set(compact(

            /*
             * Overall
             */
            'totalEvaluations',
            'effective',
            'moderatelyEffective',
            'notEffective',

            'effectiveRate',
            'moderatelyEffectiveRate',
            'notEffectiveRate',


            /*
             * Program effectiveness
             */
            'programEffectiveness',
            'mostEffectiveProgram',


            /*
             * Seed subsidy
             */
            'seedProgram',
            'seedTotalEvaluations',
            'seedTotalFeedbacks',
            'seedFeedbackRating',
            'seedEffective',
            'seedModeratelyEffective',
            'seedNotEffective',
            'seedEffectivenessRate',


            /*
             * Feedback
             */
            'feedbackAverage',


            /*
             * Yield
             */
            'avgYieldBefore',
            'avgYieldAfter',
            'yieldImprovement',


            /*
             * Selling price
             */
            'avgSellingPrice',


            /*
             * Seed yield
             */
            'seedAvgYieldBefore',
            'seedAvgYieldAfter',
            'seedAvgSellingPrice',
            'seedYieldImprovement',


            /*
             * Machine learning
             */
            'modelStatus',
            'modelName',


            /*
             * Received subsidy records
             */
            'receivedRecords',
            'receivedLocations'
        ));
    }
}