<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\QrCode;
use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;
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

        $validator
            ->boolean('is_active');

        $validator
            ->integer('imorder')
            ->notEmptyString('imorder');

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
        $rules->add($rules->existsIn('qr_code_id', 'QrCodes'), [
            'errorField' => 'qr_code_id',
            'message' => __('Unknown QR Code'),
        ]);

        return $rules;
    }

    /**
     * Gets the Path to the Image file.
     *
     * @param int $id The id of the QR Image Entity
     * @return string The absolute path to the QR Image.
     * @throws \Cake\Http\Exception\NotFoundException If the entity isn't found, or we can't find the image file.
     */
    public function getFilePath(int $id): string
    {
        $qrImage = $this->get($id, contain:['QrCodes']); // throws a NotFoundException if it doesn't exist.
        $path = TMP . 'qr_images' . DS . $qrImage->qr_code_id . DS . $id;
        if (!file_exists($path) || !is_readable($path)) {
            throw new \Cake\Http\Exception\NotFoundException(__('Unable to find the Image {0} for QR Code {1}', [
                $qrImage->name,
                $qrImage->qr_code->name,
            ]));
        }

        return $path;
    }

    /**
     * Custom finders
     */

    /**
     * Find Active QR Images
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @return \Cake\ORM\Query\SelectQuery The updated query
     */
    public function findActive(SelectQuery $query)
    {
        return $query->where(['QrImages.is_active' => true]);
    }

    /**
     * Finds the Qr Image with the imorder of 0
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @return \Cake\ORM\Query\SelectQuery The updated query
     */
    public function findOrderFirst(SelectQuery $query)
    {
        return $query->order(['QrImages.imorder' => 'asc']);
    }

    /**
     * Find Images owned by a Qr Code
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @param \App\Model\Entity\QrCode $qrCode The QrCode to find for.
     * @return \Cake\ORM\Query\SelectQuery $query The updated query
     */
    public function findQrCode(SelectQuery $query, QrCode $QrCode)
    {
        return $query->where(['QrImages.qr_code_id' => $QrCode->id]);
    }

}
