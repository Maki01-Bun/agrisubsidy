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
        <div class="row g-4">

    <!-- ========================================================
         EFFECTIVENESS EVALUATION
    ========================================================= -->
        <div class="col-xl-8 col-lg-7">
            <div class="dashboard-card h-100">
                <div class="dashboard-card-header">
                    <div class="d-flex align-items-center">
                        <!-- ICON -->
                        <div class="section-icon bg-success-subtle">
                            <i class="fas fa-chart-pie effectiveness-icon"></i>
                        </div>
                        <div>
                            <h5>Effectiveness Evaluation</h5>
                            <p>Overall subsidy program effectiveness</p>
                        </div>
                    </div>
                    <!-- ANALYTICS BADGE -->
                    <?php if (!empty($labels) && !empty($totals)) : ?>
                        <span class="analytics-badge">
                            <i class="fas fa-chart-line me-1"></i>
                            Analytics
                        </span>
                    <?php endif; ?>
                </div>
                <div class="dashboard-card-body">
                    <?php if (!empty($labels) && !empty($totals)) : ?>
                        <div class="chart-wrapper">
                            <canvas id="effectivenessChart"></canvas>
                        </div>
                    <?php else : ?>
                        <div class="evaluation-empty-state">
                            <h5>
                                No Evaluated Subsidy Yet
                            </h5>
                            <p>
                                There are currently no subsidy evaluations
                                available to display.
                            </p>
                            <span class="evaluation-empty-hint">
                                <i class="fas fa-info-circle me-1"></i>
                                Evaluation results will appear here once
                                a subsidy program has been evaluated.
                            </span>
                        </div>
                    <?php endif; ?>
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
    <!-- ============================================================
     REGISTRATION REQUESTS
============================================================ -->

<div class="row mt-4">

    <div class="col-12">

        <div class="dashboard-card registration-card">

            <!-- =================================================
                 HEADER
            ================================================== -->

            <div class="dashboard-card-header registration-header">

                <!-- LEFT -->
                <div class="registration-header-left">

                    <div class="section-icon registration-section-icon">

                        <i class="fas fa-user-clock registration-icon"></i>

                    </div>

                    <div>

                        <h5>
                            Registration Requests
                        </h5>

                        <p>
                            Review and manage farmer registration requests
                        </p>

                    </div>

                </div>


                <!-- RIGHT -->
                <?php if (!empty($notifications)) : ?>

                    <div class="registration-header-actions">

                        <span class="request-count">

                            <i class="fas fa-user-clock mr-1"></i>

                            <?= count($notifications) ?> Requests

                        </span>


                        <button
                            type="button"
                            id="bulkApproveBtn"
                            class="btn btn-success btn-sm"
                            disabled
                        >

                            <i class="fas fa-check-double mr-1"></i>

                            Approve Selected

                        </button>

                    </div>

                <?php endif; ?>

            </div>


            <!-- =================================================
                 TABLE
            ================================================== -->

            <div class="dashboard-card-body p-0">

                <div class="table-responsive">

                    <table
                        id="registration-table"
                        class="table dashboard-table registration-table align-middle mb-0 w-100"
                    >

                        <!-- =================================================
                             TABLE HEADER
                        ================================================== -->

                        <thead>

                            <tr>

                                <!-- SELECT ALL -->

                                <th
                                    class="checkbox-column text-center"
                                    width="55"
                                >

                                    <div class="checkbox-wrapper">

                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="selectAllNotifications"
                                            title="Select All"
                                        >

                                    </div>

                                </th>


                                <!-- MESSAGE -->

                                <th>
                                    MESSAGE
                                </th>


                                <!-- DATE -->

                                <th>
                                    DATE
                                </th>


                                <!-- STATUS -->

                                <th>
                                    STATUS
                                </th>


                                <!-- ACTION -->

                                <th
                                    width="100"
                                    class="text-center"
                                >
                                    ACTION
                                </th>

                            </tr>

                        </thead>


                        <!-- =================================================
                             TABLE BODY
                        ================================================== -->

                        <tbody>

                            <?php if (!empty($notifications)) : ?>

                                <?php foreach ($notifications as $notif) : ?>

                                    <tr>

                                        <!-- =================================
                                             CHECKBOX
                                        ================================== -->

                                        <td
                                            class="checkbox-column text-center"
                                        >

                                            <div class="checkbox-wrapper">

                                                <input
                                                    type="checkbox"
                                                    class="form-check-input notif-checkbox"
                                                    value="<?= h($notif->id) ?>"
                                                    id="notif-<?= h($notif->id) ?>"
                                                >

                                            </div>

                                        </td>


                                        <!-- =================================
                                             MESSAGE
                                        ================================== -->

                                        <td>

                                            <div class="request-message">

                                                <div class="message-icon">

                                                    <i class="fas fa-user"></i>

                                                </div>


                                                <div class="request-message-content">

                                                    <span>

                                                        <?= h($notif->message) ?>

                                                    </span>

                                                    <small>

                                                        Registration request

                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- =================================
                                             DATE
                                        ================================== -->

                                        <td>

                                            <?php if ($notif->created) : ?>

                                                <span class="date-text">

                                                    <i class="far fa-calendar mr-1"></i>

                                                    <?= $notif->created->format('M d, Y') ?>

                                                </span>

                                                <small class="d-block text-muted">

                                                    <?= $notif->created->format('h:i A') ?>

                                                </small>

                                            <?php else : ?>

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- =================================
                                             STATUS
                                        ================================== -->

                                        <td>

                                            <?php

                                            $badgeClass = 'status-pending';

                                            switch ($notif->status) {

                                                case 'approved':

                                                    $badgeClass =
                                                        'status-approved';

                                                    break;

                                                case 'declined':

                                                    $badgeClass =
                                                        'status-declined';

                                                    break;

                                                case 'pending':

                                                default:

                                                    $badgeClass =
                                                        'status-pending';

                                                    break;

                                            }

                                            ?>


                                            <span
                                                class="status-badge <?= $badgeClass ?>"
                                            >

                                                <?php if (
                                                    $notif->status === 'approved'
                                                ) : ?>

                                                    <i class="fas fa-check-circle"></i>

                                                <?php elseif (
                                                    $notif->status === 'declined'
                                                ) : ?>

                                                    <i class="fas fa-times-circle"></i>

                                                <?php else : ?>

                                                    <i class="fas fa-clock"></i>

                                                <?php endif; ?>


                                                <?= ucfirst(h($notif->status)) ?>

                                            </span>

                                        </td>


                                        <!-- =================================
                                             ACTION
                                        ================================== -->

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="view-btn view-registration-btn"
                                                data-id="<?= h($notif->id) ?>"
                                                data-toggle="modal"
                                                data-target="#registrationDetailsModal"
                                                title="View Registration"
                                            >

                                                <i class="fas fa-eye"></i>

                                            </button>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>


                            <?php else : ?>

                                <!-- =====================================
                                     EMPTY STATE
                                ====================================== -->

                                <tr>

                                    <td
                                        colspan="5"
                                        class="registration-empty-cell"
                                    >

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


<!-- ============================================================
     REGISTRATION DETAILS MODAL
============================================================ -->

<div
    class="modal fade"
    id="registrationDetailsModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="registrationDetailsModalLabel"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-xl modal-dialog-centered"
        role="document"
    >

        <div class="modal-content registration-modal">


            <!-- =================================================
                 MODAL HEADER
            ================================================== -->

            <div class="modal-header registration-modal-header">

                <div class="registration-modal-title-wrapper">

                    <div class="registration-modal-icon">

                        <i class="fas fa-user-clock"></i>

                    </div>


                    <div>

                        <h5
                            class="modal-title"
                            id="registrationDetailsModalLabel"
                        >
                            Registration Details
                        </h5>

                        <small>
                            Review farmer registration information
                        </small>

                    </div>

                </div>


                <button
                    type="button"
                    class="close registration-close"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <!-- =================================================
                 MODAL BODY
            ================================================== -->

            <div class="modal-body registration-modal-body">


                <!-- =============================================
                     LOADING
                ============================================== -->

                <div
                    id="registrationLoading"
                    class="registration-loading"
                >

                    <div
                        class="spinner-border text-success"
                        role="status"
                    >

                        <span class="sr-only">
                            Loading...
                        </span>

                    </div>


                    <p>
                        Loading registration details...
                    </p>

                </div>


                <!-- =============================================
                     CONTENT
                ============================================== -->

                <div
                    id="registrationModalContent"
                    style="display:none;"
                >


                    <!-- =========================================
                         DUPLICATE FARMER
                    ========================================== -->

                    <div
                        id="duplicateFarmerAlert"
                        class="registration-alert registration-alert-warning"
                        style="display:none;"
                    >

                        <div class="registration-alert-icon">

                            <i class="fas fa-user-check"></i>

                        </div>


                        <div>

                            <h6>
                                Farmer Information Already Exists
                            </h6>


                            <p>
                                This farmer is already listed in the system.
                            </p>


                            <div class="farmer-number-display">

                                <strong>
                                    Farmer Number:
                                </strong>

                                <span id="existingFarmerNumber">
                                    —
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- =========================================
                         NO DUPLICATE
                    ========================================== -->

                    <div
                        id="noDuplicateFarmerAlert"
                        class="registration-alert registration-alert-success"
                        style="display:none;"
                    >

                        <div class="registration-alert-icon">

                            <i class="fas fa-check-circle"></i>

                        </div>


                        <div>

                            <h6>
                                No Duplicate Farmer Found
                            </h6>


                            <p>
                                A new farmer record will be created
                                after approval.
                            </p>

                        </div>

                    </div>


                    <!-- =========================================
                         USER INFORMATION
                    ========================================== -->

                    <div class="registration-section">

                        <div class="registration-section-title">

                            <div class="registration-section-icon">

                                <i class="fas fa-user"></i>

                            </div>


                            <div>

                                <h6>
                                    User Information
                                </h6>

                                <small>
                                    Account information
                                </small>

                            </div>

                        </div>


                        <div class="registration-info-grid">


                            <div class="registration-info-item">

                                <span class="registration-label">
                                    Username
                                </span>

                                <span
                                    class="registration-value"
                                    id="registrationUsername"
                                >
                                    —
                                </span>

                            </div>


                            <div class="registration-info-item">

                                <span class="registration-label">
                                    Role
                                </span>

                                <span
                                    class="registration-value"
                                    id="registrationRole"
                                >
                                    —
                                </span>

                            </div>


                        </div>

                    </div>


                    <!-- =========================================
                         FARMER INFORMATION
                    ========================================== -->

                    <div class="registration-section">

                        <div class="registration-section-title">

                            <div class="registration-section-icon">

                                <i class="fas fa-id-card"></i>

                            </div>


                            <div>

                                <h6>
                                    Farmer Information
                                </h6>

                                <small>
                                    Submitted farmer details
                                </small>

                            </div>

                        </div>


                        <div
                            id="farmerInformationGrid"
                            class="registration-info-grid"
                        >
                        </div>

                    </div>


                </div>


                <!-- =============================================
                     ERROR
                ============================================== -->

                <div
                    id="registrationModalError"
                    class="registration-error"
                    style="display:none;"
                >

                    <div class="registration-error-icon">

                        <i class="fas fa-exclamation-triangle"></i>

                    </div>


                    <h5>
                        Unable to Load Registration
                    </h5>


                    <p id="registrationErrorMessage">
                        Something went wrong while loading
                        the registration details.
                    </p>


                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-dismiss="modal"
                    >

                        Close

                    </button>

                </div>


            </div>


            <!-- =================================================
                 MODAL FOOTER
            ================================================== -->

            <div
                class="modal-footer registration-modal-footer"
                id="registrationModalFooter"
                style="display:none;"
            >

                <button
                    type="button"
                    class="btn btn-light"
                    data-dismiss="modal"
                >

                    <i class="fas fa-times mr-1"></i>

                    Close

                </button>


                <button
                    type="button"
                    id="modalDeclineBtn"
                    class="btn btn-danger"
                >

                    <i class="fas fa-times mr-1"></i>

                    Decline Registration

                </button>


                <button
                    type="button"
                    id="modalApproveBtn"
                    class="btn btn-success"
                >

                    <i class="fas fa-check mr-1"></i>

                    Approve Registration

                </button>

            </div>


        </div>

    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       EFFECTIVENESS EVALUATION CHART
    ========================================================= */

    const labels = <?= json_encode($labels ?? []) ?>;
    const totals = <?= json_encode($totals ?? []) ?>;

    const effectivenessCanvas =
        document.getElementById('effectivenessChart');

    /*
     * Only create chart when:
     * 1. Canvas exists
     * 2. Evaluation data exists
     */
    if (
        effectivenessCanvas &&
        labels.length > 0 &&
        totals.length > 0
    ) {

        const ctx =
            effectivenessCanvas.getContext('2d');

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

                    /* ==========================
                       LEGEND
                    ========================== */

                    legend: {

                        position: 'bottom',

                        labels: {
                            font: {
                                size: 13
                            },

                            padding: 15
                        }
                    },


                    /* ==========================
                       TOOLTIP
                    ========================== */

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                const value =
                                    Number(context.raw) || 0;

                                const data =
                                    context.dataset.data;

                                const total =
                                    data.reduce(
                                        (a, b) =>
                                            Number(a) +
                                            Number(b),
                                        0
                                    );

                                const percentage =
                                    total > 0
                                        ? (
                                            (value / total) *
                                            100
                                        ).toFixed(1)
                                        : 0;

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


                    /* ==========================
                       DATA LABELS
                    ========================== */

                    datalabels: {

                        color: '#fff',

                        font: {
                            weight: 'bold',
                            size: 14
                        },

                        formatter: function (
                            value,
                            context
                        ) {

                            const data =
                                context.chart
                                    .data
                                    .datasets[0]
                                    .data;

                            const total =
                                data.reduce(
                                    (a, b) =>
                                        Number(a) +
                                        Number(b),
                                    0
                                );

                            if (total <= 0) {
                                return '';
                            }

                            const percentage =
                                (
                                    (value / total) *
                                    100
                                ).toFixed(1);

                            return percentage + '%';
                        }

                    }

                }

            },

            plugins: [
                ChartDataLabels
            ]

        });
    }


    /* =========================================================
       REGISTRATION REQUESTS
    ========================================================= */

    const selectAll =
        document.getElementById(
            'selectAllNotifications'
        );


    const checkboxes =
        document.querySelectorAll(
            '.notif-checkbox'
        );


    const bulkApproveBtn =
        document.getElementById(
            'bulkApproveBtn'
        );


    /* =========================================================
       REGISTRATION MODAL ELEMENTS
    ========================================================= */

    const loading =
        document.getElementById(
            'registrationLoading'
        );


    const modalContent =
        document.getElementById(
            'registrationModalContent'
        );


    const errorBox =
        document.getElementById(
            'registrationModalError'
        );


    const errorMessage =
        document.getElementById(
            'registrationErrorMessage'
        );


    const modalFooter =
        document.getElementById(
            'registrationModalFooter'
        );


    const username =
        document.getElementById(
            'registrationUsername'
        );


    const role =
        document.getElementById(
            'registrationRole'
        );


    const farmerGrid =
        document.getElementById(
            'farmerInformationGrid'
        );


    const duplicateAlert =
        document.getElementById(
            'duplicateFarmerAlert'
        );


    const noDuplicateAlert =
        document.getElementById(
            'noDuplicateFarmerAlert'
        );


    const existingFarmerNumber =
        document.getElementById(
            'existingFarmerNumber'
        );


    const approveBtn =
        document.getElementById(
            'modalApproveBtn'
        );


    const declineBtn =
        document.getElementById(
            'modalDeclineBtn'
        );


    let currentNotificationId = null;


    /* =========================================================
       UPDATE BULK APPROVE BUTTON
    ========================================================= */

    function updateBulkButton() {

        const selected =
            document.querySelectorAll(
                '.notif-checkbox:checked'
            );


        if (!bulkApproveBtn) {
            return;
        }


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


    /* =========================================================
       SELECT ALL CHECKBOX
    ========================================================= */

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


                selectAll.indeterminate =
                    false;


                updateBulkButton();

            }
        );

    }


    /* =========================================================
       INDIVIDUAL CHECKBOXES
    ========================================================= */

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


                    if (selectAll) {

                        /*
                         * All selected
                         */

                        selectAll.checked =
                            total > 0 &&
                            selected === total;


                        /*
                         * Some selected
                         */

                        selectAll.indeterminate =
                            selected > 0 &&
                            selected < total;

                    }


                    updateBulkButton();

                }
            );

        }
    );


    /* =========================================================
       BULK APPROVE
    ========================================================= */

    if (bulkApproveBtn) {

        bulkApproveBtn.addEventListener(
            'click',
            function () {

                const selected =
                    document.querySelectorAll(
                        '.notif-checkbox:checked'
                    );


                /* ==========================
                   NO SELECTION
                ========================== */

                if (selected.length === 0) {

                    alert(
                        'Please select at least one registration request.'
                    );

                    return;
                }


                /* ==========================
                   GET SELECTED IDS
                ========================== */

                const ids = [];


                selected.forEach(
                    function (checkbox) {

                        ids.push(
                            checkbox.value
                        );

                    }
                );


                /* ==========================
                   CONFIRMATION
                ========================== */

                const confirmed =
                    confirm(
                        'Are you sure you want to approve ' +
                        ids.length +
                        ' registration request(s)?'
                    );


                if (!confirmed) {
                    return;
                }


                /* ==========================
                   CREATE FORM
                ========================== */

                const form =
                    document.createElement(
                        'form'
                    );


                form.method =
                    'POST';


                form.action =
                    '<?= $this->Url->build([
                        'controller' => 'Notifications',
                        'action' => 'bulkApprove'
                    ]) ?>';


                /* ==========================
                   CSRF TOKEN
                ========================== */

                const csrfToken =
                    document.querySelector(
                        'input[name="_csrfToken"]'
                    );


                if (csrfToken) {

                    const csrf =
                        document.createElement(
                            'input'
                        );


                    csrf.type =
                        'hidden';


                    csrf.name =
                        '_csrfToken';


                    csrf.value =
                        csrfToken.value;


                    form.appendChild(
                        csrf
                    );

                }


                /* ==========================
                   ADD SELECTED IDS
                ========================== */

                ids.forEach(
                    function (id) {

                        const input =
                            document.createElement(
                                'input'
                            );


                        input.type =
                            'hidden';


                        input.name =
                            'notification_ids[]';


                        input.value =
                            id;


                        form.appendChild(
                            input
                        );

                    }
                );


                /* ==========================
                   SUBMIT
                ========================== */

                document.body.appendChild(
                    form
                );


                form.submit();

            }
        );

    }


    /* =========================================================
       VIEW REGISTRATION
    ========================================================= */

    document.querySelectorAll(
        '.view-registration-btn'
    ).forEach(
        function (button) {

            button.addEventListener(
                'click',
                function () {

                    currentNotificationId =
                        this.getAttribute(
                            'data-id'
                        );


                    loadRegistrationDetails(
                        currentNotificationId
                    );

                }
            );

        }
    );


    /* =========================================================
       LOAD REGISTRATION DETAILS
    ========================================================= */

    function loadRegistrationDetails(id) {

        /* ==========================
           RESET MODAL
        ========================== */

        if (loading) {
            loading.style.display = 'flex';
        }


        if (modalContent) {
            modalContent.style.display = 'none';
        }


        if (errorBox) {
            errorBox.style.display = 'none';
        }


        if (modalFooter) {
            modalFooter.style.display = 'none';
        }


        if (duplicateAlert) {
            duplicateAlert.style.display = 'none';
        }


        if (noDuplicateAlert) {
            noDuplicateAlert.style.display = 'none';
        }


        if (farmerGrid) {
            farmerGrid.innerHTML = '';
        }


        if (username) {
            username.textContent = '—';
        }


        if (role) {
            role.textContent = '—';
        }


        if (existingFarmerNumber) {
            existingFarmerNumber.textContent = '—';
        }


        /* ==========================
           URL
        ========================== */

        const url =
            '<?= $this->Url->build([
                'controller' => 'Notifications',
                'action' => 'getRegistrationDetails'
            ]) ?>/' +
            encodeURIComponent(id);


        /* ==========================
           FETCH
        ========================== */

        fetch(
            url,
            {
                method: 'GET',

                headers: {
                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'
                }
            }
        )

        .then(
            function (response) {

                if (!response.ok) {

                    throw new Error(
                        'Server error: ' +
                        response.status
                    );

                }


                return response.json();

            }
        )

        .then(
            function (result) {

                if (
                    !result ||
                    result.success !== true
                ) {

                    throw new Error(
                        result.message ||
                        'Registration details not found.'
                    );

                }


                /* ==========================
                   USER INFORMATION
                ========================== */

                const user =
                    result.data &&
                    result.data.user
                        ? result.data.user
                        : {};


                if (username) {

                    username.textContent =
                        user.username || '—';

                }


                if (role) {

                    role.textContent =
                        user.role || '—';

                }


                /* ==========================
                   DUPLICATE FARMER
                ========================== */

                if (
                    result.existingFarmer
                ) {

                    if (duplicateAlert) {

                        duplicateAlert.style.display =
                            'flex';

                    }


                    if (existingFarmerNumber) {

                        existingFarmerNumber.textContent =
                            result.existingFarmer.farmer_no ||
                            '—';

                    }

                } else {

                    if (noDuplicateAlert) {

                        noDuplicateAlert.style.display =
                            'flex';

                    }

                }


                /* ==========================
                   FARMER INFORMATION
                ========================== */

                const farmer =
                    result.data &&
                    result.data.farmer
                        ? result.data.farmer
                        : {};


                if (farmerGrid) {

                    Object.keys(farmer).forEach(
                        function (field) {

                            const item =
                                document.createElement(
                                    'div'
                                );


                            item.className =
                                'registration-info-item';


                            const label =
                                document.createElement(
                                    'span'
                                );


                            label.className =
                                'registration-label';


                            label.textContent =
                                formatFieldName(
                                    field
                                );


                            const value =
                                document.createElement(
                                    'span'
                                );


                            value.className =
                                'registration-value';


                            const fieldValue =
                                farmer[field];


                            value.textContent =
                                fieldValue !== null &&
                                fieldValue !== undefined &&
                                fieldValue !== ''
                                    ? fieldValue
                                    : '—';


                            item.appendChild(
                                label
                            );


                            item.appendChild(
                                value
                            );


                            farmerGrid.appendChild(
                                item
                            );

                        }
                    );

                }


                /* ==========================
                   SHOW CONTENT
                ========================== */

                if (loading) {
                    loading.style.display = 'none';
                }


                if (modalContent) {
                    modalContent.style.display = 'block';
                }


                /* ==========================
                   ACTION BUTTONS
                ========================== */

                if (
                    result.status &&
                    result.status.toLowerCase() ===
                    'pending'
                ) {

                    if (modalFooter) {
                        modalFooter.style.display =
                            'flex';
                    }


                    if (approveBtn) {
                        approveBtn.style.display =
                            'inline-block';
                    }


                    if (declineBtn) {
                        declineBtn.style.display =
                            'inline-block';
                    }

                } else {

                    if (modalFooter) {
                        modalFooter.style.display =
                            'flex';
                    }


                    if (approveBtn) {
                        approveBtn.style.display =
                            'none';
                    }


                    if (declineBtn) {
                        declineBtn.style.display =
                            'none';
                    }

                }

            }
        )

        .catch(
            function (error) {

                console.error(
                    'Registration Details Error:',
                    error
                );


                if (loading) {
                    loading.style.display = 'none';
                }


                if (errorBox) {
                    errorBox.style.display = 'flex';
                }


                if (errorMessage) {

                    errorMessage.textContent =
                        error.message ||
                        'Unable to load registration details.';

                }

            }
        );

    }


    /* =========================================================
       FORMAT FIELD NAME
    ========================================================= */

    function formatFieldName(field) {

        return field

            .replace(
                /_/g,
                ' '
            )

            .replace(
                /\b\w/g,
                function (letter) {

                    return letter.toUpperCase();

                }
            );

    }


    /* =========================================================
       APPROVE REGISTRATION
    ========================================================= */

    if (approveBtn) {

        approveBtn.addEventListener(
            'click',
            function () {

                if (!currentNotificationId) {
                    return;
                }


                if (
                    !confirm(
                        'Are you sure you want to approve this registration?'
                    )
                ) {

                    return;
                }


                submitRegistrationAction(
                    'approveRegistration',
                    currentNotificationId
                );

            }
        );

    }


    /* =========================================================
       DECLINE REGISTRATION
    ========================================================= */

    if (declineBtn) {

        declineBtn.addEventListener(
            'click',
            function () {

                if (!currentNotificationId) {
                    return;
                }


                if (
                    !confirm(
                        'Are you sure you want to decline this registration?'
                    )
                ) {

                    return;
                }


                submitRegistrationAction(
                    'declineRegistration',
                    currentNotificationId
                );

            }
        );

    }


    /* =========================================================
       APPROVE / DECLINE FORM
    ========================================================= */

    function submitRegistrationAction(
        action,
        id
    ) {

        const form =
            document.createElement(
                'form'
            );


        form.method =
            'POST';


        form.action =
            '<?= $this->Url->build([
                'controller' => 'Notifications'
            ]) ?>/' +
            action +
            '/' +
            encodeURIComponent(id);


        /* ==========================
           CSRF
        ========================== */

        const csrfToken =
            document.querySelector(
                'input[name="_csrfToken"]'
            );


        if (csrfToken) {

            const csrf =
                document.createElement(
                    'input'
                );


            csrf.type =
                'hidden';


            csrf.name =
                '_csrfToken';


            csrf.value =
                csrfToken.value;


            form.appendChild(
                csrf
            );

        }


        document.body.appendChild(
            form
        );


        form.submit();

    }


    /* =========================================================
       RESET MODAL WHEN CLOSED
    ========================================================= */

    $('#registrationDetailsModal').on(
        'hidden.bs.modal',
        function () {

            currentNotificationId =
                null;


            if (loading) {
                loading.style.display = 'flex';
            }


            if (modalContent) {
                modalContent.style.display = 'none';
            }


            if (errorBox) {
                errorBox.style.display = 'none';
            }


            if (modalFooter) {
                modalFooter.style.display = 'none';
            }


            if (duplicateAlert) {
                duplicateAlert.style.display = 'none';
            }


            if (noDuplicateAlert) {
                noDuplicateAlert.style.display = 'none';
            }


            if (farmerGrid) {
                farmerGrid.innerHTML = '';
            }

        }
    );


    /* =========================================================
       INITIAL STATE
    ========================================================= */

    updateBulkButton();

});
</script>