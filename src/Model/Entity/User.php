<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\IdentityInterface as AuthenticationIdentity;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Authorization\AuthorizationServiceInterface;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Authorization\Policy\ResultInterface;
use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property bool $is_admin
 * @property bool $is_active
 *
 * @property string|null $path (Virtual field) Path to the generated QR Code file.
 *
 * @property \Authorization\AuthorizationServiceInterface $authorization
 */
class User extends Entity implements AuthorizationIdentity, AuthenticationIdentity
{
    use ThumbTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'email' => true,
        'password' => true,
        'created' => true,
        'modified' => true,
        'is_admin' => true,
        'is_active' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * Hashes the password
     *
     * @param string $password The password to hash.
     * @return string|null The hashed password.
     * @link https://book.cakephp.org/5/en/tutorials-and-examples/cms/authentication.html
     */
    protected function _setPassword(string $password): ?string
    {
        // when creating a new entity, the rules in the table require this
        // to be at least 8 characters.
        // if this Entity is being created directly, it should throw a runtime error
        // when the password is anything but a sting.
        return (new DefaultPasswordHasher())->hash($password);
    }

    /**
     * Gets the path to the QR Image's file.
     *
     * @return string|null The path to the file.
     */
    protected function _getPath(): ?string
    {
        $path = $this->getImagePath();

        if (file_exists($path) && is_readable($path)) {
            return $path;
        }

        return null;
    }

    /**
     * Return where the path to the image should be.
     * No checking here
     *
     * @return string The path.
     */
    public function getImagePath(): ?string
    {
        return Configure::read('App.paths.users', TMP . 'users') . DS . $this->id . '.jpg';
    }

    /**
     * Check if $identity is the User
     *
     * @param mixed $resource The resource being operated on.
     * @return bool
     */
    public function isMe(mixed $resource): bool
    {
        return $this->id === $resource->user_id;
    }

    /**
     * Check if I am an Admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin ? true : false;
    }

    /**
     * Authorization\IdentityInterface method
     *
     * @param string $action The action/operation being performed.
     * @param mixed $resource The resource being operated on.
     * @return bool
     */
    public function can(string $action, mixed $resource): bool
    {
        return $this->authorization->can($this, $action, $resource);
    }

    /**
     * Authorization\IdentityInterface method
     *
     * @param string $action The action/operation being performed.
     * @param mixed $resource The resource being operated on.
     * @return \Authorization\Policy\ResultInterface
     */
    public function canResult(string $action, mixed $resource): ResultInterface
    {
        return $this->authorization->canResult($this, $action, $resource);
    }

    /**
     * Authorization\IdentityInterface method
     *
     * @param string $action The action/operation being performed.
     * @param mixed $resource The resource being operated on.
     * @param mixed $optionalArgs Multiple additional arguments which are passed to the scope
     * @return mixed The modified resource.
     */
    public function applyScope(string $action, mixed $resource, mixed ...$optionalArgs): mixed
    {
        return $this->authorization->applyScope($this, $action, $resource);
    }

    /**
     * Authorization\IdentityInterface method
     *
     * If the decorated identity implements `getOriginalData()`
     * that method should be invoked to expose the original data.
     *
     * @return self
     */
    public function getOriginalData(): self
    {
        return $this;
    }

    /**
     * Get the primary key/id field for the identity.
     *
     * @return int
     */
    public function getIdentifier(): int
    {
        return $this->id;
    }

    /**
     * Setter to be used by the middleware.
     *
     * @param \Authorization\AuthorizationServiceInterface $service The service to attach this eneity to as the identifier.
     * @return self
     */
    public function setAuthorization(AuthorizationServiceInterface $service): self
    {
        $this->authorization = $service;

        return $this;
    }
}
