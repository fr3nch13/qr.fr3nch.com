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

<section>
    <div class="row">
        <div class="col">
            <h2><?= $qrCode->name ?></h2>
        </div>
    </div>

    <div class="row">
        <div class="col">
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

</section>

<?= $this->Template->templateComment(false, __FILE__); ?>
