<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
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
        // allowed actions for anyone.
        $this->Authentication->addUnauthenticatedActions(['forward', 'show', 'index', 'view']);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['forward', 'show', 'view'])) {
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
     * @param ?string $key The QR Code key to lookup.
     * @return \Cake\Http\Response|null The response object.
     */
    public function forward(?string $key = null): ?Response
    {
        $this->request->allowMethod(['get']);

        /** @var \App\Model\Entity\QrCode $qrCode */
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

        // if it's inactive, redirect to the index action.
        if (!$qrCode->is_active) {
            $this->Flash->warning(__('This QR Code is inactive.', [
                $key,
            ]));

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $event = new Event('QrCode.onHit', $this, ['qrCode' => $qrCode]);
        $this->getEventManager()->dispatch($event);

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

        // color code from the entity
        $path = $qrCode->path;

        // light or dark code
        if ($this->request->getQuery('l') !== null) {
            if ($this->request->getQuery('l')) {
                // light code
                $path = $qrCode->path_light;
            } else {
                // dark code
                $path = $qrCode->path_dark;
            }
        }

        // color from the query string.
        if ($this->request->getQuery('c') !== null) {
            $path = $qrCode->getImagePath($this->request->getQuery('c'));
        }

        if (!$path) {
            throw new NotFoundException('Unable to find the image file.');
        }

        $fileOptions = [];

        // look for a download request.
        // anything truthy
        if ($this->request->getQuery('download')) {
            $fileOptions = [
                'download' => true,
                'name' => 'QR-' . $qrCode->qrkey . '-' . $qrCode->color_active . '.svg',
            ];
        }

        $response = $this->response->withFile($path, $fileOptions);
        $modified = date('Y-m-d H:i:s.', filemtime($path) ?: null);
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

        // look for an incoming key in the query,
        // and redirect it to the forward action.
        if ($this->request->getQuery('k')) {
            $this->redirect([
                'action' => 'forward',
                $this->request->getQuery('k'),
            ]);
        }

        $query = $this->QrCodes->find('active')
            ->find('search', search: $this->request->getQueryParams())
            ->contain([
                'Sources',
                'QrImages' => function (SelectQuery $q) {
                    // only include the first active one
                    return $q
                        ->find('active')
                        ->find('orderFirst');
                }]);
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
}
