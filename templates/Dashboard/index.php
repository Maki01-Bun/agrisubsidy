<div class="container-fluid px-0">
    <div class="row g-4 mb-4">
        <!-- Fertilizer Distributed -->
        <div class="col-xl-4 col-md-6">
            <div class="dashboard-stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Subsidy Distributed</span>
                    <h2><?= h($subsidyDistributed ?? 0) ?></h2>
                    <small>
                        <i class="fas fa-check-circle me-1"></i>
                        Successfully distributed
                    </small>
                </div>
                <div class="stat-decoration">
                    <i class="fas fa-seedling"></i>
                </div>
            </div>
        </div>
        <!-- Re-Scheduled -->
        <div class="col-xl-4 col-md-6">
            <div class="dashboard-stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Re-Scheduled</span>
                    <h2><?= h($rescheduled ?? 0) ?></h2>
                    <small>
                        <i class="fas fa-clock me-1"></i>
                        Programs rescheduled
                    </small>
                </div>
                <div class="stat-decoration">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        <!-- Cancelled -->
        <div class="col-xl-4 col-md-6">
            <div class="dashboard-stat-card stat-danger">
                <div class="stat-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Cancelled</span>
                    <h2><?= h($cancelled ?? 0) ?></h2>
                    <small>
                        <i class="fas fa-times-circle me-1"></i>
                        Cancelled programs
                    </small>
                </div>

                <div class="stat-decoration">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- =========================
         ANALYTICS SECTION
    ========================== -->
    <div class="row g-4">
        <!-- Effectiveness Evaluation -->
        <div class="col-xl-8 col-lg-7">
            <div class="dashboard-card h-100">
                <div class="dashboard-card-header">
                    <div>
                        <div class="section-icon bg-primary-subtle text-primary">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div>
                            <h5>Effectiveness Evaluation</h5>
                            <p>Overall subsidy program effectiveness</p>
                        </div>
                    </div>
                    <span class="analytics-badge">
                        <i class="fas fa-chart-line me-1"></i>
                        Analytics
                    </span>
                </div>
                <div class="dashboard-card-body">
                    <div class="chart-wrapper">
                        <canvas id="effectivenessChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feedback Summary -->
        <div class="col-xl-4 col-lg-5">
            <div class="dashboard-card h-100">
                <div class="dashboard-card-header">
                    <div>
                        <div class="section-icon bg-warning-subtle text-warning">
                            <i class="fas fa-star"></i>
                        </div>

                        <div>
                            <h5>Feedback Summary</h5>
                            <p>Farmer satisfaction overview</p>
                        </div>
                    </div>
                </div>
                <div class="dashboard-card-body">
                    <!-- Average Rating -->
                    <div class="rating-box">
                        <div class="rating-circle">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <span class="rating-label">
                                Average Rating
                            </span>
                            <div class="rating-value">
                                <?= number_format($avgRating ?? 0, 1) ?>
                                <small>/ 5</small>
                            </div>
                            <div class="rating-stars">
                                <?php
                                $rating = round($avgRating ?? 0);
                                for ($i = 1; $i <= 5; $i++) :
                                ?>
                                    <i class="fas fa-star <?= $i <= $rating ? 'active' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Feedback Breakdown -->
                    <div class="feedback-list">
                        <!-- Positive -->
                        <div class="feedback-item">
                            <div class="feedback-info">
                                <span class="feedback-icon positive">
                                    <i class="fas fa-smile"></i>
                                </span>
                                <div>
                                    <span class="feedback-title">
                                        Positive Feedback
                                    </span>
                                    <small>
                                        Satisfied beneficiaries
                                    </small>
                                </div>
                            </div>
                            <span class="feedback-count positive-count">
                                <?= $positive ?? 0 ?>
                            </span>
                        </div>
                        <!-- Neutral -->
                        <div class="feedback-item">
                            <div class="feedback-info">
                                <span class="feedback-icon neutral">
                                    <i class="fas fa-meh"></i>
                                </span>
                                <div>
                                    <span class="feedback-title">
                                        Neutral Feedback
                                    </span>
                                    <small>
                                        Average satisfaction
                                    </small>
                                </div>
                            </div>
                            <span class="feedback-count neutral-count">
                                <?= $neutral ?? 0 ?>
                            </span>
                        </div>
                        <!-- Negative -->
                        <div class="feedback-item">
                            <div class="feedback-info">
                                <span class="feedback-icon negative">
                                    <i class="fas fa-frown"></i>
                                </span>
                                <div>
                                    <span class="feedback-title">
                                        Negative Feedback
                                    </span>
                                    <small>
                                        Needs improvement
                                    </small>
                                </div>
                            </div>
                            <span class="feedback-count negative-count">
                                <?= $negative ?? 0 ?>
                            </span>
                        </div>
                    </div>
                    <!-- Beneficiary Statistics -->
                    <div class="mini-statistics">
                        <div class="mini-stat">
                            <div class="mini-stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <span>Total Beneficiaries</span>
                                <strong>
                                    <?= $totalBeneficiaries ?? 0 ?>
                                </strong>
                            </div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-icon success">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <span>Registered Farmers</span>
                                <strong>
                                    <?= $totalFarmers ?? 0 ?>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- =========================
         REGISTRATION REQUESTS
    ========================== -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card">
                <!-- HEADER -->
                <div class="dashboard-card-header registration-header">
                    <div>
                        <div class="section-icon bg-info-subtle text-info">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div>
                            <h5>Registration Requests</h5>
                            <p>
                                Review and manage farmer registration requests
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <?php if (!empty($notifications)) : ?>
                            <span class="request-count mr-2">
                                <?= count($notifications) ?> Requests
                            </span>
                            <!-- BULK APPROVE BUTTON -->
                            <button type="button" id="bulkApproveBtn"
                            class="btn btn-success btn-sm" disabled>
                                <i class="fas fa-check-double mr-1"></i>
                                Approve Selected
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- TABLE -->
                <div class="dashboard-card-body p-0">
                    <div class="table-responsive">
                        <table id="registration-table" class="table dashboard-table align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <!-- SELECT ALL -->
                                    <th class="checkbox-column">
                                        <input type="checkbox" class="form-check-input m-0" id="selectAllNotifications" title="Select All">
                                    </th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th width="120" class="text-center">
                                        Action
                                    </th>   
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($notifications)) : ?>
                                <?php foreach ($notifications as $notif) : ?>
                                    <tr>
                                        <!-- CHECKBOX -->
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input type="checkbox" class="form-check-input notif-checkbox"
                                                value="<?= h($notif->id) ?>" id="notif-<?= h($notif->id) ?>">
                                            </div>
                                        </td>
                                        <!-- MESSAGE -->
                                        <td>
                                            <div class="request-message">
                                                <div class="message-icon">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <span>
                                                        <?= h($notif->message) ?>
                                                    </span>
                                                    <small>
                                                        Registration request
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- DATE -->
                                        <td>
                                            <span class="date-text">
                                                <i class="far fa-calendar me-1"></i>
                                                <?= $notif->created ? $notif->created->format('M d, Y'): ''?>
                                            </span>
                                            <?php if ($notif->created) : ?>
                                                <small class="d-block text-muted">
                                                    <?= $notif->created->format('h:i A') ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <!-- STATUS -->
                                        <td>
                                            <?php
                                            $badgeClass = 'status-pending';
                                            switch ($notif->status) {
                                                case 'approved':
                                                    $badgeClass = 'status-approved';
                                                    break;
                                                case 'declined':
                                                    $badgeClass = 'status-declined';
                                                    break;
                                                case 'pending':
                                                    $badgeClass = 'status-pending';
                                                    break;
                                            }?>
                                            <span class="status-badge <?= $badgeClass ?>">
                                                <?php if ($notif->status === 'approved') : ?>
                                                    <i class="fas fa-check-circle"></i>
                                                <?php elseif ($notif->status === 'declined') : ?>
                                                    <i class="fas fa-times-circle"></i>
                                                <?php else : ?>
                                                    <i class="fas fa-clock"></i>
                                                <?php endif; ?>
                                                <?= ucfirst($notif->status) ?>
                                            </span>
                                        </td>
                                        <!-- ACTION -->
                                        <td class="text-center">
                                            <?= $this->Html->link(
                                            '<i class="fas fa-eye"></i>',
                                            ['controller' => 'Notifications', 'action' => 'viewRegistration', $notif->id],
                                            [ 'class' => 'view-btn', 'escape' => false, 'title' => 'View Registration' ])?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <h6>
                                                No Registration Requests
                                            </h6>
                                            <p>
                                                There are currently no pending
                                                registration requests.
                                            </p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
const ctx = document.getElementById('effectivenessChart').getContext('2d');

const labels = <?= json_encode($labels) ?>;
const totals = <?= json_encode($totals) ?>;

Chart.register(ChartDataLabels);
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Evaluations',
            data: totals,
            backgroundColor: [
                '#28a745', // Effective
                '#ffc107', // Moderately Effective
                '#dc3545'  // Not Effective
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 13
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        const total = context.dataset.data.reduce(
                            (a, b) => a + b,
                            0
                        );
                        const percentage = ((value / total) * 100).toFixed(1);
                        return context.label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            },
            datalabels: {
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: function(value, context) {
                    const data = context.chart.data.datasets[0].data;
                    const total = data.reduce(
                        (a, b) => a + b,
                        0
                    );
                    const percentage = ((value / total) * 100).toFixed(1);
                    return percentage + '%';
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});
document.addEventListener('DOMContentLoaded', function () {
    const selectAll =
        document.getElementById('selectAllNotifications');
    const checkboxes =
        document.querySelectorAll('.notif-checkbox');
    const bulkApproveBtn =
        document.getElementById('bulkApproveBtn');

    /*
     * Update Approve button
     */
    function updateBulkButton() {
        const selected =
            document.querySelectorAll(
                '.notif-checkbox:checked'
            );
        if (bulkApproveBtn) {
            bulkApproveBtn.disabled =
                selected.length === 0;
            if (selected.length > 0) {
                bulkApproveBtn.innerHTML =
                    '<i class="fas fa-check-double mr-1"></i> ' +
                    'Approve Selected (' +
                    selected.length +
                    ')';
            } else {
                bulkApproveBtn.innerHTML =
                    '<i class="fas fa-check-double mr-1"></i> ' +
                    'Approve Selected';
            }
        }
    }
    /*
     * Select All
     */
    if (selectAll) {
        selectAll.addEventListener(
            'change',
            function () {
                checkboxes.forEach(
                    function (checkbox) {
                        checkbox.checked =
                            selectAll.checked;
                    }
                );
                updateBulkButton();
            }
        );
    }
    /*
     * Individual checkbox
     */
    checkboxes.forEach(
        function (checkbox) {
            checkbox.addEventListener(
                'change',
                function () {

                    const total =
                        checkboxes.length;

                    const selected =
                        document.querySelectorAll(
                            '.notif-checkbox:checked'
                        ).length;
                    /*
                     * Update Select All
                     */
                    if (selectAll) {

                        selectAll.checked =
                            total > 0 &&
                            selected === total;
                        selectAll.indeterminate =
                            selected > 0 &&
                            selected < total;
                    }
                    updateBulkButton();
                }
            );
        }
    );
    /*
     * Bulk Approve
     */
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener(
            'click',
            function () {
                const selected =
                    document.querySelectorAll(
                        '.notif-checkbox:checked'
                    );
                if (selected.length === 0) {
                    alert(
                        'Please select at least one registration request.'
                    );
                    return;
                }
                const ids = [];
                selected.forEach(
                    function (checkbox) {

                        ids.push(
                            checkbox.value
                        );
                    }
                );
                /*
                 * Confirmation
                 */
                const confirmed =
                    confirm(
                        'Are you sure you want to approve ' +
                        ids.length +
                        ' registration request(s)?'
                    );
                if (!confirmed) {
                    return;
                }
                /*
                 * Create form
                 */
                const form =
                    document.createElement('form');
                form.method = 'POST';
                form.action =
                    '<?= $this->Url->build([
                        'controller' => 'Notifications',
                        'action' => 'bulkApprove'
                    ]) ?>';
                /*
                 * CSRF token
                 */
                const csrfToken =
                    document.querySelector(
                        'input[name="_csrfToken"]'
                    );
                if (csrfToken) {
                    const csrf =
                        document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_csrfToken';
                    csrf.value =
                        csrfToken.value;
                    form.appendChild(csrf);
                }
                /*
                 * Add selected IDs
                 */
                ids.forEach(
                    function (id) {
                        const input =
                            document.createElement('input');
                        input.type = 'hidden';
                        input.name =
                            'notification_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    }
                );
                document.body.appendChild(form);
                form.submit();
            }
        );
    }
});

</script>