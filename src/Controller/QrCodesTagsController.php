<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * QrCodesTags Controller
 *
 * @property \App\Model\Table\QrCodesTagsTable $QrCodesTags
 */
class QrCodesTagsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->QrCodesTags->find()
            ->contain(['Tags', 'QrCodes']);
        $qrCodesTags = $this->paginate($query);

        $this->set(compact('qrCodesTags'));
    }

    /**
     * View method
     *
     * @param string|null $id Qr Codes Tag id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $qrCodesTag = $this->QrCodesTags->get($id, contain: ['Tags', 'QrCodes']);
        $this->set(compact('qrCodesTag'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $qrCodesTag = $this->QrCodesTags->newEmptyEntity();
        if ($this->request->is('post')) {
            $qrCodesTag = $this->QrCodesTags->patchEntity($qrCodesTag, $this->request->getData());
            if ($this->QrCodesTags->save($qrCodesTag)) {
                $this->Flash->success(__('The qr codes tag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The qr codes tag could not be saved. Please, try again.'));
        }
        $tags = $this->QrCodesTags->Tags->find('list', limit: 200)->all();
        $qrCodes = $this->QrCodesTags->QrCodes->find('list', limit: 200)->all();
        $this->set(compact('qrCodesTag', 'tags', 'qrCodes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Qr Codes Tag id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $qrCodesTag = $this->QrCodesTags->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $qrCodesTag = $this->QrCodesTags->patchEntity($qrCodesTag, $this->request->getData());
            if ($this->QrCodesTags->save($qrCodesTag)) {
                $this->Flash->success(__('The qr codes tag has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The qr codes tag could not be saved. Please, try again.'));
        }
        $tags = $this->QrCodesTags->Tags->find('list', limit: 200)->all();
        $qrCodes = $this->QrCodesTags->QrCodes->find('list', limit: 200)->all();
        $this->set(compact('qrCodesTag', 'tags', 'qrCodes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Qr Codes Tag id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $qrCodesTag = $this->QrCodesTags->get($id);
        if ($this->QrCodesTags->delete($qrCodesTag)) {
            $this->Flash->success(__('The qr codes tag has been deleted.'));
        } else {
            $this->Flash->error(__('The qr codes tag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
