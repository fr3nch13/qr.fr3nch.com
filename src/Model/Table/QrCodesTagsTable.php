<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QrCodesTags Model
 *
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsTo $Tags
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\BelongsTo $QrCodes
 * @method \App\Model\Entity\QrCodesTag newEmptyEntity()
 * @method \App\Model\Entity\QrCodesTag newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\QrCodesTag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QrCodesTag get($primaryKey, $options = [])
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
     * @param array $config The configuration for the Table.
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
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('tag_id')
            ->notEmptyString('tag_id');

        $validator
            ->integer('qr_code_id')
            ->notEmptyString('qr_code_id');

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
        $rules->add($rules->existsIn('tag_id', 'Tags'), ['errorField' => 'tag_id']);
        $rules->add($rules->existsIn('qr_code_id', 'QrCodes'), ['errorField' => 'qr_code_id']);

        return $rules;
    }
}
