<div class="container mt-4">
    <div class="card shadow">
       <div class="card-header" style="background:#198754;">
            <h4 class="mb-0" style="color:white;">
                <i class="fas fa-clipboard-check me-2"></i>
                Subsidy Effectiveness Evaluation Survey
            </h4>
        </div>
        <?= $this->Form->create(null, ['url' => ['controller' => 'Feedbacks', 'action' => 'survey'],
        'id' => 'evaluations-form']) ?>
        <div class="card-body">
            <h5 class="text-success mb-3">
                Feedback Questions
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Farmer Name</label>
                    <?= $this->Form->control('farmerName', ['value' => $farmerName ?? '','class' => 'form-control',
                    'label' => false,'readonly' => true,]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Subsidy Type</label>
                    <?= $this->Form->control('subsidy_type', ['class' => 'form-control',
                        'label' => false,]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Farm Size (ha)</label>
                    <?= $this->Form->control('farm_size', ['class' => 'form-control',
                        'label' => false,]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Crop Yield Before</label>
                    <?= $this->Form->control('crop_yield_before', ['class' => 'form-control','label' => false,]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Crop Yield After</label>
                    <?= $this->Form->control('crop_yield_after', ['class' => 'form-control','label' => false,]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Income Before</label>
                    <?= $this->Form->control('income_before', ['class' => 'form-control','label' => false,]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Income After</label>
                    <?= $this->Form->control('income_after', ['class' => 'form-control','label' => false,]) ?>
                </div>
            </div>
            <hr>
            <h5 class="text-success">
                Survey Questionnaire
            </h5>
            <p class="text-muted">
                Please rate each statement by selecting one response.
            </p>
            <table class="table table-bordered table-hover">
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
                foreach ($questions as $index => $question):?>
                    <tr>
                        <td><?= h($question) ?></td>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <td class="text-center">
                                <input type="radio" name="q<?= $index + 1 ?>" value="<?= $i ?>"required>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="form-group mt-4">
                <label>Comment</label>
                <?= $this->Form->textarea('comment', [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Share your comments or suggestions...',
                    'required' => false
                ]) ?>
            </div>
            <hr>
            <div class="row g-4">          
                <!-- Pest -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <label class="form-label fw-bold text-danger mb-2">
                                <i class="fas fa-bug me-2"></i>Pest Experienced
                            </label>
                            <?= $this->Form->control('pest', ['class' => 'form-control form-control-lg',
                            'label' => false,'placeholder' => 'Enter pest experienced'])?>
                        </div>
                    </div>
                </div>
                <!-- Calamity -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <label class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-cloud-showers-heavy me-2"></i>Calamity Experienced
                            </label>
                            <?= $this->Form->control('calamity', ['class' => 'form-control form-control-lg',
                            'label' => false,'placeholder' => 'Enter calamity experienced']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 text-end pt-4">
                <?= $this->Form->hidden('id') ?>
                <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm">
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
        window.location.href = "<?= $this->Url->build(['controller' => 'Programs', 'action' => 'announcements']) ?>";
    });
});
</script>
<?php endif; ?>
