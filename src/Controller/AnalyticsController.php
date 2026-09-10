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
     * @return \Cake\Http\Response|null|void Renders view
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


        /*
         * ============================================================
         * OVERALL EVALUATION COUNTS
         * ============================================================
         */

        $totalEvaluations = $this->Evaluations
            ->find()
            ->count();


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
         * EFFECTIVENESS RATES
         * ============================================================
         */

        $effectiveRate = 0;
        $moderatelyEffectiveRate = 0;
        $notEffectiveRate = 0;

        if ($totalEvaluations > 0) {

            $effectiveRate = round(
                ($effective / $totalEvaluations) * 100,
                2
            );

            $moderatelyEffectiveRate = round(
                ($moderatelyEffective / $totalEvaluations) * 100,
                2
            );

            $notEffectiveRate = round(
                ($notEffective / $totalEvaluations) * 100,
                2
            );
        }


        /*
         * ============================================================
         * PROGRAM EFFECTIVENESS
         * ============================================================
         *
         * CORRECT RELATIONSHIP:
         *
         * Evaluations.feedback_id
         *          ↓
         * Feedbacks.id
         *
         * Evaluations.schedule_id
         *          ↓
         * Schedules.id
         *
         * NO subsidy_type is used.
         * NO subsidy_item is connected to schedule_id.
         *
         * ============================================================
         */

        $programFeedbackQuery = $this->Evaluations
            ->find();

        $programFeedbacks = $programFeedbackQuery
            ->select([
                'evaluation_id' =>
                    'Evaluations.id',

                'feedback_id' =>
                    'Evaluations.feedback_id',

                'schedule_id' =>
                    'Evaluations.schedule_id',

                'program_code' =>
                    'Schedules.program_code',

                'program_name' =>
                    'Schedules.program_name',

                'feedback_rating' =>
                    'Feedbacks.rating'
            ])
            ->innerJoin(
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
            ->where([
                'Feedbacks.rating IS NOT' => null,
                'Evaluations.feedback_id IS NOT' => null,
                'Evaluations.schedule_id IS NOT' => null
            ])
            ->enableHydration(false)
            ->toArray();


        /*
         * ============================================================
         * GROUP BY PROGRAM
         * ============================================================
         */

        $programEffectiveness = [];

        foreach ($programFeedbacks as $feedback) {

            $programCode = trim(
                (string)($feedback['program_code'] ?? '')
            );

            $programName = trim(
                (string)($feedback['program_name'] ?? '')
            );

            $ratingValue = $feedback['feedback_rating'] ?? null;

            /*
             * Ignore missing ratings
             */
            if (
                $ratingValue === null ||
                $ratingValue === ''
            ) {
                continue;
            }

            $rating = (float)$ratingValue;

            /*
             * Ignore invalid ratings
             */
            if ($rating < 1 || $rating > 5) {
                continue;
            }

            /*
             * Default program values
             */
            if ($programCode === '') {
                $programCode = 'N/A';
            }

            if ($programName === '') {
                $programName = 'N/A';
            }

            /*
             * Unique program
             */
            $programKey =
                $programCode . '|' . $programName;


            /*
             * Initialize
             */
            if (!isset($programEffectiveness[$programKey])) {

                $programEffectiveness[$programKey] = [

                    'program_code' =>
                        $programCode,

                    'program_name' =>
                        $programName,

                    'total_feedbacks' =>
                        0,

                    'total_rating' =>
                        0,

                    'effectiveness_rating' =>
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
             * Count feedback
             */
            $programEffectiveness[$programKey]
                ['total_feedbacks']++;


            /*
             * Add rating
             */
            $programEffectiveness[$programKey]
                ['total_rating'] += $rating;


            /*
             * ========================================================
             * RATING CLASSIFICATION
             * ========================================================
             *
             * 4.00 - 5.00 = Effective
             * 3.00 - 3.99 = Moderately Effective
             * Below 3.00  = Not Effective
             * ========================================================
             */

            if ($rating >= 4.00) {

                $programEffectiveness[$programKey]
                    ['effective_count']++;

            } elseif ($rating >= 3.00) {

                $programEffectiveness[$programKey]
                    ['moderately_effective_count']++;

            } else {

                $programEffectiveness[$programKey]
                    ['not_effective_count']++;
            }
        }


        /*
         * ============================================================
         * CALCULATE AVERAGE RATING
         * ============================================================
         */

        foreach (
            $programEffectiveness as &$program
        ) {

            if ($program['total_feedbacks'] > 0) {

                $program['effectiveness_rating'] =
                    round(
                        $program['total_rating'] /
                        $program['total_feedbacks'],
                        2
                    );
            }
        }

        unset($program);


        /*
         * ============================================================
         * CONVERT TO ARRAY
         * ============================================================
         */

        $programEffectiveness =
            array_values($programEffectiveness);


        /*
         * ============================================================
         * SORT BY HIGHEST AVERAGE RATING
         * ============================================================
         */

        usort(
            $programEffectiveness,
            function ($a, $b) {

                if (
                    $a['effectiveness_rating'] ===
                    $b['effectiveness_rating']
                ) {

                    return
                        $b['effective_count']
                        <=>
                        $a['effective_count'];
                }

                return
                    $b['effectiveness_rating']
                    <=>
                    $a['effectiveness_rating'];
            }
        );


        /*
         * ============================================================
         * MOST EFFECTIVE PROGRAM
         * ============================================================
         */

        $mostEffectiveProgram =
            !empty($programEffectiveness)
                ? $programEffectiveness[0]
                : null;


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

        if ($feedbackAverageResult) {

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
                                'Farms.average_yield'
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

        if ($yieldData) {

            $avgYieldBefore =
                (float)(
                    $yieldData->avg_yield_before ?? 0
                );

            $avgYieldAfter =
                (float)(
                    $yieldData->avg_yield_after ?? 0
                );
        }


        /*
         * ============================================================
         * YIELD IMPROVEMENT
         * ============================================================
         */

        $yieldImprovement = 0;

        if ($avgYieldBefore > 0) {

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

        if ($sellingPriceData) {

            $avgSellingPrice =
                (float)(
                    $sellingPriceData
                        ->avg_selling_price ?? 0
                );
        }


        /*
         * ============================================================
         * MOST EFFECTIVE PROGRAM YIELD DATA
         * ============================================================
         *
         * These values now correspond to the program that has the
         * highest Feedbacks.rating average.
         * ============================================================
         */

        $seedAvgYieldBefore = 0;
        $seedAvgYieldAfter = 0;
        $seedAvgSellingPrice = 0;
        $seedYieldImprovement = 0;


        if ($mostEffectiveProgram !== null) {

            $mostEffectiveProgramName =
                $mostEffectiveProgram['program_name'];

            $programYieldQuery =
                $this->Evaluations->find();

            $programYieldData =
                $programYieldQuery
                    ->select([

                        'avg_yield_before' =>
                            $programYieldQuery
                                ->func()
                                ->avg(
                                    'Farms.average_yield'
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
                        'Schedules.program_name' =>
                            $mostEffectiveProgramName
                    ])
                    ->first();


            if ($programYieldData) {

                $seedAvgYieldBefore =
                    (float)(
                        $programYieldData
                            ->avg_yield_before ?? 0
                    );

                $seedAvgYieldAfter =
                    (float)(
                        $programYieldData
                            ->avg_yield_after ?? 0
                    );

                $seedAvgSellingPrice =
                    (float)(
                        $programYieldData
                            ->avg_selling_price ?? 0
                    );
            }


            /*
             * ========================================================
             * MOST EFFECTIVE PROGRAM YIELD IMPROVEMENT
             * ========================================================
             */

            if ($seedAvgYieldBefore > 0) {

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

            'totalEvaluations',

            'effective',

            'moderatelyEffective',

            'notEffective',

            'effectiveRate',

            'moderatelyEffectiveRate',

            'notEffectiveRate',

            'mostEffectiveProgram',

            'programEffectiveness',

            'feedbackAverage',

            'avgYieldBefore',

            'avgYieldAfter',

            'yieldImprovement',

            'avgSellingPrice',

            'seedAvgYieldBefore',

            'seedAvgYieldAfter',

            'seedAvgSellingPrice',

            'seedYieldImprovement',

            'modelStatus',

            'modelName',

            'modelAccuracy',

            'lastTrained',

            'featureImportance'
        ));
    }
}
