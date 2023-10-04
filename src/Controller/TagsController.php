<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class TagsController extends AppController
{
    /**
     * Runs before the code in the actions
     */
    public function beforeFilter(EventInterface $event): void
    {

        //Allow anyone to view the list of tags, and their details page.
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);

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

        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $query = $this->Tags->find('all');
        $tags = $this->paginate($query);

        $this->set(compact('tags'));
        $this->viewBuilder()->setOption('serialize', ['tags']);
    }

    /**
     * View method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $tag = $this->Tags->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($tag);

        $this->set(compact('tag'));
        $this->viewBuilder()->setOption('serialize', ['tag']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['get', 'post']);

        $tag = $this->Tags->newEmptyEntity();
        $this->Authorization->authorize($tag);

        if ($this->request->is('post')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            $tag->user_id = $this->getActiveUser('id');
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The tag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }

        $errors = $tag->getErrors();
        $qrCodes = $this->Tags->QrCodes->find('list', limit: 200)->all();

        $this->set(compact('tag', 'qrCodes', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['tag', 'qrCodes', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $this->request->allowMethod(['get', 'patch']);

        $tag = $this->Tags->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($tag);

        if ($this->request->is('patch')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The tag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }

        $errors = $tag->getErrors();
        $qrCodes = $this->Tags->QrCodes->find('list', limit: 200)->all();

        $this->set(compact('tag', 'qrCodes', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['tag', 'qrCodes', 'errors']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $tag = $this->Tags->get((int)$id);
        $this->Authorization->authorize($tag);

        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('The tag `{0}` has been deleted.', [
                $tag->name,
            ]));

            return $this->redirect(['action' => 'index']);
        }
    }
}
