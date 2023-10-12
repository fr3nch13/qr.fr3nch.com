<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}
$action = $this->getRequest()->getParam('action');

$tabs = [
    'view' => [__('Details'), ['controller' => 'QrCodes', 'action' => 'view', $qrCode->id]],
    'edit' => [__('Edit'), ['controller' => 'QrCodes', 'action' => 'edit', $qrCode->id]],
    'qrCode' => [__('Images'), ['controller' => 'QrImages', 'action' => 'qrCode', $qrCode->id]],
];
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<h2><?= $qrCode->name ?></h2>
<h3><?= $action ?></h3>
<ul class="nav nav-tabs">
<?php foreach ($tabs as $k => $tab) :
    $options = [
        'class' => 'nav-link',
    ];
    if ($k === $action) {
        $options['class'] .= ' active';
        $options['aria-current'] = 'page';
    }
    ?>
    <li class="nav-item">
        <?= $this->Html->link($tab[0], $tab[1], $options) ?>
    </li>
<?php endforeach; ?>
</ul>

<?= $this->fetch('content'); ?>

<?= $this->Template->templateComment(false, __FILE__); ?>
