<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Evaluations</h3>
            <div class="card-tools evaluation-card-tools">
                <!--<div class="dropdown effectiveness-dropdown">-->
                <!--    <button class="btn btn-outline-primary dropdown-toggle effectiveness-btn" type="button" id="effectivenessDropdown" data-toggle="dropdown"-->
                <!--    aria-haspopup="true" aria-expanded="false">-->
                <!--        <i class="fas fa-filter"></i>-->
                <!--        <span id="effectivenessLabel">-->
                <!--            Filter Subsidy Type-->
                <!--        </span>-->
                <!--    </button>-->
                <!--    <div class="dropdown-menu dropdown-menu-right subsidy-type-menu"-->
                <!--        aria-labelledby="subsidyTypeDropdown">-->
                <!--        <div class="dropdown-header subsidy-type-header">-->
                <!--            <span class="subsidy-type-header-icon">-->
                <!--                <i class="fas fa-seedling"></i>-->
                <!--            </span>-->

                <!--            <span>-->
                <!--                Filter by Subsidy Type-->
                <!--            </span>-->
                <!--        </div>-->
                <!--        <div class="dropdown-divider"></div>-->
                <!--        <a href="#"-->
                <!--        class="dropdown-item subsidy-type-filter"-->
                <!--        data-value="Corn Seeds">-->
                <!--            <span class="subsidy-type-text">-->
                <!--                Corn Seeds-->
                <!--            </span>-->
                <!--        </a>-->
                <!--        <a href="#"-->
                <!--        class="dropdown-item subsidy-type-filter"-->
                <!--        data-value="Rice Seeds">-->
                <!--            <span class="subsidy-type-text">-->
                <!--                Rice Seeds-->
                <!--            </span>-->
                <!--        </a>-->
                <!--        <div class="dropdown-divider"></div>-->
                <!--        <a href="#"-->
                <!--        class="dropdown-item subsidy-type-filter clear-subsidy-type">-->
                <!--            <span class="subsidy-type-icon">-->
                <!--                <i class="fas fa-times"></i>-->
                <!--            </span>-->
                <!--            <span class="subsidy-type-text">-->
                <!--                Clear Filter-->
                <!--            </span>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--</div> -->
                <!-- Download Button-->
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
                        <th>Program Name</th>
                        <th>Farm Size (ha)</th> 
                        <th>Yield After (tons/ha)</th> 
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
                        <th width="30%">Yield After (tons/ha)</th>
                        <td id="crop_yield_after">N/A</td>
                    </tr>
                    <tr>
                        <th>Selling Price (₱/bags)</th>
                        <td id="selling_price"></td>
                    </tr>
                    <tr>
                        <th>Subsidy Received</th>
                        <td id="subsidy_received"></td>
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