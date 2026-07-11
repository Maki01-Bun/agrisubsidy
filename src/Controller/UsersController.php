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

    /*
    STEP 1 - FARMER INFORMATION
    */
    if ($step == 1 && $this->request->is('post')) {

        $session->write(
            'Registration.Farmer',
            $this->request->getData()
        );

        return $this->redirect([
            'action' => 'register',
            2
        ]);
    }

    /*
    STEP 2 - ACCOUNT INFORMATION
    */
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

        /*
        CHECK DUPLICATES
        */
        $existingUser = $usersTable
            ->find()
            ->where([
                'username' => $userData['username']
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

        /*
         STORE REQUEST IN NOTIFICATION
        */
        $registrationData = [
            'farmer' => $farmerData,
            'user'   => $userData
        ];

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

            $this->Flash->success(
                __('Registration submitted successfully. Please wait for admin approval.')
            );

            return $this->redirect([
                'action' => 'login'
            ]);
        }

        $this->Flash->error(
            __('Unable to submit registration.')
        );
    }

    $user = $usersTable->newEmptyEntity();

    $this->set(compact(
        'user',
        'step'
    ));
}
    public function login()
    {
        $this->viewBuilder()->setLayout('login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

}