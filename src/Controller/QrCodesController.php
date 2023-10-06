<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\Query\SelectQuery;

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

        // allowed actions for anyone.
        $this->Authentication->addUnauthenticatedActions(['forward', 'show', 'index', 'view']);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['forward', 'show', 'view', 'edit', 'delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                $event->stopPropagation();
                throw new NotFoundException('Unknown ID');
            }
        }

        parent::beforeFilter($event);
    }

    /**
     * The method that handles the forwarding
     *
     * @param string|null $key The QR Code key to lookup.
     * @return \Cake\Http\Response|null The response object.
     */
    public function forward(?string $key = null): ?Response
    {
        $this->request->allowMethod(['get']);

        // if no key given, redirect them to the index page.
        if (!$key) {
            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $qrCode = $this->QrCodes->find('key', key: $key)->first();
        $this->Authorization->authorize($qrCode);

        // if we can't find it, redirect to index with an error message.
        if (!$qrCode) {
            $this->Flash->error(__('A QR Code with the key: `{0}` could not be found.', [
                $key,
            ]));

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        return $this->redirect($qrCode->url);
    }

    /**
     * Show method
     *
     * Shows the actual QR Code.
     *
     * @param string|null $id QR Code id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function show(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $qrCode = $this->QrCodes->get((int)$id);
        $this->Authorization->authorize($qrCode);

        $response = $this->response->withFile($qrCode->path);
        return $response;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        // look for an incoming key in the query,
        // and redirect it to the forward action.
        if ($this->request->getQuery('k')) {
            $this->redirect([
                'action' => 'forward',
                $this->request->getQuery('k'),
            ]);
        }

        $query = $this->QrCodes->find('all')
            ->contain([
                'QrImages' => function (SelectQuery $q) {
                    // only include the first active one
                    return $q
                        ->find('active')
                        ->find('orderFirst');
            }]);
        $query = $this->Authorization->applyScope($query);
        $qrCodes = $this->paginate($query);

        $this->set(compact('qrCodes'));
        $this->viewBuilder()->setOption('serialize', ['qrCodes']);
    }

    /**
     * View method
     *
     * @param string|null $id QR Code id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $this->request->allowMethod(['get']);

        $qrCode = $this->QrCodes->get((int)$id, contain: ['Sources', 'Users', 'Categories', 'Tags', 'QrImages']);
        $this->Authorization->authorize($qrCode);

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

                return $this->redirect([
                    'action' => 'view',
                    $qrCode->id,
                    '_ext' => $this->getRequest()->getParam('_ext')
                ]);
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
     * @param string|null $id QR Code id.
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

                return $this->redirect([
                    'action' => 'view',
                    $qrCode->id,
                    '_ext' => $this->getRequest()->getParam('_ext')
                ]);
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
     * @param string|null $id QR Code id.
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

            return $this->redirect([
                'action' => 'index',
                '_ext' => $this->getRequest()->getParam('_ext')
            ]);
        }
    }
}
