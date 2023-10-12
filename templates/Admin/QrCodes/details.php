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
<div class="container mt-5">
    <div class="row align-items-end mb-2">
        <div class="col-lg-6 mb-md-2 mb-lg-0">
            <h2><?= $qrCode->name ?></h2>
        </div>

        <div class="col-lg-6 text-md-end">
            <ul class="list-inline">
            <?php foreach ($tabs as $k => $tab) :
                $options = [
                    'class' => 'underline text-black',
                ];
                if ($k === $action) {
                    $options['class'] .= ' active';
                    $options['aria-current'] = 'page';
                }
                ?>
                <li class="list-inline-item ms-2">
                    <?= $this->Html->link($tab[0], $tab[1], $options) ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card bg-opaque-white">
                <div class="card-body bg-white">
                    <?= $this->fetch('content'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
