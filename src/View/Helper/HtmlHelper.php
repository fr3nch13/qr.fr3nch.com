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
        $wrapper = '<div class="avatar-{0} d-flex align-items-center justify-content-center {1}">{2}</div>';
        $wrapperClass = 'bg-light';
        $icon = '<i class="bi bi-person {0}"></i>';
        $iconClass = 'text-primary';

        if (!$user) {
            if (!$this->ActiveUser->isLoggedIn()) {
                return __($wrapper, [
                    $size,
                    $wrapperClass . ' ' . $wrapperClasses,
                    __($icon, [$iconClass . ' ' . $iconClasses]),
                ]);
            }
            $user = $this->ActiveUser->getUser();
        }

        return __($wrapper, [
            $size,
            $wrapperClass . ' ' . $wrapperClasses,
            $this->Gravatar->avatar($user, ['class' => 'avatar-' . $size]),
        ]);
    }
}
