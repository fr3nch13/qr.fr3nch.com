<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}

$this->assign('page_title', $qrCode->name);

if ($qrCode->id) {
    $this->start('page_options');

    $controller = $this->getRequest()->getParam('controller');
    $action = $this->getRequest()->getParam('action');
    $tabs = [
        'QrCodes.view' => [__('Details'), [
            'controller' => 'QrCodes',
            'action' => 'view',
            $qrCode->id,
        ]],
        'QrCodes.edit' => [__('Edit'), [
            'controller' => 'QrCodes',
            'action' => 'edit',
            $qrCode->id,
        ]],
        'QrImages.qrCode' => [__('Images'), [
            'controller' => 'QrImages',
            'action' => 'qrCode',
            $qrCode->id,
        ]],
        'QrCodes.download' => [__('Download QR'), [
            'plugin' => false,
            'prefix' => false,
            'controller' => 'QrCodes',
            'action' => 'show',
            $qrCode->id,
            '?' => ['download' => true],
        ]],
    ];

    ?>
    <ul class="list-inline">
    <?php foreach ($tabs as $k => $tab) :
        $options = [
            'class' => 'underline text-black',
        ];
        if ($k === $controller . '.' . $action) {
            $options['class'] .= ' text-decoration-underline active';
            $options['aria-current'] = 'page';
        }
        ?>
        <li class="list-inline-item ms-2">
            <?= $this->Html->link($tab[0], $tab[1], $options) ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php
    $this->end(); // page_options
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<?= $this->fetch('content'); ?>

<?= $this->Template->templateComment(false, __FILE__); ?>
