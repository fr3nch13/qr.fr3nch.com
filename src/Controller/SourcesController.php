<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Sources Controller
 *
 * @property \App\Model\Table\SourcesTable $Sources
 */
class SourcesController extends AppController
{
    /**
     * Runs before the code in the actions
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authorization->authorize($this);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['view', 'edit', 'delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                $event->stopPropagation();
                throw new NotFoundException('Unknown ID');
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $query = $this->Sources->find('all');
        $sources = $this->paginate($query);

        $this->set(compact('sources'));
        $this->viewBuilder()->setOption('serialize', ['sources']);
    }

    /**
     * View method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $source = $this->Sources->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($source);

        $this->set(compact('source'));
        $this->viewBuilder()->setOption('serialize', ['source']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['get', 'post']);

        $source = $this->Sources->newEmptyEntity();
        $this->Authorization->authorize($source);

        if ($this->request->is('post')) {
            $source = $this->Sources->patchEntity($source, $this->request->getData());
            $source->user_id = $this->getActiveUser('id');
            if ($this->Sources->save($source)) {
                $this->Flash->success(__('The source has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The source could not be saved. Please, try again.'));
        }

        $errors = $source->getErrors();

        $this->set(compact('source', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['source', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $this->request->allowMethod(['get', 'patch']);

        $source = $this->Sources->get((int)$id, contain: []);
        $this->Authorization->authorize($source);

        if ($this->request->is('patch')) {
            $source = $this->Sources->patchEntity($source, $this->request->getData());
            if ($this->Sources->save($source)) {
                $this->Flash->success(__('The source has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The source could not be saved. Please, try again.'));
        }

        $errors = $source->getErrors();

        $this->set(compact('source', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['source', 'errors']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $source = $this->Sources->get((int)$id);
        $this->Authorization->authorize($source);

        if ($this->Sources->delete($source)) {
            $this->Flash->success(__('The source `{0}` has been deleted.', [
                $source->name,
            ]));

            return $this->redirect(['action' => 'index']);
        }
    }
}
