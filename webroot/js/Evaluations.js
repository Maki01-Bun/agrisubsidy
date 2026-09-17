$(function(){
	getEvaluations();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Evaluation');
        $('#evaluations-modal').modal('show');
    });
    $('#evaluations-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Evaluation');
        $.ajax({
            url: BASE_URL + '/api/Evaluations/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                    $('#program_name').val(data.program_name);
                	$('#farm_size').val(data.farm_size);
                	$('#crop_yield_after').val(data.crop_yield_after);
                	$('#selling_price').val(data.selling_price);
                    $('#feedback_rating').val(data.feedback_rating);
                	$('#effectiveness_label').val(data.effectiveness_label);
                	$('#id').val(data.id);
                    $('#evaluations-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#evaluations-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Evaluations/add';
		}else{
			url = BASE_URL + '/api/Evaluations/edit/' + id;
		}

		$.ajax({
			processData:false,
			contentType:false,
			data:fd,
			url:url,
			type:'POST',
			dataType:'json'
		}).done(function(data){
			if(data.status=='success'){
				getEvaluations();
				msgBox(data.status,data.message);
				$('#evaluations-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#evaluations-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Evaluations/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getEvaluations();
                            msgBox(data.status,data.message);
                        }else{
                            msgBox(data.status,data.message);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown){
                        msgBox('error',errorThrown);
                    });
            }
        });
    });

    $('#evaluations-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farm_size').focus();
        }, 500);
    });

    $('#evaluations-modal').on('hidden.bs.modal', function() {
        $("#evaluations-form").trigger("reset");
        $("#id").val('');
    });
});

function getEvaluations()
{
	$('#evaluations-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Evaluations/getEvaluations'
        },
        "columns": [
            {data:"program_name"},
			{data:"farm_size"},
            {data:"crop_yield_after"},
            {data:"effectiveness_label"},
            {data: null,render: function(data) {
                    var option =
                        '<div style="text-align:center;">' + '<a href="javascript:void(0)" ' + 'class="text-info" ' + 
                        'onclick="viewFeedback(' + data.id + ')" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' + 
                        'title="View Feedback">' + '<i class="fa fa-eye"></i>' + '</a>' + '</div>';
                    return option;
                }
            }
        ]
	});
}
   $(function () {

    if (!$.fn.DataTable.isDataTable('#evaluations-table')) {
        console.error('evaluations-table has not been initialized yet.');
        return;
    }

    var evaluationTable = $('#evaluations-table').DataTable();

    $(document).on('click', '.subsidy-type-filter', function (e) {

        e.preventDefault();

        var $item = $(this);

        // Clear filter
        if ($item.hasClass('clear-subsidy-type')) {

            $('#subsidyTypeLabel').text('Filter Subsidy Type');

            $('.subsidy-type-filter').removeClass('active');

            evaluationTable
                .column(2)
                .search('')
                .draw();

            return;
        }

        // Get subsidy_type value
        var subsidyType = $item.data('value');

        if (!subsidyType) {
            return;
        }

        // Update button label
        $('#subsidyTypeLabel').text(subsidyType);

        // Remove active state
        $('.subsidy-type-filter').removeClass('active');

        // Add active state
        $item.addClass('active');

        // Filter subsidy_type column
        evaluationTable
            .column(2)
            .search(
                '^' +
                $.fn.dataTable.util.escapeRegex(subsidyType) +
                '$',
                true,
                false,
                true
            )
            .draw();

        console.log('Filtering subsidy_type:', subsidyType);
    });

});
function viewFeedback(evaluationId)
{

    /*
     * Show modal
     */

    $('#feedbackModal').modal('show');

    /*
     * Reset modal fields
     */

    $('#farmer_name').text('Loading...');
    $('#crop_yield_after').text('Loading...');
    $('selling_price').text('Loading...');
    $('#subsidy_received').text('Loading...');
    $('#feedback_rating').text('Loading...');
    $('#feedback_comments').text('Loading...');

    $.ajax({
        url: 'Evaluations/getFeedback/' + evaluationId,
        type: 'GET',
        dataType: 'json',


        /*
         * SUCCESS
         */

        success: function (response) {
            console.log(
                'Feedback Response:',
                response
            );

            if (
                response &&
                response.success
            ) {

                /*
                 * Farmer Name
                 */

                $('#farmer_name').text(
                    response.data.farmer_name ||
                    'N/A'
                );

                $('#selling_price').text(
                    response.data.selling_price ||
                    'N/A'
                );

                $('#subsidy_received').text(
                    response.data.subsidy_received ||
                    'N/A'
                );

                /*
                 * Yield After
                 */

                $('#crop_yield_after').text(
                    response.data.crop_yield_after ||
                    'N/A'
                );
                /*
                 * Rating
                 */

                if (
                    response.data.feedback_rating !== null &&
                    response.data.feedback_rating !== undefined &&
                    response.data.feedback_rating !== ''
                ) {

                    $('#feedback_rating').html(
                        '<span class="badge badge-primary" ' +
                        'style="font-size:16px;">' +
                        response.data.feedback_rating +
                        '</span>'
                    );

                } else {

                    $('#feedback_rating').text(
                        'N/A'
                    );
                }

                /*
                 * Comments
                 */

                $('#feedback_comments').text(
                    response.data.comments ||
                    'No comments provided.'
                );

            }

            else {
                $('#farmer_name').text('N/A');
                $('#crop_yield_after').text('N/A');
                $('#selling_price').text('N/A');
                $('#subsidy_received').text('N/A');
                $('#feedback_rating').text('N/A');
                $('#feedback_comments').text(
                    response.message ||
                    'Feedback not found.'
                );
            }
        },

        /*
         * ERROR
         */
        error: function (xhr) {
            console.error(
                'Feedback AJAX Error:',
                xhr.responseText
            );

            $('#farmer_name').text(
                'Unable to load'
            );
            $('#crop_yield_after').text(
                'Unable to load'
            );
            $('#selling_price').text(
                'Unable to load'
            );
            $('#subsidy_received').text(
                'Unable to load'
            );
            $('#feedback_rating').text(
                'Unable to load'
            );
            $('#feedback_comments').text(
                'Unable to load feedback. Please try again.'
            );
        }

    });
}