<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\QrCodesTable $QrCodes
 * @property \App\Model\Table\SourcesTable $Sources
 * @property \App\Model\Table\TagsTable $Tags
 * @extends \Cake\ORM\Table<array{
 *   Timestamp: \Cake\ORM\Behavior\TimestampBehavior
 * }, \App\Model\Entity\User>
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('user_id');

        $this->hasMany('Sources')
            ->setClassName('Sources')
            ->setForeignKey('user_id');

        $this->hasMany('Tags')
            ->setClassName('Tags')
            ->setForeignKey('user_id');

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
            ->requirePresence('name', Validator::WHEN_CREATE);

        $validator
            ->email('email')
            ->maxLength('email', 255)
            ->notEmptyString('email', __('The Email is required, and can not be empty.'))
            ->requirePresence('email', Validator::WHEN_CREATE)
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => __('This Email already exists.'),
            ]);

        $validator
            ->scalar('password')
            ->minLength('password', 8)
            ->maxLength('password', 255)
            ->notEmptyString(
                'password',
                __('The Password is required, and can not be empty.'),
                Validator::WHEN_CREATE,
            )
            ->allowEmptyString(
                'password',
                __('The Password is required, and can not be empty.'),
                Validator::WHEN_UPDATE,
            )
            ->requirePresence('password', Validator::WHEN_CREATE);

        $validator
            ->boolean('is_active');

        $validator
            ->boolean('is_admin');

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
        // commented out as this is already done by the validator above.
        //$rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    /**
     * Custom finders
     */

    /**
     * Find Active Users
     *
     * @param \Cake\ORM\Query\SelectQuery<\App\Model\Entity\User> $query The initial query
     * @return \Cake\ORM\Query\SelectQuery<\App\Model\Entity\User> The updated query
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Users.is_active' => true]);
    }
}
