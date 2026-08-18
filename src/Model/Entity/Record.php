<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Record Entity
 *
 * @property int $id
 * @property string $subsidy_item
 * @property float $quantity
 * @property \Cake\I18n\FrozenTime $distribution_date
 * @property \Cake\I18n\FrozenTime $received_date
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \App\Model\Entity\Farmer[] $farmers
 */
class Record extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
     protected $_accessible = [
        'farmer_id' => true,
        'subsidy_item' => true,
        'quantity' => true,
        'distribution_date' => true,
        'received_date' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'farmer' => true,
    ];
}
