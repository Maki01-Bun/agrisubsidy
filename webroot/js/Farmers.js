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
                    $('#modified').val(data.modified);
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
            {data:"modified"},
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
function viewRecord(farmerId)
{
    console.log('====================================');
    console.log('VIEW FARMER RECORDS');
    console.log('Farmer ID:', farmerId);
    console.log('====================================');

    $('#viewRecordModal').modal('show');

    // ============================================================
    // LOADING FARMER INFORMATION
    // ============================================================

    $('#farmerInformation').html(
        '<div class="text-center py-3">' +
            '<i class="fas fa-spinner fa-spin mr-2"></i>' +
            'Loading farmer information...' +
        '</div>'
    );

    // ============================================================
    // LOADING RECORDS
    // ============================================================

    $('#distributionRecordsBody').html(
        '<tr>' +
            '<td colspan="6" class="text-center py-4">' +
                '<i class="fas fa-spinner fa-spin mr-2"></i>' +
                'Loading distribution records...' +
            '</td>' +
        '</tr>'
    );

    // ============================================================
    // AJAX REQUEST
    // ============================================================

    $.ajax({

        url: '<?= $this->Url->build(["controller" => "Farmers","action" => "viewRecord"]) ?>',

        type: 'GET',

        data: {
            id: farmerId
        },

        // Don't force jQuery to reject the response
        // before we inspect it.
        dataType: 'text',

        cache: false,

        success: function(response, textStatus, xhr)
        {
            console.log('====================================');
            console.log('AJAX SUCCESS');
            console.log('HTTP Status:', xhr.status);
            console.log('Raw Response:', response);
            console.log('====================================');

            var data;

            // ========================================================
            // CONVERT RESPONSE TO JSON
            // ========================================================

            try {

                data = JSON.parse(response);

            } catch (e) {

                console.error('JSON PARSE ERROR:', e);
                console.error('SERVER RESPONSE:', response);

                $('#farmerInformation').html(
                    '<div class="alert alert-danger">' +
                        '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                        '<strong>Server returned an invalid response.</strong>' +
                        '<br>' +
                        'Please check the browser console for the actual response.' +
                    '</div>'
                );

                $('#distributionRecordsBody').html(
                    '<tr>' +
                        '<td colspan="6" class="text-center text-danger py-4">' +
                            '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                            'Unable to load distribution records.' +
                        '</td>' +
                    '</tr>'
                );

                return;
            }

            console.log('Parsed JSON:', data);

            // ========================================================
            // SERVER ERROR
            // ========================================================

            if (data.status !== 'success') {

                var errorMessage =
                    data.message ||
                    'Unable to load farmer information.';

                $('#farmerInformation').html(
                    '<div class="alert alert-danger">' +
                        '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                        escapeHtml(errorMessage) +
                    '</div>'
                );

                $('#distributionRecordsBody').html(
                    '<tr>' +
                        '<td colspan="6" class="text-center text-danger py-4">' +
                            '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                            escapeHtml(errorMessage) +
                        '</td>' +
                    '</tr>'
                );

                return;
            }

            // ========================================================
            // FARMER DATA
            // ========================================================

            var farmer = data.farmer || {};

            var firstName = farmer.first_name || '';
            var lastName = farmer.last_name || '';

            var farmerName =
                (firstName + ' ' + lastName).trim();

            if (farmerName === '') {
                farmerName = 'Unknown Farmer';
            }

            // ========================================================
            // RECORDS
            // ========================================================

            var records = Array.isArray(data.records)
                ? data.records
                : [];

            console.log('Farmer:', farmer);
            console.log('Records:', records);
            console.log('Number of records:', records.length);

            // ========================================================
            // FARMER INFORMATION
            // ========================================================

            $('#farmerInformation').html(
                '<div class="card border-0 bg-light mb-3">' +
                    '<div class="card-body">' +

                        '<div class="row">' +

                            '<div class="col-md-6">' +
                                '<strong>' +
                                    '<i class="fas fa-user mr-2"></i>' +
                                    'Farmer:' +
                                '</strong> ' +
                                escapeHtml(farmerName) +
                            '</div>' +

                            '<div class="col-md-6">' +
                                '<strong>' +
                                    '<i class="fas fa-list mr-2"></i>' +
                                    'Total Records:' +
                                '</strong> ' +
                                records.length +
                            '</div>' +

                        '</div>' +

                    '</div>' +
                '</div>'
            );

            // ========================================================
            // NO RECORDS
            // ========================================================

            if (records.length === 0) {

                $('#distributionRecordsBody').html(
                    '<tr>' +
                        '<td colspan="6" class="text-center text-muted py-4">' +
                            '<i class="fas fa-info-circle mr-2"></i>' +
                            'No distribution records found for this farmer.' +
                        '</td>' +
                    '</tr>'
                );

                return;
            }

            // ========================================================
            // BUILD TABLE
            // ========================================================

            var html = '';

            $.each(records, function(index, record)
            {
                // ----------------------------------------------------
                // PROGRAM
                // ----------------------------------------------------

                var program =
                    record.program_name ||
                    '-';

                // ----------------------------------------------------
                // SUBSIDY ITEM
                // ----------------------------------------------------

                var subsidyItem =
                    record.subsidy_item ||
                    '-';

                // ----------------------------------------------------
                // QUANTITY
                // ----------------------------------------------------

                var quantity =
                    record.quantity !== null &&
                    record.quantity !== undefined
                        ? String(record.quantity)
                        : '-';

                // ----------------------------------------------------
                // DISTRIBUTION DATE
                // ----------------------------------------------------

                var distributionDate =
                    record.distribution_date ||
                    '-';

                // ----------------------------------------------------
                // RECEIVED DATE
                // ----------------------------------------------------

                var receivedDate =
                    record.received_date ||
                    '-';

                // ----------------------------------------------------
                // STATUS
                // ----------------------------------------------------

                var status =
                    getStatusBadge(record.status);

                html +=
                    '<tr>' +

                        '<td>' +
                            escapeHtml(program) +
                        '</td>' +

                        '<td>' +
                            escapeHtml(subsidyItem) +
                        '</td>' +

                        '<td>' +
                            escapeHtml(quantity) +
                        '</td>' +

                        '<td>' +
                            escapeHtml(
                                formatDate(distributionDate)
                            ) +
                        '</td>' +

                        '<td>' +
                            escapeHtml(
                                formatDate(receivedDate)
                            ) +
                        '</td>' +

                        '<td>' +
                            status +
                        '</td>' +

                    '</tr>';
            });

            // ========================================================
            // DISPLAY RECORDS
            // ========================================================

            $('#distributionRecordsBody').html(html);
        },

        // ============================================================
        // AJAX ERROR
        // ============================================================

        error: function(xhr, status, error)
        {
            console.error('====================================');
            console.error('AJAX REQUEST FAILED');
            console.error('HTTP STATUS:', xhr.status);
            console.error('STATUS:', status);
            console.error('ERROR:', error);
            console.error('RESPONSE:', xhr.responseText);
            console.error('====================================');

            var message =
                'Unable to load distribution records.';

            // --------------------------------------------------------
            // Try JSON error
            // --------------------------------------------------------

            try {

                var errorResponse =
                    JSON.parse(xhr.responseText);

                if (errorResponse.message) {
                    message = errorResponse.message;
                }

            } catch (e) {

                // Response was not JSON
                console.error(
                    'Response is not valid JSON.'
                );
            }

            // --------------------------------------------------------
            // HTTP STATUS MESSAGE
            // --------------------------------------------------------

            if (xhr.status === 404) {

                message =
                    'The Farmers/viewRecord URL was not found.';

            }
            else if (xhr.status === 500) {

                message =
                    'A server error occurred in FarmersController::viewRecord(). Check the CakePHP error log or browser console.';

            }
            else if (xhr.status === 403) {

                message =
                    'Access denied. You may not have permission to view these records.';

            }

            // ========================================================
            // DISPLAY ERROR
            // ========================================================

            $('#farmerInformation').html(
                '<div class="alert alert-danger">' +
                    '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                    '<strong>Error:</strong> ' +
                    escapeHtml(message) +
                '</div>'
            );

            $('#distributionRecordsBody').html(
                '<tr>' +
                    '<td colspan="6" class="text-center text-danger py-4">' +
                        '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                        escapeHtml(message) +
                    '</td>' +
                '</tr>'
            );
        }
    });
}


// ================================================================
// STATUS BADGE
// ================================================================

function getStatusBadge(status)
{
    if (!status) {
        return '<span class="badge badge-secondary">Unknown</span>';
    }

    var normalized =
        String(status).toLowerCase().trim();

    var badgeClass =
        'badge-secondary';

    if (
        normalized === 'completed' ||
        normalized === 'received' ||
        normalized === 'distributed'
    ) {
        badgeClass = 'badge-success';
    }
    else if (
        normalized === 'pending' ||
        normalized === 'processing'
    ) {
        badgeClass = 'badge-warning';
    }
    else if (
        normalized === 'cancelled' ||
        normalized === 'failed'
    ) {
        badgeClass = 'badge-danger';
    }

    return (
        '<span class="badge ' +
        badgeClass +
        '">' +
        escapeHtml(status) +
        '</span>'
    );
}


// ================================================================
// FORMAT DATE
// ================================================================

function formatDate(dateValue)
{
    if (!dateValue || dateValue === '-') {
        return '-';
    }

    // CakePHP may return:
    // 2026-09-06
    // 2026-09-06T00:00:00+00:00
    // 2026-09-06 00:00:00

    var value =
        String(dateValue);

    if (value.indexOf('T') !== -1) {
        value =
            value.split('T')[0];
    }

    if (value.indexOf(' ') !== -1) {
        value =
            value.split(' ')[0];
    }

    return value;
}


// ================================================================
// ESCAPE HTML
// ================================================================

function escapeHtml(value)
{
    return $('<div>')
        .text(value == null ? '' : String(value))
        .html();
}
