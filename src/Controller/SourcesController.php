<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Sources Controller
 *
 * @property \App\Model\Table\SourcesTable $Sources
 */
class SourcesController extends AppController
{
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
        $this->Authorization->skipAuthorization();

        $query = $this->Sources->find('all');

        $sources = $this->paginate($query);

        $this->set(compact('sources'));
        $this->set('_serialize', ['sources']);
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
        $this->set('_serialize', ['source']);
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

        $this->set(compact('source'));
        $this->set('_serialize', ['source']);
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

        $this->set(compact('source'));
        $this->set('_serialize', ['source']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $source = $this->Sources->get((int)$id);
        $this->Authorization->authorize($source);

        if ($this->Sources->delete($source)) {
            $this->Flash->success(__('The source has been deleted.'));
        } else {
            // @todo how to test this, since the get() above with throw a 404 first.
            $this->Flash->error(__('The source could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
