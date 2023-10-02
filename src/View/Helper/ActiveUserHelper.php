<?php
declare(strict_types=1);

/**
 * Exposes the logged in user to the templates
 */
namespace App\View\Helper;

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
     * @var array<int, string>
     */
    protected array $helpers = ['Identity'];

    /**
     * Gets the User element from the response/view
     *
     * @param string|null $key Key of something you want to get from the user
     * @return mixed
     */
    public function getUser(?string $key = null): mixed
    {
        return $this->Identity->get($key);
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