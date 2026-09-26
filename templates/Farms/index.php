<div class="col-12">

    <div class="card card-primary">

        <!-- =========================================================
             CARD HEADER
        ========================================================== -->

        <div class="card-header">

            <h3 class="card-title">
                Farms
            </h3>


            <div class="card-tools d-flex align-items-center">

                <!-- =================================================
                     EXPORT / IMPORT DROPDOWN
                ================================================== -->

                <div class="dropdown mr-2 admin-actions-dropdown">

                    <button
                        type="button"
                        class="btn admin-dropdown-btn admin-btn-green dropdown-toggle"
                        id="farmsDropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >

                        <span class="admin-dropdown-btn-icon">
                            <i class="fas fa-tractor"></i>
                        </span>

                        <span class="admin-dropdown-btn-label">
                            Export/Import
                        </span>

                    </button>


                    <div
                        class="dropdown-menu dropdown-menu-right admin-dropdown-menu"
                        aria-labelledby="farmsDropdown"
                    >

                        <!-- =================================================
                             HEADER
                        ================================================== -->

                        <div class="admin-dropdown-header">

                            <div class="admin-dropdown-header-icon admin-icon-green">

                                <i class="fas fa-tractor"></i>

                            </div>


                            <div class="admin-dropdown-header-content">

                                <div class="admin-dropdown-header-title">
                                    Farm Management
                                </div>

                                <div class="admin-dropdown-header-subtitle">
                                    Import or export farm data
                                </div>

                            </div>

                        </div>


                        <div class="admin-dropdown-divider"></div>


                        <!-- =================================================
                             EXPORT
                        ================================================== -->

                        <div class="admin-dropdown-section">

                            <i class="fas fa-download"></i>

                            <span>
                                EXPORT
                            </span>

                        </div>


                        <!-- =================================================
                             DOWNLOAD FARMS
                        ================================================== -->

                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Farms',
                                'action' => 'downloadFarmsExcel'
                            ]) ?>"
                            class="admin-dropdown-action"
                        >

                            <div class="admin-dropdown-action-icon admin-icon-green">

                                <i class="fas fa-file-excel"></i>

                            </div>


                            <div class="admin-dropdown-action-content">

                                <div class="admin-dropdown-action-title">
                                    Download Farms
                                </div>

                                <div class="admin-dropdown-action-description">
                                    Export all farm records
                                </div>

                            </div>


                            <div class="admin-dropdown-action-arrow">

                                <i class="fas fa-chevron-right"></i>

                            </div>

                        </a>


                        <!-- =================================================
                             DOWNLOAD TEMPLATE
                        ================================================== -->

                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Farms',
                                'action' => 'downloadFarmExcelTemplate'
                            ]) ?>"
                            class="admin-dropdown-action"
                        >

                            <div class="admin-dropdown-action-icon admin-icon-blue">

                                <i class="fas fa-file-download"></i>

                            </div>


                            <div class="admin-dropdown-action-content">

                                <div class="admin-dropdown-action-title">
                                    Download Template
                                </div>

                                <div class="admin-dropdown-action-description">
                                    Get the Excel farm template
                                </div>

                            </div>


                            <div class="admin-dropdown-action-arrow">

                                <i class="fas fa-chevron-right"></i>

                            </div>

                        </a>


                        <div class="admin-dropdown-divider"></div>


                        <!-- =================================================
                             IMPORT
                        ================================================== -->

                        <div class="admin-dropdown-section">

                            <i class="fas fa-upload"></i>

                            <span>
                                IMPORT
                            </span>

                        </div>


                        <!-- =================================================
                             UPLOAD FARMS
                        ================================================== -->

                        <!--
                            IMPORTANT:
                            The data-target MUST match the modal ID below.
                            Both are now:
                            #farmExcelUploadModal
                        -->

                        <button
                            type="button"
                            class="admin-dropdown-action"
                            data-toggle="modal"
                            data-target="#farmExcelUploadModal"
                        >

                            <div class="admin-dropdown-action-icon admin-icon-orange">

                                <i class="fas fa-cloud-upload-alt"></i>

                            </div>


                            <div class="admin-dropdown-action-content">

                                <div class="admin-dropdown-action-title">
                                    Upload Farms
                                </div>

                                <div class="admin-dropdown-action-description">
                                    Import farms from Excel
                                </div>

                            </div>


                            <div class="admin-dropdown-action-arrow">

                                <i class="fas fa-chevron-right"></i>

                            </div>

                        </button>

                    </div>

                </div>


                <!-- =================================================
                     ADD FARM
                ================================================== -->

                <?= $this->Html->link(
                    '<i class="fas fa-plus"></i>',
                    ['action' => 'add'],
                    [
                        'id' => 'add',
                        'class' => 'btn btn-primary',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'title' => 'Add Farm',
                        'escape' => false
                    ]
                ) ?>

            </div>

        </div>


        <!-- =========================================================
             FARMS TABLE
        ========================================================== -->

        <div class="card-body">

            <table
                id="farms-table" class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>
                            LGU RSBSA Number
                        </th>

                        <th>
                            Farm Size
                        </th>

                        <th>
                            Location
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>


<!-- ================================================================
     ADD / EDIT FARM MODAL
================================================================ -->

<div
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
    id="farms-modal"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-light">

                <h4 class="modal-title"></h4>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <?= $this->Form->create(
                null,
                [
                    'id' => 'farms-form'
                ]
            ) ?>


            <div class="modal-body">

                <!-- FARMER -->

                <div class="form-group">

                    <label for="farmer-id">
                        LGU RSBSA Number
                    </label>


                    <?= $this->Form->control(
                        'farmer_id',
                        [
                            'type' => 'select',
                            'options' => $farmers,
                            'empty' => '-- Select Farmer Number --',
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'farmer-id'
                        ]
                    ) ?>

                </div>


                <!-- FARM SIZE -->

                <div class="form-group">

                    <label for="farm_size">
                        Farm Size
                    </label>


                    <?= $this->Form->control(
                        'farm_size',
                        [
                            'class' => 'form-control',
                            'label' => false
                        ]
                    ) ?>

                </div>


                <!-- LOCATION -->

                <div class="form-group">

                    <label for="location">
                        Location
                    </label>


                    <?= $this->Form->control(
                        'location',
                        [
                            'class' => 'form-control',
                            'label' => false
                        ]
                    ) ?>

                </div>

            </div>


            <div class="modal-footer">

                <?= $this->Form->control(
                    'id',
                    [
                        'type' => 'hidden',
                        'label' => false
                    ]
                ) ?>


                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    Close
                </button>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save
                </button>

            </div>


            <?= $this->Form->end() ?>

        </div>

    </div>

</div>


<!-- ================================================================
     FARM EXCEL UPLOAD MODAL
================================================================ -->

<div
    class="modal fade"
    id="farmExcelUploadModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="farmExcelUploadModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <!-- =====================================================
                 FORM
            ====================================================== -->

            <?= $this->Form->create(
                null,
                [
                    'url' => [
                        'controller' => 'Farms',
                        'action' => 'uploadExcel'
                    ],
                    'type' => 'file',
                    'id' => 'farmExcelUploadForm'
                ]
            ) ?>


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="farmExcelUploadModalLabel"
                >

                    <i class="fas fa-file-excel text-success mr-2"></i>

                    Upload Farm Information

                </h5>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <!-- =====================================================
                 BODY
            ====================================================== -->

            <div class="modal-body">

                <div class="form-group">

                    <label for="excel_file">

                        Select Excel File

                    </label>


                    <?= $this->Form->control(
                        'excel_file',
                        [
                            'type' => 'file',
                            'label' => false,
                            'class' => 'form-control',
                            'id' => 'excel_file',
                            'accept' => '.xlsx,.xls',
                            'required' => true
                        ]
                    ) ?>

                </div>


                <div class="alert alert-info mb-0">

                    <i class="fas fa-info-circle mr-1"></i>

                    <strong>Excel format:</strong>

                    <br>

                    The first row must contain the column headers.

                    <br><br>

                    <strong>Columns:</strong>

                    <ol class="mb-0 pl-3">

                        <li>
                            LGU RSBSA Number
                        </li>

                        <li>
                            Farm Name
                        </li>

                        <li>
                            Farm Size
                        </li>

                        <li>
                            Location
                        </li>

                    </ol>

                </div>


                <small class="text-muted d-block mt-2">

                    Accepted files:
                    <strong>.xlsx</strong>
                    and
                    <strong>.xls</strong>

                </small>

            </div>


            <!-- =====================================================
                 FOOTER
            ====================================================== -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >

                    <i class="fas fa-times mr-1"></i>

                    Cancel

                </button>


                <button
                    type="submit"
                    class="btn btn-success"
                    id="uploadFarmExcelButton"
                >

                    <i class="fas fa-upload mr-1"></i>

                    Upload & Import

                </button>

            </div>


            <?= $this->Form->end() ?>

        </div>

    </div>

</div>


<!-- ================================================================
     EXCEL IMPORT RESULT
================================================================ -->

<?php

$excelImportResult =
    $this->request
        ->getSession()
        ->consume('ExcelImportResult');

?>


<?php if (!empty($excelImportResult)): ?>

    <?php

    $resultType =
        $excelImportResult['type']
        ?? 'error';


    if ($resultType === 'success') {

        $headerClass =
            'bg-success';

        $headerIcon =
            'fa-check-circle';

        $headerTitle =
            'Excel Import Completed';

    } elseif ($resultType === 'partial') {

        $headerClass =
            'bg-warning';

        $headerIcon =
            'fa-exclamation-triangle';

        $headerTitle =
            'Excel Import Completed with Issues';

    } else {

        $headerClass =
            'bg-danger';

        $headerIcon =
            'fa-times-circle';

        $headerTitle =
            'Excel Import Failed';
    }

    ?>


    <div
        class="modal fade"
        id="excelImportResultModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="excelImportResultModalLabel"
        aria-hidden="true"
    >

        <div
            class="modal-dialog modal-lg modal-dialog-centered"
            role="document"
        >

            <div class="modal-content">


                <!-- =================================================
                     RESULT HEADER
                ================================================== -->

                <div
                    class="modal-header <?= h($headerClass) ?>"
                >

                    <h5
                        class="modal-title text-white"
                        id="excelImportResultModalLabel"
                    >

                        <i
                            class="fas <?= h($headerIcon) ?> mr-2"
                        ></i>

                        <?= h($headerTitle) ?>

                    </h5>


                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        aria-label="Close"
                    >

                        <span aria-hidden="true">
                            &times;
                        </span>

                    </button>

                </div>


                <!-- =================================================
                     RESULT BODY
                ================================================== -->

                <div class="modal-body">

                    <div class="row mb-4">


                        <!-- SUCCESS -->

                        <div class="col-md-6 mb-3 mb-md-0">

                            <div
                                class="card border-success h-100 mb-0"
                            >

                                <div
                                    class="card-body text-center"
                                >

                                    <div class="mb-2">

                                        <i
                                            class="fas fa-check-circle text-success"
                                            style="font-size:35px;"
                                        ></i>

                                    </div>


                                    <h2
                                        class="text-success mb-1"
                                    >

                                        <?= h(
                                            $excelImportResult[
                                                'success'
                                            ] ?? 0
                                        ) ?>

                                    </h2>


                                    <p
                                        class="text-muted mb-0"
                                    >
                                        Successfully Imported
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- FAILED -->

                        <div class="col-md-6">

                            <div
                                class="card border-danger h-100 mb-0"
                            >

                                <div
                                    class="card-body text-center"
                                >

                                    <div class="mb-2">

                                        <i
                                            class="fas fa-times-circle text-danger"
                                            style="font-size:35px;"
                                        ></i>

                                    </div>


                                    <h2
                                        class="text-danger mb-1"
                                    >

                                        <?= h(
                                            $excelImportResult[
                                                'failed'
                                            ] ?? 0
                                        ) ?>

                                    </h2>


                                    <p
                                        class="text-muted mb-0"
                                    >
                                        Failed / Duplicate Rows
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         MESSAGE
                    ================================================== -->

                    <?php if (
                        $resultType === 'success'
                    ): ?>

                        <div class="alert alert-success">

                            <i
                                class="fas fa-check-circle mr-2"
                            ></i>

                            All valid farm records were imported
                            successfully.

                        </div>

                    <?php elseif (
                        $resultType === 'partial'
                    ): ?>

                        <div class="alert alert-warning">

                            <i
                                class="fas fa-info-circle mr-2"
                            ></i>

                            Some records were imported successfully,
                            but some rows could not be uploaded because
                            they contain duplicate or invalid data.

                        </div>

                    <?php else: ?>

                        <div class="alert alert-danger">

                            <i
                                class="fas fa-exclamation-circle mr-2"
                            ></i>

                            No records were imported because the uploaded
                            Excel file contains duplicate or invalid data.

                        </div>

                    <?php endif; ?>


                    <!-- =================================================
                         ERRORS
                    ================================================== -->

                    <?php if (
                        !empty(
                            $excelImportResult['errors']
                            ?? []
                        )
                    ): ?>

                        <div class="mt-3">

                            <h6 class="font-weight-bold">

                                Import Details

                            </h6>


                            <div
                                class="border rounded"
                                style="
                                    max-height:250px;
                                    overflow-y:auto;
                                "
                            >

                                <ul class="list-group list-group-flush">

                                    <?php foreach (
                                        $excelImportResult['errors']
                                        as $error
                                    ): ?>

                                        <li
                                            class="list-group-item text-danger"
                                        >

                                            <i
                                                class="fas fa-exclamation-circle mr-2"
                                            ></i>

                                            <?= h($error) ?>

                                        </li>

                                    <?php endforeach; ?>

                                </ul>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- =================================================
                     FOOTER
                ================================================== -->

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >

                        <i class="fas fa-times mr-1"></i>

                        Close

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================
         SHOW RESULT MODAL
    ============================================================= -->

    <script>

    $(document).ready(function () {

        $('#excelImportResultModal').modal('show');

    });

    </script>

<?php endif; ?>


<!-- ================================================================
     UPLOAD BUTTON SCRIPT
================================================================ -->

<script>

$(document).ready(function () {

    /*
     * ============================================================
     * FARM EXCEL UPLOAD
     * ============================================================
     */

    $('#farmExcelUploadForm').on(
        'submit',
        function () {

            const fileInput =
                document.getElementById(
                    'excel_file'
                );


            if (
                !fileInput ||
                !fileInput.files ||
                fileInput.files.length === 0
            ) {

                alert(
                    'Please select an Excel file first.'
                );

                return false;
            }


            const file =
                fileInput.files[0];


            const fileName =
                file.name.toLowerCase();


            /*
             * Check extension.
             */

            if (
                !fileName.endsWith('.xlsx') &&
                !fileName.endsWith('.xls')
            ) {

                alert(
                    'Please upload only .xlsx or .xls files.'
                );

                return false;
            }


            /*
             * Disable button to prevent
             * accidental double submission.
             */

            const button =
                document.getElementById(
                    'uploadFarmExcelButton'
                );


            if (button) {

                button.disabled =
                    true;


                button.innerHTML =
                    '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                    ' Uploading...';

            }


            return true;

        }
    );


    /*
     * ============================================================
     * RESET FILE INPUT WHEN MODAL CLOSES
     * ============================================================
     */

    $('#farmExcelUploadModal').on(
        'hidden.bs.modal',
        function () {

            const form =
                document.getElementById(
                    'farmExcelUploadForm'
                );


            if (form) {

                form.reset();

            }


            const button =
                document.getElementById(
                    'uploadFarmExcelButton'
                );


            if (button) {

                button.disabled =
                    false;


                button.innerHTML =
                    '<i class="fas fa-upload mr-1"></i>' +
                    ' Upload & Import';

            }

        }
    );

});

</script>