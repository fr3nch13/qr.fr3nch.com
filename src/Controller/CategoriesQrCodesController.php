<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CategoriesQrCodes Controller
 *
 * @property \App\Model\Table\CategoriesQrCodesTable $CategoriesQrCodes
 */
class CategoriesQrCodesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->CategoriesQrCodes->find()
            ->contain(['Categories', 'QrCodes']);
        $categoriesQrCodes = $this->paginate($query);

        $this->set(compact('categoriesQrCodes'));
    }

    /**
     * View method
     *
     * @param string|null $id Categories Qr Code id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoriesQrCode = $this->CategoriesQrCodes->get($id, contain: ['Categories', 'QrCodes']);
        $this->set(compact('categoriesQrCode'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoriesQrCode = $this->CategoriesQrCodes->newEmptyEntity();
        if ($this->request->is('post')) {
            $categoriesQrCode = $this->CategoriesQrCodes->patchEntity($categoriesQrCode, $this->request->getData());
            if ($this->CategoriesQrCodes->save($categoriesQrCode)) {
                $this->Flash->success(__('The categories qr code has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories qr code could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesQrCodes->Categories->find('list', limit: 200)->all();
        $qrCodes = $this->CategoriesQrCodes->QrCodes->find('list', limit: 200)->all();
        $this->set(compact('categoriesQrCode', 'categories', 'qrCodes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Categories Qr Code id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $categoriesQrCode = $this->CategoriesQrCodes->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoriesQrCode = $this->CategoriesQrCodes->patchEntity($categoriesQrCode, $this->request->getData());
            if ($this->CategoriesQrCodes->save($categoriesQrCode)) {
                $this->Flash->success(__('The categories qr code has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories qr code could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesQrCodes->Categories->find('list', limit: 200)->all();
        $qrCodes = $this->CategoriesQrCodes->QrCodes->find('list', limit: 200)->all();
        $this->set(compact('categoriesQrCode', 'categories', 'qrCodes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Categories Qr Code id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoriesQrCode = $this->CategoriesQrCodes->get($id);
        if ($this->CategoriesQrCodes->delete($categoriesQrCode)) {
            $this->Flash->success(__('The categories qr code has been deleted.'));
        } else {
            $this->Flash->error(__('The categories qr code could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
