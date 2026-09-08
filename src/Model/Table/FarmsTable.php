<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Farms Model
 *
 * @property \App\Model\Table\FarmersTable&\Cake\ORM\Association\HasMany $Farmers
 *
 * @method \App\Model\Entity\Farm newEmptyEntity()
 * @method \App\Model\Entity\Farm newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Farm[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Farm get($primaryKey, $options = [])
 * @method \App\Model\Entity\Farm findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Farm patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Farm[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Farm|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Farm saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Farm[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Farm[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Farm[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Farm[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FarmsTable extends Table
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

        $this->setTable('farms');
        $this->setDisplayField('farmer_name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Farmers', [
        'foreignKey' => 'farmer_id',
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
            ->scalar('farmer_name')
            ->maxLength('farmer_name', 255)
            ->requirePresence('farmer_name', 'create')
            ->notEmptyString('farmer_name');

        $validator
            ->decimal('farm_size', 10, 2)
            ->requirePresence('farm_size', 'create')
            ->notEmptyString('farm_size');

        $validator
            ->scalar('location')
            ->requirePresence('location', 'create')
            ->notEmptyString('location');

        $validator
            ->decimal('average_yield')
            ->allowEmptyString('average_yield');

        $validator
            ->dateTime('created')
            ->requirePresence('created', 'create')
            ->notEmptyDateTime('created');
            $validator
            ->dateTime('modified')
            ->requirePresence('modified', 'create')
            ->notEmptyDateTime('modified');

        $validator
            ->integer('farmer_id')
            ->notEmptyString('farmer_id');

        return $validator;
    }
}
