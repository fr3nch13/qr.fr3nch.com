<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CategoriesQrCodes Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $Categories
 * @property \App\Model\Table\QrCodesTable&\Cake\ORM\Association\BelongsTo $QrCodes
 * @method \App\Model\Entity\CategoriesQrCode newEmptyEntity()
 * @method \App\Model\Entity\CategoriesQrCode newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQrCode[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQrCode get(int $primaryKey, $contain = [])
 * @method \App\Model\Entity\CategoriesQrCode findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CategoriesQrCode patchEntity(\App\Model\Entity\CategoriesQrCode  $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQrCode[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CategoriesQrCode|false save(\App\Model\Entity\CategoriesQrCode $entity, $options = [])
 * @method \App\Model\Entity\CategoriesQrCode saveOrFail(\App\Model\Entity\CategoriesQrCode $entity, $options = [])
 * @method \App\Model\Entity\CategoriesQrCode[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CategoriesQrCode[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CategoriesQrCode[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CategoriesQrCode[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CategoriesQrCodesTable extends Table
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

        $this->setTable('categories_qr_codes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Categories')
            ->setClassName('Categories')
            ->setForeignKey('category_id')
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
            ->integer('category_id')
            ->notEmptyString('category_id')
            ->requirePresence('category_id', Validator::WHEN_CREATE);

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
        $rules->add($rules->isUnique(['category_id', 'qr_code_id']), [
            'errorField' => 'category',
            'message' => __('This QR Code has already been added to this Category'),
        ]);
        $rules->add($rules->existsIn('category_id', 'Categories'), [
            'errorField' => 'category_id',
            'message' => __('Unknown Category'),
        ]);
        $rules->add($rules->existsIn('qr_code_id', 'QrCodes'), [
            'errorField' => 'qr_code_id',
            'message' => __('Unknown QR Code'),
        ]);

        return $rules;
    }
}
