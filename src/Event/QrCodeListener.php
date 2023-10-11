<?php
declare(strict_types=1);

namespace App\Event;

use App\Model\Entity\QrCode;
use App\Model\Table\QrCodesTable;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\I18n\DateTime;

/**
 * Handles events related to Qr Code Entities.
 */
class QrCodeListener implements EventListenerInterface
{
    /**
     * Used to map the event names to the specific methods.
     *
     * @return array<string, string> The mapped events to the methods.
     */
    public function implementedEvents(): array
    {
        return [
            'QrCode.onHit' => 'registerHit',
        ];
    }

    /**
     * When a QR Code is scanned, we should register it.
     *
     * @param \Cake\Event\Event<mixed> $event The triggered event.
     * @param \App\Model\Entity\QrCode $qrCode The Entity we need to update.
     * @param bool If the Hit was registered as expected.
     */
    public function registerHit(Event $event, QrCode $qrCode): bool
    {
        $subject = $event->getSubject();

        // means it is an object that uses the LocatorAwareTrait.
        if (method_exists($subject, 'getTableLocator')) {
            $config = $subject->getTableLocator()->exists('QrCodes') ? [] : ['className' => QrCodesTable::class];
            /** @var \App\Model\Table\QrCodesTable $QrCodes */
            $QrCodes = $subject->getTableLocator()->get('QrCodes', $config);

            $qrCode->hits = $qrCode->hits + 1;
            $qrCode->last_hit = new DateTime();

            debug($qrCode);
            exit;

            return $QrCodes->save($qrCode) ? true : false;
        }

        return false;
    }
}
