<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sources Model
 *
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\HasMany $QrCodes
 * @method \App\Model\Entity\Source newEmptyEntity()
 * @method \App\Model\Entity\Source newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Source[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Source get($primaryKey, $options = [])
 * @method \App\Model\Entity\Source findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Source patchEntity(\App\Model\Entity\Source  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Source[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Source|false save(\App\Model\Entity\Source $entity, $options = [])
 * @method \App\Model\Entity\Source saveOrFail(\App\Model\Entity\Source $entity, $options = [])
 * @method \App\Model\Entity\Source[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Source[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Source[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Source[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SourcesTable extends Table
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

        $this->setTable('sources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users')
            ->setClassName('Users')
            ->setForeignKey('user_id');

        $this->hasMany('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('source_id');
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
            ->scalar('key')
            ->maxLength('key', 255)
            ->requirePresence('key', 'create')
            ->notEmptyString('key')
            ->add('key', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('qr_code_key_field')
            ->maxLength('qr_code_key_field', 255)
            ->requirePresence('qr_code_key_field', 'create')
            ->notEmptyString('qr_code_key_field');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

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
        $rules->add($rules->isUnique(['key']), ['errorField' => 'key']);

        return $rules;
    }
}
