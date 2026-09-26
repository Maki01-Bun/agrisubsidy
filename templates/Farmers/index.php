<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Beneficiaries</h3>
            <div class="card-tools d-flex align-items-center">
                <div class="dropdown admin-actions-dropdown">
                    <button
                        type="button"
                        class="btn admin-dropdown-btn admin-btn-green dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="admin-dropdown-btn-icon">
                            <i class="fas fa-users"></i>
                        </span>

                        <span class="admin-dropdown-btn-label">
                            Export/Import
                        </span>
                    </button>


                    <div class="dropdown-menu dropdown-menu-right admin-dropdown-menu">

                        <!-- HEADER -->

                        <div class="admin-dropdown-header">

                            <div class="admin-dropdown-header-icon">
                                <i class="fas fa-users"></i>
                            </div>

                            <div class="admin-dropdown-header-content">

                                <div class="admin-dropdown-header-title">
                                    Beneficiary Management
                                </div>

                                <div class="admin-dropdown-header-subtitle">
                                    Import or export farmer data
                                </div>

                            </div>

                        </div>


                        <div class="admin-dropdown-divider"></div>


                        <!-- EXPORT -->

                        <div class="admin-dropdown-section">
                            <i class="fas fa-download"></i>
                            <span>EXPORT</span>
                        </div>


                        <?= $this->Html->link(
                            '
                            <div class="admin-dropdown-action-icon admin-icon-blue">
                                <i class="fas fa-file-excel"></i>
                            </div>

                            <div class="admin-dropdown-action-content">
                                <div class="admin-dropdown-action-title">
                                    Download Farmers
                                </div>

                                <div class="admin-dropdown-action-description">
                                    Export all farmer records
                                </div>
                            </div>

                            <div class="admin-dropdown-action-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                            ',
                            [
                                'action' => 'downloadFarmersExcel'
                            ],
                            [
                                'class' => 'admin-dropdown-action',
                                'escape' => false
                            ]
                        ) ?>


                        <?= $this->Html->link(
                            '
                            <div class="admin-dropdown-action-icon admin-icon-green">
                                <i class="fas fa-file-download"></i>
                            </div>

                            <div class="admin-dropdown-action-content">
                                <div class="admin-dropdown-action-title">
                                    Download Template
                                </div>

                                <div class="admin-dropdown-action-description">
                                    Get the official Farmer Excel import template
                                </div>
                            </div>

                            <div class="admin-dropdown-action-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                            ',
                            [
                                'action' => 'downloadExcelTemplate'
                            ],
                            [
                                'class' => 'admin-dropdown-action',
                                'escape' => false
                            ]
                        ) ?>


                        <div class="admin-dropdown-divider"></div>
                        <!-- IMPORT -->
                        <div class="admin-dropdown-section">
                            <i class="fas fa-upload"></i>
                            <span>IMPORT</span>
                        </div>


                        <button
                            type="button"
                            class="admin-dropdown-action"
                            data-toggle="modal"
                            data-target="#excelUploadModal">

                            <div class="admin-dropdown-action-icon admin-icon-orange">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>

                            <div class="admin-dropdown-action-content">

                                <div class="admin-dropdown-action-title">
                                    Upload Farmer Excel
                                </div>

                                <div class="admin-dropdown-action-description">
                                    Import farmers from Excel
                                </div>

                            </div>

                            <div class="admin-dropdown-action-arrow">
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
                                Upload Farmer Excel
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
                                    'label' => false, 'required' => true, 'accept' => '.xlsx'])?>
                                    <small class="form-text text-muted">
                                        Only the official Farmer Excel template (.xlsx) is accepted.
                                    </small>
                                    <div class="alert alert-warning mt-3 mb-0" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        <strong>Important:</strong>
                                        Only the official Farmer Excel format is accepted.
                                        Other Excel files, including Farms Excel files, will be rejected.
                                    </div>
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
                    <label for="farmer_no">LGU RSBSA Number</label>
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
                    Upload Farmer Excel
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
                        'accept' => '.xlsx'
                    ]) ?>

                    <small class="form-text text-muted">

                        Only the official <strong>Farmer Excel template (.xlsx)</strong> is accepted.
                    </small>
                    <div class="alert alert-warning mt-3 mb-0" role="alert">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Important:</strong>
                        Only the official Farmer Excel format is accepted.
                        Other Excel files, including Farms Excel files, will be rejected.
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="submit"
                        class="btn btn-success">
                    <i class="fas fa-upload mr-1"></i>
                    Upload Farmer Excel
                </button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?php
/*
 * =============================================================
 * RECORD API URL
 * =============================================================
 *
 * CakePHP generates the correct application URL.
 *
 * Example:
 * /Records/getRecord
 *
 * JavaScript will append:
 * /250
 *
 * Result:
 * /Records/getRecord/250
 *
 */
?>

<script>
    window.RECORD_GET_URL =
        <?= json_encode(
            $this->Url->build([
                'controller' => 'Records',
                'action' => 'getRecord'
            ])
        ) ?>;
</script>


<!-- =========================================================
     FARMER DISTRIBUTION RECORD MODAL
========================================================== -->

<div class="modal fade"
     id="viewRecordModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="viewRecordModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-xl"
         role="document">

        <div class="modal-content">


            <!-- =================================================
                 HEADER
            ================================================== -->

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


            <!-- =================================================
                 BODY
            ================================================== -->

            <div class="modal-body">


                <!-- =============================================
                     FARMER INFORMATION
                ============================================== -->

                <div id="farmerInformation"
                     class="mb-3">

                    <div class="text-center py-3">

                        <i class="fas fa-spinner fa-spin mr-2"></i>

                        Loading farmer information...

                    </div>

                </div>


                <!-- =============================================
                     DISTRIBUTION RECORDS
                ============================================== -->

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
                                    Quantity (tons)
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

                                    <i class="fas
                                              fa-spinner
                                              fa-spin
                                              mr-2"></i>

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

    /* ============================================================
       FARMER EXCEL FILE SELECTION
    ============================================================ */

    $(document).on(
        'change.farmerExcel',
        '#excelUploadForm input[type="file"]',
        function () {

            const fileInput = $(this);

            const file =
                fileInput[0] &&
                fileInput[0].files &&
                fileInput[0].files[0];

            if (!file) {
                return;
            }

            const filename =
                String(file.name || '').toLowerCase();

            if (!filename.endsWith('.xlsx')) {

                alert(
                    'Wrong Excel Format\n\n' +
                    'Please select only the official Farmer Excel template (.xlsx).\n\n' +
                    'Other Excel files will not be accepted.'
                );

                fileInput.val('');

                return;
            }
        }
    );


    /* ============================================================
       IMPORT RESULT
    ============================================================ */

    <?php if (!empty($importResult)): ?>

    const importResult =
        <?= json_encode($importResult) ?>;

    const resultType =
        importResult.type || 'success';

    const resultTitle =
        importResult.title || 'Import Completed';

    const resultMessage =
        importResult.message || '';

    const successCount =
        parseInt(
            importResult.success || 0,
            10
        );

    const duplicateCount =
        parseInt(
            importResult.duplicate || 0,
            10
        );

    const failedCount =
        parseInt(
            importResult.failed || 0,
            10
        );

    $('#importResultModalLabel')
        .text(resultTitle);

    $('#importResultMessage')
        .text(resultMessage);

    $('#importSuccessCount')
        .text(successCount);

    $('#importDuplicateCount')
        .text(duplicateCount);

    $('#importFailedCount')
        .text(failedCount);

    if (resultType === 'error') {

        $('#importResultSummary')
            .hide();

    } else {

        $('#importResultSummary')
            .show();
    }

    $('.import-result-modal')
        .removeClass(
            'result-success result-warning result-error'
        );

    $('.import-ok-btn')
        .removeClass(
            'btn-success btn-warning btn-danger'
        );

    if (resultType === 'success') {

        $('.import-result-modal')
            .addClass('result-success');

        $('.import-result-icon')
            .html(
                '<i class="fas fa-check"></i>'
            );

        $('.import-ok-btn')
            .addClass('btn-success');

    }
    else if (resultType === 'warning') {

        $('.import-result-modal')
            .addClass('result-warning');

        $('.import-result-icon')
            .html(
                '<i class="fas fa-exclamation-triangle"></i>'
            );

        $('.import-ok-btn')
            .addClass('btn-warning');

    }
    else {

        $('.import-result-modal')
            .addClass('result-error');

        $('.import-result-icon')
            .html(
                '<i class="fas fa-times-circle"></i>'
            );

        $('.import-ok-btn')
            .addClass('btn-danger');
    }

    $('#importResultModal').modal({
        backdrop: 'static',
        keyboard: false
    });

    <?php endif; ?>
});
</script>