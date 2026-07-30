<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Distributions Model
 *
 * @property \App\Model\Table\FarmersTable&\Cake\ORM\Association\HasMany $Farmers
 *
 * @method \App\Model\Entity\Distribution newEmptyEntity()
 * @method \App\Model\Entity\Distribution newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Distribution[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Distribution get($primaryKey, $options = [])
 * @method \App\Model\Entity\Distribution findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Distribution patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Distribution[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Distribution|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Distribution saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Distribution[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Distribution[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Distribution[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Distribution[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DistributionsTable extends Table
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

        $this->setTable('distributions');
        $this->setDisplayField('subsidy_item');
        $this->setPrimaryKey('id');

        $this->belongsTo('Farmers', [
        'foreignKey' => 'farmer_id',
        'joinType' => 'INNER',
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
            ->scalar('subsidy_item')
            ->maxLength('subsidy_item', 255)
            ->requirePresence('subsidy_item', 'create')
            ->notEmptyString('subsidy_item');

        $validator
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity');

        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');
        $validator
            ->dateTime('distribution_date')
            ->requirePresence('distribution_date', 'create')
            ->notEmptyDateTime('distribution_date');

        $validator
            ->dateTime('received_date')
            ->requirePresence('received_date', 'create')
            ->notEmptyDateTime('received_date');

        $validator
            ->integer('farmer_id')
            ->notEmptyString('farmer_id');

        return $validator;
    }
}
