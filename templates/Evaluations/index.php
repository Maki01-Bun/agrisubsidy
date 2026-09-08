<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Evaluations</h3>
            <div class="card-tools evaluation-card-tools">
                <div class="dropdown effectiveness-dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle effectiveness-btn" type="button" id="effectivenessDropdown" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                        <span id="effectivenessLabel">
                            Filter Effectiveness
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right effectiveness-menu" aria-labelledby="effectivenessDropdown">
                        <!-- Header -->
                        <div class="dropdown-header effectiveness-header">
                            <span class="effectiveness-header-icon">
                                <i class="fas fa-chart-line"></i>
                            </span>

                            <span>
                                Filter by Effectiveness
                            </span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <!-- Effectiveness Options -->
                        <?php foreach ($this->Option->filterEffectiveness() as $value => $label): ?>
                            <a
                                href="#"
                                class="dropdown-item effectiveness-filter"
                                data-value="<?= h($value) ?>">
                                <?php if ($value === 'Effective'): ?>
                                    <span class="effectiveness-icon effective">
                                        <i class="fas fa-check"></i>
                                    </span>
                                <?php elseif ($value === 'Moderately Effective'): ?>
                                    <span class="effectiveness-icon moderate">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="effectiveness-icon not-effective">
                                        <i class="fas fa-times"></i>
                                    </span>
                                <?php endif; ?>
                                <span class="effectiveness-text">
                                    <?= h($label) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                        <div class="dropdown-divider"></div>
                        <!-- Clear -->
                        <a
                            href="#"
                            class="dropdown-item effectiveness-filter clear-effectiveness">
                            <span class="effectiveness-text">
                                Clear Filter
                            </span>
                        </a>
                    </div>
                </div>
                <!-- Download Button -->
                <a href="<?= $this->Url->build([ 'controller' => 'Evaluations', 'action' => 'downloadSummary' ]) ?>"
                class="btn btn-success evaluation-download-btn" title="Download Evaluation Summary">
                    <i class="fas fa-file-excel"></i>
                    <span>Download Evaluation Summary</span>
                </a>
            </div>
        </div>
        <div class="card-body"> 
            <table id="evaluations-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Farmer Name</th>
                        <th>Subsidy Type</th>
                        <th>Farm Size</th> 
                        <th>Yield After</th> 
                        <th>Effectiveness Label</th> 
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- View Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-comment-dots"></i>
                    Farmer Feedback Details
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
                        <td id="farmer_name"></td>
                    </tr>
                    <tr>
                        <th width="30%">Yield After (bags/ha)</th>
                        <td id="crop_yield_after">N/A</td>
                    </tr>
                    <tr>
                        <th>Rating</th>
                        <td id="feedback_rating"></td>
                    </tr>
                    <tr>
                        <th>Comments</th>
                        <td id="feedback_comments"></td>
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