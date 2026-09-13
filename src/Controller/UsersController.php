<?php
declare(strict_types=1);

namespace App\Controller;

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

        $notificationsTable = $this->getTableLocator()->get('Notifications');
        $usersTable = $this->Users;

        //STEP 1 - FARMER INFORMATION
        if ($step == 1 && $this->request->is('post')) {
            $session->write(
                'Registration.Farmer',
                $this->request->getData()
            );
            return $this->redirect(['action' => 'register',2 ]);
        }

        //STEP 2 - ACCOUNT INFORMATION
        if ($step == 2 && $this->request->is('post')) {
            $farmerData = $session->read('Registration.Farmer');
            if (!$farmerData) {
                $this->Flash->error(
                    __('Registration session expired.')
                );
                return $this->redirect([
                    'action' => 'register',
                    1
                ]);
            }
            $userData = $this->request->getData();

            // Validate password
            $password = $userData['password'] ?? '';
            $confirmPassword = $userData['confirm_password'] ?? '';

            if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
                $this->Flash->error(  __('Password must be at least 8 characters and contain at least one capital letter and one special character.')
                );
                return $this->redirect(['action' => 'register', 2]);
            }
            if ($password !== $confirmPassword) {
                $this->Flash->error(__('Password and Confirm Password do not match.')
                );
                return $this->redirect(['action' => 'register', 2]);
            }
            //CHECK DUPLICATES
            $existingUser = $usersTable
                ->find()->where(['username' => $userData['username']])->first();

            if ($existingUser) {
                $this->Flash->error(__('Username already exists.')
                );
                return $this->redirect(['action' => 'register',2
                ]);
            }

            //STORE REQUEST IN NOTIFICATION
            $registrationData = ['farmer' => $farmerData,'user'   => $userData];
            $notification = $notificationsTable->newEmptyEntity();
            $notification = $notificationsTable->patchEntity(
                $notification,
                [
                    'user_id' => null,
                    'title'   => 'New Farmer Registration',
                    'message' => 'A new farmer registration requires approval.',
                    'type'    => 'registration',
                    'status'  => 'pending',
                    'data'    => json_encode($registrationData)
                ]
            );
            if ($notificationsTable->save($notification)) {
                $session->delete('Registration.Farmer');
                $this->Flash->success(__('Registration submitted successfully. Please wait for admin approval.')
                );
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(
                __('Unable to submit registration.')
            );
        }
        $user = $usersTable->newEmptyEntity();

        $this->set(compact('user','step'));
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
        return $this->redirect($this->Auth->logout());
    }

}