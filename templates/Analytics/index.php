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
    <div class="card card-primary feature-importance-card mt-3">

    <div class="card-header">
        <h3 class="card-title text-dark">
            <i class="fas fa-chart-bar mr-2"></i>
            Features That Most Affect Effectiveness
        </h3>
    </div>

    <div class="card-body">
        <?php
        $featureLabelsSafe = $featureLabels ?? [];
        $featureValuesSafe = $featureValues ?? [];
        /*
         * Determine if we actually have usable data.
         */
        $hasFeatureData =
            !empty($featureLabelsSafe) &&
            !empty($featureValuesSafe);
        /*
         * Check whether at least one value is greater than zero.
         */
        $hasNonZeroValue = false;
        foreach ($featureValuesSafe as $value) {
            if ((float)$value > 0) {
                $hasNonZeroValue = true;
                break;
            }
        }
        ?>
        <?php if ($hasFeatureData && $hasNonZeroValue): ?>
            <div class="feature-chart-wrapper">
                <canvas id="featureImportanceChart"></canvas>
            </div>
        <?php else: ?>
            <div class="feature-empty-state">
                <div class="feature-empty-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h5>
                    No Feature Importance Data
                </h5>
                <p>
                    Feature importance will be displayed here
                    once enough evaluation data is available.
                </p>
                <span class="feature-empty-note">
                    <i class="fas fa-info-circle mr-1"></i>
                    Continue recording farmer evaluations to generate
                    feature importance results.
                </span>
            </div>
        <?php endif; ?>
    </div>
</div>
    <!-- PROGRAM EFFECTIVENESS TABLE -->
<div class="table-responsive mt-3">
    <table class="table table-hover mb-0">
        <thead style="background:#07851f; color:white;">
            <tr>
                <th class="text-center">Rank</th>
                <th class="text-center">Program Code</th>
                <th class="text-center">Program Name</th>
                <th class="text-center">Feedback<br>Responses</th>
                <th class="text-center">Average Feedback<br>Rating</th>
                <th class="text-center">Performance</th>
                <th class="text-center">Evaluation Results</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($programEffectiveness)): ?>

                <?php foreach ($programEffectiveness as $index => $program): ?>

                    <?php
                    $rank = $index + 1;

                    $programCode = $program['program_code'] ?? 'N/A';
                    $programName = $program['program_name'] ?? 'N/A';

                    $totalFeedbacks = (int)($program['total_feedbacks'] ?? 0);
                    $averageRating = (float)($program['effectiveness_rating'] ?? 0);

                    $effectiveCount =
                        (int)($program['effective_count'] ?? 0);

                    $moderatelyEffectiveCount =
                        (int)($program['moderately_effective_count'] ?? 0);

                    $notEffectiveCount =
                        (int)($program['not_effective_count'] ?? 0);

                    $isMostEffective =
                        !empty($mostEffectiveProgram) &&
                        ($mostEffectiveProgram['program_code'] ?? '') === $programCode;

                    $ratingPercent = ($averageRating / 5) * 100;
                    ?>

                    <tr>

                        <!-- RANK -->
                        <td class="text-center align-middle">
                            <?php if ($isMostEffective): ?>

                                <span class="badge badge-success"
                                      style="font-size:14px; padding:7px 10px;">
                                    <i class="fas fa-trophy"></i>
                                    #<?= $rank ?>
                                </span>

                            <?php else: ?>

                                <strong>#<?= $rank ?></strong>

                            <?php endif; ?>
                        </td>

                        <!-- PROGRAM CODE -->
                        <td class="align-middle">
                            <strong>
                                <?= h($programCode) ?>
                            </strong>
                        </td>

                        <!-- PROGRAM NAME -->
                        <td class="align-middle">
                            <strong>
                                <?= h($programName) ?>
                            </strong>
                        </td>

                        <!-- FEEDBACK RESPONSES -->
                        <td class="text-center align-middle">
                            <?= $totalFeedbacks ?>
                        </td>

                        <!-- AVERAGE FEEDBACK RATING -->
                        <td class="text-center align-middle">

                            <div style="font-size:20px; font-weight:bold;">
                                <?= number_format($averageRating, 2) ?>
                                / 5.00
                            </div>

                            <div style="margin-top:5px; font-size:20px;">

                                <?php
                                $fullStars = floor($averageRating);
                                $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                ?>

                                <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>

                                <?php if ($hasHalfStar): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php endif; ?>

                                <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>

                            </div>

                        </td>

                        <!-- PERFORMANCE -->
                        <td class="text-center align-middle">

                            <?php if ($isMostEffective): ?>

                                <span class="badge badge-success"
                                      style="font-size:13px; padding:7px 10px;">
                                    <i class="fas fa-trophy"></i>
                                    Most Effective
                                </span>

                            <?php elseif ($averageRating >= 4.00): ?>

                                <span class="badge badge-success">
                                    Highly Rated
                                </span>

                            <?php elseif ($averageRating >= 3.00): ?>

                                <span class="badge badge-warning">
                                    Moderately Rated
                                </span>

                            <?php else: ?>

                                <span class="badge badge-danger">
                                    Low Rated
                                </span>

                            <?php endif; ?>

                        </td>

                        <!-- EVALUATION RESULTS -->
                        <td class="text-center align-middle">

                            <div class="mb-1">

                                <span class="badge badge-success">
                                    Effective:
                                    <?= $effectiveCount ?>
                                </span>

                                <span class="badge badge-warning">
                                    Moderate:
                                    <?= $moderatelyEffectiveCount ?>
                                </span>

                            </div>

                            <div>

                                <span class="badge badge-danger">
                                    Not Effective:
                                    <?= $notEffectiveCount ?>
                                </span>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7"
                        class="text-center text-muted py-4">

                        <i class="fas fa-info-circle"></i>
                        No program evaluation data available.

                    </td>
                </tr>

            <?php endif; ?>

        </tbody>
    </table>
</div>
</div>

<?= $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js') ?>
<script>
    /* =========================================================
       FEATURES THAT MOST AFFECT EFFECTIVENESS
    ========================================================= */
    const featureLabels =
        <?= json_encode($featureLabels ?? []) ?>;
    const featureValues =
        <?= json_encode($featureValues ?? []) ?>;
    const featureCanvas =
        document.getElementById('featureImportanceChart');

    /*
     * Create chart only when:
     * 1. Canvas exists
     * 2. Feature labels exist
     * 3. Feature values exist
     */
    if (
        featureCanvas &&
        Array.isArray(featureLabels) &&
        Array.isArray(featureValues) &&
        featureLabels.length > 0 &&
        featureValues.length > 0
    ) {
        const featureCtx =
            featureCanvas.getContext('2d');
        /*
         * Convert values to numbers
         */
        const importanceValues =
            featureValues.map(function (value) {
                return Number(value) || 0;
            });

        /*
         * Create Feature Importance Chart
         */

        new Chart(featureCtx, {
            type: 'bar',
            data: {
                labels: featureLabels,
                datasets: [{
                    label: 'Importance (%)',
                    data: importanceValues,
                    backgroundColor:
                        'rgba(54, 162, 235, 0.55)',
                    borderColor:
                        'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    barPercentage: 0.65,
                    categoryPercentage: 0.75
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 700
                },
                plugins: {
                    /*
                     * Hide legend because there is
                     * only one dataset
                     */
                    legend: {

                        display: false
                    },
                    /*
                     * Tooltip
                     */
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value =
                                    Number(context.raw) || 0;
                                return (
                                    'Importance: ' +
                                    value.toFixed(2) +
                                    '%'
                                );
                            }
                        }
                    }
                },
                scales: {
                    /*
                     * Y AXIS
                     */
                    y: {

                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 10,
                            callback: function (value) {
                                return value + '%';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Importance (%)'
                        }
                    },
                    /*
                     * X AXIS
                     */
                    x: {
                        grid: {

                            display: false
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });

    } else {

        console.warn(
            'Feature importance data is unavailable.'
        );
    }
</script>