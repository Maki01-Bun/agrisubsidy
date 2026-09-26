<div class="analytics-page">

    <!-- =========================================================
         PAGE TITLE
    ========================================================= -->

    <div class="analytics-title">

        <i class="fas fa-chart-line"></i>

        AgriSubsidy Data Analytics

    </div>


    <!-- =========================================================
         MODEL SUMMARY
    ========================================================= -->

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
                        class="form-control"
                    >

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
                 YIELD TREND
            ================================================== -->

            <div class="trend-panel">

                <div class="trend-panel-header">

                    <div>

                        <h4>

                            <i class="fas fa-seedling"></i>

                            Yield Trend

                        </h4>

                        <small>
                            Average yield before and after
                            the subsidy.
                        </small>

                    </div>

                </div>


                <div class="trend-chart-container">

                    <canvas
                        id="yieldTrendChart"
                    ></canvas>

                </div>

            </div>


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


    <!-- =========================================================
         SUBSIDY RECEIVED RECORDS
    ========================================================= -->

    <div class="card subsidy-received-card mt-3">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="subsidy-received-header">

            <div class="subsidy-received-header-inner">


                <!-- TITLE -->

                <div class="subsidy-received-title">

                    <h3>
                        Beneficiaries Received the Subsidy
                    </h3>

                </div>


                <!-- FILTER + DOWNLOAD -->

                <div class="subsidy-received-tools">


                    <!-- LOCATION -->

                    <div class="subsidy-location-filter">

                        <select
                            id="receivedLocationFilter"
                            class="form-control"
                        >

                            <option value="ALL">
                                All Locations
                            </option>


                            <?php
                            $receivedLocations =
                                $receivedLocations ?? [];
                            ?>


                            <?php foreach (
                                $receivedLocations
                                as $location
                            ): ?>

                                <option
                                    value="<?= h(
                                        $location
                                    ) ?>"
                                >
                                    <?= h(
                                        $location
                                    ) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- DOWNLOAD -->

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


        <!-- =====================================================
             BODY
        ====================================================== -->

        <div class="subsidy-received-body">

            <?php
            $receivedRecords =
                $receivedRecords ?? [];
            ?>


            <?php if (
                !empty(
                    $receivedRecords
                )
            ): ?>


                <!-- COUNT -->

                <div
                    class="received-record-count"
                    id="receivedRecordCount"
                >

                    Showing

                    <strong>
                        <?= count(
                            $receivedRecords
                        ) ?>
                    </strong>

                    received subsidy record(s)

                </div>


                <!-- TABLE -->

                <div
                    class="table-responsive received-table-wrapper"
                >

                    <table
                        id="receivedSubsidyTable"
                        class="table subsidy-received-table mb-0"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

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
                                            $record[
                                                'barangay'
                                            ]
                                            ?? 'N/A'
                                        )
                                    );


                                if (
                                    $location === ''
                                ) {

                                    $location =
                                        'N/A';

                                }

                                ?>


                                <tr
                                    data-location="<?= h(
                                        $location
                                    ) ?>"
                                >


                                    <!-- NUMBER -->

                                    <td
                                        class="row-number"
                                    >

                                        <?= $index + 1 ?>

                                    </td>


                                    <!-- FARMER NO -->

                                    <td>

                                        <strong
                                            class="farmer-number"
                                        >

                                            <?= h(
                                                $record[
                                                    'farmer_no'
                                                ]
                                                ?? '-'
                                            ) ?>

                                        </strong>

                                    </td>


                                    <!-- FARMER NAME -->

                                    <td>

                                        <strong
                                            class="farmer-name"
                                        >

                                            <?= h(
                                                $record[
                                                    'farmer_name'
                                                ]
                                                ?? '-'
                                            ) ?>

                                        </strong>

                                    </td>


                                    <!-- DISTRIBUTION CODE -->

                                    <td>

                                        <span
                                            class="program-badge"
                                        >

                                            <?= h(
                                                $record[
                                                    'program_code'
                                                ]
                                                ?? '-'
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- SUBSIDY ITEM -->

                                    <td>

                                        <?= h(
                                            $record[
                                                'subsidy_item'
                                            ]
                                            ?? 'Seed Subsidy'
                                        ) ?>

                                    </td>


                                    <!-- QUANTITY -->

                                    <td>

                                        <span
                                            class="quantity-value"
                                        >

                                            <?= h(
                                                $record[
                                                    'quantity'
                                                ]
                                                ?? '0.00'
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- LOCATION -->

                                    <td>

                                        <span
                                            class="location-value"
                                        >

                                            <?= h(
                                                $location
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- DISTRIBUTION DATE -->

                                    <td>

                                        <?= h(
                                            $record[
                                                'distribution_date'
                                            ]
                                            ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- DISTRIBUTION TIME -->

                                    <td>

                                        <?= h(
                                            $record[
                                                'distribution_time'
                                            ]
                                            ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- RECEIVED DATE -->

                                    <td>

                                        <?= h(
                                            $record[
                                                'received_date'
                                            ]
                                            ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <span
                                            class="received-status"
                                        >

                                            <?= h(
                                                $record[
                                                    'status'
                                                ]
                                                ?? 'Received'
                                            ) ?>

                                        </span>

                                    </td>


                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <!-- EMPTY FILTER -->

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


                <!-- EMPTY -->

                <div
                    class="subsidy-received-empty"
                >

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


<!-- =========================================================
     CHART DATA
========================================================= -->

<script>

const yieldTrendData =
    <?= json_encode(
        $yieldTrend ?? [],
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    ) ?>;


const effectivenessTrendData =
    <?= json_encode(
        $effectivenessTrend ?? [],
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


<!-- =========================================================
     CHART.JS
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/chart.js"
></script>


<!-- =========================================================
     TREND CHARTS
========================================================= -->

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
         * =====================================================
         * PERIOD SELECTOR
         * =====================================================
         */

        const trendPeriod =
            document.getElementById(
                'trendPeriod'
            );


        /*
         * =====================================================
         * CHART VARIABLES
         * =====================================================
         */

        let yieldChart =
            null;


        let effectivenessChart =
            null;


        let surveyChart =
            null;


        let distributionChart =
            null;


        /*
         * =====================================================
         * FILTER PERIOD
         * =====================================================
         */

        function getPeriodData(
            data,
            period
        ) {

            return (
                data || []
            ).filter(
                function (
                    row
                ) {

                    return String(
                        row.period_type
                        ?? ''
                    ).toLowerCase()
                    ===
                    period;

                }
            );

        }


        /*
         * =====================================================
         * DESTROY CHART
         * =====================================================
         */

        function destroyChart(
            chart
        ) {

            if (
                chart
            ) {

                chart.destroy();

            }

        }


        /*
         * =====================================================
         * YIELD CHART
         * =====================================================
         */

        function renderYieldChart(
            period
        ) {

            const canvas =
                document.getElementById(
                    'yieldTrendChart'
                );


            if (
                !canvas
            ) {

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
                    function (
                        row
                    ) {

                        return row.period;

                    }
                );


            const before =
                data.map(
                    function (
                        row
                    ) {

                        return Number(
                            row.yield_before
                            ?? 0
                        );

                    }
                );


            const after =
                data.map(
                    function (
                        row
                    ) {

                        return Number(
                            row.yield_after
                            ?? 0
                        );

                    }
                );


            yieldChart =
                new Chart(
                    canvas,
                    {

                        type:
                            'line',

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

                            plugins: {

                                legend: {

                                    display:
                                        true

                                }

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


        /*
         * =====================================================
         * EFFECTIVENESS CHART
         * =====================================================
         */

        function renderEffectivenessChart(
            period
        ) {

            const canvas =
                document.getElementById(
                    'effectivenessTrendChart'
                );


            if (
                !canvas
            ) {

                return;

            }


            destroyChart(
                effectivenessChart
            );


            const data =
                getPeriodData(
                    effectivenessTrendData,
                    period
                );


            const labels =
                data.map(
                    function (
                        row
                    ) {

                        return row.period;

                    }
                );


            effectivenessChart =
                new Chart(
                    canvas,
                    {

                        type:
                            'line',

                        data: {

                            labels:
                                labels,

                            datasets: [

                                {
                                    label:
                                        'Effective',

                                    data:
                                        data.map(
                                            function (
                                                row
                                            ) {

                                                return Number(
                                                    row.effective
                                                    ?? 0
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
                                        'Moderately Effective',

                                    data:
                                        data.map(
                                            function (
                                                row
                                            ) {

                                                return Number(
                                                    row.moderately_effective
                                                    ?? 0
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
                                        'Not Effective',

                                    data:
                                        data.map(
                                            function (
                                                row
                                            ) {

                                                return Number(
                                                    row.not_effective
                                                    ?? 0
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
                                        'Not Yet Predicted',

                                    data:
                                        data.map(
                                            function (
                                                row
                                            ) {

                                                return Number(
                                                    row.not_yet_predicted
                                                    ?? 0
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
                                            'Evaluation Records'

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


        /*
         * =====================================================
         * SURVEY CHART
         * =====================================================
         */

        function renderSurveyChart(
            period
        ) {

            const canvas =
                document.getElementById(
                    'surveyTrendChart'
                );


            if (
                !canvas
            ) {

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
                    function (
                        row
                    ) {

                        return row.period;

                    }
                );


            const ratings =
                data.map(
                    function (
                        row
                    ) {

                        return Number(
                            row.average_rating
                            ?? 0
                        );

                    }
                );


            surveyChart =
                new Chart(
                    canvas,
                    {

                        type:
                            'line',

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


        /*
         * =====================================================
         * DISTRIBUTION CHART
         * =====================================================
         */

        function renderDistributionChart(
            period
        ) {

            const canvas =
                document.getElementById(
                    'distributionTrendChart'
                );


            if (
                !canvas
            ) {

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
                    function (
                        row
                    ) {

                        return row.period;

                    }
                );


            distributionChart =
                new Chart(
                    canvas,
                    {

                        type:
                            'line',

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
                                                    row.received
                                                    ?? 0
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
                                                    row.not_received
                                                    ?? 0
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
                                                    row.rescheduled
                                                    ?? 0
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
                                                    row.cancelled
                                                    ?? 0
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


        /*
         * =====================================================
         * RENDER EVERYTHING
         * =====================================================
         */

        function renderAllCharts()
        {

            const period =
                trendPeriod
                    ? trendPeriod.value
                    : 'monthly';


            renderYieldChart(
                period
            );


            renderEffectivenessChart(
                period
            );


            renderSurveyChart(
                period
            );


            renderDistributionChart(
                period
            );

        }


        /*
         * =====================================================
         * PERIOD CHANGE
         * =====================================================
         */

        if (
            trendPeriod
        ) {

            trendPeriod.addEventListener(
                'change',
                function ()
                {

                    renderAllCharts();

                }
            );

        }


        /*
         * =====================================================
         * INITIAL RENDER
         * =====================================================
         */

        renderAllCharts();

    }

);

</script>


<!-- =========================================================
     RECEIVED SUBSIDY FILTER / DOWNLOAD
========================================================= -->

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
         * =====================================================
         * ELEMENTS
         * =====================================================
         */

        const locationFilter =
            document.getElementById(
                'receivedLocationFilter'
            );


        const downloadButton =
            document.getElementById(
                'downloadReceivedSubsidy'
            );


        const table =
            document.getElementById(
                'receivedSubsidyTable'
            );


        /*
         * FIXED:
         * Your HTML uses receivedRecordCount.
         */

        const visibleCount =
            document.getElementById(
                'receivedRecordCount'
            );


        const noResult =
            document.getElementById(
                'receivedNoFilterResult'
            );


        if (
            !locationFilter ||
            !table
        ) {

            return;

        }


        /*
         * =====================================================
         * ROWS
         * =====================================================
         */

        const rows =
            Array.from(
                table.querySelectorAll(
                    'tbody tr'
                )
            );


        /*
         * =====================================================
         * ROW NUMBERS
         * =====================================================
         */

        function updateRowNumbers(
            visibleRows
        ) {

            visibleRows.forEach(
                function (
                    row,
                    index
                ) {

                    const numberCell =
                        row.querySelector(
                            '.row-number'
                        );


                    if (
                        numberCell
                    ) {

                        numberCell.textContent =
                            index + 1;

                    }

                }
            );

        }


        /*
         * =====================================================
         * FILTER
         * =====================================================
         */

        function filterRecords()
        {

            const selectedLocation =
                (
                    locationFilter.value
                    || 'ALL'
                ).trim();


            const visibleRows = [];


            rows.forEach(
                function (
                    row
                ) {

                    const rowLocation =
                        (
                            row.dataset.location
                            || ''
                        ).trim();


                    const shouldShow =
                        selectedLocation ===
                        'ALL'
                        ||
                        rowLocation.toLowerCase()
                        ===
                        selectedLocation.toLowerCase();


                    if (
                        shouldShow
                    ) {

                        row.style.display =
                            '';

                        visibleRows.push(
                            row
                        );

                    } else {

                        row.style.display =
                            'none';

                    }

                }
            );


            /*
             * NUMBER
             */

            updateRowNumbers(
                visibleRows
            );


            /*
             * COUNT
             */

            if (
                visibleCount
            ) {

                visibleCount.innerHTML =
                    'Showing <strong>'
                    +
                    visibleRows.length
                    +
                    '</strong> received subsidy record(s)';

            }


            /*
             * EMPTY
             */

            if (
                noResult
            ) {

                noResult.style.display =
                    visibleRows.length === 0
                        ? 'block'
                        : 'none';

            }

        }


        /*
         * =====================================================
         * FILTER CHANGE
         * =====================================================
         */

        locationFilter.addEventListener(
            'change',
            function ()
            {

                filterRecords();

            }
        );


        /*
         * =====================================================
         * DOWNLOAD
         * =====================================================
         */

        if (
            downloadButton
        ) {

            downloadButton.addEventListener(
                'click',
                function ()
                {

                    const selectedLocation =
                        locationFilter.value;


                    const visibleRows =
                        rows.filter(
                            function (
                                row
                            ) {

                                return (
                                    row.style.display
                                    !==
                                    'none'
                                );

                            }
                        );


                    if (
                        visibleRows.length === 0
                    ) {

                        alert(
                            'There are no received subsidy records to download for the selected location.'
                        );

                        return;

                    }


                    const originalHTML =
                        downloadButton.innerHTML;


                    downloadButton.disabled =
                        true;


                    downloadButton.innerHTML =
                        '<i class="fas fa-spinner fa-spin mr-1"></i> Generating...';


                    const baseUrl =
                        '<?= $this->Url->build([
                            "controller" => "Records",
                            "action" => "downloadReceivedSubsidyExcel"
                        ]) ?>';


                    const url =
                        baseUrl
                        +
                        '?location='
                        +
                        encodeURIComponent(
                            selectedLocation
                        );


                    window.location.href =
                        url;


                    setTimeout(
                        function ()
                        {

                            downloadButton.disabled =
                                false;


                            downloadButton.innerHTML =
                                originalHTML;

                        },
                        1500
                    );

                }
            );

        }


        /*
         * =====================================================
         * INITIAL FILTER
         * =====================================================
         */

        filterRecords();

    }
);

</script>