<div class="row">

    <?php foreach ($schedules as $schedule): ?>

        <div class="col-lg-12 col-md-6 col-sm-12">

            <div class="card card-success mb-4 shadow">

                <div class="card-header">

                    <h3 class="card-title font-weight-bold">
                        <?= h($schedule->program_name) ?>
                    </h3>

                    <div class="card-tools">

                        <button type="button"
                                class="btn btn-tool"
                                data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                        <button type="button"
                                class="btn btn-tool"
                                data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <p>
                                <strong>Subsidy Type:</strong><br>
                                <?= h($schedule->subsidy_type) ?>
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-light border">
                        <strong>Description</strong><br>
                        <?= nl2br(h($schedule->description)) ?>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-calendar-alt"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                Distribution Date
                            </span>

                            <span class="info-box-number">
                                <?= date('F d, Y', strtotime($schedule->start_date)) ?>
                            </span>
                        </div>
                    </div>

                    <div class="info-box">
                        <span class="info-box-icon bg-primary">
                            <i class="fas fa-clock"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                Distribution Time
                            </span>

                            <span class="info-box-number">
                                <?= date('h:i A', strtotime($schedule->start_time)) ?>
                                -
                                <?= date('h:i A', strtotime($schedule->end_time)) ?>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-success">
                    <i class="fas fa-check-circle"></i>
                    Official Subsidy Distribution Schedule
                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>