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

<img
    class="img-fluid"
    src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>"
    alt="<?= __('The QR Code'); ?>">

<div class="product-view">
sdfasdfasdf
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
