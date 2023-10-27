<?php
declare(strict_types=1);

/**
 * Helps with the Html stuff
 */
namespace App\View\Helper;

use App\Model\Entity\User;
use BootstrapUI\View\Helper\HtmlHelper as BootstrapUiHtmlHelper;

/**
 * Html helper library.
 *
 * @property \App\View\Helper\ActiveUserHelper $ActiveUser
 * @property \App\View\Helper\GravatarHelper $Gravatar
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class HtmlHelper extends BootstrapUiHtmlHelper
{
    /**
     * helpers
     *
     * @var array<int, string>
     */
    protected array $helpers = [
        'ActiveUser',
        'Gravatar',
        'Url',
    ];

    /**
     * Creates the avatar html
     *
     * @param string $size The one of three sizes [sm, md, lg]
     * @param \App\Model\Entity\User|null $user The user to make the avatar for, if none, the logged in user is used.
     * @param string $wrapperClasses Classes you want to add to the div wrapper
     * @param string $iconClasses Classes you want to add to the icon
     */
    public function avatar(
        string $size = 'md',
        ?User $user = null,
        string $wrapperClasses = '',
        string $iconClasses = ''
    ): string {
        $wrapper = '<span class="avatar avatar-{0} {1}">{2}</span>';
        $wrapperClass = '';

        if (!$user) {
            if (!$this->ActiveUser->isLoggedIn()) {
                return $this->icon('person');
            }
            $user = $this->ActiveUser->getUser();
        }

        return __($wrapper, [
            $size,
            $wrapperClass . ' ' . $wrapperClasses,
            $this->Gravatar->avatar($user),
        ]);
    }
}
