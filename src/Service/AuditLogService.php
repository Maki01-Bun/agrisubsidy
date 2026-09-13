<?php

namespace App\Service;

use Cake\ORM\TableRegistry;
use Cake\Http\ServerRequest;

class AuditLogService
{
    protected $auditLogs;

    public function __construct()
    {
        $this->auditLogs = TableRegistry::getTableLocator()
            ->get('AuditLogs');
    }

    public function log(
        ServerRequest $request,
        int $userId,
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $properties = []
    ): bool {

        $auditLog = $this->auditLogs->newEntity([
            'user_id' => $userId,

            'action' => $action,

            'subject_type' => $subjectType,

            'subject_id' => $subjectId,

            'description' => $description,

            'properties' => !empty($properties)
                ? json_encode($properties, JSON_UNESCAPED_UNICODE)
                : null,

            'ip_address' => $request->clientIp(),

            'user_agent' => $request->getHeaderLine('User-Agent'),
        ]);

        return (bool)$this->auditLogs->save($auditLog);
    }
}