<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var iterable<\App\Model\Entity\QrImage> $qrImages
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/index');
}
// TODO: build out this page.
// This will be the page where you can manage the images related to a QR Code.
// labels: images, templates
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="qrImages index content">
    <h2>List of images for <?= $qrCode->name; ?></h2>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
