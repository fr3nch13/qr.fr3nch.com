<?php
declare(strict_types=1);

/**
 * Default view fo the site
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 * @property \App\View\Helper\ActiveUserHelper $ActiveUser
 * @property \App\View\Helper\FormHelper $Form
 * @property \App\View\Helper\HtmlHelper $Html
 * @property \App\View\Helper\TemplateHelper $Template
 * @property \BootstrapUI\View\Helper\FlashHelper $Flash
 * @property \BootstrapUI\View\Helper\PaginatorHelper $Paginator
 * @property \BootstrapUI\View\Helper\BreadcrumbsHelper $Breadcrumbs
 * @property \Authentication\View\Helper\IdentityHelper $Identity
 */
class AppView extends View
{
    /**
     * The name of the layout file to render the view inside of. The name
     * specified is the filename of the layout in /templates/Layout without
     * the .php extension.
     *
     * @var string
     */
    protected string $layout = 'default';

    /**
     * Get content type for this view.
     *
     * @return string
     */
    public static function contentType(): string
    {
        return 'text/html';
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like adding helpers.
     *
     * e.g. `$this->addHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->addHelper('Template');
        $this->loadHelper('Authentication.Identity');
        $this->loadHelper('ActiveUser');
        $this->loadHelper('Html');
        $this->loadHelper('Form');
        $this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
        $this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);
        $this->loadHelper('Breadcrumbs', ['className' => 'BootstrapUI.Breadcrumbs']);
    }
}
