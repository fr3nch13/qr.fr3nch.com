<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QrCodes Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsToMany $Categories
 * @property \App\Model\Table\SourcesTable&\Cake\ORM\Association\BelongsTo $Sources
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\QrCode newEmptyEntity()
 * @method \App\Model\Entity\QrCode newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\QrCode[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QrCode get(int $primaryKey, $contain = [])
 * @method \App\Model\Entity\QrCode findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\QrCode patchEntity(\App\Model\Entity\QrCode  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QrCode[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\QrCode|false save(\App\Model\Entity\QrCode $entity, $options = [])
 * @method \App\Model\Entity\QrCode saveOrFail(\App\Model\Entity\QrCode $entity, $options = [])
 * @method \App\Model\Entity\QrCode[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrCode[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrCode[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrCode[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QrCodesTable extends Table
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

        $this->setTable('qr_codes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users')
            ->setClassName('Users')
            ->setForeignKey('user_id');

        $this->belongsTo('Sources')
            ->setClassName('Sources')
            ->setForeignKey('source_id');

        $this->belongsToMany('Categories')
            ->setClassName('Categories')
            ->setForeignKey('qr_code_id')
            ->setTargetForeignKey('category_id')
            ->setThrough('CategoriesQrCodes');

        $this->belongsToMany('Tags')
            ->setClassName('Tags')
            ->setForeignKey('qr_code_id')
            ->setTargetForeignKey('tag_id')
            ->setThrough('QrCodesTags');
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
            ->notEmptyString('key')
            ->requirePresence('key', Validator::WHEN_CREATE)
            ->add('key', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => __('This Key already exists.'),
            ])
            ->add('key', 'characters', [
                'rule' => 'characters',
                'provider' => 'key',
            ]);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->notEmptyString('name')
            ->requirePresence('name', Validator::WHEN_CREATE);

        $validator
            ->scalar('description')
            ->notEmptyString('description')
            ->requirePresence('description', Validator::WHEN_CREATE);

        $validator
            ->scalar('url')
            ->notEmptyString('url')
            ->requirePresence('url', Validator::WHEN_CREATE)
            ->add('url', 'url', [
                'rule' => 'url',
                'message' => __('The URL is invalid.'),
            ]);

        $validator
            ->scalar('bitly_id')
            ->maxLength('bitly_id', 255)
            ->notEmptyString('bitly_id')
            ->requirePresence('bitly_id', Validator::WHEN_CREATE);

        $validator
            ->integer('source_id')
            ->notEmptyString('source_id')
            ->requirePresence('source_id', Validator::WHEN_CREATE);

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
        //$rules->add($rules->isUnique(['key']), ['errorField' => 'key']);
        $rules->add($rules->existsIn('source_id', 'Sources'), [
            'errorField' => 'source_id',
            'message' => __('Unknown Source'),
        ]);

        $rules->add($rules->existsIn('user_id', 'Users'), [
            'errorField' => 'user_id',
            'message' => __('Unknown User'),
        ]);

        return $rules;
    }
}
