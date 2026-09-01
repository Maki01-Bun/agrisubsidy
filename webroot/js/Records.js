$(function(){
	getRecords();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Record');
        $('#records-modal').modal('show');
    });
    $('#records-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Record');
        $.ajax({
            url: BASE_URL + '/api/Records/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                	$('#farmer-id').val(data.farmer_id);
                    $('#program_name').val(data.program_name);
                	$('#subsidy-item').val(data.subsidy_item);
                	$('#quantity').val(data.quantity);
                    $('#schedule-id').val(data.schedule_id);
                    $('#distribution-date').val(data.distribution_date);
                    $('#received-date').val(data.received_date);
                    $('#status').val(data.status);
                	$('#id').val(data.id);
                    $('#records-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#records-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Records/add';
		}else{
			url = BASE_URL + '/api/Records/edit/' + id;
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
				getRecords();
				msgBox(data.status,data.message);
				$('#records-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#records-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Records/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getRecords();
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

    $('#records-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farmer_id').focus();
        }, 500);
    });

    $('#records-modal').on('hidden.bs.modal', function() {
        $("#records-form").trigger("reset");
        $("#id").val('');
    });
});

function getRecords()
{
	$('#records-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Records/getRecords'
        },
        "columns": [
			{data:"farmer_name"},
            {data:"program_name"},
			{data:"subsidy_item"},
            {data:"quantity"},
            {data:"distribution_date"},
            {data:"received_date"},
            {data:"status"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;">' + '<a href="" class="edit" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' +
                    'title="Edit Record" ' + 'data-id="' + data.id + '">' + '<i class="fa fas fa-pen"></i>' + '</a>' + ' | ' +
                    '<a href="" class="delete text-danger" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' + 'title="Delete Record" ' +
                    'data-id="' + data.id + '">' + '<i class="fa fa-trash"></i>' + '</a>' + ' | ' + '<a href="javascript:void(0)" ' + 'class="text-info" ' + 
                    'onclick="viewRecord(' + data.id + ')" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' + 'title="View Record" ' + 'data-id="' + data.id + '">' + 
                    '<i class="fa fa-eye"></i>' + '</a>' + '</div>';
                    return option;
                }
            }
        ]
	});
}
$(function () {

    if (!$.fn.DataTable.isDataTable('#records-table')) {

        console.error(
            'records-table has not been initialized yet.'
        );

        return;
    }


    var recordsTable =
        $('#records-table').DataTable();

    $(document).on(
        'click',
        '.status-filter',
        function (e) {

            e.preventDefault();


            var $item = $(this);

            if (
                $item.hasClass('clear-status')
            ) {

                $('#statusLabel').text(
                    'Filter Status'
                );


                $('.status-filter')
                    .removeClass('active');


                recordsTable
                    .column(6)
                    .search('')
                    .draw();


                console.log(
                    'Status filter cleared'
                );


                return;
            }

            var value =
                $item.attr('data-value');


            if (!value) {

                return;

            }


            $('#statusLabel').text(
                value
            );


            $('.status-filter')
                .removeClass('active');

            $item.addClass('active');

            recordsTable
                .column(6)
                .search(
                    '^' +
                    $.fn.dataTable.util.escapeRegex(
                        value
                    ) +
                    '$',
                    true,
                    false
                )
                .draw();


            console.log(
                'Filtering Status:',
                value
            );

        }
    );

});

function viewRecord(recordId)
{

    $('#recordViewModal').modal('show');

    $('#view_farmer_name').text(
        'Loading...'
    );

    $('#view_program_name').text(
        'Loading...'
    );

    $('#view_subsidy_item').text(
        'Loading...'
    );

    $('#view_quantity').text(
        'Loading...'
    );

    $('#view_distribution_date').text(
        'Loading...'
    );

    $('#view_received_date').text(
        'Loading...'
    );

    $('#view_status').text(
        'Loading...'
    );

    $('#view_distribution_time').text(
        'Loading...'
    );

    $('#view_confirmed_at').text(
        'Loading...'
    );

    $.ajax({

        url:
            'Records/view/' +
            recordId,

        type: 'GET',

        dataType: 'json',

        success: function (response) {

            console.log(
                'Record Response:',
                response
            );


            if (
                response &&
                response.success
            ) {

                var data =
                    response.data;


                /*
                 * Farmer Name
                 */

                $('#view_farmer_name').text(
                    data.farmer_name ||
                    'N/A'
                );


                /*
                 * Program Name
                 */

                $('#view_program_name').text(
                    data.program_name ||
                    'N/A'
                );


                /*
                 * Subsidy Item
                 */

                $('#view_subsidy_item').text(
                    data.subsidy_item ||
                    'N/A'
                );


                /*
                 * Quantity
                 */

                $('#view_quantity').text(
                    data.quantity ||
                    'N/A'
                );


                /*
                 * Distribution Date
                 */

                $('#view_distribution_date').text(
                    data.distribution_date ||
                    'N/A'
                );


                /*
                 * Distribution Time
                 */

                $('#view_distribution_time').text(
                    data.distribution_time ||
                    'N/A'
                );


                /*
                 * Received Date
                 */

                $('#view_received_date').text(
                    data.received_date ||
                    'N/A'
                );


                /*
                 * Status
                 */

                $('#view_status').html(
                    getStatusBadge(
                        data.status
                    )
                );


                /*
                 * Confirmed At
                 */

                $('#view_confirmed_at').text(
                    data.confirmed_at ||
                    'N/A'
                );

            }

            else {

                $('#view_farmer_name').text(
                    'N/A'
                );

                $('#view_program_name').text(
                    'N/A'
                );

                $('#view_subsidy_item').text(
                    'N/A'
                );

                $('#view_quantity').text(
                    'N/A'
                );

                $('#view_distribution_date').text(
                    'N/A'
                );

                $('#view_distribution_time').text(
                    'N/A'
                );

                $('#view_received_date').text(
                    'N/A'
                );

                $('#view_status').text(
                    'N/A'
                );

                $('#view_confirmed_at').text(
                    'N/A'
                );


                console.error(
                    response.message ||
                    'Record not found.'
                );

            }

        },

        error: function (xhr) {

            console.error(
                'Record AJAX Error:',
                xhr.responseText
            );


            $('#view_farmer_name').text(
                'Unable to load'
            );

            $('#view_program_name').text(
                'Unable to load'
            );

            $('#view_subsidy_item').text(
                'Unable to load'
            );

            $('#view_quantity').text(
                'Unable to load'
            );

            $('#view_distribution_date').text(
                'Unable to load'
            );

            $('#view_distribution_time').text(
                'Unable to load'
            );

            $('#view_received_date').text(
                'Unable to load'
            );

            $('#view_status').text(
                'Unable to load'
            );

            $('#view_confirmed_at').text(
                'Unable to load'
            );

        }

    });

}

function getStatusBadge(status)
{

    /*
     * RECEIVED
     */

    if (status === 'Received') {

        return (
            '<span class="badge badge-success">' +

                '<i class="fas fa-check mr-1"></i>' +

                'Received' +

            '</span>'
        );

    }


    /*
     * CANCELLED
     */

    if (status === 'Cancelled') {

        return (
            '<span class="badge badge-danger">' +

                '<i class="fas fa-times mr-1"></i>' +

                'Cancelled' +

            '</span>'
        );

    }


    /*
     * NOT RECEIVED
     */

    if (status === 'Not Received') {

        return (
            '<span class="badge badge-warning">' +

                '<i class="fas fa-exclamation mr-1"></i>' +

                'Not Received' +

            '</span>'
        );

    }

    if (status === 'Re-Scheduled') {

        return (
            '<span class="badge badge-info">' +

                '<i class="fas fa-calendar-alt mr-1"></i>' +

                'Re-Scheduled' +

            '</span>'
        );

    }
    return (
        '<span class="badge badge-secondary">' +

            (status || 'N/A') +

        '</span>'
    );

}
