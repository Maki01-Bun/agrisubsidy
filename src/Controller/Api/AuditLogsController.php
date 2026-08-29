<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

class AuditLogsController extends AppController
{
    /**
     * GET /api/AuditLogs/index
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $audits = $this->AuditLogs->find()
            ->order([
                'action_date' => 'DESC'
            ])
            ->all();

        $data = [];

        foreach ($audits as $audit) {

            $data[] = [
                'id' => $audit->id,
                'action' => $audit->action,
                'action_date' => $audit->action_date
                    ? $audit->action_date->format('Y-m-d H:i:s')
                    : null
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'data' => $data
                ])
            );
    }


    /**
     * GET /api/AuditLogs/getAudits
     */
    public function getAudits()
    {
        $this->request->allowMethod(['get']);

        $audits = $this->AuditLogs->find()
            ->order([
                'action_date' => 'DESC'
            ])
            ->all();

        $data = [];

        foreach ($audits as $audit) {

            $data[] = [
                'id' => $audit->id,
                'action' => $audit->action,
                'action_date' => $audit->action_date
                    ? $audit->action_date->format('Y-m-d H:i:s')
                    : null
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'data' => $data
                ])
            );
    }


    /**
     * GET /api/AuditLogs/view/{id}
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);

        try {

            $audit = $this->AuditLogs->get($id);

            $data = [
                'id' => $audit->id,
                'action' => $audit->action,
                'action_date' => $audit->action_date
                    ? $audit->action_date->format('Y-m-d H:i:s')
                    : null
            ];

            return $this->response
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'data' => $data
                    ])
                );

        } catch (\Exception $e) {

            return $this->response
                ->withStatus(404)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'Audit log not found.'
                    ])
                );
        }
    }
}
