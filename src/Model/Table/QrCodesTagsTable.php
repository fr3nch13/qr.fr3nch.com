<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QrCodesTags Model
 *
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\BelongsTo $QrCodes
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsTo $Tags
 * @method \App\Model\Entity\QrCodesTag newEmptyEntity()
 * @method \App\Model\Entity\QrCodesTag newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\QrCodesTag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QrCodesTag get($primaryKey, $contain = [])
 * @method \App\Model\Entity\QrCodesTag findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\QrCodesTag patchEntity(\App\Model\Entity\QrCodesTag  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QrCodesTag[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\QrCodesTag|false save(\App\Model\Entity\QrCodesTag $entity, $options = [])
 * @method \App\Model\Entity\QrCodesTag saveOrFail(\App\Model\Entity\QrCodesTag $entity, $options = [])
 * @method \App\Model\Entity\QrCodesTag[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrCodesTag[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrCodesTag[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrCodesTag[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class QrCodesTagsTable extends Table
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

        $this->setTable('qr_codes_tags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Tags')
            ->setClassName('Tags')
            ->setForeignKey('tag_id')
            ->setJoinType('INNER');

        $this->hasMany('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('qr_code_id')
            ->setJoinType('INNER');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     * @todo Check to make sure the combination of tag_id and qr_code_id are unique,
     *      So we're not tagging the same QR with the same Tag multiple times.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('tag_id')
            ->notEmptyString('tag_id')
            ->requirePresence('tag_id', Validator::WHEN_CREATE);

        $validator
            ->integer('qr_code_id')
            ->notEmptyString('qr_code_id')
            ->requirePresence('qr_code_id', Validator::WHEN_CREATE);

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
        $rules->add($rules->isUnique(['tag_id', 'qr_code_id']), [
            'errorField' => 'tags',
            'message' => __('This QR Code has already been tagged with this Tag'),
        ]);
        $rules->add($rules->existsIn('tag_id', 'Tags'), [
            'errorField' => 'tag_id',
            'message' => __('Unknown Tag'),
        ]);
        $rules->add($rules->existsIn('qr_code_id', 'QrCodes'), [
            'errorField' => 'qr_code_id',
            'message' => __('Unknown QR Code'),
        ]);

        return $rules;
    }
}
