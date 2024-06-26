<?php
declare(strict_types=1);

/**
 * The core App Controller
 */
namespace App\Controller;

use App\Model\Entity\User;
use App\View\AjaxView;
use App\View\AppView;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\View\JsonView;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @property \Cake\Controller\Component\FlashComponent $Flash
 * @property \Cake\Controller\Component\FormProtectionComponent $FormProtection
 */
class AppController extends Controller
{
    /**
     * @var \App\Model\Entity\User|null The logged in user entity.
     */
    public ?User $ActiveUser = null;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         *
         * @link https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        $this->loadComponent('FormProtection');

        /**
         * @link https://book.cakephp.org/5/en/tutorials-and-examples/cms/authentication.html
         */
        $this->loadComponent('Authentication.Authentication');

        /**
         *  @link https://book.cakephp.org/5/en/tutorials-and-examples/cms/authorization.html
         */
        $this->loadComponent('Authorization.Authorization');

        $viewClass = AppView::class;

        // determine which view class to use
        if ($this->request->is('ajax')) {
            $viewClass = AjaxView::class;
        }
        if ($this->request->is('json')) {
            $viewClass = JsonView::class;
        }

        $this->viewBuilder()->setClassName($viewClass);
    }

    /**
     * Runs before the code in the actions
     *
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        $this->Authorization->authorize($this);
    }

    /**
     * Gets the active/logged in user from the session.
     *
     * @param ?string $field If the user exists, return this field
     * @param mixed|null $default If the field doesn't exist, or the users doesn't then return this.
     * @return mixed The logged in user, or $default if none, or the field doesn't exist.
     */
    public function getActiveUser(?string $field = null, mixed $default = null): mixed
    {
        /** @var \Authentication\Identity|null $identity */
        $identity = $this->Authentication->getIdentity();
        if ($identity) {
            /** @var \App\Model\Entity\User|null $user */
            $user = $identity->getOriginalData();

            if ($user && $field) {
                return $user->{$field} ?: $default;
            }

            return $user;
        }

        return $default;
    }
}
