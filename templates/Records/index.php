<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Distribution Record History</h3>
            <div class="card-tools d-flex align-items-center">
                <div class="dropdown status-dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle status-btn" type="button"
                    id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                        <span id="statusLabel">
                            Filter Status
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right status-menu" aria-labelledby="statusDropdown">
                        <!-- Header -->
                        <div class="dropdown-header status-header">
                            <span class="status-header-icon">
                                <i class="fas fa-tasks"></i>
                            </span>
                            <span>
                                Filter by Status
                            </span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <!-- Status Options -->
                        <?php foreach ($this->Option->filterStatus() as $value => $label): ?>
                            <a href="#" class="dropdown-item status-filter" data-value="<?= h($value) ?>">
                                <?php if ($value === 'Received'): ?>
                                    <span class="status-icon received">
                                        <i class="fas fa-check"></i>
                                    </span>
                                <?php elseif ($value === 'Cancelled'): ?>
                                    <span class="status-icon cancelled">
                                        <i class="fas fa-times"></i>
                                    </span>
                                <?php elseif ($value === 'Not Received'): ?>
                                    <span class="status-icon not-received">
                                        <i class="fas fa-exclamation"></i>
                                    </span>
                                <?php endif; ?>
                                <span class="status-text">
                                    <?= h($label) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>  
                        <div class="dropdown-divider"></div>    
                        <!-- Clear -->      
                        <a
                            href="#"
                            class="dropdown-item status-filter clear-status">
                            <span class="status-text">
                                Clear Filter
                            </span>
                        </a>    
                    </div>       
                </div>
                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#excelUploadModal" data-placement="bottom" title="Upload Excel">
                    <i class="fas fa-file-excel mr-1"></i>
                    Upload Records
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
                                Upload Records
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
            <table id="records-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Distribution Code</th>
                        <th>Subsidy Item</th>
                        <th>Received Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="records-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($records,['id'=>'records-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="farmer_id">Farmer</label>
                    <?= $this->Form->control('farmer_id', ['type' => 'select','options' => $farmers,
                    'empty' => '-- Select Farmer --','class' => 'form-control','label' => false]) ?>
                    <label for="subsidy_item">Subsidy Item</label>
                    <?= $this->Form->control('subsidy_item', ['type' => 'text', 'value' => 'Seed Subsidy',
                    'class' => 'form-control', 'label' => false, 'readonly' => true])?>
                    <label for="quantity">Quantity (bags)</label>
                    <?= $this->Form->control('quantity',['class'=>'form-control','label'=>false]) ?>
                    <label for="schedule-id">Schedule</label>
                    <?= $this->Form->control('schedule_id', [
                        'type' => 'select',
                        'options' => $schedules,
                        'empty' => '-- Select Schedule --',
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'schedule_id'
                    ]) ?>
                    <label for="distribution-date">Distribution Date</label>
                    <?= $this->Form->control('distribution_date', [
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'distribution_date',
                        'readonly' => true
                    ]) ?>
                    <label for="received-date">Received Date</label>
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
                    Upload Distribution Records
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
                        'accept' => '.xlsx,.xls',
                        'required' => true
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
<div class="modal fade" id="recordViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-comment-dots"></i>
                    Distribution Records Details
                </h5>
                <button type="button" 
                        class="close text-white" 
                        data-dismiss="modal">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Farmer Name</th>
                        <td id="view_farmer_name"></td>
                    </tr>
                    <tr>
                        <th width="30%">Distribution Code</th>
                        <td id="view_program_code"></td>
                    </tr>
                    <tr>
                        <th width="30%">Subsidy Item</th>
                        <td id="view_subsidy_item"></td>
                    </tr>
                    <tr>
                        <th>Quantity (bags)</th>
                        <td id="view_quantity">N/A</td>
                    </tr>
                    <tr>
                        <th>Distribution Date</th>
                        <td id="view_distribution_date">N/A</td>
                    </tr>
                    <tr>
                        <th>Received Date</th>
                        <td id="view_received_date">N/A</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="view_status">N/A</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" 
                        data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const scheduleData = <?= json_encode($scheduleData) ?>;

    const scheduleSelect = document.getElementById('schedule_id');
    const distributionDate = document.getElementById('distribution_date');

    scheduleSelect.addEventListener('change', function () {

        const scheduleId = this.value;

        const schedule = scheduleData.find(function (item) {
            return String(item.id) === String(scheduleId);
        });

        if (schedule && schedule.start_date) {
            distributionDate.value = schedule.start_date;
        } else {
            distributionDate.value = '';
        }
    });

});
</script>