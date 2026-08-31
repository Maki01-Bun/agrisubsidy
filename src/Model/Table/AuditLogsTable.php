<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuditLogs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\AuditLog newEmptyEntity()
 * @method \App\Model\Entity\AuditLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AuditLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuditLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\AuditLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AuditLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AuditLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuditLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuditLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AuditLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AuditLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AuditLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AuditLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AuditLogsTable extends Table
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

        $this->setTable('audit_logs');
        $this->setDisplayField('action');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('Timestamp');
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->notEmptyString('action');

        $validator
            ->dateTime('action_date')
            ->requirePresence('action_date', 'create')
            ->notEmptyDateTime('action_date');

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
