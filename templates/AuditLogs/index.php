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

            <div class="table-responsive">

                <table
                    id="audit-logs-table"
                    class="table table-bordered table-hover table-striped mb-0"
                    width="100%"
                >

                    <thead>
                        <tr>

                            <th class="text-center">
                                ID
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                    <?php if (!empty($auditLogs)): ?>

                        <?php foreach ($auditLogs as $log): ?>

                            <tr>

                                <!-- ID -->
                                <td class="text-center font-weight-bold">
                                    <?= h($log->id) ?>
                                </td>


                                <!-- USER -->
                                <td>

                                    <?php if ($log->user): ?>

                                        <i class="fas fa-user-circle text-muted mr-1"></i>

                                        <?= h(
                                            $log->user->username
                                            ?? 'Unknown User'
                                        ) ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            <i class="fas fa-user-slash mr-1"></i>
                                            Unknown User
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- ACTION -->
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

                                    switch ($log->action) {

                                        case 'login':
                                            $actionClass = 'success';
                                            break;

                                        case 'logout':
                                            $actionClass = 'warning';
                                            break;

                                        case 'login_failed':
                                            $actionClass = 'danger';
                                            break;

                                        case 'registered':
                                            $actionClass = 'info';
                                            break;

                                        case 'created':
                                            $actionClass = 'success';
                                            break;

                                        case 'updated':
                                            $actionClass = 'primary';
                                            break;

                                        case 'deleted':
                                            $actionClass = 'danger';
                                            break;

                                    }
                                    ?>

                                    <span class="badge badge-<?= $actionClass ?>">
                                        <?= h($actionLabel) ?>
                                    </span>

                                </td>


                                <!-- DESCRIPTION -->
                                <td>
                                    <?= h($log->description) ?>
                                </td>


                                <!-- SUBJECT -->
                                <td>

                                    <?php if ($log->subject_type): ?>

                                        <span class="text-dark">
                                            <?= h($log->subject_type) ?>
                                        </span>

                                        <?php if ($log->subject_id): ?>

                                            <span class="text-muted">
                                                #<?= h($log->subject_id) ?>
                                            </span>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- IP -->
                                <td>
                                    <code>
                                        <?= h(
                                            $log->ip_address ?? 'N/A'
                                        ) ?>
                                    </code>
                                </td>


                                <!-- DATE -->
                                <td
                                    data-order="<?= $log->created
                                        ? $log->created->format('Y-m-d H:i:s')
                                        : '' ?>"
                                >

                                    <?php if ($log->created): ?>

                                        <span class="text-nowrap">
                                            <?= h(
                                                $log->created->format(
                                                    'Y-m-d h:i A'
                                                )
                                            ) ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted">
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