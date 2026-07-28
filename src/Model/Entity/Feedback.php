<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Feedback Entity
 *
 * @property int $id
 * @property int|null $rating
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime|null $feedback_date
 *
 * @property \App\Model\Entity\User $user
 */
class Feedback extends Entity
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
        'rating' => true,
        'comment' => true,
        'feedback_date' => true,
        'user' => true,
    ];
}
