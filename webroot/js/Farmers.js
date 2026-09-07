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
    console.log('VIEW FARMER DISTRIBUTION RECORDS');
    console.log('Farmer ID:', farmerId);
    console.log('====================================');

    /*
     * Show modal
     */

    $('#viewRecordModal').modal('show');

    /*
     * Reset farmer information
     */

    $('#farmerInformation').html(
        '<div class="text-center py-3">' +
            '<i class="fas fa-spinner fa-spin mr-2"></i>' +
            'Loading farmer information...' +
        '</div>'
    );

    /*
     * Reset records table
     */

    $('#distributionRecordsBody').html(
        '<tr>' +
            '<td colspan="6" class="text-center py-4">' +
                '<i class="fas fa-spinner fa-spin mr-2"></i>' +
                'Loading distribution records...' +
            '</td>' +
        '</tr>'
    );


    /*
     * AJAX
     *
     * farmerId is the ID of the farmer,
     * NOT the ID of a single distribution record.
     */

    $.ajax({

        url: 'Records/getFarmerRecords/' + farmerId,

        type: 'GET',

        dataType: 'json',

        cache: false,


        /*
         * SUCCESS
         */

        success: function(response)
        {
            console.log(
                'Farmer Distribution Records:',
                response
            );


            /*
             * Check response
             */

            if (
                response &&
                response.success
            ) {

                /*
                 * Farmer information
                 */

                var farmer =
                    response.farmer || {};

                var firstName =
                    farmer.first_name || '';

                var lastName =
                    farmer.last_name || '';

                var farmerName =
                    (firstName + ' ' + lastName).trim();

                if (!farmerName) {
                    farmerName = 'N/A';
                }


                /*
                 * Records
                 */

                var records =
                    Array.isArray(response.records)
                        ? response.records
                        : [];


                console.log(
                    'Farmer Name:',
                    farmerName
                );

                console.log(
                    'Total Records:',
                    records.length
                );


                /*
                 * Display farmer information
                 */

                $('#farmerInformation').html(

                    '<div class="card border-0 bg-light mb-3">' +

                        '<div class="card-body">' +

                            '<div class="row">' +

                                '<div class="col-md-6">' +

                                    '<strong>' +

                                        '<i class="fas fa-user mr-2"></i>' +

                                        'Farmer: ' +

                                    '</strong>' +

                                    escapeHtml(farmerName) +

                                '</div>' +


                                '<div class="col-md-6">' +

                                    '<strong>' +

                                        '<i class="fas fa-list mr-2"></i>' +

                                        'Total Distribution Records: ' +

                                    '</strong>' +

                                    records.length +

                                '</div>' +

                            '</div>' +

                        '</div>' +

                    '</div>'
                );


                /*
                 * No records
                 */

                if (records.length === 0) {

                    $('#distributionRecordsBody').html(

                        '<tr>' +

                            '<td colspan="6" ' +
                                'class="text-center text-muted py-4">' +

                                '<i class="fas fa-info-circle mr-2"></i>' +

                                'No distribution records found for this farmer.' +

                            '</td>' +

                        '</tr>'
                    );

                    return;
                }


                /*
                 * Build table
                 */

                var html = '';


                $.each(
                    records,
                    function(index, record)
                    {

                        /*
                         * Program
                         */

                        var program =
                            record.program_name ||
                            record.program ||
                            'N/A';


                        /*
                         * Subsidy Item
                         */

                        var subsidyItem =
                            record.subsidy_item ||
                            record.subsidy_name ||
                            record.item_name ||
                            'N/A';


                        /*
                         * Quantity
                         */

                        var quantity =
                            record.quantity !== null &&
                            record.quantity !== undefined
                                ? record.quantity
                                : 'N/A';


                        /*
                         * Distribution Date
                         */

                        var distributionDate =
                            record.distribution_date ||
                            'N/A';


                        /*
                         * Received Date
                         */

                        var receivedDate =
                            record.received_date ||
                            'N/A';


                        /*
                         * Status
                         */

                        var status =
                            getStatusBadge(
                                record.status
                            );


                        /*
                         * Create row
                         */

                        html +=

                            '<tr>' +

                                '<td>' +

                                    escapeHtml(
                                        program
                                    ) +

                                '</td>' +


                                '<td>' +

                                    escapeHtml(
                                        subsidyItem
                                    ) +

                                '</td>' +


                                '<td>' +

                                    escapeHtml(
                                        String(quantity)
                                    ) +

                                '</td>' +


                                '<td>' +

                                    escapeHtml(
                                        formatDate(
                                            distributionDate
                                        )
                                    ) +

                                '</td>' +


                                '<td>' +

                                    escapeHtml(
                                        formatDate(
                                            receivedDate
                                        )
                                    ) +

                                '</td>' +


                                '<td>' +

                                    status +

                                '</td>' +

                            '</tr>';
                    }
                );


                /*
                 * Display all records
                 */

                $('#distributionRecordsBody').html(
                    html
                );

            }

            else {

                /*
                 * Server returned an error
                 */

                var message =
                    response &&
                    response.message
                        ? response.message
                        : 'Records not found.';


                $('#farmerInformation').html(

                    '<div class="alert alert-danger">' +

                        '<i class="fas fa-exclamation-triangle mr-2"></i>' +

                        escapeHtml(message) +

                    '</div>'
                );


                $('#distributionRecordsBody').html(

                    '<tr>' +

                        '<td colspan="6" ' +
                            'class="text-center text-danger py-4">' +

                            '<i class="fas fa-exclamation-triangle mr-2"></i>' +

                            escapeHtml(message) +

                        '</td>' +

                    '</tr>'
                );
            }
        },


        /*
         * ERROR
         */

        error: function(xhr, status, error)
        {
            console.error(
                'Farmer Distribution Records AJAX Error:',
                xhr.responseText
            );

            console.error(
                'HTTP Status:',
                xhr.status
            );

            console.error(
                'Status:',
                status
            );

            console.error(
                'Error:',
                error
            );


            var message =
                'Unable to load distribution records.';


            /*
             * Try to read JSON error
             */

            try {

                var errorResponse =
                    JSON.parse(
                        xhr.responseText
                    );

                if (
                    errorResponse &&
                    errorResponse.message
                ) {

                    message =
                        errorResponse.message;
                }

            }
            catch (e) {

                console.error(
                    'Response is not valid JSON.'
                );
            }


            /*
             * HTTP status
             */

            if (xhr.status === 403) {

                message =
                    'Access denied. You may not have permission to view these records.';

            }
            else if (xhr.status === 404) {

                message =
                    'The farmer distribution records API was not found.';

            }
            else if (xhr.status === 500) {

                message =
                    'A server error occurred while loading the farmer records.';
            }


            /*
             * Display error
             */

            $('#farmerInformation').html(

                '<div class="alert alert-danger">' +

                    '<i class="fas fa-exclamation-triangle mr-2"></i>' +

                    '<strong>Error:</strong> ' +

                    escapeHtml(message) +

                '</div>'
            );


            $('#distributionRecordsBody').html(

                '<tr>' +

                    '<td colspan="6" ' +
                        'class="text-center text-danger py-4">' +

                        '<i class="fas fa-exclamation-triangle mr-2"></i>' +

                        escapeHtml(message) +

                    '</td>' +

                '</tr>'
            );
        }

    });
}


/*
 * ================================================================
 * STATUS BADGE
 * ================================================================
 */

function getStatusBadge(status)
{
    if (!status) {

        return (
            '<span class="badge badge-secondary">' +
                'N/A' +
            '</span>'
        );
    }


    /*
     * Normalize status
     */

    var normalized =
        String(status)
            .toLowerCase()
            .trim();


    /*
     * RECEIVED
     */

    if (normalized === 'received') {

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

    if (normalized === 'cancelled') {

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

    if (normalized === 'not received') {

        return (

            '<span class="badge badge-warning">' +

                '<i class="fas fa-exclamation mr-1"></i>' +

                'Not Received' +

            '</span>'
        );
    }


    /*
     * RE-SCHEDULED
     */

    if (
        normalized === 're-scheduled' ||
        normalized === 'rescheduled'
    ) {

        return (

            '<span class="badge badge-info">' +

                '<i class="fas fa-calendar-alt mr-1"></i>' +

                'Re-Scheduled' +

            '</span>'
        );
    }


    /*
     * DEFAULT
     */

    return (

        '<span class="badge badge-secondary">' +

            escapeHtml(
                String(status)
            ) +

        '</span>'
    );
}


/*
 * ================================================================
 * FORMAT DATE
 * ================================================================
 */

function formatDate(dateValue)
{
    if (
        !dateValue ||
        dateValue === '-'
    ) {

        return 'N/A';
    }


    var value =
        String(dateValue);


    /*
     * 2026-09-06T00:00:00+00:00
     */

    if (
        value.indexOf('T') !== -1
    ) {

        value =
            value.split('T')[0];
    }


    /*
     * 2026-09-06 00:00:00
     */

    if (
        value.indexOf(' ') !== -1
    ) {

        value =
            value.split(' ')[0];
    }


    return value;
}


/*
 * ================================================================
 * ESCAPE HTML
 * ================================================================
 */

function escapeHtml(value)
{
    return $('<div>')
        .text(
            value == null
                ? ''
                : String(value)
        )
        .html();
}