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
        $auditLogs = $this->AuditLogs
            ->find()
            ->contain(['Users'])
            ->order([
                'AuditLogs.created' => 'DESC'
            ]);

        $this->set(compact('auditLogs'));
    }
}
