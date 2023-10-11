<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
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
     *
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        // allowed actions for anyone.
        $this->Authentication->addUnauthenticatedActions(['show']);

        // make sure we have an ID where needed.
        $action = $this->request->getParam('action');
        // admin actions
        if (in_array($action, ['show'])) {
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
     * @param ?string $id Image id.
     * @return \Cake\Http\Response Shows the image to the browser
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function show(?string $id = null): Response
    {
        $this->request->allowMethod(['get']);

        $qrImage = $this->QrImages->get((int)$id, contain: ['QrCodes']);
        $this->Authorization->authorize($qrImage);

        if (!$qrImage->path) {
            throw new NotFoundException('Unable to find the image file.');
        }

        $response = $this->response->withFile($qrImage->path);
        $modified = date('Y-m-d H:i:s.', filemtime($qrImage->path) ?: null);
        $response = $response->withModified($modified);

        // allow browser and proxy caching when debug is off
        if (!Configure::read('debug')) {
            $response = $response->withSharable(true, 3600);
            $response = $response->withExpires('+5 minutes');
        }

        return $response;
    }
}
