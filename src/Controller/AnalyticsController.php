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
        public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        $this->loadModel('Evaluations');
        $this->loadModel('Farms');
        $this->loadModel('Feedbacks');
        $this->loadModel('Schedules');
        $this->loadModel('Records');
        $this->loadModel('Farmers');
    }


    /**
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index()
    {
        /*
         * =====================================================
         * LOAD ALL EVALUATIONS
         * =====================================================
         */

        $evaluations = $this->Evaluations->find()
            ->order([
                'Evaluations.id' => 'ASC'
            ])
            ->all()
            ->toArray();


        $totalEvaluations =
            count($evaluations);


        /*
         * =====================================================
         * EFFECTIVENESS COUNTS
         *
         * 0 = Not Effective
         * 1 = Moderately Effective
         * 2 = Effective
         * NULL = Not Yet Predicted
         * =====================================================
         */

        $effective = 0;

        $moderatelyEffective = 0;

        $notEffective = 0;

        $notYetPredicted = 0;


        foreach (
            $evaluations as $evaluation
        ) {

            $value =
                $evaluation->effectiveness_label;


            if (
                $value === null ||
                $value === ''
            ) {

                $notYetPredicted++;

                continue;

            }


            switch (
                (int)$value
            ) {

                case 2:

                    $effective++;

                    break;


                case 1:

                    $moderatelyEffective++;

                    break;


                case 0:

                    $notEffective++;

                    break;


                default:

                    $notYetPredicted++;

                    break;

            }

        }


        $predictedEvaluations =
            $effective
            + $moderatelyEffective
            + $notEffective;


        /*
         * =====================================================
         * FEEDBACK DATA
         * =====================================================
         *
         * feedbacks.answer contains Q1-Q10 JSON.
         *
         * We use feedback_date as the time reference for:
         *
         * - yield trend
         * - effectiveness trend
         * - survey trend
         *
         * because evaluations are connected to feedbacks through
         * evaluations.feedback_id.
         *
         * =====================================================
         */

        $feedbacks =
            $this->Feedbacks->find()
                ->order([
                    'Feedbacks.feedback_date' => 'ASC',
                    'Feedbacks.id' => 'ASC'
                ])
                ->all()
                ->toArray();


        /*
         * =====================================================
         * MAP FEEDBACK ID -> FEEDBACK
         * =====================================================
         */

        $feedbackMap = [];


        foreach (
            $feedbacks as $feedback
        ) {

            $feedbackMap[
                (int)$feedback->id
            ] = $feedback;

        }


        /*
         * =====================================================
         * QUESTION SUMMARY
         * =====================================================
         */

        $questionSummary =
            $this->buildQuestionSummary(
                $feedbacks
            );


        /*
         * =====================================================
         * SURVEY AVERAGE
         * =====================================================
         */

        $feedbackTotalScore = 0;

        $feedbackTotalResponses = 0;


        foreach (
            $feedbacks as $feedback
        ) {

            $answers =
                $this->decodeAnswers(
                    $feedback->answer ?? null
                );


            foreach (
                $answers as $answer
            ) {

                $normalized =
                    $this->normalizeAnswer(
                        $answer
                    );


                if (
                    $normalized === null
                ) {
                    continue;
                }


                $feedbackTotalScore +=
                    $normalized;

                $feedbackTotalResponses++;

            }

        }


        $feedbackAverage =
            $feedbackTotalResponses > 0
                ? round(
                    $feedbackTotalScore /
                    $feedbackTotalResponses,
                    2
                )
                : 0;


        /*
         * =====================================================
         * TREND DATA
         * =====================================================
         */

        $yieldTrend =
            $this->buildYieldTrend(
                $evaluations,
                $feedbackMap
            );


        $effectivenessTrend =
            $this->buildEffectivenessTrend(
                $evaluations,
                $feedbackMap
            );


        $surveyTrend =
            $this->buildSurveyTrend(
                $feedbacks
            );


        /*
         * =====================================================
         * DISTRIBUTION DATA
         * =====================================================
         */

        $allRecords =
            $this->Records->find()
                ->order([
                    'Records.id' => 'ASC'
                ])
                ->all()
                ->toArray();


        $distributionTrend =
            $this->buildDistributionTrend(
                $allRecords
            );


        /*
         * =====================================================
         * RICE TYPE SUMMARY
         * =====================================================
         */

        $riceTypeSummary =
            $this->buildRiceTypeSummary(
                $evaluations
            );


        /*
         * =====================================================
         * YIELD SUMMARY
         * =====================================================
         */

        $averageYieldBefore =
            $this->calculateAverage(
                $evaluations,
                'average_yield'
            );


        $averageYieldAfter =
            $this->calculateAverage(
                $evaluations,
                'crop_yield_after'
            );


        $yieldImprovement =
            0;


        if (
            $averageYieldBefore > 0
        ) {

            $yieldImprovement =
                (
                    (
                        $averageYieldAfter
                        -
                        $averageYieldBefore
                    )
                    /
                    $averageYieldBefore
                )
                * 100;

        }


        /*
         * =====================================================
         * SELLING PRICE
         * =====================================================
         */

        $sellingPrices = [];


        foreach (
            $evaluations as $evaluation
        ) {

            if (
                $evaluation->selling_price !== null &&
                $evaluation->selling_price !== ''
            ) {

                $sellingPrices[] =
                    (float)$evaluation->selling_price;

            }

        }


        $averageSellingPrice =
            !empty($sellingPrices)
                ? array_sum($sellingPrices)
                    / count($sellingPrices)
                : 0;


        $minimumSellingPrice =
            !empty($sellingPrices)
                ? min($sellingPrices)
                : 0;


        $maximumSellingPrice =
            !empty($sellingPrices)
                ? max($sellingPrices)
                : 0;


        /*
         * =====================================================
         * RECEIVED RECORDS
         * =====================================================
         */

        $receivedRecords =
            $this->getReceivedRecords();


        /*
         * =====================================================
         * RECEIVED LOCATIONS
         * =====================================================
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
            ) {

                $receivedLocations[] =
                    $location;

            }

        }


        $receivedLocations =
            array_values(
                array_unique(
                    $receivedLocations
                )
            );


        natcasesort(
            $receivedLocations
        );


        $receivedLocations =
            array_values(
                $receivedLocations
            );


        /*
         * =====================================================
         * DISTRIBUTION STATUS SUMMARY
         * =====================================================
         */

        $distributionStatusSummary =
            $this->buildDistributionStatusSummary(
                $allRecords
            );


        /*
         * =====================================================
         * TOTAL SUBSIDY QUANTITY
         * =====================================================
         */

        $totalSubsidyQuantity = 0;

        $receivedQuantityCount = 0;


        foreach (
            $allRecords as $record
        ) {

            $status =
                strtolower(
                    trim(
                        (string)(
                            $record->status
                            ?? ''
                        )
                    )
                );


            if (
                $status === 'received'
            ) {

                $quantity =
                    $record->quantity ?? null;


                if (
                    is_numeric($quantity)
                ) {

                    $totalSubsidyQuantity +=
                        (float)$quantity;

                    $receivedQuantityCount++;

                }

            }

        }


        $averageSubsidyQuantity =
            $receivedQuantityCount > 0
                ? $totalSubsidyQuantity /
                    $receivedQuantityCount
                : 0;


        /*
         * =====================================================
         * SET VIEW VARIABLES
         * =====================================================
         */

        $this->set([

            /*
             * Main counts
             */

            'totalEvaluations' =>
                $totalEvaluations,

            'predictedEvaluations' =>
                $predictedEvaluations,

            'notYetPredicted' =>
                $notYetPredicted,

            'effective' =>
                $effective,

            'moderatelyEffective' =>
                $moderatelyEffective,

            'notEffective' =>
                $notEffective,


            /*
             * Feedback
             */

            'feedbackAverage' =>
                $feedbackAverage,

            'feedbackTotalResponses' =>
                $feedbackTotalResponses,

            'questionSummary' =>
                $questionSummary,


            /*
             * Trends
             */

            'yieldTrend' =>
                $yieldTrend,

            'effectivenessTrend' =>
                $effectivenessTrend,

            'surveyTrend' =>
                $surveyTrend,

            'distributionTrend' =>
                $distributionTrend,


            /*
             * Yield
             */

            'averageYieldBefore' =>
                $averageYieldBefore,

            'averageYieldAfter' =>
                $averageYieldAfter,

            'yieldImprovement' =>
                $yieldImprovement,


            /*
             * Selling price
             */

            'averageSellingPrice' =>
                $averageSellingPrice,

            'minimumSellingPrice' =>
                $minimumSellingPrice,

            'maximumSellingPrice' =>
                $maximumSellingPrice,


            /*
             * Rice type
             */

            'riceTypeSummary' =>
                $riceTypeSummary,


            /*
             * Distribution
             */

            'receivedRecords' =>
                $receivedRecords,

            'receivedLocations' =>
                $receivedLocations,

            'distributionStatusSummary' =>
                $distributionStatusSummary,

            'totalSubsidyQuantity' =>
                $totalSubsidyQuantity,

            'averageSubsidyQuantity' =>
                $averageSubsidyQuantity

        ]);
    }


    /**
     * =========================================================
     * BUILD YIELD TREND
     * =========================================================
     */
    private function buildYieldTrend(
        array $evaluations,
        array $feedbackMap
    ): array {

        $monthly = [];

        $quarterly = [];


        foreach (
            $evaluations as $evaluation
        ) {

            $feedbackId =
                (int)(
                    $evaluation->feedback_id
                    ?? 0
                );


            if (
                $feedbackId <= 0 ||
                !isset(
                    $feedbackMap[$feedbackId]
                )
            ) {

                continue;

            }


            $feedback =
                $feedbackMap[$feedbackId];


            $date =
                $this->toDateTime(
                    $feedback->feedback_date
                    ?? null
                );


            if (!$date) {
                continue;
            }


            $before =
                $evaluation->average_yield;


            $after =
                $evaluation->crop_yield_after;


            if (
                !is_numeric($before) &&
                !is_numeric($after)
            ) {

                continue;

            }


            $before =
                is_numeric($before)
                    ? (float)$before
                    : 0;


            $after =
                is_numeric($after)
                    ? (float)$after
                    : 0;


            /*
             * MONTH
             */

            $monthKey =
                $date->format('Y-m');


            if (
                !isset(
                    $monthly[$monthKey]
                )
            ) {

                $monthly[$monthKey] = [
                    'period_type' =>
                        'monthly',

                    'period' =>
                        $date->format('M Y'),

                    'sort' =>
                        $monthKey,

                    'before_total' =>
                        0,

                    'after_total' =>
                        0,

                    'count' =>
                        0
                ];

            }


            $monthly[$monthKey]
                ['before_total']
                += $before;


            $monthly[$monthKey]
                ['after_total']
                += $after;


            $monthly[$monthKey]
                ['count']++;


            /*
             * QUARTER
             */

            $quarter =
                (int)(
                    ceil(
                        (int)$date->format('n')
                        / 3
                    )
                );


            $year =
                $date->format('Y');


            $quarterKey =
                $year
                . '-Q'
                . $quarter;


            if (
                !isset(
                    $quarterly[$quarterKey]
                )
            ) {

                $quarterly[$quarterKey] = [
                    'period_type' =>
                        'quarterly',

                    'period' =>
                        'Q'
                        . $quarter
                        . ' '
                        . $year,

                    'sort' =>
                        $year
                        . '-'
                        . str_pad(
                            (string)$quarter,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'before_total' =>
                        0,

                    'after_total' =>
                        0,

                    'count' =>
                        0
                ];

            }


            $quarterly[$quarterKey]
                ['before_total']
                += $before;


            $quarterly[$quarterKey]
                ['after_total']
                += $after;


            $quarterly[$quarterKey]
                ['count']++;

        }


        return $this->finalizeYieldTrend(
            array_merge(
                array_values($monthly),
                array_values($quarterly)
            )
        );
    }


    /**
     * =========================================================
     * FINALIZE YIELD TREND
     * =========================================================
     */
    private function finalizeYieldTrend(
        array $data
    ): array {

        foreach (
            $data as &$row
        ) {

            $count =
                (int)(
                    $row['count']
                    ?? 0
                );


            $row['yield_before'] =
                $count > 0
                    ? round(
                        $row['before_total']
                        / $count,
                        2
                    )
                    : 0;


            $row['yield_after'] =
                $count > 0
                    ? round(
                        $row['after_total']
                        / $count,
                        2
                    )
                    : 0;


            unset(
                $row['before_total'],
                $row['after_total'],
                $row['count']
            );

        }

        unset($row);


        usort(
            $data,
            function (
                $a,
                $b
            ) {

                if (
                    $a['period_type']
                    ===
                    $b['period_type']
                ) {

                    return strcmp(
                        $a['sort'],
                        $b['sort']
                    );

                }


                return strcmp(
                    $a['period_type'],
                    $b['period_type']
                );

            }
        );


        foreach (
            $data as &$row
        ) {

            unset(
                $row['sort']
            );

        }

        unset($row);


        return $data;
    }


    /**
     * =========================================================
     * BUILD EFFECTIVENESS TREND
     * =========================================================
     */
    private function buildEffectivenessTrend(
        array $evaluations,
        array $feedbackMap
    ): array {

        $monthly = [];

        $quarterly = [];


        foreach (
            $evaluations as $evaluation
        ) {

            $feedbackId =
                (int)(
                    $evaluation->feedback_id
                    ?? 0
                );


            if (
                $feedbackId <= 0 ||
                !isset(
                    $feedbackMap[$feedbackId]
                )
            ) {

                continue;

            }


            $feedback =
                $feedbackMap[$feedbackId];


            $date =
                $this->toDateTime(
                    $feedback->feedback_date
                    ?? null
                );


            if (!$date) {
                continue;
            }


            $value =
                $evaluation->effectiveness_label;


            /*
             * MONTH
             */

            $monthKey =
                $date->format('Y-m');


            if (
                !isset(
                    $monthly[$monthKey]
                )
            ) {

                $monthly[$monthKey] = [
                    'period_type' =>
                        'monthly',

                    'period' =>
                        $date->format('M Y'),

                    'sort' =>
                        $monthKey,

                    'effective' =>
                        0,

                    'moderately_effective' =>
                        0,

                    'not_effective' =>
                        0,

                    'not_yet_predicted' =>
                        0
                ];

            }


            $this->incrementEffectiveness(
                $monthly[$monthKey],
                $value
            );


            /*
             * QUARTER
             */

            $quarter =
                (int)(
                    ceil(
                        (int)$date->format('n')
                        / 3
                    )
                );


            $year =
                $date->format('Y');


            $quarterKey =
                $year
                . '-Q'
                . $quarter;


            if (
                !isset(
                    $quarterly[$quarterKey]
                )
            ) {

                $quarterly[$quarterKey] = [
                    'period_type' =>
                        'quarterly',

                    'period' =>
                        'Q'
                        . $quarter
                        . ' '
                        . $year,

                    'sort' =>
                        $year
                        . '-'
                        . str_pad(
                            (string)$quarter,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'effective' =>
                        0,

                    'moderately_effective' =>
                        0,

                    'not_effective' =>
                        0,

                    'not_yet_predicted' =>
                        0
                ];

            }


            $this->incrementEffectiveness(
                $quarterly[$quarterKey],
                $value
            );

        }


        $data =
            array_merge(
                array_values($monthly),
                array_values($quarterly)
            );


        usort(
            $data,
            function (
                $a,
                $b
            ) {

                if (
                    $a['period_type']
                    ===
                    $b['period_type']
                ) {

                    return strcmp(
                        $a['sort'],
                        $b['sort']
                    );

                }


                return strcmp(
                    $a['period_type'],
                    $b['period_type']
                );

            }
        );


        foreach (
            $data as &$row
        ) {

            unset(
                $row['sort']
            );

        }

        unset($row);


        return $data;
    }


    /**
     * =========================================================
     * INCREMENT EFFECTIVENESS
     * =========================================================
     */
    private function incrementEffectiveness(
        array &$row,
        mixed $value
    ): void {

        if (
            $value === null ||
            $value === ''
        ) {

            $row['not_yet_predicted']++;

            return;

        }


        switch (
            (int)$value
        ) {

            case 2:

                $row['effective']++;

                break;


            case 1:

                $row['moderately_effective']++;

                break;


            case 0:

                $row['not_effective']++;

                break;


            default:

                $row['not_yet_predicted']++;

                break;

        }

    }


    /**
     * =========================================================
     * BUILD SURVEY TREND
     * =========================================================
     */
    private function buildSurveyTrend(
        array $feedbacks
    ): array {

        $monthly = [];

        $quarterly = [];


        foreach (
            $feedbacks as $feedback
        ) {

            $date =
                $this->toDateTime(
                    $feedback->feedback_date
                    ?? null
                );


            if (!$date) {
                continue;
            }


            $answers =
                $this->decodeAnswers(
                    $feedback->answer ?? null
                );


            $scores = [];


            foreach (
                $answers as $answer
            ) {

                $normalized =
                    $this->normalizeAnswer(
                        $answer
                    );


                if (
                    $normalized !== null
                ) {

                    $scores[] =
                        $normalized;

                }

            }


            if (
                empty($scores)
            ) {

                continue;

            }


            $average =
                array_sum($scores)
                /
                count($scores);


            /*
             * MONTH
             */

            $monthKey =
                $date->format('Y-m');


            if (
                !isset(
                    $monthly[$monthKey]
                )
            ) {

                $monthly[$monthKey] = [
                    'period_type' =>
                        'monthly',

                    'period' =>
                        $date->format('M Y'),

                    'sort' =>
                        $monthKey,

                    'total' =>
                        0,

                    'responses' =>
                        0
                ];

            }


            $monthly[$monthKey]['total']
                += $average;


            $monthly[$monthKey]['responses']++;


            /*
             * QUARTER
             */

            $quarter =
                (int)(
                    ceil(
                        (int)$date->format('n')
                        / 3
                    )
                );


            $year =
                $date->format('Y');


            $quarterKey =
                $year
                . '-Q'
                . $quarter;


            if (
                !isset(
                    $quarterly[$quarterKey]
                )
            ) {

                $quarterly[$quarterKey] = [
                    'period_type' =>
                        'quarterly',

                    'period' =>
                        'Q'
                        . $quarter
                        . ' '
                        . $year,

                    'sort' =>
                        $year
                        . '-'
                        . str_pad(
                            (string)$quarter,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'total' =>
                        0,

                    'responses' =>
                        0
                ];

            }


            $quarterly[$quarterKey]['total']
                += $average;


            $quarterly[$quarterKey]['responses']++;

        }


        $data =
            array_merge(
                array_values($monthly),
                array_values($quarterly)
            );


        foreach (
            $data as &$row
        ) {

            $responses =
                (int)(
                    $row['responses']
                    ?? 0
                );


            $row['average_rating'] =
                $responses > 0
                    ? round(
                        $row['total']
                        /
                        $responses,
                        2
                    )
                    : 0;


            unset(
                $row['total'],
                $row['responses']
            );

        }

        unset($row);


        usort(
            $data,
            function (
                $a,
                $b
            ) {

                if (
                    $a['period_type']
                    ===
                    $b['period_type']
                ) {

                    return strcmp(
                        $a['sort'],
                        $b['sort']
                    );

                }


                return strcmp(
                    $a['period_type'],
                    $b['period_type']
                );

            }
        );


        foreach (
            $data as &$row
        ) {

            unset(
                $row['sort']
            );

        }

        unset($row);


        return $data;
    }


    /**
     * =========================================================
     * BUILD DISTRIBUTION TREND
     * =========================================================
     */
    private function buildDistributionTrend(
        array $records
    ): array {

        $monthly = [];

        $quarterly = [];


        foreach (
            $records as $record
        ) {

            /*
             * Use received_date when available.
             *
             * For records that do not have a received date,
             * use the record created date.
             */

            $dateValue =
                $record->received_date
                ?? null;


            if (
                empty($dateValue)
            ) {

                $dateValue =
                    $record->created
                    ?? null;

            }


            $date =
                $this->toDateTime(
                    $dateValue
                );


            if (!$date) {
                continue;
            }


            $status =
                strtolower(
                    trim(
                        (string)(
                            $record->status
                            ?? ''
                        )
                    )
                );


            /*
             * Normalize status
             */

            if (
                in_array(
                    $status,
                    [
                        'received',
                        'receive'
                    ],
                    true
                )
            ) {

                $statusKey =
                    'received';

            } elseif (
                in_array(
                    $status,
                    [
                        'not received',
                        'not_received',
                        'notreceived'
                    ],
                    true
                )
            ) {

                $statusKey =
                    'not_received';

            } elseif (
                in_array(
                    $status,
                    [
                        're-scheduled',
                        'rescheduled',
                        're scheduled',
                        're_schedule',
                        're-schedule'
                    ],
                    true
                )
            ) {

                $statusKey =
                    'rescheduled';

            } elseif (
                in_array(
                    $status,
                    [
                        'cancelled',
                        'canceled'
                    ],
                    true
                )
            ) {

                $statusKey =
                    'cancelled';

            } else {

                /*
                 * Ignore unknown statuses rather than
                 * incorrectly assigning them.
                 */

                continue;

            }


            /*
             * MONTH
             */

            $monthKey =
                $date->format('Y-m');


            if (
                !isset(
                    $monthly[$monthKey]
                )
            ) {

                $monthly[$monthKey] = [
                    'period_type' =>
                        'monthly',

                    'period' =>
                        $date->format('M Y'),

                    'sort' =>
                        $monthKey,

                    'received' =>
                        0,

                    'not_received' =>
                        0,

                    'rescheduled' =>
                        0,

                    'cancelled' =>
                        0
                ];

            }


            $monthly[$monthKey]
                [$statusKey]++;


            /*
             * QUARTER
             */

            $quarter =
                (int)(
                    ceil(
                        (int)$date->format('n')
                        / 3
                    )
                );


            $year =
                $date->format('Y');


            $quarterKey =
                $year
                . '-Q'
                . $quarter;


            if (
                !isset(
                    $quarterly[$quarterKey]
                )
            ) {

                $quarterly[$quarterKey] = [
                    'period_type' =>
                        'quarterly',

                    'period' =>
                        'Q'
                        . $quarter
                        . ' '
                        . $year,

                    'sort' =>
                        $year
                        . '-'
                        . str_pad(
                            (string)$quarter,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'received' =>
                        0,

                    'not_received' =>
                        0,

                    'rescheduled' =>
                        0,

                    'cancelled' =>
                        0
                ];

            }


            $quarterly[$quarterKey]
                [$statusKey]++;

        }


        $data =
            array_merge(
                array_values($monthly),
                array_values($quarterly)
            );


        usort(
            $data,
            function (
                $a,
                $b
            ) {

                if (
                    $a['period_type']
                    ===
                    $b['period_type']
                ) {

                    return strcmp(
                        $a['sort'],
                        $b['sort']
                    );

                }


                return strcmp(
                    $a['period_type'],
                    $b['period_type']
                );

            }
        );


        foreach (
            $data as &$row
        ) {

            unset(
                $row['sort']
            );

        }

        unset($row);


        return $data;
    }


    /**
     * =========================================================
     * BUILD DISTRIBUTION STATUS SUMMARY
     * =========================================================
     */
    private function buildDistributionStatusSummary(
        array $records
    ): array {

        $summary = [
            'Received' =>
                0,

            'Not Received' =>
                0,

            'Re-Scheduled' =>
                0,

            'Cancelled' =>
                0
        ];


        foreach (
            $records as $record
        ) {

            $status =
                strtolower(
                    trim(
                        (string)(
                            $record->status
                            ?? ''
                        )
                    )
                );


            if (
                in_array(
                    $status,
                    [
                        'received',
                        'receive'
                    ],
                    true
                )
            ) {

                $summary['Received']++;

            } elseif (
                in_array(
                    $status,
                    [
                        'not received',
                        'not_received',
                        'notreceived'
                    ],
                    true
                )
            ) {

                $summary['Not Received']++;

            } elseif (
                in_array(
                    $status,
                    [
                        're-scheduled',
                        'rescheduled',
                        're scheduled',
                        're-schedule'
                    ],
                    true
                )
            ) {

                $summary['Re-Scheduled']++;

            } elseif (
                in_array(
                    $status,
                    [
                        'cancelled',
                        'canceled'
                    ],
                    true
                )
            ) {

                $summary['Cancelled']++;

            }

        }


        return $summary;
    }


    /**
     * =========================================================
     * BUILD RICE TYPE SUMMARY
     * =========================================================
     */
    private function buildRiceTypeSummary(
        array $evaluations
    ): array {

        $summary = [

            0 => [
                'label' =>
                    'Inbred',

                'count' =>
                    0,

                'yield_before_total' =>
                    0,

                'yield_after_total' =>
                    0,

                'yield_count' =>
                    0
            ],

            1 => [
                'label' =>
                    'Hybrid',

                'count' =>
                    0,

                'yield_before_total' =>
                    0,

                'yield_after_total' =>
                    0,

                'yield_count' =>
                    0
            ]

        ];


        foreach (
            $evaluations as $evaluation
        ) {

            if (
                $evaluation->rice_type === null
            ) {

                continue;

            }


            $type =
                (int)$evaluation->rice_type;


            if (
                !isset(
                    $summary[$type]
                )
            ) {

                continue;

            }


            $summary[$type]['count']++;


            if (
                is_numeric(
                    $evaluation->average_yield
                ) &&
                is_numeric(
                    $evaluation->crop_yield_after
                )
            ) {

                $summary[$type]
                    ['yield_before_total']
                    +=
                    (float)$evaluation->average_yield;


                $summary[$type]
                    ['yield_after_total']
                    +=
                    (float)$evaluation->crop_yield_after;


                $summary[$type]
                    ['yield_count']++;

            }

        }


        foreach (
            $summary as &$row
        ) {

            $count =
                $row['yield_count'];


            $row['average_yield_before'] =
                $count > 0
                    ? round(
                        $row['yield_before_total']
                        / $count,
                        2
                    )
                    : 0;


            $row['average_yield_after'] =
                $count > 0
                    ? round(
                        $row['yield_after_total']
                        / $count,
                        2
                    )
                    : 0;


            $row['yield_improvement'] =
                $row['average_yield_before'] > 0
                    ? round(
                        (
                            (
                                $row['average_yield_after']
                                -
                                $row['average_yield_before']
                            )
                            /
                            $row['average_yield_before']
                        )
                        * 100,
                        2
                    )
                    : 0;


            unset(
                $row['yield_before_total'],
                $row['yield_after_total'],
                $row['yield_count']
            );

        }

        unset($row);


        return $summary;
    }


    /**
     * =========================================================
     * GET RECEIVED RECORDS
     * =========================================================
     */
    private function getReceivedRecords(): array
    {
        $records =
            $this->Records->find()
                ->where([
                    'Records.status' =>
                        'Received'
                ])
                ->order([
                    'Records.received_date' =>
                        'DESC',

                    'Records.id' =>
                        'DESC'
                ])
                ->all()
                ->toArray();


        $result = [];


        foreach (
            $records as $record
        ) {

            $farmer =
                null;


            if (
                !empty(
                    $record->farmer_id
                )
            ) {

                $farmer =
                    $this->Farmers->find()
                        ->where([
                            'Farmers.id' =>
                                (int)$record->farmer_id
                        ])
                        ->first();

            }


            $schedule =
                null;


            if (
                !empty(
                    $record->schedule_id
                )
            ) {

                $schedule =
                    $this->Schedules->find()
                        ->where([
                            'Schedules.id' =>
                                (int)$record->schedule_id
                        ])
                        ->first();

            }


            $farmerName =
                '-';


            if ($farmer) {

                $farmerName =
                    trim(
                        implode(
                            ' ',
                            array_filter([
                                trim(
                                    (string)(
                                        $farmer->first_name
                                        ?? ''
                                    )
                                ),

                                trim(
                                    (string)(
                                        $farmer->middle_name
                                        ?? ''
                                    )
                                ),

                                trim(
                                    (string)(
                                        $farmer->last_name
                                        ?? ''
                                    )
                                )
                            ])
                        )
                    );

            }


            $distributionDate =
                '-';


            if (
                $schedule &&
                !empty(
                    $schedule->start_date
                )
            ) {

                $date =
                    $this->toDateTime(
                        $schedule->start_date
                    );


                if ($date) {

                    $distributionDate =
                        $date->format(
                            'M d, Y'
                        );

                }

            }


            $distributionTime =
                '-';


            if (
                $schedule &&
                !empty(
                    $schedule->start_time
                )
            ) {

                $time =
                    $this->toDateTime(
                        $schedule->start_time
                    );


                if ($time) {

                    $distributionTime =
                        $time->format(
                            'h:i A'
                        );

                } else {

                    $distributionTime =
                        (string)(
                            $schedule->start_time
                        );

                }

            }


            $receivedDate =
                '-';


            if (
                !empty(
                    $record->received_date
                )
            ) {

                $date =
                    $this->toDateTime(
                        $record->received_date
                    );


                if ($date) {

                    $receivedDate =
                        $date->format(
                            'M d, Y'
                        );

                }

            }


            $barangay =
                'N/A';


            if (
                $schedule &&
                !empty(
                    $schedule->barangay
                )
            ) {

                $barangay =
                    trim(
                        (string)
                        $schedule->barangay
                    );

            }


            $result[] = [

                'id' =>
                    $record->id
                    ?? null,

                'farmer_no' =>
                    $farmer->farmer_no
                    ?? '-',

                'farmer_name' =>
                    $farmerName,

                'program_code' =>
                    $schedule->program_code
                    ?? '-',

                'program_name' =>
                    $schedule->program_name
                    ?? 'Seed Subsidy',

                'subsidy_item' =>
                    $record->subsidy_item
                    ?? 'Seed Subsidy',

                'quantity' =>
                    $record->quantity
                    ?? '0.00',

                'barangay' =>
                    $barangay,

                'distribution_date' =>
                    $distributionDate,

                'distribution_time' =>
                    $distributionTime,

                'received_date' =>
                    $receivedDate,

                'status' =>
                    $record->status
                    ?? 'Received'

            ];

        }


        return $result;
    }


    /**
     * =========================================================
     * BUILD QUESTION SUMMARY
     * =========================================================
     */
    private function buildQuestionSummary(
        array $feedbacks
    ): array {

        $questions =
            $this->getQuestions();


        $summary = [];


        foreach (
            $questions as $key => $question
        ) {

            $summary[$key] = [

                'question' =>
                    $question,

                'count' => [
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0
                ],

                'total' =>
                    0,

                'responses' =>
                    0,

                'average' =>
                    0,

                'interpretation' =>
                    'No Responses'

            ];

        }


        foreach (
            $feedbacks as $feedback
        ) {

            $answers =
                $this->decodeAnswers(
                    $feedback->answer
                    ?? null
                );


            foreach (
                $answers as $key => $answer
            ) {

                $normalized =
                    $this->normalizeAnswer(
                        $answer
                    );


                if (
                    $normalized === null
                ) {

                    continue;

                }


                if (
                    !isset(
                        $summary[$key]
                    )
                ) {

                    continue;

                }


                $summary[$key]
                    ['count'][$normalized]++;


                $summary[$key]
                    ['total']
                    += $normalized;


                $summary[$key]
                    ['responses']++;

            }

        }


        foreach (
            $summary as &$item
        ) {

            if (
                $item['responses'] > 0
            ) {

                $item['average'] =
                    round(
                        $item['total']
                        /
                        $item['responses'],
                        2
                    );


                $item['interpretation'] =
                    $this->getRatingInterpretation(
                        $item['average']
                    );

            }

        }

        unset($item);


        return $summary;
    }


    /**
     * =========================================================
     * QUESTIONS
     * =========================================================
     */
    private function getQuestions(): array
    {
        return [

            'q1' =>
                'I am satisfied with the service/intervention that I received from the DA.',

            'q2' =>
                'I spent a reasonable amount of time waiting to receive the seed subsidy.',

            'q3' =>
                'I received the seed subsidy that I needed and that was promised by the Department of Agriculture, following the prescribed distribution procedures.',

            'q4' =>
                'The City Agriculture Office was easily accessible and could be approached or contacted regarding the seed subsidy distribution.',

            'q5' =>
                'I was properly informed about the proper use, benefits, and expected results of the seed subsidy I received, and my feedback was listened to.',

            'q6' =>
                'I did not have to pay an unreasonable amount of fees to receive the seed subsidy. (Do not rate if the seed subsidy was provided free of charge.)',

            'q7' =>
                'I believe the distribution of the seed subsidy was fair to all qualified farmer beneficiaries, or "walang palakasan."',

            'q8' =>
                'I was treated courteously by the staff during the seed subsidy distribution, and they were helpful when I needed assistance.',

            'q9' =>
                'I received the seed subsidy that I needed, or, if my request was not granted, the reason for the denial was sufficiently explained to me.',

            'q10' =>
                'I received the seed subsidy within the expected time for its intended purpose.'

        ];
    }


    /**
     * =========================================================
     * DECODE ANSWERS
     * =========================================================
     */
    private function decodeAnswers(
        mixed $answer
    ): array {

        if (
            is_array($answer)
        ) {

            $decoded =
                $answer;

        } else {

            $decoded =
                json_decode(
                    (string)$answer,
                    true
                );

        }


        if (
            !is_array($decoded)
        ) {

            return [];

        }


        $result = [];


        for (
            $i = 1;
            $i <= 10;
            $i++
        ) {

            $key =
                'q' . $i;


            if (
                array_key_exists(
                    $key,
                    $decoded
                )
            ) {

                $result[$key] =
                    $decoded[$key];

                continue;

            }


            $upperKey =
                'Q' . $i;


            if (
                array_key_exists(
                    $upperKey,
                    $decoded
                )
            ) {

                $result[$key] =
                    $decoded[$upperKey];

                continue;

            }


            $numberKey =
                (string)$i;


            if (
                array_key_exists(
                    $numberKey,
                    $decoded
                )
            ) {

                $result[$key] =
                    $decoded[$numberKey];

            }

        }


        return $result;
    }


    /**
     * =========================================================
     * NORMALIZE ANSWER
     * =========================================================
     */
    private function normalizeAnswer(
        mixed $value
    ): ?int {

        if (
            $value === null
        ) {

            return null;

        }


        $value =
            trim(
                (string)$value
            );


        if (
            $value === ''
        ) {

            return null;

        }


        $lower =
            strtolower($value);


        if (
            in_array(
                $lower,
                [
                    'blank',
                    'n/a',
                    'na',
                    'skip',
                    'skipped',
                    'not applicable',
                    'not_applicable'
                ],
                true
            )
        ) {

            return null;

        }


        if (
            !is_numeric($value)
        ) {

            return null;

        }


        $number =
            (int)$value;


        if (
            $number < 1 ||
            $number > 5
        ) {

            return null;

        }


        return $number;
    }


    /**
     * =========================================================
     * RATING INTERPRETATION
     * =========================================================
     */
    private function getRatingInterpretation(
        float $average
    ): string {

        if (
            $average >= 4.21
        ) {

            return 'Strongly Agree';

        }


        if (
            $average >= 3.41
        ) {

            return 'Agree';

        }


        if (
            $average >= 2.61
        ) {

            return 'Neutral';

        }


        if (
            $average >= 1.81
        ) {

            return 'Disagree';

        }


        return 'Strongly Disagree';
    }


    /**
     * =========================================================
     * AVERAGE FIELD
     * =========================================================
     */
    private function calculateAverage(
        array $records,
        string $field
    ): float {

        $values = [];


        foreach (
            $records as $record
        ) {

            $value =
                $record->{$field}
                ?? null;


            if (
                is_numeric($value)
            ) {

                $values[] =
                    (float)$value;

            }

        }


        if (
            empty($values)
        ) {

            return 0;

        }


        return round(
            array_sum($values)
            /
            count($values),
            2
        );
    }


    /**
     * =========================================================
     * DATE CONVERSION
     * =========================================================
     */
    private function toDateTime(
        mixed $value
    ): ?\DateTime {

        if (
            $value instanceof \DateTimeInterface
        ) {

            return new \DateTime(
                $value->format(
                    'Y-m-d H:i:s'
                )
            );

        }


        if (
            $value === null ||
            trim((string)$value) === ''
        ) {

            return null;

        }


        try {

            return new \DateTime(
                (string)$value
            );

        } catch (
            \Throwable $e
        ) {

            return null;

        }
    }
}