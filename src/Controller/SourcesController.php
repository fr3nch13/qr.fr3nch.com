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
        $query = $this->Sources->find();
        $sources = $this->paginate($query);

        $this->set(compact('sources'));
    }

    /**
     * View method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $source = $this->Sources->get($id, contain: ['QrCodes']);
        $this->set(compact('source'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $source = $this->Sources->newEmptyEntity();
        if ($this->request->is('post')) {
            $source = $this->Sources->patchEntity($source, $this->request->getData());
            if ($this->Sources->save($source)) {
                $this->Flash->success(__('The source has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The source could not be saved. Please, try again.'));
        }
        $this->set(compact('source'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $source = $this->Sources->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $source = $this->Sources->patchEntity($source, $this->request->getData());
            if ($this->Sources->save($source)) {
                $this->Flash->success(__('The source has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The source could not be saved. Please, try again.'));
        }
        $this->set(compact('source'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Source id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $source = $this->Sources->get($id);
        if ($this->Sources->delete($source)) {
            $this->Flash->success(__('The source has been deleted.'));
        } else {
            $this->Flash->error(__('The source could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
