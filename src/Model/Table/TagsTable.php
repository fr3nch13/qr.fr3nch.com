<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tags Model
 *
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\BelongsToMany $QrCodes
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Tag newEmptyEntity()
 * @method \App\Model\Entity\Tag newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Tag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tag get(int $primaryKey, $contain = [])
 * @method \App\Model\Entity\Tag findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Tag patchEntity(\App\Model\Entity\Tag  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tag|false save(\App\Model\Entity\Tag $entity, $options = [])
 * @method \App\Model\Entity\Tag saveOrFail(\App\Model\Entity\Tag $entity, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TagsTable extends Table
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

        $this->setTable('tags');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users')
            ->setClassName('Users')
            ->setForeignKey('user_id');

        $this->belongsToMany('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('tag_id')
            ->setTargetForeignKey('qr_code_id')
            ->setThrough('QrCodesTags');

        $this->addBehavior('Timestamp');

        // Friendsofcake/search
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            ->add('q', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => ['name'],
            ])
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
                'message' => __('This Tag already exists.'),
            ]);

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
        // commented out as this is already done by the validator above.
        //$rules->add($rules->isUnique(['name']), ['errorField' => 'name']);
        $rules->add($rules->existsIn('user_id', 'Users'), [
            'errorField' => 'user_id',
            'message' => __('Unknown User'),
        ]);

        return $rules;
    }
}
