<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

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
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);
        $this->Authorization->skipAuthorization();

        $query = $this->Categories->find('all')
            ->contain(['ParentCategories']);
        $categories = $this->paginate($query);

        $this->set(compact('categories'));
        $this->viewBuilder()
            ->setOption('serialize', ['categories']);
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
        $this->Authorization->skipAuthorization();

        $category = $this->Categories->get((int)$id, contain: ['ParentCategories', 'QrCodes', 'ChildCategories']);

        $this->set(compact('category'));
        $this->viewBuilder()
            ->setOption('serialize', ['category']);
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

        $parentCategories = $this->Categories->ParentCategories->find('list', limit: 200)->all();

        $this->set(compact('category', 'parentCategories'));
        $this->viewBuilder()
            ->setOption('serialize', ['category', 'parentCategories']);
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

        $parentCategories = $this->Categories->ParentCategories->find('list', limit: 200)->all();

        $this->set(compact('category', 'parentCategories'));
        $this->viewBuilder()
            ->setOption('serialize', ['category', 'parentCategories']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $category = $this->Categories->get((int)$id);
        $this->Authorization->authorize($category);

        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            // @todo how to test this, since the get() above with throw a 404 first.
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
