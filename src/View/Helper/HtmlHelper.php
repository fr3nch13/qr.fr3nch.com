<?php
declare(strict_types=1);

/**
 * Helps with the Html stuff
 */
namespace App\View\Helper;

use BootstrapUI\View\Helper\HtmlHelper as BootstrapUiHtmlHelper;

/**
 * Html helper library.
 *
 * @property \App\View\Helper\ActiveUserHelper $ActiveUser
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
        'Url',
    ];

    /**
     * Creates the avatar html
     *
     * @param string $size The one of three sizes [sm, md, lg]
     * @param string $wrapperClasses Classes you want to add to the div wrapper
     * @param string $iconClasses Classes you want to add to the icon
     */
    public function avatar(
        string $size = 'md',
        string $wrapperClasses = '',
        string $iconClasses = ''
    ): string
    {
        $wrapper = '<div class="avatar-{0} d-flex align-items-center justify-content-center {1}">{2}</div>';
        $wrapperClass = 'bg-light';
        $icon = '<i class="bi bi-person {0}"></i>';
        $iconClass = 'text-primary';

        if (!$this->ActiveUser->isLoggedIn()) {
            return __($wrapper, [
                $size,
                $wrapperClass . ' ' . $wrapperClasses,
                __($icon, [$iconClass. ' ' . $iconClasses])
            ]);
        }

        if (!$this->ActiveUser->getUser()->getPathThumb()) {
            $wrapperClass = 'bg-primary';
            $iconClass = 'text-light';
            return __($wrapper, [
                $size,
                $wrapperClass . ' ' . $wrapperClasses,
                __($icon, [$iconClass. ' ' . $iconClasses])
            ]);
        }

        $avatarUrl = $this->Url->build([
            'plugin' => false,
            'prefix' => false,
            'controller' => 'Users',
            'action' => 'avatar',
            $this->ActiveUser->getUser('id'),
            '?' => ['thumb' => $size],
        ]);

        $img = $this->image($avatarUrl, [
            'alt' => $this->ActiveUser->getUser('name'),
        ]);

        return __($wrapper, [
            $size,
            $wrapperClass,
            $img
        ]);
    }
}
