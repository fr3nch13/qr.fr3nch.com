<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class TagsController extends AppController
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

        $query = $this->Tags->find('all');

        $tags = $this->paginate($query);

        $this->set(compact('tags'));
        $this->viewBuilder()
            ->setOption('serialize', ['tags']);
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
        $this->viewBuilder()
            ->setOption('serialize', ['tag']);
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

        $qrCodes = $this->Tags->QrCodes->find('list', limit: 200)->all();
        $this->set(compact('tag', 'qrCodes'));
        $this->viewBuilder()
            ->setOption('serialize', ['tag', 'qrCodes']);
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

        $qrCodes = $this->Tags->QrCodes->find('list', limit: 200)->all();
        $this->set(compact('tag', 'qrCodes'));
        $this->viewBuilder()
            ->setOption('serialize', ['tag', 'qrCodes']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $tag = $this->Tags->get((int)$id);
        $this->Authorization->authorize($tag);

        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('The tag has been deleted.'));
        } else {
            // @todo how to test this, since the get() above with throw a 404 first.
            $this->Flash->error(__('The tag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
