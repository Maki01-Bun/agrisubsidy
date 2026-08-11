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
    <div class="row g-3 mt-3">

        <!-- EFFECTIVENESS -->
        <div class="col-lg-5">
            <div class="analytics-panel">
                <div class="panel-title">
                    <i class="fas fa-chart-pie me-2"></i>
                    Most Effective Subsidy Program
                </div>
                <div class="panel-body">
                    <div class="chart-container">
                        <canvas id="programEffectivenessChart"></canvas>
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

   <!--PROGRAM EFFECTIVENESS RECOMMENDATION-->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="analytics-panel recommendation-panel">
                <div class="panel-title recommendation-title bg-success">
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
                            <?php if (!empty($recommendations)): ?>
                                <?php foreach ($recommendations as $recommendation): ?>
                                    <tr>
                                        <td class="program-name">
                                            <?= h($recommendation['program']) ?>
                                        </td>
                                        <td>
                                            <?php if (
                                                $recommendation['prediction']
                                                === 'Effective'
                                            ): ?>
                                                <span class="prediction-effective">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    <?= h($recommendation['prediction']) ?>
                                                </span>
                                            <?php elseif (
                                                $recommendation['prediction']
                                                === 'Moderately Effective'
                                            ): ?>
                                                <span class="prediction-moderate">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    <?= h($recommendation['prediction']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="prediction-not">
                                                    <i class="fas fa-times-circle me-1"></i>
                                                    <?= h($recommendation['prediction']) ?>
                                                </span>

                                            <?php endif; ?>
                                        </td>
                                        <td class="confidence-value">
                                            <?= h($recommendation['confidence']) ?>%
                                        </td>
                                        <td class="action-value">
                                            <?= h($recommendation['action']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="analytics-empty">
                                            <i class="fas fa-info-circle"></i>
                                            No program recommendations available.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js') ?>
<script>
const programEffectivenessChart =
    document.getElementById('programEffectivenessChart');
if (programEffectivenessChart) {
    const recommendations =
        <?= json_encode($recommendations ?? []) ?>;
    if (recommendations.length > 0) {
        // Sort by confidence, highest first
        recommendations.sort(function (a, b) {
            return parseFloat(b.confidence) -
            parseFloat(a.confidence);
        });
        const programs = recommendations.map(function (item) {
            return item.program;
        });
        const confidence = recommendations.map(function (item) {
            return parseFloat(item.confidence);
        });
        const predictions = recommendations.map(function (item) {
            return item.prediction;
        });
        new Chart(programEffectivenessChart, {
            type: 'doughnut',
            data: {
                labels: programs,
                datasets: [{
                    data: confidence,
                    backgroundColor: [
                        '#28a745',
                        '#ffb13b',
                        '#ff6384',
                        '#36a2eb',
                        '#9966ff'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,

                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const index =
                                    context.dataIndex;
                                return (' ' + predictions [index] + ' — ' + confidence[index] + '% confidence');
                            }
                        }
                    }
                }
            }
        });
    }
}

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