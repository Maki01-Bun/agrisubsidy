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
                        <th width="30%">Pest</th>
                        <td id="feedback_pest">N/A</td>
                    </tr>
                    <tr>
                        <th width="30%">Yield After</th>
                        <td id="crop_yield_after">N/A</td>
                    </tr>
                    <tr>
                        <th>Calamity</th>
                        <td id="feedback_calamity">N/A</td>
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

<script>

function viewFeedback(evaluationId)
{
    // Reset modal
    $('#feedbackContent').hide();
    $('#feedbackError').hide();
    $('#feedbackLoading').show();

    // Show modal
    $('#feedbackModal').modal('show');


    $.ajax({

        url: "<?= $this->Url->build([
            'controller' => 'Evaluations',
            'action' => 'getFeedback'
        ]) ?>/" + evaluationId,

        type: 'GET',

        dataType: 'json',

        success: function(response)
        {
            $('#feedbackLoading').hide();

            if (response.success) {

                $('#farmer_name').text(
                    response.data.farmer_name || 'N/A'
                );
                $('#feedback_pest').text(
                    response.data.pest || 'None'
                );

                $('#crop_yield_after').text(
                    response.data.crop_yield_after || 'None'
                );

                $('#feedback_calamity').text(
                    response.data.calamity || 'None'
                );

                $('#farmer_number').text(
                    response.data.farmer_number || 'N/A'
                );

                $('#feedback_rating').html(
                    '<span class="badge badge-primary" ' +
                    'style="font-size:16px;">' +
                    (response.data.feedback_rating || 'N/A') +
                    '</span>'
                );

                $('#feedback_comments').text(
                    response.data.comments || 'No comments provided.'
                );


                $('#feedbackContent').show();

            } else {

                $('#feedbackErrorMessage').text(
                    response.message || 'Feedback not found.'
                );

                $('#feedbackError').show();
            }
        },

        error: function(xhr)
        {
            $('#feedbackLoading').hide();

            console.log(xhr.responseText);

            $('#feedbackErrorMessage').text(
                'Unable to load feedback. Please try again.'
            );

            $('#feedbackError').show();
        }

    });
}

$(function () {

    /*
     * Select effectiveness
     */
    $(document).on('click', '.effectiveness-filter', function (e) {

        e.preventDefault();

        var value = $(this).data('value');

        if (value) {

            // Update button text
            $('#effectivenessLabel').text(value);

            // Store selected value
            $('#effectivenessDropdown').attr(
                'data-effectiveness',
                value
            );

            console.log('Selected Effectiveness:', value);

            /*
             * If you are using DataTables:
             *
             * Replace "evaluationTable" with your
             * actual DataTable variable.
             */

            if (typeof evaluationTable !== 'undefined') {

                evaluationTable
                    .column( /* EFFECTIVENESS COLUMN */ )
                    .search(value)
                    .draw();

            }

        }

    });

    $(document).on('click', '.clear-effectiveness', function (e) {

        e.preventDefault();

        $('#effectivenessLabel').text(
            'Filter Effectiveness'
        );

        $('#effectivenessDropdown').removeAttr(
            'data-effectiveness'
        );

        console.log('Effectiveness filter cleared');

        if (typeof evaluationTable !== 'undefined') {

            evaluationTable
                .column( /* EFFECTIVENESS COLUMN */ )
                .search('')
                .draw();

        }

    });

});
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | EFFECTIVENESS FILTER
    |--------------------------------------------------------------------------
    */

    $('.effectiveness-filter').on('click', function (e) {

        e.preventDefault();

        var $filter = $(this);

        /*
        |--------------------------------------------------------------------------
        | CLEAR FILTER
        |--------------------------------------------------------------------------
        */

        if ($filter.hasClass('clear-effectiveness')) {

            // Show every evaluation
            $('.evaluation-row').show();

            // Reset button text
            $('#effectivenessLabel').text('Filter Effectiveness');

            // Remove active state
            $('.effectiveness-filter')
                .removeClass('active');

            // Close dropdown
            $('#effectivenessDropdown')
                .attr('aria-expanded', 'false');

            $('.effectiveness-menu')
                .removeClass('show');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | GET SELECTED VALUE
        |--------------------------------------------------------------------------
        */

        var selectedValue = $filter.attr('data-value');


        /*
        |--------------------------------------------------------------------------
        | FILTER ROWS
        |--------------------------------------------------------------------------
        */

        $('.evaluation-row').each(function () {

            var $row = $(this);

            var rowValue = $row.attr('data-effectiveness');


            if (rowValue === selectedValue) {

                // Matching effectiveness
                $row.show();

            } else {

                // Different effectiveness
                $row.hide();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | UPDATE BUTTON LABEL
        |--------------------------------------------------------------------------
        */

        $('#effectivenessLabel').text(selectedValue);


        /*
        |--------------------------------------------------------------------------
        | ACTIVE FILTER
        |--------------------------------------------------------------------------
        */

        $('.effectiveness-filter')
            .removeClass('active');

        $filter.addClass('active');


        /*
        |--------------------------------------------------------------------------
        | CLOSE DROPDOWN
        |--------------------------------------------------------------------------
        */

        $('#effectivenessDropdown')
            .attr('aria-expanded', 'false');

        $('.effectiveness-menu')
            .removeClass('show');

    });


    /*
    |--------------------------------------------------------------------------
    | RESET FILTER WHEN DROPDOWN IS OPENED
    |--------------------------------------------------------------------------
    */

    $('#effectivenessDropdown').on('click', function () {

        // Bootstrap controls the actual dropdown.
        // This is intentionally left here so the filter
        // does not interfere with Bootstrap's dropdown behavior.

    });

});
</script>