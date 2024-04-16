<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * Runs before the code in the actions
     *
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        $this->Authentication->addUnauthenticatedActions(['login', 'logout', 'profile']);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        if (in_array($action, ['profile'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                throw new NotFoundException('Unknown ID');
            }
        }

        parent::beforeFilter($event);
    }

    /**
     * Action to allow users to login.
     *
     * @return ?\Cake\Http\Response Renders view
     */
    public function login(): ?Response
    {
        $this->request->allowMethod(['get', 'post']);

        /** @var \Authentication\Authenticator\Result|null $result */
        $result = $this->Authentication->getResult();

        // regardless of POST or GET, redirect if user is logged in
        if ($result) {
            if ($result->isValid()) {
                $this->Flash->success(__('Welcome back {0}', [
                    $this->getActiveUser('name'),
                ]));

                // redirect to the main dashboard after login success
                $redirect = $this->request->getQuery('redirect', [
                    'prefix' => 'Admin',
                    'controller' => 'Users',
                    'action' => 'dashboard',
                ]);

                return $this->redirect($redirect);
            }
        }

        // display error if user submitted and authentication failed
        if ($this->request->is('post') && (!$result || !$result->isValid())) {
            $this->Flash->error(__('Invalid email or password, or your account may be inactive.'));
        }

        $errors = [];
        if ($result) {
            $errors = $result->getErrors();
        }

        $this->set(compact('result', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['result', 'errors']);

        return null;
    }

    /**
     * Allow users to logout
     *
     * @return \Cake\Http\Response|null The redirect
     */
    public function logout(): ?Response
    {
        $this->request->allowMethod(['get', 'post']);

        /** @var \Authentication\Authenticator\Result|null $result */
        $result = $this->Authentication->getResult();

        // regardless of POST or GET, redirect if user is logged in
        if ($result) {
            if ($result->isValid()) {
                $this->Authentication->logout();
            }
        }

        $this->Flash->success(__('You have been logged out'));

        // either way redirect them to the login page
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login',
        ]);
    }

    /**
     * Public Profile method
     *
     * @param ?string $id User id.
     * @return ?\Cake\Http\Response Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function profile(?string $id = null): ?Response
    {
        $this->request->allowMethod(['get']);

        $user = $this->Users->get((int)$id, contain: []);
        $this->Authorization->authorize($user);

        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', ['user']);

        return null;
    }
}
