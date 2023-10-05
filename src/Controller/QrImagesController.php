<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * QrImages Controller
 *
 * @property \App\Model\Table\QrImagesTable $QrImages
 */
class QrImagesController extends AppController
{
    /**
     * Runs before the code in the actions
     */
    public function beforeFilter(EventInterface $event): void
    {
        // allowed actions for anyone.
        $this->Authentication->addUnauthenticatedActions(['show']);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['show', 'edit', 'delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                $event->stopPropagation();
                throw new NotFoundException('Unknown ID');
            }
        }

        parent::beforeFilter($event);
    }

    /**
     * Show method
     *
     * Shows the actual Image.
     *
     * @param string|null $id Image id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function show(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $qrImage = $this->QrImages->get((int)$id);
        $this->Authorization->authorize($qrImage);

        $response = $this->response->withFile($qrImage->path);
        return $response;
    }

    /**
     * Index method
     *
     * @param string|null $id QR Code id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function qrCode(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $qrCode = $this->QrImages->QrCodes->get((int)$id);
        $this->Authorization->authorize($qrCode);

        $query = $this->QrImages->find('all');
        $qrImages = $this->paginate($query);

        $this->set(compact('qrCode', 'qrImages'));
        $this->viewBuilder()->setOption('serialize', ['qrCode', 'qrImages']);
    }

    /**
     * Add method
     *
     * @param string|null $id QR Code id.
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function add(?string $id = null)
    {
        $this->request->allowMethod(['get', 'post']);

        $qrCode = $this->QrImages->QrCodes->get((int)$id);
        $this->Authorization->authorize($qrCode);

        $qrImage = $this->QrImages->newEmptyEntity();
        $qrImage->qr_code_id = $qrCode->id;
        $this->Authorization->authorize($qrImage);

        if ($this->request->is('post')) {
            $qrImage = $this->QrImages->patchEntity($qrImage, $this->request->getData());
            $qrImage->qr_code_id = $qrCode->id;
            if ($this->QrImages->save($qrImage)) {
                $this->Flash->success(__('The image has been saved.'));

                return $this->redirect(['action' => 'qrCode', $qrCode->id]);
            }
            $this->Flash->error(__('The image could not be saved. Please, try again.'));
        }

        $errors = $qrImage->getErrors();

        $this->set(compact('qrCode', 'qrImage', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['qrCode', 'qrImage', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Image id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $this->request->allowMethod(['get', 'patch']);

        $qrImage = $this->QrImages->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($qrImage);

        if ($this->request->is('patch')) {
            $qrImage = $this->QrImages->patchEntity($qrImage, $this->request->getData());
            if ($this->QrImages->save($qrImage)) {
                $this->Flash->success(__('The image has been saved.'));

                return $this->redirect(['action' => 'qrCode', $qrImage->qr_code->id]);
            }
            $this->Flash->error(__('The image could not be saved. Please, try again.'));
        }

        $errors = $qrImage->getErrors();

        $this->set(compact('qrImage', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['qrImage', 'errors']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Image id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['delete']);

        $qrImage = $this->QrImages->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($qrImage);

        if ($this->QrImages->delete($qrImage)) {
            $this->Flash->success(__('The image `{0}` for `{1}` has been deleted.', [
                $qrImage->name,
                $qrImage->qr_code->name,
            ]));

            return $this->redirect(['action' => 'qrCode', $qrImage->qr_code->id]);
        }
    }
}
