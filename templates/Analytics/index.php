<div class="analytics-page">
    <div class="analytics-title">
        <i class="fas fa-chart-line"></i>
        AgriSubsidy Data Analytics
    </div>
    <div class="row g-3">
        <!-- MODEL STATUS -->
        <div class="col-lg-6 col-md-6">
            <div class="model-card">
                <div class="model-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <div class="model-label">
                        Model Status
                    </div>
                    <div class="model-value text-success">
                        Active
                    </div>
                    <small>
                        Random Forest Classifier
                    </small>
                </div>
            </div>
        </div>
        <!-- DATA USED -->
        <div class="col-lg-6 col-md-6">
            <div class="model-card">
                <div class="model-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <div class="model-label">
                        Data Used
                    </div>
                    <div class="model-value">
                        <?= number_format(
                            $totalEvaluations
                        ) ?>
                    </div>
                    <small>
                        Evaluation Records
                    </small>
                </div>
            </div>
        </div>
    </div>
    <!-- =========================================================
         TREND ANALYSIS
    ========================================================= -->
    <div class="card analytics-trend-card mt-3">
        <div class="analytics-trend-header">
            <div class="analytics-trend-header-inner">
                <div class="analytics-trend-title">
                    <h3>
                        <i class="fas fa-chart-line"></i>
                        Trend Analysis
                    </h3>
                    <p>
                        Monitor yield, effectiveness,
                        survey ratings, and subsidy
                        distribution over time.
                    </p>
                </div>
                <!-- PERIOD -->
                <div class="analytics-trend-filter">
                    <label for="trendPeriod">
                        View By
                    </label>
                    <select
                        id="trendPeriod"
                        class="form-control">
                        <option value="monthly">
                            Monthly
                        </option>
                        <option value="quarterly">
                            Quarterly
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="analytics-trend-body">
            <!-- =================================================
                 EFFECTIVENESS TREND
            ================================================== -->
            <div class="trend-panel">
                <div class="trend-panel-header">
                    <div>
                        <h4>
                            <i class="fas fa-chart-bar"></i>
                            Effectiveness Trend
                        </h4>
                        <small>
                            Evaluation results over time.
                        </small>
                    </div>
                </div>
                <div class="trend-chart-container">
                    <canvas
                        id="effectivenessTrendChart"
                    ></canvas>
                </div>
            </div>
            <!-- =================================================
                 SURVEY TREND
            ================================================== -->
            <div class="trend-panel">
                <div class="trend-panel-header">
                    <div>
                        <h4>
                            <i class="fas fa-star"></i>
                            Survey Rating Trend
                        </h4>
                        <small>
                            Average Q1–Q10 survey rating
                            over time.
                        </small>
                    </div>
                </div>
                <div class="trend-chart-container">
                    <canvas
                        id="surveyTrendChart"
                    ></canvas>
                </div>
            </div>
            <!-- =================================================
                 DISTRIBUTION TREND
            ================================================== -->
            <div class="trend-panel">
                <div class="trend-panel-header">
                    <div>
                        <h4>
                            <i class="fas fa-truck-loading"></i>
                            Subsidy Distribution Trend
                        </h4>
                        <small>
                            Distribution records by status
                            over time.
                        </small>
                    </div>
                </div>
                <div class="trend-chart-container">
                    <canvas
                        id="distributionTrendChart"
                    ></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- ML Model Evaluation Results Section -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark"><i class="fas fa-brain me-2"></i> ML Subsidy Effectiveness Model Summary</h5>
        <span class="badge bg-light text-primary fs-6">
            Overall Status: <?= h($modelEvaluationSummary['evaluation_summary']['overall_effectiveness']) ?>
        </span>
    </div>
    <div class="card-body">
        
        <!-- Key Metrics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <div class="text-muted small">Total Respondents</div>
                    <div class="fs-4 fw-bold"><?= h($modelEvaluationSummary['evaluation_summary']['total_respondents']) ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <div class="text-muted small">Overall Confidence</div>
                    <div class="fs-4 fw-bold text-success"><?= h($modelEvaluationSummary['evaluation_summary']['overall_confidence']) ?>%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <div class="text-muted small">Avg Yield / Hectare</div>
                    <div class="fs-4 fw-bold"><?= h($modelEvaluationSummary['evaluation_summary']['average_yield_per_hectare']) ?> tons/ha</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <div class="text-muted small">Avg Questionnaire Score</div>
                    <div class="fs-4 fw-bold"><?= h($modelEvaluationSummary['evaluation_summary']['average_questionnaire_score']) ?> / 5</div>
                </div>
            </div>
        </div>

        <!-- Summary Paragraph -->
        <div class="alert alert-info border-info mb-4">
            <i class="fas fa-info-circle me-1"></i> <?= h($modelEvaluationSummary['summary']) ?>
        </div>

        <!-- Prediction Distribution Breakdown -->
        <h6 class="fw-bold mb-3">Prediction Distribution Breakdown</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Effectiveness Level</th>
                        <th>Record Count</th>
                        <th>Prediction Share (%)</th>
                        <th>Model Score (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modelEvaluationSummary['prediction_distribution'] as $level => $data): ?>
                    <tr>
                        <td>
                            <span class="badge <?= $level === 'High' ? 'bg-success' : ($level === 'Moderate' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= h($level) ?>
                            </span>
                        </td>
                        <td><?= h($data['count']) ?></td>
                        <td><?= h($data['percentage']) ?>%</td>
                        <td><?= h($modelEvaluationSummary['model_distribution'][$level] ?? 0) ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>  
    <!-- =========================================================
         SUBSIDY RECEIVED RECORDS
    ========================================================= -->
    <div class="card subsidy-received-card mt-3">
        <div class="subsidy-received-header">
            <div class="subsidy-received-header-inner">
                <div class="subsidy-received-title">
                    <h3>
                        Beneficiaries Received the Subsidy
                    </h3>
                </div>
                <div class="subsidy-received-tools">
                     <div class="subsidy-location-filter">
                        <?= $this->Form->control(
                            'receivedLocationFilter',
                            [
                                'type' => 'select',

                                'options' =>
                                    $this->Option->location(),

                                'empty' =>
                                    'All Locations',

                                'label' =>
                                    false,

                                'class' =>
                                    'form-control',

                                'id' =>
                                    'receivedLocationFilter'
                            ]
                        ) ?>
                    </div>
                    <button
                        type="button"
                        id="downloadReceivedSubsidy"
                        class="btn subsidy-download-btn"
                    >
                        Download
                    </button>
                </div>
            </div>
        </div>
        <div class="subsidy-received-body">

            <?php
            $receivedRecords =
                $receivedRecords ?? [];
            ?>


            <?php if (!empty($receivedRecords)): ?>

                <div
                    class="received-record-count"
                    id="receivedRecordCount"
                >

                    Showing

                    <strong>
                        <?= count($receivedRecords) ?>
                    </strong>

                    received subsidy record(s)

                </div>


                <div
                    class="table-responsive received-table-wrapper"
                >

                    <table
                        id="receivedSubsidyTable"
                        class="table subsidy-received-table mb-0"
                    >

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>
                                    LGU RSBSA Number
                                </th>

                                <th>
                                    Farmer Name
                                </th>

                                <th>
                                    Distribution Code
                                </th>

                                <th>
                                    Subsidy Item
                                </th>

                                <th>
                                    Quantity (tons)
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Distribution Date
                                </th>

                                <th>
                                    Distribution Time
                                </th>

                                <th>
                                    Received Date
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach (
                                $receivedRecords
                                as $index => $record
                            ): ?>

                                <?php

                                $location =
                                    trim(
                                        (string)(
                                            $record['barangay']
                                            ?? 'N/A'
                                        )
                                    );

                                if ($location === '') {

                                    $location = 'N/A';

                                }

                                ?>


                                <tr
                                    data-location="<?= h($location) ?>"
                                >

                                    <td class="row-number">

                                        <?= $index + 1 ?>

                                    </td>


                                    <td>

                                        <strong
                                            class="farmer-number"
                                        >

                                            <?= h(
                                                $record['farmer_no']
                                                ?? '-'
                                            ) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <strong
                                            class="farmer-name"
                                        >

                                            <?= h(
                                                $record['farmer_name']
                                                ?? '-'
                                            ) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <span
                                            class="program-badge"
                                        >

                                            <?= h(
                                                $record['program_code']
                                                ?? '-'
                                            ) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?= h(
                                            $record['subsidy_item']
                                            ?? 'Seed Subsidy'
                                        ) ?>

                                    </td>


                                    <td>

                                        <span
                                            class="quantity-value"
                                        >

                                            <?= h(
                                                $record['quantity']
                                                ?? '0.00'
                                            ) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <span
                                            class="location-value"
                                        >

                                            <?= h($location) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?= h(
                                            $record[
                                                'distribution_date'
                                            ] ?? '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= h(
                                            $record[
                                                'distribution_time'
                                            ] ?? '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= h(
                                            $record[
                                                'received_date'
                                            ] ?? '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <span
                                            class="received-status"
                                        >

                                            <?= h(
                                                $record['status']
                                                ?? 'Received'
                                            ) ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <div
                    id="receivedNoFilterResult"
                    class="received-filter-empty"
                    style="display:none;"
                >

                    <h5>
                        No Records Found
                    </h5>

                    <p>
                        No received subsidy records were found
                        for the selected location.
                    </p>

                </div>


            <?php else: ?>

                <div class="subsidy-received-empty">

                    <h5>
                        No Subsidy Records Yet
                    </h5>

                    <p>
                        Farmers who receive the subsidy
                        will appear here.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<!-- ================================================================
     CHART.JS
================================================================ -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>


<!-- ================================================================
     CHART DATA
================================================================ -->

<script>

const effectivenessLabels =
    <?= json_encode(
        $labels ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;


const effectivenessTotals =
    <?= json_encode(
        $totals ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;


/*
 * ================================================================
 * EFFECTIVENESS TREND
 * ================================================================
 *
 * This MUST come from $effectivenessTrend.
 *
 * Example:
 *
 * [
 *     {
 *         period_type: "monthly",
 *         period: "January 2026",
 *         effective: 10,
 *         moderately_effective: 5,
 *         not_effective: 2,
 *         not_yet_predicted: 1
 *     }
 * ]
 */

const effectivenessTrendData =
    <?= json_encode(
        $effectivenessTrend ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;


const yieldTrendData =
    <?= json_encode(
        $yieldTrend ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;


const surveyTrendData =
    <?= json_encode(
        $surveyTrend ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;


const distributionTrendData =
    <?= json_encode(
        $distributionTrend ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;

</script>


<!-- ================================================================
     ALL CHARTS
================================================================ -->

<script>

$(document).ready(function () {

    'use strict';


    /* ============================================================
       CHART INSTANCES
    ============================================================ */

    let yieldChart = null;

    let effectivenessTrendChart = null;

    let surveyChart = null;

    let distributionChart = null;



    /* ============================================================
       PERIOD SELECT
    ============================================================ */

    const trendPeriod =
        document.getElementById(
            'trendPeriod'
        );



    /* ============================================================
       FILTER PERIOD
    ============================================================ */

    function getPeriodData(
        data,
        period
    ) {

        return (
            Array.isArray(data)
                ? data
                : []
        ).filter(
            function (row) {

                return String(
                    row.period_type ?? ''
                )
                .toLowerCase()
                ===
                String(
                    period
                )
                .toLowerCase();

            }
        );

    }



    /* ============================================================
       DESTROY CHART
    ============================================================ */

    function destroyChart(
        chart
    ) {

        if (chart) {

            chart.destroy();

        }

    }



    /* ============================================================
       EFFECTIVENESS COLORS
    ============================================================ */

    function getEffectivenessColor(
        label
    ) {

        const normalized =
            String(
                label ?? ''
            )
            .trim()
            .toLowerCase();


        if (
            normalized === 'effective' ||
            normalized === '2'
        ) {

            return '#28a745';
        }


        if (
            normalized ===
                'moderately effective' ||

            normalized ===
                'moderate effective' ||

            normalized ===
                'moderately-effective' ||

            normalized ===
                'moderate' ||

            normalized === '1'
        ) {

            return '#ffc107';
        }


        if (
            normalized ===
                'not effective' ||

            normalized ===
                'not-effective' ||

            normalized === '0'
        ) {

            return '#dc3545';
        }


        return '#6c757d';
    }



    /* ============================================================
       1. EFFECTIVENESS EVALUATION PIE
    ============================================================ */

    const effectivenessCanvas =
        document.getElementById(
            'effectivenessChart'
        );


    if (
        effectivenessCanvas &&
        typeof Chart !== 'undefined'
    ) {

        const chartValues =
            effectivenessTotals.map(
                function (value) {

                    const number =
                        Number(value);

                    return Number.isFinite(
                        number
                    )
                        ? number
                        : 0;
                }
            );


        const chartTotal =
            chartValues.reduce(
                function (
                    total,
                    value
                ) {

                    return total + value;

                },
                0
            );


        const chartColors =
            effectivenessLabels.map(
                function (label) {

                    return getEffectivenessColor(
                        label
                    );

                }
            );


        const oldChart =
            Chart.getChart(
                effectivenessCanvas
            );


        if (oldChart) {

            oldChart.destroy();
        }


        new Chart(
            effectivenessCanvas,
            {

                type: 'pie',

                data: {

                    labels:
                        effectivenessLabels,

                    datasets: [

                        {

                            data:
                                chartValues,

                            backgroundColor:
                                chartColors,

                            borderColor:
                                '#ffffff',

                            borderWidth:
                                3,

                            hoverOffset:
                                8
                        }

                    ]
                },


                options: {

                    responsive:
                        true,

                    maintainAspectRatio:
                        false,


                    plugins: {

                        legend: {

                            display:
                                true,

                            position:
                                'bottom',

                            labels: {

                                padding:
                                    18,

                                usePointStyle:
                                    true,

                                pointStyle:
                                    'circle',

                                font: {

                                    size:
                                        13,

                                    weight:
                                        '600'
                                },


                                generateLabels:
                                    function (
                                        chart
                                    ) {

                                        const dataset =
                                            chart.data
                                                .datasets[0];


                                        return chart
                                            .data
                                            .labels
                                            .map(
                                                function (
                                                    label,
                                                    index
                                                ) {

                                                    return {

                                                        text:
                                                            label,

                                                        fillStyle:
                                                            dataset
                                                                .backgroundColor[
                                                                    index
                                                                ],

                                                        strokeStyle:
                                                            '#ffffff',

                                                        lineWidth:
                                                            2,

                                                        hidden:
                                                            false,

                                                        index:
                                                            index
                                                    };

                                                }
                                            );
                                    }
                            }
                        },


                        tooltip: {

                            enabled:
                                true,

                            callbacks: {

                                label:
                                    function (
                                        context
                                    ) {

                                        const value =
                                            Number(
                                                context.raw ??
                                                0
                                            );


                                        const percentage =
                                            chartTotal >
                                            0

                                                ? (
                                                    value /
                                                    chartTotal *
                                                    100
                                                ).toFixed(1)

                                                : '0.0';


                                        return (
                                            context.label +
                                            ': ' +
                                            value +
                                            ' (' +
                                            percentage +
                                            '%)'
                                        );
                                    }
                            }
                        },


                        datalabels:

                            typeof
                            ChartDataLabels !==
                            'undefined'

                                ? {

                                    display:
                                        function (
                                            context
                                        ) {

                                            return Number(
                                                context
                                                    .dataset
                                                    .data[
                                                        context
                                                            .dataIndex
                                                    ] ??
                                                0
                                            ) > 0;
                                        },


                                    color:
                                        function (
                                            context
                                        ) {

                                            const label =
                                                context
                                                    .chart
                                                    .data
                                                    .labels[
                                                        context
                                                            .dataIndex
                                                    ];


                                            const normalized =
                                                String(
                                                    label ??
                                                    ''
                                                )
                                                .trim()
                                                .toLowerCase();


                                            if (
                                                normalized ===
                                                    'moderately effective' ||

                                                normalized ===
                                                    'moderate effective' ||

                                                normalized ===
                                                    'moderately-effective' ||

                                                normalized ===
                                                    'moderate' ||

                                                normalized ===
                                                    '1'
                                            ) {

                                                return '#212529';
                                            }


                                            return '#ffffff';
                                        },


                                    font: {

                                        weight:
                                            'bold',

                                        size:
                                            14
                                    },


                                    formatter:
                                        function (
                                            value
                                        ) {

                                            if (
                                                !chartTotal ||
                                                !value
                                            ) {

                                                return '';
                                            }


                                            return (
                                                (
                                                    Number(value) /
                                                    chartTotal *
                                                    100
                                                ).toFixed(1) +
                                                '%'
                                            );
                                        }

                                }

                                : {}
                    }
                },


                plugins:

                    typeof
                    ChartDataLabels !==
                    'undefined'

                        ? [
                            ChartDataLabels
                        ]

                        : []
            }
        );
    }


function renderEffectivenessChart() {

    const canvas =
        document.getElementById(
            'effectivenessTrendChart'
        );

    if (!canvas) {
        return;
    }


    /*
     * =========================================================
     * DESTROY OLD CHART
     * =========================================================
     */

    if (effectivenessTrendChart) {

        effectivenessTrendChart.destroy();

        effectivenessTrendChart = null;
    }


    /*
     * =========================================================
     * USE SCHEDULE DATA ONLY
     * =========================================================
     */

    const data =
        Array.isArray(effectivenessTrendData)
            ? effectivenessTrendData.filter(
                function (row) {

                    return String(
                        row.period_type ?? ''
                    ).toLowerCase() ===
                    'schedule';

                }
            )
            : [];


    /*
     * =========================================================
     * DEBUG
     * =========================================================
     */

    console.log(
        'EFFECTIVENESS SCHEDULE DATA:',
        data
    );


    /*
     * =========================================================
     * LABELS
     * =========================================================
     */

    const labels =
        data.map(
            function (row) {

                return String(
                    row.schedule_label ??
                    row.period ??
                    ''
                );

            }
        );


    /*
     * =========================================================
     * EFFECTIVE
     * =========================================================
     */

    const effective =
        data.map(
            function (row) {

                return Number(
                    row.effective ?? 0
                );

            }
        );


    /*
     * =========================================================
     * MODERATELY EFFECTIVE
     * =========================================================
     */

    const moderatelyEffective =
        data.map(
            function (row) {

                return Number(
                    row.moderately_effective ??
                    0
                );

            }
        );


    /*
     * =========================================================
     * NOT EFFECTIVE
     * =========================================================
     */

    const notEffective =
        data.map(
            function (row) {

                return Number(
                    row.not_effective ??
                    0
                );

            }
        );


    /*
     * =========================================================
     * NOT YET PREDICTED
     * =========================================================
     */

    const notYetPredicted =
        data.map(
            function (row) {

                return Number(
                    row.not_yet_predicted ??
                    0
                );

            }
        );


    /*
     * =========================================================
     * CREATE CHART
     * =========================================================
     */

    effectivenessTrendChart =
        new Chart(
            canvas,
            {

                type: 'line',


                data: {

                    labels: labels,


                    datasets: [

                        {
                            label:
                                'Effective',

                            data:
                                effective,

                            borderColor:
                                '#28a745',

                            backgroundColor:
                                '#28a745',

                            borderWidth:
                                3,

                            tension:
                                0.25,

                            fill:
                                false,

                            pointRadius:
                                6,

                            pointHoverRadius:
                                8
                        },


                        {
                            label:
                                'Moderately Effective',

                            data:
                                moderatelyEffective,

                            borderColor:
                                '#ffc107',

                            backgroundColor:
                                '#ffc107',

                            borderWidth:
                                3,

                            tension:
                                0.25,

                            fill:
                                false,

                            pointRadius:
                                6,

                            pointHoverRadius:
                                8
                        },


                        {
                            label:
                                'Not Effective',

                            data:
                                notEffective,

                            borderColor:
                                '#dc3545',

                            backgroundColor:
                                '#dc3545',

                            borderWidth:
                                3,

                            tension:
                                0.25,

                            fill:
                                false,

                            pointRadius:
                                6,

                            pointHoverRadius:
                                8
                        },


                        {
                            label:
                                'Not Yet Predicted',

                            data:
                                notYetPredicted,

                            borderColor:
                                '#6c757d',

                            backgroundColor:
                                '#6c757d',

                            borderWidth:
                                3,

                            tension:
                                0.25,

                            fill:
                                false,

                            pointRadius:
                                6,

                            pointHoverRadius:
                                8
                        }

                    ]
                },


                options: {

                    responsive:
                        true,

                    maintainAspectRatio:
                        false,


                    interaction: {

                        mode:
                            'index',

                        intersect:
                            false
                    },


                    plugins: {

                        legend: {

                            display:
                                true,

                            position:
                                'bottom',

                            labels: {

                                usePointStyle:
                                    false,

                                padding:
                                    20,

                                font: {

                                    size:
                                        14
                                }
                            }
                        },


                        tooltip: {

                            enabled:
                                true,

                            callbacks: {

                                title:
                                    function (
                                        context
                                    ) {

                                        return (
                                            context[0]?.label ??
                                            ''
                                        );

                                    },


                                label:
                                    function (
                                        context
                                    ) {

                                        return (
                                            context.dataset.label +
                                            ': ' +
                                            Number(
                                                context.raw ?? 0
                                            )
                                        );

                                    }

                            }
                        }

                    },


                    scales: {

                        y: {

                            beginAtZero:
                                true,

                            ticks: {

                                precision:
                                    0,

                                stepSize:
                                    1
                            },

                            title: {

                                display:
                                    true,

                                text:
                                    'Evaluation Records'
                            }

                        },


                        x: {

                            title: {

                                display:
                                    true,

                                text:
                                    'Schedule'
                            },

                            ticks: {

                                autoSkip:
                                    false,

                                maxRotation:
                                    45,

                                minRotation:
                                    0
                            }

                        }

                    }

                }

            }
        );
}

    /* ============================================================
       3. YIELD TREND
    ============================================================ */

    function renderYieldChart(
        period
    ) {

        const canvas =
            document.getElementById(
                'yieldTrendChart'
            );


        if (!canvas) {

            return;
        }


        destroyChart(
            yieldChart
        );


        const data =
            getPeriodData(
                yieldTrendData,
                period
            );


        const labels =
            data.map(
                function (row) {

                    return (
                        row.period ??
                        ''
                    );
                }
            );


        const before =
            data.map(
                function (row) {

                    return Number(
                        row.yield_before ??
                        0
                    );
                }
            );


        const after =
            data.map(
                function (row) {

                    return Number(
                        row.yield_after ??
                        0
                    );
                }
            );


        yieldChart =
            new Chart(
                canvas,
                {

                    type:
                        labels.length > 1
                            ? 'line'
                            : 'bar',


                    data: {

                        labels:
                            labels,

                        datasets: [

                            {

                                label:
                                    'Yield Before',

                                data:
                                    before,

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2,

                                pointRadius:
                                    4
                            },


                            {

                                label:
                                    'Yield After',

                                data:
                                    after,

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2,

                                pointRadius:
                                    4
                            }
                        ]
                    },


                    options: {

                        responsive:
                            true,

                        maintainAspectRatio:
                            false,


                        interaction: {

                            mode:
                                'index',

                            intersect:
                                false
                        },


                        scales: {

                            y: {

                                beginAtZero:
                                    true,

                                title: {

                                    display:
                                        true,

                                    text:
                                        'Yield (tons/ha)'
                                }
                            },


                            x: {

                                title: {

                                    display:
                                        true,

                                    text:
                                        period ===
                                        'monthly'

                                            ? 'Month'

                                            : 'Quarter'
                                }
                            }
                        }
                    }
                }
            );
    }



    /* ============================================================
       4. SURVEY TREND
    ============================================================ */

    function renderSurveyChart(
        period
    ) {

        const canvas =
            document.getElementById(
                'surveyTrendChart'
            );


        if (!canvas) {

            return;
        }


        destroyChart(
            surveyChart
        );


        const data =
            getPeriodData(
                surveyTrendData,
                period
            );


        const labels =
            data.map(
                function (row) {

                    return (
                        row.period ??
                        ''
                    );
                }
            );


        const ratings =
            data.map(
                function (row) {

                    return Number(
                        row.average_rating ??
                        0
                    );
                }
            );


        surveyChart =
            new Chart(
                canvas,
                {

                    type:
                        labels.length > 1
                            ? 'line'
                            : 'bar',


                    data: {

                        labels:
                            labels,

                        datasets: [

                            {

                                label:
                                    'Average Survey Rating',

                                data:
                                    ratings,

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2,

                                pointRadius:
                                    4
                            }
                        ]
                    },


                    options: {

                        responsive:
                            true,

                        maintainAspectRatio:
                            false,


                        scales: {

                            y: {

                                min:
                                    1,

                                max:
                                    5,

                                ticks: {

                                    stepSize:
                                        1
                                },

                                title: {

                                    display:
                                        true,

                                    text:
                                        'Average Rating'
                                }
                            },


                            x: {

                                title: {

                                    display:
                                        true,

                                    text:
                                        period ===
                                        'monthly'

                                            ? 'Month'

                                            : 'Quarter'
                                }
                            }
                        }
                    }
                }
            );
    }



    /* ============================================================
       5. DISTRIBUTION TREND
    ============================================================ */

    function renderDistributionChart(
        period
    ) {

        const canvas =
            document.getElementById(
                'distributionTrendChart'
            );


        if (!canvas) {

            return;
        }


        destroyChart(
            distributionChart
        );


        const data =
            getPeriodData(
                distributionTrendData,
                period
            );


        const labels =
            data.map(
                function (row) {

                    return (
                        row.period ??
                        ''
                    );
                }
            );


        distributionChart =
            new Chart(
                canvas,
                {

                    type:
                        labels.length > 1
                            ? 'line'
                            : 'bar',


                    data: {

                        labels:
                            labels,


                        datasets: [

                            {

                                label:
                                    'Received',

                                data:
                                    data.map(
                                        function (
                                            row
                                        ) {

                                            return Number(
                                                row.received ??
                                                0
                                            );
                                        }
                                    ),

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2
                            },


                            {

                                label:
                                    'Not Received',

                                data:
                                    data.map(
                                        function (
                                            row
                                        ) {

                                            return Number(
                                                row.not_received ??
                                                0
                                            );
                                        }
                                    ),

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2
                            },


                            {

                                label:
                                    'Re-Scheduled',

                                data:
                                    data.map(
                                        function (
                                            row
                                        ) {

                                            return Number(
                                                row.rescheduled ??
                                                0
                                            );
                                        }
                                    ),

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2
                            },


                            {

                                label:
                                    'Pending',

                                data:
                                    data.map(
                                        function (
                                            row
                                        ) {

                                            return Number(
                                                row.pending ??
                                                0
                                            );
                                        }
                                    ),

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2
                            },


                            {

                                label:
                                    'Cancelled',

                                data:
                                    data.map(
                                        function (
                                            row
                                        ) {

                                            return Number(
                                                row.cancelled ??
                                                0
                                            );
                                        }
                                    ),

                                tension:
                                    0.3,

                                fill:
                                    false,

                                borderWidth:
                                    2
                            }
                        ]
                    },


                    options: {

                        responsive:
                            true,

                        maintainAspectRatio:
                            false,


                        interaction: {

                            mode:
                                'index',

                            intersect:
                                false
                        },


                        scales: {

                            y: {

                                beginAtZero:
                                    true,

                                ticks: {

                                    precision:
                                        0
                                },

                                title: {

                                    display:
                                        true,

                                    text:
                                        'Distribution Records'
                                }
                            },


                            x: {

                                title: {

                                    display:
                                        true,

                                    text:
                                        period ===
                                        'monthly'

                                            ? 'Month'

                                            : 'Quarter'
                                }
                            }
                        }
                    }
                }
            );
    }


function renderAllCharts() {

    const period =
        trendPeriod
            ? trendPeriod.value
            : 'monthly';


    renderYieldChart(
        period
    );


    /*
     * EFFECTIVENESS:
     * ALWAYS EVERY SCHEDULE
     */
    renderEffectivenessChart();


    renderSurveyChart(
        period
    );


    renderDistributionChart(
        period
    );
}



    /* ============================================================
       MONTHLY / QUARTERLY
    ============================================================ */

    if (trendPeriod) {

        trendPeriod.addEventListener(
            'change',
            function () {

                renderAllCharts();

            }
        );
    }



    /* ============================================================
       INITIAL RENDER
    ============================================================ */

    renderAllCharts();


    
    /* ============================================================
       SUBSIDY RECEIVED LOCATION FILTER
    ============================================================ */

    const $locationFilter =
        $('#receivedLocationFilter');

    const $receivedTable =
        $('#receivedSubsidyTable');


    /* ============================================================
       INITIALIZE SELECT2
    ============================================================ */

    if ($locationFilter.length) {

        if (
            $locationFilter.hasClass(
                'select2-hidden-accessible'
            )
        ) {

            $locationFilter.select2(
                'destroy'
            );

        }


        $locationFilter.select2({

            placeholder:
                'All Locations',

            allowClear:
                false,

            width:
                '100%',

            minimumResultsForSearch:
                0,

            language: {

                noResults:
                    function () {

                        return 'No location found';

                    },

                searching:
                    function () {

                        return 'Searching...';

                    }

            }

        });

    }


    /* ============================================================
       LOCATION CHANGE
    ============================================================ */

    $locationFilter.on(
        'change',
        function () {

            const selectedLocation =
                String(
                    $(this).val() || ''
                ).trim();


            console.log(
                'Selected Location:',
                selectedLocation
            );


            /* ====================================================
               CHECK TABLE
            ==================================================== */

            if (!$receivedTable.length) {

                console.error(
                    '#receivedSubsidyTable was not found.'
                );

                return;

            }


            /* ====================================================
               CHECK DATATABLE
            ==================================================== */

            const isDataTable =
                $.fn.DataTable.isDataTable(
                    '#receivedSubsidyTable'
                );


            /* ====================================================
               DATATABLE FILTER
            ==================================================== */

            if (isDataTable) {

                const table =
                    $('#receivedSubsidyTable')
                        .DataTable();


                /*
                 * Actual table column order:
                 *
                 * 0  #
                 * 1  LGU RSBSA Number
                 * 2  Farmer Name
                 * 3  Distribution Code
                 * 4  Subsidy Item
                 * 5  Quantity
                 * 6  Location
                 * 7  Distribution Date
                 * 8  Distribution Time
                 * 9  Received Date
                 * 10 Status
                 */

                const locationColumn =
                    6;


                /* ----------------------------------------------
                   ALL LOCATIONS
                ---------------------------------------------- */

                if (
                    selectedLocation === '' ||
                    selectedLocation.toUpperCase()
                        === 'ALL'
                ) {

                    table
                        .column(locationColumn)
                        .search('')
                        .draw();

                }


                /* ----------------------------------------------
                   SELECTED LOCATION
                ---------------------------------------------- */

                else {

                    const escapedLocation =
                        $.fn.dataTable.util
                            .escapeRegex(
                                selectedLocation
                            );


                    table
                        .column(locationColumn)
                        .search(
                            '^' +
                            escapedLocation +
                            '$',
                            true,
                            false,
                            true
                        )
                        .draw();

                }


                /* ----------------------------------------------
                   COUNT
                ---------------------------------------------- */

                const visibleCount =
                    table.rows({
                        search: 'applied'
                    }).count();


                $('#receivedRecordCount').html(
                    'Showing <strong>' +
                    visibleCount +
                    '</strong> received subsidy record(s)'
                );


                /* ----------------------------------------------
                   EMPTY MESSAGE
                ---------------------------------------------- */

                if (
                    visibleCount === 0
                ) {

                    $('#receivedNoFilterResult')
                        .show();

                }

                else {

                    $('#receivedNoFilterResult')
                        .hide();

                }


                return;

            }


            /* ====================================================
               NORMAL HTML TABLE FILTER
            ==================================================== */

            let visibleCount = 0;

            let totalRows = 0;


            $receivedTable
                .find('tbody tr')
                .each(
                    function () {

                        const $row =
                            $(this);


                        totalRows++;


                        /*
                         * Location is already stored here:
                         *
                         * <tr data-location="Rizal">
                         */

                        const rowLocation =
                            String(
                                $row.attr(
                                    'data-location'
                                ) || ''
                            ).trim();


                        /*
                         * Normalize
                         */

                        const normalizedRowLocation =
                            rowLocation.toLowerCase();


                        const normalizedSelectedLocation =
                            selectedLocation.toLowerCase();


                        /* ----------------------------------------
                           ALL LOCATIONS
                        ---------------------------------------- */

                        if (
                            selectedLocation === '' ||
                            selectedLocation.toUpperCase()
                                === 'ALL'
                        ) {

                            $row.show();

                            visibleCount++;

                            return;

                        }


                        /* ----------------------------------------
                           MATCH
                        ---------------------------------------- */

                        if (
                            normalizedRowLocation ===
                            normalizedSelectedLocation
                        ) {

                            $row.show();

                            visibleCount++;

                        }

                        else {

                            $row.hide();

                        }

                    }
                );


            /* ====================================================
               UPDATE COUNT
            ==================================================== */

            $('#receivedRecordCount').html(

                'Showing <strong>' +
                visibleCount +
                '</strong> received subsidy record(s)'

            );


            /* ====================================================
               SHOW/HIDE EMPTY RESULT
            ==================================================== */

            if (
                totalRows > 0 &&
                visibleCount === 0
            ) {

                $('#receivedNoFilterResult')
                    .show();

            }

            else {

                $('#receivedNoFilterResult')
                    .hide();

            }

        }
    );


    /* ============================================================
       DOWNLOAD RECEIVED SUBSIDY
    ============================================================ */

    $('#downloadReceivedSubsidy').on(
        'click',
        function () {

            /*
             * Only download currently visible rows.
             */

            const rows = [];


            $('#receivedSubsidyTable tbody tr:visible')
                .each(
                    function () {

                        const row = [];


                        $(this)
                            .find('td')
                            .each(
                                function () {

                                    row.push(
                                        $(this)
                                            .text()
                                            .trim()
                                    );

                                }
                            );


                        rows.push(row);

                    }
                );


            if (rows.length === 0) {

                alert(
                    'There are no records to download.'
                );

                return;

            }


            /*
             * Header
             */

            const headers = [];


            $('#receivedSubsidyTable thead th')
                .each(
                    function () {

                        headers.push(
                            $(this)
                                .text()
                                .trim()
                        );

                    }
                );


            /*
             * CSV
             */

            let csv = '';


            csv += headers
                .map(
                    function (value) {

                        return '"' +
                            value
                                .replace(
                                    /"/g,
                                    '""'
                                ) +
                            '"';

                    }
                )
                .join(',');


            csv += '\n';


            rows.forEach(
                function (row) {

                    csv += row
                        .map(
                            function (value) {

                                return '"' +
                                    String(value)
                                        .replace(
                                            /"/g,
                                            '""'
                                        ) +
                                    '"';

                            }
                        )
                        .join(',');


                    csv += '\n';

                }
            );


            /*
             * Create download
             */

            const blob =
                new Blob(
                    [csv],
                    {
                        type:
                            'text/csv;charset=utf-8;'
                    }
                );


            const url =
                URL.createObjectURL(
                    blob
                );


            const link =
                document.createElement(
                    'a'
                );


            link.href =
                url;


            link.download =
                'received_subsidy_records.csv';


            document.body.appendChild(
                link
            );


            link.click();


            document.body.removeChild(
                link
            );


            URL.revokeObjectURL(
                url
            );

        }
    );

});

</script>