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

                            <span
                                class="legend-dot scheduled">
                            </span>

                            <span>
                                Scheduled
                            </span>

                        </div>


                        <div class="legend-item">

                            <span
                                class="legend-dot rescheduled">
                            </span>

                            <span>
                                Re-Scheduled
                            </span>

                        </div>


                        <div class="legend-item">

                            <span
                                class="legend-dot completed">
                            </span>

                            <span>
                                Completed
                            </span>

                        </div>


                        <div class="legend-item">

                            <span
                                class="legend-dot cancelled">
                            </span>

                            <span>
                                Cancelled
                            </span>

                        </div>


                        <div class="legend-item">

                            <span
                                class="legend-dot today">
                            </span>

                            <span>
                                Today
                            </span>

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


<style>

/* =========================================================
   CALENDAR LEGEND
========================================================= */

.calendar-legend {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 16px;
    padding: 15px 5px 5px;
}


.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: #555;
    white-space: nowrap;
}


.legend-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    min-width: 12px;
    border-radius: 50%;
}


/* Scheduled */

.legend-dot.scheduled {
    background-color: #007bff;
}


/* Re-Scheduled */

.legend-dot.rescheduled {
    background-color: #ffc107;
}


/* Completed */

.legend-dot.completed {
    background-color: #28a745;
}


/* Cancelled */

.legend-dot.cancelled {
    background-color: #dc3545;
}


/* Today */

.legend-dot.today {
    background-color: #6f42c1;
}


/* =========================================================
   CALENDAR EVENTS
========================================================= */

.calendar-event {
    display: block;
    width: 100%;
    margin-top: 3px;
    padding: 3px 5px;
    border-radius: 4px;
    font-size: 11px;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


/* Scheduled */

.calendar-event.scheduled {
    background-color: #007bff;
    color: #fff;
}


/* Re-Scheduled */

.calendar-event.rescheduled {
    background-color: #ffc107;
    color: #212529;
}


/* Completed */

.calendar-event.completed {
    background-color: #28a745;
    color: #fff;
}


/* Cancelled */

.calendar-event.cancelled {
    background-color: #dc3545;
    color: #fff;
}


/* =========================================================
   TODAY
========================================================= */

.calendar-day.today {
    border: 2px solid #6f42c1;
}


.calendar-day.today .calendar-day-number {
    color: #6f42c1;
    font-weight: 700;
}


/* =========================================================
   SCHEDULE LIST STATUS
========================================================= */

.schedule-item.rescheduled {
    border-left: 4px solid #ffc107;
}


.schedule-item.completed {
    border-left: 4px solid #28a745;
}


.schedule-item.cancelled {
    border-left: 4px solid #dc3545;
}


/* Scheduled */

.schedule-status.active {
    color: #0056b3;
    background: #e7f1ff;
    border: 1px solid #b8daff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}


/* Re-Scheduled */

.schedule-status.rescheduled {
    color: #856404;
    background: #fff3cd;
    border: 1px solid #ffeeba;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}


/* Completed */

.schedule-status.completed {
    color: #155724;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}


/* Cancelled */

.schedule-status.cancelled {
    color: #721c24;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   MESSAGES
========================================================= */

.cancelled-message {
    color: #721c24;
    font-size: 13px;
    font-weight: 500;
}


.completed-message {
    color: #155724;
    font-size: 13px;
    font-weight: 500;
}


/* =========================================================
   RESPONSIVE LEGEND
========================================================= */

@media (max-width: 768px) {

    .calendar-legend {
        gap: 10px;
    }

    .legend-item {
        font-size: 12px;
    }

}

</style>


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

    let originalStartDate = '';

    let originalEndDate = '';


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

    if (
        !calendarDays ||
        !calendarMonthYear ||
        !scheduleList
    ) {

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

            return '';
        }

        return String(value)
            .substring(0, 10);
    }


    /* =========================================================
       NORMALIZE TIME
    ========================================================= */

    function normalizeTime(value) {

        if (!value) {

            return '';
        }

        return String(value)
            .substring(0, 5);
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

        const status =
            String(
                schedule.status || ''
            )
            .trim()
            .toLowerCase();


        return status === 'cancelled';
    }


    /* =========================================================
       RE-SCHEDULED
    ========================================================= */

    function isRescheduled(schedule) {

        const status =
            String(
                schedule.status || ''
            )
            .trim()
            .toLowerCase();


        return (
            status === 're-scheduled' ||
            status === 'rescheduled'
        );
    }


    /* =========================================================
       COMPLETED
    ========================================================= */

    function isCompleted(schedule) {

        const status =
            String(
                schedule.status || ''
            )
            .trim()
            .toLowerCase();


        /*
         * Cancelled is not completed.
         */

        if (
            status === 'cancelled'
        ) {

            return false;
        }


        /*
         * Re-Scheduled stays Re-Scheduled.
         *
         * We do NOT automatically turn it into
         * Completed just because the old date passed.
         */

        if (
            status === 're-scheduled' ||
            status === 'rescheduled'
        ) {

            return false;
        }


        /*
         * Explicit database status.
         */

        if (
            status === 'completed'
        ) {

            return true;
        }


        /*
         * Scheduled records can be displayed
         * as completed when their end date has passed.
         */

        const dates =
            getScheduleDates(
                schedule
            );


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


        return endDate < today;
    }


    /* =========================================================
       GET STATUS
    ========================================================= */

    function getScheduleStatus(schedule) {

        const status =
            String(
                schedule.status || ''
            )
            .trim()
            .toLowerCase();


        /*
         * CANCELLED
         */

        if (
            status === 'cancelled'
        ) {

            return 'cancelled';
        }


        /*
         * RE-SCHEDULED
         *
         * This has priority over date-based completion.
         */

        if (
            status === 're-scheduled' ||
            status === 'rescheduled'
        ) {

            return 'rescheduled';
        }


        /*
         * EXPLICIT COMPLETED
         */

        if (
            status === 'completed'
        ) {

            return 'completed';
        }


        /*
         * SCHEDULED BUT DATE HAS PASSED
         */

        if (
            isCompleted(schedule)
        ) {

            return 'completed';
        }


        /*
         * NORMAL SCHEDULED
         */

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
                    getScheduleDates(
                        schedule
                    );


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


        if (
            isNaN(
                date.getTime()
            )
        ) {

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
       CSRF TOKEN
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
       CANCEL URL
    ========================================================= */

    function getCancelUrl(id) {

        return '<?= $this->Url->build(
            '/api/Schedules/cancel'
        ) ?>/' +
        encodeURIComponent(id);
    }


    /* =========================================================
       UPDATE LOCAL SCHEDULE
       ========================================================= */

    function updateLocalSchedule(updatedSchedule) {

        if (
            !updatedSchedule ||
            !updatedSchedule.id
        ) {

            return;
        }


        const index =
            schedules.findIndex(
                function (item) {

                    return String(item.id) ===
                        String(updatedSchedule.id);
                }
            );


        if (index !== -1) {

            schedules[index] =
                updatedSchedule;

        } else {

            schedules.push(
                updatedSchedule
            );
        }


        /*
         * Refresh calendar
         */

        renderCalendar();


        /*
         * Refresh list
         */

        if (selectedDate) {

            renderScheduleList(
                schedulesOnDate(
                    selectedDate
                ),
                selectedDate
            );

        } else {

            renderScheduleList(
                schedules
            );
        }
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


            /*
             * PREVIOUS MONTH
             */

            if (
                i < firstDay
            ) {

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


            /*
             * CURRENT MONTH
             */

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


            /*
             * NEXT MONTH
             */

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
                document.createElement(
                    'div'
                );


            day.className =
                'calendar-day';


            if (otherMonth) {

                day.classList.add(
                    'other-month'
                );
            }


            /*
             * TODAY
             */

            if (
                cellDate === today
            ) {

                day.classList.add(
                    'today'
                );
            }


            /*
             * SELECTED
             */

            if (
                cellDate === selectedDate
            ) {

                day.classList.add(
                    'selected'
                );
            }


            /*
             * DAY NUMBER
             */

            const number =
                document.createElement(
                    'div'
                );


            number.className =
                'calendar-day-number';


            number.textContent =
                dayNumber;


            day.appendChild(
                number
            );


            /*
             * SCHEDULES
             */

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


                        /*
                         * ADD STATUS CLASS
                         */

                        event.classList.add(
                            status
                        );


                        /*
                         * PROGRAM CODE
                         */

                        event.textContent =
                            schedule.program_code ||
                            schedule.program_name ||
                            'Schedule';


                        /*
                         * TOOLTIP
                         */

                        event.title =
                            (
                                schedule.program_code
                                    ? schedule.program_code + ' - '
                                    : ''
                            ) +
                            (
                                schedule.program_name ||
                                'Schedule'
                            ) +
                            ' (' +
                            (
                                status === 'rescheduled'
                                    ? 'Re-Scheduled'
                                    : status === 'completed'
                                        ? 'Completed'
                                        : status === 'cancelled'
                                            ? 'Cancelled'
                                            : 'Scheduled'
                            ) +
                            ')';


                        day.appendChild(
                            event
                        );
                    }
                );


            /*
             * MORE
             */

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


            /*
             * CLICK DATE
             */

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
       RENDER SCHEDULE LIST
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

        } else {

            selectedDateText.textContent =
                'All Schedules';
        }


        scheduleCount.textContent =
            list
                ? list.length
                : 0;


        /*
         * EMPTY
         */

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


        /*
         * SORT
         */

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


        /*
         * CREATE ITEMS
         */

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


                /*
                 * STATUS CLASS
                 */

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


                if (
                    status === 'rescheduled'
                ) {

                    item.classList.add(
                        'rescheduled'
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


                /*
                 * DATE TEXT
                 */

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


                /*
                 * STATUS HTML
                 */

                let statusHtml = '';


                if (
                    status === 'cancelled'
                ) {

                    statusHtml = `

                        <span
                            class="schedule-status cancelled">

                            <i class="fas fa-ban mr-1"></i>

                            Cancelled

                        </span>

                    `;

                } else if (
                    status === 'completed'
                ) {

                    statusHtml = `

                        <span
                            class="schedule-status completed">

                            <i class="fas fa-check-double mr-1"></i>

                            Completed

                        </span>

                    `;

                } else if (
                    status === 'rescheduled'
                ) {

                    statusHtml = `

                        <span
                            class="schedule-status rescheduled">

                            <i class="fas fa-calendar-alt mr-1"></i>

                            Re-Scheduled

                        </span>

                    `;

                } else {

                    statusHtml = `

                        <span
                            class="schedule-status active">

                            <i class="fas fa-check-circle mr-1"></i>

                            Scheduled

                        </span>

                    `;
                }


                /*
                 * ACTIONS
                 */

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

                } else if (
                    status === 'completed'
                ) {

                    actionHtml = `

                        <div class="completed-message">

                            <i class="fas fa-check-double mr-1"></i>

                            This schedule has been completed.

                        </div>

                    `;

                } else {

                    /*
                     * Scheduled and Re-Scheduled
                     * are both editable.
                     */

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


                /*
                 * ITEM HTML
                 */

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

                                        ${escapeHtml(
                                            description
                                        )}

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

                                        ${escapeHtml(
                                            barangay
                                        )}

                                    </span>

                                </div>

                            `
                            : ''
                    }


                    <div class="schedule-item-date">

                        <i class="far fa-calendar-alt"></i>

                        <span>

                            ${escapeHtml(
                                dateText
                            )}

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

    if (
        $('#add').length
    ) {

        $('#add').on(
            'click',
            function (e) {

                e.preventDefault();


                scheduleForm.reset();


                $('#id').val('');


                originalStartDate =
                    '';

                originalEndDate =
                    '';


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


            /*
             * CANCELLED
             */

            if (
                isCancelled(schedule)
            ) {

                alert(
                    'This schedule has already been cancelled and cannot be edited.'
                );

                return;
            }


            /*
             * COMPLETED
             */

            if (
                isCompleted(schedule)
            ) {

                alert(
                    'This schedule has already been completed and cannot be edited.'
                );

                return;
            }


            /*
             * ORIGINAL DATES
             */

            originalStartDate =
                normalizeDate(
                    schedule.start_date
                );


            originalEndDate =
                normalizeDate(
                    schedule.end_date
                );


            /*
             * FORM VALUES
             */

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
                originalStartDate
            );


            $('#end-date').val(
                originalEndDate
            );


            $('#start-time').val(
                normalizeTime(
                    schedule.start_time
                )
            );


            $('#end-time').val(
                normalizeTime(
                    schedule.end_time
                )
            );


            $('#id').val(
                schedule.id
            );


            formSubmitting =
                false;


            saveScheduleButton.disabled =
                false;


            saveScheduleButton.classList.remove(
                'disabled'
            );


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

    if (
        scheduleForm
    ) {

        scheduleForm.addEventListener(
            'submit',
            function (e) {

                /*
                 * PREVENT DOUBLE SUBMIT
                 */

                if (
                    formSubmitting
                ) {

                    e.preventDefault();

                    return false;
                }


                /*
                 * FORM DATES
                 */

                const startDate =
                    $('#start-date').val() || '';


                const endDate =
                    $('#end-date').val() || '';


                /*
                 * END DATE VALIDATION
                 */

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


                /*
                 * CURRENT ID
                 */

                const currentId =
                    $('#id').val();


                /*
                 * PROGRAM CODE
                 */

                const programCode =
                    String(
                        $('#program-code').val()
                    )
                    .trim()
                    .toLowerCase();


                /*
                 * DUPLICATE VALIDATION
                 *
                 * NEW SCHEDULES ONLY
                 */

                if (
                    !currentId &&
                    programCode
                ) {

                    const duplicate =
                        schedules.some(
                            function (schedule) {

                                return (
                                    !isCancelled(
                                        schedule
                                    ) &&

                                    !isCompleted(
                                        schedule
                                    ) &&

                                    String(
                                        schedule.program_code ||
                                        ''
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


                    if (
                        duplicate
                    ) {

                        e.preventDefault();


                        alert(
                            'A schedule with the same Program Code and date already exists.'
                        );


                        return false;
                    }
                }


                /*
                 * DETECT DATE CHANGE
                 */

                let dateChanged =
                    false;


                if (
                    currentId
                ) {

                    dateChanged =
                        (
                            originalStartDate !==
                                startDate ||

                            originalEndDate !==
                                endDate
                        );
                }


                /*
                 * LOCK SUBMIT
                 */

                formSubmitting =
                    true;


                saveScheduleButton.disabled =
                    true;


                saveScheduleButton.classList.add(
                    'disabled'
                );


                /*
                 * BUTTON TEXT
                 */

                if (
                    currentId
                ) {

                    if (
                        dateChanged
                    ) {

                        saveScheduleText.textContent =
                            'Re-Scheduling...';

                    } else {

                        saveScheduleText.textContent =
                            'Updating...';
                    }

                } else {

                    saveScheduleText.textContent =
                        'Saving...';
                }


                /*
                 * IMPORTANT
                 *
                 * Status is NOT manually submitted.
                 *
                 * The SchedulesController API determines
                 * whether the date changed and sets:
                 *
                 * Re-Scheduled
                 */

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


            if (!id) {

                alert(
                    'Schedule ID is missing.'
                );

                return;
            }


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


            /*
             * CANCELLED
             */

            if (
                isCancelled(schedule)
            ) {

                alert(
                    'This schedule is already cancelled.'
                );

                return;
            }


            /*
             * COMPLETED
             */

            if (
                isCompleted(schedule)
            ) {

                alert(
                    'This schedule has already been completed and cannot be cancelled.'
                );

                return;
            }


            /*
             * PROGRAM
             */

            const program =
                schedule.program_code ||
                schedule.program_name ||
                'this schedule';


            /*
             * CONFIRM
             */

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


            /*
             * LOCK BUTTON
             */

            cancelButton.disabled =
                true;


            const originalButtonHTML =
                cancelButton.innerHTML;


            cancelButton.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i> Cancelling...';


            /*
             * CSRF
             */

            const csrfToken =
                getCsrfToken();


            /*
             * URL
             */

            const cancelUrl =
                getCancelUrl(id);


            try {

                const response =
                    await fetch(
                        cancelUrl,
                        {

                            method: 'POST',

                            credentials:
                                'same-origin',

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


                const responseText =
                    await response.text();


                let data;


                try {

                    data =
                        JSON.parse(
                            responseText
                        );

                } catch (jsonError) {

                    throw new Error(
                        'The server returned an invalid response.'
                    );
                }


                /*
                 * HTTP ERROR
                 */

                if (
                    !response.ok
                ) {

                    throw new Error(
                        data.message ||
                        data.error ||
                        `HTTP ${response.status}`
                    );
                }


                /*
                 * APPLICATION ERROR
                 */

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


                /*
                 * UPDATE LOCAL DATA
                 */

                const index =
                    schedules.findIndex(
                        function (item) {

                            return String(
                                item.id
                            ) === String(id);

                        }
                    );


                if (
                    index !== -1
                ) {

                    schedules[index].status =
                        data.schedule_status ||
                        'Cancelled';
                }


                /*
                 * REFRESH
                 */

                renderCalendar();


                if (
                    selectedDate
                ) {

                    renderScheduleList(
                        schedulesOnDate(
                            selectedDate
                        ),
                        selectedDate
                    );

                } else {

                    renderScheduleList(
                        schedules
                    );
                }


                alert(
                    data.message ||
                    'Schedule has been cancelled successfully.'
                );

            } catch (error) {

                console.error(
                    'Cancel error:',
                    error
                );


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


    if (
        previousMonth
    ) {

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


    if (
        nextMonth
    ) {

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


    if (
        todayButton
    ) {

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


            if (
                saveScheduleButton
            ) {

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
       AUTO REFRESH UI
       EVERY 60 SECONDS
    ========================================================= */

    setInterval(
        function () {

            renderCalendar();


            if (
                selectedDate
            ) {

                renderScheduleList(
                    schedulesOnDate(
                        selectedDate
                    ),
                    selectedDate
                );

            } else {

                renderScheduleList(
                    schedules
                );
            }

        },
        60000
    );

});

</script>