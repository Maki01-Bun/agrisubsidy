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