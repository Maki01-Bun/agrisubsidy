<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Evaluations Model
 *
 * @property \App\Model\Table\FarmersTable&\Cake\ORM\Association\HasMany $Farmers
 *
 * @method \App\Model\Entity\Evaluation newEmptyEntity()
 * @method \App\Model\Entity\Evaluation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Evaluation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Evaluation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EvaluationsTable extends Table
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

        $this->setTable('evaluations');
        $this->setDisplayField('farm_size');
        $this->setPrimaryKey('id');

        $this->belongsTo('Farmers', [
            'foreignKey' => 'farmer_id',
        ]);
        $this->belongsTo('Feedbacks', [
            'foreignKey' => 'feedback_id',
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
            ->scalar('subsidy_type')
            ->maxLength('subsidy_type', 255)
            ->requirePresence('subsidy_type', 'create')
            ->notEmptyString('subsidy_type');

        $validator
            ->decimal('farm_size')
            ->requirePresence('farm_size', 'create')
            ->notEmptyString('farm_size');
    
        $validator
            ->decimal('crop_yield_before')
            ->requirePresence('crop_yield_before', 'create')
            ->notEmptyString('crop_yield_before');
    
        $validator
            ->decimal('crop_yield_after')
            ->requirePresence('crop_yield_after', 'create')
            ->notEmptyString('crop_yield_after');
    
        $validator
            ->decimal('income_before')
            ->requirePresence('income_before', 'create')
            ->notEmptyString('income_before');
    
        $validator
            ->decimal('income_after')
            ->requirePresence('income_after', 'create')
            ->notEmptyString('income_after');
    
        $validator
            ->scalar('pest')
            ->maxLength('pest', 100)
            ->requirePresence('pest', 'create')
            ->notEmptyString('pest');
    
        $validator
            ->scalar('calamity')
            ->maxLength('calamity', 100)
            ->requirePresence('calamity', 'create')
            ->notEmptyString('calamity');
    
        $validator
            ->scalar('effectiveness_label')
            ->maxLength('effectiveness_label', 100)
            ->requirePresence('effectiveness_label', 'create')
            ->notEmptyString('effectiveness_label');
    
        return $validator;
    }
}
