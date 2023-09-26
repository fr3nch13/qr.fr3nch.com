<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
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
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login', 'logout']);
    }

    /**
     * Action to allow users to login.
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $this->Authorization->skipAuthorization();

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
    }

    /**
     * Allow users to logout
     *
     * @return \Cake\Http\Response|null The redirect
     */
    public function logout(): ?Response
    {
        $this->request->allowMethod(['get', 'post']);
        $this->Authorization->skipAuthorization();

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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);
        // @todo Figure out how to do authorization on a logged-in index page
        // seems like i need to make a Policy for the Model
        // Specifically here, make sure only admin can see the list.
        $this->Authorization->skipAuthorization();

        $query = $this->Users->find('all');

        $users = $this->paginate($query);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $user = $this->Users->get((int)$id, contain: []);
        $this->Authorization->authorize($user);

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['get', 'post']);

        $user = $this->Users->newEmptyEntity();
        $this->Authorization->authorize($user);

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $this->request->allowMethod(['get', 'patch']);

        $user = $this->Users->get((int)$id, contain: []);
        $this->Authorization->authorize($user);

        if ($this->request->is('patch')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $user = $this->Users->get((int)$id);
        $this->Authorization->authorize($user);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            // @todo how to test this, since the get() above with throw a 404 first.
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
