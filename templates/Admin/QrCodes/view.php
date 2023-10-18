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
    <div class="col-lg-4 order-4 order-lg-1">
        <h5 class="d-block d-lg-none"><?= __('Code') ?></h5>
        <img
            class="img-fluid"
            src="<?= $this->Url->build([
                'plugin' => false,
                'prefix' => false,
                'controller' => 'QrCodes',
                'action' => 'show',
                $qrCode->id,
                '?' => ['bypass' => true],
            ]) ?>"
            alt="<?= __('The QR Code'); ?>">
    </div>
    <div class="col-lg-8 order-1 order-lg-2">
        <dl class="row">
            <dt class="col-4 col-md-3"><?= __('Key') ?></dt>
            <dd class="col-8 col-md-9"><?= h($qrCode->qrkey) ?> </dd>

            <dt class="col-4 col-md-3"><?= __('Hits') ?></dt>
            <dd class="col-8 col-md-9"><span
                class="badge bg-light text-dark rounded-pill"><?= $qrCode->hits ?></span> </dd>

            <dt class="col-4 col-md-3"><?= __('Last Hit') ?></dt>
            <dd class="col-8 col-md-9"><?= h($qrCode->last_hit ? $qrCode->last_hit->format('M d, Y') : null) ?> </dd>

            <dt class="col-4 col-md-3"><?= __('Created') ?></dt>
            <dd class="col-8 col-md-9"><?= h($qrCode->created ? $qrCode->created->format('M d, Y') : null) ?> </dd>

            <dt class="col-4 col-md-3"><?= __('Source') ?></dt>
            <dd class="col-8 col-md-9"><?= $qrCode->hasValue('source') ? $this->Html->link(
                $qrCode->source->name,
                [
                    'controller' => 'Sources',
                    'action' => 'view',
                    $qrCode->source->id,
                ]
            ) : '' ?> </dd>

            <dt class="col-4 col-md-3"><?= __('Owner') ?></dt>
            <dd class="col-8 col-md-9"><?= $qrCode->hasValue('user') ? $this->Html->link(
                $qrCode->user->name,
                [
                    'controller' => 'Users',
                    'action' => 'view',
                    $qrCode->user->id,
                ]
            ) : '' ?> </dd>

            <dt class="col-4 col-md-3"><?= __('URL') ?></dt>
            <dd class="col-8 col-md-9"><a
                href="<?= $qrCode->url ?>"
                target="tab-<?=$qrCode->id?>"
                ><?= $qrCode->url ?></a> </dd>
        </dl>
    </div>
    <div class="col-lg-12 order-2 order-lg-3 my-2">
        <h5><?= __('Description') ?></h5>
        <div class="text-secondary mb-3"><?= $this->Text->autoParagraph(h($qrCode->description)) ?></div>
    </div>
    <div class="col-lg-12 order-3 order-lg-4 my-2">
        <h5><?= __('Tags') ?></h5>
        <?php foreach ($qrCode->tags as $tag) : ?>
            <span
                class="
                my-1 my-md-2 mx-1 mx-md-2
                btn btn-sm btn-light btn-outline-secondary
                rounded-pill" role="button"><?= $tag->name ?></span>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
