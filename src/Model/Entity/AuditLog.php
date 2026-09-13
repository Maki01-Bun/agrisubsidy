<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuditLog Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string $description
 * @property string|null $properties
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\User $user
 */
class AuditLog extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'action' => true,
        'subject_type' => true,
        'subject_id' => true,
        'description' => true,
        'properties' => true,
        'ip_address' => true,
        'user_agent' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
    ];
}