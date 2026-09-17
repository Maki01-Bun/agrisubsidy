<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Notifications Controller
 *
 * @method \App\Model\Entity\Notification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NotificationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
{
    $notifications = $this->Notifications->find()->order(['id' => 'DESC']);

    $this->set(compact('notifications'));
}

    /**
     * View method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $notification = $this->Notifications->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('notification'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $notification = $this->Notifications->newEmptyEntity();
        if ($this->request->is('post')) {
            $notification = $this->Notifications->patchEntity($notification, $this->request->getData());
            if ($this->Notifications->save($notification)) {
                $this->Flash->success(__('The notification has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The notification could not be saved. Please, try again.'));
        }
        $this->set(compact('notification'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $notification = $this->Notifications->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $notification = $this->Notifications->patchEntity($notification, $this->request->getData());
            if ($this->Notifications->save($notification)) {
                $this->Flash->success(__('The notification has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The notification could not be saved. Please, try again.'));
        }
        $this->set(compact('notification'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $notification = $this->Notifications->get($id);
        if ($this->Notifications->delete($notification)) {
            $this->Flash->success(__('The notification has been deleted.'));
        } else {
            $this->Flash->error(__('The notification could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function deleteAll()
    {
        $this->request->allowMethod(['post']);

        $result = $this->Notifications
            ->query()
            ->delete()
            ->execute();

        $this->Flash->success(
            __('All notifications deleted.')
        );

        return $this->redirect(['action' => 'index']);
    }


       public function count()
        {
            $this->request->allowMethod(['get']);

            $user = $this->Auth->user();

            $count = $this->Notifications
                ->find()
                ->where([
                    'is_read' => 0,
                    'user_id' => $user['id']
                ])
                ->count();

            $this->set([
                'count' => $count,
                '_serialize' => ['count']
            ]);
        }

        public function approveRegistration($id)
    {
        $notificationsTable = $this->getTableLocator()->get('Notifications');
        $usersTable = $this->getTableLocator()->get('Users');
        $farmersTable = $this->getTableLocator()->get('Farmers');

        $notification = $notificationsTable->get($id);

        $data = json_decode($notification->data, true);

        $connection = $usersTable->getConnection();

        $connection->begin();

        try {

            // Check if username already exists
            $existingUser = $usersTable->find()
                ->where(['username' => $data['user']['username']])
                ->first();

            if ($existingUser) {
                throw new \Exception('Username already exists.');
            }

            // Create the system account
            $user = $usersTable->newEntity($data['user']);

            if (!$usersTable->save($user)) {
                throw new \Exception('User save failed.');
            }

            // Check if farmer already exists
            $existingFarmer = $farmersTable->find()
                ->where([
                    'first_name'  => $data['farmer']['first_name'],
                    'middle_name' => $data['farmer']['middle_name'],
                    'last_name'   => $data['farmer']['last_name']
                ])
                ->first();

            if ($existingFarmer) {

                // Farmer already exists
                // Link the system account to the existing farmer
                $existingFarmer->user_id = $user->id;

                if (!$farmersTable->save($existingFarmer)) {
                    throw new \Exception('Unable to link user to existing farmer.');
                }

            } else {

                // Farmer not found
                // Create a new farmer record
                $data['farmer']['user_id'] = $user->id;

                $farmer = $farmersTable->newEntity($data['farmer']);

                if (!$farmersTable->save($farmer)) {
                    throw new \Exception('Farmer save failed.');
                }
            }

            // Update notification
            $notification->status = 'approved';
            $notification->is_read = 1;

            if (!$notificationsTable->save($notification)) {
                throw new \Exception('Notification update failed.');
            }

            $connection->commit();

            $this->Flash->success(__('Registration approved successfully.'));

        } catch (\Exception $e) {

            $connection->rollback();

            $this->Flash->error($e->getMessage());
        }

        return $this->redirect(['controller'=>'Dashboard','action' => 'index']);
    }

    public function declineRegistration($id)
    {
        $notification = $this->Notifications->get($id);

        $notification->status = 'declined';
        $notification->is_read = 1;

        if ($this->Notifications->save($notification)) {
            $this->Flash->success(__('Registration declined.'));
        } else {
            $this->Flash->error(__('Unable to decline registration.'));
        }

        return $this->redirect(['controller'=>'Dashboard','action' => 'index']);
    }

    public function viewRegistration($id)
    {
        $notificationsTable = $this->getTableLocator()->get('Notifications');
        $farmersTable = $this->getTableLocator()->get('Farmers');
        $notification = $notificationsTable->get($id);
        $data = json_decode($notification->data, true);

        // Check existing farmer
        $existingFarmer = $farmersTable->find()
            ->where([
                'first_name' => $data['farmer']['first_name'],
                'middle_name' => $data['farmer']['middle_name'],
                'last_name' => $data['farmer']['last_name']
            ])
            ->first();


        $this->set([
            'notification' => $notification,
            'data' => $data,
            'existingFarmer' => $existingFarmer
        ]);
    }

    public function getRegistrationDetails($id)
{
    $this->request->allowMethod(['get']);

    try {
        $notificationsTable = $this->getTableLocator()->get('Notifications');
        $farmersTable = $this->getTableLocator()->get('Farmers');

        // Get notification
        $notification = $notificationsTable->find()
            ->where(['Notifications.id' => $id])
            ->first();

        if (!$notification) {
            $this->response = $this->response->withStatus(404);
            $this->response = $this->response->withType('application/json');

            return $this->response->withStringBody(json_encode([
                'success' => false,
                'message' => 'Registration notification not found.'
            ]));
        }

        // Decode notification data
        $data = $notification->data;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data)) {
            $this->response = $this->response->withStatus(500);
            $this->response = $this->response->withType('application/json');

            return $this->response->withStringBody(json_encode([
                'success' => false,
                'message' => 'Invalid registration data.'
            ]));
        }

        $farmerData = $data['farmer'] ?? [];
        $userData = $data['user'] ?? [];

        /*
         * Check if farmer already exists
         */
        $existingFarmer = null;

        $firstName = trim((string)($farmerData['first_name'] ?? ''));
        $middleName = trim((string)($farmerData['middle_name'] ?? ''));
        $lastName = trim((string)($farmerData['last_name'] ?? ''));

        if ($firstName !== '' && $lastName !== '') {

            $conditions = [
                'Farmers.first_name' => $firstName,
                'Farmers.last_name' => $lastName
            ];

            // Only compare middle name if supplied
            if ($middleName !== '') {
                $conditions['Farmers.middle_name'] = $middleName;
            }

            $existingFarmer = $farmersTable->find()
                ->where($conditions)
                ->first();
        }

        /*
         * Convert existing farmer to JSON-safe array
         */
        $existingFarmerData = null;

        if ($existingFarmer) {
            $existingFarmerData = [
                'id' => $existingFarmer->id ?? null,
                'farmer_no' => $existingFarmer->farmer_no ?? null,
                'first_name' => $existingFarmer->first_name ?? null,
                'middle_name' => $existingFarmer->middle_name ?? null,
                'last_name' => $existingFarmer->last_name ?? null
            ];
        }

        /*
         * Prepare response
         */
        $result = [
            'success' => true,
            'status' => $notification->status ?? 'pending',

            'data' => [
                'user' => $userData,
                'farmer' => $farmerData
            ],

            'existingFarmer' => $existingFarmerData
        ];

        $this->response = $this->response
            ->withStatus(200)
            ->withType('application/json');

        return $this->response->withStringBody(
            json_encode($result)
        );

    } catch (\Throwable $e) {

        // Log the real error
        \Cake\Log\Log::error(
            'getRegistrationDetails Error: ' .
            $e->getMessage() .
            "\n" .
            $e->getTraceAsString()
        );

        $this->response = $this->response
            ->withStatus(500)
            ->withType('application/json');

        // Don't expose internal server details to the browser
        return $this->response->withStringBody(json_encode([
            'success' => false,
            'message' => 'Unable to load registration details.'
        ]));
    }
}


/**
 * Bulk approve registration requests
 */
public function bulkApprove()
{
    $this->request->allowMethod(['post']);

    $notificationsTable = $this->getTableLocator()->get('Notifications');
    $usersTable = $this->getTableLocator()->get('Users');
    $farmersTable = $this->getTableLocator()->get('Farmers');

    $notificationIds = $this->request->getData('notification_ids');

    /*
     * Make sure IDs were selected
     */
    if (
        empty($notificationIds) ||
        !is_array($notificationIds)
    ) {
        $this->Flash->error(
            __('Please select at least one registration request.')
        );

        return $this->redirect(
            $this->referer()
        );
    }

    $approved = 0;
    $failed = 0;

    foreach ($notificationIds as $notificationId) {

        $connection = $usersTable->getConnection();

        try {

            /*
             * Get registration notification
             */
            $notification = $notificationsTable->get(
                $notificationId
            );

            /*
             * Only process pending registrations
             */
            if (
                strtolower(
                    trim(
                        (string)$notification->status
                    )
                ) !== 'pending'
            ) {
                continue;
            }

            /*
             * Decode registration data
             */
            $data = json_decode(
                $notification->data,
                true
            );

            if (
                !is_array($data) ||
                empty($data['user']) ||
                empty($data['farmer'])
            ) {
                $failed++;
                continue;
            }

            /*
             * Start transaction
             */
            $connection->begin();

            /*
             * ==========================================
             * CHECK USERNAME
             * ==========================================
             */
            $existingUser = $usersTable->find()
                ->where([
                    'username' =>
                        $data['user']['username']
                ])
                ->first();

            if ($existingUser) {

                throw new \Exception(
                    'Username already exists: ' .
                    $data['user']['username']
                );
            }

            /*
             * ==========================================
             * CREATE USER
             * ==========================================
             */
            $user = $usersTable->newEntity(
                $data['user']
            );

            if (
                !$usersTable->save($user)
            ) {

                throw new \Exception(
                    'Unable to create user account.'
                );
            }

            /*
             * ==========================================
             * CHECK EXISTING FARMER
             * ==========================================
             */
            $existingFarmer = $farmersTable->find()
                ->where([
                    'first_name' =>
                        $data['farmer']['first_name'],

                    'middle_name' =>
                        $data['farmer']['middle_name'],

                    'last_name' =>
                        $data['farmer']['last_name']
                ])
                ->first();

            /*
             * ==========================================
             * LINK EXISTING FARMER
             * ==========================================
             */
            if ($existingFarmer) {

                $existingFarmer->user_id =
                    $user->id;

                if (
                    !$farmersTable->save(
                        $existingFarmer
                    )
                ) {

                    throw new \Exception(
                        'Unable to link existing farmer to user account.'
                    );
                }

            } else {

                /*
                 * ======================================
                 * CREATE FARMER
                 * ======================================
                 */
                $data['farmer']['user_id'] =
                    $user->id;

                $farmer =
                    $farmersTable->newEntity(
                        $data['farmer']
                    );

                if (
                    !$farmersTable->save($farmer)
                ) {

                    throw new \Exception(
                        'Unable to create farmer record.'
                    );
                }
            }

            /*
             * ==========================================
             * APPROVE NOTIFICATION
             * ==========================================
             */
            $notification->status =
                'approved';

            $notification->is_read = 1;

            if (
                !$notificationsTable->save(
                    $notification
                )
            ) {

                throw new \Exception(
                    'Unable to update registration request.'
                );
            }

            /*
             * ==========================================
             * COMMIT TRANSACTION
             * ==========================================
             */
            $connection->commit();

            $approved++;

        } catch (\Throwable $e) {

            /*
             * Roll back this registration only
             */
            if (
                $connection->inTransaction()
            ) {
                $connection->rollback();
            }

            $failed++;

            /*
             * Log the actual error
             */
            \Cake\Log\Log::error(
                'Bulk registration approval failed: ' .
                $e->getMessage()
            );
        }
    }

    /*
     * ==============================================
     * FLASH MESSAGES
     * ==============================================
     */

    if ($approved > 0) {

        $this->Flash->success(
            __(
                '{0} registration request(s) approved successfully.',
                $approved
            )
        );
    }

    if ($failed > 0) {

        $this->Flash->error(
            __(
                '{0} registration request(s) could not be approved.',
                $failed
            )
        );
    }

    return $this->redirect(
        $this->referer()
    );
}



}
