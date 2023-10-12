<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */

if (!$this->getRequest()->is('ajax')) {
    $this->extend('/Admin/QrCodes/details');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="card bg-opaque-white">
    <div class="card-body bg-white">
        <div class="row">
            <div class="col-lg-4 order-2 order-lg-1">
                <img
                    class="img-fluid"
                    src="<?= $this->Url->build([
                        'plugin' => false,
                        'prefix' => false,
                        'controller' => 'QrCodes',
                        'action' => 'show',
                        $qrCode->id,
                        '?' => ['bypass' => true]
                    ]) ?>"
                    alt="<?= __('The QR Code'); ?>">
            </div>
            <div class="col-lg-8 order-1 order-lg-2">
                <dl class="row">
                    <dt class="col-3"><?= __('Key') ?></dt>
                    <dd class="col-9"><?= h($qrCode->qrkey) ?> </dd>

                    <dt class="col-3"><?= __('Hits') ?></dt>
                    <dd class="col-9"><span class="badge bg-light text-dark rounded-pill"><?= $qrCode->hits ?></span> </dd>

                    <dt class="col-3"><?= __('Last Hit') ?></dt>
                    <dd class="col-9"><?= h($qrCode->last_hit) ?> </dd>

                    <dt class="col-4 col-md-3"><?= __('Created') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($qrCode->created) ?> </dd>

                    <dt class="col-3"><?= __('Source') ?></dt>
                    <dd class="col-9"><?= $qrCode->hasValue('source') ? $this->Html->link(
                        $qrCode->source->name,
                        [
                            'controller' => 'Sources',
                            'action' => 'view',
                            $qrCode->source->id
                        ]) : '' ?> </dd>

                    <dt class="col-3"><?= __('Owner') ?></dt>
                    <dd class="col-9"><?= $qrCode->hasValue('user') ? $this->Html->link(
                        $qrCode->user->name,
                        [
                            'controller' => 'Users',
                            'action' => 'view',
                            $qrCode->user->id
                        ]) : '' ?> </dd>

                    <dt class="col-3"><?= __('URL') ?></dt>
                    <dd class="col-9"><a
                        href="<?= $qrCode->url ?>"
                        target="tab-<?=$qrCode->id?>"
                        ><?= $qrCode->url ?></a> </dd>
                </dl>
                <div class="row">
                    <p class="text-secondary mb-3"><?= $this->Text->autoParagraph(h($qrCode->description)) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
