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
                <div class="panel-title d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-seedling mr-2"></i>
                        Seed Subsidy Effectiveness
                    </div>
                    <span class="badge badge-success">
                        Overall Program Performance
                    </span>
                </div>
                <div class="panel-body">
                    <?php if (!empty($seedSubsidyEffectiveness)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Program Code</th>
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
                                    <?php foreach (
                                        $seedSubsidyEffectiveness
                                        as $seed
                                    ): ?>
                                        <?php
                                        $rate = (float)(
                                            $seed['effectiveness_rate'] ?? 0
                                        );
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
                                        <tr>
                                            <!-- PROGRAM -->
                                            <td>
                                                <strong>
                                                    <?= h(
                                                        $seed['program_name']
                                                        ?? 'Seed Subsidy'
                                                    ) ?>
                                                </strong>
                                            </td>
                                            <!-- TOTAL -->
                                            <td class="text-center">
                                                <?= number_format(
                                                    (int)(
                                                        $seed[
                                                            'total_evaluations'
                                                        ] ?? 0
                                                    )
                                                ) ?>
                                            </td>
                                            <!-- EFFECTIVE -->
                                            <td class="text-center">
                                                <span class="badge badge-success">
                                                    <?= number_format(
                                                        (int)(
                                                            $seed[
                                                                'effective_count'
                                                            ] ?? 0
                                                        )
                                                    ) ?>
                                                </span>
                                            </td>
                                            <!-- MODERATELY EFFECTIVE -->
                                            <td class="text-center">
                                                <span class="badge badge-warning">
                                                    <?= number_format(
                                                        (int)(
                                                            $seed[
                                                                'moderately_effective_count'
                                                            ] ?? 0
                                                        )
                                                    ) ?>
                                                </span>
                                            </td>
                                            <!-- NOT EFFECTIVE -->
                                            <td class="text-center">
                                                <span class="badge badge-danger">
                                                    <?= number_format(
                                                        (int)(
                                                            $seed[
                                                                'not_effective_count'
                                                            ] ?? 0
                                                        )
                                                    ) ?>
                                                </span>
                                            </td>
                                            <!-- RATE -->
                                            <td class="text-center">
                                                <strong>
                                                    <?= number_format(
                                                        $rate,
                                                        1
                                                    ) ?>%
                                                </strong>
                                                <br>
                                                <span class="badge <?= $badgeClass ?> mt-1">

                                                    <?= h($statusText) ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i
                                class="fas fa-seedling text-muted"
                                style="font-size:42px;"
                            ></i>
                            <h5 class="mt-3">
                                No Seed Subsidy Evaluation Data
                            </h5>
                            <p class="text-muted mb-0">
                                No evaluations are currently connected
                                to Seed Subsidy schedules.
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