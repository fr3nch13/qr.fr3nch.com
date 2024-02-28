<?php
declare(strict_types=1);

/**
 * Exposes the logged in user to the templates
 */
namespace App\View\Helper;

use App\Model\Entity\User;
use Cake\View\Helper;

/**
 * ActiveUserHelper helper library.
 *
 * @property \Authentication\View\Helper\IdentityHelper $Identity
 */
class ActiveUserHelper extends Helper
{
    /**
     * helpers
     *
     * @var array<int|string, string|array<string, string>>
     */
    protected array $helpers = [
        'Identity' => ['className' => 'Authentication.Identity'],
    ];

    /**
     * Gets the User element from the response/view
     *
     * @param ?string $key Key of something you want to get from the user
     * @return \App\Model\Entity\User|mixed Either the User, or the value of the key for the user.
     */
    public function getUser(?string $key = null): mixed
    {
        return $this->Identity->get($key);
    }

    /**
     * If the user logged in is this user
     *
     * @param \App\Model\Entity\User $user
     * @return bool
     */
    public function isMe(User $user): bool
    {
        return $user->id === $this->Identity->get('id');
    }

    /**
     * If the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->Identity->get('is_admin');
    }

    /**
     * If the user is logged in or not
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->Identity->isLoggedIn();
    }
}
