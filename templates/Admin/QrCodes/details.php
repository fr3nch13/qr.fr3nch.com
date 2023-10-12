<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}
$action = $this->getRequest()->getAttribute('action');
print_r($action);
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<h2><?= $qrCode->name ?></h2>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <?= $this->Html->link(__('Details'), [
        'controler' => 'QrCodes',
        'action' => 'view',
        $qrCode->id,
    ], [
        'class' => 'nav-link',
    ]) ?>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled">Disabled</a>
  </li>
</ul>

<?= $this->fetch('content'); ?>

<?= $this->Template->templateComment(false, __FILE__); ?>
