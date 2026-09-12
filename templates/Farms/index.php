<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Farms</h3>
            <div class="card-tools d-flex align-items-center">
                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#excelUploadModal" data-placement="bottom" title="Upload Excel">
                    <i class="fas fa-file-excel mr-1"></i>
                    Upload Farms
                </button>
                 <?= $this->Html->link('<i class="fas fa-plus"></i>',['action' => 'add'],
                [ 'id' => 'add', 'class' => 'btn btn-primary', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Add Farm', 'escape' => false ])?>
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
                        <th>Average Yield (bags/ha)</th>
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
                    <label for="farmer-id">Farmer</label>
                    <?= $this->Form->control('farmer_id', ['type' => 'select', 'options' => $farmers,
                        'empty' => '-- Select Farmer --','class' => 'form-control','label' => false])?>
                </div>
                <div class="form-group">
                    <label for="farm-name">Farm Name</label>
                    <?= $this->Form->control('farm_name',['class'=>'form-control','label'=>false])?>
                </div>
                <div class="form-group">
                    <label for="farm_size">Farm Size</label>
                    <?= $this->Form->control('farm_size',['class'=>'form-control','label'=>false])?>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <?= $this->Form->control('location',['class'=>'form-control','label'=>false])?>
                </div>
                <div class="form-group">
                    <label for="average_yield">Average Yield (bags/ha)</label>
                    <?= $this->Form->control('average_yield',['class'=>'form-control','label'=>false])?>
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
<?php
$excelImportResult = $this->request
    ->getSession()
    ->consume('ExcelImportResult');
?>

<?php if (!empty($excelImportResult)): ?>

<div class="modal fade"
     id="excelImportResultModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="excelImportResultModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <?php
            /*
             * Determine modal type
             */
            $resultType =
                $excelImportResult['type'] ?? 'error';

            if ($resultType === 'partial') {

                $headerClass = 'bg-warning';
                $headerIcon = 'fa-exclamation-triangle';
                $headerTitle =
                    'Excel Import Completed with Issues';

            } else {

                $headerClass = 'bg-danger';
                $headerIcon = 'fa-times-circle';
                $headerTitle =
                    'Excel Import Failed';
            }
            ?>

            <div class="modal-header <?= $headerClass ?>">

                <h5 class="modal-title text-white"
                    id="excelImportResultModalLabel">

                    <i class="fas <?= $headerIcon ?> mr-2"></i>

                    <?= h($headerTitle) ?>

                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <!-- SUCCESS -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card border-success h-100 mb-0">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <i class="fas fa-check-circle
                                              text-success"
                                       style="font-size: 35px;">
                                    </i>
                                </div>
                                <h2 class="text-success mb-1">
                                    <?= h(
                                        $excelImportResult['success']
                                        ?? 0
                                    ) ?>
                                </h2>
                                <p class="text-muted mb-0">

                                    Successfully Imported
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- FAILED -->
                    <div class="col-md-6">
                        <div class="card border-danger
                                    h-100 mb-0">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <i class="fas fa-times-circle
                                              text-danger"
                                       style="font-size: 35px;">
                                    </i>
                                </div>
                                <h2 class="text-danger mb-1">
                                    <?= h(
                                        $excelImportResult['failed']
                                        ?? 0
                                    ) ?>
                                </h2>
                                <p class="text-muted mb-0">

                                    Failed / Duplicate Rows
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($resultType === 'partial'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle mr-2"></i>
                        Some records were imported successfully,
                        but some rows could not be uploaded because
                        they contain duplicate or invalid data.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        No records were imported because the uploaded
                        Excel file contains duplicate or invalid data.
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {

    $('#excelImportResultModal').modal('show');
});
</script>
<?php endif; ?>
