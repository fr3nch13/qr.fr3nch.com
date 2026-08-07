<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sources Model
 *
 * @property \App\Model\Table\QrCodesTable $QrCodes
 * @property \App\Model\Table\UsersTable $Users
 * @extends \Cake\ORM\Table<array{
 *   Timestamp: \Cake\ORM\Behavior\TimestampBehavior
 * }, \App\Model\Entity\Source>
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SourcesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users')
            ->setClassName('Users')
            ->setForeignKey('user_id');

        $this->hasMany('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('source_id');

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
            ->scalar('name')
            ->maxLength('name', 255)
            ->notEmptyString('name', __('The Name is required, and can not be empty.'))
            ->requirePresence('name', Validator::WHEN_CREATE)
            ->add('name', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => __('This Name already exists.'),
            ]);

        $validator
            ->scalar('description')
            ->notEmptyString('description', __('The Description is required, and can not be empty.'))
            ->requirePresence('description', Validator::WHEN_CREATE);

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id')
            ->requirePresence('user_id', Validator::WHEN_CREATE);

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
        $rules->add($rules->existsIn('user_id', 'Users'), [
            'errorField' => 'user_id',
            'message' => __('Unknown User'),
        ]);

        return $rules;
    }

    /**
     * Custom finders
     */

    /**
     * Find Active Sources
     *
     * Here should be need to impliment it later.
     *
     * @param \Cake\ORM\Query\SelectQuery<\App\Model\Entity\Source> $query The initial query
     * @return \Cake\ORM\Query\SelectQuery<\App\Model\Entity\Source> The updated query
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query;
    }
}
