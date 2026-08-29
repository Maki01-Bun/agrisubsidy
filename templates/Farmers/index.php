<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Beneficiaries</h3>
             <div class="card-tools d-flex align-items-center">
                <!-- Upload Excel -->
                <button type="button"class="btn btn-success mr-2" data-toggle="modal" data-target="#excelUploadModal" data-toggle="tooltip" data-placement="bottom" title="Upload Excel">
                    <i class="fas fa-file-excel mr-1"></i>
                    Upload Beneficiaries Excel
                </button>
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',['id' => 'add','class' => 'btn btn-primary','data-toggle' => 'tooltip',
                    'data-placement' => 'bottom','title' => 'Add Farmer','escape' => false])?>
            </div>
        </div>
        <div class="card-body">
            <table id="farmers-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Farmer Number</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Middlename</th>
                        <th>Birthdate</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Created</th>
                        <th>Modified</th>
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
                    <label for="farmer_no">Farmer Number</label>
                    <?= $this->Form->control('farmer_no',['class'=>'form-control','label'=>false]) ?>
                    <label for="first_name">First Name</label>
                    <?= $this->Form->control('first_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="last_name">Last Name</label>
                    <?= $this->Form->control('last_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="middle_name">Middle Name</label>
                    <?= $this->Form->control('middle_name',['class'=>'form-control','label'=>false]) ?>
                    <label class="form-label">Birthdate</label>
                    <?= $this->Form->control('birthdate', ['class' => 'form-control','type' => 'date','label' => false,'max' => date('Y-m-d', strtotime('-18 years'))]) ?>
                    <label for="gender">Gender</label>
                    <?= $this->Form->control('gender',['class'=>'form-control',
                    'options'=>$this->Option->gender(),'label'=>false]) ?>
                    <label for="address">Address</label>
                    <?= $this->Form->control('address',['class'=>'form-control','label'=>false]) ?>
                    <label for="contact_no">Contact Number</label>
                    <?= $this->Form->control('contact_no', ['class' => 'form-control','type' => 'text','maxlength' => 11,'minlength' => 11,
                    'placeholder' => '09XXXXXXXXX','label' => false]) ?>
                    <td><?= h($farmer->created) ?></td>
                    <td><?= h($farmer->modified) ?></td>    
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