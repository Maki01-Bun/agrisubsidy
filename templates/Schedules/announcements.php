<?php
use Cake\I18n\FrozenDate;

$today = date('Y-m-d');
?>

<div class="container-fluid px-0">

    <!-- =========================================================
         ANNOUNCEMENT HEADER
    ========================================================== -->

    <div class="announcement-header mb-4">

        <div class="announcement-header-content">

            <div>

                <span class="announcement-label">
                    <i class="fas fa-bullhorn mr-2"></i>
                    OFFICIAL ANNOUNCEMENT
                </span>

                <h2 class="announcement-title">
                    Subsidy Distribution Announcements
                </h2>

                <p class="announcement-subtitle mb-0">
                    Stay updated with the latest subsidy distribution
                    schedules from AgriSubsidy.
                </p>

            </div>

            <div class="announcement-icon">
                <i class="fas fa-bullhorn"></i>
            </div>

        </div>

    </div>


    <!-- =========================================================
         ANNOUNCEMENTS
    ========================================================== -->

    <?php if (!empty($schedules)): ?>

        <div class="row">

            <?php foreach ($schedules as $schedule): ?>

                <?php

                /*
                 * =====================================================
                 * DATE VALUES
                 * =====================================================
                 */

                $startDate = null;
                $endDate = null;


                if (!empty($schedule->start_date)) {

                    $startDate = date(
                        'Y-m-d',
                        strtotime(
                            (string)$schedule->start_date
                        )
                    );
                }


                if (!empty($schedule->end_date)) {

                    $endDate = date(
                        'Y-m-d',
                        strtotime(
                            (string)$schedule->end_date
                        )
                    );

                } else {

                    $endDate = $startDate;
                }


                /*
                 * =====================================================
                 * DATABASE STATUS
                 * =====================================================
                 */

                $databaseStatus = strtolower(
                    trim(
                        (string)(
                            $schedule->status ?? ''
                        )
                    )
                );


                /*
                 * =====================================================
                 * DETERMINE STATUS
                 *
                 * IMPORTANT:
                 *
                 * Re-Scheduled is checked BEFORE date-based
                 * Upcoming/Ongoing/Completed logic.
                 *
                 * This prevents a Re-Scheduled schedule from
                 * incorrectly appearing as Completed.
                 * =====================================================
                 */

                if (
                    $databaseStatus === 'cancelled'
                ) {

                    $status = 'Cancelled';

                    $statusClass =
                        'status-cancelled';

                    $statusIcon =
                        'fa-times-circle';


                } elseif (
                    $databaseStatus === 're-scheduled' ||
                    $databaseStatus === 'rescheduled'
                ) {

                    $status = 'Re-Scheduled';

                    $statusClass =
                        'status-rescheduled';

                    $statusIcon =
                        'fa-calendar-alt';


                } elseif (
                    $databaseStatus === 'completed'
                ) {

                    $status = 'Completed';

                    $statusClass =
                        'status-completed';

                    $statusIcon =
                        'fa-check-circle';


                } elseif (
                    !empty($startDate) &&
                    $today < $startDate
                ) {

                    $status = 'Upcoming';

                    $statusClass =
                        'status-upcoming';

                    $statusIcon =
                        'fa-clock';


                } elseif (
                    !empty($startDate) &&
                    !empty($endDate) &&
                    $today >= $startDate &&
                    $today <= $endDate
                ) {

                    $status = 'Ongoing';

                    $statusClass =
                        'status-ongoing';

                    $statusIcon =
                        'fa-play-circle';


                } else {

                    $status = 'Completed';

                    $statusClass =
                        'status-completed';

                    $statusIcon =
                        'fa-check-circle';
                }


                /*
                 * =====================================================
                 * PROGRAM INFORMATION
                 * =====================================================
                 */

                $programCode =
                    !empty($schedule->program_code)
                        ? $schedule->program_code
                        : 'Subsidy Program';


                $programName =
                    !empty($schedule->program_name)
                        ? $schedule->program_name
                        : 'N/A';


                /*
                 * =====================================================
                 * DESCRIPTION
                 * =====================================================
                 */

                $description =
                    !empty($schedule->description)
                        ? $schedule->description
                        : 'No additional details available.';


                /*
                 * =====================================================
                 * BARANGAY
                 * =====================================================
                 */

                if (!empty($schedule->barangay)) {

                    $barangay =
                        $schedule->barangay;

                } else {

                    $barangay =
                        'No barangay specified.';
                }


                /*
                 * =====================================================
                 * START TIME
                 * =====================================================
                 */

                $startTime = null;

                if (!empty($schedule->start_time)) {

                    $startTime = date(
                        'h:i A',
                        strtotime(
                            (string)$schedule->start_time
                        )
                    );
                }


                /*
                 * =====================================================
                 * END TIME
                 * =====================================================
                 */

                $endTime = null;

                if (!empty($schedule->end_time)) {

                    $endTime = date(
                        'h:i A',
                        strtotime(
                            (string)$schedule->end_time
                        )
                    );
                }


                /*
                 * =====================================================
                 * FORMATTED DATES
                 * =====================================================
                 */

                $formattedStartDate =
                    'Date not specified';


                if (!empty($startDate)) {

                    $formattedStartDate =
                        date(
                            'F d, Y',
                            strtotime($startDate)
                        );
                }


                $formattedEndDate = null;

                if (!empty($endDate)) {

                    $formattedEndDate =
                        date(
                            'F d, Y',
                            strtotime($endDate)
                        );
                }


                $footerDate =
                    'Date not specified';


                if (!empty($startDate)) {

                    $footerDate =
                        date(
                            'M d, Y',
                            strtotime($startDate)
                        );
                }

                ?>


                <!-- =================================================
                     ANNOUNCEMENT CARD
                ================================================== -->

                <div
                    class="
                        col-xl-6
                        col-lg-6
                        col-md-12
                        mb-4
                    "
                >

                    <div
                        class="
                            announcement-card
                            <?= h($statusClass) ?>-card
                        "
                    >


                        <!-- =========================================
                             TOP STATUS BAR
                        ========================================== -->

                        <div class="announcement-card-top">

                            <div class="announcement-program">

                                <i
                                    class="
                                        fas
                                        fa-seedling
                                        mr-2
                                    "
                                ></i>

                                Program Code:

                                <?= h($programCode) ?>

                            </div>


                            <span
                                class="
                                    announcement-status
                                    <?= h($statusClass) ?>
                                "
                            >

                                <i
                                    class="
                                        fas
                                        <?= h($statusIcon) ?>
                                        mr-1
                                    "
                                ></i>

                                <?= h($status) ?>

                            </span>

                        </div>


                        <!-- =========================================
                             MAIN CONTENT
                        ========================================== -->

                        <div class="announcement-card-body">


                            <!-- TITLE -->

                            <div class="announcement-title-section">

                                <div class="announcement-bell">

                                    <i
                                        class="
                                            fas
                                            fa-bullhorn
                                        "
                                    ></i>

                                </div>


                                <div>

                                    <h4>
                                        <?= h($programName) ?>
                                    </h4>

                                    <span class="text-muted">

                                        Official distribution schedule

                                    </span>

                                </div>

                            </div>


                            <!-- DESCRIPTION -->

                            <div class="announcement-description">

                                <div class="description-title">

                                    <i
                                        class="
                                            fas
                                            fa-info-circle
                                            mr-2
                                        "
                                    ></i>

                                    Announcement Details

                                </div>


                                <p>

                                    <?= nl2br(
                                        h($description)
                                    ) ?>

                                </p>

                            </div>


                            <!-- BARANGAY -->

                            <div class="announcement-description">

                                <div class="description-title">

                                    <i
                                        class="
                                            fas
                                            fa-map-marker-alt
                                            mr-2
                                        "
                                    ></i>

                                    Barangay

                                </div>

                                <p>

                                    <?= h(
                                        strtoupper($barangay)
                                    ) ?>

                                </p>

                            </div>


                            <!-- =================================================
                                 SCHEDULE
                            ================================================== -->

                            <div class="schedule-grid">


                                <!-- DATE -->

                                <div class="schedule-item">

                                    <div
                                        class="
                                            schedule-icon
                                            date-icon
                                        "
                                    >

                                        <i
                                            class="
                                                fas
                                                fa-calendar-alt
                                            "
                                        ></i>

                                    </div>


                                    <div class="schedule-content">

                                        <span>
                                            Distribution Date
                                        </span>


                                        <strong>

                                            <?php if (
                                                !empty($startDate) &&
                                                !empty($endDate) &&
                                                $startDate !== $endDate
                                            ): ?>

                                                <?= h(
                                                    $formattedStartDate
                                                ) ?>

                                                -

                                                <?= h(
                                                    $formattedEndDate
                                                ) ?>

                                            <?php else: ?>

                                                <?= h(
                                                    $formattedStartDate
                                                ) ?>

                                            <?php endif; ?>

                                        </strong>

                                    </div>

                                </div>


                                <!-- TIME -->

                                <div class="schedule-item">

                                    <div
                                        class="
                                            schedule-icon
                                            time-icon
                                        "
                                    >

                                        <i
                                            class="
                                                fas
                                                fa-clock
                                            "
                                        ></i>

                                    </div>


                                    <div class="schedule-content">

                                        <span>
                                            Distribution Time
                                        </span>


                                        <strong>

                                            <?php if (
                                                !empty($startTime) &&
                                                !empty($endTime)
                                            ): ?>

                                                <?= h(
                                                    $startTime
                                                ) ?>

                                                -

                                                <?= h(
                                                    $endTime
                                                ) ?>

                                            <?php elseif (
                                                !empty($startTime)
                                            ): ?>

                                                <?= h(
                                                    $startTime
                                                ) ?>

                                            <?php else: ?>

                                                Time not specified

                                            <?php endif; ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- =================================================
                                 STATUS MESSAGE
                            ================================================== -->


                            <?php if (
                                $status === 'Upcoming'
                            ): ?>


                                <!-- UPCOMING -->

                                <div class="countdown-box">

                                    <div class="countdown-icon">

                                        <i
                                            class="
                                                fas
                                                fa-hourglass-half
                                            "
                                        ></i>

                                    </div>


                                    <div>

                                        <span
                                            class="
                                                countdown-label
                                            "
                                        >

                                            Distribution starts on

                                        </span>


                                        <strong>

                                            <?= h(
                                                $formattedStartDate
                                            ) ?>

                                        </strong>

                                    </div>

                                </div>


                            <?php elseif (
                                $status === 'Re-Scheduled'
                            ): ?>


                                <!-- =================================================
                                     RE-SCHEDULED
                                ================================================== -->

                                <div class="rescheduled-box">

                                    <div class="rescheduled-box-header">

                                        <i
                                            class="
                                                fas
                                                fa-calendar-alt
                                                mr-2
                                            "
                                        ></i>

                                        <strong>

                                            This distribution schedule
                                            has been re-scheduled.

                                        </strong>

                                    </div>


                                    <span>

                                        The distribution date has been
                                        changed. Please follow the new
                                        official schedule shown above.

                                    </span>


                                    <?php if (
                                        !empty($startDate)
                                    ): ?>

                                        <div
                                            class="
                                                rescheduled-new-date
                                            "
                                        >

                                            <i
                                                class="
                                                    fas
                                                    fa-calendar-check
                                                    mr-2
                                                "
                                            ></i>

                                            <strong>

                                                New Distribution Date:

                                            </strong>

                                            <?= h(
                                                $formattedStartDate
                                            ) ?>


                                            <?php if (
                                                !empty($endDate) &&
                                                $startDate !== $endDate
                                            ): ?>

                                                -

                                                <?= h(
                                                    $formattedEndDate
                                                ) ?>

                                            <?php endif; ?>

                                        </div>

                                    <?php endif; ?>

                                </div>


                            <?php elseif (
                                $status === 'Ongoing'
                            ): ?>


                                <!-- ONGOING -->

                                <div class="ongoing-box">

                                    <div>

                                        <i
                                            class="
                                                fas
                                                fa-broadcast-tower
                                                mr-2
                                            "
                                        ></i>

                                        <strong>

                                            Distribution is currently
                                            ongoing.

                                        </strong>

                                    </div>


                                    <span>

                                        Please proceed according to
                                        the official schedule.

                                    </span>

                                </div>


                            <?php elseif (
                                $status === 'Cancelled'
                            ): ?>


                                <!-- CANCELLED -->

                                <div class="cancelled-box">

                                    <div>

                                        <i
                                            class="
                                                fas
                                                fa-times-circle
                                                mr-2
                                            "
                                        ></i>

                                        <strong>

                                            This distribution schedule
                                            has been cancelled.

                                        </strong>

                                    </div>


                                    <span>

                                        Please wait for further
                                        official announcements
                                        regarding a possible
                                        rescheduled distribution.

                                    </span>

                                </div>


                            <?php else: ?>


                                <!-- COMPLETED -->

                                <div class="completed-box">

                                    <div>

                                        <i
                                            class="
                                                fas
                                                fa-check-circle
                                                mr-2
                                            "
                                        ></i>

                                        <strong>

                                            This distribution schedule
                                            has been completed.

                                        </strong>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <!-- =================================================
                                 REMINDER
                            ================================================== -->

                            <?php if (
                                $status === 'Upcoming' ||
                                $status === 'Ongoing' ||
                                $status === 'Re-Scheduled'
                            ): ?>

                                <div class="reminder-box">

                                    <div class="reminder-icon">

                                        <i
                                            class="
                                                fas
                                                fa-exclamation-circle
                                            "
                                        ></i>

                                    </div>


                                    <div>

                                        <strong>
                                            Please be guided
                                        </strong>


                                        <p>

                                            Farmers are encouraged
                                            to arrive on time and
                                            bring the necessary
                                            identification or
                                            documents required for
                                            subsidy claiming.

                                        </p>

                                    </div>

                                </div>

                            <?php endif; ?>


                        </div>


                        <!-- =========================================
                             FOOTER
                        ========================================== -->

                        <div class="announcement-card-footer">

                            <div class="official-label">

                                <i
                                    class="
                                        fas
                                        fa-shield-alt
                                        mr-2
                                    "
                                ></i>

                                Official AgriSubsidy Announcement

                            </div>


                            <div class="announcement-date">

                                <i
                                    class="
                                        far
                                        fa-calendar-check
                                        mr-1
                                    "
                                ></i>

                                <?= h($footerDate) ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>


    <?php else: ?>


        <!-- =====================================================
             NO ANNOUNCEMENTS
        ====================================================== -->

        <div class="no-announcement">

            <div class="no-announcement-icon">

                <i
                    class="
                        fas
                        fa-bullhorn
                    "
                ></i>

            </div>


            <h4>
                No Announcements Available
            </h4>


            <p>

                There are currently no subsidy distribution
                announcements. Please check again later.

            </p>

        </div>

    <?php endif; ?>

</div>