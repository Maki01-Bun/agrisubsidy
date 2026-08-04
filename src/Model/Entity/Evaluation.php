<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Evaluation Entity
 *
 * @property int $id
 * @property int|null $farmer_id
 * @property int|null $feedback_id
 * @property int|null $pest_id
 * @property string $subsidy_type
 * @property float $farm_id
 * @property float $record_id
 * @property float $crop_yield_after
 * @property float $income_after
 * @property string $calamity
 * @property string $effectiveness_label
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Evaluation extends Entity
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
        'subsidy_type' => true,
        'farm_id' => true,
        'record_id' => true,
        'crop_yield_after' => true,
        'income_after' => true,
        'pest_id' => true,
        'calamity' => true,
        'effectiveness_label' => true,
        'farmer_id' => true,
        'feedback_id' => true,
        'created' => true,
        'modified' => true,
    ];
}
