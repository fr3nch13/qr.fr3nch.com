<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\QrCode;
use App\Model\Entity\QrImage;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\ServerRequest;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Laminas\Diactoros\Exception\ExceptionInterface;
use const UPLOAD_ERR_FORM_SIZE;
use const UPLOAD_ERR_INI_SIZE;
use const UPLOAD_ERR_NO_FILE;

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
            ->notEmptyString('name');

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
     * Make sure it's image and thumbnails are deleted.
     *
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\QrImage $qrImage
     * @param \ArrayObject $options
     * @return void
     */
    public function afterDelete(Event $event, QrImage $qrImage, ArrayObject $options): void
    {
        // delete the images.
        $qrImage->deleteThumbs(true);
    }

    /**
     * Handles the multiple files from the muliple file upload on the add form.
     *
     * @param \App\Model\Entity\QrImage $qrImage The new entity created in the controller action.
     * @param \Cake\Http\ServerRequest $serverRequest The Submitted server request.
     * @return \App\Model\Entity\QrImage If there were errors, the entity will be returned with them.
     */
    public function handleNewImages(QrImage $qrImage, ServerRequest $serverRequest): QrImage
    {
        $basePath = Configure::read('App.paths.qr_images');
        if (!$basePath) {
            $qrImage->setError('newimages', __('Unable to save the image, unknown path'));

            return $qrImage;
        }

        if (!$qrImage->qr_code) {
            $qrImage->setError('newimages', __('Unable to save the image, unknown code'));

            return $qrImage;
        }

        $images = $serverRequest->getUploadedFiles();

        // none were sent.
        if (
            !isset($images['newimages']) ||
            !is_array($images['newimages']) ||
            !count($images['newimages'])
        ) {
            $qrImage->setError('newimages', __('No images were uploaded'));

            return $qrImage;
        }

        // the save button was hit, and the images interface loaded, but the user didn't upload an image.
        if (count($images['newimages']) === 1) {
            $image = reset($images['newimages']);
            if ($image->getError() === UPLOAD_ERR_NO_FILE) {
                $qrImage->setError('newimages', __('No images were uploaded'));

                return $qrImage;
            }
        }

        // find out the image count so we can set them in order
        $imgCount = $this->find('qrCode', QrCode: $qrImage->qr_code)->count();

        // now process each of the images before saving them.
        foreach ($images['newimages'] as $image) {
            /** @var \Laminas\Diactoros\UploadedFile $image */
            $error = $image->getError();

            if ($error) {
                if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
                    $qrImage->setError('newimages', __('The file `{0}` is too big.', [
                        $image->getClientFilename(),
                        $error,
                    ]));
                } else {
                    $qrImage->setError('newimages', __('There was an issue with the file: {0} - {1}', [
                        $image->getClientFilename(),
                        $error,
                    ]));
                }

                continue;
            }

            // make sure it's an image.
            if (
                !in_array($image->getClientMediaType(), [
                'image/png',
                'image/jpg',
                'image/jpeg',
                'image/gif',
                'image/svg+xml',
                'image/heic', // used by apple
                'image/heif',
                ])
            ) {
                $qrImage->setError('newimages', __('The file type is invalid: {0}', [
                    $image->getClientFilename(),
                ]));

                continue;
            }

            // Create the entity for this image.
            $newImage = clone $qrImage;

            // file info
            /** @var string $file_name The checks above should cover it enough to have this be a string. */
            $file_name = $image->getClientFilename();

            $filename = pathinfo($file_name, PATHINFO_FILENAME);
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            // nice file name
            $newImage->name = Inflector::humanize($filename);
            $newImage->ext = $ext;

            // make it active.
            $newImage->is_active = true;

            // image order
            $imgCount++;
            $newImage->imorder = $imgCount;

            if ($newImage->hasErrors()) {
                foreach ($newImage->getErrors() as $error) {
                    foreach ($error as $msg) {
                        $qrImage->setError('newimages', __('Error: {0} - {1}', [
                            $file_name,
                            $msg,
                        ]));

                        continue;
                    }
                }
            }

            $newImage = $this->saveOrFail($newImage);
            if ($newImage) {
                $home = $newImage->getImagePath();
                $dir = dirname($home);
                if (!is_dir($dir)) {
                    mkdir($dir);
                }

                try {
                    $image->moveTo($home);
                } catch (ExceptionInterface $exception) {
                    // delete the image from the database.
                    $this->delete($newImage);

                    $emsg = null;
                    if (Configure::read('debug')) {
                        $emsg = ' - ' . $exception->getMessage();
                    }

                    // report an error to the web.
                    $qrImage->setError('newimages', __('Error: {0}', [
                        $file_name,
                        $emsg,
                    ]));

                    continue;
                }
            }
        }

        return $qrImage;
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
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['QrImages.is_active' => true]);
    }

    /**
     * Finds the Qr Image with the imorder of 0
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @return \Cake\ORM\Query\SelectQuery The updated query
     */
    public function findOrderFirst(SelectQuery $query): SelectQuery
    {
        return $query->order(['QrImages.imorder' => 'asc']);
    }

    /**
     * Find Images owned by a Qr Code
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @param \App\Model\Entity\QrCode $QrCode The QrCode to find for.
     * @return \Cake\ORM\Query\SelectQuery $query The updated query
     */
    public function findQrCode(SelectQuery $query, QrCode $QrCode): SelectQuery
    {
        return $query->where(['QrImages.qr_code_id' => $QrCode->id]);
    }
}
