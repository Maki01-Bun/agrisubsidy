<?php
use Cake\I18n\FrozenDate;

$today = date('Y-m-d');
?>
<div class="container-fluid px-0">
    <!-- Announcement Header -->
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
                    Stay updated with the latest subsidy distribution schedules
                    from AgriSubsidy.
                </p>
            </div>
            <div class="announcement-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
        </div>
    </div>

    <?php if (!empty($schedules)): ?>
        <div class="row">
            <?php foreach ($schedules as $schedule): ?>

                <?php
                /*
                 * =====================================================
                 * DATE AND STATUS
                 * =====================================================
                 */
                $startDate = date(
                    'Y-m-d',
                    strtotime($schedule->start_date)
                );
                $endDate = !empty($schedule->end_date)
                    ? date(
                        'Y-m-d',
                        strtotime($schedule->end_date)
                    )
                    : $startDate;
                $startTimestamp = strtotime(
                    $schedule->start_date
                );
                $endTimestamp = strtotime(
                    $endDate
                );


                /*
                 * Determine announcement status.
                 */
                if ($today < $startDate) {
                    $status = 'Upcoming';
                    $statusClass = 'status-upcoming';
                    $statusIcon = 'fa-clock';
                } elseif (
                    $today >= $startDate &&
                    $today <= $endDate
                ) {
                    $status = 'Ongoing';
                    $statusClass = 'status-ongoing';
                    $statusIcon = 'fa-play-circle';
                } else {

                    $status = 'Completed';
                    $statusClass = 'status-completed';
                    $statusIcon = 'fa-check-circle';
                }
                ?>
                <!-- =================================================
                     ANNOUNCEMENT CARD
                ================================================== -->
                <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                    <div class="announcement-card">
                        <!-- =========================================
                             TOP STATUS BAR
                        ========================================== -->
                        <div class="announcement-card-top">
                            <div class="announcement-program">
                                <i class="fas fa-seedling mr-2"></i>
                                Program Code:
                                <?= h(
                                    $schedule->program_code
                                    ?? 'Subsidy Program'
                                ) ?>
                            </div>
                            <span class="announcement-status <?= h($statusClass) ?>">
                                <i
                                    class="fas
                                           <?= h($statusIcon) ?>
                                           mr-1"
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
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h4>
                                        <?= h(
                                        $schedule->program_name
                                        ?? 'N/A'
                                    ) ?>
                                    </h4>
                                    <span class="text-muted">
                                        Official distribution schedule
                                    </span>
                                </div>
                            </div>
                            <!-- =====================================
                                 DESCRIPTION
                            ====================================== -->
                            <div class="announcement-description">
                                <div class="description-title">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Announcement Details
                                </div>
                                <p>
                                    <?= !empty($schedule->description)
                                        ? nl2br(
                                            h($schedule->description)
                                        )
                                        : 'No additional details available.'
                                    ?>
                                </p>
                            </div>
                            <div class="announcement-description">
                                <div class="description-title">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Baranggay
                                </div>
                                <p>
                                    <?= !empty($schedule->baranggay)
                                        ? nl2br(
                                            h($schedule->baranggay)
                                        )
                                        : 'No additional details available.'
                                    ?>
                                </p>
                            </div>
                            <!-- =====================================
                                 SCHEDULE INFORMATION
                            ====================================== -->
                            <div class="schedule-grid">
                                <!-- DISTRIBUTION DATE -->
                                <div class="schedule-item">
                                    <div class="schedule-icon date-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="schedule-content">
                                        <span>
                                            Distribution Date
                                        </span>
                                        <strong>
                                            <?= date(
                                                'F d, Y',
                                                strtotime(
                                                    $schedule->start_date
                                                )
                                            ) ?>
                                        </strong>
                                    </div>
                                </div>
                                <!-- DISTRIBUTION TIME -->
                                <div class="schedule-item">
                                    <div class="schedule-icon time-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="schedule-content">
                                        <span>
                                            Distribution Time
                                        </span>
                                        <strong>
                                            <?= date(
                                                'h:i A',
                                                strtotime(
                                                    $schedule->start_time
                                                )
                                            ) ?>
                                            -
                                            <?= date(
                                                'h:i A',
                                                strtotime(
                                                    $schedule->end_time
                                                )
                                            ) ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>


                            <!-- =====================================
                                 COUNTDOWN / STATUS
                            ====================================== -->
                            <?php if ($status === 'Upcoming'): ?>
                                <div class="countdown-box">
                                    <div class="countdown-icon">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <div>
                                        <span class="countdown-label">
                                            Distribution starts on
                                        </span>
                                        <strong>
                                            <?= date(
                                                'F d, Y',
                                                strtotime(
                                                    $schedule->start_date
                                                )
                                            ) ?>
                                        </strong>
                                    </div>
                                </div>
                            <?php elseif ($status === 'Ongoing'): ?>
                                <div class="ongoing-box">
                                    <i
                                        class="fas
                                               fa-broadcast-tower
                                               mr-2"
                                    ></i>
                                    <strong>
                                        Distribution is currently ongoing.
                                    </strong>
                                    <span>
                                        Please proceed according to
                                        the official schedule.

                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="completed-box">
                                    <i
                                        class="fas
                                               fa-check-circle
                                               mr-2"
                                    ></i>
                                    <strong>

                                        This distribution schedule
                                        has been completed.

                                    </strong>
                                </div>
                            <?php endif; ?>
                            <!-- =====================================
                                 IMPORTANT REMINDER
                            ====================================== -->
                            <?php if ($status !== 'Completed'): ?>
                                <div class="reminder-box">
                                    <div class="reminder-icon">
                                        <i
                                            class="fas
                                                   fa-exclamation-circle"
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
                                    class="fas
                                           fa-shield-alt
                                           mr-2"
                                ></i>
                                Official AgriSubsidy Announcement
                            </div>
                            <div class="announcement-date">
                                <i
                                    class="far
                                           fa-calendar-check
                                           mr-1"
                                ></i>
                                <?= date(
                                    'M d, Y',
                                    strtotime(
                                        $schedule->start_date
                                    )
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- =============================================
             NO ANNOUNCEMENTS
        ============================================== -->
        <div class="no-announcement">
            <div class="no-announcement-icon">
                <i class="fas fa-bullhorn"></i>
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