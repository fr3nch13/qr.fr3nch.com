<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
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
        //Allow anyone to view the list of tags, and their details page.
        $this->Authentication->addUnauthenticatedActions(['index']);

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
            ->matching('QrCodes')
            ->find('search', search: $this->request->getQueryParams());
        $query = $this->Authorization->applyScope($query);
        $tags = $this->paginate($query);

        $this->set(compact('tags'));
        $this->viewBuilder()->setOption('serialize', ['tags']);

        return null;
    }
}
