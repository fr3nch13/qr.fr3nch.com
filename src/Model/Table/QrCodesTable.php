<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\QrCode;
use App\Model\Entity\User;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use FilesystemIterator;
use GlobIterator;
use Search\Model\Filter\Base;

/**
 * QrCodes Model
 *
 * @property \App\Model\Table\QrImagesTable&\Cake\ORM\Association\HasMany $QrImages
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
 * @mixin \Search\Model\Behavior\SearchBehavior
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

        $this->belongsTo('Users')
            ->setClassName('Users')
            ->setForeignKey('user_id');

        $this->belongsTo('Sources')
            ->setClassName('Sources')
            ->setForeignKey('source_id');

        $this->hasMany('QrImages')
            ->setClassName('QrImages')
            ->setForeignKey('qr_code_id');

        $this->belongsToMany('Tags')
            ->setClassName('Tags')
            ->setForeignKey('qr_code_id')
            ->setTargetForeignKey('tag_id')
            ->setThrough('QrCodesTags');

        $this->addBehavior('Timestamp');

        // Friendsofcake/search
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            // add filtering by just the qrcode
            ->add('q', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => ['qrkey', 'name', 'url', 'description'],
            ])
            // add filtering by source name
            ->add('s', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => ['Sources.name'],
            ])
            // add filtering by tag name
            ->callback('t', [
                'callback' => function (SelectQuery $query, array $args, Base $filter) {
                    $query
                        ->innerJoinWith('Tags', function (SelectQuery $query) use ($args) {
                            return $query->where(['Tags.name LIKE' => $args['t']]);
                        })
                        ->group('QrCodes.id');

                    return true;
                },
            ]);
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
            ->scalar('qrkey')
            ->maxLength('qrkey', 255)
            ->notEmptyString('qrkey')
            ->requirePresence('qrkey', Validator::WHEN_CREATE)
            ->add('qrkey', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => __('This Key already exists.'),
            ])
            ->add('qrkey', 'qrkey', [
                'rule' => 'qrKey',
                'provider' => 'qr',
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
            ->add('url', 'qrurl', [
                'rule' => 'qrUrl',
                'provider' => 'qr',
                'message' => __('The URL is invalid.'),
            ]);

        $validator
            ->boolean('is_active');

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
        $rules->add($rules->existsIn('source_id', 'Sources'), [
            'errorField' => 'source_id',
            'message' => __('Unknown Source'),
        ]);

        $rules->add($rules->existsIn('user_id', 'Users'), [
            'errorField' => 'user_id',
            'message' => __('Unknown User'),
        ]);

        // ensures the 'qrkey' field can't be updated through the entity.
        $rules->addUpdate(function ($entity, $options) {
            return !$entity->isDirty('qrkey');
        }, 'update', [
            'errorField' => 'qrkey',
            'message' => __('QR Key can not be updated.'),
        ]);

        return $rules;
    }

    /**
     * Before marshal which runs before patching an entity.
     *
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        // see of there are new tags to add.
        if (isset($data['tags']['_ids']) && is_array($data['tags']['_ids'])) {
            $user_id = 0;
            // make the user of the QrCode the user of the new tags.
            // existing code.
            if (isset($data['id'])) {
                $qrCode = $this->get((int)$data['id']);
                if ($qrCode) {
                    $user_id = $qrCode->user_id;
                }
            }
            // new code
            if (!$user_id && isset($data['user_id'])) {
                $user_id = $data['user_id'];
            }

            foreach ($data['tags']['_ids'] as $pos => $value) {
                // maybe have a new one, at least it was typed.
                if (!is_numeric($value)) {
                    $tag = $this->Tags->find()->where([
                        'Tags.name' => $value,
                    ])->first();
                    if ($tag) {
                        // fix the ArrayObject
                        $data['tags']['_ids'][$pos] = $tag->id;
                    } else {
                        $tag = $this->Tags->newEntity([
                            'name' => $value,
                            'user_id' => $user_id,
                        ]);
                        if (!$tag->hasErrors()) {
                            $tag = $this->Tags->saveOrFail($tag);
                            $data['tags']['_ids'][$pos] = $tag->id;
                        }
                    }
                }
            }
        }
    }

    /**
     * AfterSave callback
     *
     * @return void
     */
    public function afterSave(Event $event, QrCode $entity, ArrayObject $options): void
    {
        // This should trigger creating a QR Code if it doesn't exist,
        // as the Entity's virtual field will try to generate one.
        // so we just need to trigger those virtual fields.
        if ($entity->color && !$entity->path) {
            throw new InternalErrorException('Unable to create Color QR Code.');
        }
        if (!$entity->path_dark || !$entity->path_light) {
            throw new InternalErrorException('Unable to create Default QR Codes.');
        }
    }

    /**
     * Make sure it's Qr Images are deleted first.
     *
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\QrCode $qrCode
     * @param \ArrayObject $options
     * @return void
     */
    public function beforeDelete(Event $event, QrCode $qrCode, ArrayObject $options): void
    {
        // delete the images.
        // I know the foreign key constraints will delete the QR Images from the database automatically,
        // But I want to have CakePHP delete them first.
        // so that the image files and thembnails are deleted as well.
        $qrImages = $this->QrImages->find('qrCode', QrCode: $qrCode);

        foreach ($qrImages as $qrImage) {
            // this should trigger the afterDelete in QrImagesTable
            $this->QrImages->delete($qrImage);
        }

        // the directory that holds the images should be empty, delete it.
        $imgPath = Configure::read('App.paths.qr_images', TMP . 'qr_images') . DS . $qrCode->id;
        if (is_dir($imgPath)) {
            rmdir($imgPath);
        }
    }

    /**
     * Make sure it's image and thumbnails are deleted.
     *
     * @param \Cake\Event\Event $event
     * @param \App\Model\Entity\QrCode $qrCode
     * @param \ArrayObject $options
     * @return void
     */
    public function afterDelete(Event $event, QrCode $qrCode, ArrayObject $options): void
    {
        $path = Configure::read('App.paths.qr_codes') . DS;
        $finder = $path . $qrCode->id . '-*';
        $iterator = new GlobIterator($finder, FilesystemIterator::KEY_AS_FILENAME);
        if ($iterator->count()) {
            /** @var \SplFileInfo $item */
            foreach ($iterator as $item) {
                if (is_file($item->getRealPath())) {
                    unlink($item->getRealPath());
                }
            }
        }
    }

    /**
     * Custom finders
     */

    /**
     * Find Active QR Codes
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @return \Cake\ORM\Query\SelectQuery The updated query
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['QrCodes.is_active' => true]);
    }

    /**
     * Find a QR code by its key
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @param string $key The key to look for.
     * @return \Cake\ORM\Query\SelectQuery The updated query
     */
    public function findKey(SelectQuery $query, string $key): SelectQuery
    {
        return $query->where(['QrCodes.qrkey' => $key]);
    }

    /**
     * Find Qr Codes owned by a user
     *
     * @param \Cake\ORM\Query\SelectQuery $query The initial query
     * @param \App\Model\Entity\User $user The user to scope the query to.
     * @return \Cake\ORM\Query\SelectQuery The updated query
     */
    public function findOwnedBy(SelectQuery $query, User $user): SelectQuery
    {
        return $query->where(['QrCodes.user_id' => $user->id]);
    }
}
