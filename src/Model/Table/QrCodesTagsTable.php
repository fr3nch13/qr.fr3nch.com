<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QrCodesTags Model
 *
 * @property \App\Model\Table\QrCodesTable $QrCodes
 * @property \App\Model\Table\TagsTable $Tags
 * @extends \Cake\ORM\Table<array{}, \App\Model\Entity\QrCodesTag>
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

        $this->belongsTo('Tags')
            ->setClassName('Tags')
            ->setForeignKey('tag_id');

        $this->belongsTo('QrCodes')
            ->setClassName('QrCodes')
            ->setForeignKey('qr_code_id');
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
