<div class="register-box">

    <div class="card">

        <div class="card-body register-card-body">

            <p class="register-box-msg">
                Registration - Step <?= $step ?> of 2
            </p>

            <?= $this->Flash->render() ?>


            <?php if ($step == 1): ?>

                <!-- =====================================================
                     STEP 1
                ====================================================== -->

                <?= $this->Form->create(null, [
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ]
                ]) ?>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('farmer_no', [
                        'class' => 'form-control',
                        'placeholder' => 'Farmer Number',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('first_name', [
                        'class' => 'form-control',
                        'placeholder' => 'First Name',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('middle_name', [
                        'class' => 'form-control',
                        'placeholder' => 'Middle Name',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('last_name', [
                        'class' => 'form-control',
                        'placeholder' => 'Last Name',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('gender', [
                        'class' => 'form-control',
                        'label' => false,
                        'options' => $this->Option->gender()
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('contact_no', [
                        'class' => 'form-control',
                        'placeholder' => 'Contact Number',
                        'maxlength' => 11,
                        'minlength' => 11,
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('address', [
                        'class' => 'form-control',
                        'placeholder' => 'Address',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Birthdate
                    </label>

                    <?= $this->Form->control('birthdate', [
                        'class' => 'form-control',
                        'type' => 'date',
                        'label' => false,
                        'max' => date(
                            'Y-m-d',
                            strtotime('-18 years')
                        ),
                        'required' => true
                    ]) ?>

                </div>


                <div class="row mb-3">

                    <div class="col-6">

                        <?= $this->Html->link(
                            'Cancel',
                            [
                                'controller' => 'Users',
                                'action' => 'login'
                            ],
                            [
                                'class' =>
                                    'btn btn-secondary btn-block'
                            ]
                        ) ?>

                    </div>


                    <div class="col-6">

                        <button
                            type="submit"
                            class="btn btn-primary btn-block"
                        >
                            Next
                        </button>

                    </div>

                </div>


                <?= $this->Form->end() ?>


            <?php else: ?>


                <!-- =====================================================
                     STEP 2
                ====================================================== -->

                <?= $this->Form->create($user, [
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                    'id' => 'registration-form'
                ]) ?>


                <!-- USERNAME -->

                <div class="input-group mb-3">

                    <?= $this->Form->control('username', [
                        'class' => 'form-control',
                        'placeholder' => 'Username',
                        'label' => false,
                        'required' => true,
                        'id' => 'username',
                        'autocomplete' => 'username'
                    ]) ?>

                    <div class="input-group-append">

                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="input-group mb-3">

                    <?= $this->Form->control('email', [
                        'class' => 'form-control',
                        'type' => 'email',
                        'placeholder' => 'Email',
                        'label' => false,
                        'required' => true,
                        'id' => 'email',
                        'autocomplete' => 'email'
                    ]) ?>

                    <div class="input-group-append">

                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>

                    </div>

                </div>


                <!-- PASSWORD -->

                <div class="input-group mb-3">

                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'class' => 'form-control',
                        'id' => 'password',
                        'placeholder' => 'Password',
                        'label' => false,
                        'required' => true,
                        'autocomplete' => 'new-password'
                    ]) ?>

                    <div class="input-group-append">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword(
                                'password',
                                'passwordIcon'
                            )"
                        >

                            <i
                                id="passwordIcon"
                                class="fas fa-eye-slash"
                            ></i>

                        </button>

                    </div>

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="input-group mb-3">

                    <?= $this->Form->control(
                        'confirm_password',
                        [
                            'type' => 'password',
                            'class' => 'form-control',
                            'placeholder' =>
                                'Confirm Password',
                            'label' => false,
                            'required' => true,
                            'id' =>
                                'confirm_password',
                            'autocomplete' =>
                                'new-password'
                        ]
                    ) ?>

                    <div class="input-group-append">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword(
                                'confirm_password',
                                'confirmPasswordIcon'
                            )"
                        >

                            <i
                                id="confirmPasswordIcon"
                                class="fas fa-eye-slash"
                            ></i>

                        </button>

                    </div>

                </div>


                <!-- ROLE -->

                <?= $this->Form->hidden('role', [
                    'value' => 'farmer'
                ]) ?>


                <!-- BUTTONS -->

                <div class="row">

                    <div class="col-6 pr-1">

                        <?= $this->Html->link(
                            'Cancel',
                            [
                                'controller' =>
                                    'Users',
                                'action' =>
                                    'login'
                            ],
                            [
                                'class' =>
                                    'btn btn-secondary btn-block'
                            ]
                        ) ?>

                    </div>


                    <div class="col-6 pl-1">

                        <button
                            type="submit"
                            id="registerButton"
                            class="btn btn-success btn-block"
                        >
                            Register
                        </button>

                    </div>

                </div>


                <?= $this->Form->end() ?>


            <?php endif; ?>

        </div>

    </div>

</div>


<!-- ================================================================
     ERROR MODAL
================================================================ -->

<div
    class="modal fade"
    id="registrationErrorModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title">

                    <i class="fas fa-exclamation-circle mr-2"></i>

                    Registration Error

                </h5>

                <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal"
                >
                    <span>&times;</span>
                </button>

            </div>


            <div class="modal-body text-center">

                <i
                    class="fas fa-exclamation-triangle text-danger"
                    style="
                        font-size:45px;
                        margin-bottom:15px;
                    "
                ></i>

                <p
                    id="registrationErrorMessage"
                    class="mb-0"
                    style="font-size:16px;"
                ></p>

            </div>


            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    class="btn btn-danger"
                    data-dismiss="modal"
                >
                    OK
                </button>

            </div>

        </div>

    </div>

</div>


<!-- ================================================================
     SUCCESS / APPROVAL MODAL
================================================================ -->

<div
    class="modal fade"
    id="registrationResultModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>

    <div
        class="modal-dialog modal-dialog-centered"
        role="document"
    >

        <div class="modal-content">

            <div
                class="modal-header"
                style="
                    background:#078a27;
                    color:white;
                "
            >

                <h5 class="modal-title">

                    <i class="fas fa-check-circle mr-2"></i>

                    Registration Submitted

                </h5>

            </div>


            <div class="modal-body text-center">

                <i
                    class="fas fa-user-clock"
                    style="
                        font-size:55px;
                        color:#078a27;
                        margin-bottom:20px;
                    "
                ></i>


                <h5>
                    Registration Successful
                </h5>


                <p
                    class="mb-0"
                    style="
                        font-size:16px;
                        line-height:1.6;
                        color:#555;
                    "
                >
                    Your registration has been submitted
                    successfully.
                    <br>
                    Please wait for the administrator to
                    approve your registration before you
                    can log in.
                </p>

            </div>


            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    id="registrationResultButton"
                    class="btn btn-success px-4"
                >
                    OK
                </button>

            </div>

        </div>

    </div>

</div>


<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | PASSWORD TOGGLE
    |--------------------------------------------------------------------------
    */

    window.togglePassword = function(
        inputId,
        iconId
    ) {

        const input =
            document.getElementById(inputId);

        const icon =
            document.getElementById(iconId);

        if (!input || !icon) {
            return;
        }

        if (input.type === 'password') {

            input.type = 'text';

            icon.classList.remove(
                'fa-eye-slash'
            );

            icon.classList.add(
                'fa-eye'
            );

        } else {

            input.type = 'password';

            icon.classList.remove(
                'fa-eye'
            );

            icon.classList.add(
                'fa-eye-slash'
            );

        }

    };


    /*
    |--------------------------------------------------------------------------
    | ERROR MODAL
    |--------------------------------------------------------------------------
    */

    window.showRegistrationError =
        function(message) {

            $('#registrationErrorMessage')
                .text(message);

            $('#registrationErrorModal')
                .modal('show');

        };


    /*
    |--------------------------------------------------------------------------
    | SUCCESS MODAL
    |--------------------------------------------------------------------------
    */

    <?php if (!empty($registrationSuccess)): ?>

        $('#registrationResultModal').modal({
            backdrop: 'static',
            keyboard: false
        });

    <?php endif; ?>


    /*
    |--------------------------------------------------------------------------
    | SUCCESS MODAL OK
    |--------------------------------------------------------------------------
    */

    $('#registrationResultButton')
        .on('click', function () {

            window.location.href =
                '<?= $this->Url->build([
                    'controller' => 'Users',
                    'action' => 'login'
                ]) ?>';

        });


    /*
    |--------------------------------------------------------------------------
    | FIELD ERROR
    |--------------------------------------------------------------------------
    */

    function setFieldError(fieldId) {

        $('#' + fieldId)
            .addClass('is-invalid');

    }


    function clearFieldError(fieldId) {

        $('#' + fieldId)
            .removeClass('is-invalid');

    }


    /*
    |--------------------------------------------------------------------------
    | PASSWORD MATCH
    |--------------------------------------------------------------------------
    */

    $('#registration-form')
        .on('submit', function(e) {

            const password =
                $('#password').val();

            const confirmPassword =
                $('#confirm_password').val();


            if (
                password !==
                confirmPassword
            ) {

                e.preventDefault();


                $('#confirm_password')
                    .val('')
                    .addClass('is-invalid');


                showRegistrationError(
                    'The password and confirm password do not match.'
                );


                setTimeout(function() {

                    $('#confirm_password')
                        .focus();

                }, 500);


                return false;

            }


            clearFieldError(
                'confirm_password'
            );


            /*
            |--------------------------------------------------------------------------
            | PREVENT DOUBLE CLICK
            |--------------------------------------------------------------------------
            */

            $('#registerButton')
                .prop('disabled', true)
                .html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Registering...'
                );

        });


    /*
    |--------------------------------------------------------------------------
    | PASSWORD INPUT
    |--------------------------------------------------------------------------
    */

    $('#password').on(
        'input',
        function() {

            clearFieldError('password');

        }
    );


    $('#confirm_password').on(
        'input',
        function() {

            clearFieldError(
                'confirm_password'
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | USERNAME INPUT
    |--------------------------------------------------------------------------
    */

    $('#username').on(
        'input',
        function() {

            clearFieldError('username');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EMAIL INPUT
    |--------------------------------------------------------------------------
    */

    $('#email').on(
        'input',
        function() {

            clearFieldError('email');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHECK USERNAME
    |--------------------------------------------------------------------------
    */

    $('#username').on(
        'blur',
        function() {

            const username =
                $.trim($(this).val());

            if (username === '') {
                return;
            }


            $.ajax({

                url: '<?= $this->Url->build([
                    'controller' =>
                        'Users',
                    'action' =>
                        'checkUsername'
                ]) ?>',

                type: 'GET',

                dataType: 'json',

                data: {
                    username: username
                },

                success: function(response) {

                    if (
                        response.status ===
                            'error' &&
                        response.exists ===
                            true
                    ) {

                        $('#username')
                            .val('')
                            .addClass(
                                'is-invalid'
                            );

                        showRegistrationError(
                            'Username is already taken. Please choose another username.'
                        );

                        setTimeout(
                            function() {

                                $('#username')
                                    .focus();

                            },
                            500
                        );

                    }

                },

                error: function(xhr) {

                    console.error(
                        'Username check failed:',
                        xhr.responseText
                    );

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHECK EMAIL
    |--------------------------------------------------------------------------
    */

    $('#email').on(
        'blur',
        function() {

            const email =
                $.trim($(this).val());

            if (email === '') {
                return;
            }


            $.ajax({

                url: '<?= $this->Url->build([
                    'controller' =>
                        'Users',
                    'action' =>
                        'checkEmail'
                ]) ?>',

                type: 'GET',

                dataType: 'json',

                data: {
                    email: email
                },

                success: function(response) {

                    if (
                        response.status ===
                            'error' &&
                        response.exists ===
                            true
                    ) {

                        $('#email')
                            .val('')
                            .addClass(
                                'is-invalid'
                            );

                        showRegistrationError(
                            'Email is already registered. Please use another email address.'
                        );

                        setTimeout(
                            function() {

                                $('#email')
                                    .focus();

                            },
                            500
                        );

                    }

                },

                error: function(xhr) {

                    console.error(
                        'Email check failed:',
                        xhr.responseText
                    );

                }

            });

        }
    );

});

</script>