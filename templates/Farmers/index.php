<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Beneficiaries</h3>
            <div class="card-tools d-flex align-items-center">
                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#excelUploadModal" data-placement="bottom" title="Upload Excel">
                    <i class="fas fa-file-excel mr-1"></i>
                    Upload Beneficiaries
                </button>
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
<div class="modal fade"
     id="importResultModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="importResultModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content import-result-modal">

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

            <div class="modal-body text-center">

                <h4 id="importResultModalLabel"
                    class="import-result-title">
                    Import Completed
                </h4>

                <p id="importResultMessage"
                   class="import-result-message">
                </p>

            </div>

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

    /*
     * =====================================================
     * GET CAKEPHP FLASH MESSAGE
     * =====================================================
     */

    var flashMessage = $('.message');

    /*
     * If CakePHP generated a success message
     */

    if (flashMessage.length) {

        var messageText = flashMessage.text().trim();

        /*
         * Determine message type
         */

        var isWarning =
            flashMessage.hasClass('warning') ||
            flashMessage.hasClass('alert-warning') ||
            flashMessage.find('.alert-warning').length > 0;

        var isError =
            flashMessage.hasClass('error') ||
            flashMessage.hasClass('alert-danger') ||
            flashMessage.find('.alert-danger').length > 0;


        /*
         * =================================================
         * SET MODAL CONTENT
         * =================================================
         */

        $('#importResultMessage').text(messageText);


        /*
         * =================================================
         * SUCCESS
         * =================================================
         */

        if (!isWarning && !isError) {

            $('#importResultModal')
                .removeClass('warning error');

            $('#importResultModal .import-result-icon')
                .html('<i class="fas fa-check"></i>');

            $('#importResultModalLabel')
                .text('Import Completed');
        }


        /*
         * =================================================
         * WARNING
         * =================================================
         */

        else if (isWarning) {

            $('#importResultModal')
                .removeClass('error')
                .addClass('warning');

            $('#importResultModal .import-result-icon')
                .html('<i class="fas fa-exclamation"></i>');

            $('#importResultModalLabel')
                .text('Data Already Uploaded');
        }


        /*
         * =================================================
         * ERROR
         * =================================================
         */

        else if (isError) {

            $('#importResultModal')
                .removeClass('warning')
                .addClass('error');

            $('#importResultModal .import-result-icon')
                .html('<i class="fas fa-times"></i>');

            $('#importResultModalLabel')
                .text('Import Failed');
        }


        /*
         * =================================================
         * SHOW MODAL
         * =================================================
         */

        $('#importResultModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        /*
         * =================================================
         * HIDE ORIGINAL FLASH MESSAGE
         * =================================================
         */

        flashMessage.hide();
    }

});
</script>