<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Farmer Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property \Cake\I18n\FrozenDate $birthdate
 * @property string $gender
 * @property string $address
 * @property int $contact_no
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Beneficiary[] $beneficiaries
 */
class Farmer extends Entity
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
        'first_name' => true,
        'last_name' => true,
        'middle_name' => true,
        'birthdate' => true,
        'gender' => true,
        'address' => true,
        'contact_no' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'beneficiaries' => true,
    ];
}
