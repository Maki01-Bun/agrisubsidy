<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header text-white" style="background:#4E944F !important;">
            <h4 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Subsidy Effectiveness Evaluation Survey
            </h4>
        </div>
        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Feedbacks', 'action' => 'survey'],
            'id' => 'evaluations-form'
        ]) ?>
        <div class="card-body">
            <!-- BASIC INFORMATION -->
            <h5 class="text-success mb-3">
                <i class="fas fa-user me-2"></i>Basic Information
            </h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Farmer Name</label>
                    <?= $this->Form->control('farmerName', [
                        'value' => $farmerName ?? '',
                        'class' => 'form-control',
                        'label' => false,
                        'readonly' => true
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="schedule_id">Schedule</label>
                    <?= $this->Form->control('schedule_id', [
                        'type' => 'select',
                        'options' => $schedules,
                        'empty' => '-- Select Schedule --',
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'schedule_id'
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Farm</label>
                    <?= $this->Form->control('farm_id', [
                        'options' => $farms,
                        'empty' => '-- Select Farm --',
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'farm_id'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Crop Yield After (bags/ha)</label>
                    <?= $this->Form->control('crop_yield_after', [
                        'class' => 'form-control',
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Subsidy Received</label>
                    <?= $this->Form->control('subsidy_received', [
                        'type' => 'select',
                        'options' => [
                            'Yes' => 'Yes',
                            'No' => 'No'
                        ],  
                        'empty' => 'Select Yes or No',
                        'class' => 'form-control',
                        'label' => false
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Selling Price (₱/kg)</label>
                    <?= $this->Form->control('selling_price', [
                        'class' => 'form-control',
                        'label' => false
                    ]) ?>
                </div>
            </div>
            <hr>
            <!-- SURVEY -->
            <h5 class="text-success mb-3">
                <i class="fas fa-list-check me-2"></i>Survey Questionnaire
            </h5>
            <p class="text-muted">
                Please rate each statement by selecting one response.
            </p>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-success text-center">
                        <tr>
                            <th style="width:45%">Statement</th>
                            <th>1<br><small>Strongly Disagree</small></th>
                            <th>2<br><small>Disagree</small></th>
                            <th>3<br><small>Neutral</small></th>
                            <th>4<br><small>Agree</small></th>
                            <th>5<br><small>Strongly Agree</small></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $questions = [
                        'The subsidy improved my crop production.',
                        'The subsidy increased my farm income.',
                        'The subsidy was distributed on time.',
                        'The quality of the subsidy met my expectations.',
                        'The subsidy helped reduce farming expenses.',
                        'Overall, I am satisfied with the subsidy program.'
                    ];
                    foreach ($questions as $index => $question):
                    ?>
                        <tr>
                            <td><?= h($question) ?></td>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        name="q<?= $index + 1 ?>"
                                        value="<?= $i ?>"
                                        required>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <!-- COMMENT -->
            <div class="mb-4">
                <label class="fw-bold">
                    Additional Comments
                </label>
                <?= $this->Form->textarea('comment', [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Share your comments or suggestions...'
                ]) ?>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <?= $this->Form->hidden('id') ?>
            <button type="submit"
                    class="btn btn-success btn-lg px-5 rounded-pill">
                <i class="fas fa-paper-plane me-2"></i>
                Submit Evaluation
            </button>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>
                    Submission Successful
                </h5>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-check-circle text-success"
                   style="font-size:70px;"></i>
                <h4 class="mt-3">
                    Thank you!
                </h4>
                <p class="mb-0">
                    Your feedback has been submitted successfully.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success"
                        id="successOk">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>
<?php if ($this->request->getQuery('submitted')): ?>
<script>
$(document).ready(function () {
    $('#successModal').modal('show');
    $('#successOk').click(function () {
        window.location.href = "<?= $this->Url->build(['controller' => 'Schedules', 'action' => 'announcements']) ?>";
    });
});
</script>
<?php endif; ?>
