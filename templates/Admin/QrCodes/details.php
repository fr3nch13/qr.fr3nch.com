<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}
?>
tabs go here.
<?= $this->Template->templateComment(true, __FILE__); ?>

<?= $this->fetch('content');

<?= $this->Template->templateComment(false, __FILE__); ?>
