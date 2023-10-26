<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Admin Users Controller
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
        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        if (in_array($action, ['delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                $event->stopPropagation();
                throw new NotFoundException('Unknown ID');
            }
        }

        parent::beforeFilter($event);
    }

    /**
     * Main admin page
     *
     * @return ?\Cake\Http\Response Renders view
     */
    public function dashboard(): ?Response
    {
        $this->request->allowMethod(['get']);

        $activeUser = $this->getActiveUser();

        /** @var \Fr3nch13\Stats\Model\Table\StatsCountsTable $StatsCounts */
        $StatsCounts = $this->getTableLocator()->get('Fr3nch13/Stats.StatsCounts');
        $stats = $StatsCounts->getObjectStats('QrCode.hits');

        $this->set(compact('activeUser', 'stats'));
        $this->viewBuilder()->setOption('serialize', [
            'activeUser',
            'stats',
        ]);

        return null;
    }

    /**
     * Index method
     *
     * @return ?\Cake\Http\Response Renders view
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
     * @return ?\Cake\Http\Response Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null): ?Response
    {
        if (!$id) {
            $id = $this->getActiveUser('id');
        }

        $this->request->allowMethod(['get']);

        $user = $this->Users->get((int)$id, contain: [
            'QrCodes',
        ]);
        $this->Authorization->authorize($user);

        $this->set(compact('user'));
        $this->viewBuilder()->setOption('serialize', ['user']);

        return null;
    }

    /**
     * Add method
     *
     * @return ?\Cake\Http\Response Redirects on successful add, renders view otherwise.
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
     * @return ?\Cake\Http\Response Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null): ?Response
    {
        if (!$id) {
            $id = $this->getActiveUser('id');
        }

        $this->request->allowMethod(['get', 'put']);

        $user = $this->Users->get((int)$id, contain: []);
        $this->Authorization->authorize($user);

        if ($this->request->is('put')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                if ($id === $this->getActiveUser('id')) {
                    $this->Authentication->setIdentity($user);
                }

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
        $this->request->allowMethod(['delete', 'post']);

        $user = $this->Users->get((int)$id);
        $this->Authorization->authorize($user);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user `{0}` has been deleted.', [
                $user->name,
            ]));
        }

        return $this->redirect([
            'action' => 'index',
            '_ext' => $this->getRequest()->getParam('_ext'),
        ]);
    }
}
