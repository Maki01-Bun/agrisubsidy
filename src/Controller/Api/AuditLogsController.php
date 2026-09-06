<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

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
        $auditLogs = $this->AuditLogs->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($auditLogs));
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
            'contain' => [],
        ]);

        $this->set(compact('auditLog'));
    }
    
    public function getAuditLogs()
    {
        $auditLogs = $this->AuditLogs->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$auditLogs]));
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
                $result = ['status' => 'success', 'message' => 'The audit log has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The audit log could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }
}
