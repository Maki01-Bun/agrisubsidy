<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Beneficiaries</h3>
            <div class="card-tools d-flex align-items-center">
                <div class="dropdown mr-2 beneficiary-actions">
                    <button
                        type="button"
                        class="btn btn-success beneficiary-dropdown-btn dropdown-toggle"
                        id="beneficiariesDropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="beneficiary-btn-icon">
                            <i class="fas fa-users"></i>
                        </span>
                        <span class="beneficiary-btn-label">Beneficiaries</span>
                    </button>

                    <div
                        class="dropdown-menu dropdown-menu-right beneficiary-dropdown-menu"
                        aria-labelledby="beneficiariesDropdown"
                    >

                        <!-- HEADER -->
                        <div class="beneficiary-menu-header">
                            <div class="beneficiary-header-icon">
                                <i class="fas fa-users"></i>
                            </div>

                            <div class="beneficiary-header-content">
                                <div class="beneficiary-header-title">
                                    Beneficiary Management
                                </div>

                                <div class="beneficiary-header-subtitle">
                                    Import or export beneficiary data
                                </div>
                            </div>
                        </div>

                        <div class="beneficiary-divider"></div>

                        <!-- EXPORT -->
                        <div class="beneficiary-section-label">
                            <i class="fas fa-download"></i>
                            <span>EXPORT</span>
                        </div>


                        <!-- DOWNLOAD BENEFICIARIES -->
                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Farmers',
                                'action' => 'downloadFarmersExcel'
                            ]) ?>"
                            class="beneficiary-link"
                        >
                            <div class="beneficiary-link-icon beneficiary-excel-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>

                            <div class="beneficiary-link-content">
                                <div class="beneficiary-link-title">
                                    Download Beneficiaries
                                </div>

                                <div class="beneficiary-link-description">
                                    Export all beneficiary records
                                </div>
                            </div>

                            <div class="beneficiary-link-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>


                        <!-- DOWNLOAD TEMPLATE -->
                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Farmers',
                                'action' => 'downloadExcelTemplate'
                            ]) ?>"
                            class="beneficiary-link"
                        >
                            <div class="beneficiary-link-icon beneficiary-template-icon">
                                <i class="fas fa-file-download"></i>
                            </div>

                            <div class="beneficiary-link-content">
                                <div class="beneficiary-link-title">
                                    Download Template
                                </div>

                                <div class="beneficiary-link-description">
                                    Get the Excel import template
                                </div>
                            </div>

                            <div class="beneficiary-link-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>


                        <div class="beneficiary-divider"></div>


                        <!-- IMPORT -->
                        <div class="beneficiary-section-label">
                            <i class="fas fa-upload"></i>
                            <span>IMPORT</span>
                        </div>


                        <!-- UPLOAD -->
                        <button
                            type="button"
                            class="beneficiary-upload"
                            data-toggle="modal"
                            data-target="#excelUploadModal"
                        >
                            <div class="beneficiary-link-icon beneficiary-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>

                            <div class="beneficiary-link-content">
                                <div class="beneficiary-link-title">
                                    Upload Beneficiaries
                                </div>

                                <div class="beneficiary-link-description">
                                    Import beneficiaries from Excel
                                </div>
                            </div>

                            <div class="beneficiary-link-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </button>

                    </div>
                </div>
                <?= $this->Html->link('<i class="fas fa-plus"></i>',['action' => 'add'],
                [ 'id' => 'add', 'class' => 'btn btn-primary', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Add Record', 'escape' => false ])?>
            </div>
            <div class="modal fade" id="excelUploadModal" tabindex="-1" role="dialog" aria-labelledby="excelUploadModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="excelUploadModalLabel">
                                <i class="fas fa-file-excel mr-2"></i>
                                Upload Beneficiaries
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?= $this->Form->create(null, ['url' => ['action' => 'uploadExcel'], 'type' => 'file', 'id' => 'excelUploadForm'])?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="excel_file">
                                        Select Excel File
                                    </label>
                                    <?= $this->Form->control('excel_file', ['type' => 'file', 'class' => 'form-control',
                                    'label' => false, 'required' => true, 'accept' => '.xlsx,.xls'])?>
                                    <small class="form-text text-muted">
                                        Please upload an Excel file (.xlsx or .xls).
                                    </small>
                                </div>
                            </div>
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i>
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload mr-1"></i>
                                    Upload
                                </button>
                            </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="farmers-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>LGU RSBSA Number</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Middlename</th>
                        <th>Birthdate</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Created</th>
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
<div class="modal fade"
     id="excelUploadModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="excelUploadModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"
         role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, [
                'url' => ['action' => 'uploadExcel'],
                'type' => 'file',
                'id' => 'excelUploadForm'
            ]) ?>
            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title"
                    id="excelUploadModalLabel">
                    <i class="fas fa-file-excel mr-2"></i>
                    Upload Beneficiaries
                </h5>
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body">
                <div class="form-group">
                    <label for="excel_file">
                        Select Excel File
                    </label>
                    <?= $this->Form->control('excel_file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'label' => false,
                        'required' => true,
                        'accept' => '.xlsx,.xls'
                    ]) ?>

                    <small class="form-text text-muted">

                        Accepted files:
                        <strong>.xlsx</strong> and
                        <strong>.xls</strong>
                    </small>
                </div>
            </div>
            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="submit"
                        class="btn btn-success">
                    <i class="fas fa-upload mr-1"></i>
                    Upload & Import
                </button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<div class="modal fade"
     id="viewRecordModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="viewRecordModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl"
         role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"
                    id="viewRecordModalLabel">
                    <i class="fas fa-user mr-2"></i>
                    Farmer Distribution Records
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
                <div id="farmerInformation"
                     class="mb-3">
                    <div class="text-center py-3">
                        <i class="fas fa-spinner fa-spin mr-2"></i>

                        Loading farmer information...
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    Program
                                </th>
                                <th>
                                    Subsidy Item
                                </th>
                                <th>
                                    Quantity
                                </th>
                                <th>
                                    Distribution Date
                                </th>
                                <th>
                                    Received Date
                                </th>
                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody id="distributionRecordsBody">
                            <tr>
                                <td colspan="6"
                                    class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Loading records...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$importResult = $this->request->getSession()->consume('ExcelImportResult');
?>

<div class="modal fade"
     id="importResultModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="importResultModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content import-result-modal">

            <!-- HEADER -->
            <div class="modal-header import-result-header">

                <div class="import-result-icon">
                    <i class="fas fa-check"></i>
                </div>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body text-center">

                <h4 id="importResultModalLabel"
                    class="import-result-title">
                    Import Completed
                </h4>

                <p id="importResultMessage"
                   class="import-result-message">
                </p>

                <!-- IMPORT SUMMARY -->
                <div id="importResultSummary"
                     class="import-result-summary mt-3"
                     style="display:none;">

                    <div class="row">

                        <div class="col-4">
                            <div class="import-summary-box uploaded">
                                <div class="summary-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>

                                <div class="summary-number"
                                     id="importSuccessCount">
                                    0
                                </div>

                                <div class="summary-label">
                                    Uploaded
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="import-summary-box duplicate">
                                <div class="summary-icon">
                                    <i class="fas fa-copy"></i>
                                </div>

                                <div class="summary-number"
                                     id="importDuplicateCount">
                                    0
                                </div>

                                <div class="summary-label">
                                    Already Uploaded
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="import-summary-box failed">
                                <div class="summary-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>

                                <div class="summary-number"
                                     id="importFailedCount">
                                    0
                                </div>

                                <div class="summary-label">
                                    Failed
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer justify-content-center">

                <button type="button"
                        class="btn btn-success import-ok-btn"
                        data-dismiss="modal">

                    <i class="fas fa-check mr-1"></i>
                    OK

                </button>

            </div>

        </div>

    </div>

</div>
<script>
$(document).ready(function () {

    <?php if (!empty($importResult)): ?>

        var importResult = <?= json_encode($importResult) ?>;

        /*
         * ============================================
         * GET IMPORT RESULT
         * ============================================
         */

        var resultType = importResult.type || 'success';
        var resultTitle = importResult.title || 'Import Completed';
        var resultMessage = importResult.message || '';

        var successCount = parseInt(importResult.success || 0);
        var duplicateCount = parseInt(importResult.duplicate || 0);
        var failedCount = parseInt(importResult.failed || 0);


        /*
         * ============================================
         * SET MESSAGE
         * ============================================
         */

        $('#importResultModalLabel').text(resultTitle);

        $('#importResultMessage').text(resultMessage);


        /*
         * ============================================
         * SET COUNTS
         * ============================================
         */

        $('#importSuccessCount').text(successCount);
        $('#importDuplicateCount').text(duplicateCount);
        $('#importFailedCount').text(failedCount);

        $('#importResultSummary').show();


        /*
         * ============================================
         * RESET MODAL CLASSES
         * ============================================
         */

        $('.import-result-modal')
            .removeClass('result-success result-warning result-error');


        /*
         * ============================================
         * SUCCESS
         * ============================================
         */

        if (resultType === 'success') {

            $('.import-result-modal')
                .addClass('result-success');

            $('.import-result-icon')
                .html('<i class="fas fa-check"></i>');

            $('.import-ok-btn')
                .removeClass('btn-warning btn-danger')
                .addClass('btn-success');
        }


        /*
         * ============================================
         * WARNING / ALREADY UPLOADED
         * ============================================
         */

        else if (resultType === 'warning') {

            $('.import-result-modal')
                .addClass('result-warning');

            $('.import-result-icon')
                .html('<i class="fas fa-exclamation-triangle"></i>');

            $('.import-ok-btn')
                .removeClass('btn-success btn-danger')
                .addClass('btn-warning');
        }


        /*
         * ============================================
         * ERROR
         * ============================================
         */

        else {

            $('.import-result-modal')
                .addClass('result-error');

            $('.import-result-icon')
                .html('<i class="fas fa-times"></i>');

            $('.import-ok-btn')
                .removeClass('btn-success btn-warning')
                .addClass('btn-danger');
        }


        /*
         * ============================================
         * SHOW RESULT MODAL
         * ============================================
         */

        $('#importResultModal').modal({
            backdrop: 'static',
            keyboard: false
        });

    <?php endif; ?>

});
</script>