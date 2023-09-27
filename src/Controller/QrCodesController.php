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
        $this->request->allowMethod(['get']);
        $this->Authorization->skipAuthorization();

        $query = $this->QrCodes->find('all')
            ->contain(['Sources', 'Users', 'Categories', 'Tags']);
        $qrCodes = $this->paginate($query);

        $this->set(compact('qrCodes'));
        $this->viewBuilder()->setOption('serialize', ['qrCodes']);
    }

    /**
     * View method
     *
     * @param string|null $id Qr Code id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $this->request->allowMethod(['get']);
        $this->Authorization->skipAuthorization();

        $qrCode = $this->QrCodes->get((int)$id, contain: ['Sources', 'Users', 'Categories', 'Tags']);

        $this->set(compact('qrCode'));
        $this->viewBuilder()->setOption('serialize', ['qrCode']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['get', 'post']);

        $qrCode = $this->QrCodes->newEmptyEntity();
        $this->Authorization->authorize($qrCode);

        if ($this->request->is('post')) {
            $qrCode = $this->QrCodes->patchEntity($qrCode, $this->request->getData());
            $qrCode->user_id = $this->getActiveUser('id');
            if ($this->QrCodes->save($qrCode)) {
                $this->Flash->success(__('The qr code has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The qr code could not be saved. Please, try again.'));
        }

        $errors = $qrCode->getErrors();
        $sources = $this->QrCodes->Sources->find('list', limit: 200)->all();
        $categories = $this->QrCodes->Categories->find('list', limit: 200)->all();
        $tags = $this->QrCodes->Tags->find('list', limit: 200)->all();

        $this->set(compact('qrCode', 'sources', 'categories', 'tags', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['qrCode', 'sources', 'categories', 'tags', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Qr Code id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $this->request->allowMethod(['get', 'patch']);

        $qrCode = $this->QrCodes->get((int)$id, contain: ['Categories', 'Tags']);
        $this->Authorization->authorize($qrCode);

        if ($this->request->is('patch')) {
            $qrCode = $this->QrCodes->patchEntity($qrCode, $this->request->getData());
            if ($this->QrCodes->save($qrCode)) {
                $this->Flash->success(__('The qr code has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The qr code could not be saved. Please, try again.'));
        }

        $errors = $qrCode->getErrors();
        $sources = $this->QrCodes->Sources->find('list', limit: 200)->all();
        $categories = $this->QrCodes->Categories->find('list', limit: 200)->all();
        $tags = $this->QrCodes->Tags->find('list', limit: 200)->all();

        $this->set(compact('qrCode', 'sources', 'categories', 'tags', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['qrCode', 'sources', 'categories', 'tags', 'errors']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Qr Code id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $qrCode = $this->QrCodes->get((int)$id);
        $this->Authorization->authorize($qrCode);

        if ($this->QrCodes->delete($qrCode)) {
            $this->Flash->success(__('The qr code `{0}` has been deleted.', [
                $qrCode->name,
            ]));

            return $this->redirect(['action' => 'index']);
        }
    }
}
