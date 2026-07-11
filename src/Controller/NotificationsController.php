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
    $notifications = $this->Notifications
            ->find()
            ->order(['id' => 'DESC']);

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

            $user = $usersTable->newEntity($data['user']);

            if (!$usersTable->save($user)) {
                throw new \Exception('User save failed');
            }

            $data['farmer']['user_id'] = $user->id;

            $farmer = $farmersTable->newEntity($data['farmer']);

            if (!$farmersTable->save($farmer)) {
                throw new \Exception('Farmer save failed');
            }

            $notification->status = 'approved';
            $notification->is_read = 1;

            $notificationsTable->save($notification);

            $connection->commit();

            $this->Flash->success(__('Registration approved.'));
        } catch (\Exception $e) {

            $connection->rollback();

            $this->Flash->error(__('Approval failed.'));
        }

        return $this->redirect(['action' => 'index']);
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

        return $this->redirect(['action' => 'index']);
    }

    public function viewRegistration($id)
    {
        $notification = $this->Notifications->get($id);
    
        $data = json_decode($notification->data, true);
    
        $this->set(compact(
            'notification',
            'data'
        ));
    }
}
