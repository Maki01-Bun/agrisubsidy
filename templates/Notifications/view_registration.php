<div class="col-12">

    <div class="card card-primary">

        <div class="card-header">
            <h3 class="card-title">
                Registration Details
            </h3>
        </div>

        <div class="card-body">

            <h5>User Information</h5>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Username</th>
                    <td><?= h($data['user']['username'] ?? '') ?></td>
                </tr>

                <tr>
                    <th>Role</th>
                    <td><?= h($data['user']['role'] ?? '') ?></td>
                </tr>

            </table>

            <h5 class="mt-4">
                Farmer Information
            </h5>

            <table class="table table-bordered">

                <?php foreach (($data['farmer'] ?? []) as $field => $value) : ?>

                    <tr>
                        <th width="30%">
                            <?= ucwords(str_replace('_', ' ', $field)) ?>
                        </th>
                        <td>
                            <?= h($value) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>

        </div>

        <div class="card-footer">

            <?= $this->Html->link(
                'Back',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary']
            ) ?>

            <?php if ($notification->status === 'pending') : ?>

                <?= $this->Form->postLink(
                    'Approve',
                    ['action' => 'approveRegistration', $notification->id],
                    [
                        'class' => 'btn btn-success',
                        'confirm' => 'Approve this registration?'
                    ]
                ) ?>

                <?= $this->Form->postLink(
                    'Decline',
                    ['action' => 'declineRegistration', $notification->id],
                    [
                        'class' => 'btn btn-danger',
                        'confirm' => 'Decline this registration?'
                    ]
                ) ?>

            <?php endif; ?>

        </div>

    </div>

</div>