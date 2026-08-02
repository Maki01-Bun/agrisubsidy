<div class="card-body p-5">
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active"
               data-toggle="tab"
               href="#profile">
                <i class="fas fa-user"></i>
                Profile Information
            </a>
        </li>
        <?php if (strtolower($user->role ?? '') === 'farmer'): ?>
        <li class="nav-item">
            <a class="nav-link"
               data-toggle="tab"
               href="#farm">
                <i class="fas fa-tractor"></i>
                Farm Information
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <div class="tab-content">
        <!--PROFILE TAB-->
        <div class="tab-pane fade show active" id="profile">
            <div class="text-center">
                <div class="profile-image-wrapper">
                    <img src="<?= $this->Url->image('default-profile.png') ?>"
                         class="rounded-circle shadow border border-white"
                         width="140"
                         height="140">
                </div>
                <h3 class="mt-3 font-weight-bold text-success">
                    <?= h($farmer->first_name ?? '') ?>
                    <?= h($farmer->last_name ?? '') ?>
                </h3>
                <span class="badge badge-success px-3 py-2">
                    <i class="fas fa-user-tag"></i>
                    <?= h($user->role ?? '') ?>
                </span>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="info-card shadow-sm p-3 rounded">
                        <div class="icon-box bg-success text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Full Name
                            </small>
                            <h6 class="font-weight-bold mb-0">
                                <?= h($farmer->first_name ?? '') ?>
                                <?= h($farmer->middle_name ?? '') ?>
                                <?= h($farmer->last_name ?? '') ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="info-card shadow-sm p-3 rounded">
                        <div class="icon-box bg-primary text-white">
                            <i class="fas fa-at"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Username
                            </small>
                            <h6 class="font-weight-bold mb-0">
                                <?= h($user->username ?? '') ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="info-card shadow-sm p-3 rounded">
                        <div class="icon-box bg-warning text-white">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Role
                            </small>
                            <h6 class="font-weight-bold mb-0">
                                <?= h($user->role ?? '') ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="info-card shadow-sm p-3 rounded">
                        <div class="icon-box bg-danger text-white">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Account Created
                            </small>
                            <h6 class="font-weight-bold mb-0">
                                <?= $user->created ?? 'N/A' ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--FARM TAB-->
        <?php if (strtolower($user->role ?? '') === 'farmer'): ?>
        <div class="tab-pane fade" id="farm">
            <?php if (!empty($farms)): ?>
            <div class="row">
                <?php foreach ($farms as $farm): ?>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-tractor"></i>
                                <?= h($farm->farm_name) ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <small class="text-muted">
                                        Farm Size
                                    </small>
                                    <h5 class="text-success">
                                        <?= h($farm->farm_size) ?> ha
                                    </h5>
                                </div>
                                <div class="col-6 mb-3">
                                    <small class="text-muted">
                                        Location
                                    </small>
                                    <h6>
                                        <?= h($farm->location) ?>
                                    </h6>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i>
                                Registered:
                                <?= $farm->created
                                    ? $farm->created->format('F d, Y')
                                    : 'N/A'; ?>
                            </small>
                         </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                <h5>No Farm Information Found</h5>
                <p class="mb-0">
                    There are no registered farms linked to your account.
                </p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>