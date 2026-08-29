<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * POS Controller
 *
 * @method \App\Model\Entity\PO[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuditLogsController extends AppController
{
     public function index()
    {

    }
      public function add()
    {
        $this->request->allowMethod(['post']);

        $auditLogs = $this->getTableLocator()->get('AuditLogs');

        $log = $auditLogs->newEmptyEntity();
   
        $user = $this->request->getAttribute('identity');

        $log->user_id = $user ? $user->id : null;
        $log->action = $this->request->getData('action');
        $log->action_date = date('Y-m-d H:i:s');

        if ($auditLogs->save($log)) {
            $this->set([
                'success' => true,
                '_serialize' => ['success']
            ]);
        } else {
            $this->set([
                'success' => false,
                '_serialize' => ['success']
            ]);
        }
    }
}
