<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class TagsController extends AppController
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
        if (in_array($action, ['view', 'edit', 'delete'])) {
            $pass = $this->request->getParam('pass');
            if (empty($pass) || !isset($pass['0'])) {
                $event->stopPropagation();
                throw new NotFoundException('Unknown ID');
            }
        }

        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return ?\Cake\Http\Response Renders view
     */
    public function index(): ?Response
    {
        $this->request->allowMethod(['get']);

        $query = $this->Tags->find('all')
            ->find('search', search: $this->request->getQueryParams());
        $query = $this->Authorization->applyScope($query);
        $tags = $this->paginate($query);

        $this->set(compact('tags'));
        $this->viewBuilder()->setOption('serialize', ['tags']);

        return null;
    }

    /**
     * View method
     *
     * @param ?string $id Tag id.
     * @return ?\Cake\Http\Response Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null): ?Response
    {
        $this->request->allowMethod(['get']);

        $tag = $this->Tags->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($tag);

        $this->set(compact('tag'));
        $this->viewBuilder()->setOption('serialize', ['tag']);

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

        $tag = $this->Tags->newEmptyEntity();
        $this->Authorization->authorize($tag);

        if ($this->request->is('post')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            $tag->user_id = $this->getActiveUser('id');
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The tag has been saved.'));

                return $this->redirect([
                    'action' => 'view',
                    $tag->id,
                    '_ext' => $this->getRequest()->getParam('_ext'),
                ]);
            }
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }

        $errors = $tag->getErrors();
        $qrCodes = $this->Tags->QrCodes->find('active')->find('list', limit: 200)->all();

        $this->set(compact('tag', 'qrCodes', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['tag', 'qrCodes', 'errors']);

        return null;
    }

    /**
     * Edit method
     *
     * @param ?string $id Tag id.
     * @return ?\Cake\Http\Response Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null): ?Response
    {
        $this->request->allowMethod(['get', 'put']);

        $tag = $this->Tags->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($tag);

        if ($this->request->is('put')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The tag has been saved.'));

                return $this->redirect([
                    'action' => 'view',
                    $tag->id,
                    '_ext' => $this->getRequest()->getParam('_ext'),
                ]);
            }
            $this->Flash->error(__('The tag could not be saved. Please, try again.'));
        }

        $errors = $tag->getErrors();
        $qrCodes = $this->Tags->QrCodes->find('active')->find('list', limit: 200)->all();

        $this->set(compact('tag', 'qrCodes', 'errors'));
        $this->viewBuilder()->setOption('serialize', ['tag', 'qrCodes', 'errors']);

        return null;
    }

    /**
     * Delete method
     *
     * @param ?string $id Tag id.
     * @return ?\Cake\Http\Response Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null): ?Response
    {
        $this->request->allowMethod(['delete', 'post']);

        $tag = $this->Tags->get((int)$id);
        $this->Authorization->authorize($tag);

        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('The tag `{0}` has been deleted.', [
                $tag->name,
            ]));
        }

        return $this->redirect([
            'action' => 'index',
            '_ext' => $this->getRequest()->getParam('_ext'),
        ]);
    }
}
