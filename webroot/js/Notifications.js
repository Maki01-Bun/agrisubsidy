$(function () {

    getNotifications();

    // Add Notification Modal
    $('#add').on('click', function (e) {
        e.preventDefault();

        $('.modal-title').html('Add Notification');
        $('#notifications-modal').modal('show');
    });

    // View Notification
    $('#notifications-table').on('click', '.view', function (e) {

        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            url: BASE_URL + '/api/Notifications/view/' + id,
            type: 'GET',
            dataType: 'json'
        })
        .done(function (data) {

            $('#message').text(data.message);

            if (data.status) {
                $('#status').text(data.status);
            } else {
                $('#status').text(data.is_read == 1 ? 'Read' : 'Unread');
            }

            $('#notifications-modal').modal('show');

            markAsRead(id);

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });

    });

    // Approve Registration
    $('#notifications-table').on('click', '.approve', function (e) {

        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            url: BASE_URL + '/api/Notifications/approveRegistration/' + id,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': $('[name="_csrfToken"]').val()
            }
        })
        .done(function (data) {

            msgBox(data.status, data.message);

            if (data.status === 'success') {
                $('#notifications-table').DataTable().ajax.reload();
            }

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });

    });

    // Decline Registration
    $('#notifications-table').on('click', '.decline', function (e) {

        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            url: BASE_URL + '/api/Notifications/declineRegistration/' + id,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': $('[name="_csrfToken"]').val()
            }
        })
        .done(function (data) {

            msgBox(data.status, data.message);

            if (data.status === 'success') {
                $('#notifications-table').DataTable().ajax.reload();
            }

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            msgBox('error', errorThrown);
        });

    });

    // Delete Notification
    $('#notifications-table').on('click', '.delete', function (e) {

        e.preventDefault();

        let id = $(this).data('id');

        isDelete(function (confirmed) {

            if (confirmed) {

                $.ajax({
                    url: BASE_URL + '/api/Notifications/delete/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    }
                })
                .done(function (data) {

                    msgBox(data.status, data.message);

                    if (data.status === 'success') {
                        $('#notifications-table').DataTable().ajax.reload();
                    }

                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    msgBox('error', errorThrown);
                });

            }

        });

    });

    // Reset Modal
    $('#notifications-modal').on('hidden.bs.modal', function () {

        $('#message').text('');
        $('#status').text('');

    });

});


// Load Notifications
function getNotifications() {

    $('#notifications-table').DataTable({

        responsive: true,
        destroy: true,
        processing: true,

        order: [[2, 'desc']],

        ajax: {
            url: BASE_URL + '/api/Notifications/getNotifications',
            type: 'GET'
        },

        columns: [

            {
                data: 'message'
            },

            {
                data: null,
                render: function (data) {

                    if (data.status) {

                        if (data.status === 'approved') {
                            return '<span class="badge bg-success">Approved</span>';
                        }

                        if (data.status === 'declined') {
                            return '<span class="badge bg-danger">Declined</span>';
                        }

                        return '<span class="badge bg-warning">Pending</span>';
                    }

                    return data.is_read == 1
                        ? '<span class="badge bg-success">Read</span>'
                        : '<span class="badge bg-warning">Unread</span>';
                }
            },

            {
                data: 'created'
            },

            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {

                    let buttons = `
                        <div class="text-center">

                            <a href="#" 
                               class="view"
                               data-id="${data.id}"
                               title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                    `;

                    if (data.status === 'pending') {

                        buttons += `
                            |
                            <a href="#"
                               class="approve text-success"
                               data-id="${data.id}"
                               title="Approve">
                                <i class="fa fa-check"></i>
                            </a>

                            |
                            <a href="#"
                               class="decline text-danger"
                               data-id="${data.id}"
                               title="Decline">
                                <i class="fa fa-times"></i>
                            </a>
                        `;
                    }

                    buttons += `
                            |
                            <a href="#"
                               class="delete text-danger"
                               data-id="${data.id}"
                               title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>

                        </div>
                    `;

                    return buttons;
                }
            }

        ]

    });

}


// Mark Notification as Read
function markAsRead(id) {

    $.ajax({
        url: BASE_URL + '/api/Notifications/markAsRead/' + id,
        type: 'POST',
        headers: {
            'X-CSRF-Token': $('[name="_csrfToken"]').val()
        }
    });

}