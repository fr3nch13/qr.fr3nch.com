<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\QrCode;
use App\Model\Entity\User;
use ArrayObject;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Search\Model\Filter\Base;

/**
 * QrCodes Model
 *
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

        $this->addBehavior('Timestamp');

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
                'fields' => ['name', 'description'],
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
            ->add('qrkey', 'characters', [
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
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        // see of there are new tags to add.
        if (isset($data['tags']['_ids'])) {
            debug($data['tags']['_ids']);
            foreach ($data['tags']['_ids'] as $pos => $value) {
                if (!is_numeric($value)) {
                    $tag = $this->Tags->find()->where([
                        'Tags.name' => $value,
                    ])->first();
                    debug($tag);
                }
            }
        }
        debug($options);
    }

    /**
     * AfterSave callback
     *
     * @return void
     */
    public function afterSave(Event $event, QrCode $entity, ArrayObject $options): void
    {
        // This should trigger creating a QR Code if it doesn't exist,
        // as the Entity's firtual field will try to generate one.
        // so we just need to trigger that firtual field.
        // TODO: Test this to make sure we output the exception properly.
        if (!$entity->path) {
            throw new InternalErrorException('Unable to create QR Code.');
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

    /**
     * Gets the Path to the Generated QR Code Image.
     * If it's not created, try to create it.
     *
     * @param int $id The id of the QR Code Entity
     * @return string The absolute path to the generated QR code Image.
     * @throws \Cake\Http\Exception\NotFoundException If the entity isn't found, or we can't create the image.
     */
    public function getQrImagePath(int $id, bool $regenerate = false): string
    {
        $qrCode = $this->get($id); // throws a NotFoundException if it doesn't exist.

        if ($regenerate === true) {
            $qrCode->regenerate = true;
        }

        if (!$qrCode->path) {
            throw new NotFoundException(__('Unable to find the QR Image for the QR Code `{0}`', [
                $qrCode->name,
            ]));
        }

        return $qrCode->path;
    }
}
