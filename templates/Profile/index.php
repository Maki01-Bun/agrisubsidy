<div class="card-body profile-container p-3 p-md-4 p-lg-5">
    <div class="profile-tabs-wrapper mb-4">
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   data-toggle="tab"
                   href="#profile"
                   role="tab">
                    <i class="fas fa-user mr-2"></i>
                    <span>Profile Information</span>
                </a>
            </li>
            <?php if (strtolower($user->role ?? '') === 'farmer'): ?>
                <li class="nav-item">
                    <a class="nav-link"
                       data-toggle="tab"
                       href="#farm"
                       role="tab">
                        <i class="fas fa-tractor mr-2"></i>
                        <span>Farm Information</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- =========================
         TAB CONTENT
    ========================== -->
    <div class="tab-content">
        <!-- =================================
             PROFILE INFORMATION
        ================================== -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <!-- PROFILE HEADER -->
            <div class="profile-header">
                <div class="profile-avatar-wrapper">
                    <img src="<?= $this->Url->image('default-profile.png') ?>"
                         class="profile-avatar"
                         alt="Profile Picture">
                </div>
                <div class="profile-header-content">
                    <h2 class="profile-name">
                        <?= h($farmer->first_name ?? '') ?>
                        <?= h($farmer->last_name ?? '') ?>
                    </h2>
                    <div class="profile-role">
                        <i class="fas fa-user-tag mr-1"></i>
                        <?= h($user->role ?? '') ?>
                    </div>
                    <div class="profile-status">
                        <span>
                            <i class="fas fa-circle"></i>
                            Account Active
                        </span>
                    </div>
                </div>
            </div>
            <!-- PROFILE INFORMATION TITLE -->
            <div class="section-heading mt-4 mb-3">
                <div class="section-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div>
                    <h5>Personal Information</h5>
                    <p>Basic information associated with your account.</p>
                </div>
            </div>
            <!-- PROFILE INFORMATION CARDS -->
            <div class="row">
                <?php if (strtolower($user->role ?? '') === 'farmer'): ?>
                    <div class="col-12 col-sm-6 col-xl-6 mb-3">
                        <div class="info-card">
                            <div class="info-icon success">
                                <i class="fas fa-user"></i>
                            </div>  
                            <div class="info-content">
                                <span class="info-label">
                                    Full Name
                                </span>
                                <span class="info-value">
                                    <?= h($farmer->first_name ?? '') ?>
                                    <?= h($farmer->middle_name ?? '') ?>
                                    <?= h($farmer->last_name ?? '') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- USERNAME -->
                <div class="col-12 col-sm-6 col-xl-6 mb-3">
                    <div class="info-card">

                        <div class="info-icon primary">
                            <i class="fas fa-at"></i>
                        </div>

                        <div class="info-content">
                            <span class="info-label">
                                Username
                            </span>

                            <span class="info-value">
                                <?= h($user->username ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- ROLE -->
                <div class="col-12 col-sm-6 col-xl-6 mb-3">
                    <div class="info-card">
                        <div class="info-icon warning">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">
                                Account Role
                            </span>
                            <span class="info-value text-capitalize">
                                <?= h($user->role ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- CREATED -->
                <div class="col-12 col-sm-6 col-xl-6 mb-3">
                    <div class="info-card">
                        <div class="info-icon danger">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">
                                Account Created
                            </span>
                            <span class="info-value">
                                <?php
                                if (!empty($user->created)) {
                                    echo h(
                                        $user->created instanceof \Cake\I18n\FrozenTime ||
                                        $user->created instanceof \Cake\I18n\FrozenDate
                                            ? $user->created->format('F d, Y')
                                            : $user->created
                                    );
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- =================================
             FARM INFORMATION
        ================================== -->
        <?php if (strtolower($user->role ?? '') === 'farmer'): ?>
        <div class="tab-pane fade" id="farm" role="tabpanel">
            <!-- FARM SECTION HEADER -->
            <div class="farm-section-header">
                <div>
                    <div class="section-heading mb-1">
                        <div class="section-icon farm-icon">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <div>
                            <h5>Farm Information</h5>
                            <p>
                                Registered farm properties associated with your account.
                            </p>
                        </div>
                    </div>
                </div>
                <?php if (!empty($farms)): ?>
                    <div class="farm-count">
                        <i class="fas fa-layer-group mr-1"></i>
                        <?= count($farms) ?>
                        <?= count($farms) == 1 ? 'Farm' : 'Farms' ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($farms)): ?>
                <!-- FARM CARDS -->
                <div class="row mt-3">
                    <?php foreach ($farms as $farm): ?>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="farm-card">
                                <!-- FARM HEADER -->
                                <div class="farm-card-header">
                                    <div class="farm-title-wrapper">
                                        <div class="farm-icon-circle">
                                            <i class="fas fa-tractor"></i>
                                        </div>
                                        <div class="farm-title">
                                            <span>Registered Farm</span>
                                            <h5>
                                                <?= h($farm->farm_name ?: 'Unnamed Farm') ?>
                                            </h5>
                                        </div>
                                    </div>
                                    <span class="farm-badge">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Registered
                                    </span>
                                </div>
                                <!-- FARM BODY -->
                                <div class="farm-card-body">
                                    <div class="farm-detail-grid">
                                        <!-- FARM SIZE -->
                                        <div class="farm-detail">
                                            <div class="farm-detail-icon size">
                                                <i class="fas fa-ruler-combined"></i>
                                            </div>
                                            <div>
                                                <span class="farm-detail-label">
                                                    Farm Size
                                                </span>
                                                <strong>
                                                    <?= h($farm->farm_size ?? '0') ?>
                                                    <small>ha</small>
                                                </strong>
                                            </div>
                                        </div>
                                        <!-- LOCATION -->
                                        <div class="farm-detail">
                                            <div class="farm-detail-icon location">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>
                                                <span class="farm-detail-label">
                                                    Location
                                                </span>
                                                <strong class="location-text">
                                                    <?= h($farm->location ?? 'N/A') ?>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- DIVIDER -->
                                    <div class="farm-divider"></div>
                                    <!-- REGISTERED DATE -->
                                    <div class="farm-date">
                                        <i class="far fa-calendar-alt"></i>
                                        <span>
                                            Registered on
                                            <strong>
                                                <?php
                                                if (!empty($farm->created)) {
                                                    echo h(
                                                        $farm->created instanceof \Cake\I18n\FrozenTime ||
                                                        $farm->created instanceof \Cake\I18n\FrozenDate
                                                            ? $farm->created->format('F d, Y')
                                                            : $farm->created
                                                    );
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- EMPTY FARM STATE -->
                <div class="empty-farm-state">
                    <div class="empty-farm-icon">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <h5>No Farm Information Found</h5>
                    <p>
                        There are no registered farms linked to your account.
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
