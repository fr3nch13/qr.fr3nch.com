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
     */
    public function beforeFilter(EventInterface $event): void
    {
        $this->Authentication->addUnauthenticatedActions(['login', 'logout', 'profile']);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        if (in_array($action, ['profile', 'delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                $event->stopPropagation();
                throw new NotFoundException('Unknown ID');
            }
        }

        parent::beforeFilter($event);
    }

    /**
     * Action to allow users to login.
     *
     * @return \Cake\Http\Response|null|void Renders view
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

                // redirect to /qr-codes after login success
                $redirect = $this->request->getQuery('redirect', [
                    'controller' => 'QrCodes',
                    'action' => 'index',
                ]);

                return $this->redirect($redirect);
            }
        }

        // display error if user submitted and authentication failed
        if ($this->request->is('post') && (!$result || !$result->isValid())) {
            $this->Flash->error(__('Invalid email or password'));
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
     * @return \Cake\Http\Response|null|void Renders view
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(): ?Response
    {
        $this->request->allowMethod(['get']);

        $query = $this->Users->find('all');
        $query = $this->Authorization->applyScope($query);
        $users = $this->paginate($query);

        $this->set(compact('users'));
        $this->viewBuilder()->setOption('serialize', ['users']);

        return null;
    }

    /**
     * Private View method
     *
     * @param ?string $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null): ?Response
    {
        if (!$id) {
            $id = $this->getActiveUser('id');
        }

        $this->request->allowMethod(['get']);

        $user = $this->Users->get((int)$id, contain: []);
        $this->Authorization->authorize($user);

        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', ['user']);

        return null;
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): ?Response
    {
        $this->request->allowMethod(['get', 'post']);

        $user = $this->Users->newEmptyEntity();
        $this->Authorization->authorize($user);

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect([
                    'action' => 'view',
                    $user->id,
                    '_ext' => $this->getRequest()->getParam('_ext'),
                ]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $errors = $user->getErrors();

        $this->set(compact('user', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['user', 'errors']);

        return null;
    }

    /**
     * Edit method
     *
     * @param ?string $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null): ?Response
    {
        if (!$id) {
            $id = $this->getActiveUser('id');
        }

        $this->request->allowMethod(['get', 'patch']);

        $user = $this->Users->get((int)$id, contain: []);
        $this->Authorization->authorize($user);

        if ($this->request->is('patch')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect([
                    'action' => 'view',
                    $user->id,
                    '_ext' => $this->getRequest()->getParam('_ext'),
                ]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $errors = $user->getErrors();

        $this->set(compact('user', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['user', 'errors']);

        return null;
    }

    /**
     * Delete method
     *
     * @param ?string $id User id.
     * @return ?\Cake\Http\Response Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null): ?Response
    {
        $this->request->allowMethod(['delete']);

        $user = $this->Users->get((int)$id);
        $this->Authorization->authorize($user);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user `{0}` has been deleted.', [
                $user->name,
            ]));
        } else {
            $this->Flash->error(__('Unable to delete the user `{0}`.', [
                $user->name,
            ]));
        }

        return $this->redirect([
            'action' => 'index',
            '_ext' => $this->getRequest()->getParam('_ext'),
        ]);
    }
}
