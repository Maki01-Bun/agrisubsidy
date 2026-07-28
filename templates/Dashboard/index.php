<div class="col-md-4 col-sm-6 d-flex">
    <div class="card stat-card card-hover w-100 h-100">
        <div class="card-body d-flex align-items-center bg-success">
            <i class="fas fa-check fa-2x me-3 opacity-75"></i>
            <div>
                <h6 class="fw-light mb-1">Fertilizer Distributed</h6>
                <h3 class="mb-0 fw-bold">
                    20
                </h3>
             </div>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-6 d-flex">
    <div class="card stat-card card-hover w-100 h-100">
        <div class="card-body d-flex align-items-center bg-warning">
            <i class="fas fa-calendar-alt fa-2x me-3 opacity-75"></i>
            <div>
                <h6 class="fw-light mb-1">Re-Scheduled</h6>
                <h3 class="mb-0 fw-bold">
                    3
                </h3>
             </div>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-6 d-flex">
    <div class="card stat-card card-hover w-100 h-100">
        <div class="card-body d-flex align-items-center bg-danger">
            <i class="fas fa-times fa-2x me-3 opacity-75"></i>
            <div>
                <h6 class="fw-light mb-1">Cancelled</h6>
                <h3 class="mb-0 fw-bold">
                    1
                </h3>
             </div>
        </div>
    </div>
</div>
<div class="row mt-4 g-4">

    <!-- Effectiveness Chart -->
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 fw-semibold">
                <i class="fas fa-chart-pie text-primary me-2"></i>
                Effectiveness Evaluation
            </div>

            <div class="card-body">
                <div style="height:350px;">
                    <canvas id="effectivenessChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Summary -->
    <div class="col-lg-3">

        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-star text-warning me-2"></i>
                Feedback Summary
            </div>

            <div class="card-body">

                <div class="text-center mb-4">
                    <h6 class="text-muted">Average Rating</h6>
                    <h1 class="fw-bold text-primary">
                        <?= number_format($avgRating ?? 0, 1) ?>
                        <small class="fs-6">/ 5</small>
                    </h1>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>
                        <i class="fas fa-smile text-success me-2"></i>
                        Positive Feedback
                    </span>
                    <span class="badge bg-success">
                        <?= $positive ?? 0 ?>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>
                        <i class="fas fa-meh text-warning me-2"></i>
                        Neutral Feedback
                    </span>
                    <span class="badge bg-warning">
                        <?= $neutral ?? 0 ?>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-frown text-danger me-2"></i>
                        Negative Feedback
                    </span>
                    <span class="badge bg-danger">
                        <?= $negative ?? 0 ?>
                    </span>
                </div>

                <hr>

                <div class="row text-center mt-4">
                    <div class="col-6">
                        <h6>Total Beneficiaries</h6>
                        <h4 class="text-primary">
                            <?= $totalBeneficiaries ?? 0 ?>
                        </h4>
                    </div>

                    <div class="col-6">
                        <h6>Registered Farmers</h6>
                        <h4 class="text-success">
                            <?= $totalFarmers ?? 0 ?>
                        </h4>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- Registration Requests -->
<div class="row mt-4 bottom-4">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header registration-header fw-semibold">
                <i class="fas fa-user-clock me-2"></i>
                Registration Requests
            </div>
            <div class="card-body">
                <table id="registration-table" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="check-all">
                            </th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th width="250">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($notifications)) : ?>
                        <?php foreach ($notifications as $notif) : ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="notif-checkbox"value="<?= $notif->id ?>">
                                </td>
                                <td>
                                    <?= h($notif->message) ?>
                                </td>
                                <td>
                                    <?= $notif->created? $notif->created->format('M d, Y h:i A'): '' ?>
                                </td>
                                <td>
                                    <?php if ($notif->status == 'pending') : ?>
                                        <span class="badge badge-warning">
                                            Pending
                                        </span>
                                    <?php elseif ($notif->status == 'approved') : ?>
                                        <span class="badge badge-success">
                                            Approved
                                        </span>
                                    <?php elseif ($notif->status == 'declined') : ?>
                                        <span class="badge badge-danger">
                                            Declined
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <?= $this->Html->link('<i class="fas fa-eye"></i>',
                                        ['controller' => 'Notifications','action' => 'viewRegistration',$notif->id],
                                        ['class' => 'btn btn-info btn-sm','escape' => false,'title' => 'View Details']) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No notifications found.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($labels) ?>;
const totals = <?= json_encode($totals) ?>;

const ctx = document.getElementById('effectivenessChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Evaluations',
            data: totals,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
        }
    }
});
</script>