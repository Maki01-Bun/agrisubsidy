<div class="analytics-page">

    <div class="analytics-title">
        <i class="fas fa-chart-line"></i>
        AgriSubsidy Data Analytics
    </div>
    <!-- MODEL SUMMARY -->
    <div class="row g-3">
        <!-- STATUS -->
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
                        <?= number_format($totalEvaluations) ?>
                    </div>
                    <small>
                        Evaluation Records
                    </small>
                </div>
            </div>
        </div>
    </div>
    <!-- CHART ROW -->
    <div class="row g-3 mt-3">
        <!-- FEATURES -->
        <div class="col-lg-12">
            <div class="analytics-panel">
                <div class="panel-title">
                    Features That Most Affect Effectiveness
                </div>
                <div class="panel-body">
                    <div class="chart-container">
                        <canvas id="featureChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mt-3">
        <div class="col-lg-12">

            <div class="analytics-panel">

                <!-- PANEL HEADER -->
                <div class="panel-title d-flex justify-content-between align-items-center">

                    <div>
                        <i class="fas fa-seedling mr-2"></i>
                        Seed Subsidy Effectiveness
                    </div>

                    <span class="badge badge-success">
                        Overall Program Performance
                    </span>

                </div>


                <!-- PANEL BODY -->
                <div class="panel-body">

                    <?php if (!empty($seedSubsidyEffectiveness)): ?>

                        <?php
                        /*
                        * Since this dashboard focuses only on the
                        * general Seed Subsidy program, we use the
                        * evaluation data returned by the controller.
                        */

                        $seed = $seedSubsidyEffectiveness[0];

                        $totalEvaluations = (int)(
                            $seed['total_evaluations'] ?? 0
                        );

                        $effectiveCount = (int)(
                            $seed['effective_count'] ?? 0
                        );

                        $moderatelyEffectiveCount = (int)(
                            $seed['moderately_effective_count'] ?? 0
                        );

                        $notEffectiveCount = (int)(
                            $seed['not_effective_count'] ?? 0
                        );


                        /*
                        * Calculate the effectiveness rate.
                        *
                        * Effectiveness Rate =
                        * Effective Evaluations / Total Evaluations × 100
                        */

                        if ($totalEvaluations > 0) {

                            $rate = (
                                $effectiveCount /
                                $totalEvaluations
                            ) * 100;

                        } else {

                            $rate = 0;

                        }


                        /*
                        * Determine overall program status.
                        */

                        if ($rate >= 75) {

                            $badgeClass = 'badge-success';
                            $statusText = 'Highly Effective';

                        } elseif ($rate >= 50) {

                            $badgeClass = 'badge-warning';
                            $statusText = 'Moderately Effective';

                        } else {

                            $badgeClass = 'badge-danger';
                            $statusText = 'Needs Improvement';

                        }
                        ?>


                        <!-- OVERALL SUMMARY -->
                        <div class="row mb-4">

                            <!-- PROGRAM -->
                            <div class="col-lg-4 col-md-6 mb-3">

                                <div class="analytics-summary-card">

                                    <div class="d-flex align-items-center">

                                        <div class="mr-3">
                                            <i
                                                class="fas fa-seedling"
                                                style="font-size:32px;"
                                            ></i>
                                        </div>

                                        <div>

                                            <small class="text-muted">
                                                PROGRAM
                                            </small>

                                            <h5 class="mb-0">
                                                <strong>
                                                    Seed Subsidy
                                                </strong>
                                            </h5>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- TOTAL EVALUATIONS -->
                            <div class="col-lg-4 col-md-6 mb-3">

                                <div class="analytics-summary-card">

                                    <div class="d-flex align-items-center">

                                        <div class="mr-3">
                                            <i
                                                class="fas fa-clipboard-check"
                                                style="font-size:32px;"
                                            ></i>
                                        </div>

                                        <div>

                                            <small class="text-muted">
                                                TOTAL EVALUATIONS
                                            </small>

                                            <h5 class="mb-0">
                                                <strong>
                                                    <?= number_format(
                                                        $totalEvaluations
                                                    ) ?>
                                                </strong>
                                            </h5>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- EFFECTIVENESS RATE -->
                            <div class="col-lg-4 col-md-12 mb-3">

                                <div class="analytics-summary-card">

                                    <div class="d-flex align-items-center">

                                        <div class="mr-3">
                                            <i
                                                class="fas fa-chart-line"
                                                style="font-size:32px;"
                                            ></i>
                                        </div>

                                        <div>

                                            <small class="text-muted">
                                                EFFECTIVENESS RATE
                                            </small>

                                            <h5 class="mb-0">

                                                <strong>
                                                    <?= number_format(
                                                        $rate,
                                                        1
                                                    ) ?>%
                                                </strong>

                                                <span
                                                    class="badge <?= $badgeClass ?> ml-2"
                                                >
                                                    <?= h($statusText) ?>
                                                </span>

                                            </h5>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- EFFECTIVENESS TABLE -->
                        <div class="table-responsive">

                            <table
                                class="table table-bordered table-hover mb-0"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            Program Code
                                        </th>

                                        <th>
                                            Program Name
                                        </th>

                                        <th class="text-center">
                                            Total Evaluations
                                        </th>

                                        <th class="text-center">
                                            Effective
                                        </th>

                                        <th class="text-center">
                                            Moderately Effective
                                        </th>

                                        <th class="text-center">
                                            Not Effective
                                        </th>

                                        <th class="text-center">
                                            Effectiveness Rate
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <tr>

                                        <!-- PROGRAM CODE -->
                                        <td>

                                            <strong>
                                                SEED
                                            </strong>

                                        </td>


                                        <!-- PROGRAM NAME -->
                                        <td>

                                            <strong>
                                                Seed Subsidy
                                            </strong>

                                        </td>


                                        <!-- TOTAL EVALUATIONS -->
                                        <td class="text-center">

                                            <?= number_format(
                                                $totalEvaluations
                                            ) ?>

                                        </td>


                                        <!-- EFFECTIVE -->
                                        <td class="text-center">

                                            <span
                                                class="badge badge-success"
                                                style="font-size:14px;"
                                            >

                                                <?= number_format(
                                                    $effectiveCount
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- MODERATELY EFFECTIVE -->
                                        <td class="text-center">

                                            <span
                                                class="badge badge-warning"
                                                style="font-size:14px;"
                                            >

                                                <?= number_format(
                                                    $moderatelyEffectiveCount
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- NOT EFFECTIVE -->
                                        <td class="text-center">

                                            <span
                                                class="badge badge-danger"
                                                style="font-size:14px;"
                                            >

                                                <?= number_format(
                                                    $notEffectiveCount
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- EFFECTIVENESS RATE -->
                                        <td class="text-center">

                                            <strong
                                                style="font-size:17px;"
                                            >

                                                <?= number_format(
                                                    $rate,
                                                    1
                                                ) ?>%

                                            </strong>

                                            <br>

                                            <span
                                                class="badge <?= $badgeClass ?> mt-1"
                                            >

                                                <?= h($statusText) ?>

                                            </span>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>


                        <!-- INTERPRETATION -->
                        <div class="mt-4">

                            <div class="alert <?= $rate >= 75
                                ? 'alert-success'
                                : ($rate >= 50
                                    ? 'alert-warning'
                                    : 'alert-danger') ?> mb-0">

                                <div class="d-flex">

                                    <div class="mr-3">

                                        <i
                                            class="fas fa-info-circle"
                                            style="font-size:22px;"
                                        ></i>

                                    </div>

                                    <div>

                                        <strong>
                                            Seed Subsidy Performance:
                                        </strong>

                                        <?php if ($totalEvaluations > 0): ?>

                                            Out of
                                            <strong>
                                                <?= number_format(
                                                    $totalEvaluations
                                                ) ?>
                                            </strong>
                                            evaluations,
                                            <strong>
                                                <?= number_format(
                                                    $effectiveCount
                                                ) ?>
                                            </strong>
                                            were classified as
                                            <strong>Effective</strong>.

                                            This resulted in an overall
                                            effectiveness rate of
                                            <strong>
                                                <?= number_format(
                                                    $rate,
                                                    1
                                                ) ?>%
                                            </strong>,

                                            which is classified as
                                            <strong>
                                                <?= h($statusText) ?>
                                            </strong>.

                                        <?php else: ?>

                                            There are currently no
                                            evaluation results available
                                            for the Seed Subsidy program.

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>


                    <?php else: ?>

                        <!-- NO DATA -->
                        <div class="text-center py-5">

                            <i
                                class="fas fa-seedling text-muted"
                                style="font-size:48px;"
                            ></i>

                            <h5 class="mt-3">
                                No Seed Subsidy Evaluation Data
                            </h5>

                            <p class="text-muted mb-0">

                                No evaluations are currently connected
                                to the Seed Subsidy program.

                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>
    </div>
</div>

<?= $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js') ?>
<script>
const featureChart =
    document.getElementById('featureChart');
new Chart(featureChart, {
    type: 'bar',
    data: {
        labels: [
            <?php foreach ($featureImportance as $feature => $value): ?>
                <?= json_encode($feature) ?>,
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Importance',
            data: [
                <?php foreach ($featureImportance as $feature => $value): ?>
                    <?= $value ?>,
                <?php endforeach; ?>
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>