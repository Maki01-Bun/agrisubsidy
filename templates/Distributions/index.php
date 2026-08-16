<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Distribution History</h3>
            
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add Distribution Data','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="distributions-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Subsidy Item</th>
                        <th>Quantity</th>
                        <th>Distribution Date</th>
                        <th>Received Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="distributions-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($distributions,['id'=>'distributions-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="farmer_id">Farmer</label>
                    <?= $this->Form->control('farmer_id', ['type' => 'select','options' => $farmers,
                    'empty' => '-- Select Farmer --','class' => 'form-control','label' => false]) ?>
                    <label for="subsidy_item">Subsidy Item</label>
                    <?= $this->Form->control('subsidy_item', [
                        'class' => 'form-control',
                        'label' => false,
                        'options' => $this->Option->subsidy(),
                        'empty' => '-- Enter Subsidy Item --'
                    ]) ?>
                    <label for="quantity">Quantity</label>
                    <?= $this->Form->control('quantity',['class'=>'form-control','label'=>false]) ?>
                    <label for="distribution_date">Distribution Date</label>
                    <?= $this->Form->control('distribution_date',['class'=>'form-control','label'=>false]) ?>
                    <label for="received_date">Received Date</label>
                    <?= $this->Form->control('received_date',['class'=>'form-control','label'=>false]) ?>
                    <label for="status">Status</label>
                    <?= $this->Form->control('status',['class'=>'form-control',
                    'options'=>$this->Option->status(),'label'=>false]) ?>
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