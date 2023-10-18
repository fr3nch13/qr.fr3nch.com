<?php
declare(strict_types=1);

/**
 * Default view fo the site
 */
namespace App\View;

trait LoadAppHelpersTrait
{
    /**
     * Load the helpers for the View classes
     *
     * @return void
     */
    public function loadAppHelpers(): void
    {
        $this->loadHelper('ActiveUser');
        $this->loadHelper('Breadcrumbs', ['className' => 'BootstrapUI.Breadcrumbs']);
        $this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
        $this->loadHelper('Form', [
            'templates' => 'app_form',
        ]);
        $this->loadHelper('Gravatar');
        $this->loadHelper('Html');
        $this->loadHelper('Authentication.Identity');
        $this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);
        $this->addHelper('Template');
        $this->addHelper('Url');
        $this->loadHelper('Search.Search');
    }
}
