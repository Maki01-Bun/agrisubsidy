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
        $this->setDisplayField('description');
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
        /*
         * user_id
         */
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        /*
         * action
         */
        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->notEmptyString('action');

        /*
         * subject_type
         *
         * Nullable in database.
         */
        $validator
            ->scalar('subject_type')
            ->maxLength('subject_type', 255)
            ->allowEmptyString('subject_type');

        /*
         * subject_id
         *
         * Nullable in database.
         */
        $validator
            ->integer('subject_id')
            ->allowEmptyString('subject_id');

        /*
         * description
         */
        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        /*
         * properties
         *
         * longtext and nullable.
         */
        $validator
            ->scalar('properties')
            ->allowEmptyString('properties');

        /*
         * ip_address
         */
        $validator
            ->scalar('ip_address')
            ->maxLength('ip_address', 45)
            ->allowEmptyString('ip_address');

        /*
         * user_agent
         */
        $validator
            ->scalar('user_agent')
            ->allowEmptyString('user_agent');

        /*
         * created and updated are normally handled
         * automatically by CakePHP TimestampBehavior.
         */

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
