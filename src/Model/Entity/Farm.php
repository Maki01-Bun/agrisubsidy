<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Farm Entity
 *
 * @property int $id
 * @property string $farmer_name
 * @property float $farm_size
 * @property string $location
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Farm extends Entity
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
        'farmer_name' => true,
        'farm_size' => true,
        'location' => true,
        'created' => true,
        'modified' => true,
        'farmers' => true,
    ];
}
