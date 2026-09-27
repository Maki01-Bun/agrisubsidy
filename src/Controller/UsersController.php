<?php
declare(strict_types=1);

namespace App\Controller;

use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user = $this->Users->newEmptyEntity();

        $this->set(compact('user'));
    }
   public function register($step = 1)
{
    $this->viewBuilder()->setLayout('register');

    $session = $this->request->getSession();

    $notificationsTable =
        $this->getTableLocator()->get('Notifications');

    $usersTable = $this->Users;

    // ============================================================
    // DEFAULT VALUES
    // ============================================================

    $registrationSuccess = false;


    // ============================================================
    // STEP 1 - FARMER INFORMATION
    // ============================================================

    if (
        $step == 1 &&
        $this->request->is('post')
    ) {

        $farmerData =
            $this->request->getData();


        // --------------------------------------------------------
        // STORE FARMER INFORMATION IN SESSION
        // --------------------------------------------------------

        $session->write(
            'Registration.Farmer',
            $farmerData
        );


        // --------------------------------------------------------
        // GO TO STEP 2
        // --------------------------------------------------------

        return $this->redirect([
            'action' => 'register',
            2
        ]);
    }


    // ============================================================
    // STEP 2 - ACCOUNT INFORMATION
    // ============================================================

    if (
        $step == 2 &&
        $this->request->is('post')
    ) {


        // ========================================================
        // GET STEP 1 DATA
        // ========================================================

        $farmerData =
            $session->read(
                'Registration.Farmer'
            );


        // ========================================================
        // CHECK REGISTRATION SESSION
        // ========================================================

        if (!$farmerData) {

            $this->Flash->error(
                __('Registration session expired.')
            );

            return $this->redirect([
                'action' => 'register',
                1
            ]);
        }


        // ========================================================
        // GET STEP 2 DATA
        // ========================================================

        $userData =
            $this->request->getData();


        // ========================================================
        // PASSWORD
        // ========================================================

        $password =
            $userData['password'] ?? '';

        $confirmPassword =
            $userData['confirm_password'] ?? '';


        // ========================================================
        // PASSWORD FORMAT VALIDATION
        // ========================================================

        if (
            !preg_match(
                '/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/',
                $password
            )
        ) {

            $this->Flash->error(
                __(
                    'Password must be at least 8 characters and contain at least one capital letter and one special character.'
                )
            );

            return $this->redirect([
                'action' => 'register',
                2
            ]);
        }


        // ========================================================
        // CONFIRM PASSWORD
        // ========================================================

        if (
            $password !==
            $confirmPassword
        ) {

            $this->Flash->error(
                __(
                    'Password and Confirm Password do not match.'
                )
            );

            return $this->redirect([
                'action' => 'register',
                2
            ]);
        }


        // ========================================================
        // CHECK USERNAME
        // ========================================================

        $existingUser =
            $usersTable
                ->find()
                ->where([
                    'username' =>
                        $userData['username']
                ])
                ->first();


        if ($existingUser) {

            $this->Flash->error(
                __('Username already exists.')
            );

            return $this->redirect([
                'action' => 'register',
                2
            ]);
        }


        // ========================================================
        // CHECK EMAIL
        // ========================================================

        $existingEmail =
            $usersTable
                ->find()
                ->where([
                    'email' =>
                        $userData['email']
                ])
                ->first();


        if ($existingEmail) {

            $this->Flash->error(
                __('Email already exists.')
            );

            return $this->redirect([
                'action' => 'register',
                2
            ]);
        }


        // ========================================================
        // FORCE ROLE TO FARMER
        // ========================================================

        $userData['role'] = 'farmer';


        // ========================================================
        // REMOVE CONFIRM PASSWORD
        // ========================================================

        unset(
            $userData['confirm_password']
        );


        // ========================================================
        // PREPARE REGISTRATION DATA
        // ========================================================

        $registrationData = [

            'farmer' => $farmerData,

            'user' => $userData

        ];


        // ========================================================
        // GET LGU-RSBSA / FARMER NUMBER
        // ========================================================

        $farmerNo = trim(
            (string)(
                $farmerData['farmer_no']
                ?? ''
            )
        );


        // ========================================================
        // CREATE NOTIFICATION
        // ========================================================

        $notification =
            $notificationsTable
                ->newEmptyEntity();


        $notification =
            $notificationsTable->patchEntity(
                $notification,
                [

                    'user_id' => null,

                    'title' =>
                        'New Farmer Registration',

                    'message' =>
                        'A new farmer registration has a LGU-RSBSA number of ' .
                        $farmerNo .
                        ' and requires approval.',

                    'type' =>
                        'registration',

                    'status' =>
                        'pending',

                    'data' =>
                        json_encode(
                            $registrationData
                        )

                ]
            );


        // ========================================================
        // SAVE REGISTRATION REQUEST
        // ========================================================

        if (
            $notificationsTable->save(
                $notification
            )
        ) {


            // ====================================================
            // DELETE TEMPORARY SESSION DATA
            // ====================================================

            $session->delete(
                'Registration.Farmer'
            );


            // ====================================================
            // REGISTRATION SUCCESS
            // ====================================================

            $registrationSuccess = true;


        } else {


            // ====================================================
            // REGISTRATION FAILED
            // ====================================================

            $this->Flash->error(
                __(
                    'Unable to submit registration. Please try again.'
                )
            );
        }
    }


    // ============================================================
    // CREATE EMPTY USER ENTITY
    // ============================================================

    $user =
        $usersTable->newEmptyEntity();


    // ============================================================
    // SEND DATA TO VIEW
    // ============================================================

    $this->set(
        compact(
            'user',
            'step',
            'registrationSuccess'
        )
    );
}
    public function login()
    {
        $this->viewBuilder()->setLayout('login');
        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            // Load Users table
            $this->loadModel('Users');
            // Find user by username
            $existingUser = $this->Users->find()
                ->where([
                    'username' => $username
                ])
                ->first();
            // Check if account is locked
            if (
                $existingUser &&
                $existingUser->locked_until &&
                $existingUser->locked_until > date('Y-m-d H:i:s')
            ) {

                $this->Flash->error(
                    'Your account is temporarily locked. Please try again later.'
                );
                return;
            }
            // Check username and password
            $user = $this->Auth->identify();
            if ($user) {
                // Successful login reset attempts
                if ($existingUser) {
    
                    $existingUser->failed_attempts = 0;
                    $existingUser->locked_until = null;
    
                    $this->Users->save($existingUser);
    
                }
                $this->Auth->setUser($user);
                $this->AuditLogger->logActivity(
                    'login',
                    'User logged in successfully',
                    $existingUser,
                    [
                        'username' => $existingUser->username,
                        'role' => $existingUser->role,
                    ]
                );
    
                if ($user['role'] == 'admin') {
    
    
                    return $this->redirect([
                        'controller'=>'AuditLogs',
                        'action'=>'index'
                    ]);
    
                } elseif ($user['role'] == 'staff') {
                    return $this->redirect([
                        'controller'=>'Dashboard',
                        'action'=>'index'
                    ]);
                } else {
                    return $this->redirect([
                        'controller'=>'Schedules',
                        'action'=>'announcements'
                    ]);
                }
            }
            // Wrong username/password
            if ($existingUser) {
                $existingUser->failed_attempts++;
                if ($existingUser->failed_attempts >= 5) {
                    $existingUser->locked_until =
                        date(
                            'Y-m-d H:i:s',
                            strtotime('+5 seconds')
                        );
                    $existingUser->failed_attempts = 0;
                    $this->Flash->error(
                        'Too many failed attempts. Your account is locked for 15 minutes.'
                    );
                } else {
                    $remaining =
                        5 - $existingUser->failed_attempts;
                    $this->Flash->error(
                        "Invalid username or password. $remaining attempts remaining."
                    );
                }
                $this->Users->save($existingUser);
            } else {
                // Do not reveal that username does not exist
                $this->Flash->error(
                    'Invalid username or password.'
                );
            }
        }
    }

    public function logout()
    {
        $user = $this->Auth->user();
        if ($user) {
            $this->AuditLogger->logActivity(
                'logout',
                'User logged out successfully',
                $user,
                [
                    'username' => $user['username'],
                    'role' => $user['role'],
                ]
            );
        }
        return $this->redirect($this->Auth->logout());
    }
    
    /* =========================================================
    * EDIT LOGGED-IN USER ACCOUNT
    * Username + Email + Password
    * ========================================================= */
    public function editAccount()
    {
        /*
        * =========================================================
        * GET PAGE TO RETURN TO
        * =========================================================
        */
        $returnUrl = $this->request->referer();

        if (empty($returnUrl)) {
            $returnUrl = '/';
        }


        /*
        * =========================================================
        * GET CURRENTLY LOGGED-IN USER
        *
        * This application uses $this->Auth.
        * =========================================================
        */
        $authUser = $this->Auth->user();

        if (!$authUser) {

            $this->Flash->error(
                'You must be logged in to edit your account.'
            );

            return $this->redirect([
                'action' => 'login'
            ]);
        }


        /*
        * =========================================================
        * GET USER ID
        * =========================================================
        */
        $userId = $authUser['id'] ?? null;

        if (empty($userId)) {

            $this->Flash->error(
                'Unable to identify your account.'
            );

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * ONLY ALLOW POST / PUT / PATCH
        * =========================================================
        */
        if (!$this->request->is(['post', 'put', 'patch'])) {

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * LOAD USER FROM DATABASE
        * =========================================================
        */
        try {

            $user = $this->Users->get($userId);

        } catch (\Throwable $e) {

            $this->Flash->error(
                'Your account could not be found.'
            );

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * GET FORM DATA
        * =========================================================
        */
        $data = $this->request->getData();


        /*
        * =========================================================
        * ORIGINAL VALUES
        *
        * We keep these so we know exactly what changed.
        * =========================================================
        */
        $originalUsername = (string)$user->username;
        $originalEmail    = (string)$user->email;


        /*
        * =========================================================
        * GET USERNAME
        * =========================================================
        */
        $username = trim(
            (string)($data['username'] ?? '')
        );

        if ($username === '') {

            $this->Flash->error(
                'Username is required.'
            );

            return $this->redirect($returnUrl);
        }


        if (strlen($username) < 3) {

            $this->Flash->error(
                'Username must be at least 3 characters long.'
            );

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * GET EMAIL
        * =========================================================
        */
        $email = trim(
            (string)($data['email'] ?? '')
        );

        if ($email === '') {

            $this->Flash->error(
                'Email address is required.'
            );

            return $this->redirect($returnUrl);
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $this->Flash->error(
                'Please enter a valid email address.'
            );

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * CHECK DUPLICATE USERNAME
        * =========================================================
        */
        $existingUsername = $this->Users->find()
            ->where([
                'Users.username' => $username,
                'Users.id !=' => $userId
            ])
            ->first();

        if ($existingUsername) {

            $this->Flash->error(
                'That username is already being used by another account.'
            );

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * CHECK DUPLICATE EMAIL
        * =========================================================
        */
        $existingEmail = $this->Users->find()
            ->where([
                'Users.email' => $email,
                'Users.id !=' => $userId
            ])
            ->first();

        if ($existingEmail) {

            $this->Flash->error(
                'That email address is already being used by another account.'
            );

            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * PASSWORD DATA
        * =========================================================
        */
        $currentPassword = (string)(
            $data['current_password'] ?? ''
        );

        $newPassword = (string)(
            $data['new_password'] ?? ''
        );

        $confirmPassword = (string)(
            $data['confirm_password'] ?? ''
        );


        /*
        * =========================================================
        * DETERMINE IF PASSWORD IS BEING CHANGED
        * =========================================================
        */
        $changingPassword = ($newPassword !== '');


        /*
        * =========================================================
        * DETERMINE WHICH INFORMATION CHANGED
        * =========================================================
        */
        $usernameChanged = (
            $username !== $originalUsername
        );

        $emailChanged = (
            strtolower($email) !== strtolower($originalEmail)
        );

        $passwordChanged = false;


        /*
        * =========================================================
        * PASSWORD CHANGE
        * =========================================================
        */
        if ($changingPassword) {

            /*
            * -----------------------------------------------------
            * CURRENT PASSWORD REQUIRED
            * -----------------------------------------------------
            */
            if ($currentPassword === '') {

                $this->Flash->error(
                    'Please enter your current password.'
                );

                return $this->redirect($returnUrl);
            }


            /*
            * -----------------------------------------------------
            * PASSWORD HASHER
            *
            * IMPORTANT:
            * User.php already hashes password through _setPassword().
            * -----------------------------------------------------
            */
            $hasher = new \Cake\Auth\DefaultPasswordHasher();


            /*
            * -----------------------------------------------------
            * VERIFY CURRENT PASSWORD
            * -----------------------------------------------------
            */
            if (
                empty($user->password) ||
                !$hasher->check(
                    $currentPassword,
                    $user->password
                )
            ) {

                $this->Flash->error(
                    'The current password is incorrect.'
                );

                return $this->redirect($returnUrl);
            }


            /*
            * -----------------------------------------------------
            * PASSWORD REQUIREMENTS
            *
            * At least:
            * 8 characters
            * 1 uppercase
            * 1 special character
            * -----------------------------------------------------
            */
            if (!preg_match(
                '/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/',
                $newPassword
            )) {

                $this->Flash->error(
                    'New password must be at least 8 characters and contain at least one capital letter and one special character.'
                );

                return $this->redirect($returnUrl);
            }


            /*
            * -----------------------------------------------------
            * CONFIRM PASSWORD
            * -----------------------------------------------------
            */
            if ($confirmPassword === '') {

                $this->Flash->error(
                    'Please confirm your new password.'
                );

                return $this->redirect($returnUrl);
            }


            if ($newPassword !== $confirmPassword) {

                $this->Flash->error(
                    'The new passwords do not match.'
                );

                return $this->redirect($returnUrl);
            }


            /*
            * -----------------------------------------------------
            * PREVENT SAME PASSWORD
            * -----------------------------------------------------
            */
            if ($hasher->check(
                $newPassword,
                $user->password
            )) {

                $this->Flash->error(
                    'Your new password must be different from your current password.'
                );

                return $this->redirect($returnUrl);
            }


            /*
            * -----------------------------------------------------
            * SET NEW PASSWORD
            *
            * DO NOT HASH MANUALLY.
            *
            * User.php:
            * _setPassword()
            * automatically hashes the password.
            * -----------------------------------------------------
            */
            $user->password = $newPassword;

            /*
            * Force CakePHP to save the password field.
            */
            $user->setDirty('password', true);

            $passwordChanged = true;
        }


        /*
        * =========================================================
        * UPDATE USERNAME
        * =========================================================
        */
        $user->username = $username;


        /*
        * =========================================================
        * UPDATE EMAIL
        * =========================================================
        */
        $user->email = $email;


        /*
        * =========================================================
        * SAVE USER
        * =========================================================
        */
        $saved = $this->Users->save(
            $user,
            [
                'checkRules' => true,
                'checkExisting' => true
            ]
        );


        /*
        * =========================================================
        * SAVE SUCCESS
        * =========================================================
        */
        if ($saved) {

            /*
            * -----------------------------------------------------
            * VERIFY PASSWORD WAS ACTUALLY STORED
            * -----------------------------------------------------
            */
            if ($passwordChanged) {

                $savedUser = $this->Users->get($userId);

                if (
                    empty($savedUser->password) ||
                    !$hasher->check(
                        $newPassword,
                        $savedUser->password
                    )
                ) {

                    $this->Flash->error(
                        'The account was saved, but the new password was not stored correctly.'
                    );

                    return $this->redirect($returnUrl);
                }
            }


            /*
            * =====================================================
            * UPDATE CURRENT AUTH SESSION
            *
            * This prevents the username/email from becoming stale
            * in the current session.
            * =====================================================
            */
            $currentAuthUser = $this->Auth->user();

            if ($currentAuthUser) {

                $currentAuthUser['username'] = $user->username;
                $currentAuthUser['email']    = $user->email;

                $this->Auth->setUser($currentAuthUser);
            }


            /*
            * =====================================================
            * BUILD SUCCESS CONFIRMATION
            * =====================================================
            */
            $updatedItems = [];

            if ($usernameChanged) {
                $updatedItems[] = 'Username';
            }

            if ($emailChanged) {
                $updatedItems[] = 'Email address';
            }

            if ($passwordChanged) {
                $updatedItems[] = 'Password';
            }


            /*
            * =====================================================
            * SUCCESS MESSAGE
            * =====================================================
            */
            if (!empty($updatedItems)) {

                $message = implode(
                    ', ',
                    $updatedItems
                ) . (
                    count($updatedItems) === 1
                        ? ' has been updated successfully.'
                        : ' have been updated successfully.'
                );

            } else {

                $message = 'Your account information is already up to date.';
            }


            /*
            * =====================================================
            * STORE SUCCESS MESSAGE FOR SWEETALERT
            *
            * The profile page will display this after redirect.
            * =====================================================
            */
            $this->request
                ->getSession()
                ->write(
                    'EditAccountSuccess',
                    [
                        'message' => $message,
                        'usernameChanged' => $usernameChanged,
                        'emailChanged' => $emailChanged,
                        'passwordChanged' => $passwordChanged
                    ]
                );


            /*
            * =====================================================
            * ALSO KEEP FLASH MESSAGE
            * =====================================================
            */
            $this->Flash->success($message);


            /*
            * =====================================================
            * RETURN TO PROFILE
            * =====================================================
            */
            return $this->redirect($returnUrl);
        }


        /*
        * =========================================================
        * SAVE FAILED
        * =========================================================
        */
        $errors = $user->getErrors();

        if (!empty($errors)) {

            foreach ($errors as $field => $fieldErrors) {

                foreach ($fieldErrors as $error) {

                    $message = is_array($error)
                        ? implode(', ', $error)
                        : (string)$error;

                    $this->Flash->error(
                        ucfirst($field) . ': ' . $message
                    );
                }
            }

        } else {

            $this->Flash->error(
                'Unable to update your account. Please check your information and try again.'
            );
        }


        /*
        * =========================================================
        * RETURN TO PROFILE
        * =========================================================
        */
        return $this->redirect($returnUrl);
    }
    
    /**
 * Check whether username already exists.
 */
public function checkUsername()
{
    $this->request->allowMethod(['get']);

    $username = trim(
        (string)$this->request->getQuery('username')
    );

    if ($username === '') {

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'success',
                'exists' => false
            ]));
    }


    $exists = $this->Users
        ->find()
        ->where([
            'username' => $username
        ])
        ->count() > 0;


    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'status' => $exists ? 'error' : 'success',
            'exists' => $exists
        ]));
}


/**
 * Check whether email already exists.
 */
public function checkEmail()
{
    $this->request->allowMethod(['get']);

    $email = trim(
        (string)$this->request->getQuery('email')
    );

    if ($email === '') {

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'success',
                'exists' => false
            ]));
    }


    $exists = $this->Users
        ->find()
        ->where([
            'email' => $email
        ])
        ->count() > 0;


    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'status' => $exists ? 'error' : 'success',
            'exists' => $exists
        ]));
}
}