<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Evaluations</h3>
            
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add Evaluation','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="evaluations-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Farm Size</th> 
                        <th>Yield Before</th> 
                        <th>Yield After</th> 
                        <th>Income Before</th> 
                        <th>Income After</th> 
                        <th>Pest</th> 
                        <th>Calamity</th> 
                        <th>Effectiveness Label</th> 
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="evaluations-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($evaluation,['id'=>'evaluations-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="farmer_name">Farmer Name</label>
                    <input type="text" id="farmer_name" class="form-control" readonly>
                    <label for="subsidy_type">Subsidy Type</label>
                    <input type="text" id="subsidy_type" class="form-control" readonly>
                    <label for="farm_size">Farm Size</label>
                    <?= $this->Form->control('farm_size',['class'=>'form-control','label'=>false]) ?>
                    <label for="crop_yield_before">Crop Yield Before</label>
                    <?= $this->Form->control('crop_yield_before',['class'=>'form-control','label'=>false]) ?>
                    <label for="crop_yield_after">Crop Yield After</label>
                    <?= $this->Form->control('crop_yield_after',['class'=>'form-control','label'=>false]) ?>
                    <label for="income_before">Income Before</label>
                    <?= $this->Form->control('income_before',['class'=>'form-control','label'=>false]) ?>
                    <label for="income_after">Income After</label>
                    <?= $this->Form->control('income_after',['class'=>'form-control','label'=>false]) ?>
                    <label for="pest">Pest</label>
                    <?= $this->Form->control('pest',['class'=>'form-control','label'=>false]) ?>
                    <label for="calamity">Calamity</label>
                    <?= $this->Form->control('calamity',['class'=>'form-control','label'=>false]) ?>
                    <label for="effectiveness_label">Effectiveness Label</label>
                    <?= $this->Form->control('effectiveness_label', ['class' => 'form-control','label' => false,'disabled' => true]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>