<div class="container-fluid px-3">

    <!-- =========================================================
         PAGE HEADER
    ========================================================== -->
    <div class="d-flex align-items-center justify-content-between mb-3">

        <div>
            <h3 class="mb-1 font-weight-bold">
                <i class="fas fa-history text-muted mr-2"></i>
                Audit Logs
            </h3>

            <small class="text-muted">
                Monitor and review system activities
            </small>
        </div>
        
         <div>

        <a
            href="<?= $this->Url->build([
                'controller' => 'AuditLogs',
                'action' => 'downloadSummary'
            ]) ?>"
            class="btn btn-success"
            title="Download Audit Log Summary"
        >
            <i class="fas fa-file-excel mr-1"></i>
            Download Summary
        </a>

    </div>

    </div>


    <!-- =========================================================
         FILTER CARD
    ========================================================== -->
    <div class="card audit-filter-card shadow-sm mb-3">

        <div class="card-header py-2 px-3">

            <div class="d-flex align-items-center justify-content-between">

                <div class="d-flex align-items-center">

                    <div class="filter-icon mr-2">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>
                        <span class="font-weight-bold">
                            Filters
                        </span>

                        <small class="text-muted ml-1">
                            Refine audit records
                        </small>
                    </div>

                </div>

                <button
                    type="button"
                    id="clearFilters"
                    class="btn btn-sm btn-outline-secondary"
                >
                    <i class="fas fa-times mr-1"></i>
                    Clear
                </button>

            </div>

        </div>


        <div class="card-body px-3 py-3">

            <div class="row">

                <!-- USER -->
                <div class="col-xl-2 col-lg-4 col-md-6 mb-2">

                    <label
                        for="userFilter"
                        class="filter-label"
                    >
                        <i class="fas fa-user mr-1"></i>
                        User
                    </label>

                    <select
                        id="userFilter"
                        class="form-control form-control-sm"
                    >

                        <option value="">All Users</option>

                        <?php
                        $users = [];

                        foreach ($auditLogs as $log) {
                            if ($log->user && !empty($log->user->username)) {
                                $users[$log->user->username] =
                                    $log->user->username;
                            }
                        }

                        ksort($users);
                        ?>

                        <?php foreach ($users as $username): ?>

                            <option value="<?= h($username) ?>">
                                <?= h($username) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- ACTION -->
                <div class="col-xl-2 col-lg-4 col-md-6 mb-2">

                    <label
                        for="actionFilter"
                        class="filter-label"
                    >
                        <i class="fas fa-bolt mr-1"></i>
                        Action
                    </label>

                    <select
                        id="actionFilter"
                        class="form-control form-control-sm"
                    >

                        <option value="">All Actions</option>

                        <?php
                        $actions = [];

                        foreach ($auditLogs as $log) {
                            if (!empty($log->action)) {
                                $actions[$log->action] =
                                    $log->action;
                            }
                        }

                        ksort($actions);
                        ?>

                        <?php foreach ($actions as $action): ?>

                            <option value="<?= h($action) ?>">
                                <?= h(
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $action
                                        )
                                    )
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- SUBJECT -->
                <div class="col-xl-2 col-lg-4 col-md-6 mb-2">

                    <label
                        for="subjectFilter"
                        class="filter-label"
                    >
                        <i class="fas fa-cube mr-1"></i>
                        Subject
                    </label>

                    <select
                        id="subjectFilter"
                        class="form-control form-control-sm"
                    >

                        <option value="">All Subjects</option>

                        <?php
                        $subjects = [];

                        foreach ($auditLogs as $log) {
                            if (!empty($log->subject_type)) {
                                $subjects[$log->subject_type] =
                                    $log->subject_type;
                            }
                        }

                        ksort($subjects);
                        ?>
                        <?php foreach ($subjects as $subject): ?>

                            <option value="<?= h($subject) ?>">
                                <?= h($subject) ?>
                            </option>

                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- =========================================================
         AUDIT LOG TABLE CARD
    ========================================================== -->
    <div class="card audit-table-card shadow-sm">

        <div class="card-header py-2 px-3">

            <div class="d-flex align-items-center justify-content-between">

                <div class="d-flex align-items-center">

                    <i class="fas fa-list-alt text-muted mr-2"></i>

                    <span class="font-weight-bold">
                        Activity Records
                    </span>

                </div>

                <span class="badge badge-light">
                    Audit Trail
                </span>

            </div>

        </div>


       <div class="card-body p-0">

    <div class="table-responsive audit-table-responsive">

        <table
            id="audit-logs-table"
            class="table audit-modern-table mb-0"
            width="100%"
        >

            <thead>
                <tr>

                    <th class="text-center audit-id-column">
                        ID
                    </th>

                    <th>
                        <i class="fas fa-user mr-1"></i>
                        User
                    </th>

                    <th>
                        <i class="fas fa-bolt mr-1"></i>
                        Action
                    </th>

                    <th>
                        <i class="fas fa-align-left mr-1"></i>
                        Description
                    </th>

                    <th>
                        <i class="fas fa-cube mr-1"></i>
                        Subject
                    </th>

                    <th>
                        <i class="fas fa-network-wired mr-1"></i>
                        IP Address
                    </th>

                    <th>
                        <i class="far fa-clock mr-1"></i>
                        Date
                    </th>

                </tr>
            </thead>


            <tbody>

            <?php if (!empty($auditLogs)): ?>

                <?php foreach ($auditLogs as $log): ?>

                    <tr>

                        <!-- =================================================
                             ID
                        ================================================== -->
                        <td class="text-center">

                            <span class="audit-id">
                                #<?= h($log->id) ?>
                            </span>

                        </td>


                        <!-- =================================================
                             USER
                        ================================================== -->
                        <td>

                            <?php if ($log->user): ?>

                                <div class="audit-user">

                                    <div class="audit-user-icon">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div class="audit-user-name">
                                        <?= h(
                                            $log->user->username
                                            ?? 'Unknown User'
                                        ) ?>
                                    </div>

                                </div>

                            <?php else: ?>

                                <div class="audit-user">

                                    <div class="audit-user-icon unknown">
                                        <i class="fas fa-user-slash"></i>
                                    </div>

                                    <span class="text-muted">
                                        Unknown User
                                    </span>

                                </div>

                            <?php endif; ?>

                        </td>


                        <!-- =================================================
                             ACTION
                        ================================================== -->
                        <td>

                            <?php

                            $actionLabel = ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $log->action
                                )
                            );

                            $actionClass = 'secondary';
                            $actionIcon = 'fa-info-circle';

                            switch ($log->action) {

                                case 'login':
                                    $actionClass = 'success';
                                    $actionIcon = 'fa-sign-in-alt';
                                    break;

                                case 'logout':
                                    $actionClass = 'warning';
                                    $actionIcon = 'fa-sign-out-alt';
                                    break;

                                case 'login_failed':
                                    $actionClass = 'danger';
                                    $actionIcon = 'fa-exclamation-triangle';
                                    break;

                                case 'registered':
                                    $actionClass = 'info';
                                    $actionIcon = 'fa-user-plus';
                                    break;

                                case 'created':
                                    $actionClass = 'success';
                                    $actionIcon = 'fa-plus';
                                    break;

                                case 'updated':
                                    $actionClass = 'primary';
                                    $actionIcon = 'fa-edit';
                                    break;

                                case 'deleted':
                                    $actionClass = 'danger';
                                    $actionIcon = 'fa-trash';
                                    break;

                            }

                            ?>

                            <span class="audit-action badge-<?= $actionClass ?>">

                                <i class="fas <?= $actionIcon ?> mr-1"></i>

                                <?= h($actionLabel) ?>

                            </span>

                        </td>


                        <!-- =================================================
                             DESCRIPTION
                        ================================================== -->
                        <td>

                            <div class="audit-description">

                                <?= h(
                                    $log->description
                                ) ?>

                            </div>

                        </td>


                        <!-- =================================================
                             SUBJECT
                        ================================================== -->
                        <td>

                            <?php if ($log->subject_type): ?>

                                <div class="audit-subject">

                                    <span class="audit-subject-name">
                                        <?= h(
                                            $log->subject_type
                                        ) ?>
                                    </span>

                                    <?php if ($log->subject_id): ?>

                                        <span class="audit-subject-id">
                                            #<?= h(
                                                $log->subject_id
                                            ) ?>
                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php else: ?>

                                <span class="audit-na">
                                    N/A
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- =================================================
                             IP ADDRESS
                        ================================================== -->
                        <td>

                            <span class="audit-ip">

                                <i class="fas fa-globe mr-1"></i>

                                <?= h(
                                    $log->ip_address ?? 'N/A'
                                ) ?>

                            </span>

                        </td>


                        <!-- =================================================
                             DATE
                        ================================================== -->
                        <td
                            data-order="<?= $log->created
                                ? $log->created->format('Y-m-d H:i:s')
                                : '' ?>"
                        >

                            <?php if ($log->created): ?>

                                <div class="audit-date">

                                    <div class="audit-date-main">

                                        <?= h(
                                            $log->created->format(
                                                'Y-m-d'
                                            )
                                        ) ?>

                                    </div>

                                    <div class="audit-date-time">

                                        <i class="far fa-clock mr-1"></i>

                                        <?= h(
                                            $log->created->format(
                                                'h:i A'
                                            )
                                        ) ?>

                                    </div>

                                </div>

                            <?php else: ?>

                                <span class="audit-na">
                                    N/A
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

    </div>

</div>

<script>
$(document).ready(function () {

    /*
     * Initialize DataTable
     */
    var table = $('#audit-logs-table').DataTable({

        responsive: true,

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],

        order: [
            [6, 'desc']
        ],

        columnDefs: [
            {
                targets: 0,
                className: 'text-center'
            }
        ],

        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ logs",
            info: "Showing _START_ to _END_ of _TOTAL_ logs",
            infoEmpty: "No audit logs available",
            zeroRecords: "No matching audit logs found",
            emptyTable: "No audit logs found",
            paginate: {
                previous: "Previous",
                next: "Next"
            }
        }

    });


    /*
     * USER FILTER
     */
    $('#userFilter').on('change', function () {

        table
            .column(1)
            .search(this.value)
            .draw();

    });


    /*
     * ACTION FILTER
     */
    $('#actionFilter').on('change', function () {

        table
            .column(2)
            .search(this.value)
            .draw();

    });


    /*
     * SUBJECT FILTER
     */
    $('#subjectFilter').on('change', function () {

        table
            .column(4)
            .search(this.value)
            .draw();

    });


    /*
     * CLEAR FILTERS
     */
    $('#clearFilters').on('click', function () {

        $('#userFilter').val('');
        $('#actionFilter').val('');
        $('#subjectFilter').val('');

        table
            .search('')
            .columns()
            .search('')
            .draw();

    });

});
</script>