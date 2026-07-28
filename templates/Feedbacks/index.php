<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success   text-white">
            <h4 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Subsidy Effectiveness Evaluation Survey
            </h4>
        </div>
        <?= $this->Form->create(null, ['url' => ['controller' => 'Feedbacks', 'action' => 'survey'],
        'id' => 'evaluations-form']) ?>
        <div class="card-body">
            <h5 class="text-success mb-3">
                Beneficiary Information
            </h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Farmer Name</label>
                    <?= $this->Form->control('farmer_name', [
                        'class' => 'form-control',
                        'label' => false,
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Subsidy Type</label>
                    <?= $this->Form->control('subsidy_type', [
                        'class' => 'form-control',
                        'label' => false,
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Farm Size (ha)</label>
                    <?= $this->Form->control('farm_size', [
                        'class' => 'form-control',
                        'label' => false,
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Crop Yield Before</label>
                    <?= $this->Form->control('crop_yield_before', [
                        'class' => 'form-control',
                        'label' => false,
                    ]) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Crop Yield After</label>
                    <?= $this->Form->control('crop_yield_after', [
                        'class' => 'form-control',
                        'label' => false,
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Income Before</label>
                    <?= $this->Form->control('income_before', [
                        'class' => 'form-control',
                        'label' => false,
                    ]) ?>
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
                <label>Additional Comments</label>
                <?= $this->Form->textarea('comments', [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Share your comments or suggestions...'
                ]) ?>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Pest</label>
                    <?= $this->Form->control('pest', ['class' => 'form-control','label' => false
                    ]) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Calamity</label>
                    <?= $this->Form->control('calamity', [
                        'class' => 'form-control',
                        'label' => false
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <?= $this->Form->hidden('id') ?>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i>
                Submit Evaluation
            </button>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>