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
                    'onclick="viewRecord(' + data.id + ')" ' + 'data-toggle="tooltip" ' + 'data-placement="bottom" ' + 'title="View Record" ' + 'data-id="' + data.id + '">' + 
                    '<i class="fa fa-eye"></i>' + '</a>' + '</div>';
                    return option;
                }
            }
        ]
    });
}
// =============================================================
// VIEW FARMER DISTRIBUTION RECORDS
// =============================================================

function viewRecord(farmerId) {

    console.log('======================================');
    console.log('VIEW RECORD');
    console.log('Farmer ID:', farmerId);
    console.log('======================================');


    // =========================================================
    // VALIDATE FARMER ID
    // =========================================================

    if (!farmerId) {

        console.error(
            'Invalid farmer ID.'
        );

        showModalError(
            'Invalid farmer ID.'
        );

        return;
    }


    // =========================================================
    // RESET FARMER INFORMATION
    // =========================================================

    $('#farmerInformation').html(`

        <div class="text-center py-3">

            <i class="fas
                      fa-spinner
                      fa-spin
                      mr-2"></i>

            Loading farmer information...

        </div>

    `);


    // =========================================================
    // RESET RECORD TABLE
    // =========================================================

    $('#distributionRecordsBody').html(`

        <tr>

            <td colspan="6"
                class="text-center py-4">

                <i class="fas
                          fa-spinner
                          fa-spin
                          mr-2"></i>

                Loading records...

            </td>

        </tr>

    `);


    // =========================================================
    // SHOW MODAL
    // =========================================================

    $('#viewRecordModal').modal('show');


    // =========================================================
    // CHECK RECORD URL
    // =========================================================

    if (
        typeof window.RECORD_GET_URL === 'undefined' ||
        !window.RECORD_GET_URL
    ) {

        console.error(
            'RECORD_GET_URL is not defined.'
        );

        showModalError(
            'Record API URL is not configured.'
        );

        return;
    }


    // =========================================================
    // BUILD REQUEST URL
    // =========================================================

    var url =
        window.RECORD_GET_URL +
        '/' +
        encodeURIComponent(farmerId);


    console.log(
        'Request URL:',
        url
    );


    // =========================================================
    // AJAX REQUEST
    // =========================================================

    $.ajax({

        url: url,

        type: 'GET',

        dataType: 'json',

        cache: false,


        // =====================================================
        // BEFORE SEND
        // =====================================================

        beforeSend: function () {

            console.log(
                'AJAX request started.'
            );

        },


        // =====================================================
        // SUCCESS
        // =====================================================

        success: function (
            response,
            textStatus,
            xhr
        ) {

            console.log(
                '======================================'
            );

            console.log(
                'AJAX SUCCESS'
            );

            console.log(
                'HTTP Status:',
                xhr.status
            );

            console.log(
                'Response:',
                response
            );

            console.log(
                '======================================'
            );


            // =================================================
            // CHECK RESPONSE
            // =================================================

            if (!response) {

                showModalError(
                    'Empty response received from server.'
                );

                return;
            }


            // =================================================
            // CHECK SUCCESS FLAG
            // =================================================

            if (response.success !== true) {

                showModalError(
                    response.message ||
                    'Unable to load farmer records.'
                );

                return;
            }


            // =================================================
            // CHECK DATA
            // =================================================

            if (!response.data) {

                showModalError(
                    'No farmer data was returned.'
                );

                return;
            }


            // =================================================
            // DATA
            // =================================================

            var data = response.data;


            console.log(
                'Farmer data:',
                data
            );


            // =================================================
            // FARMER NAME
            // =================================================

            var farmerName =
                data.farmer_name || 'N/A';


            // =================================================
            // TOTAL RECORDS
            // =================================================

            var totalRecords =
                data.total_records || 0;


            // =================================================
            // DISPLAY FARMER INFORMATION
            // =================================================

            $('#farmerInformation').html(`

                <div class="card card-body bg-light py-2 mb-0">

                    <div class="row align-items-center">


                        <div class="col-md-8">

                            <h6 class="mb-0 text-success">

                                <i class="fas
                                          fa-user-circle
                                          mr-1"></i>

                                <strong>
                                    Farmer Name:
                                </strong>

                                ${escapeHtml(
                                    farmerName
                                )}

                            </h6>

                        </div>


                        <div class="col-md-4
                                    text-md-right
                                    mt-2
                                    mt-md-0">

                            <span class="badge badge-info p-2">

                                Total Records:
                                ${escapeHtml(
                                    totalRecords
                                )}

                            </span>

                        </div>


                    </div>

                </div>

            `);


            // =================================================
            // GET ALL RECORDS
            // =================================================

            var records =
                Array.isArray(data.all_records)
                    ? data.all_records
                    : [];


            console.log(
                'Number of records:',
                records.length
            );


            // =================================================
            // NO RECORDS
            // =================================================

            if (records.length === 0) {

                $('#distributionRecordsBody').html(`

                    <tr>

                        <td colspan="6"
                            class="text-center
                                   py-4
                                   text-muted">

                            <i class="fas
                                      fa-folder-open
                                      mr-1"></i>

                            No distribution records
                            found for this farmer.

                        </td>

                    </tr>

                `);

                return;
            }


            // =================================================
            // BUILD TABLE ROWS
            // =================================================

            var rowsHtml = '';


            $.each(
                records,
                function (
                    index,
                    item
                ) {


                    // =========================================
                    // STATUS
                    // =========================================

                    var status =
                        item.status || 'N/A';


                    var statusBadge = '';


                    // =========================================
                    // RECEIVED
                    // =========================================

                    if (
                        status === 'Received'
                    ) {

                        statusBadge = `

                            <span class="badge badge-success">

                                <i class="fas
                                          fa-check
                                          mr-1"></i>

                                Received

                            </span>

                        `;

                    }


                    // =========================================
                    // CANCELLED
                    // =========================================

                    else if (
                        status === 'Cancelled'
                    ) {

                        statusBadge = `

                            <span class="badge badge-danger">

                                <i class="fas
                                          fa-times
                                          mr-1"></i>

                                Cancelled

                            </span>

                        `;

                    }


                    // =========================================
                    // NOT RECEIVED
                    // =========================================

                    else if (
                        status === 'Not Received'
                    ) {

                        statusBadge = `

                            <span class="badge badge-warning">

                                <i class="fas
                                          fa-clock
                                          mr-1"></i>

                                Not Received

                            </span>

                        `;

                    }


                    // =========================================
                    // RE-SCHEDULED
                    // =========================================

                    else if (
                        status === 'Re-Scheduled'
                    ) {

                        statusBadge = `

                            <span class="badge badge-info">

                                <i class="fas
                                          fa-calendar-alt
                                          mr-1"></i>

                                Re-Scheduled

                            </span>

                        `;

                    }


                    // =========================================
                    // OTHER STATUS
                    // =========================================

                    else {

                        statusBadge = `

                            <span class="badge badge-secondary">

                                ${escapeHtml(
                                    status
                                )}

                            </span>

                        `;
                    }


                    // =========================================
                    // TABLE ROW
                    // =========================================

                    rowsHtml += `

                        <tr>

                            <td>
                                ${escapeHtml(
                                    item.program_name ||
                                    'N/A'
                                )}
                            </td>


                            <td>
                                ${escapeHtml(
                                    item.subsidy_item ||
                                    'N/A'
                                )}
                            </td>


                            <td>
                                ${escapeHtml(
                                    item.quantity ||
                                    '0.00'
                                )}
                            </td>


                            <td>
                                ${escapeHtml(
                                    item.distribution_date ||
                                    'N/A'
                                )}
                            </td>


                            <td>
                                ${escapeHtml(
                                    item.received_date ||
                                    'N/A'
                                )}
                            </td>


                            <td>
                                ${statusBadge}
                            </td>

                        </tr>

                    `;

                }
            );


            // =================================================
            // DISPLAY TABLE
            // =================================================

            $('#distributionRecordsBody')
                .html(rowsHtml);


            console.log(
                'Distribution records table updated.'
            );

        },


        // =====================================================
        // ERROR
        // =====================================================

        error: function (
            xhr,
            textStatus,
            errorThrown
        ) {

            console.error(
                '======================================'
            );

            console.error(
                'AJAX ERROR'
            );

            console.error(
                'HTTP Status:',
                xhr.status
            );

            console.error(
                'Text Status:',
                textStatus
            );

            console.error(
                'Error:',
                errorThrown
            );

            console.error(
                'Response:',
                xhr.responseText
            );

            console.error(
                '======================================'
            );


            var message =
                'Failed to load farmer distribution records.';


            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;
            }


            showModalError(
                message
            );

        }

    });

}


// =============================================================
// SHOW MODAL ERROR
// =============================================================

function showModalError(message) {

    $('#farmerInformation').html(`

        <div class="alert alert-warning mb-0"
             role="alert">

            <i class="fas
                      fa-exclamation-triangle
                      mr-2"></i>

            ${escapeHtml(message)}

        </div>

    `);


    $('#distributionRecordsBody').html(`

        <tr>

            <td colspan="6"
                class="text-center
                       py-4
                       text-danger">

                <i class="fas
                          fa-exclamation-circle
                          mr-1"></i>

                ${escapeHtml(message)}

            </td>

        </tr>

    `);

}


// =============================================================
// ESCAPE HTML
// =============================================================

function escapeHtml(text) {

    if (
        text === null ||
        text === undefined
    ) {

        return '';
    }


    return String(text)

        .replace(
            /&/g,
            '&amp;'
        )

        .replace(
            /</g,
            '&lt;'
        )

        .replace(
            />/g,
            '&gt;'
        )

        .replace(
            /"/g,
            '&quot;'
        )

        .replace(
            /'/g,
            '&#039;'
        );
}