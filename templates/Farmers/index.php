<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Farmers</h3>
             <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add Farmer','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="farmers-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Middlename</th>
                        <th>Birthdate</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="farmers-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($farmer,['id'=>'farmers-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <?= $this->Form->control('first_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="last-name">Last Name</label>
                    <?= $this->Form->control('last_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="middle-name">Middle Name</label>
                    <?= $this->Form->control('middle_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="birthdate">Birthdate</label>
                    <?= $this->Form->control('birthdate',['class'=>'form-control','label'=>false]) ?>
                    <label for="gender">Gender</label>
                    <?= $this->Form->control('gender',['class'=>'form-control',
                    'options'=>$this->Option->gender(),'label'=>false]) ?>
                    <label for="address">Address</label>
                    <?= $this->Form->control('address',['class'=>'form-control','label'=>false]) ?>
                    <label for="contact-no">Contact Number</label>
                    <?= $this->Form->control('contact_no',['class'=>'form-control','label'=>false]) ?>
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