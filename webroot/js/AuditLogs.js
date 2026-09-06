$(function () {

    'use strict';

    /* =========================================================
       AUDIT LOGS DATATABLE
    ========================================================= */

    const auditTable = $('#audit-logs-table').DataTable({

        processing: true,
        serverSide: false,

        ajax: {

            /*
             * Your routes.php has:
             *
             * /api/AuditLogs/  -> index()
             *
             * So use the index route directly.
             */
            url: BASE_URL + '/api/AuditLogs/',
            type: 'GET',

            dataType: 'json',

            dataSrc: function (json) {

                console.log(
                    'Audit Logs Response:',
                    json
                );

                /*
                 * FORMAT 1:
                 *
                 * {
                 *     success: true,
                 *     data: [...]
                 * }
                 */
                if (
                    json &&
                    !Array.isArray(json) &&
                    Array.isArray(json.data)
                ) {

                    return json.data;
                }


                /*
                 * FORMAT 2:
                 *
                 * [...]
                 */
                if (Array.isArray(json)) {

                    return json;
                }


                /*
                 * Invalid response
                 */
                console.error(
                    'Invalid Audit Logs response:',
                    json
                );

                return [];
            },

            error: function (xhr, status, error) {

                console.error(
                    '================================='
                );

                console.error(
                    'Audit Logs AJAX Error'
                );

                console.error(
                    'Status:',
                    xhr.status
                );

                console.error(
                    'Status Text:',
                    status
                );

                console.error(
                    'Error:',
                    error
                );

                console.error(
                    'Response:',
                    xhr.responseText
                );

                console.error(
                    '================================='
                );
            }
        },


        /* =====================================================
           COLUMNS
        ===================================================== */

        columns: [

            /* -----------------------------------------------
               ACTION
            ------------------------------------------------ */

            {
                data: 'action',

                defaultContent: 'N/A',

                render: function (data, type, row) {

                    if (
                        data === null ||
                        data === undefined ||
                        data === ''
                    ) {

                        return `
                            <span class="text-muted">
                                N/A
                            </span>
                        `;
                    }

                    return `
                        <span class="audit-action">
                            <i class="fas fa-history mr-2"></i>
                            ${escapeHtml(data)}
                        </span>
                    `;
                }
            },


            /* -----------------------------------------------
               ACTION DATE
            ------------------------------------------------ */

            {
                data: 'action_date',

                defaultContent: 'N/A',

                render: function (data, type, row) {

                    if (
                        data === null ||
                        data === undefined ||
                        data === ''
                    ) {

                        return `
                            <span class="text-muted">
                                N/A
                            </span>
                        `;
                    }


                    /*
                     * Convert:
                     *
                     * 2026-08-29 20:45:00
                     *
                     * to:
                     *
                     * 2026-08-29T20:45:00
                     */
                    const date = new Date(
                        String(data).replace(' ', 'T')
                    );


                    /*
                     * Invalid date
                     */
                    if (isNaN(date.getTime())) {

                        return `
                            <span class="text-muted">
                                ${escapeHtml(data)}
                            </span>
                        `;
                    }


                    return `
                        <div class="audit-date">

                            <div>
                                <i class="far fa-calendar-alt mr-1"></i>

                                <strong>
                                    ${date.toLocaleDateString(
                                        'en-US',
                                        {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric'
                                        }
                                    )}
                                </strong>
                            </div>

                            <small class="text-muted">

                                <i class="far fa-clock mr-1"></i>

                                ${date.toLocaleTimeString(
                                    'en-US',
                                    {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }
                                )}

                            </small>

                        </div>
                    `;
                }
            }

        ],


        /* =====================================================
           DEFAULT SORT
        ===================================================== */

        order: [
            [1, 'desc']
        ],


        /* =====================================================
           PAGINATION
        ===================================================== */

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        responsive: true,


        /* =====================================================
           LANGUAGE
        ===================================================== */

        language: {

            search: '',

            searchPlaceholder:
                'Search audit logs...',

            lengthMenu:
                'Show _MENU_ logs',

            info:
                'Showing _START_ to _END_ of _TOTAL_ audit logs',

            infoEmpty:
                'No audit logs available',

            emptyTable: `
                <div class="py-4 text-center text-muted">

                    <i class="fas fa-history fa-2x mb-2"></i>

                    <div>
                        No audit logs found.
                    </div>

                </div>
            `,

            zeroRecords: `
                <div class="py-4 text-center text-muted">

                    <i class="fas fa-search fa-2x mb-2"></i>

                    <div>
                        No matching audit logs found.
                    </div>

                </div>
            `,

            processing: `
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Loading audit logs...
            `
        }

    });


    /* =========================================================
       RELOAD AUDIT LOGS
    ========================================================= */

    window.reloadAuditLogs = function () {

        console.log(
            'Reloading audit logs...'
        );

        auditTable.ajax.reload(
            null,
            false
        );
    };


    /* =========================================================
       GET SINGLE AUDIT LOG
    ========================================================= */

    window.getAuditLog = function (id) {

        if (
            id === null ||
            id === undefined ||
            id === ''
        ) {

            console.error(
                'Audit Log ID is required.'
            );

            return;
        }


        console.log(
            'Getting Audit Log:',
            id
        );


        $.ajax({

            url:
                BASE_URL +
                '/api/AuditLogs/view/' +
                encodeURIComponent(id),

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                console.log(
                    'Single Audit Log Response:',
                    response
                );


                if (
                    response &&
                    response.success &&
                    response.data
                ) {

                    console.log(
                        'Audit Log Data:',
                        response.data
                    );

                    return response.data;
                }


                console.error(
                    'Unable to retrieve audit log:',
                    response
                );
            },

            error: function (xhr, status, error) {

                console.error(
                    'Get Audit Log Error:',
                    xhr.status,
                    status,
                    error
                );

                console.error(
                    'Response:',
                    xhr.responseText
                );
            }

        });

    };


    /* =========================================================
       REFRESH BUTTON
       
       You can use:
       
       <button onclick="reloadAuditLogs()">
           Refresh
       </button>
    ========================================================= */

    $(document).on(
        'click',
        '#refresh-audit-logs',
        function (e) {

            e.preventDefault();

            reloadAuditLogs();
        }
    );


    /* =========================================================
       VIEW AUDIT LOG BUTTON
       
       Example:
       
       <button
           class="view-audit-log"
           data-id="1">
           View
       </button>
    ========================================================= */

    $(document).on(
        'click',
        '.view-audit-log',
        function (e) {

            e.preventDefault();

            const id = $(this).data('id');

            getAuditLog(id);
        }
    );


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';
        }

        return $('<div>')
            .text(String(value))
            .html();
    }


    /* =========================================================
       DEBUG
    ========================================================= */

    console.log(
        'AuditLogs.js loaded successfully.'
    );

});