<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Schedules</h3>
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add Schedule','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="schedules-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Program Name</th>
                        <th>Subsidy Type</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="schedules-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($schedule,['id'=>'schedules-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="program_name">Program Name</label>
                    <?= $this->Form->control('program_name',['class'=>'form-control','label'=>false]) ?>
                   <label for="subsidy_type">Subsidy Type</label>
                    <?= $this->Form->control('subsidy_type', ['class' => 'form-control','label' => false,
                    'options' => $this->Option->subsidy(),'empty' => '-- Enter Subsidy Type --']) ?>
                    <label for="description">Description</label>
                    <?= $this->Form->control('description',['class'=>'form-control','label'=>false]) ?>
                    <label for="start_date">Start Date</label>
                    <?= $this->Form->control('start_date',['class'=>'form-control','label'=>false]) ?>
                    <label for="end_date">End Date</label>
                    <?= $this->Form->control('end_date',['class'=>'form-control','label'=>false]) ?>
                    <label for="start_time">Start Time</label>
                    <?= $this->Form->control('start_time',['class'=>'form-control','label'=>false]) ?>
                    <label for="end_time">End Time</label>
                    <?= $this->Form->control('end_time',['class'=>'form-control','label'=>false]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save & Notify Farmers</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>