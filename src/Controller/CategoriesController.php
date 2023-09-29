<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{
    /**
     * Runs before the code in the actions
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        //Allow anyone to view the list of categories, and their details page.
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);

        $this->Authorization->authorize($this);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['view', 'edit', 'delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                throw new NotFoundException('Unknown ID');
            }
        }
    }

    /**
     * Use the below to check if the user can access the called action.
     * This is a general check, and should return true at the end,
     * as the Authorization->authorize() will handle the specific authorization in each action.
     *
     * @param \App\Model\Entity\User|null $user The logged in user
     * @return bool If they're allowed or not.
     */
    public function isAuthorized(?User $user): bool
    {
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['add', 'edit', 'delete'])) {
            if (!$user) {
                return false;
            }

            return $user->isAdmin();
        }

        // default is allow.
        return true;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $query = $this->Categories->find('all')
            ->contain(['QrCodes', 'ParentCategories']);
        $categories = $this->paginate($query);

        $this->set(compact('categories'));
        $this->viewBuilder()->setOption('serialize', ['categories']);
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $category = $this->Categories->get((int)$id, contain: ['ParentCategories', 'QrCodes', 'ChildCategories']);

        $this->set(compact('category'));
        $this->viewBuilder()->setOption('serialize', ['category']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['get', 'post']);

        $category = $this->Categories->newEmptyEntity();
        $this->Authorization->authorize($category);

        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            $category->user_id = $this->getActiveUser('id');
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }

        $errors = $category->getErrors();
        $parentCategories = $this->Categories->ParentCategories->find('list', limit: 200)->all();

        $this->set(compact('category', 'parentCategories', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['category', 'parentCategories', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $this->request->allowMethod(['get', 'patch']);

        $category = $this->Categories->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($category);

        if ($this->request->is('patch')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }

        $errors = $category->getErrors();
        $parentCategories = $this->Categories->ParentCategories->find('list', limit: 200)->all();

        $this->set(compact('category', 'parentCategories', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['category', 'parentCategories', 'errors']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $category = $this->Categories->get((int)$id);
        $this->Authorization->authorize($category);

        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category `{0}` has been deleted.', [
                $category->name,
            ]));

            return $this->redirect(['action' => 'index']);
        }
    }
}
