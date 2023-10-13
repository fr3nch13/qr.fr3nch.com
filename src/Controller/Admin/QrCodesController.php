<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * QrCodes Controller
 *
 * @property \App\Model\Table\QrCodesTable $QrCodes
 */
class QrCodesController extends AppController
{
    /**
     * Init method
     *
     * Mainly here to add the Search Component.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Search.Search', [
            // This is default config. You can modify "actions" as needed to make
            // the Search component work only for specified methods.
            'actions' => ['index'],
        ]);
    }

    /**
     * Runs before the code in the actions
     *
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['show', 'view', 'edit', 'delete'])) {
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
     * In the admin, this will forward active, and inactive, but
     * not register a hit. This is so we can test our forwarding.
     *
     * @param ?string $key The QR Code key to lookup.
     * @return \Cake\Http\Response|null The response object.
     */
    public function forward(?string $key = null): ?Response
    {
        $this->request->allowMethod(['get']);

        // if no key given, redirect them to the index page.
        if (!$key) {
            $this->Flash->error(__('No key was given.'));

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $qrCode = $this->QrCodes->find('key', key: $key)->first();

        // if we can't find it, redirect to index with an error message.
        if (!$qrCode) {
            $this->Flash->error(__('A QR Code with the key: `{0}` could not be found.', [
                $key,
            ]));

            return $this->redirect([
                'action' => 'index',
            ]);
        }
        $this->Authorization->authorize($qrCode);

        return $this->redirect(trim($qrCode->url));
    }

    /**
     * Show method
     *
     * Shows the actual QR Code.
     *
     * @param ?string $id QR Code id.
     * @return \Cake\Http\Response Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function show(?string $id = null): Response
    {
        $this->request->allowMethod(['get']);

        $qrCode = $this->QrCodes->get((int)$id);
        $this->Authorization->authorize($qrCode);

        if (!$qrCode->path) {
            throw new NotFoundException('Unable to find the image file.');
        }

        $response = $this->response->withFile($qrCode->path);
        $modified = date('Y-m-d H:i:s.', filemtime($qrCode->path) ?: null);
        $response = $response->withModified($modified);

        // allow browser and proxy caching when debug is off
        if (!Configure::read('debug')) {
            $response = $response->withSharable(true, 3600);
            $response = $response->withExpires('+5 minutes');
        }

        return $response;
    }

    /**
     * Index method
     *
     * @return ?\Cake\Http\Response Renders view
     */
    public function index(): ?Response
    {
        $this->request->allowMethod(['get']);

        $query = $this->QrCodes->find('all')
            ->find('search', search: $this->request->getQueryParams())
            ->contain([
                'Sources',
                'QrImages',
            ]);
        $query = $this->Authorization->applyScope($query);
        $qrCodes = $this->paginate($query);

        // for the filters
        $sources = $this->QrCodes->Sources
            ->find('active')
            ->find(
                'list',
                keyField: 'name',
                valueField: 'name',
                limit: 200
            )
            ->order(['name' => 'asc'])
            ->all();
        $tags = $this->QrCodes->Tags
            ->find('active')
            ->find(
                'list',
                keyField: 'name',
                valueField: 'name',
                limit: 200
            )
            ->order(['name' => 'asc'])
            ->all();

        $this->set(compact('qrCodes', 'sources', 'tags'));
        $this->viewBuilder()->setOption('serialize', ['qrCodes', 'sources', 'tags']);

        return null;
    }

    /**
     * View method
     *
     * @param ?string $id QR Code id.
     * @return ?\Cake\Http\Response Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null): ?Response
    {
        $this->request->allowMethod(['get']);

        $qrCode = $this->QrCodes->get((int)$id, contain: ['Sources', 'Users', 'Tags', 'QrImages']);
        $this->Authorization->authorize($qrCode);

        $this->set(compact('qrCode'));
        $this->viewBuilder()->setOption('serialize', ['qrCode']);

        return null;
    }

    /**
     * Add method
     *
     * @return ?\Cake\Http\Response Redirects on successful add, renders view otherwise.
     */
    public function add(): ?Response
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
                    '_ext' => $this->getRequest()->getParam('_ext'),
                ]);
            }
            $this->Flash->error(__('The qr code could not be saved. Please, try again.'));
        }

        $errors = $qrCode->getErrors();
        $sources = $this->QrCodes->Sources->find('active')->find('list', limit: 200)->all();
        $tags = $this->QrCodes->Tags->find('active')->find('list', limit: 200)->all();

        $this->set(compact('qrCode', 'sources', 'tags', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['qrCode', 'sources', 'tags', 'errors']);

        return null;
    }

    /**
     * Edit method
     *
     * @param ?string $id QR Code id.
     * @return ?\Cake\Http\Response Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null): ?Response
    {
        $this->request->allowMethod(['get', 'put']);

        $qrCode = $this->QrCodes->get((int)$id, contain: ['Tags']);
        $this->Authorization->authorize($qrCode);

        if ($this->request->is('put')) {
            debug($this->request->getData());
            $qrCode = $this->QrCodes->patchEntity($qrCode, $this->request->getData());
            debug($qrCode);
            exit;
            if ($this->QrCodes->save($qrCode)) {
                $this->Flash->success(__('The qr code has been saved.'));

                return $this->redirect([
                    'action' => 'view',
                    $qrCode->id,
                    '_ext' => $this->getRequest()->getParam('_ext'),
                ]);
            }
            $this->Flash->error(__('The qr code could not be saved. Please, try again.'));
        }

        $errors = $qrCode->getErrors();
        $sources = $this->QrCodes->Sources->find('active')->find('list', limit: 200)->all();
        $tags = $this->QrCodes->Tags->find('active')->find('list', limit: 200)->all();

        $this->set(compact('qrCode', 'sources', 'tags', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['qrCode', 'sources', 'tags', 'errors']);

        return null;
    }

    /**
     * Delete method
     *
     * @param ?string $id QR Code id.
     * @return ?\Cake\Http\Response Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null): ?Response
    {
        $this->request->allowMethod(['delete']);

        $qrCode = $this->QrCodes->get((int)$id);
        $this->Authorization->authorize($qrCode);

        if ($this->QrCodes->delete($qrCode)) {
            $this->Flash->success(__('The qr code `{0}` has been deleted.', [
                $qrCode->name,
            ]));
        }

        return $this->redirect([
            'action' => 'index',
            '_ext' => $this->getRequest()->getParam('_ext'),
        ]);
    }
}
