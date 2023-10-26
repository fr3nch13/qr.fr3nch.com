<?php
declare(strict_types=1);

namespace App\Event;

use App\Model\Entity\QrCode;
use App\Model\Table\QrCodesTable;
use Cake\Event\Event;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Fr3nch13\Stats\Event\StatsListener;

/**
 * Handles events related to Qr Code Entities.
 */
class QrCodeListener extends StatsListener
{
    use LocatorAwareTrait;

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
     * @param \Cake\Event\Event $event The triggered event.
     * @param \App\Model\Entity\QrCode $qrCode The Entity we need to update.
     * @return bool If the Hit was registered as expected.
     */
    public function registerHit(Event $event, QrCode $qrCode): bool
    {
        // track if any qr codes are hot
        parent::recordCount($event, 'QrCode.hits');

        // track the specific qr code
        parent::recordCount($event, 'QrCode.hits.' . $qrCode->id);

        $config = $this->getTableLocator()->exists('QrCodes') ? [] : ['className' => QrCodesTable::class];
        /** @var \App\Model\Table\QrCodesTable $QrCodes */
        $QrCodes = $this->getTableLocator()->get('QrCodes', $config);

        $qrCode->hits = $qrCode->hits + 1;
        $qrCode->last_hit = new DateTime();

        return $QrCodes->save($qrCode) ? true : false;
    }
}
