<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <!-- Header -->
        <div class="profile-header bg-success text-white p-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fa-3x me-3"></i>
                <div>
                    <h3 class="mb-0">
                        My Profile
                    </h3>
                    <small>
                        Account Information
                    </small>
                </div>

            </div>
        </div>

        <div class="card-body p-5">
            <!-- Profile Section -->
            <div class="text-center">
                <div class="profile-image-wrapper">
                    <img src="<?= $this->Url->image('default-profile.png') ?>"
                         class="rounded-circle shadow border border-4 border-white"
                         width="140"
                         height="140">
                </div>
                <h3 class="mt-3 fw-bold text-success">
                    <?= h($user->first_name ?? '') ?>
                    <?= h($user->last_name ?? '') ?>
                </h3>
                <span class="badge bg-success px-3 py-2">
                    <i class="fas fa-user-tag"></i>
                    <?= h($user->role ?? '') ?>
                </span>
            </div>
            <hr class="my-4">
            <!-- Information Cards -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-card shadow-sm p-3 rounded-3">
                        <div class="icon-box bg-success text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Full Name
                            </small>
                            <h6 class="mb-0 fw-bold">
                                <?= h($user->first_name ?? '') ?>
                                <?= h($user->middle_name ?? '') ?>
                                <?= h($user->last_name ?? '') ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card shadow-sm p-3 rounded-3">
                        <div class="icon-box bg-primary text-white">
                            <i class="fas fa-at"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Username
                            </small>
                            <h6 class="mb-0 fw-bold">
                                <?= h($user->username ?? '') ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card shadow-sm p-3 rounded-3">
                        <div class="icon-box bg-warning text-white">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Role
                            </small>
                            <h6 class="mb-0 fw-bold">
                                <?= h($user->role ?? '') ?>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card shadow-sm p-3 rounded-3">
                        <div class="icon-box bg-danger text-white">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div>
                            <small class="text-muted">
                                Account Created
                            </small>
                            <h6 class="mb-0 fw-bold">
                                <?= $user->created ?? 'N/A' ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="card-footer bg-light text-end p-3">
            <a href="<?= $this->Url->build([
                'controller'=>'Profiles',
                'action'=>'edit'
            ]) ?>"
            class="btn btn-success px-4 rounded-pill">
                <i class="fas fa-edit"></i>
                Edit Profile
            </a>
        </div>
    </div>
</div>