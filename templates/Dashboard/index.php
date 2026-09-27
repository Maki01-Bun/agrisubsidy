<div class="container-fluid px-0">

    <!-- ============================================================
         STATISTICS
    ============================================================= -->

    <div class="row g-4 mb-4">

        <!-- Subsidy Distributed -->
        <div class="col-xl-4 col-md-6">

            <div class="dashboard-stat-card stat-success">

                <div class="stat-icon">
                    <i class="fas fa-seedling"></i>
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Subsidy Distributed
                    </span>

                    <h2>
                        <?= h($subsidyDistributed ?? 0) ?>
                    </h2>

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

                    <span class="stat-label">
                        Re-Scheduled
                    </span>

                    <h2>
                        <?= h($rescheduled ?? 0) ?>
                    </h2>

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

                    <span class="stat-label">
                        Cancelled
                    </span>

                    <h2>
                        <?= h($cancelled ?? 0) ?>
                    </h2>

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


    <!-- ============================================================
         ANALYTICS
    ============================================================= -->

    <div class="row g-4">

        <!-- ========================================================
             EFFECTIVENESS EVALUATION
        ========================================================= -->

        <div class="col-xl-8 col-lg-7">

            <div class="dashboard-card h-100">

                <div class="dashboard-card-header">

                    <div class="d-flex align-items-center">

                        <div class="section-icon bg-success-subtle">

                            <i class="fas fa-chart-pie effectiveness-icon"></i>

                        </div>

                        <div>

                            <h5>
                                Effectiveness Evaluation
                            </h5>

                            <p>
                                Overall seed subsidy program effectiveness
                            </p>

                        </div>

                    </div>

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


        <!-- ========================================================
             FEEDBACK SUMMARY
        ========================================================= -->

        <div class="col-xl-4 col-lg-5">

            <div class="dashboard-card h-100">

                <div class="dashboard-card-header">

                    <div>

                        <div class="section-icon bg-warning-subtle text-warning">

                            <i class="fas fa-star"></i>

                        </div>

                        <div>

                            <h5>
                                Feedback Summary
                            </h5>

                            <p>
                                Farmer satisfaction overview
                            </p>

                        </div>

                    </div>

                </div>


                <div class="dashboard-card-body">

                    <div class="mini-statistics">
                
                        <div class="mini-stat">
                
                            <div class="mini-stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                
                            <div>
                                <span>
                                    Total Beneficiaries
                                </span>
                
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
                                <span>
                                    Registered Farmers
                                </span>
                
                                <strong>
                                    <?= $totalFarmers ?? 0 ?>
                                </strong>
                            </div>
                
                        </div>
                
                    </div>

                    <!-- Average Rating -->

                    <div class="rating-box mt-3">

                        <div class="rating-circle">

                            <i class="fas fa-star"></i>

                        </div>

                        <div>

                            <span class="rating-label">
                                Average Rating
                            </span>

                            <div class="rating-value">

                                <?= number_format($avgRating ?? 0, 1) ?>

                                <small>
                                    / 5
                                </small>

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


                    <!-- Feedback -->

                    <div class="feedback-list">

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

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         REGISTRATION REQUESTS
    ============================================================= -->

    <div class="row mt-4">

        <div class="col-12">

            <div class="dashboard-card registration-card">


                <!-- =================================================
                     HEADER
                ================================================== -->

                <div class="dashboard-card-header registration-header">

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


                            <tbody>

                                <?php if (!empty($notifications)) : ?>

                                    <?php foreach ($notifications as $notif) : ?>

                                        <tr>

                                            <!-- CHECKBOX -->

                                            <td class="checkbox-column text-center">

                                                <div class="checkbox-wrapper">

                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input notif-checkbox"
                                                        value="<?= h($notif->id) ?>"
                                                        id="notif-<?= h($notif->id) ?>"
                                                        <?= in_array(
                                                            strtolower((string)$notif->status),
                                                            ['approved', 'declined'],
                                                            true
                                                        ) ? 'disabled' : '' ?>
                                                    >

                                                </div>

                                            </td>


                                            <!-- MESSAGE -->

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


                                            <!-- DATE -->

                                            <td>

                                                <?php if ($notif->created) : ?>

                                                    <?php
                                                    /*
                                                     * Notifications.created is a database timestamp.
                                                     *
                                                     * Use the Unix timestamp as the absolute moment.
                                                     * Then convert that moment to Philippine time for
                                                     * the exact date/time displayed below.
                                                     *
                                                     * This fixes the 8-hour difference visible when the
                                                     * database/CakePHP value is UTC but the application
                                                     * is being viewed in the Philippines.
                                                     */
                                                    $createdTimestamp = $notif->created->getTimestamp();

                                                    $createdPhilippine =
                                                        (new \DateTimeImmutable('@' . $createdTimestamp))
                                                            ->setTimezone(
                                                                new \DateTimeZone('Asia/Manila')
                                                            );
                                                    ?>

                                                    <span
                                                        class="date-text realtime-registration-time"
                                                        data-created="<?= h($createdTimestamp) ?>"
                                                    >

                                                        <i class="far fa-clock mr-1"></i>

                                                        <span class="relative-time">
                                                            Just now
                                                        </span>

                                                    </span>

                                                    <small class="d-block text-muted exact-registration-time">

                                                        <?= h($createdPhilippine->format('M d, Y h:i A')) ?>

                                                    </small>

                                                <?php else : ?>

                                                    <span class="text-muted">
                                                        —
                                                    </span>

                                                <?php endif; ?>

                                            </td>


                                            <!-- STATUS -->

                                            <td>

                                                <?php

                                                $badgeClass =
                                                    'status-pending';

                                                switch (
                                                    strtolower(
                                                        (string)$notif->status
                                                    )
                                                ) {

                                                    case 'approved':

                                                        $badgeClass =
                                                            'status-approved';

                                                        break;

                                                    case 'declined':

                                                        $badgeClass =
                                                            'status-declined';

                                                        break;

                                                    default:

                                                        $badgeClass =
                                                            'status-pending';

                                                        break;

                                                }

                                                ?>

                                                <span
                                                    class="status-badge <?= h($badgeClass) ?>"
                                                >

                                                    <?php if (
                                                        strtolower(
                                                            (string)$notif->status
                                                        ) === 'approved'
                                                    ) : ?>

                                                        <i class="fas fa-check-circle"></i>

                                                    <?php elseif (
                                                        strtolower(
                                                            (string)$notif->status
                                                        ) === 'declined'
                                                    ) : ?>

                                                        <i class="fas fa-times-circle"></i>

                                                    <?php else : ?>

                                                        <i class="fas fa-clock"></i>

                                                    <?php endif; ?>


                                                    <?= ucfirst(
                                                        h(
                                                            $notif->status ?: 'pending'
                                                        )
                                                    ) ?>

                                                </span>

                                            </td>


                                            <!-- =================================================
                                                 VIEW
                                                 DO NOT REMOVE
                                            ================================================== -->

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
        class="modal-dialog modal-lg modal-dialog-centered"
        role="document"
    >

        <div class="modal-content registration-modal">

            <!-- HEADER -->

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


            <!-- BODY -->

            <div class="modal-body registration-modal-body">

                <!-- LOADING -->

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


                <!-- CONTENT -->

                <div
                    id="registrationModalContent"
                    style="display:none;"
                >

                    <!-- DUPLICATE FARMER -->

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
                                    LGU-RSBSA Number:
                                </strong>

                                <span id="existingFarmerNumber">
                                    —
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- NO DUPLICATE -->

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


                    <!-- USER INFORMATION -->

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
                    <!-- FARMER INFORMATION -->

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


                <!-- ERROR -->

                <div
                    id="registrationModalError"
                    class="registration-error"
                    style="display:none;">

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


            <!-- FOOTER -->

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


                <?= $this->Form->create(null, [
                    'id' => 'declineRegistrationForm',
                    'url' => '#',
                    'style' => 'display:inline; margin:0;'
                ]) ?>

                    <button
                        type="button"
                        id="modalDeclineBtn"
                        class="btn btn-danger"
                    >
                        <i class="fas fa-times mr-1"></i>
                        Decline Registration
                    </button>

                <?= $this->Form->end() ?>


                <?= $this->Form->create(null, [
                    'id' => 'approveRegistrationForm',
                    'url' => '#',
                    'style' => 'display:inline; margin:0;'
                ]) ?>

                    <button
                        type="button"
                        id="modalApproveBtn"
                        class="btn btn-success"
                    >
                        <i class="fas fa-check mr-1"></i>
                        Approve Registration
                    </button>

                <?= $this->Form->end() ?>

            </div>

        </div>

    </div>

</div>

    <!-- ============================================================
         APPROVE / DECLINE CONFIRMATION MODAL
    ============================================================= -->

    <div
        class="modal fade"
        id="registrationConfirmModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="registrationConfirmModalLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >

            <div class="modal-content">


                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="registrationConfirmModalLabel"
                    >
                        Confirm Action
                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <div class="modal-body text-center py-4">

                    <div
                        id="registrationConfirmIcon"
                        class="mb-3"
                    >

                        <i
                            class="fas fa-question-circle text-primary"
                            style="font-size:65px;"
                        ></i>

                    </div>


                    <h4
                        id="registrationConfirmTitle"
                        class="mb-2"
                    >
                        Confirm Action
                    </h4>


                    <p
                        id="registrationConfirmMessage"
                        class="text-muted mb-0"
                    >
                        Are you sure you want to continue?
                    </p>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        id="cancelRegistrationActionBtn"
                    >

                        <i class="fas fa-times mr-1"></i>

                        Cancel

                    </button>


                    <button
                        type="button"
                        id="confirmRegistrationActionBtn"
                        class="btn btn-primary"
                    >

                        Continue

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         REGISTRATION RESULT MODAL
    ============================================================= -->

    <div
        class="modal fade"
        id="registrationResultModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="registrationResultModalLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >

            <div class="modal-content">


                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="registrationResultModalLabel"
                    >
                        Registration Status
                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <div class="modal-body text-center py-4">

                    <div
                        id="registrationResultIcon"
                        class="mb-3"
                    >

                        <i
                            class="fas fa-check-circle text-success"
                            style="font-size:65px;"
                        ></i>

                    </div>


                    <h4
                        id="registrationResultTitle"
                        class="mb-2"
                    >
                        Registration Approved
                    </h4>


                    <p
                        id="registrationResultMessage"
                        class="text-muted mb-0"
                    >
                        The farmer registration has been approved successfully.
                    </p>

                </div>


                <div class="modal-footer justify-content-center">

                    <button
                        type="button"
                        class="btn btn-primary px-4"
                        data-dismiss="modal"
                    >

                        <i class="fas fa-check mr-1"></i>

                        OK

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         BULK APPROVE CONFIRMATION MODAL
    ============================================================= -->

    <div
        class="modal fade"
        id="bulkApproveConfirmModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="bulkApproveConfirmModalLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >

            <div class="modal-content">


                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="bulkApproveConfirmModalLabel"
                    >
                        Confirm Bulk Approval
                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <div class="modal-body text-center py-4">

                    <div class="mb-3">

                        <i
                            class="fas fa-users-cog text-success"
                            style="font-size:65px;"
                        ></i>

                    </div>


                    <h4 class="mb-2">
                        Approve Selected Registrations?
                    </h4>


                    <p class="text-muted mb-0">

                        You are about to approve

                        <strong
                            id="bulkApproveCount"
                            class="text-success"
                        >
                            0
                        </strong>

                        selected registration(s).

                    </p>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >

                        <i class="fas fa-times mr-1"></i>

                        Cancel

                    </button>


                    <button
                        type="button"
                        id="confirmBulkApproveBtn"
                        class="btn btn-success"
                    >

                        <i class="fas fa-check-double mr-1"></i>

                        Approve Selected

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         BULK APPROVE RESULT MODAL
    ============================================================= -->

    <div
        class="modal fade"
        id="bulkApproveResultModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="bulkApproveResultModalLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog modal-dialog-centered"
            role="document"
        >

            <div class="modal-content">


                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="bulkApproveResultModalLabel"
                    >
                        Bulk Approval
                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <div class="modal-body text-center py-4">

                    <div
                        id="bulkResultIcon"
                        class="mb-3"
                    >

                        <i
                            class="fas fa-check-circle text-success"
                            style="font-size:65px;"
                        ></i>

                    </div>


                    <h4 id="bulkResultTitle">
                        Bulk Approval Successful
                    </h4>


                    <p
                        id="bulkResultMessage"
                        class="text-muted mb-0"
                    >
                        The selected farmer registrations have been approved successfully.
                    </p>

                </div>


                <div class="modal-footer justify-content-center">

                    <button
                        type="button"
                        class="btn btn-success px-4"
                        data-dismiss="modal"
                    >

                        <i class="fas fa-check mr-1"></i>

                        OK

                    </button>

                </div>

            </div>

        </div>

    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

<!-- ================================================================
     JAVASCRIPT
================================================================ -->
<script>
$(document).ready(function () {

    'use strict';


    /* ============================================================
       EFFECTIVENESS EVALUATION CHART
       ============================================================ */

    const labels = <?= json_encode($labels ?? []) ?>;
    const totals = <?= json_encode($totals ?? []) ?>;

    const effectivenessCanvas =
        document.getElementById('effectivenessChart');


    /* ============================================================
       EFFECTIVENESS CHART COLOR FUNCTION
       ============================================================ */

    function getEffectivenessColor(label) {

        const normalized =
            String(label || '')
                .trim()
                .toLowerCase();

        if (
            normalized === 'effective' ||
            normalized === '2'
        ) {
            return '#28a745';
        }

        if (
            normalized === 'moderately effective' ||
            normalized === 'moderate effective' ||
            normalized === 'moderately-effective' ||
            normalized === 'moderate' ||
            normalized === '1'
        ) {
            return '#ffc107';
        }

        if (
            normalized === 'not effective' ||
            normalized === 'not-effective' ||
            normalized === '0'
        ) {
            return '#dc3545';
        }

        return '#6c757d';
    }


    /* ============================================================
       CREATE EFFECTIVENESS CHART
       ============================================================ */

    if (
        effectivenessCanvas &&
        typeof Chart !== 'undefined' &&
        Array.isArray(labels) &&
        Array.isArray(totals) &&
        labels.length > 0 &&
        totals.length > 0
    ) {

        console.log(
            'Effectiveness Labels:',
            labels
        );

        console.log(
            'Effectiveness Totals:',
            totals
        );


        /* --------------------------------------------------------
           DESTROY EXISTING CHART
           -------------------------------------------------------- */

        if (
            typeof Chart.getChart === 'function'
        ) {

            const oldChart =
                Chart.getChart(
                    effectivenessCanvas
                );

            if (oldChart) {
                oldChart.destroy();
            }
        }


        /* --------------------------------------------------------
           CONVERT DATA TO NUMBERS
           -------------------------------------------------------- */

        const chartValues =
            totals.map(function (value) {

                const number =
                    Number(value);

                return Number.isFinite(number)
                    ? number
                    : 0;
            });


        /* --------------------------------------------------------
           CALCULATE TOTAL
           -------------------------------------------------------- */

        const chartTotal =
            chartValues.reduce(
                function (sum, value) {
                    return sum + value;
                },
                0
            );


        /* --------------------------------------------------------
           COLORS
           -------------------------------------------------------- */

        const chartColors =
            labels.map(function (label) {

                return getEffectivenessColor(
                    label
                );

            });


        console.log(
            'Effectiveness Colors:',
            chartColors
        );


        /* --------------------------------------------------------
           CREATE CHART
           -------------------------------------------------------- */

        new Chart(
            effectivenessCanvas,
            {

                type: 'pie',


                data: {

                    labels: labels,

                    datasets: [

                        {

                            data: chartValues,

                            backgroundColor:
                                chartColors,

                            borderColor:
                                '#ffffff',

                            borderWidth:
                                3,

                            hoverOffset:
                                8

                        }

                    ]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    animation: {

                        duration: 800

                    },


                    plugins: {


                        /* =================================================
                           LEGEND
                           ================================================= */

                        legend: {

                            display: true,

                            position: 'bottom',

                            align: 'center',

                            labels: {

                                padding: 18,

                                usePointStyle: true,

                                pointStyle: 'circle',

                                font: {

                                    size: 13,

                                    weight: '600'

                                },

                                generateLabels:
                                    function (chart) {

                                        const dataset =
                                            chart.data.datasets[0];

                                        return chart.data.labels.map(
                                            function (
                                                label,
                                                index
                                            ) {

                                                return {

                                                    text:
                                                        label,

                                                    fillStyle:
                                                        dataset.backgroundColor[index],

                                                    strokeStyle:
                                                        '#ffffff',

                                                    lineWidth:
                                                        2,

                                                    hidden:
                                                        false,

                                                    index:
                                                        index

                                                };

                                            }
                                        );

                                    }

                            }

                        },


                        /* =================================================
                           TOOLTIP
                           ================================================= */

                        tooltip: {

                            enabled: true,

                            callbacks: {

                                label:
                                    function (
                                        context
                                    ) {

                                        const value =
                                            Number(
                                                context.raw || 0
                                            );


                                        const percentage =
                                            chartTotal > 0

                                                ? (
                                                    value /
                                                    chartTotal *
                                                    100
                                                ).toFixed(1)

                                                : '0.0';


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


                        /* =================================================
                           DATA LABELS
                           ================================================= */

                        datalabels:

                            typeof ChartDataLabels !==
                            'undefined'

                                ? {

                                    display:
                                        function (
                                            context
                                        ) {

                                            const value =
                                                Number(
                                                    context.dataset.data[
                                                        context.dataIndex
                                                    ] || 0
                                                );

                                            return value > 0;

                                        },


                                    color:
                                        function (
                                            context
                                        ) {

                                            const label =
                                                context.chart
                                                    .data
                                                    .labels[
                                                        context.dataIndex
                                                    ];


                                            const normalized =
                                                String(
                                                    label || ''
                                                )
                                                .trim()
                                                .toLowerCase();


                                            /*
                                             * Dark text for yellow
                                             */

                                            if (
                                                normalized ===
                                                'moderately effective' ||

                                                normalized ===
                                                'moderate effective' ||

                                                normalized ===
                                                'moderately-effective' ||

                                                normalized ===
                                                'moderate' ||

                                                normalized ===
                                                '1'
                                            ) {

                                                return '#212529';

                                            }


                                            return '#ffffff';

                                        },


                                    font: {

                                        weight:
                                            'bold',

                                        size:
                                            14

                                    },


                                    formatter:
                                        function (
                                            value
                                        ) {

                                            if (
                                                !chartTotal ||
                                                !value
                                            ) {

                                                return '';

                                            }


                                            const percentage =
                                                (
                                                    Number(value) /
                                                    chartTotal *
                                                    100
                                                ).toFixed(1);


                                            return (
                                                percentage +
                                                '%'
                                            );

                                        }

                                }

                                : {}

                    }

                },


                plugins:

                    typeof ChartDataLabels !==
                    'undefined'

                        ? [ChartDataLabels]

                        : []

            }
        );

    }


    /* ============================================================
       EFFECTIVENESS CHART - NO DATA
       ============================================================ */

    else {

        console.log(
            'No effectiveness evaluation data.'
        );

    }



    /* ============================================================
       REGISTRATION VARIABLES
       ============================================================ */

    let currentNotificationId = null;

    let pendingRegistrationId = null;

    let pendingRegistrationAction = null;

    let isSubmittingRegistrationAction = false;

    let isSubmittingBulkApproval = false;



    /* ============================================================
       CAKEPHP URLS
       ============================================================ */

    const registrationDetailsBaseUrl =
        '<?= $this->Url->build([
            'controller' => 'Notifications',
            'action' => 'getRegistrationDetails'
        ]) ?>';


    const approveBaseUrl =
        '<?= $this->Url->build([
            'controller' => 'Notifications',
            'action' => 'approveRegistration'
        ]) ?>';


    const declineBaseUrl =
        '<?= $this->Url->build([
            'controller' => 'Notifications',
            'action' => 'declineRegistration'
        ]) ?>';


    const bulkApproveUrl =
        '<?= $this->Url->build([
            'controller' => 'Notifications',
            'action' => 'bulkApprove'
        ]) ?>';



    /* ============================================================
       REAL-TIME REGISTRATION REQUEST TIME

       The notification timestamp is handled as an absolute Unix
       timestamp. This means the browser never guesses the timezone.

       PHP provides the current server epoch time when the page loads.
       JavaScript then keeps that server clock moving using elapsed
       browser time. The relative age therefore does not depend on the
       Windows/browser timezone or clock being correct.

       The exact timestamp under the relative time is rendered by PHP
       in Asia/Manila time.
       ============================================================ */

    const serverNowAtLoad =
        <?= time() * 1000 ?>;

    const browserNowAtLoad =
        Date.now();


    function getCurrentServerTime() {

        return (
            serverNowAtLoad +
            (Date.now() - browserNowAtLoad)
        );

    }


    function updateRegistrationTimes() {

        $('.realtime-registration-time').each(function () {

            const element = $(this);

            const createdTimestamp =
                Number(
                    element.attr('data-created')
                );


            if (
                !Number.isFinite(createdTimestamp) ||
                createdTimestamp <= 0
            ) {

                element
                    .find('.relative-time')
                    .text('Unknown');

                return;

            }


            const createdMillis =
                createdTimestamp * 1000;


            let differenceSeconds =
                Math.floor(
                    (
                        getCurrentServerTime() -
                        createdMillis
                    ) / 1000
                );


            /*
             * Prevent negative values from appearing if the server
             * and database clocks differ by a small amount.
             */
            if (differenceSeconds < 0) {
                differenceSeconds = 0;
            }


            let text = '';


            /* --------------------------------------------------------
               UNDER 1 MINUTE
            --------------------------------------------------------- */

            if (differenceSeconds < 60) {

                text = 'Just now';

            }


            /* --------------------------------------------------------
               UNDER 1 HOUR
            --------------------------------------------------------- */

            else if (differenceSeconds < 3600) {

                const minutes =
                    Math.floor(
                        differenceSeconds / 60
                    );

                text =
                    minutes === 1
                        ? '1 minute ago'
                        : minutes + ' minutes ago';

            }


            /* --------------------------------------------------------
               UNDER 1 DAY
            --------------------------------------------------------- */

            else if (differenceSeconds < 86400) {

                const hours =
                    Math.floor(
                        differenceSeconds / 3600
                    );

                const minutes =
                    Math.floor(
                        (differenceSeconds % 3600) / 60
                    );

                if (minutes === 0) {

                    text =
                        hours === 1
                            ? '1 hour ago'
                            : hours + ' hours ago';

                } else {

                    const hourText =
                        hours === 1
                            ? '1 hour'
                            : hours + ' hours';

                    const minuteText =
                        minutes === 1
                            ? '1 minute'
                            : minutes + ' minutes';

                    text =
                        hourText +
                        ' ' +
                        minuteText +
                        ' ago';

                }

            }


            /* --------------------------------------------------------
               1 DAY OR OLDER
            --------------------------------------------------------- */

            else {

                const days =
                    Math.floor(
                        differenceSeconds / 86400
                    );

                const hours =
                    Math.floor(
                        (differenceSeconds % 86400) / 3600
                    );

                if (
                    days === 1 &&
                    hours === 0
                ) {

                    text = 'Yesterday';

                }

                else if (hours === 0) {

                    text =
                        days === 1
                            ? '1 day ago'
                            : days + ' days ago';

                }

                else {

                    const dayText =
                        days === 1
                            ? '1 day'
                            : days + ' days';

                    const hourText =
                        hours === 1
                            ? '1 hour'
                            : hours + ' hours';

                    text =
                        dayText +
                        ' ' +
                        hourText +
                        ' ago';

                }

            }


            element
                .find('.relative-time')
                .text(text);

        });

    }


    /*
     * Update immediately when the dashboard opens.
     */
    updateRegistrationTimes();


    /*
     * Keep the displayed age current.
     */
    setInterval(
        updateRegistrationTimes,
        1000
    );



    /* ============================================================
       CSRF TOKEN
       ============================================================ */

    function getCsrfToken() {

        let token =
            $('input[name="_csrfToken"]')
                .first()
                .val();


        if (!token) {

            token =
                $('meta[name="csrfToken"]')
                    .attr('content');

        }


        return token || '';

    }



    /* ============================================================
       ADD FARMER FIELD
       ============================================================ */

    function addFarmerField(
        container,
        label,
        value
    ) {

        let displayValue =
            value;


        if (
            displayValue === null ||
            displayValue === undefined ||
            displayValue === ''
        ) {

            displayValue = '—';

        }


        const item =
            $('<div>')
                .addClass(
                    'registration-info-item'
                );


        $('<span>')
            .addClass(
                'registration-label'
            )
            .text(
                label
            )
            .appendTo(
                item
            );


        $('<span>')
            .addClass(
                'registration-value'
            )
            .text(
                displayValue
            )
            .appendTo(
                item
            );


        container.append(
            item
        );

    }



    /* ============================================================
       RESET REGISTRATION MODAL
       ============================================================ */

    function resetRegistrationModal() {

        $('#registrationLoading')
            .show();


        $('#registrationModalContent')
            .hide();


        $('#registrationModalError')
            .hide();


        $('#registrationModalFooter')
            .hide();


        $('#duplicateFarmerAlert')
            .hide();


        $('#noDuplicateFarmerAlert')
            .hide();


        $('#modalApproveBtn')
            .hide();


        $('#modalDeclineBtn')
            .hide();


        $('#registrationUsername')
            .text('—');


        $('#registrationRole')
            .text('—');


        $('#existingFarmerNumber')
            .text('—');


        $('#registrationErrorMessage')
            .text('');


        $('#farmerInformationGrid')
            .empty();

    }



    /* ============================================================
       SHOW REGISTRATION ERROR
       ============================================================ */

    function showRegistrationError(
        message
    ) {

        $('#registrationLoading')
            .hide();


        $('#registrationModalContent')
            .hide();


        $('#registrationModalFooter')
            .hide();


        $('#registrationErrorMessage')
            .text(
                message ||
                'Unable to load registration details.'
            );


        $('#registrationModalError')
            .show();

    }



    /* ============================================================
       SHOW REGISTRATION RESULT
       ============================================================ */

    function showRegistrationResult(
        type,
        title,
        message
    ) {

        $('#registrationResultTitle')
            .text(
                title
            );


        $('#registrationResultMessage')
            .text(
                message
            );


        const icon =
            $('#registrationResultIcon');


        icon.empty();


        if (
            type === 'success'
        ) {

            icon.html(
                '<i class="fas fa-check-circle text-success" ' +
                'style="font-size:65px;"></i>'
            );

        }

        else if (
            type === 'warning'
        ) {

            icon.html(
                '<i class="fas fa-exclamation-triangle text-warning" ' +
                'style="font-size:65px;"></i>'
            );

        }

        else {

            icon.html(
                '<i class="fas fa-times-circle text-danger" ' +
                'style="font-size:65px;"></i>'
            );

        }


        $('#registrationResultModal')
            .modal({
                backdrop: 'static',
                keyboard: false
            });

    }



    /* ============================================================
       SHOW BULK RESULT
       ============================================================ */

    function showBulkResult(
        type,
        title,
        message
    ) {

        $('#bulkResultTitle')
            .text(
                title
            );


        $('#bulkResultMessage')
            .text(
                message
            );


        const icon =
            $('#bulkResultIcon');


        icon.empty();


        if (
            type === 'success'
        ) {

            icon.html(
                '<i class="fas fa-check-circle text-success" ' +
                'style="font-size:65px;"></i>'
            );

        }

        else if (
            type === 'warning'
        ) {

            icon.html(
                '<i class="fas fa-exclamation-triangle text-warning" ' +
                'style="font-size:65px;"></i>'
            );

        }

        else {

            icon.html(
                '<i class="fas fa-times-circle text-danger" ' +
                'style="font-size:65px;"></i>'
            );

        }


        $('#bulkApproveResultModal')
            .modal({
                backdrop: 'static',
                keyboard: false
            });

    }



    /* ============================================================
       VIEW REGISTRATION
       ============================================================ */

    $(document).on(
        'click',
        '.view-registration-btn',
        function (e) {

            e.preventDefault();


            const id =
                $(this).data('id');


            console.log(
                'VIEW REGISTRATION ID:',
                id
            );


            if (!id) {

                showRegistrationError(
                    'Registration ID is missing.'
                );


                $('#registrationDetailsModal')
                    .modal('show');


                return;

            }


            currentNotificationId =
                id;


            resetRegistrationModal();


            $('#registrationDetailsModal')
                .modal('show');


            const requestUrl =
                registrationDetailsBaseUrl +
                '/' +
                encodeURIComponent(id);


            console.log(
                'GET:',
                requestUrl
            );


            $.ajax({

                url:
                    requestUrl,

                type:
                    'GET',

                dataType:
                    'json',

                cache:
                    false,

                timeout:
                    15000,


                success:
                    function (response) {

                        console.log(
                            'REGISTRATION RESPONSE:',
                            response
                        );


                        if (
                            !response ||
                            response.success !== true
                        ) {

                            showRegistrationError(

                                response &&
                                response.message

                                    ? response.message

                                    : 'Invalid response from server.'

                            );

                            return;

                        }


                        const data =
                            response.data || {};


                        const user =
                            data.user || {};


                        const farmer =
                            data.farmer || {};


                        const existingFarmer =
                            response.existingFarmer ||
                            null;


                        /* ==========================================
                           USER
                           ========================================== */

                        $('#registrationUsername')
                            .text(
                                user.username ||
                                '—'
                            );


                        $('#registrationRole')
                            .text(
                                user.role ||
                                'farmer'
                            );


                        /* ==========================================
                           FARMER
                           ========================================== */

                        const farmerGrid =
                            $('#farmerInformationGrid');


                        farmerGrid.empty();
                        
                        addFarmerField(
                            farmerGrid,
                            'LGU-RSBSA Number',
                            farmer.farmer_no
                        );


                        addFarmerField(
                            farmerGrid,
                            'First Name',
                            farmer.first_name
                        );


                        addFarmerField(
                            farmerGrid,
                            'Middle Name',
                            farmer.middle_name
                        );


                        addFarmerField(
                            farmerGrid,
                            'Last Name',
                            farmer.last_name
                        );


                        addFarmerField(
                            farmerGrid,
                            'Address',
                            farmer.address
                        );


                        addFarmerField(
                            farmerGrid,
                            'Contact Number',
                            farmer.contact_no
                        );


                        addFarmerField(
                            farmerGrid,
                            'Gender',
                            farmer.gender
                        );


                        addFarmerField(
                            farmerGrid,
                            'Birth Date',
                            farmer.birthdate
                        );


                        /* ==========================================
                           DUPLICATE
                           ========================================== */

                        if (
                            existingFarmer
                        ) {

                            $('#existingFarmerNumber')
                                .text(
                                    existingFarmer.farmer_no ||
                                    existingFarmer.id ||
                                    '—'
                                );


                            $('#duplicateFarmerAlert')
                                .show();


                            $('#noDuplicateFarmerAlert')
                                .hide();

                        }

                        else {

                            $('#duplicateFarmerAlert')
                                .hide();


                            $('#noDuplicateFarmerAlert')
                                .show();

                        }


                        /* ==========================================
                           STATUS
                           ========================================== */

                        const status =
                            String(
                                response.status ||
                                'pending'
                            )
                            .toLowerCase()
                            .trim();


                        console.log(
                            'STATUS:',
                            status
                        );


                        $('#registrationLoading')
                            .hide();


                        $('#registrationModalContent')
                            .show();


                        /* ==========================================
                           ALREADY APPROVED
                           ========================================== */

                        if (
                            status === 'approved'
                        ) {

                            $('#registrationModalFooter')
                                .hide();


                            $('#modalApproveBtn')
                                .hide();


                            $('#modalDeclineBtn')
                                .hide();


                            setTimeout(
                                function () {

                                    $('#registrationDetailsModal')
                                        .modal('hide');


                                    setTimeout(
                                        function () {

                                            showRegistrationResult(

                                                'warning',

                                                'Registration Already Approved',

                                                'This farmer registration has already been approved.'

                                            );

                                        },
                                        350
                                    );

                                },
                                250
                            );


                            return;

                        }


                        /* ==========================================
                           ALREADY DECLINED
                           ========================================== */

                        if (
                            status === 'declined'
                        ) {

                            $('#registrationModalFooter')
                                .hide();


                            $('#modalApproveBtn')
                                .hide();


                            $('#modalDeclineBtn')
                                .hide();


                            setTimeout(
                                function () {

                                    $('#registrationDetailsModal')
                                        .modal('hide');


                                    setTimeout(
                                        function () {

                                            showRegistrationResult(

                                                'warning',

                                                'Registration Already Declined',

                                                'This farmer registration has already been declined.'

                                            );

                                        },
                                        350
                                    );

                                },
                                250
                            );


                            return;

                        }


                        /* ==========================================
                           PENDING
                           ========================================== */

                        $('#registrationModalFooter')
                            .show();


                        $('#modalApproveBtn')
                            .show();


                        $('#modalDeclineBtn')
                            .show();

                    },


                error:
                    function (
                        xhr,
                        textStatus,
                        errorThrown
                    ) {

                        console.error(
                            'REGISTRATION ERROR:',
                            xhr.status,
                            textStatus,
                            errorThrown,
                            xhr.responseText
                        );


                        let message =
                            'Unable to load registration details.';


                        if (
                            textStatus ===
                            'timeout'
                        ) {

                            message =
                                'The request timed out. Please try again.';

                        }

                        else if (
                            xhr.status === 404
                        ) {

                            message =
                                'The registration details URL was not found.';

                        }

                        else if (
                            xhr.status === 403
                        ) {

                            message =
                                'You are not authorized to view this registration.';

                        }

                        else if (
                            xhr.status >= 500
                        ) {

                            message =
                                'A server error occurred while loading the registration.';

                        }


                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;

                        }


                        showRegistrationError(
                            message
                        );

                    }

            });

        }
    );



    /* ============================================================
       APPROVE BUTTON
       ============================================================ */

    $(document).on(
        'click',
        '#modalApproveBtn',
        function () {

            if (
                !currentNotificationId
            ) {

                showRegistrationResult(

                    'error',

                    'Unable to Approve',

                    'Registration ID is missing.'

                );

                return;

            }


            pendingRegistrationId =
                currentNotificationId;


            pendingRegistrationAction =
                'approve';


            $('#registrationConfirmModalLabel')
                .text(
                    'Confirm Registration Approval'
                );


            $('#registrationConfirmIcon')
                .html(
                    '<i class="fas fa-check-circle text-success" ' +
                    'style="font-size:65px;"></i>'
                );


            $('#registrationConfirmTitle')
                .text(
                    'Approve Farmer Registration?'
                );


            $('#registrationConfirmMessage')
                .text(
                    'Are you sure you want to approve this farmer registration?'
                );


            $('#confirmRegistrationActionBtn')
                .removeClass(
                    'btn-danger btn-primary'
                )
                .addClass(
                    'btn-success'
                )
                .html(
                    '<i class="fas fa-check mr-1"></i>' +
                    ' Approve Registration'
                )
                .prop(
                    'disabled',
                    false
                );


            $('#registrationDetailsModal')
                .modal('hide');


            setTimeout(
                function () {

                    $('#registrationConfirmModal')
                        .modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                },
                400
            );

        }
    );



    /* ============================================================
       DECLINE BUTTON
       ============================================================ */

    $(document).on(
        'click',
        '#modalDeclineBtn',
        function () {

            if (
                !currentNotificationId
            ) {

                showRegistrationResult(

                    'error',

                    'Unable to Decline',

                    'Registration ID is missing.'

                );

                return;

            }


            pendingRegistrationId =
                currentNotificationId;


            pendingRegistrationAction =
                'decline';


            $('#registrationConfirmModalLabel')
                .text(
                    'Confirm Registration Decline'
                );


            $('#registrationConfirmIcon')
                .html(
                    '<i class="fas fa-times-circle text-danger" ' +
                    'style="font-size:65px;"></i>'
                );


            $('#registrationConfirmTitle')
                .text(
                    'Decline Farmer Registration?'
                );


            $('#registrationConfirmMessage')
                .text(
                    'Are you sure you want to decline this farmer registration?'
                );


            $('#confirmRegistrationActionBtn')
                .removeClass(
                    'btn-success btn-primary'
                )
                .addClass(
                    'btn-danger'
                )
                .html(
                    '<i class="fas fa-times mr-1"></i>' +
                    ' Decline Registration'
                )
                .prop(
                    'disabled',
                    false
                );


            $('#registrationDetailsModal')
                .modal('hide');


            setTimeout(
                function () {

                    $('#registrationConfirmModal')
                        .modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                },
                400
            );

        }
    );



    /* ============================================================
       CONFIRM APPROVE / DECLINE
       ============================================================ */

    $(document).on(
        'click',
        '#confirmRegistrationActionBtn',
        function () {

            if (
                isSubmittingRegistrationAction
            ) {

                return;

            }


            if (
                !pendingRegistrationId ||
                !pendingRegistrationAction
            ) {

                showRegistrationResult(

                    'error',

                    'Unable to Process',

                    'The registration information is missing.'

                );

                return;

            }


            isSubmittingRegistrationAction =
                true;


            const button =
                $(this);


            button
                .prop(
                    'disabled',
                    true
                );


            button.html(
                '<span class="spinner-border spinner-border-sm mr-1"></span>' +
                ' Processing...'
            );


            let actionUrl;


            if (
                pendingRegistrationAction ===
                'approve'
            ) {

                actionUrl =
                    approveBaseUrl +
                    '/' +
                    encodeURIComponent(
                        pendingRegistrationId
                    );

            }

            else {

                actionUrl =
                    declineBaseUrl +
                    '/' +
                    encodeURIComponent(
                        pendingRegistrationId
                    );

            }


            console.log(
                'SUBMIT:',
                actionUrl
            );


            /*
             * NATIVE FORM
             * This allows CakePHP CSRF protection.
             */

            const form =
                $('<form>', {

                    method:
                        'POST',

                    action:
                        actionUrl

                });


            const csrfToken =
                getCsrfToken();


            if (
                csrfToken
            ) {

                form.append(
                    $('<input>', {

                        type:
                            'hidden',

                        name:
                            '_csrfToken',

                        value:
                            csrfToken

                    })
                );

            }


            $('body')
                .append(
                    form
                );


            /*
             * Native submit.
             */

            HTMLFormElement
                .prototype
                .submit
                .call(
                    form[0]
                );

        }
    );



    /* ============================================================
       CONFIRM MODAL CLOSED
       ============================================================ */

    $('#registrationConfirmModal')
        .on(
            'hidden.bs.modal',
            function () {

                if (
                    !isSubmittingRegistrationAction
                ) {

                    pendingRegistrationId =
                        null;


                    pendingRegistrationAction =
                        null;


                    $('#confirmRegistrationActionBtn')
                        .prop(
                            'disabled',
                            false
                        );

                }

            }
        );



    /* ============================================================
       SELECT ALL
       ============================================================ */

    $(document).on(
        'change',
        '#selectAllNotifications',
        function () {

            const checked =
                $(this)
                    .prop(
                        'checked'
                    );


            $('.notif-checkbox:not(:disabled)')
                .prop(
                    'checked',
                    checked
                );


            updateBulkApproveButton();

        }
    );



    /* ============================================================
       INDIVIDUAL CHECKBOX
       ============================================================ */

    $(document).on(
        'change',
        '.notif-checkbox',
        function () {

            updateBulkApproveButton();

        }
    );



    /* ============================================================
       UPDATE BULK BUTTON
       ============================================================ */

    function updateBulkApproveButton() {

        const selected =
            $('.notif-checkbox:checked')
                .length;


        const available =
            $('.notif-checkbox:not(:disabled)')
                .length;


        $('#bulkApproveBtn')
            .prop(
                'disabled',
                selected === 0
            );


        if (
            selected > 0
        ) {

            $('#bulkApproveBtn')
                .html(
                    '<i class="fas fa-check-double mr-1"></i>' +
                    ' Approve Selected (' +
                    selected +
                    ')'
                );

        }

        else {

            $('#bulkApproveBtn')
                .html(
                    '<i class="fas fa-check-double mr-1"></i>' +
                    ' Approve Selected'
                );

        }


        $('#selectAllNotifications')
            .prop(
                'checked',
                available > 0 &&
                selected === available
            );

    }



    /* ============================================================
       BULK APPROVE BUTTON
       ============================================================ */

    $(document).on(
        'click',
        '#bulkApproveBtn',
        function () {

            const selectedIds =
                $('.notif-checkbox:checked')
                    .map(
                        function () {

                            return $(this).val();

                        }
                    )
                    .get();


            console.log(
                'SELECTED IDS:',
                selectedIds
            );


            if (
                selectedIds.length === 0
            ) {

                showRegistrationResult(

                    'warning',

                    'No Registration Selected',

                    'Please select at least one farmer registration to approve.'

                );

                return;

            }


            $('#bulkApproveCount')
                .text(
                    selectedIds.length
                );


            $('#bulkApproveConfirmModal')
                .modal({
                    backdrop: 'static',
                    keyboard: false
                });

        }
    );



    /* ============================================================
       CONFIRM BULK APPROVE
       ============================================================ */

    $(document).on(
        'click',
        '#confirmBulkApproveBtn',
        function () {

            if (
                isSubmittingBulkApproval
            ) {

                return;

            }


            const selectedIds =
                $('.notif-checkbox:checked')
                    .map(
                        function () {

                            return $(this).val();

                        }
                    )
                    .get();


            if (
                selectedIds.length === 0
            ) {

                $('#bulkApproveConfirmModal')
                    .modal('hide');


                showRegistrationResult(

                    'warning',

                    'No Registration Selected',

                    'Please select at least one farmer registration to approve.'

                );

                return;

            }


            isSubmittingBulkApproval =
                true;


            const button =
                $(this);


            button
                .prop(
                    'disabled',
                    true
                );


            button.html(
                '<span class="spinner-border spinner-border-sm mr-1"></span>' +
                ' Approving...'
            );


            /*
             * CREATE NATIVE FORM
             */

            const form =
                $('<form>', {

                    method:
                        'POST',

                    action:
                        bulkApproveUrl

                });


            /*
             * CSRF
             */

            const csrfToken =
                getCsrfToken();


            if (
                csrfToken
            ) {

                form.append(
                    $('<input>', {

                        type:
                            'hidden',

                        name:
                            '_csrfToken',

                        value:
                            csrfToken

                    })
                );

            }


            /*
             * IDS
             */

            selectedIds.forEach(
                function (id) {

                    form.append(
                        $('<input>', {

                            type:
                                'hidden',

                            name:
                                'notification_ids[]',

                            value:
                                id

                        })
                    );

                }
            );


            console.log(
                'BULK URL:',
                bulkApproveUrl
            );


            console.log(
                'BULK IDS:',
                selectedIds
            );


            $('body')
                .append(
                    form
                );


            /*
             * NATIVE SUBMIT
             */

            HTMLFormElement
                .prototype
                .submit
                .call(
                    form[0]
                );

        }
    );



    /* ============================================================
       BULK CONFIRM MODAL CLOSED
       ============================================================ */

    $('#bulkApproveConfirmModal')
        .on(
            'hidden.bs.modal',
            function () {

                if (
                    !isSubmittingBulkApproval
                ) {

                    $('#confirmBulkApproveBtn')
                        .prop(
                            'disabled',
                            false
                        );


                    $('#confirmBulkApproveBtn')
                        .html(
                            '<i class="fas fa-check-double mr-1"></i>' +
                            ' Approve Selected'
                        );

                }

            }
        );



    /* ============================================================
       DETAILS MODAL CLOSED
       ============================================================ */

    $('#registrationDetailsModal')
        .on(
            'hidden.bs.modal',
            function () {

                /*
                 * Do not clear ID while confirmation
                 * is waiting.
                 */

                if (
                    !pendingRegistrationAction
                ) {

                    currentNotificationId =
                        null;

                }

            }
        );



    /* ============================================================
       CLEAN RESULT MODAL BACKDROP
       ============================================================ */

    $('#registrationResultModal')
        .on(
            'hidden.bs.modal',
            function () {

                $('.modal-backdrop')
                    .remove();


                $('body')
                    .removeClass(
                        'modal-open'
                    );


                $('body')
                    .css(
                        'padding-right',
                        ''
                    );

            }
        );


    $('#bulkApproveResultModal')
        .on(
            'hidden.bs.modal',
            function () {

                $('.modal-backdrop')
                    .remove();


                $('body')
                    .removeClass(
                        'modal-open'
                    );


                $('body')
                    .css(
                        'padding-right',
                        ''
                    );

            }
        );



    /* ============================================================
       INITIALIZE
       ============================================================ */

    updateBulkApproveButton();


});
</script>
