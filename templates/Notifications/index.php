<!-- <div class="col-12">
    <div class="card card-primary">

        <div class="card-header">
            <h3 class="card-title text-dark">Registrations Requests</h3>

            <div class="card-tools">


                <?= $this->Form->postLink(
                    '<i class="fas fa-trash"></i> Delete All',
                    ['action' => 'deleteAll'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'escape' => false,
                        'confirm' => 'Are you sure you want to delete all notifications?'
                    ]
                ) ?>

            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

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
                                    <input type="checkbox"
                                           class="notif-checkbox"
                                           value="<?= $notif->id ?>">
                                </td>

                                <td>
                                    <?= h($notif->message) ?>
                                </td>

                                <td>
                                    <?= $notif->created
                                        ? $notif->created->format('M d, Y h:i A')
                                        : '' ?>
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

                                        <?= $this->Html->link(
                                            '<i class="fas fa-eye"></i>',
                                            ['action' => 'viewRegistration', $notif->id],
                                            [
                                                'class' => 'btn btn-info btn-sm',
                                                'escape' => false,
                                                'title' => 'View Details'
                                            ]
                                        ) ?>

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

<form id="bulk-delete-form"
      method="post"
      action="<?= $this->Url->build(['action' => 'deleteSelected']) ?>">

    <input type="hidden" name="ids" id="selected-ids">

</form> -->
