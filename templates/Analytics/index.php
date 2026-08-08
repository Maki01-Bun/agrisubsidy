<div class="analytics-page">

    <div class="analytics-title">
        <i class="fas fa-chart-line"></i>
        AgriSubsidy Data Analytics
    </div>

    <!-- MODEL SUMMARY -->
    <div class="row g-3">
        <!-- STATUS -->
        <div class="col-lg-3 col-md-6">
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

        <!-- ACCURACY -->
        <div class="col-lg-3 col-md-6">
            <div class="model-card">
                <div class="model-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="model-label">
                        Accuracy
                    </div>
                    <div class="model-value">
                        <?= number_format($modelAccuracy, 1) ?>%
                    </div>
                </div>
            </div>
        </div>

        <!-- LAST TRAINED -->
        <div class="col-lg-3 col-md-6">
            <div class="model-card">
                <div class="model-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="model-label">
                        Last Trained
                    </div>
                    <div class="model-value" style="font-size:15px;">
                        <?= h($lastTrained) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA USED -->
        <div class="col-lg-3 col-md-6">
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
    <div class="row g-3">

        <!-- EFFECTIVENESS -->
        <div class="col-lg-5">
            <div class="analytics-panel">
                <div class="panel-title">
                    Effectiveness Evaluation
                </div>
                <div class="panel-body">
                    <div class="chart-container">
                        <canvas id="effectivenessChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- FEATURES -->
        <div class="col-lg-7">
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

    <!-- PROGRAM RECOMMENDATIONS -->
    <div class="row g-3 mt-3">
        <div class="col-lg-8">
            <div class="analytics-panel">
                <div class="panel-title bg-success text-white">
                    Program Effectiveness Recommendation
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 recommendation-table">
                            <thead>
                                <tr>
                                    <th>Subsidy Program</th>
                                    <th>Prediction</th>
                                    <th>Confidence</th>
                                    <th>Suggested Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($recommendations as $recommendation): ?>
                                <tr>
                                    <td>
                                        <?= h($recommendation['program']) ?>
                                    </td>
                                    <td>
                                        <?php if ($recommendation['prediction'] === 'Effective'): ?>
                                            <span class="prediction-effective">
                                                <?= h($recommendation['prediction']) ?>
                                            </span>
                                        <?php elseif ($recommendation['prediction'] === 'Moderately Effective'): ?>
                                            <span class="prediction-moderate">
                                                <?= h($recommendation['prediction']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="prediction-not">
                                                <?= h($recommendation['prediction']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= h($recommendation['confidence']) ?>%
                                    </td>
                                    <td>
                                        <?= h($recommendation['action']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODEL ACTIONS -->
        <div class="col-lg-4">
            <div class="analytics-panel">
                <div class="panel-title bg-danger text-white">
                    Model Actions
                </div>
                <div class="panel-body">
                    <?= $this->Html->link(
                        '<i class="fas fa-sync-alt"></i> Retrain Model',['controller' => 'Analytics', 'action' => 'retrain'],
                        ['class' => 'btn btn-light border model-action','escape' => false])?>
                    <button class="btn btn-light border model-action">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Upload New Data
                    </button>
                    <button class="btn btn-light border model-action">
                        <i class="fas fa-download"></i>
                        Export Results
                    </button>
                    <button class="btn btn-light border model-action">
                        <i class="fas fa-chart-bar"></i>
                        View Model Reports
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js') ?>
<script>
const effectivenessChart =
    document.getElementById('effectivenessChart');
new Chart(effectivenessChart, {
    type: 'pie',
    data: {
        labels: [
            'Effective',
            'Moderately Effective',
            'Not Effective'
        ],
        datasets: [{
            data: [
                <?= $effective ?>,
                <?= $moderatelyEffective ?>,
                <?= $notEffective ?>
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

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