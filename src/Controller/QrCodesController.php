<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * QrCodes Controller
 *
 * @property \App\Model\Table\QrCodesTable $QrCodes
 */
class QrCodesController extends AppController
{
    /**
     * Runs before the code in the actions
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $query = $this->QrCodes->find()
            ->contain(['Sources', 'Users']);
        $qrCodes = $this->paginate($query);

        $this->set(compact('qrCodes'));
    }

    /**
     * View method
     *
     * @param string|null $id Qr Code id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();
        $qrCode = $this->QrCodes->get($id, contain: ['Sources', 'Users', 'Categories', 'Tags']);
        $this->set(compact('qrCode'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $qrCode = $this->QrCodes->newEmptyEntity();
        if ($this->request->is('post')) {
            $qrCode = $this->QrCodes->patchEntity($qrCode, $this->request->getData());
            if ($this->QrCodes->save($qrCode)) {
                $this->Flash->success(__('The qr code has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The qr code could not be saved. Please, try again.'));
        }
        $sources = $this->QrCodes->Sources->find('list', limit: 200)->all();
        $users = $this->QrCodes->Users->find('list', limit: 200)->all();
        $categories = $this->QrCodes->Categories->find('list', limit: 200)->all();
        $tags = $this->QrCodes->Tags->find('list', limit: 200)->all();
        $this->set(compact('qrCode', 'sources', 'users', 'categories', 'tags'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Qr Code id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $qrCode = $this->QrCodes->get($id, contain: ['Categories', 'Tags']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $qrCode = $this->QrCodes->patchEntity($qrCode, $this->request->getData());
            if ($this->QrCodes->save($qrCode)) {
                $this->Flash->success(__('The qr code has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The qr code could not be saved. Please, try again.'));
        }
        $sources = $this->QrCodes->Sources->find('list', limit: 200)->all();
        $users = $this->QrCodes->Users->find('list', limit: 200)->all();
        $categories = $this->QrCodes->Categories->find('list', limit: 200)->all();
        $tags = $this->QrCodes->Tags->find('list', limit: 200)->all();
        $this->set(compact('qrCode', 'sources', 'users', 'categories', 'tags'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Qr Code id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $qrCode = $this->QrCodes->get($id);
        if ($this->QrCodes->delete($qrCode)) {
            $this->Flash->success(__('The qr code has been deleted.'));
        } else {
            $this->Flash->error(__('The qr code could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
