<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var iterable<\App\Model\Entity\QrImage> $qrImages
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}
// TODO: build out this page.
// This will be the page where you can manage the images related to a QR Code.
// labels: images, templates
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="qrImages index content">
    <h2>List of images for <?= $qrCode->name; ?></h2>
    <div class="qr_images">
        <?php foreach ($qrImages as $qrImage) : ?>
        <div class="qr_image">
            <?= $this->Template->objectComment('QrImages/entity') ?>
            <?= $this->Template->objectComment('QrImages/entity/' . ($qrImage->is_active ? 'active' : 'inactive')) ?>
            <img class="<?= ($qrImage->is_active ? 'active' : 'inactive') ?>" src="<?= $this->Url->build([
                'controller' => 'QrImages',
                'action' => 'show',
                $qrImage->id,
                ]) ?>" alt="<?= $qrImage->name ?>">
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
