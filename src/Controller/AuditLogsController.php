<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;

class AuditLogsController extends AppController
{
    /**
     * Audit Logs page
     */
    public function index()
    {
        // Data is loaded through the API.
    }


    /**
     * Add Audit Log
     *
     * POST /AuditLogs/add
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $auditLogs = $this->getTableLocator()->get('AuditLogs');

        /*
         * Get action from request.
         */
        $action = trim(
            (string)$this->request->getData('action')
        );

        /*
         * Validate action.
         */
        if ($action === '') {

            return $this->response
                ->withType('application/json')
                ->withStatus(400)
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'Audit action is required.'
                    ])
                );
        }


        /*
         * Create new audit log entity.
         */
        $log = $auditLogs->newEmptyEntity();


        /*
         * Get currently authenticated user.
         */
        $identity = $this->request->getAttribute('identity');


        /*
         * Save user ID.
         */
        if ($identity) {

            $log->user_id = $identity->getIdentifier();

        } else {

            $log->user_id = null;
        }


        /*
         * Save action.
         */
        $log->action = $action;


        /*
         * Save current date and time.
         */
        $log->action_date = FrozenTime::now();


        /*
         * Save audit log.
         */
        if ($auditLogs->save($log)) {

            return $this->response
                ->withType('application/json')
                ->withStatus(200)
                ->withStringBody(
                    json_encode([
                        'success' => true,
                        'message' => 'Audit log saved successfully.',
                        'data' => [
                            'id' => $log->id,
                            'user_id' => $log->user_id,
                            'action' => $log->action,
                            'action_date' => $log->action_date
                                ? $log->action_date->format('Y-m-d H:i:s')
                                : null
                        ]
                    ])
                );
        }


        /*
         * Save failed.
         */
        return $this->response
            ->withType('application/json')
            ->withStatus(500)
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'message' => 'Unable to save audit log.',
                    'errors' => $log->getErrors()
                ])
            );
    }
}
