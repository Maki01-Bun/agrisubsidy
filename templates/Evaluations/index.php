<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Evaluations</h3>
            <div class="card-tools evaluation-card-tools d-flex align-items-center">
                <a href="<?= $this->Url->build(['controller' => 'Evaluations', 'action' => 'downloadSummary']) ?>"
                   class="btn btn-success mr-2 evaluation-download-btn"
                   title="Download Evaluation Summary">
                    <i class="fas fa-file-excel mr-1"></i>
                    Download Evaluation Summary
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
                        <th>Pest</th> 
                        <th>Calamity</th> 
                        <th>Feedback Rating</th>
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
                        <th>Rating</th>
                        <td id="feedback_rating"></td>
                    </tr>
                    <tr>
                        <th>Comments</th>
                        <td id="feedback_comments"></td>
                    </tr>
                    <tr>
                        <th>Date Submitted</th>
                        <td id="feedback_date"></td>
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

                $('#feedback_date').text(
                    response.data.feedback_date || 'N/A'
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
</script>