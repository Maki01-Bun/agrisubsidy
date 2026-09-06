<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AuditLogs Controller
 *
 * @property \App\Model\Table\AuditLogsTable $AuditLogs
 * @method \App\Model\Entity\AuditLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuditLogsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $auditLogs = $this->paginate($this->AuditLogs);

        $this->set(compact('auditLogs'));
    }

    /**
     * View method
     *
     * @param string|null $id Audit Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $auditLog = $this->AuditLogs->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('auditLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $auditLog = $this->AuditLogs->newEmptyEntity();
        if ($this->request->is('post')) {
            $auditLog = $this->AuditLogs->patchEntity($auditLog, $this->request->getData());
            if ($this->AuditLogs->save($auditLog)) {
                $this->Flash->success(__('The audit log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The audit log could not be saved. Please, try again.'));
        }
        $users = $this->AuditLogs->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('auditLog', 'users'));
    }
}
