<?php
/**
 * Schedules
 *
 * @var \App\View\AppView $this
 * @var iterable $schedules
 * @var \App\Model\Entity\Schedule $schedule
 */
?>

<div class="col-12">

    <div class="card card-primary">

        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="card-header">

            <h3 class="card-title text-dark">
                <i class="fas fa-calendar-alt mr-2"></i>
                Schedules
            </h3>

            <div class="card-tools">

                <?= $this->Html->link(
                    '<i class="fas fa-plus"></i>',
                    '#',
                    [
                        'id' => 'add',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'title' => 'Add Schedule',
                        'escape' => false
                    ]
                ) ?>

            </div>

        </div>


        <!-- =====================================================
             BODY
        ====================================================== -->

        <div class="card-body">

            <div class="schedule-planner">


                <!-- =================================================
                     CALENDAR
                ================================================== -->

                <div class="schedule-calendar-section">

                    <div class="calendar-header">

                        <button
                            type="button"
                            class="calendar-nav-btn"
                            id="previousMonth"
                            title="Previous Month">

                            <i class="fas fa-chevron-left"></i>

                        </button>


                        <div class="calendar-title-wrapper">

                            <h3 id="calendarMonthYear"></h3>

                            <button
                                type="button"
                                id="todayButton"
                                class="today-button">

                                Today

                            </button>

                        </div>


                        <button
                            type="button"
                            class="calendar-nav-btn"
                            id="nextMonth"
                            title="Next Month">

                            <i class="fas fa-chevron-right"></i>

                        </button>

                    </div>


                    <!-- =================================================
                         WEEK DAYS
                    ================================================== -->

                    <div class="calendar-weekdays">

                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>

                    </div>


                    <!-- =================================================
                         CALENDAR DAYS
                    ================================================== -->

                    <div
                        id="calendarDays"
                        class="calendar-days">
                    </div>


                    <!-- =================================================
                         LEGEND
                    ================================================== -->

                    <div class="calendar-legend">

                        <div class="legend-item">

                            <span class="legend-dot scheduled"></span>

                            <span>Scheduled</span>

                        </div>


                        <div class="legend-item">

                            <span class="legend-dot completed"></span>

                            <span>Completed</span>

                        </div>


                        <div class="legend-item">

                            <span class="legend-dot cancelled"></span>

                            <span>Cancelled</span>

                        </div>


                        <div class="legend-item">

                            <span class="legend-dot today"></span>

                            <span>Today</span>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     SCHEDULE LIST
                ================================================== -->

                <div class="schedule-list-section">

                    <div class="schedule-list-header">

                        <div class="schedule-list-heading">

                            <div class="schedule-list-icon">

                                <i class="fas fa-calendar-check"></i>

                            </div>


                            <div>

                                <h4>
                                    Schedule List
                                </h4>

                                <small id="selectedDateText">
                                    All Schedules
                                </small>

                            </div>

                        </div>


                        <div
                            id="scheduleCount"
                            class="schedule-count">

                            0

                        </div>

                    </div>


                    <div
                        id="scheduleList"
                        class="schedule-list">

                        <div class="schedule-empty">

                            <div class="schedule-empty-icon">

                                <i class="fas fa-calendar-times"></i>

                            </div>

                            <p>
                                No schedules available.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- ============================================================
     ADD / EDIT SCHEDULE MODAL
============================================================ -->

<div
    class="modal fade"
    id="schedules-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header bg-primary">

                <h4
                    id="modal-title"
                    class="modal-title">

                    Add Schedule

                </h4>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <!-- FORM -->

            <?= $this->Form->create(
                $schedule,
                [
                    'id' => 'schedules-form'
                ]
            ) ?>


            <div class="modal-body">

                <div class="row">


                    <!-- PROGRAM CODE -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="program-code">
                                Program Code
                            </label>

                            <?= $this->Form->control(
                                'program_code',
                                [
                                    'id' => 'program-code',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'placeholder' => 'Example: SD-1'
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- PROGRAM NAME -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="program-name">
                                Program Name
                            </label>

                            <?= $this->Form->control(
                                'program_name',
                                [
                                    'id' => 'program-name',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'placeholder' => 'Program name'
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="col-12">

                        <div class="form-group">

                            <label for="description">
                                Description
                            </label>

                            <?= $this->Form->control(
                                'description',
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'rows' => 3,
                                    'placeholder' => 'Schedule description'
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- BARANGAY -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="barangay">
                                Barangay
                            </label>

                            <?= $this->Form->control(
                                'barangay',
                                [
                                    'id' => 'barangay',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'placeholder' => 'Barangay'
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- START DATE -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="start-date">
                                Start Date
                            </label>

                            <?= $this->Form->control(
                                'start_date',
                                [
                                    'id' => 'start-date',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'label' => false
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- END DATE -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="end-date">
                                End Date
                            </label>

                            <?= $this->Form->control(
                                'end_date',
                                [
                                    'id' => 'end-date',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'label' => false
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- START TIME -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="start-time">
                                Start Time
                            </label>

                            <?= $this->Form->control(
                                'start_time',
                                [
                                    'id' => 'start-time',
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'label' => false
                                ]
                            ) ?>

                        </div>

                    </div>


                    <!-- END TIME -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="end-time">
                                End Time
                            </label>

                            <?= $this->Form->control(
                                'end_time',
                                [
                                    'id' => 'end-time',
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'label' => false
                                ]
                            ) ?>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->

            <div class="modal-footer">

                <?= $this->Form->control(
                    'id',
                    [
                        'id' => 'id',
                        'type' => 'hidden',
                        'label' => false
                    ]
                ) ?>


                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal">

                    Close

                </button>


                <button
                    type="submit"
                    id="saveScheduleButton"
                    class="btn btn-primary">

                    <i class="fas fa-save mr-1"></i>

                    <span id="saveScheduleText">
                        Save & Notify Farmers
                    </span>

                </button>

            </div>


            <?= $this->Form->end() ?>

        </div>

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       SCHEDULE DATA
    ========================================================= */

    let schedules = <?= json_encode(
        $schedules ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    ) ?>;


    /* =========================================================
       VARIABLES
    ========================================================= */

    let currentDate = new Date();
    let selectedDate = null;
    let formSubmitting = false;


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const calendarDays =
        document.getElementById('calendarDays');

    const calendarMonthYear =
        document.getElementById('calendarMonthYear');

    const scheduleList =
        document.getElementById('scheduleList');

    const selectedDateText =
        document.getElementById('selectedDateText');

    const scheduleCount =
        document.getElementById('scheduleCount');

    const scheduleForm =
        document.getElementById('schedules-form');

    const saveScheduleButton =
        document.getElementById('saveScheduleButton');

    const saveScheduleText =
        document.getElementById('saveScheduleText');


    /* =========================================================
       SAFETY CHECK
    ========================================================= */

    if (!calendarDays || !calendarMonthYear || !scheduleList) {
        console.error(
            'Schedule calendar elements could not be found.'
        );
        return;
    }


    /* =========================================================
       FORMAT DATE
    ========================================================= */

    function formatDate(date) {

        const year =
            date.getFullYear();

        const month =
            String(
                date.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                date.getDate()
            ).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }


    /* =========================================================
       NORMALIZE DATE
    ========================================================= */

    function normalizeDate(value) {

        if (!value) {
            return null;
        }

        return String(value).substring(0, 10);
    }


    /* =========================================================
       NORMALIZE TIME
    ========================================================= */

    function normalizeTime(value) {

        if (!value) {
            return '';
        }

        return String(value).substring(0, 5);
    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;
    }


    /* =========================================================
       SCHEDULE DATES
    ========================================================= */

    function getScheduleDates(schedule) {

        const start =
            normalizeDate(
                schedule.start_date
            );

        const end =
            normalizeDate(
                schedule.end_date ||
                schedule.start_date
            );

        return {
            start: start,
            end: end
        };
    }


    /* =========================================================
       CANCELLED
    ========================================================= */

    function isCancelled(schedule) {

        return String(
            schedule.status || ''
        )
        .trim()
        .toLowerCase() === 'cancelled';
    }


    /* =========================================================
       COMPLETED
    ========================================================= */

    function isCompleted(schedule) {

        /*
         * Cancelled always has priority.
         */

        if (isCancelled(schedule)) {
            return false;
        }


        /*
         * Database status is Completed.
         */

        if (
            String(
                schedule.status || ''
            )
            .trim()
            .toLowerCase() === 'completed'
        ) {
            return true;
        }


        const dates =
            getScheduleDates(schedule);

        const endDate =
            dates.end ||
            dates.start;


        if (!endDate) {
            return false;
        }


        const today =
            formatDate(
                new Date()
            );


        /*
         * A schedule whose end date
         * has already passed is displayed
         * as Completed.
         *
         * NOTE:
         * This does NOT update the database.
         */

        return endDate < today;
    }


    /* =========================================================
       GET STATUS
    ========================================================= */

    function getScheduleStatus(schedule) {

        if (isCancelled(schedule)) {
            return 'cancelled';
        }

        if (isCompleted(schedule)) {
            return 'completed';
        }

        return 'scheduled';
    }


    /* =========================================================
       SCHEDULES ON DATE
    ========================================================= */

    function schedulesOnDate(dateString) {

        if (!dateString) {
            return [];
        }

        return schedules.filter(
            function (schedule) {

                const dates =
                    getScheduleDates(schedule);


                if (!dates.start) {
                    return false;
                }


                const start =
                    new Date(
                        dates.start +
                        'T00:00:00'
                    );


                const end =
                    new Date(
                        (
                            dates.end ||
                            dates.start
                        ) +
                        'T23:59:59'
                    );


                const date =
                    new Date(
                        dateString +
                        'T12:00:00'
                    );


                return (
                    date >= start &&
                    date <= end
                );
            }
        );
    }


    /* =========================================================
       DISPLAY DATE
    ========================================================= */

    function formatDisplayDate(dateString) {

        if (!dateString) {
            return '';
        }


        const date =
            new Date(
                dateString +
                'T12:00:00'
            );


        if (isNaN(date.getTime())) {
            return dateString;
        }


        return date.toLocaleDateString(
            'en-US',
            {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            }
        );
    }


    /* =========================================================
       DISPLAY TIME
    ========================================================= */

    function formatDisplayTime(time) {

        if (!time) {
            return '';
        }


        const parts =
            String(time).split(':');


        let hour =
            parseInt(
                parts[0],
                10
            );


        const minute =
            parts[1] || '00';


        if (isNaN(hour)) {
            return time;
        }


        const suffix =
            hour >= 12
                ? 'PM'
                : 'AM';


        hour =
            hour % 12 || 12;


        return `${hour}:${minute} ${suffix}`;
    }


    /* =========================================================
       GET CSRF TOKEN
    ========================================================= */

    function getCsrfToken() {

        const csrfMeta =
            document.querySelector(
                'meta[name="csrfToken"]'
            );


        if (csrfMeta) {

            return csrfMeta.getAttribute(
                'content'
            ) || '';

        }


        return '<?= h(
            (string)$this->request->getAttribute('csrfToken')
        ) ?>';
    }


    /* =========================================================
       GET CANCEL URL
    ========================================================= */

    function getCancelUrl(id) {

        return '<?= $this->Url->build(
            '/api/Schedules/cancel'
        ) ?>/' +
        encodeURIComponent(id);
    }


    /* =========================================================
       RENDER CALENDAR
    ========================================================= */

    function renderCalendar() {

        calendarDays.innerHTML = '';


        const year =
            currentDate.getFullYear();

        const month =
            currentDate.getMonth();


        const monthName =
            currentDate.toLocaleString(
                'default',
                {
                    month: 'long'
                }
            );


        calendarMonthYear.textContent =
            `${monthName} ${year}`;


        const firstDay =
            new Date(
                year,
                month,
                1
            ).getDay();


        const daysInMonth =
            new Date(
                year,
                month + 1,
                0
            ).getDate();


        const daysInPreviousMonth =
            new Date(
                year,
                month,
                0
            ).getDate();


        const totalCells =
            Math.ceil(
                (
                    firstDay +
                    daysInMonth
                ) / 7
            ) * 7;


        const today =
            formatDate(
                new Date()
            );


        for (
            let i = 0;
            i < totalCells;
            i++
        ) {

            let dayNumber;
            let cellDate;
            let otherMonth = false;


            /* =================================================
               PREVIOUS MONTH
            ================================================= */

            if (i < firstDay) {

                dayNumber =
                    daysInPreviousMonth -
                    firstDay +
                    i +
                    1;


                cellDate =
                    formatDate(
                        new Date(
                            year,
                            month - 1,
                            dayNumber
                        )
                    );


                otherMonth = true;
            }


            /* =================================================
               CURRENT MONTH
            ================================================= */

            else if (
                i <
                firstDay +
                daysInMonth
            ) {

                dayNumber =
                    i -
                    firstDay +
                    1;


                cellDate =
                    formatDate(
                        new Date(
                            year,
                            month,
                            dayNumber
                        )
                    );
            }


            /* =================================================
               NEXT MONTH
            ================================================= */

            else {

                dayNumber =
                    i -
                    (
                        firstDay +
                        daysInMonth
                    ) +
                    1;


                cellDate =
                    formatDate(
                        new Date(
                            year,
                            month + 1,
                            dayNumber
                        )
                    );


                otherMonth = true;
            }


            const day =
                document.createElement('div');


            day.className =
                'calendar-day';


            if (otherMonth) {

                day.classList.add(
                    'other-month'
                );
            }


            if (cellDate === today) {

                day.classList.add(
                    'today'
                );
            }


            if (cellDate === selectedDate) {

                day.classList.add(
                    'selected'
                );
            }


            /* =================================================
               DAY NUMBER
            ================================================= */

            const number =
                document.createElement('div');


            number.className =
                'calendar-day-number';


            number.textContent =
                dayNumber;


            day.appendChild(
                number
            );


            /* =================================================
               SCHEDULES
            ================================================= */

            const daySchedules =
                schedulesOnDate(
                    cellDate
                );


            daySchedules
                .slice(0, 3)
                .forEach(
                    function (schedule) {

                        const event =
                            document.createElement(
                                'div'
                            );


                        event.className =
                            'calendar-event';


                        const status =
                            getScheduleStatus(
                                schedule
                            );


                        event.classList.add(
                            status
                        );


                        event.textContent =
                            schedule.program_code ||
                            schedule.program_name ||
                            'Schedule';


                        event.title =
                            schedule.program_name ||
                            schedule.program_code ||
                            'Schedule';


                        day.appendChild(
                            event
                        );
                    }
                );


            /* =================================================
               MORE
            ================================================= */

            if (
                daySchedules.length > 3
            ) {

                const more =
                    document.createElement(
                        'small'
                    );


                more.className =
                    'calendar-more';


                more.textContent =
                    `+${daySchedules.length - 3} more`;


                day.appendChild(
                    more
                );
            }


            /* =================================================
               CLICK
            ================================================= */

            day.addEventListener(
                'click',
                function () {

                    selectedDate =
                        cellDate;


                    renderCalendar();


                    renderScheduleList(
                        schedulesOnDate(
                            cellDate
                        ),
                        cellDate
                    );
                }
            );


            calendarDays.appendChild(
                day
            );
        }
    }


    /* =========================================================
       RENDER LIST
    ========================================================= */

    function renderScheduleList(
        list,
        date = null
    ) {

        scheduleList.innerHTML = '';


        if (date) {

            selectedDateText.textContent =
                formatDisplayDate(
                    date
                );

        }
        else {

            selectedDateText.textContent =
                'All Schedules';

        }


        scheduleCount.textContent =
            list
                ? list.length
                : 0;


        /* =================================================
           EMPTY
        ================================================= */

        if (
            !list ||
            list.length === 0
        ) {

            scheduleList.innerHTML = `

                <div class="schedule-empty">

                    <div class="schedule-empty-icon">

                        <i class="fas fa-calendar-times"></i>

                    </div>

                    <p>
                        No schedules
                        ${
                            date
                                ? 'for this date'
                                : 'available'
                        }.
                    </p>

                </div>

            `;

            return;
        }


        /* =================================================
           SORT
        ================================================= */

        list =
            [...list].sort(
                function (a, b) {

                    const dateA =
                        normalizeDate(
                            a.start_date
                        ) || '';


                    const dateB =
                        normalizeDate(
                            b.start_date
                        ) || '';


                    if (
                        dateA !== dateB
                    ) {

                        return dateA.localeCompare(
                            dateB
                        );
                    }


                    const timeA =
                        normalizeTime(
                            a.start_time
                        ) || '';


                    const timeB =
                        normalizeTime(
                            b.start_time
                        ) || '';


                    return timeA.localeCompare(
                        timeB
                    );
                }
            );


        /* =================================================
           CREATE ITEMS
        ================================================= */

        list.forEach(
            function (schedule) {

                const item =
                    document.createElement(
                        'div'
                    );


                item.className =
                    'schedule-item';


                const status =
                    getScheduleStatus(
                        schedule
                    );


                if (
                    status === 'cancelled'
                ) {

                    item.classList.add(
                        'cancelled'
                    );
                }


                if (
                    status === 'completed'
                ) {

                    item.classList.add(
                        'completed'
                    );
                }


                const code =
                    schedule.program_code ||
                    '';


                const name =
                    schedule.program_name ||
                    'Schedule';


                const description =
                    schedule.description ||
                    '';


                const barangay =
                    schedule.barangay ||
                    '';


                const startDate =
                    normalizeDate(
                        schedule.start_date
                    );


                const endDate =
                    normalizeDate(
                        schedule.end_date
                    );


                const startTime =
                    normalizeTime(
                        schedule.start_time
                    );


                const endTime =
                    normalizeTime(
                        schedule.end_time
                    );


                /* =================================================
                   DATE
                ================================================= */

                let dateText =
                    formatDisplayDate(
                        startDate
                    );


                if (
                    endDate &&
                    endDate !== startDate
                ) {

                    dateText +=
                        ' - ' +
                        formatDisplayDate(
                            endDate
                        );
                }


                /* =================================================
                   STATUS
                ================================================= */

                let statusHtml = '';


                if (
                    status === 'cancelled'
                ) {

                    statusHtml = `

                        <span class="schedule-status cancelled">

                            <i class="fas fa-ban mr-1"></i>

                            Cancelled

                        </span>

                    `;

                }
                else if (
                    status === 'completed'
                ) {

                    statusHtml = `

                        <span class="schedule-status completed">

                            <i class="fas fa-check-double mr-1"></i>

                            Completed

                        </span>

                    `;

                }
                else {

                    statusHtml = `

                        <span class="schedule-status active">

                            <i class="fas fa-check-circle mr-1"></i>

                            Scheduled

                        </span>

                    `;
                }


                /* =================================================
                   ACTIONS
                ================================================= */

                let actionHtml = '';


                if (
                    status === 'cancelled'
                ) {

                    actionHtml = `

                        <div class="cancelled-message">

                            <i class="fas fa-ban mr-1"></i>

                            This schedule has been cancelled.

                        </div>

                    `;

                }
                else if (
                    status === 'completed'
                ) {

                    actionHtml = `

                        <div class="completed-message">

                            <i class="fas fa-check-double mr-1"></i>

                            This schedule has been completed.

                        </div>

                    `;

                }
                else {

                    actionHtml = `

                        <button
                            type="button"
                            class="btn btn-outline-primary edit-schedule"
                            data-id="${escapeHtml(schedule.id)}">

                            <i class="fas fa-edit mr-1"></i>

                            Edit

                        </button>


                        <button
                            type="button"
                            class="btn btn-outline-danger cancel-schedule"
                            data-id="${escapeHtml(schedule.id)}">

                            <i class="fas fa-ban mr-1"></i>

                            Cancel

                        </button>

                    `;
                }


                /* =================================================
                   ITEM HTML
                ================================================= */

                item.innerHTML = `

                    <div class="schedule-item-top">

                        <div class="schedule-item-code">

                            ${escapeHtml(code)}

                        </div>

                        ${statusHtml}

                    </div>


                    <div class="schedule-item-title">

                        ${escapeHtml(name)}

                    </div>


                    ${
                        description
                        ? `

                            <div class="schedule-item-location">

                                <i class="fas fa-info-circle"></i>

                                <span>

                                    ${escapeHtml(description)}

                                </span>

                            </div>

                        `
                        : ''
                    }


                    ${
                        barangay
                        ? `

                            <div class="schedule-item-location">

                                <i class="fas fa-map-marker-alt"></i>

                                <span>

                                    ${escapeHtml(barangay)}

                                </span>

                            </div>

                        `
                        : ''
                    }


                    <div class="schedule-item-date">

                        <i class="far fa-calendar-alt"></i>

                        <span>

                            ${escapeHtml(dateText)}

                        </span>

                    </div>


                    ${
                        startTime
                        ? `

                            <div class="schedule-item-time">

                                <i class="far fa-clock"></i>

                                <span>

                                    ${escapeHtml(
                                        formatDisplayTime(
                                            startTime
                                        )
                                    )}

                                    ${
                                        endTime
                                        ? `

                                            -

                                            ${escapeHtml(
                                                formatDisplayTime(
                                                    endTime
                                                )
                                            )}

                                        `
                                        : ''
                                    }

                                </span>

                            </div>

                        `
                        : ''
                    }


                    <div class="schedule-item-actions">

                        ${actionHtml}

                    </div>

                `;


                scheduleList.appendChild(
                    item
                );

            }
        );
    }


    /* =========================================================
       ADD SCHEDULE
    ========================================================= */

    if ($('#add').length) {

        $('#add').on(
            'click',
            function (e) {

                e.preventDefault();


                scheduleForm.reset();


                $('#id').val('');


                $('#modal-title').text(
                    'Add Schedule'
                );


                saveScheduleText.textContent =
                    'Save & Notify Farmers';


                saveScheduleButton.disabled =
                    false;


                saveScheduleButton.classList.remove(
                    'disabled'
                );


                formSubmitting =
                    false;


                $('#schedules-modal').modal(
                    'show'
                );
            }
        );
    }


    /* =========================================================
       EDIT SCHEDULE
    ========================================================= */

    document.addEventListener(
        'click',
        function (e) {

            const editButton =
                e.target.closest(
                    '.edit-schedule'
                );


            if (!editButton) {
                return;
            }


            const id =
                editButton.getAttribute(
                    'data-id'
                );


            const schedule =
                schedules.find(
                    function (item) {

                        return String(
                            item.id
                        ) === String(id);

                    }
                );


            if (!schedule) {

                alert(
                    'Schedule information could not be found.'
                );

                return;
            }


            if (isCancelled(schedule)) {

                alert(
                    'This schedule has already been cancelled and cannot be edited.'
                );

                return;
            }


            if (isCompleted(schedule)) {

                alert(
                    'This schedule has already been completed and cannot be edited.'
                );

                return;
            }


            $('#modal-title').text(
                'Edit Schedule'
            );


            $('#program-code').val(
                schedule.program_code || ''
            );


            $('#program-name').val(
                schedule.program_name || ''
            );


            $('#description').val(
                schedule.description || ''
            );


            $('#barangay').val(
                schedule.barangay || ''
            );


            $('#start-date').val(
                normalizeDate(
                    schedule.start_date
                ) || ''
            );


            $('#end-date').val(
                normalizeDate(
                    schedule.end_date
                ) || ''
            );


            $('#start-time').val(
                normalizeTime(
                    schedule.start_time
                ) || ''
            );


            $('#end-time').val(
                normalizeTime(
                    schedule.end_time
                ) || ''
            );


            $('#id').val(
                schedule.id
            );


            formSubmitting =
                false;


            saveScheduleButton.disabled =
                false;


            saveScheduleText.textContent =
                'Update & Notify Farmers';


            $('#schedules-modal').modal(
                'show'
            );
        }
    );


    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    if (scheduleForm) {

        scheduleForm.addEventListener(
            'submit',
            function (e) {

                if (formSubmitting) {

                    e.preventDefault();

                    return false;
                }


                const startDate =
                    $('#start-date').val();


                const endDate =
                    $('#end-date').val();


                /* =================================================
                   DATE VALIDATION
                ================================================= */

                if (
                    startDate &&
                    endDate &&
                    endDate < startDate
                ) {

                    e.preventDefault();


                    alert(
                        'End date cannot be earlier than start date.'
                    );


                    return false;
                }


                /* =================================================
                   DUPLICATE VALIDATION
                ================================================= */

                const currentId =
                    $('#id').val();


                const programCode =
                    String(
                        $('#program-code').val()
                    )
                    .trim()
                    .toLowerCase();


                if (
                    !currentId &&
                    programCode
                ) {

                    const duplicate =
                        schedules.some(
                            function (schedule) {

                                return (
                                    !isCancelled(schedule) &&
                                    !isCompleted(schedule) &&

                                    String(
                                        schedule.program_code || ''
                                    )
                                    .trim()
                                    .toLowerCase() ===
                                    programCode &&

                                    normalizeDate(
                                        schedule.start_date
                                    ) ===
                                    startDate &&

                                    normalizeDate(
                                        schedule.end_date
                                    ) ===
                                    endDate
                                );
                            }
                        );


                    if (duplicate) {

                        e.preventDefault();


                        alert(
                            'A schedule with the same Program Code and date already exists.'
                        );


                        return false;
                    }
                }


                /* =================================================
                   LOCK SUBMIT
                ================================================= */

                formSubmitting =
                    true;


                saveScheduleButton.disabled =
                    true;


                saveScheduleButton.classList.add(
                    'disabled'
                );


                saveScheduleText.textContent =
                    currentId
                        ? 'Updating...'
                        : 'Saving...';
            }
        );
    }


    /* =========================================================
       CANCEL SCHEDULE
    ========================================================= */

    document.addEventListener(
        'click',
        async function (e) {

            const cancelButton =
                e.target.closest(
                    '.cancel-schedule'
                );


            if (!cancelButton) {
                return;
            }


            e.preventDefault();


            const id =
                cancelButton.getAttribute(
                    'data-id'
                );


            /* =================================================
               VALIDATE ID
            ================================================= */

            if (!id) {

                alert(
                    'Schedule ID is missing.'
                );

                return;
            }


            /* =================================================
               FIND SCHEDULE
            ================================================= */

            const schedule =
                schedules.find(
                    function (item) {

                        return String(
                            item.id
                        ) === String(id);

                    }
                );


            if (!schedule) {

                alert(
                    'Schedule information could not be found.'
                );

                return;
            }


            /* =================================================
               CHECK CANCELLED
            ================================================= */

            if (isCancelled(schedule)) {

                alert(
                    'This schedule is already cancelled.'
                );

                return;
            }


            /* =================================================
               CHECK COMPLETED
            ================================================= */

            if (isCompleted(schedule)) {

                alert(
                    'This schedule has already been completed and cannot be cancelled.'
                );

                return;
            }


            /* =================================================
               PROGRAM
            ================================================= */

            const program =
                schedule.program_code ||
                schedule.program_name ||
                'this schedule';


            /* =================================================
               CONFIRM
            ================================================= */

            const confirmed =
                confirm(
                    'Are you sure you want to cancel ' +
                    program +
                    '?\n\n' +
                    'The schedule will be marked as Cancelled.'
                );


            if (!confirmed) {
                return;
            }


            /* =================================================
               LOCK BUTTON
            ================================================= */

            cancelButton.disabled =
                true;


            const originalButtonHTML =
                cancelButton.innerHTML;


            cancelButton.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i> Cancelling...';


            /* =================================================
               CSRF
            ================================================= */

            const csrfToken =
                getCsrfToken();


            /* =================================================
               CANCEL URL
            ================================================= */

            const cancelUrl =
                getCancelUrl(id);


            console.log(
                'Cancel URL:',
                cancelUrl
            );


            console.log(
                'Cancelling Schedule ID:',
                id
            );


            /* =================================================
               SEND REQUEST
               
               IMPORTANT:
               Only send the ID.
               The API controller decides
               the new status.
            ================================================= */

            try {

                const response =
                    await fetch(
                        cancelUrl,
                        {

                            method: 'POST',

                            credentials: 'same-origin',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-Token':
                                    csrfToken,

                                'X-Requested-With':
                                    'XMLHttpRequest'

                            },

                            body:
                                JSON.stringify({
                                    id: id
                                })
                        }
                    );


                /* =================================================
                   READ SERVER RESPONSE
                ================================================= */

                const responseText =
                    await response.text();


                console.log(
                    'Cancel HTTP status:',
                    response.status
                );


                console.log(
                    'Cancel response:',
                    responseText
                );


                let data;


                try {

                    data =
                        JSON.parse(
                            responseText
                        );

                }
                catch (jsonError) {

                    throw new Error(
                        'The server returned an invalid response.'
                    );
                }


                /* =================================================
                   HTTP ERROR
                ================================================= */

                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        data.error ||
                        `HTTP ${response.status}`
                    );
                }


                /* =================================================
                   APPLICATION ERROR
                ================================================= */

                if (
                    data.success !== true &&
                    data.status !== 'success'
                ) {

                    throw new Error(
                        data.message ||
                        data.error ||
                        'Unable to cancel the schedule.'
                    );
                }


                /* =================================================
                   UPDATE LOCAL DATA
                ================================================= */

                const index =
                    schedules.findIndex(
                        function (item) {

                            return String(
                                item.id
                            ) === String(id);

                        }
                    );


                if (index !== -1) {

                    schedules[index].status =
                        data.schedule_status ||
                        'Cancelled';
                }


                /* =================================================
                   REFRESH CALENDAR
                ================================================= */

                renderCalendar();


                /* =================================================
                   REFRESH LIST
                ================================================= */

                if (selectedDate) {

                    renderScheduleList(
                        schedulesOnDate(
                            selectedDate
                        ),
                        selectedDate
                    );

                }
                else {

                    renderScheduleList(
                        schedules
                    );
                }


                /* =================================================
                   SUCCESS MESSAGE
                ================================================= */

                alert(
                    data.message ||
                    'Schedule has been cancelled successfully.'
                );


            }
            catch (error) {

                console.error(
                    'Cancel error:',
                    error
                );


                /* =================================================
                   RESTORE BUTTON
                ================================================= */

                cancelButton.disabled =
                    false;


                cancelButton.innerHTML =
                    originalButtonHTML;


                alert(
                    'Unable to cancel the schedule.\n\n' +
                    error.message
                );
            }

        }
    );


    /* =========================================================
       PREVIOUS MONTH
    ========================================================= */

    const previousMonth =
        document.getElementById(
            'previousMonth'
        );


    if (previousMonth) {

        previousMonth.addEventListener(
            'click',
            function () {

                currentDate.setMonth(
                    currentDate.getMonth() - 1
                );


                selectedDate =
                    null;


                renderCalendar();


                renderScheduleList(
                    schedules
                );
            }
        );
    }


    /* =========================================================
       NEXT MONTH
    ========================================================= */

    const nextMonth =
        document.getElementById(
            'nextMonth'
        );


    if (nextMonth) {

        nextMonth.addEventListener(
            'click',
            function () {

                currentDate.setMonth(
                    currentDate.getMonth() + 1
                );


                selectedDate =
                    null;


                renderCalendar();


                renderScheduleList(
                    schedules
                );
            }
        );
    }


    /* =========================================================
       TODAY
    ========================================================= */

    const todayButton =
        document.getElementById(
            'todayButton'
        );


    if (todayButton) {

        todayButton.addEventListener(
            'click',
            function () {

                currentDate =
                    new Date();


                selectedDate =
                    formatDate(
                        new Date()
                    );


                renderCalendar();


                renderScheduleList(
                    schedulesOnDate(
                        selectedDate
                    ),
                    selectedDate
                );
            }
        );
    }


    /* =========================================================
       MODAL RESET
    ========================================================= */

    $('#schedules-modal').on(
        'hidden.bs.modal',
        function () {

            formSubmitting =
                false;


            if (saveScheduleButton) {

                saveScheduleButton.disabled =
                    false;


                saveScheduleButton.classList.remove(
                    'disabled'
                );
            }
        }
    );


    /* =========================================================
       INITIAL RENDER
    ========================================================= */

    renderCalendar();


    renderScheduleList(
        schedules
    );


    /* =========================================================
       AUTO CHECK COMPLETED
       
       Every 60 seconds
       
       IMPORTANT:
       This only updates the UI.
       It does NOT change the database.
    ========================================================= */

    setInterval(
        function () {

            renderCalendar();


            if (selectedDate) {

                renderScheduleList(
                    schedulesOnDate(
                        selectedDate
                    ),
                    selectedDate
                );

            }
            else {

                renderScheduleList(
                    schedules
                );
            }

        },
        60000
    );

});

</script>