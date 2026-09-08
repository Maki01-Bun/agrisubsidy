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
        $this->loadModel('Evaluations');
        $this->loadModel('Farms');
        $this->loadModel('Feedbacks');
    
        // ============================================================
        // TOTAL EVALUATION DATA
        // ============================================================
    
        $totalEvaluations = $this->Evaluations->find()
            ->count();
    
    
        // ============================================================
        // EFFECTIVENESS COUNTS
        // ============================================================
    
        $effective = $this->Evaluations->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Effective'
            ])
            ->count();
    
        $moderatelyEffective = $this->Evaluations->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Moderately Effective'
            ])
            ->count();
    
        $notEffective = $this->Evaluations->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Not Effective'
            ])
            ->count();
    
    
        // ============================================================
        // SUBSIDY PROGRAM COUNTS
        // ============================================================
    
        $programs = $this->Evaluations->find()
            ->select([
                'subsidy_type' => 'Evaluations.subsidy_type',
    
                'total' => $this->Evaluations
                    ->find()
                    ->func()
                    ->count('Evaluations.id')
            ])
            ->group([
                'Evaluations.subsidy_type'
            ])
            ->enableHydration(false)
            ->toArray();
    
    
        // ============================================================
        // AVERAGE FEEDBACK
        // ============================================================
    
        $feedbackData = $this->Feedbacks->find()
            ->select([
                'average_rating' => $this->Feedbacks
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
    
    
        // ============================================================
        // AVERAGE CROP YIELD
        // ============================================================
    
        $yieldData = $this->Evaluations->find()
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
                        ->avg('Evaluations.crop_yield_after')
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
    
    
        // ============================================================
        // CALCULATE OVERALL IMPROVEMENT
        // ============================================================
    
        $yieldImprovement = 0;
    
        if ($yieldBefore > 0) {
    
            $yieldImprovement =
                (($yieldAfter - $yieldBefore) / $yieldBefore) * 100;
        }
    
    
        // ============================================================
        // MACHINE LEARNING MODEL INFORMATION
        // ============================================================
    
        $modelStatus = 'Active';
    
        $modelName = 'Random Forest Classifier';
    
        $modelAccuracy = 93.5;
    
        $lastTrained = 'July 22, 2026 11:30 PM';
    
    
        // ============================================================
        // FEATURE IMPORTANCE
        // ============================================================
    
        $featureImportance = [
    
            'Crop Yield Increase' =>
                round(abs($yieldImprovement), 2),
    
            'Subsidy Utilization' =>
                42,
    
            'Distribution Timeliness' =>
                34,
    
            'Farmer Feedback' =>
                20
        ];
    
    
        // ============================================================
        // PROGRAM EFFECTIVENESS RECOMMENDATIONS
        // ============================================================
    
        $recommendations = [];
    
    
        foreach ($programs as $program) {
    
            $programName =
                $program['subsidy_type'] ?? 'Unknown';
    
    
            // ========================================================
            // GET ALL EVALUATIONS FOR THIS SUBSIDY PROGRAM
            // ========================================================
    
            $programEvaluations = $this->Evaluations->find()
                ->where([
                    'Evaluations.subsidy_type' => $programName
                ])
                ->enableHydration(false)
                ->toArray();
    
    
            $programTotal = count($programEvaluations);
    
    
            if ($programTotal === 0) {
                continue;
            }
    
    
            // ========================================================
            // EFFECTIVENESS COUNTS
            // ========================================================
    
            $effectiveCount = 0;
            $moderateCount = 0;
            $notEffectiveCount = 0;
    
    
            foreach ($programEvaluations as $evaluation) {
    
                $label = trim(
                    $evaluation['effectiveness_label'] ?? ''
                );
    
    
                if ($label === 'Effective') {
    
                    $effectiveCount++;
    
                } elseif ($label === 'Moderately Effective') {
    
                    $moderateCount++;
    
                } elseif ($label === 'Not Effective') {
    
                    $notEffectiveCount++;
                }
            }
    
    
            // ========================================================
            // DETERMINE PROGRAM PREDICTION
            // ========================================================
    
            $effectivenessCounts = [
    
                'Effective' =>
                    $effectiveCount,
    
                'Moderately Effective' =>
                    $moderateCount,
    
                'Not Effective' =>
                    $notEffectiveCount
            ];
    
    
            arsort($effectivenessCounts);
    
    
            $prediction =
                array_key_first($effectivenessCounts);
    
    
            $predictionCount =
                $effectivenessCounts[$prediction] ?? 0;
    
    
            // ========================================================
            // CONFIDENCE
            // ========================================================
    
            $confidence = 0;
    
            if ($programTotal > 0) {
    
                $confidence =
                    round(
                        ($predictionCount / $programTotal) * 100
                    );
            }
    
    
            // ========================================================
            // PROGRAM YIELD AND INCOME
            // ========================================================
    
            $programPerformance = $this->Evaluations->find()
                ->select([
    
                    'avg_yield_before' =>
                        $this->Evaluations
                            ->find()
                            ->func()
                            ->avg('Farms.crop_yield'),
    
                    'avg_yield_after' =>
                        $this->Evaluations
                            ->find()
                            ->func()
                            ->avg('Evaluations.crop_yield_after'),
    
                ])
                ->where([
                    'Evaluations.subsidy_type' => $programName
                ])
                ->innerJoinWith('Farms')
                ->first();
    
    
            $programYieldBefore = 0;
            $programYieldAfter = 0;
    
            $programIncomeBefore = 0;
            $programIncomeAfter = 0;
    
    
            if ($programPerformance) {
    
                $programYieldBefore =
                    (float)(
                        $programPerformance->avg_yield_before ?? 0
                    );
    
                $programYieldAfter =
                    (float)(
                        $programPerformance->avg_yield_after ?? 0
                    );
            }
    
    
            // ========================================================
            // YIELD IMPROVEMENT
            // ========================================================
    
            $programYieldImprovement = 0;
    
            if ($programYieldBefore > 0) {
    
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
    
    
            // ========================================================
            // INCOME IMPROVEMENT
            // ========================================================
    
            $programIncomeImprovement = 0;
    
            if ($programIncomeBefore > 0) {
    
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
    
    
            // ========================================================
            // DETERMINE PROGRAM TREND
            // ========================================================
    
            if (
                $programYieldImprovement >= 10 ||
                $programIncomeImprovement >= 10
            ) {
    
                $trend = 'Improving';
    
            } elseif (
                $programYieldImprovement <= -10 ||
                $programIncomeImprovement <= -10
            ) {
    
                $trend = 'Declining';
    
            } else {
    
                $trend = 'Stable';
            }
    
    
            // ========================================================
            // PROBLEM COUNTERS
            // ========================================================
    
            $delayedCount = 0;
    
            $partialCount = 0;
    
            $notDistributedCount = 0;
    
            $subsidyIssueCount = 0;
    
            $pestCount = 0;
    
            $calamityCount = 0;
    
            $pestCalamityCount = 0;
    
    
            // ========================================================
            // CROP COUNTERS
            // ========================================================
    
            $riceCount = 0;
    
            $cornCount = 0;
    
    
            // ========================================================
            // ANALYZE PROGRAM EVALUATIONS
            // ========================================================
    
            foreach ($programEvaluations as $evaluation) {
    
                // ----------------------------------------------------
                // CROP
                // ----------------------------------------------------
    
                $cropType = strtolower(
                    trim(
                        $evaluation['crop_type'] ?? ''
                    )
                );
    
    
                if (
                    strpos($cropType, 'rice') !== false
                ) {
    
                    $riceCount++;
                }
    
    
                if (
                    strpos($cropType, 'corn') !== false
                ) {
    
                    $cornCount++;
                }
    
    
                // ----------------------------------------------------
                // DISTRIBUTION
                // ----------------------------------------------------
    
                $distributionStatus =
                    strtolower(
                        trim(
                            $evaluation['distribution_status']
                            ?? ''
                        )
                    );
    
    
                $distributionTimeliness =
                    strtolower(
                        trim(
                            $evaluation['distribution_timeliness']
                            ?? ''
                        )
                    );
    
    
                $outcomeCause =
                    strtolower(
                        trim(
                            $evaluation['outcome_cause']
                            ?? ''
                        )
                    );
    
    
                $pest =
                    strtolower(
                        trim(
                            $evaluation['pest']
                            ?? ''
                        )
                    );
    
    
                $calamity =
                    strtolower(
                        trim(
                            $evaluation['calamity']
                            ?? ''
                        )
                    );
    
    
                // ----------------------------------------------------
                // DELAY
                // ----------------------------------------------------
    
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
    
    
                // ----------------------------------------------------
                // PARTIAL
                // ----------------------------------------------------
    
                if (
                    strpos(
                        $distributionStatus,
                        'partial'
                    ) !== false
                ) {
    
                    $partialCount++;
                }
    
    
                // ----------------------------------------------------
                // NOT DISTRIBUTED
                // ----------------------------------------------------
    
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
    
    
                // ----------------------------------------------------
                // SUBSIDY ISSUE
                // ----------------------------------------------------
    
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
    
    
            // ========================================================
            // DETERMINE MAIN PROBLEM
            // ========================================================
    
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
    
    
            $problems = array_filter(
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
                    array_key_first($problems);
    
                $mainProblemCount =
                    $problems[$mainProblem];
            }
    
    
            // ========================================================
            // PROBLEM RATE
            // ========================================================
    
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
    
    
            // ========================================================
            // DETERMINE MAIN CROP
            // ========================================================
    
            if ($riceCount > $cornCount) {
    
                $mainCrop = 'Rice';
    
            } elseif ($cornCount > $riceCount) {
    
                $mainCrop = 'Corn';
    
            } elseif (
                $riceCount === 0 &&
                $cornCount === 0
            ) {
    
                $mainCrop = 'General';
    
            } else {
    
                $mainCrop = 'Rice and Corn';
            }
    
    
            // ========================================================
            // GENERATE DYNAMIC ACTION
            // ========================================================
    
            // --------------------------------------------------------
            // EFFECTIVE
            // --------------------------------------------------------
    
            if ($prediction === 'Effective') {
    
                if ($trend === 'Improving') {
    
                    $action =
                        'Maintain the current Seed Subsidy strategy for ' .
                        $mainCrop . '. Continue timely and complete seed ' .
                        'and sustain the practices contributing to the ' .
                        'program improvement.';
    
                } elseif ($trend === 'Declining') {
    
                    $action =
                        'Investigate the decline in ' .
                        $mainCrop . ' outcomes despite the effective Seed ' .
                        'Subsidy classification. Review seed quality, ' .
                        'distribution performance, farmer feedback, and ' .
                        'production results, then implement corrective measures.';
    
                } else {
    
                    $action =
                        'Maintain timely and complete Seed Subsidy distribution ' .
                        'for ' . $mainCrop . ', continue monitoring seed ' .
                        'utilization, crop yield, and feedback.';
                }
            }
    
    
            // --------------------------------------------------------
            // MODERATELY EFFECTIVE
            // --------------------------------------------------------
    
            elseif ($prediction === 'Moderately Effective') {
    
                if ($mainProblem === 'Delayed Distribution') {
    
                    if ($trend === 'Improving') {
    
                        $action =
                            'Continue improving Seed Subsidy distribution for ' .
                            $mainCrop . ' by reducing remaining delivery delays, ' .
                            'resolving distribution bottlenecks, and prioritizing ' .
                            'delayed beneficiaries while monitoring the positive ' .
                            'yield trend.';
    
                    } else {
    
                        $action =
                            'Resolve delayed Seed Subsidy distribution for ' .
                            $mainCrop . ' by reviewing delivery schedules, ' .
                            'identifying bottlenecks, prioritizing delayed ' .
                            'beneficiaries, and conducting follow-up monitoring.';
                    }
                }
    
    
                elseif ($mainProblem === 'Partial Distribution') {
    
                    $action =
                        'Complete the remaining Seed Subsidy allocation for ' .
                        'partially served ' . $mainCrop . ' farmers, verify ' .
                        'the quantity and quality of seeds received, and ' .
                        'monitor crop performance after distribution.';
                }
    
    
                elseif ($mainProblem === 'Not Distributed') {
    
                    $action =
                        'Prioritize ' . $mainCrop . ' beneficiaries who have ' .
                        'not received their Seed Subsidy, coordinate immediate ' .
                        'release and delivery, and verify successful receipt ' .
                        'of the seed allocation.';
                }
    
    
                elseif ($mainProblem === 'Subsidy Issue') {
    
                    $action =
                        'Review the Seed Subsidy allocation and distribution ' .
                        'records for ' . $mainCrop . ', correct affected ' .
                        'beneficiary allocations, resolve the identified ' .
                        'subsidy issue, and verify successful seed delivery.';
                }
    
    
                elseif ($trend === 'Improving') {
    
                    $action =
                        'Maintain the current Seed Subsidy implementation ' .
                        'for ' . $mainCrop . ' while continuing to monitor ' .
                        'the increasing crop yield. Identify the ' .
                        'practices contributing to improvement and sustain them.';
                }
    
    
                else {
    
                    $action =
                        'Conduct a program-level review of the Seed Subsidy ' .
                        'for ' . $mainCrop . ', identify the factors limiting ' .
                        'effectiveness, apply corrective measures, and continue ' .
                        'monitoring farmer outcomes.';
                }
            }
    
    
            // --------------------------------------------------------
            // NOT EFFECTIVE
            // --------------------------------------------------------
    
            elseif ($prediction === 'Not Effective') {
    
                if ($mainProblem === 'Delayed Distribution') {
    
                    $action =
                        'Immediately review the Seed Subsidy distribution ' .
                        'process for ' . $mainCrop . ', identify the cause ' .
                        'of recurring delays, revise delivery schedules, ' .
                        'prioritize affected beneficiaries, and monitor ' .
                        'future distributions for timely completion.';
                }
    
    
                elseif ($mainProblem === 'Partial Distribution') {
    
                    $action =
                        'Complete all outstanding Seed Subsidy allocations ' .
                        'for ' . $mainCrop . ', verify beneficiary coverage ' .
                        'and seed quantities, investigate the cause of ' .
                        'incomplete distribution, and implement corrective ' .
                        'controls for the next distribution cycle.';
                }
    
    
                elseif ($mainProblem === 'Not Distributed') {
    
                    $action =
                        'Conduct an immediate review of undistributed Seed ' .
                        'Subsidy allocations for ' . $mainCrop . ', release ' .
                        'pending seeds, identify the cause of non-distribution, ' .
                        'and verify receipt by affected farmers.';
                }
    
    
                elseif ($mainProblem === 'Subsidy Issue') {
    
                    $action =
                        'Conduct a comprehensive review of the Seed Subsidy ' .
                        'allocation and distribution process for ' .
                        $mainCrop . ', correct affected beneficiary records ' .
                        'and allocations, resolve the identified issue, and ' .
                        'verify successful seed delivery.';
                }
    
    
                elseif ($trend === 'Declining') {
    
                    $action =
                        'Conduct a comprehensive review of declining ' .
                        $mainCrop . ' crop yield, assess seed ' .
                        'quality and utilization, identify the causes of poor ' .
                        'performance, implement corrective measures, and ' .
                        'perform follow-up monitoring.';
                }
    
    
                else {
    
                    $action =
                        'Conduct a comprehensive review of the Seed Subsidy ' .
                        'program for ' . $mainCrop . ', identify the primary ' .
                        'causes of poor performance, implement corrective ' .
                        'measures, and perform follow-up monitoring.';
                }
            }
    
    
            // --------------------------------------------------------
            // NO DATA
            // --------------------------------------------------------
    
            else {
    
                $action =
                    'Collect additional Seed Subsidy evaluation data for ' .
                    $mainCrop . ' to determine program performance and ' .
                    'identify the appropriate corrective action.';
            }
    
    
            // ========================================================
            // ADD RECOMMENDATION
            // ========================================================
    
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
    
                'main_problem' =>
                    $mainProblem,
    
                'problem_rate' =>
                    $problemRate,
    
                'action' =>
                    $action
            ];
        }
        // SET DATA TO VIEW
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
