<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Records Model
 *
 * @property \App\Model\Table\FarmersTable&\Cake\ORM\Association\BelongsTo $Farmers
 * @property \App\Model\Table\FarmersTable&\Cake\ORM\Association\BelongsTo $Schedules
 *
 *
 * @method \App\Model\Entity\Record newEmptyEntity()
 * @method \App\Model\Entity\Record newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Record[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Record get($primaryKey, $options = [])
 * @method \App\Model\Entity\Record findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Record patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Record[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Record|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Record saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class RecordsTable extends Table
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

        $this->setTable('records');
        $this->setPrimaryKey('id');

        $this->belongsTo('Farmers', [
            'foreignKey' => 'farmer_id',
            'bindingKey' => 'id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Schedules', [
            'foreignKey' => 'schedule_id',
            'bindingKey' => 'id',
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
            ->scalar('subsidy_item')
            ->maxLength('subsidy_item', 255)
            ->requirePresence('subsidy_item', 'create')
            ->notEmptyString('subsidy_item');

        $validator
            ->decimal('quantity')
            ->allowEmptyString('quantity');

        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');


        $validator
            ->dateTime('received_date')
            ->allowEmptyDateTime('received_date');
            
        $validator
            ->dateTime('confirmed_at')
            ->allowEmptyDateTime('confirmed_at');

        $validator
            ->integer('farmer_id')
            ->notEmptyString('farmer_id');

        $validator
            ->integer('schedule_id')
            ->notEmptyString('schedule_id');

        return $validator;
    }
}
