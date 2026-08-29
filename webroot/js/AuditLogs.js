$(function () {

    $('#audit-logs-table').DataTable({

        processing: true,
        serverSide: false,

        ajax: {

            url: '/agrisubsidy/api/AuditLogs/index',
            type: 'GET',

            dataSrc: function (json) {

                console.log('Audit Logs Response:', json);

                if (
                    json &&
                    Array.isArray(json.data)
                ) {
                    return json.data;
                }

                return [];
            },

            error: function (xhr) {

                console.error(
                    'Audit Logs AJAX Error:',
                    xhr.responseText
                );
            }
        },


        columns: [

            /* =========================
               ACTION
            ========================== */
            {
                data: 'action',
                defaultContent: 'N/A',

                render: function (data) {

                    if (!data) {
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


            /* =========================
               ACTION DATE
            ========================== */
            {
                data: 'action_date',
                defaultContent: 'N/A',

                render: function (data) {

                    if (!data) {
                        return `
                            <span class="text-muted">
                                N/A
                            </span>
                        `;
                    }

                    /*
                     * Convert:
                     * 2026-08-29 20:45:00
                     *
                     * to:
                     * 2026-08-29T20:45:00
                     */
                    const date = new Date(
                        data.replace(' ', 'T')
                    );

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


        /* =========================
           DEFAULT SORT
        ========================== */

        order: [
            [1, 'desc']
        ],


        /* =========================
           PAGINATION
        ========================== */

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],


        /* =========================
           RESPONSIVE
        ========================== */

        responsive: true,


        /* =========================
           LANGUAGE
        ========================== */

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


    /* =========================
       ESCAPE HTML
    ========================== */

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }

        return $('<div>')
            .text(value)
            .html();
    }

});
