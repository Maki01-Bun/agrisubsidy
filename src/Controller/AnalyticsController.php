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


    /*
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index()
    {
        /*
         * =====================================================
         * LOAD EVALUATIONS
         * =====================================================
         */

        $evaluations = $this->Evaluations
            ->find()
            ->order([
                'Evaluations.id' => 'ASC'
            ])
            ->all()
            ->toArray();


        /*
         * =====================================================
         * LOAD FEEDBACKS
         * =====================================================
         */

        $feedbacks = $this->Feedbacks
            ->find()
            ->order([
                'Feedbacks.id' => 'ASC'
            ])
            ->all()
            ->toArray();


        /*
         * =====================================================
         * TOTAL COUNTS
         * =====================================================
         */

        $totalEvaluations =
            count($evaluations);

        $totalFeedbacks =
            count($feedbacks);


        /*
         * =====================================================
         * FEEDBACK MAP
         * =====================================================
                        $effectivenessRecords
        $feedbackMap = [];

        foreach ($feedbacks as $feedback) {

            $feedbackMap[
                (int)$feedback->id
            ] = $feedback;
        }


        /*
         * =====================================================
                        $evaluations
                    );
         * EFFECTIVENESS COUNTERS
         * =====================================================
         */

        $effective = 0;

        $moderatelyEffective = 0;

        $notEffective = 0;

        $notYetPredicted = 0;


        /*
         * =====================================================
         * EFFECTIVENESS RECORDS
         *
         * IMPORTANT:
         * The classification happens ONLY HERE.
         *
         * Both the PIE and TREND use these same records.
         * =====================================================
         */

        $effectivenessRecords = [];


        foreach (
            $evaluations as $evaluation
        ) {

            /*
             * -------------------------------------------------
             * ORIGINAL DATABASE VALUE
             * -------------------------------------------------
             */
            $rawValue =
                $evaluation->effectiveness_label
                ?? null;


            /*
             * -------------------------------------------------
             * NORMALIZE VALUE
             *
             * Accepted:
             *
             * 2
             * "2"
             * "Effective"
             *
             * 1
             * "1"
             * "Moderately Effective"
             *
             * 0
             * "0"
             * "Not Effective"
             *
             * NULL / empty
             * -------------------------------------------------
             */

            $value = null;


            if (
                $rawValue !== null &&
                trim((string)$rawValue) !== ''
            ) {

                $normalizedValue =
                    strtolower(
                        trim(
                            (string)$rawValue
                        )
                    );


                /*
                 * EFFECTIVE
                 */

                if (
                    $normalizedValue === '2' ||
                    $normalizedValue === 'effective'
                ) {

                    $value = 2;
                }


                /*
                 * MODERATELY EFFECTIVE
                 */

                elseif (
                    $normalizedValue === '1' ||
                    $normalizedValue === 'moderately effective' ||
                    $normalizedValue === 'moderate effective' ||
                    $normalizedValue === 'moderately-effective' ||
                    $normalizedValue === 'moderate'
                ) {

                    $value = 1;
                }


                /*
                 * NOT EFFECTIVE
                 */

                elseif (
                    $normalizedValue === '0' ||
                    $normalizedValue === 'not effective' ||
                    $normalizedValue === 'not-effective'
                ) {

                    $value = 0;
                }
            }


            /*
             * =================================================
             * CLASSIFY ONCE
             * =================================================
             */

            if ($value === 2) {

                $category =
                    'effective';

                $label =
                    'Effective';

                $effective++;

            }

            elseif ($value === 1) {

                $category =
                    'moderately_effective';

                $label =
                    'Moderately Effective';

                $moderatelyEffective++;

            }

            elseif ($value === 0) {

                $category =
                    'not_effective';

                $label =
                    'Not Effective';

                $notEffective++;

            }

            else {

                $category =
                    'not_yet_predicted';

                $label =
                    'Not Yet Predicted';

                $notYetPredicted++;
            }


            /*
             * =================================================
             * STORE CLASSIFICATION
             * =================================================
             */

            $effectivenessRecords[] = [

                'evaluation_id' =>
                    (int)$evaluation->id,

                'schedule_id' =>
                    (int)(
                        $evaluation->schedule_id
                        ?? 0
                    ),

                'effectiveness_label' =>
                    $value,

                'category' =>
                    $category,

                'label' =>
                    $label,

                'feedback_id' =>
                    (int)(
                        $evaluation->feedback_id
                        ?? 0
                    ),

                'evaluation_date' =>
                    $evaluation->evaluation_date
                    ?? null,

                'created' =>
                    $evaluation->created
                    ?? null
            ];
        }


        /*
         * =====================================================
         * PIE CHART DATA
         *
         * SAME COUNTERS USED BY THE TREND
         * =====================================================
         */

        $labels = [

            'Effective',

            'Moderately Effective',

            'Not Effective',

            'Not Yet Predicted'

        ];


        $totals = [

            $effective,

            $moderatelyEffective,

            $notEffective,

            $notYetPredicted

        ];


        /*
         * =====================================================
         * EFFECTIVENESS TREND
         *
         * USES THE ALREADY CLASSIFIED RECORDS
         * =====================================================
         */

        $effectivenessTrend =
            $this->buildEffectivenessTrend(
                $effectivenessRecords
            );


        /*
         * =====================================================
         * SURVEY TREND
         * =====================================================
         */

        $surveyTrend =
            $this->buildSurveyTrend(
                $feedbacks
            );


        /*
         * =====================================================
         * ALL DISTRIBUTION RECORDS
         * =====================================================
         */

        $allRecords =
            $this->Records
                ->find()
                ->contain(['Schedules'])
                ->order([
                    'Records.id' => 'ASC'
                ])
                ->all()
                ->toArray();


        /*
         * =====================================================
         * DISTRIBUTION TREND
         * =====================================================
         */

        $distributionTrend =
            $this->buildDistributionTrend(
                $allRecords
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
         * RICE / SEED TYPE SUMMARY
         * =====================================================
         */

        $riceSummary =
            $this->buildRiceTypeSummary(
                $evaluations
            );


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
                $location !== '' &&
                !in_array(
                    $location,
                    $receivedLocations,
                    true
                )
            ) {

                $receivedLocations[] =
                    $location;
            }
        }


        sort($receivedLocations);


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
         * YIELD TREND
         *
         * Keep variable available for the view.
         * =====================================================
         */

        $yieldTrend =
            $this->buildYieldTrend(
                $evaluations
            );


        /*
         * =====================================================
         * SEND EVERYTHING TO VIEW
         * =====================================================
         */

        $this->set([

            /*
             * COUNTS
             */

            'totalEvaluations' =>
                $totalEvaluations,

            'totalFeedbacks' =>
                $totalFeedbacks,


            /*
             * EFFECTIVENESS PIE
             */

            'labels' =>
                $labels,

            'totals' =>
                $totals,


            /*
             * EFFECTIVENESS COUNTS
             */

            'effectiveCount' =>
                $effective,

            'moderatelyEffectiveCount' =>
                $moderatelyEffective,

            'notEffectiveCount' =>
                $notEffective,

            'notYetPredictedCount' =>
                $notYetPredicted,


            /*
             * EFFECTIVENESS TREND
             */

            'effectivenessRecords' =>
                $effectivenessRecords,

            'effectivenessTrend' =>
                $effectivenessTrend,


            /*
             * OTHER TRENDS
             */

            'yieldTrend' =>
                $yieldTrend,

            'surveyTrend' =>
                $surveyTrend,

            'distributionTrend' =>
                $distributionTrend,


            /*
             * OTHER ANALYTICS
             */

            'distributionStatusSummary' =>
                $distributionStatusSummary,

            'riceSummary' =>
                $riceSummary,

            'questionSummary' =>
                $questionSummary,


            /*
             * RECEIVED SUBSIDY
             */

            'receivedRecords' =>
                $receivedRecords,

            'receivedLocations' =>
                $receivedLocations,


            /*
             * RAW DATA
             */

            'evaluations' =>
                $evaluations,

            'feedbacks' =>
                $feedbacks,

            'allRecords' =>
                $allRecords
        ]);
    }


    /*
     * =========================================================
     * EFFECTIVENESS TREND
     * =========================================================
     *
     * IMPORTANT:
     *
     * This method DOES NOT classify effectiveness again.
     *
     * It uses:
     *
     * $record['category']
     *
     * which was already classified in index().
     * =========================================================
     */
    private function buildEffectivenessTrend(
    array $effectivenessRecords
): array {

    $scheduleTrend = [];


    /*
     * =========================================================
     * LOOP THROUGH THE EXACT SAME RECORDS USED BY THE PIE
     * =========================================================
     */

    foreach ($effectivenessRecords as $record) {

        $scheduleId =
            (int)(
                $record['schedule_id']
                ?? 0
            );


        /*
         * No schedule = cannot place on schedule trend.
         */

        if ($scheduleId <= 0) {
            continue;
        }


        /*
         * =====================================================
         * USE THE EXACT CATEGORY FROM THE PIE CLASSIFICATION
         * =====================================================
         */

        $category =
            $record['category']
            ?? 'not_yet_predicted';


        $validCategories = [

            'effective',

            'moderately_effective',

            'not_effective',

            'not_yet_predicted'
        ];


        /*
         * Safety only.
         *
         * We DO NOT recalculate the effectiveness label.
         */

        if (
            !in_array(
                $category,
                $validCategories,
                true
            )
        ) {

            $category =
                'not_yet_predicted';
        }


        /*
         * =====================================================
         * GET SCHEDULE NAME
         * =====================================================
         *
         * Change this if your schedule code is stored under
         * another field.
         */

        $schedule =
            $this->Schedules
                ->find()
                ->select([
                    'id',
                    'program_code',
                    'start_date'
                ])
                ->where([
                    'Schedules.id' =>
                        $scheduleId
                ])
                ->first();


        /*
         * =====================================================
         * SCHEDULE LABEL
         * =====================================================
         */

        if ($schedule) {

            $scheduleCode =
                trim(
                    (string)(
                        $schedule->program_code
                        ?? ''
                    )
                );


            if ($scheduleCode === '') {

                $scheduleCode =
                    'Schedule ' .
                    $scheduleId;
            }

        } else {

            $scheduleCode =
                'Schedule ' .
                $scheduleId;
        }


        /*
         * =====================================================
         * CREATE SCHEDULE ROW
         * =====================================================
         */

        if (
            !isset(
                $scheduleTrend[$scheduleId]
            )
        ) {

            $scheduleTrend[$scheduleId] = [

                'schedule_id' =>
                    $scheduleId,

                'period' =>
                    $scheduleCode,

                'period_type' =>
                    'schedule',

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


        /*
         * =====================================================
         * ADD THE SAME CATEGORY USED BY THE PIE
         * =====================================================
         */

        $scheduleTrend[
            $scheduleId
        ][$category]++;
    }


    /*
     * =========================================================
     * SORT BY SCHEDULE ID
     * =========================================================
     */

    ksort(
        $scheduleTrend,
        SORT_NUMERIC
    );


    /*
     * =========================================================
     * RETURN
     * =========================================================
     */

    return array_values(
        $scheduleTrend
    );
}
    private function buildYieldTrend(
        array $evaluations
    ): array {

        $monthly = [];

        $quarterly = [];


        foreach ($evaluations as $evaluation) {

            $date =
                $this->toDateTime(
                    $evaluation->created
                    ?? null
                );

            $before =
                $evaluation->average_yield
                ?? null;

            $after =
                $evaluation->crop_yield_after
                ?? null;

            if (
                $date === null ||
                !is_numeric($before) ||
                !is_numeric($after)
            ) {
                continue;
            }

            $quarter =
                (int)ceil(
                    (int)$date->format('n') / 3
                );

            $periods = [
                [
                    'rows' => &$monthly,
                    'key' => $date->format('Y-m'),
                    'period' => $date->format('F Y'),
                    'period_type' => 'monthly'
                ],
                [
                    'rows' => &$quarterly,
                    'key' => $date->format('Y') . '-Q' . $quarter,
                    'period' => 'Q' . $quarter . ' ' . $date->format('Y'),
                    'period_type' => 'quarterly'
                ]
            ];

            foreach ($periods as $period) {

                $key = $period['key'];

                if (!isset($period['rows'][$key])) {
                    $period['rows'][$key] = [
                        'period' => $period['period'],
                        'period_type' => $period['period_type'],
                        'yield_before_sum' => 0,
                        'yield_after_sum' => 0,
                        'count' => 0
                    ];
                }

                $period['rows'][$key]['yield_before_sum'] +=
                    (float)$before;

                $period['rows'][$key]['yield_after_sum'] +=
                    (float)$after;

                $period['rows'][$key]['count']++;
            }
        }

        $trend = [];

        foreach (
            ['monthly' => $monthly, 'quarterly' => $quarterly]
            as $periodType => $rows
        ) {

            ksort($rows);

            foreach ($rows as $row) {
                $trend[] = [
                    'period' => $row['period'],
                    'period_type' => $periodType,
                    'yield_before' => round(
                        $row['yield_before_sum'] / $row['count'],
                        2
                    ),
                    'yield_after' => round(
                        $row['yield_after_sum'] / $row['count'],
                        2
                    )
                ];
            }
        }

        return $trend;
    }


    /*
     * =========================================================
     * SURVEY TREND
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
                    ??
                    $feedback->created
                    ??
                    null
                );


            if ($date === null) {

                continue;
            }


            $answers =
                $this->decodeAnswers(
                    $feedback->answer
                    ?? null
                );


            $ratings = [];


            for (
                $i = 1;
                $i <= 10;
                $i++
            ) {

                $key =
                    'q' . $i;


                if (
                    isset(
                        $answers[$key]
                    )
                ) {

                    $rating =
                        $this->normalizeAnswer(
                            $answers[$key]
                        );


                    if (
                        $rating >= 1 &&
                        $rating <= 5
                    ) {

                        $ratings[] =
                            $rating;
                    }
                }
            }


            if (
                empty($ratings)
            ) {

                continue;
            }


            $average =
                array_sum($ratings)
                /
                count($ratings);


            /*
             * MONTH
             */

            $monthKey =
                $date->format('F Y');


            if (
                !isset(
                    $monthly[$monthKey]
                )
            ) {

                $monthly[$monthKey] = [

                    'period' =>
                        $monthKey,

                    'period_type' =>
                        'monthly',

                    'sum' =>
                        0,

                    'count' =>
                        0
                ];
            }


            $monthly[
                $monthKey
            ]['sum'] += $average;


            $monthly[
                $monthKey
            ]['count']++;


            /*
             * QUARTER
             */

            $quarter =
                (int)ceil(
                    (int)$date->format('n') / 3
                );


            $quarterKey =
                'Q' .
                $quarter .
                ' ' .
                $date->format('Y');


            if (
                !isset(
                    $quarterly[$quarterKey]
                )
            ) {

                $quarterly[$quarterKey] = [

                    'period' =>
                        $quarterKey,

                    'period_type' =>
                        'quarterly',

                    'sum' =>
                        0,

                    'count' =>
                        0
                ];
            }


            $quarterly[
                $quarterKey
            ]['sum'] += $average;


            $quarterly[
                $quarterKey
            ]['count']++;
        }


        /*
         * CONVERT MONTHLY
         */

        $monthlyOutput = [];


        foreach (
            $monthly as $row
        ) {

            $monthlyOutput[] = [

                'period' =>
                    $row['period'],

                'period_type' =>
                    'monthly',

                'average_rating' =>
                    round(
                        $row['sum']
                        /
                        max(
                            1,
                            $row['count']
                        ),
                        2
                    )
            ];
        }


        /*
         * CONVERT QUARTERLY
         */

        $quarterlyOutput = [];


        foreach (
            $quarterly as $row
        ) {

            $quarterlyOutput[] = [

                'period' =>
                    $row['period'],

                'period_type' =>
                    'quarterly',

                'average_rating' =>
                    round(
                        $row['sum']
                        /
                        max(
                            1,
                            $row['count']
                        ),
                        2
                    )
            ];
        }


        return array_merge(
            $monthlyOutput,
            $quarterlyOutput
        );
    }


    /*
     * =========================================================
     * DISTRIBUTION TREND
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

            $date =
                $this->toDateTime(
                    $record->distribution_date
                    ??
                    $record->schedule->start_date
                    ??
                    $record->created_at
                    ??
                    $record->created
                    ??
                    $record->received_date
                    ??
                    null
                );


            if ($date === null) {

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


            $monthKey =
                $date->format('F Y');


            if (
                !isset(
                    $monthly[$monthKey]
                )
            ) {

                $monthly[$monthKey] = [

                    'period' =>
                        $monthKey,

                    'period_type' =>
                        'monthly',

                    'received' =>
                        0,

                    'not_received' =>
                        0,

                    'rescheduled' =>
                        0,

                    'cancelled' =>
                        0,

                    'pending' =>
                        0
                ];
            }


            if (
                $status === 'received'
            ) {

                $monthly[
                    $monthKey
                ]['received']++;

            }

            elseif (
                $status === 'not received'
            ) {

                $monthly[
                    $monthKey
                ]['not_received']++;

            }

            elseif (
                $status === 're-scheduled' ||
                $status === 'rescheduled'
            ) {

                $monthly[
                    $monthKey
                ]['rescheduled']++;

            }

            elseif (
                $status === 'cancelled'
            ) {

                $monthly[
                    $monthKey
                ]['cancelled']++;
            }

            elseif (
                $status === 'pending'
            ) {

                $monthly[
                    $monthKey
                ]['pending']++;
            }


            /*
             * QUARTER
             */

            $quarter =
                (int)ceil(
                    (int)$date->format('n') / 3
                );


            $quarterKey =
                'Q' .
                $quarter .
                ' ' .
                $date->format('Y');


            if (
                !isset(
                    $quarterly[$quarterKey]
                )
            ) {

                $quarterly[$quarterKey] = [

                    'period' =>
                        $quarterKey,

                    'period_type' =>
                        'quarterly',

                    'received' =>
                        0,

                    'not_received' =>
                        0,

                    'rescheduled' =>
                        0,

                    'cancelled' =>
                        0,

                    'pending' =>
                        0
                ];
            }


            if (
                $status === 'received'
            ) {

                $quarterly[
                    $quarterKey
                ]['received']++;

            }

            elseif (
                $status === 'not received'
            ) {

                $quarterly[
                    $quarterKey
                ]['not_received']++;

            }

            elseif (
                $status === 're-scheduled' ||
                $status === 'rescheduled'
            ) {

                $quarterly[
                    $quarterKey
                ]['rescheduled']++;

            }

            elseif (
                $status === 'cancelled'
            ) {

                $quarterly[
                    $quarterKey
                ]['cancelled']++;
            }

            elseif (
                $status === 'pending'
            ) {

                $quarterly[
                    $quarterKey
                ]['pending']++;
            }
        }


        return array_merge(
            array_values($monthly),
            array_values($quarterly)
        );
    }


    /*
     * =========================================================
     * DISTRIBUTION STATUS SUMMARY
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
                trim(
                    (string)(
                        $record->status
                        ?? ''
                    )
                );


            if (
                isset(
                    $summary[$status]
                )
            ) {

                $summary[$status]++;

            }

            elseif (
                strtolower($status)
                ===
                'rescheduled'
            ) {

                $summary[
                    'Re-Scheduled'
                ]++;
            }
        }


        return $summary;
    }


    /*
     * =========================================================
     * RICE / SEED TYPE SUMMARY
     * =========================================================
     */
    private function buildRiceTypeSummary(
        array $evaluations
    ): array {

        $summary = [

            'Inbred' =>
                0,

            'Hybrid' =>
                0,

            'Unknown' =>
                0
        ];


        foreach (
            $evaluations as $evaluation
        ) {

            $value =
                strtolower(
                    trim(
                        (string)(
                            $evaluation->rice_type
                            ?? ''
                        )
                    )
                );


            if (
                $value === '0' ||
                $value === 'inbred'
            ) {

                $summary['Inbred']++;

            }

            elseif (
                $value === '1' ||
                $value === 'hybrid'
            ) {

                $summary['Hybrid']++;

            }

            else {

                $summary['Unknown']++;
            }
        }


        return $summary;
    }


    /*
     * =========================================================
     * RECEIVED RECORDS
     * =========================================================
     */
    private function getReceivedRecords(): array
    {
        $records =
            $this->Records
                ->find()
                ->where([
                    'Records.status' =>
                        'Received'
                ])
                ->order([
                    'Records.id' =>
                        'ASC'
                ])
                ->all()
                ->toArray();


        $output = [];


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
                    $this->Farmers
                        ->find()
                        ->where([
                            'Farmers.id' =>
                                $record->farmer_id
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
                    $this->Schedules
                        ->find()
                        ->where([
                            'Schedules.id' =>
                                $record->schedule_id
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

                                $farmer->first_name
                                    ?? '',

                                $farmer->middle_name
                                    ?? '',

                                $farmer->last_name
                                    ?? ''

                            ])
                        )
                    );


                if (
                    $farmerName === ''
                ) {

                    $farmerName =
                        $farmer->name
                        ??
                        '-';
                }
            }


            $output[] = [

                'farmer_no' =>
                    $farmer->farmer_no
                    ??
                    $farmer->rsbsa_no
                    ??
                    '-',

                'farmer_name' =>
                    $farmerName,

                'program_code' =>
                    $schedule->program_code
                    ??
                    $record->program_code
                    ??
                    '-',

                'subsidy_item' =>
                    $record->subsidy_item
                    ??
                    'Seed Subsidy',

                'quantity' =>
                    number_format(
                        (float)(
                            $record->quantity
                            ?? 0
                        ),
                        2,
                        '.',
                        ''
                    ),

                'barangay' =>
                    $schedule->barangay
                    ??
                    $record->barangay
                    ??
                    '-',

                'distribution_date' =>
                    $schedule->start_date
                    ??
                    $record->distribution_date
                    ??
                    '-',

                'distribution_time' =>
                    $schedule->start_date
                    ??
                    '-',

                'received_date' =>
                    $record->received_date
                    ??
                    $record->updated
                    ??
                    '-',

                'status' =>
                    $record->status
                    ??
                    'Received'
            ];
        }


        return $output;
    }


    /*
     * =========================================================
     * QUESTION SUMMARY
     * =========================================================
     */
    private function buildQuestionSummary(
        array $feedbacks
    ): array {

        $questions =
            $this->getQuestions();


        $summary = [];


        foreach (
            $questions as $question
        ) {

            $summary[
                $question
            ] = [

                'total' =>
                    0,

                'count' =>
                    0,

                'average' =>
                    0
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
                $questions as $question
            ) {

                if (
                    !array_key_exists(
                        $question,
                        $answers
                    )
                ) {

                    continue;
                }


                $rating =
                    $this->normalizeAnswer(
                        $answers[$question]
                    );


                if (
                    $rating < 1 ||
                    $rating > 5
                ) {

                    continue;
                }


                $summary[
                    $question
                ]['total'] +=
                    $rating;


                $summary[
                    $question
                ]['count']++;
            }
        }


        foreach (
            $summary as $question => &$data
        ) {

            $data['average'] =
                $data['count'] > 0

                    ? round(
                        $data['total']
                        /
                        $data['count'],
                        2
                    )

                    : 0;
        }


        unset($data);


        return $summary;
    }


    /*
     * =========================================================
     * QUESTIONS
     * =========================================================
     */
    private function getQuestions(): array
    {
        return [

            'q1',

            'q2',

            'q3',

            'q4',

            'q5',

            'q6',

            'q7',

            'q8',

            'q9',

            'q10'
        ];
    }


    /*
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

            return $answer;
        }


        if (
            $answer === null ||
            trim((string)$answer) === ''
        ) {

            return [];
        }


        $decoded =
            json_decode(
                (string)$answer,
                true
            );


        return
            is_array($decoded)
                ? $decoded
                : [];
    }


    /*
     * =========================================================
     * NORMALIZE ANSWER
     * =========================================================
     */
    private function normalizeAnswer(
        mixed $value
    ): float {

        if (
            is_numeric($value)
        ) {

            return (float)$value;
        }


        $normalized =
            strtolower(
                trim(
                    (string)$value
                )
            );


        $map = [

            '1' =>
                1,

            '2' =>
                2,

            '3' =>
                3,

            '4' =>
                4,

            '5' =>
                5
        ];


        return
            isset(
                $map[$normalized]
            )

                ? (float)$map[
                    $normalized
                ]

                : 0;
    }


    /*
     * =========================================================
     * RATING INTERPRETATION
     * =========================================================
     */
    private function getRatingInterpretation(
        float $average
    ): string {

        if ($average >= 4.21) {

            return 'Highly Effective';
        }

        if ($average >= 3.41) {

            return 'Effective';
        }

        if ($average >= 2.61) {

            return 'Moderately Effective';
        }

        if ($average >= 1.81) {

            return 'Less Effective';
        }

        return 'Not Effective';
    }


    /*
     * =========================================================
     * CALCULATE AVERAGE
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
                $record instanceof \ArrayAccess

                    ? $record[$field]
                        ?? null

                    : (
                        is_object($record)
                            ? (
                                $record->$field
                                ?? null
                            )

                            : (
                                $record[$field]
                                ?? null
                            )
                    );


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


        return
            array_sum($values)
            /
            count($values);
    }


  /**
 * Convert a value to DateTime safely.
 */
private function toDateTime(mixed $value): ?\DateTime
{
    if ($value instanceof \DateTime) {
        return $value;
    }

    if ($value instanceof \DateTimeImmutable) {
        return new \DateTime(
            $value->format('Y-m-d H:i:s')
        );
    }

    if ($value === null || $value === '') {
        return null;
    }
    try {

        return new \DateTime(
            (string)$value
        );

    } catch (\Exception $e) {

        return null;
    }
}
}