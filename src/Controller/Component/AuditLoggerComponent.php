<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AuditLoggerComponent extends Component
{
    protected $AuditLogs;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->AuditLogs = TableRegistry::getTableLocator()
            ->get('AuditLogs');
    }

    public function logActivity(
        string $action,
        string $description,
        $subject = null,
        ?array $properties = null
    ): bool {
        $controller = $this->getController();

        /*
        * Get authenticated user
        */
        $user = $controller->Auth->user();

        $userId = null;

        if ($user) {
            if (is_array($user) && isset($user['id'])) {
                $userId = (int)$user['id'];
            } elseif (is_object($user) && isset($user->id)) {
                $userId = (int)$user->id;
            }
        }

        /*
        * Request information
        */
        $request = $controller->getRequest();

        /*
        * Subject information
        */
        $subjectType = null;
        $subjectId = null;

        if ($subject !== null) {

            /*
            * CakePHP Entity
            */
            if (is_object($subject)) {

                if (isset($subject->id)) {
                    $subjectId = (int)$subject->id;
                }

                $class = get_class($subject);

                $subjectType = basename(
                    str_replace('\\', '/', $class)
                );
            }

            /*
            * Auth user array
            */
            elseif (is_array($subject)) {

                if (isset($subject['id'])) {
                    $subjectId = (int)$subject['id'];
                }

                $subjectType = 'User';
            }
        }

        /*
        * Create audit log
        */
        $auditLog = $this->AuditLogs->newEmptyEntity();

        $auditLog->user_id = $userId;
        $auditLog->action = $action;
        $auditLog->subject_type = $subjectType;
        $auditLog->subject_id = $subjectId;
        $auditLog->description = $description;

        $auditLog->properties = $properties !== null
            ? json_encode(
                $properties,
                JSON_UNESCAPED_UNICODE
            )
            : null;

        $auditLog->ip_address = $request->clientIp();

        $auditLog->user_agent =
            $request->getHeaderLine('User-Agent');

        return (bool)$this->AuditLogs->save($auditLog);
    }
}