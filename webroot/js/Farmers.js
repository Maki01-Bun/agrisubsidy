$(function(){
    getFarmers();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Farmer');
        $('#farmers-modal').modal('show');
    });
    $('#farmers-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Farmer');
        $.ajax({
            url: BASE_URL + '/api/Farmers/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                    $('#farmer-no').val(data.farmer_no);
                    $('#first-name').val(data.first_name);
                    $('#last-name').val(data.last_name);
                    $('#middle-name').val(data.middle_name);
                    $('#birthdate').val(data.birthdate);
                    $('#gender').val(data.gender);
                    $('#address').val(data.address);
                    $('#contact-no').val(data.contact_no);
                    $('#created').val(data.created);
                    $('#user_id').val(data.user_id);
                    $('#id').val(data.id);
                    $('#farmers-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

    $('#farmers-form').submit(function(e){
        e.preventDefault();
        let fd =new FormData(this);
        let id = $('#id').val();
        let url = '';
        if(id==''){
            url = BASE_URL + '/api/Farmers/add';
        }else{
            url = BASE_URL + '/api/Farmers/edit/' + id;
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
                getFarmers();
                msgBox(data.status,data.message);
                $('#farmers-modal').modal('hide');
            }else{
                msgBox(data.status,data.message);
            }
        }).fail(function(jqXHR,textStatus,errorThrown){
            msgBox('error',errorThrown);
        });
    });

    $('#farmers-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Farmers/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getFarmers();
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

    $('#farmers-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farmer_no').focus();
        }, 500);
    });

    $('#farmers-modal').on('hidden.bs.modal', function() {
        $("#farmers-form").trigger("reset");
        $("#id").val('');
    });
});

function getFarmers()
{
    $('#farmers-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Farmers/getFarmers'
        },
        "columns": [
            {data:"farmer_no"},
            {data:"first_name"},
            {data:"last_name"},
            {data:"middle_name"},
            {data:"birthdate"},
            {data:"gender"},
            {data:"address"},
            {data:"contact_no"},
            {data:"created"},
            { data: null,render: function(data){
                     var option = '<div style="text-align:center;">' + '<a href="" class="edit" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' +
                    'title="Edit Record" ' + 'data-id="' + data.id + '">' + '<i class="fa fas fa-pen"></i>' + '</a>' + ' | ' +
                    '<a href="" class="delete text-danger" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' + 'title="Delete Record" ' +
                    'data-id="' + data.id + '">' + '<i class="fa fa-trash"></i>' + '</a>' + ' | ' + '<a href="javascript:void(0)" ' + 'class="text-info" ' + 
                    'onclick="viewRecord(' + data.id + ')" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' + 'title="View Distribution Record" ' + 'data-id="' + data.id + '">' + 
                    '<i class="fa fa-eye"></i>' + '</a>' + '</div>';
                    return option;
                }
            }
        ]
    });
}
function viewRecord(recordId) {

    if (!recordId) {
        console.error('Invalid record ID.');
        return;
    }

    // Show loading state
    $('#view_farmer_name').text('Loading...');
    $('#view_total_records').text('0');

    $('#view_distribution_records').html(`
        <tr>
            <td colspan="6" class="text-center">
                <i class="fas fa-spinner fa-spin"></i>
                Loading distribution records...
            </td>
        </tr>
    `);

    // Open modal
    $('#viewRecordModal').modal('show');

    $.ajax({
        url: BASE_URL + '/Records/getRecord/' + recordId,
        type: 'GET',
        dataType: 'json',

        success: function(response) {

            console.log('getRecord response:', response);

            if (!response || response.success !== true) {

                $('#view_distribution_records').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            Unable to load distribution records.
                        </td>
                    </tr>
                `);

                return;
            }

            var data = response.data || {};

            console.log('Record data:', data);

            // Farmer
            $('#view_farmer_name').text(
                data.farmer_name || 'N/A'
            );

            // Total
            $('#view_total_records').text(
                data.total_records || 1
            );

            // Program
            var programName =
                data.program_name ||
                data.schedule_program_name ||
                'N/A';

            // Other fields
            var subsidyItem =
                data.subsidy_item || 'N/A';

            var quantity =
                data.quantity !== null &&
                data.quantity !== undefined &&
                data.quantity !== ''
                    ? data.quantity
                    : 'N/A';

            var distributionDate =
                data.distribution_date || 'N/A';

            var receivedDate =
                data.received_date || 'N/A';

            var status =
                data.status || 'N/A';

            // Status badge
            var statusBadge = '';

            if (status === 'Received') {

                statusBadge = `
                    <span class="badge badge-success">
                        <i class="fas fa-check"></i>
                        Received
                    </span>
                `;

            } else if (status === 'Cancelled') {

                statusBadge = `
                    <span class="badge badge-danger">
                        <i class="fas fa-times"></i>
                        Cancelled
                    </span>
                `;

            } else if (status === 'Not Received') {

                statusBadge = `
                    <span class="badge badge-warning">
                        <i class="fas fa-clock"></i>
                        Not Received
                    </span>
                `;

            } else {

                statusBadge = `
                    <span class="badge badge-secondary">
                        ${escapeHtml(status)}
                    </span>
                `;
            }

            // Build table row
            $('#view_distribution_records').html(`
                <tr>

                    <td>
                        ${escapeHtml(programName)}
                    </td>

                    <td>
                        ${escapeHtml(subsidyItem)}
                    </td>

                    <td>
                        ${escapeHtml(quantity)}
                    </td>

                    <td>
                        ${escapeHtml(distributionDate)}
                    </td>

                    <td>
                        ${escapeHtml(receivedDate)}
                    </td>

                    <td>
                        ${statusBadge}
                    </td>

                </tr>
            `);
        },

        error: function(xhr, status, error) {

            console.error('getRecord AJAX error:', error);
            console.error('Response:', xhr.responseText);

            $('#view_distribution_records').html(`
                <tr>
                    <td colspan="6" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Failed to load distribution records.
                    </td>
                </tr>
            `);
        }
    });
}


/**
 * Prevent HTML injection when displaying AJAX values.
 */
function escapeHtml(value) {

    if (value === null || value === undefined) {
        return '';
    }

    return $('<div>')
        .text(value)
        .html();
}