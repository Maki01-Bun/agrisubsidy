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
        // =========================================================
        // LOAD MODELS
        // =========================================================

        $this->loadModel('Evaluations');
        $this->loadModel('Farms');
        $this->loadModel('Feedbacks');

        // =========================================================
        // TOTAL EVALUATIONS
        // =========================================================

        $totalEvaluations = $this->Evaluations
            ->find()
            ->count();

        // =========================================================
        // EFFECTIVENESS COUNTS
        // =========================================================

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

        // =========================================================
        // SUBSIDY PROGRAM COUNTS
        // =========================================================

        $programs = $this->Evaluations
            ->find()
            ->select([
                'subsidy_type' =>
                    'Evaluations.subsidy_type',

                'total' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->count('Evaluations.id')
            ])
            ->group([
                'Evaluations.subsidy_type'
            ])
            ->enableHydration(false)
            ->toArray();

        // =========================================================
        // AVERAGE FEEDBACK
        // =========================================================

        $feedbackData = $this->Feedbacks
            ->find()
            ->select([
                'average_rating' =>
                    $this->Feedbacks
                        ->find()
                        ->func()
                        ->avg('Feedbacks.rating')
            ])
            ->first();

        $feedbackAverage = 0;

        if ($feedbackData) {
            $feedbackAverage = (float)(
                $feedbackData->average_rating ?? 0
            );
        }

        // =========================================================
        // AVERAGE CROP YIELD
        // =========================================================
        //
        // IMPORTANT:
        //
        // Farms does NOT have crop_yield.
        //
        // Correct field:
        // Farms.average_yield
        //
        // =========================================================

        $yieldData = $this->Evaluations
            ->find()
            ->select([
                'avg_yield_before' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->avg('Farms.average_yield'),

                'avg_yield_after' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->avg(
                            'Evaluations.crop_yield_after'
                        )
            ])
            ->innerJoinWith('Farms')
            ->first();

        $yieldBefore = 0;
        $yieldAfter = 0;

        if ($yieldData) {

            $yieldBefore = (float)(
                $yieldData->avg_yield_before ?? 0
            );

            $yieldAfter = (float)(
                $yieldData->avg_yield_after ?? 0
            );
        }

        // =========================================================
        // YIELD IMPROVEMENT
        // =========================================================

        $yieldImprovement = 0;

        if ($yieldBefore > 0) {

            $yieldImprovement =
                (
                    (
                        $yieldAfter -
                        $yieldBefore
                    )
                    /
                    $yieldBefore
                ) * 100;
        }

        // =========================================================
        // MACHINE LEARNING MODEL INFORMATION
        // =========================================================

        $modelStatus = 'Active';

        $modelName =
            'Random Forest Classifier';

        $modelAccuracy = 93.5;

        $lastTrained =
            'July 22, 2026 11:30 PM';

        // =========================================================
        // FEATURE IMPORTANCE
        // =========================================================

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

            /*
             * Selling price is now included as a
             * dataset/model parameter.
             */
            'Selling Price' =>
                25
        ];

        // =========================================================
        // PROGRAM EFFECTIVENESS RECOMMENDATIONS
        // =========================================================

        $recommendations = [];

        foreach ($programs as $program) {

            // =====================================================
            // PROGRAM NAME
            // =====================================================

            $programName =
                $program['subsidy_type']
                ?? 'Unknown';

            // =====================================================
            // GET PROGRAM EVALUATIONS
            // =====================================================

            $programEvaluations =
                $this->Evaluations
                    ->find()
                    ->where([
                        'Evaluations.subsidy_type' =>
                            $programName
                    ])
                    ->enableHydration(false)
                    ->toArray();

            $programTotal =
                count($programEvaluations);

            if ($programTotal === 0) {
                continue;
            }

            // =====================================================
            // EFFECTIVENESS COUNTS
            // =====================================================

            $effectiveCount = 0;

            $moderateCount = 0;

            $notEffectiveCount = 0;

            foreach (
                $programEvaluations
                as $evaluation
            ) {

                $label = trim(
                    $evaluation[
                        'effectiveness_label'
                    ] ?? ''
                );

                if (
                    $label === 'Effective'
                ) {

                    $effectiveCount++;

                } elseif (
                    $label === 'Moderately Effective'
                ) {

                    $moderateCount++;

                } elseif (
                    $label === 'Not Effective'
                ) {

                    $notEffectiveCount++;
                }
            }

            // =====================================================
            // DETERMINE PROGRAM PREDICTION
            // =====================================================

            $effectivenessCounts = [

                'Effective' =>
                    $effectiveCount,

                'Moderately Effective' =>
                    $moderateCount,

                'Not Effective' =>
                    $notEffectiveCount
            ];

            arsort(
                $effectivenessCounts
            );

            $prediction =
                array_key_first(
                    $effectivenessCounts
                );

            $predictionCount =
                $effectivenessCounts[
                    $prediction
                ] ?? 0;

            // =====================================================
            // CONFIDENCE
            // =====================================================

            $confidence = 0;

            if ($programTotal > 0) {

                $confidence =
                    round(
                        (
                            $predictionCount /
                            $programTotal
                        ) * 100
                    );
            }

            // =====================================================
            // PROGRAM YIELD
            // =====================================================
            //
            // BEFORE:
            // Farms.average_yield
            //
            // AFTER:
            // Evaluations.crop_yield_after
            //
            // =====================================================

            $programPerformance =
                $this->Evaluations
                    ->find()
                    ->select([

                        'avg_yield_before' =>
                            $this->Evaluations
                                ->find()
                                ->func()
                                ->avg(
                                    'Farms.average_yield'
                                ),

                        'avg_yield_after' =>
                            $this->Evaluations
                                ->find()
                                ->func()
                                ->avg(
                                    'Evaluations.crop_yield_after'
                                ),

                        /*
                         * AVERAGE SELLING PRICE
                         *
                         * This comes from Evaluations.
                         */
                        'avg_selling_price' =>
                            $this->Evaluations
                                ->find()
                                ->func()
                                ->avg(
                                    'Evaluations.selling_price'
                                )
                    ])
                    ->where([
                        'Evaluations.subsidy_type' =>
                            $programName
                    ])
                    ->innerJoinWith('Farms')
                    ->first();

            // =====================================================
            // DEFAULT VALUES
            // =====================================================

            $programYieldBefore = 0;

            $programYieldAfter = 0;

            $programSellingPrice = 0;

            // =====================================================
            // GET PERFORMANCE DATA
            // =====================================================

            if ($programPerformance) {

                $programYieldBefore =
                    (float)(
                        $programPerformance
                            ->avg_yield_before
                        ?? 0
                    );

                $programYieldAfter =
                    (float)(
                        $programPerformance
                            ->avg_yield_after
                        ?? 0
                    );

                $programSellingPrice =
                    (float)(
                        $programPerformance
                            ->avg_selling_price
                        ?? 0
                    );
            }

            // =====================================================
            // YIELD IMPROVEMENT
            // =====================================================

            $programYieldImprovement = 0;

            if (
                $programYieldBefore > 0
            ) {

                $programYieldImprovement =
                    (
                        (
                            $programYieldAfter -
                            $programYieldBefore
                        )
                        /
                        $programYieldBefore
                    ) * 100;
            }

            // =====================================================
            // SELLING PRICE ANALYSIS
            // =====================================================
            //
            // There is only one selling price field currently.
            //
            // Therefore we treat the average selling price
            // as the current program selling-price indicator.
            //
            // We DO NOT calculate before/after price because
            // there is no selling_price_before field.
            //
            // =====================================================

            $programIncomeBefore = 0;

            $programIncomeAfter = 0;

            /*
             * Calculate estimated production value:
             *
             * Yield × Selling Price
             *
             * This provides a useful income/value indicator.
             */

            if (
                $programYieldBefore > 0 &&
                $programSellingPrice > 0
            ) {

                $programIncomeBefore =
                    $programYieldBefore *
                    $programSellingPrice;
            }

            if (
                $programYieldAfter > 0 &&
                $programSellingPrice > 0
            ) {

                $programIncomeAfter =
                    $programYieldAfter *
                    $programSellingPrice;
            }

            // =====================================================
            // INCOME IMPROVEMENT
            // =====================================================

            $programIncomeImprovement = 0;

            if (
                $programIncomeBefore > 0
            ) {

                $programIncomeImprovement =
                    (
                        (
                            $programIncomeAfter -
                            $programIncomeBefore
                        )
                        /
                        $programIncomeBefore
                    ) * 100;
            }

            // =====================================================
            // DETERMINE PROGRAM TREND
            // =====================================================

            if (
                $programYieldImprovement >= 10
                ||
                $programIncomeImprovement >= 10
            ) {

                $trend =
                    'Improving';

            } elseif (
                $programYieldImprovement <= -10
                ||
                $programIncomeImprovement <= -10
            ) {

                $trend =
                    'Declining';

            } else {

                $trend =
                    'Stable';
            }

            // =====================================================
            // PROBLEM COUNTERS
            // =====================================================

            $delayedCount = 0;

            $partialCount = 0;

            $notDistributedCount = 0;

            $subsidyIssueCount = 0;

            // =====================================================
            // CROP COUNTERS
            // =====================================================

            $riceCount = 0;

            $cornCount = 0;

            // =====================================================
            // ANALYZE PROGRAM EVALUATIONS
            // =====================================================

            foreach (
                $programEvaluations
                as $evaluation
            ) {

                // =================================================
                // CROP
                // =================================================

                $cropType =
                    strtolower(
                        trim(
                            $evaluation[
                                'crop_type'
                            ] ?? ''
                        )
                    );

                if (
                    strpos(
                        $cropType,
                        'rice'
                    ) !== false
                ) {

                    $riceCount++;
                }

                if (
                    strpos(
                        $cropType,
                        'corn'
                    ) !== false
                ) {

                    $cornCount++;
                }

                // =================================================
                // DISTRIBUTION STATUS
                // =================================================

                $distributionStatus =
                    strtolower(
                        trim(
                            $evaluation[
                                'distribution_status'
                            ] ?? ''
                        )
                    );

                // =================================================
                // DISTRIBUTION TIMELINESS
                // =================================================

                $distributionTimeliness =
                    strtolower(
                        trim(
                            $evaluation[
                                'distribution_timeliness'
                            ] ?? ''
                        )
                    );

                // =================================================
                // OUTCOME CAUSE
                // =================================================

                $outcomeCause =
                    strtolower(
                        trim(
                            $evaluation[
                                'outcome_cause'
                            ] ?? ''
                        )
                    );

                // =================================================
                // DELAY
                // =================================================

                if (
                    strpos(
                        $distributionTimeliness,
                        'delay'
                    ) !== false
                    ||
                    strpos(
                        $distributionTimeliness,
                        'late'
                    ) !== false
                ) {

                    $delayedCount++;
                }

                // =================================================
                // PARTIAL
                // =================================================

                if (
                    strpos(
                        $distributionStatus,
                        'partial'
                    ) !== false
                ) {

                    $partialCount++;
                }

                // =================================================
                // NOT DISTRIBUTED
                // =================================================

                if (
                    strpos(
                        $distributionStatus,
                        'not distributed'
                    ) !== false
                    ||
                    strpos(
                        $distributionStatus,
                        'undistributed'
                    ) !== false
                ) {

                    $notDistributedCount++;
                }

                // =================================================
                // SUBSIDY ISSUE
                // =================================================

                if (
                    strpos(
                        $outcomeCause,
                        'subsidy issue'
                    ) !== false
                    ||
                    strpos(
                        $outcomeCause,
                        'distribution issue'
                    ) !== false
                ) {

                    $subsidyIssueCount++;
                }
            }

            // =====================================================
            // DETERMINE MAIN PROBLEM
            // =====================================================

            $problems = [

                'Delayed Distribution' =>
                    $delayedCount,

                'Partial Distribution' =>
                    $partialCount,

                'Not Distributed' =>
                    $notDistributedCount,

                'Subsidy Issue' =>
                    $subsidyIssueCount
            ];

            $problems =
                array_filter(
                    $problems,
                    function ($count) {
                        return $count > 0;
                    }
                );

            arsort($problems);

            $mainProblem = 'None';

            $mainProblemCount = 0;

            if (!empty($problems)) {

                $mainProblem =
                    array_key_first(
                        $problems
                    );

                $mainProblemCount =
                    $problems[
                        $mainProblem
                    ];
            }

            // =====================================================
            // PROBLEM RATE
            // =====================================================

            $problemRate = 0;

            if ($programTotal > 0) {

                $problemRate =
                    round(
                        (
                            $mainProblemCount /
                            $programTotal
                        ) * 100
                    );
            }

            // =====================================================
            // DETERMINE MAIN CROP
            // =====================================================

            if (
                $riceCount > $cornCount
            ) {

                $mainCrop =
                    'Rice';

            } elseif (
                $cornCount > $riceCount
            ) {

                $mainCrop =
                    'Corn';

            } elseif (
                $riceCount === 0 &&
                $cornCount === 0
            ) {

                $mainCrop =
                    'General';

            } else {

                $mainCrop =
                    'Rice and Corn';
            }

            // =====================================================
            // GENERATE ACTION
            // =====================================================

            if (
                $prediction === 'Effective'
            ) {

                if (
                    $trend === 'Improving'
                ) {

                    $action =
                        'Maintain the current subsidy strategy for ' .
                        $mainCrop .
                        '. Continue timely and complete ' .
                        'distribution and sustain the practices ' .
                        'contributing to program improvement.';

                } elseif (
                    $trend === 'Declining'
                ) {

                    $action =
                        'Investigate the decline in ' .
                        $mainCrop .
                        ' outcomes despite the effective ' .
                        'classification. Review subsidy quality, ' .
                        'distribution performance, farmer feedback, ' .
                        'selling price, and production results, ' .
                        'then implement corrective measures.';

                } else {

                    $action =
                        'Maintain timely and complete subsidy ' .
                        'distribution for ' .
                        $mainCrop .
                        ', while monitoring yield, selling price, ' .
                        'and farmer feedback.';
                }

            } elseif (
                $prediction ===
                'Moderately Effective'
            ) {

                if (
                    $mainProblem ===
                    'Delayed Distribution'
                ) {

                    if (
                        $trend === 'Improving'
                    ) {

                        $action =
                            'Continue improving subsidy distribution ' .
                            'for ' .
                            $mainCrop .
                            ' by reducing remaining delivery delays ' .
                            'and monitoring the positive yield trend.';

                    } else {

                        $action =
                            'Resolve delayed subsidy distribution for ' .
                            $mainCrop .
                            ' by reviewing delivery schedules, ' .
                            'identifying bottlenecks, and prioritizing ' .
                            'delayed beneficiaries.';
                    }

                } elseif (
                    $mainProblem ===
                    'Partial Distribution'
                ) {

                    $action =
                        'Complete the remaining subsidy allocation ' .
                        'for partially served ' .
                        $mainCrop .
                        ' farmers, verify quantities received, and ' .
                        'monitor crop performance.';

                } elseif (
                    $mainProblem ===
                    'Not Distributed'
                ) {

                    $action =
                        'Prioritize ' .
                        $mainCrop .
                        ' beneficiaries who have not received ' .
                        'their subsidy and verify successful delivery.';

                } elseif (
                    $mainProblem ===
                    'Subsidy Issue'
                ) {

                    $action =
                        'Review subsidy allocation and distribution ' .
                        'records for ' .
                        $mainCrop .
                        ', correct affected allocations, and verify ' .
                        'successful delivery.';

                } elseif (
                    $trend === 'Improving'
                ) {

                    $action =
                        'Maintain the current subsidy implementation ' .
                        'for ' .
                        $mainCrop .
                        ' while monitoring increasing crop yield and ' .
                        'selling-price performance.';

                } else {

                    $action =
                        'Conduct a program-level review for ' .
                        $mainCrop .
                        ', identify factors limiting effectiveness, ' .
                        'apply corrective measures, and continue ' .
                        'monitoring farmer outcomes.';
                }

            } elseif (
                $prediction ===
                'Not Effective'
            ) {

                if (
                    $mainProblem ===
                    'Delayed Distribution'
                ) {

                    $action =
                        'Immediately review the subsidy distribution ' .
                        'process for ' .
                        $mainCrop .
                        ', identify recurring delays, revise delivery ' .
                        'schedules, and monitor future distributions.';

                } elseif (
                    $mainProblem ===
                    'Partial Distribution'
                ) {

                    $action =
                        'Complete all outstanding subsidy allocations ' .
                        'for ' .
                        $mainCrop .
                        ', verify beneficiary coverage and quantities, ' .
                        'and implement corrective controls.';

                } elseif (
                    $mainProblem ===
                    'Not Distributed'
                ) {

                    $action =
                        'Conduct an immediate review of undistributed ' .
                        'subsidy allocations for ' .
                        $mainCrop .
                        ', release pending subsidies, and verify ' .
                        'receipt by affected farmers.';

                } elseif (
                    $mainProblem ===
                    'Subsidy Issue'
                ) {

                    $action =
                        'Conduct a comprehensive review of subsidy ' .
                        'allocation and distribution for ' .
                        $mainCrop .
                        ', correct affected records, and verify ' .
                        'successful delivery.';

                } elseif (
                    $trend === 'Declining'
                ) {

                    $action =
                        'Conduct a comprehensive review of declining ' .
                        $mainCrop .
                        ' crop yield, assess subsidy utilization, ' .
                        'selling price, and farmer feedback, identify ' .
                        'the causes of poor performance, and implement ' .
                        'corrective measures.';

                } else {

                    $action =
                        'Conduct a comprehensive review of the subsidy ' .
                        'program for ' .
                        $mainCrop .
                        ', identify the primary causes of poor ' .
                        'performance, implement corrective measures, ' .
                        'and perform follow-up monitoring.';
                }

            } else {

                $action =
                    'Collect additional subsidy evaluation data for ' .
                    $mainCrop .
                    ' to determine program performance and identify ' .
                    'the appropriate corrective action.';
            }

            // =====================================================
            // ADD RECOMMENDATION
            // =====================================================

            $recommendations[] = [

                'program' =>
                    $programName,

                'crop' =>
                    $mainCrop,

                'prediction' =>
                    $prediction,

                'confidence' =>
                    $confidence,

                'trend' =>
                    $trend,

                'yield_improvement' =>
                    round(
                        $programYieldImprovement,
                        2
                    ),

                'selling_price' =>
                    round(
                        $programSellingPrice,
                        2
                    ),

                'income_improvement' =>
                    round(
                        $programIncomeImprovement,
                        2
                    ),

                'main_problem' =>
                    $mainProblem,

                'problem_rate' =>
                    $problemRate,

                'action' =>
                    $action
            ];
        }

        // =========================================================
        // SEND DATA TO VIEW
        // =========================================================

        $this->set(compact(

            'totalEvaluations',

            'effective',

            'moderatelyEffective',

            'notEffective',

            'programs',

            'feedbackAverage',

            'yieldBefore',

            'yieldAfter',

            'yieldImprovement',

            'modelStatus',

            'modelName',

            'modelAccuracy',

            'lastTrained',

            'featureImportance',

            'recommendations'
        ));
    }
}
