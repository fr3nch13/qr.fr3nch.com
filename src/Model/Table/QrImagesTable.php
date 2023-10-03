<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * QrImages Model
 *
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\BelongsTo $QrCodes
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsTo $Tags
 * @method \App\Model\Entity\QrImage newEmptyEntity()
 * @method \App\Model\Entity\QrImage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\QrImage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QrImage get(int $primaryKey, $contain = [])
 * @method \App\Model\Entity\QrImage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\QrImage patchEntity(\App\Model\Entity\QrImage  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QrImage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\QrImage|false save(\App\Model\Entity\QrImage $entity, $options = [])
 * @method \App\Model\Entity\QrImage saveOrFail(\App\Model\Entity\QrImage $entity, $options = [])
 * @method \App\Model\Entity\QrImage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrImage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrImage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\QrImage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class QrImagesTable extends Table
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

        $this->setTable('qr_images');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

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
            ->scalar('name')
            ->maxLength('name', 255)
            ->notEmptyString('name')
            ->requirePresence('name', Validator::WHEN_CREATE);

        // TODO: Add file upload fields, and upload logic.

        $validator
            ->integer('qr_code_id')
            ->notEmptyString('qr_code_id')
            ->requirePresence('qr_code_id', Validator::WHEN_CREATE);

        $validator
            ->boolean('is_active');

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
        $rules->add($rules->existsIn('qr_code_id', 'QrCodes'), [
            'errorField' => 'qr_code_id',
            'message' => __('Unknown QR Code'),
        ]);

        return $rules;
    }
}
