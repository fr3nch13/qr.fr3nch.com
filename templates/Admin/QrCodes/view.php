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
<div class="row">
    <div class="col-lg-8">
        <dl class="row">
            <dt class="col-sm-3"><?= __('Key') ?></dt>
            <dd class="col-sm-9"><?= h($qrCode->qrkey) ?> </dd>

            <dt class="col-sm-3"><?= __('Hits') ?></dt>
            <dd class="col-sm-9"><?= h($qrCode->last_hit) ?> </dd>

            <dt class="col-sm-3"><?= __('Last Hit') ?></dt>
            <dd class="col-sm-9"><?= h($qrCode->last_hit) ?> </dd>

            <dt class="col-sm-3"><?= __('Created') ?></dt>
            <dd class="col-sm-9"><?= h($qrCode->created) ?> </dd>

            <dt class="col-sm-3"><?= __('Source') ?></dt>
            <dd class="col-sm-9"><?= $qrCode->hasValue('source') ? $this->Html->link(
                $qrCode->source->name,
                [
                    'controller' => 'Sources',
                    'action' => 'view',
                    $qrCode->source->id
                ]) : '' ?> </dd>

            <dt class="col-sm-3"><?= __('Owner') ?></dt>
            <dd class="col-sm-9"><?= $qrCode->hasValue('user') ? $this->Html->link(
                $qrCode->user->name,
                [
                    'controller' => 'Users',
                    'action' => 'view',
                    $qrCode->user->id
                ]) : '' ?> </dd>

            <dt class="col-sm-3"><?= __('URL') ?></dt>
            <dd class="col-sm-9"><?= $qrCode->url ?> </dd>
        </dl>
    </div>
    <div class="col-lg-4">
        <img
            class="img-fluid"
            src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>"
            alt="<?= __('The QR Code'); ?>">
    </div>
</div>


<div class="product-view">
sdfasdfasdf
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
