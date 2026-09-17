<div class="card-body profile-container p-3 p-md-4 p-lg-5">

    <!-- =========================================================
         PROFILE TABS
    ========================================================== -->
    <div class="profile-tabs-wrapper mb-4">

        <ul class="nav nav-tabs profile-tabs" role="tablist">

            <!-- PROFILE TAB -->
            <li class="nav-item">
                <a class="nav-link active"
                   data-toggle="tab"
                   href="#profile"
                   role="tab">

                    <i class="fas fa-user mr-2"></i>

                    <span>Profile Information</span>

                </a>
            </li>

            <!-- FARM TAB -->
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


    <!-- =========================================================
         TAB CONTENT
    ========================================================== -->
    <div class="tab-content">


        <!-- =====================================================
             PROFILE INFORMATION
        ====================================================== -->
        <div class="tab-pane fade show active"
             id="profile"
             role="tabpanel">


            <!-- =================================================
                 PROFILE HEADER
            ================================================== -->
            <div class="profile-header">

                <!-- PROFILE IMAGE -->
                <div class="profile-avatar-wrapper">

                    <img src="<?= $this->Url->image('default-profile.png') ?>"
                         class="profile-avatar"
                         alt="Profile Picture">

                </div>


                <!-- PROFILE INFORMATION -->
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


            <!-- =================================================
                 EDIT ACCOUNT BUTTON
            ================================================== -->
            <div class="profile-account-actions">

                <button type="button"
                        class="btn btn-success profile-edit-account-btn"
                        data-toggle="modal"
                        data-target="#editAccountModal">

                    <i class="fas fa-user-edit mr-2"></i>

                    Edit Account

                </button>

            </div>


            <!-- =================================================
                 PERSONAL INFORMATION TITLE
            ================================================== -->
            <div class="section-heading mt-4 mb-3">

                <div class="section-icon">

                    <i class="fas fa-id-card"></i>

                </div>

                <div>

                    <h5>
                        Personal Information
                    </h5>

                    <p>
                        Basic information associated with your account.
                    </p>

                </div>

            </div>


            <!-- =================================================
                 PERSONAL INFORMATION CARDS
            ================================================== -->
            <div class="row">


                <!-- =============================================
                     FULL NAME
                ============================================== -->
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


                <!-- =============================================
                     USERNAME
                ============================================== -->
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


                <!-- =============================================
                     ROLE
                ============================================== -->
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


                <!-- =============================================
                     CREATED
                ============================================== -->
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



        <!-- =====================================================
             FARM INFORMATION
        ====================================================== -->
        <?php if (strtolower($user->role ?? '') === 'farmer'): ?>

            <div class="tab-pane fade"
                 id="farm"
                 role="tabpanel">


                <!-- =============================================
                     FARM SECTION HEADER
                ============================================== -->
                <div class="farm-section-header">

                    <div>

                        <div class="section-heading mb-1">

                            <div class="section-icon farm-icon">

                                <i class="fas fa-tractor"></i>

                            </div>


                            <div>

                                <h5>
                                    Farm Information
                                </h5>

                                <p>
                                    Registered farm properties associated with your account.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- FARM COUNT -->
                    <?php if (!empty($farms)): ?>

                        <div class="farm-count">

                            <i class="fas fa-layer-group mr-1"></i>

                            <?= count($farms) ?>

                            <?= count($farms) == 1 ? 'Farm' : 'Farms' ?>

                        </div>

                    <?php endif; ?>

                </div>



                <!-- =============================================
                     FARMS FOUND
                ============================================== -->
                <?php if (!empty($farms)): ?>

                    <div class="row mt-3">

                        <?php foreach ($farms as $farm): ?>

                            <div class="col-12 col-md-6 mb-4">

                                <div class="farm-card">


                                    <!-- =================================
                                         FARM HEADER
                                    ================================== -->
                                    <div class="farm-card-header">

                                        <div class="farm-title-wrapper">

                                            <div class="farm-icon-circle">

                                                <i class="fas fa-tractor"></i>

                                            </div>
                                            <div class="farm-title">
                                                <span>
                                                    Registered Farm
                                                </span>
                                                <div class="farm-location">
                                                    <div class="farm-detail-icon location">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <h5>
                                                        <?= h(
                                                            !empty($farm->location)
                                                                ? $farm->location
                                                                : 'Unknown Farm Location'
                                                        ) ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>

                                        <span class="farm-badge">

                                            <i class="fas fa-check-circle mr-1"></i>

                                            Registered

                                        </span>

                                    </div>



                                    <!-- =================================
                                         FARM BODY
                                    ================================== -->
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

                                                        <?= h(
                                                            $farm->farm_size ?? '0'
                                                        ) ?>

                                                        <small>
                                                            ha
                                                        </small>

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


                    <!-- =============================================
                         EMPTY FARM STATE
                    ============================================== -->
                    <div class="empty-farm-state">

                        <div class="empty-farm-icon">

                            <i class="fas fa-tractor"></i>

                        </div>


                        <h5>
                            No Farm Information Found
                        </h5>


                        <p>
                            There are no registered farms linked to your account.
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</div>


<!-- =========================================================
     EDIT ACCOUNT MODAL
========================================================== -->

<div class="modal fade"
     id="editAccountModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="editAccountModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg"
         role="document">

        <div class="modal-content edit-account-modal">

            <!-- =================================================
                 MODAL HEADER
            ================================================== -->

            <div class="modal-header">

                <div>

                    <h5 class="modal-title"
                        id="editAccountModalLabel">

                        <i class="fas fa-user-edit mr-2"></i>

                        Edit Account

                    </h5>

                    <small class="text-muted">
                        Update your username, email, or password.
                    </small>

                </div>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>


            <!-- =================================================
                 FORM
            ================================================== -->

            <?= $this->Form->create($user, [
                'url' => [
                    'controller' => 'Users',
                    'action' => 'editAccount'
                ],
                'class' => 'edit-account-form',
                'id' => 'editAccountForm',
                'autocomplete' => 'off'
            ]) ?>


            <!-- =================================================
                 MODAL BODY
            ================================================== -->

            <div class="modal-body">

                <!-- =================================================
                     USERNAME + EMAIL
                ================================================== -->

                <div class="row">

                    <!-- USERNAME -->

                    <div class="col-12 col-md-6">

                        <div class="form-group">

                            <label for="username">

                                <i class="fas fa-at mr-1"></i>

                                Username

                            </label>

                            <?= $this->Form->control('username', [

                                'label' => false,

                                'class' => 'form-control',

                                'id' => 'username',

                                'value' => $user->username ?? '',

                                'required' => true,

                                'autocomplete' => 'username'

                            ]) ?>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="col-12 col-md-6">

                        <div class="form-group">

                            <label for="email">

                                <i class="fas fa-envelope mr-1"></i>

                                Email Address

                            </label>

                            <?= $this->Form->control('email', [

                                'label' => false,

                                'type' => 'email',

                                'class' => 'form-control',

                                'id' => 'email',

                                'value' => $user->email ?? '',

                                'required' => true,

                                'placeholder' => 'Enter your email address',

                                'autocomplete' => 'email'

                            ]) ?>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     PASSWORD DIVIDER
                ================================================== -->

                <div class="account-section-divider">

                    <span>
                        Password Change
                    </span>

                </div>


                <!-- =================================================
                     CURRENT PASSWORD
                ================================================== -->

                <div class="form-group">

                    <label for="current_password">

                        <i class="fas fa-lock mr-1"></i>

                        Current Password

                    </label>


                    <div class="password-input-wrapper">

                        <?= $this->Form->password(
                            'current_password',
                            [
                                'class' => 'form-control',

                                'id' => 'current_password',

                                'placeholder' =>
                                    'Enter your current password',

                                'autocomplete' =>
                                    'current-password'
                            ]
                        ) ?>


                        <button type="button"
                                class="password-toggle"
                                data-target="current_password"
                                title="Show password"
                                aria-label="Show password">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>


                    <small class="form-text text-muted">

                        Required only when changing your password.

                    </small>

                </div>


                <!-- =================================================
                     NEW PASSWORD
                ================================================== -->

                <div class="form-group">

                    <label for="new_password">

                        <i class="fas fa-key mr-1"></i>

                        New Password

                    </label>


                    <div class="password-input-wrapper">

                        <?= $this->Form->password(
                            'new_password',
                            [
                                'class' => 'form-control',

                                'id' => 'new_password',

                                'placeholder' =>
                                    'Leave blank to keep current password',

                                'autocomplete' =>
                                    'new-password'
                            ]
                        ) ?>


                        <button type="button"
                                class="password-toggle"
                                data-target="new_password"
                                title="Show password"
                                aria-label="Show password">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>


                    <small class="form-text text-muted">

                        At least 8 characters,
                        one capital letter,
                        and one special character.

                    </small>

                </div>


                <!-- =================================================
                     CONFIRM PASSWORD
                ================================================== -->

                <div class="form-group mb-0">

                    <label for="confirm_password">

                        <i class="fas fa-check-circle mr-1"></i>

                        Confirm New Password

                    </label>


                    <div class="password-input-wrapper">

                        <?= $this->Form->password(
                            'confirm_password',
                            [
                                'class' => 'form-control',

                                'id' => 'confirm_password',

                                'placeholder' =>
                                    'Confirm your new password',

                                'autocomplete' =>
                                    'new-password'
                            ]
                        ) ?>


                        <button type="button"
                                class="password-toggle"
                                data-target="confirm_password"
                                title="Show password"
                                aria-label="Show password">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 MODAL FOOTER
            ================================================== -->

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>

                    Cancel

                </button>


                <button type="submit"
                        class="btn btn-success"
                        id="saveAccountButton">

                    <i class="fas fa-save mr-1"></i>

                    Save Changes

                </button>

            </div>


            <?= $this->Form->end() ?>

        </div>

    </div>

</div>


<!-- =========================================================
     EDIT ACCOUNT JAVASCRIPT
========================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       PASSWORD SHOW / HIDE
    ========================================================== */

    document
        .querySelectorAll('.password-toggle')
        .forEach(function (button) {

            button.addEventListener('click', function (e) {

                e.preventDefault();

                const targetId =
                    button.getAttribute('data-target');

                const input =
                    document.getElementById(targetId);

                if (!input) {
                    return;
                }

                const icon =
                    button.querySelector('i');


                if (input.type === 'password') {

                    input.type = 'text';

                    if (icon) {

                        icon.classList.remove(
                            'fa-eye'
                        );

                        icon.classList.add(
                            'fa-eye-slash'
                        );
                    }

                    button.setAttribute(
                        'title',
                        'Hide password'
                    );

                    button.setAttribute(
                        'aria-label',
                        'Hide password'
                    );

                } else {

                    input.type = 'password';

                    if (icon) {

                        icon.classList.remove(
                            'fa-eye-slash'
                        );

                        icon.classList.add(
                            'fa-eye'
                        );
                    }

                    button.setAttribute(
                        'title',
                        'Show password'
                    );

                    button.setAttribute(
                        'aria-label',
                        'Show password'
                    );
                }

            });

        });


    /* =========================================================
       FORM
    ========================================================== */

    const form =
        document.getElementById('editAccountForm');

    if (!form) {
        return;
    }


    /* =========================================================
       FORM SUBMIT VALIDATION
    ========================================================== */

    form.addEventListener('submit', function (e) {

        const username =
            document.getElementById('username');

        const email =
            document.getElementById('email');

        const currentPassword =
            document.getElementById('current_password');

        const newPassword =
            document.getElementById('new_password');

        const confirmPassword =
            document.getElementById('confirm_password');


        const usernameValue =
            username
                ? username.value.trim()
                : '';


        const emailValue =
            email
                ? email.value.trim()
                : '';


        const currentValue =
            currentPassword
                ? currentPassword.value
                : '';


        const newValue =
            newPassword
                ? newPassword.value
                : '';


        const confirmValue =
            confirmPassword
                ? confirmPassword.value
                : '';


        /* =====================================================
           USERNAME
        ====================================================== */

        if (usernameValue === '') {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Username Required',
                text: 'Please enter your username.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        if (usernameValue.length < 3) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Invalid Username',
                text: 'Username must be at least 3 characters long.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /* =====================================================
           EMAIL
        ====================================================== */

        if (emailValue === '') {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Email Required',
                text: 'Please enter your email address.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /*
         * CORRECT EMAIL REGEX
         */
        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!emailPattern.test(emailValue)) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Invalid Email',
                text: 'Please enter a valid email address.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /* =====================================================
           NO PASSWORD CHANGE
        ====================================================== */

        if (newValue === '') {

            return true;
        }


        /* =====================================================
           CURRENT PASSWORD
        ====================================================== */

        if (currentValue === '') {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Current Password Required',
                text: 'Please enter your current password before changing your password.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /* =====================================================
           PASSWORD REQUIREMENTS
        ====================================================== */

        /*
         * CORRECT PASSWORD REGEX
         */
        const passwordPattern =
            /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/;


        if (!passwordPattern.test(newValue)) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Invalid Password',
                text: 'New password must be at least 8 characters and contain at least one capital letter and one special character.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /* =====================================================
           CONFIRM PASSWORD
        ====================================================== */

        if (confirmValue === '') {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Confirm Password',
                text: 'Please confirm your new password.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        if (newValue !== confirmValue) {

            e.preventDefault();

            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'The new passwords do not match.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /* =====================================================
           SAME PASSWORD
        ====================================================== */

        if (newValue === currentValue) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Same Password',
                text: 'Your new password must be different from your current password.',
                confirmButtonColor: '#087f23'
            });

            return;
        }


        /* =====================================================
           DISABLE BUTTON WHILE SUBMITTING
        ====================================================== */

        const saveButton =
            document.getElementById('saveAccountButton');

        if (saveButton) {

            saveButton.disabled = true;

            saveButton.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        }


        return true;

    });


    /* =========================================================
       RESET PASSWORD FIELDS WHEN MODAL CLOSES
    ========================================================== */

    if (typeof window.jQuery !== 'undefined') {

        $('#editAccountModal').on(
            'hidden.bs.modal',
            function () {

                const currentPassword =
                    document.getElementById(
                        'current_password'
                    );

                const newPassword =
                    document.getElementById(
                        'new_password'
                    );

                const confirmPassword =
                    document.getElementById(
                        'confirm_password'
                    );


                if (currentPassword) {

                    currentPassword.value = '';

                    currentPassword.type =
                        'password';
                }


                if (newPassword) {

                    newPassword.value = '';

                    newPassword.type =
                        'password';
                }


                if (confirmPassword) {

                    confirmPassword.value = '';

                    confirmPassword.type =
                        'password';
                }


                /*
                 * RESET EYE ICONS
                 */
                document
                    .querySelectorAll(
                        '#editAccountModal .password-toggle i'
                    )
                    .forEach(function (icon) {

                        icon.classList.remove(
                            'fa-eye-slash'
                        );

                        icon.classList.add(
                            'fa-eye'
                        );

                    });


                /*
                 * RESET SAVE BUTTON
                 */
                const saveButton =
                    document.getElementById(
                        'saveAccountButton'
                    );

                if (saveButton) {

                    saveButton.disabled = false;

                    saveButton.innerHTML =
                        '<i class="fas fa-save mr-1"></i> Save Changes';
                }

            }
        );

    }


    /* =========================================================
       SUCCESS CONFIRMATION AFTER REDIRECT
    ========================================================== */

    <?php

    $editAccountSuccess =
        $this->request
            ->getSession()
            ->consume('EditAccountSuccess');

    ?>

    <?php if (!empty($editAccountSuccess)): ?>

        Swal.fire({

            icon: 'success',

            title: 'Account Updated',

            html:
                '<div style="text-align:left;">' +

                <?php if (!empty($editAccountSuccess['usernameChanged'])): ?>

                    '<div class="mb-2">' +
                        '<i class="fas fa-check-circle text-success mr-2"></i>' +
                        '<strong>Username</strong> has been updated.' +
                    '</div>' +

                <?php endif; ?>


                <?php if (!empty($editAccountSuccess['emailChanged'])): ?>

                    '<div class="mb-2">' +
                        '<i class="fas fa-check-circle text-success mr-2"></i>' +
                        '<strong>Email address</strong> has been updated.' +
                    '</div>' +

                <?php endif; ?>


                <?php if (!empty($editAccountSuccess['passwordChanged'])): ?>

                    '<div class="mb-2">' +
                        '<i class="fas fa-check-circle text-success mr-2"></i>' +
                        '<strong>Password</strong> has been updated.' +
                    '</div>' +

                <?php endif; ?>


                <?php if (
                    empty($editAccountSuccess['usernameChanged']) &&
                    empty($editAccountSuccess['emailChanged']) &&
                    empty($editAccountSuccess['passwordChanged'])
                ): ?>

                    '<div class="mb-2">' +
                        '<i class="fas fa-info-circle text-info mr-2"></i>' +
                        'Your account information is already up to date.' +
                    '</div>' +

                <?php endif; ?>

                '</div>',

            confirmButtonColor: '#087f23',

            confirmButtonText: 'OK'

        });

    <?php endif; ?>

});

</script>