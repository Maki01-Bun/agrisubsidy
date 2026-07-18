<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Farmers Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FeedbacksTable&\Cake\ORM\Association\HasMany $Feedbacks
 * @property \App\Model\Table\DistributionsTable&\Cake\ORM\Association\HasMany $Distributions
 *
 * @method \App\Model\Entity\Farmer newEmptyEntity()
 * @method \App\Model\Entity\Farmer newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Farmer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Farmer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Farmer findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Farmer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Farmer[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Farmer|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Farmer saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Farmer[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Farmer[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Farmer[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Farmer[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FarmersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('farmers');
        $this->setDisplayField('farmer_no');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Feedbacks', [
            'foreignKey' => 'farmer_id',
        ]);
        $this->hasMany('Distributions', [
            'foreignKey' => 'farmer_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('farmer_no')
            ->maxLength('farmer_no', 20)
            ->requirePresence('farmer_no', 'create')
            ->notEmptyString('farmer_no');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 255)
            ->requirePresence('middle_name', 'create')
            ->notEmptyString('middle_name');

        $validator
            ->date('birthdate')
            ->requirePresence('birthdate', 'create')
            ->notEmptyDate('birthdate');

        $validator
            ->scalar('gender')
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

       $validator
            ->scalar('contact_no')
            ->maxLength('contact_no', 11)
            ->requirePresence('contact_no', 'create')
            ->notEmptyString('contact_no');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
