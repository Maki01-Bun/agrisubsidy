<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Http\Response;

/**
 * AuditLogs Controller
 *
 * API controller for audit logs.
 *
 * @property \App\Model\Table\AuditLogsTable $AuditLogs
 */
class AuditLogsController extends AppController
{
    /**
     * Index method
     *
     * GET /api/AuditLogs
     *
     * Returns all audit logs as JSON.
     *
     * @return \Cake\Http\Response
     */
    public function index(): Response
    {
        $auditLogs = $this->AuditLogs
            ->find()
            ->contain(['Users'])
            ->order([
                'AuditLogs.action_date' => 'DESC'
            ])
            ->all();

        $data = [];

        foreach ($auditLogs as $auditLog) {
            $data[] = [
                'id' => $auditLog->id,
                'user_id' => $auditLog->user_id,
                'action' => $auditLog->action,
                'action_date' => $auditLog->action_date
                    ? $auditLog->action_date->format('Y-m-d H:i:s')
                    : null,

                'user' => $auditLog->user
                    ? [
                        'id' => $auditLog->user->id ?? null,
                        'username' => $auditLog->user->username ?? null,
                        'name' => $auditLog->user->name ?? null,
                    ]
                    : null,
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => true,
                    'data' => $data,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
    }

    /**
     * View method
     *
     * GET /api/AuditLogs/view/{id}
     *
     * Returns one audit log as JSON.
     *
     * @param string|null $id Audit log ID.
     *
     * @return \Cake\Http\Response
     */
    public function view(?string $id = null): Response
    {
        if ($id === null || !is_numeric($id)) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'A valid audit log ID is required.',
                    ])
                );
        }

        try {
            $auditLog = $this->AuditLogs->get(
                (int)$id,
                [
                    'contain' => ['Users'],
                ]
            );

            $data = [
                'id' => $auditLog->id,
                'user_id' => $auditLog->user_id,
                'action' => $auditLog->action,
                'action_date' => $auditLog->action_date
                    ? $auditLog->action_date->format('Y-m-d H:i:s')
                    : null,

                'user' => $auditLog->user
                    ? [
                        'id' => $auditLog->user->id ?? null,
                        'username' => $auditLog->user->username ?? null,
                        'name' => $auditLog->user->name ?? null,
                    ]
                    : null,
            ];

            return $this->response
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => true,
                        'data' => $data,
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                );
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            return $this->response
                ->withStatus(404)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'Audit log not found.',
                    ])
                );
        }
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
     * POST /api/AuditLogs/add
     *
     * @return \Cake\Http\Response
     */
    public function add(): Response
    {
        if (!$this->request->is('post')) {
            return $this->response
                ->withStatus(405)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => false,
                        'message' => 'Method not allowed.',
                    ])
                );
        }

        $auditLog = $this->AuditLogs->newEmptyEntity();

        $auditLog = $this->AuditLogs->patchEntity(
            $auditLog,
            $this->request->getData()
        );

        if ($this->AuditLogs->save($auditLog)) {
            return $this->response
                ->withStatus(201)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'success' => true,
                        'message' => 'The audit log has been saved.',
                        'data' => [
                            'id' => $auditLog->id,
                            'user_id' => $auditLog->user_id,
                            'action' => $auditLog->action,
                            'action_date' => $auditLog->action_date
                                ? $auditLog->action_date->format('Y-m-d H:i:s')
                                : null,
                        ],
                    ])
                );
        }

        return $this->response
            ->withStatus(422)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'message' => 'The audit log could not be saved.',
                    'errors' => $auditLog->getErrors(),
                ])
            );
    }
}
