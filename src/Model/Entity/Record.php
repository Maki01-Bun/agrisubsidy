<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Record Entity
 *
 * @property int $id
 * @property int $farmer_id
 * @property string $subsidy_item
 * @property float $quantity
 * @property \Cake\I18n\FrozenTime|null $received_date
 * @property string $status
 * @property int|null $schedule_id
 * @property \Cake\I18n\FrozenTime|null $confirmed_at
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \App\Model\Entity\Farmer $farmer
 * @property \App\Model\Entity\Schedule|null $schedule
 */
class Record extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'farmer_id' => true,
        'subsidy_item' => true,
        'quantity' => true,
        'received_date' => true,
        'status' => true,
        'schedule_id' => true,
        'confirmed_at' => true,
        'created' => true,
        'modified' => true,
        'farmer' => true,
        'schedule' => true,
    ];
}