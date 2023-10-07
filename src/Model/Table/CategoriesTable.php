<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Category;
use Cake\ORM\Rule\ExistsIn;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\HasMany $ChildCategories
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $ParentCategories
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\BelongsToMany $QrCodes
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Category newEmptyEntity()
 * @method \App\Model\Entity\Category newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Category get(int $primaryKey, $contain = [])
 * @method \App\Model\Entity\Category findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Category patchEntity(\App\Model\Entity\Category  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Category[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Category|false save(\App\Model\Entity\Category $entity, $options = [])
 * @method \App\Model\Entity\Category saveOrFail(\App\Model\Entity\Category $entity, $options = [])
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesTable extends Table
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

        $this->setTable('categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users')
            ->setClassName('Users')
            ->setForeignKey('user_id');

        $this->belongsTo('ParentCategories')
            ->setClassName('Categories')
            ->setForeignKey('parent_id');

        $this->hasMany('ChildCategories')
            ->setClassName('Categories')
            ->setForeignKey('parent_id');

        $this->belongsToMany('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('category_id')
            ->setTargetForeignKey('qr_code_id')
            ->setThrough('CategoriesQrCodes');

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
                'fields' => ['name', 'description'],
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
            ->notEmptyString('name')
            ->add('name', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => __('This Name already exists.'),
            ])
            ->requirePresence('name', Validator::WHEN_CREATE);

        $validator
            ->scalar('description')
            ->notEmptyString('description')
            ->requirePresence('description', Validator::WHEN_CREATE);

        $validator
            ->integer('parent_id')
            ->allowEmptyString('parent_id')
            ->add('parent_id', '_existsIn', [
                'rule' => 'validateParent',
                'provider' => 'table',
                'message' => __('This Parent Category doesn\'t exist.'),
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
        $rules->add($rules->existsIn('parent_id', 'ParentCategories'), [
            'errorField' => 'parent_id',
            'message' => __('Unknown Parent Category'),
        ]);

        $rules->add($rules->existsIn('user_id', 'Users'), [
            'errorField' => 'user_id',
            'message' => __('Unknown User'),
        ]);

        return $rules;
    }

    /**
     * Used to check that the parent_id does indeed exist in the table, before the buildRules.
     * This is because, if the validationDefault fails, build Rules won't trigger.
     * In this case, if the parent_id doesn't exist, and the name does, only the error for the name will show.
     * With this, the parent_id error will also show.
     * Blatently solen from:
     *
     * @param mixed $value The value of parent_id to check if the category exists.
     * @param array<string, mixed> $options The options array.
     *   May also be the validation context, if there are no options.
     * @param array<string, mixed>|null $context Either the validation context or null.
     * @return bool True if the category exists, or false if not.
     * @link https://github.com/cakephp/cakephp/blob/5.x/src/ORM/Table.php#L3119
     */
    public function validateParent(mixed $value, array $options, ?array $context = null): bool
    {
        if ($context === null) {
            $context = $options;
        }
        $entity = new Category(
            $context['data'],
            [
                'useSetters' => false,
                'markNew' => $context['newRecord'],
                'source' => $this->getRegistryAlias(),
            ]
        );
        $fields = array_merge(
            [$context['field']],
            isset($options['scope']) ? (array)$options['scope'] : []
        );
        $values = $entity->extract($fields);
        foreach ($values as $field) {
            if ($field !== null && !is_scalar($field)) {
                return false;
            }
        }
        $rule = new ExistsIn($fields, 'ParentCategories', [
            'errorField' => 'parent_id',
            'message' => __('Unknown Parent Category'),
        ]);

        return $rule($entity, ['repository' => $this]);
    }
}
