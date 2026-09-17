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
         * =======================================================  =====
         */

        $this->loadModel('Evaluations');
        $this->loadModel('Farms');
        $this->loadModel('Feedbacks');
        $this->loadModel('Schedules');


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

                    /*
                     * Evaluation
                     */
                    'evaluation_id' =>
                        'Evaluations.id',

                    'feedback_id' =>
                        'Evaluations.feedback_id',

                    'schedule_id' =>
                        'Evaluations.schedule_id',

                    'effectiveness_label' =>
                        'Evaluations.effectiveness_label',


                    /*
                     * Program
                     */
                    'program_code' =>
                        'Schedules.program_code',

                    'program_name' =>
                        'Schedules.program_name',


                    /*
                     * Feedback
                     */
                    'feedback_rating' =>
                        'Feedbacks.rating'
                ])


                /*
                 * ====================================================
                 * JOIN FEEDBACK
                 * ====================================================
                 */

                ->leftJoin(
                    ['Feedbacks' => 'feedbacks'],
                    [
                        'Feedbacks.id = Evaluations.feedback_id'
                    ]
                )


                /*
                 * ====================================================
                 * JOIN SCHEDULE
                 * ====================================================
                 */

                ->innerJoin(
                    ['Schedules' => 'schedules'],
                    [
                        'Schedules.id = Evaluations.schedule_id'
                    ]
                )


                /*
                 * ====================================================
                 * NO FEEDBACK FILTER HERE
                 * ====================================================
                 */

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

            /*
             * --------------------------------------------------------
             * PROGRAM CODE
             * --------------------------------------------------------
             */

            $programCode =
                trim(
                    (string)(
                        $evaluation['program_code']
                        ?? ''
                    )
                );


            /*
             * --------------------------------------------------------
             * PROGRAM NAME
             * --------------------------------------------------------
             */

            $programName =
                trim(
                    (string)(
                        $evaluation['program_name']
                        ?? ''
                    )
                );


            /*
             * --------------------------------------------------------
             * IGNORE EVALUATION IF PROGRAM IS UNKNOWN
             * --------------------------------------------------------
             */

            if ($programCode === '') {
                continue;
            }


            if ($programName === '') {
                $programName = 'N/A';
            }


            /*
             * --------------------------------------------------------
             * PROGRAM KEY
             * --------------------------------------------------------
             */

            $programKey =
                $programCode;


            /*
             * --------------------------------------------------------
             * INITIALIZE PROGRAM
             * --------------------------------------------------------
             */

            if (
                !isset(
                    $programGroups[
                        $programKey
                    ]
                )
            ) {

                $programGroups[
                    $programKey
                ] = [

                    'program_code' =>
                        $programCode,

                    'program_name' =>
                        $programName,


                    /*
                     * ALL EVALUATIONS
                     */
                    'total_evaluations' =>
                        0,


                    /*
                     * FEEDBACK
                     */
                    'total_feedbacks' =>
                        0,

                    'total_rating' =>
                        0,


                    /*
                     * EFFECTIVENESS LABELS
                     */
                    'effective_count' =>
                        0,

                    'moderately_effective_count' =>
                        0,

                    'not_effective_count' =>
                        0
                ];
            }


            /*
             * ========================================================
             * COUNT ALL EVALUATIONS
             * ========================================================
             */

            $programGroups[
                $programKey
            ]['total_evaluations']++;


            /*
             * ========================================================
             * EFFECTIVENESS LABEL
             * ========================================================
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


            /*
             * Normalize possible variations.
             */

            $label =
                preg_replace(
                    '/\s+/',
                    ' ',
                    $label
                );


            if (
                $label === 'effective'
            ) {

                $programGroups[
                    $programKey
                ]['effective_count']++;

            } elseif (
                $label === 'moderately effective'
            ) {

                $programGroups[
                    $programKey
                ]['moderately_effective_count']++;

            } elseif (
                $label === 'not effective'
            ) {

                $programGroups[
                    $programKey
                ]['not_effective_count']++;
            }


            /*
             * ========================================================
             * FEEDBACK RATING
             * ========================================================
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


            /*
             * Only accept valid 1-5 ratings.
             */

            if (
                $rating < 1 ||
                $rating > 5
            ) {
                continue;
            }


            /*
             * Count feedback response.
             */

            $programGroups[
                $programKey
            ]['total_feedbacks']++;


            /*
             * Add feedback rating.
             */

            $programGroups[
                $programKey
            ]['total_rating'] += $rating;
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

            /*
             * --------------------------------------------------------
             * TOTAL EVALUATIONS
             * --------------------------------------------------------
             */

            $totalProgramEvaluations =
                (int)(
                    $program[
                        'total_evaluations'
                    ]
                );


            /*
             * --------------------------------------------------------
             * TOTAL FEEDBACK
             * --------------------------------------------------------
             */

            $totalFeedbacks =
                (int)(
                    $program[
                        'total_feedbacks'
                    ]
                );


            /*
             * --------------------------------------------------------
             * TOTAL RATING
             * --------------------------------------------------------
             */

            $totalRating =
                (float)(
                    $program[
                        'total_rating'
                    ]
                );


            /*
             * --------------------------------------------------------
             * AVERAGE FEEDBACK RATING
             * --------------------------------------------------------
             */

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


            /*
             * --------------------------------------------------------
             * EFFECTIVE COUNT
             * --------------------------------------------------------
             */

            $effectiveCount =
                (int)(
                    $program[
                        'effective_count'
                    ]
                );


            /*
             * --------------------------------------------------------
             * MODERATELY EFFECTIVE COUNT
             * --------------------------------------------------------
             */

            $moderatelyEffectiveCount =
                (int)(
                    $program[
                        'moderately_effective_count'
                    ]
                );


            /*
             * --------------------------------------------------------
             * NOT EFFECTIVE COUNT
             * --------------------------------------------------------
             */

            $notEffectiveCount =
                (int)(
                    $program[
                        'not_effective_count'
                    ]
                );


            /*
             * --------------------------------------------------------
             * EFFECTIVE PERCENTAGE
             * --------------------------------------------------------
             */

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


            /*
             * --------------------------------------------------------
             * STORE PROGRAM
             * --------------------------------------------------------
             */

            $programEffectiveness[] = [

                'program_code' =>
                    $program[
                        'program_code'
                    ],

                'program_name' =>
                    $program[
                        'program_name'
                    ],


                /*
                 * ALL EVALUATIONS
                 */
                'total_evaluations' =>
                    $totalProgramEvaluations,


                /*
                 * FEEDBACK
                 */
                'total_feedbacks' =>
                    $totalFeedbacks,

                'total_rating' =>
                    $totalRating,

                'effectiveness_rating' =>
                    $averageRating,


                /*
                 * EFFECTIVENESS RESULTS
                 */
                'effective_count' =>
                    $effectiveCount,

                'moderately_effective_count' =>
                    $moderatelyEffectiveCount,

                'not_effective_count' =>
                    $notEffectiveCount,


                /*
                 * EFFECTIVE %
                 */
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

                /*
                 * ====================================================
                 * 1. HIGHEST AVERAGE FEEDBACK RATING
                 * ====================================================
                 */

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


                /*
                 * ====================================================
                 * 2. MORE EFFECTIVE EVALUATIONS
                 * ====================================================
                 */

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


                /*
                 * ====================================================
                 * 3. HIGHER EFFECTIVE PERCENTAGE
                 * ====================================================
                 */

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


                /*
                 * ====================================================
                 * 4. FEWER NOT EFFECTIVE EVALUATIONS
                 * ====================================================
                 */

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


                /*
                 * ====================================================
                 * 5. MORE FEEDBACK RESPONSES
                 * ====================================================
                 */

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


                /*
                 * ====================================================
                 * 6. MORE TOTAL EVALUATIONS
                 * ====================================================
                 */

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


            /*
             * Check program name.
             */

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


            /*
             * Check program code.
             */

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

            /*
             * Total evaluations
             */

            $seedTotalEvaluations =
                (int)(
                    $seedProgram[
                        'total_evaluations'
                    ]
                    ?? 0
                );


            /*
             * Feedback responses
             */

            $seedTotalFeedbacks =
                (int)(
                    $seedProgram[
                        'total_feedbacks'
                    ]
                    ?? 0
                );


            /*
             * Average feedback rating
             */

            $seedFeedbackRating =
                (float)(
                    $seedProgram[
                        'effectiveness_rating'
                    ]
                    ?? 0
                );


            /*
             * Effective
             */

            $seedEffective =
                (int)(
                    $seedProgram[
                        'effective_count'
                    ]
                    ?? 0
                );


            /*
             * Moderately Effective
             */

            $seedModeratelyEffective =
                (int)(
                    $seedProgram[
                        'moderately_effective_count'
                    ]
                    ?? 0
                );


            /*
             * Not Effective
             */

            $seedNotEffective =
                (int)(
                    $seedProgram[
                        'not_effective_count'
                    ]
                    ?? 0
                );


            /*
             * Effectiveness rate is based on ALL evaluations.
             */

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


            /*
             * --------------------------------------------------------
             * PROGRAM YIELD QUERY
             * --------------------------------------------------------
             */

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


                    /*
                     * Join Farms
                     */

                    ->innerJoin(
                        ['Farms' => 'farms'],
                        [
                            'Farms.id = Evaluations.farm_id'
                        ]
                    )


                    /*
                     * Join Schedules
                     */

                    ->innerJoin(
                        ['Schedules' => 'schedules'],
                        [
                            'Schedules.id = Evaluations.schedule_id'
                        ]
                    )


                    /*
                     * Match Seed program
                     */

                    ->where([
                        'Schedules.program_code' =>
                            $seedProgramCode
                    ])


                    ->first();


            /*
             * --------------------------------------------------------
             * GET SEED VALUES
             * --------------------------------------------------------
             */

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


            /*
             * --------------------------------------------------------
             * SEED YIELD IMPROVEMENT
             * --------------------------------------------------------
             */

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


        $modelAccuracy =
            93.5;


        $lastTrained =
            'July 22, 2026 11:30 PM';


        /*
         * ============================================================
         * FEATURE IMPORTANCE
         * ============================================================
         */

        $featureImportance = [

            'Crop Yield Increase' =>
                round(
                    abs($yieldImprovement),
                    2
                ),

            'Subsidy Utilization' =>
                42,

            'Distribution Timeliness' =>
                34,

            'Farmer Feedback' =>
                20,

            'Selling Price' =>
                25
        ];


        /*
         * ============================================================
         * SEND DATA TO VIEW
         * ============================================================
         */

        $this->set(compact(

            /*
             * --------------------------------------------------------
             * Overall
             * --------------------------------------------------------
             */

            'totalEvaluations',

            'effective',

            'moderatelyEffective',

            'notEffective',

            'effectiveRate',

            'moderatelyEffectiveRate',

            'notEffectiveRate',


            /*
             * --------------------------------------------------------
             * Program effectiveness
             * --------------------------------------------------------
             */

            'programEffectiveness',

            'mostEffectiveProgram',


            /*
             * --------------------------------------------------------
             * Seed Subsidy
             * --------------------------------------------------------
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
             * --------------------------------------------------------
             * Feedback
             * --------------------------------------------------------
             */

            'feedbackAverage',


            /*
             * --------------------------------------------------------
             * Yield
             * --------------------------------------------------------
             */

            'avgYieldBefore',

            'avgYieldAfter',

            'yieldImprovement',


            /*
             * --------------------------------------------------------
             * Selling price
             * --------------------------------------------------------
             */

            'avgSellingPrice',


            /*
             * --------------------------------------------------------
             * Seed yield
             * --------------------------------------------------------
             */

            'seedAvgYieldBefore',

            'seedAvgYieldAfter',

            'seedAvgSellingPrice',

            'seedYieldImprovement',


            /*
             * --------------------------------------------------------
             * Machine learning
             * --------------------------------------------------------
             */

            'modelStatus',

            'modelName',

            'modelAccuracy',

            'lastTrained',


            /*
             * --------------------------------------------------------
             * Feature importance
             * --------------------------------------------------------
             */

            'featureImportance'
        ));
    }
}