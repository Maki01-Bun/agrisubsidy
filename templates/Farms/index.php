<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Farms</h3>
            <div class="card-tools d-flex align-items-center">
                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#excelUploadModal" data-placement="bottom" title="Upload Excel">
                    <i class="fas fa-file-excel mr-1"></i>
                    Upload Farms
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="farms-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Farm Name</th>
                        <th>Farm Size</th>
                        <th>Location</th>
                        <th>Average Yield</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="farms-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create(null,['id'=>'farms-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="farm_name">Farm Name</label>
                    <?= $this->Form->control('farm_name',['class'=>'form-control','label'=>false]) ?>
                </div>
                <div class="form-group">
                    <label for="farm_size">Farm Size</label>
                    <?= $this->Form->control('farm_size',['class'=>'form-control','label'=>false]) ?>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <?= $this->Form->control('location',['class'=>'form-control','label'=>false]) ?>
                </div>
                <div class="form-group">
                    <label for="crop_yield">Crop Yield</label>
                    <?= $this->Form->control('crop_yield',['class'=>'form-control','label'=>false]) ?>
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
<div class="modal fade" id="excelUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <?= $this->Form->create(null, [
                'url' => ['action' => 'uploadExcel'],
                'type' => 'file'
            ]) ?>

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel text-success"></i>
                    Upload Farms Informations
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Select Excel File</label>

                    <?= $this->Form->control('excel_file', [
                        'type' => 'file',
                        'label' => false,
                        'class' => 'form-control',
                        'accept' => '.xlsx,.xls'
                    ]) ?>
                </div>

                <small class="text-muted">
                    Accepted files: .xlsx and .xls
                </small>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Cancel
                </button>

                <button type="submit"
                        class="btn btn-success">
                    <i class="fas fa-upload"></i>
                    Upload & Import
                </button>

            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>
