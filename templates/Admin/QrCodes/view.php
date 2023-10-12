<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}

$this->assign('page_title', $qrCode->name);
$this->assign('tabs', [
    'view' => [
        __('Details'),
        ['controler' => 'QrCodes', 'action' => 'view', $qrCode->id],
        ['class' => 'active'],
    ],
    'edit' => [
        __('Edit'),
        ['controler' => 'QrCodes', 'action' => 'edit', $qrCode->id]
    ],
    'images' => [
        __('Images'),
        ['controler' => 'QrImages', 'action' => 'qrCode', $qrCode->id]
    ],
]);
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
