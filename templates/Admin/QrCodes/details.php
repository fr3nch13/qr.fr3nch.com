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
        'QrCodes.edit' => [__('Edit'), [
            'controller' => 'QrCodes',
            'action' => 'edit',
            $qrCode->id,
        ]],
        'QrCodes.delete' => [__('Delete'), [
            'controller' => 'QrCodes',
            'action' => 'delete',
            $qrCode->id,
        ]],
    ];

    ?>
    <ul class="nav justify-content-end">
    <?php foreach ($tabs as $k => $tab) :
        $options = [
            'class' => 'nav-link underline pe-0',
        ];

        if ($k === $controller . '.' . $action) {
            $options['class'] .= ' active';
            $options['aria-current'] = 'page';
        }

        if ($action == 'delete') {
            $options['class'] .= ' text-red';
            $options['confirm'] = __('Are you sure you want to delete # {0}?', $qrCode->qrkey);
            $link = $this->Form->postLink($tab[0], $tab[1], $options);
        } else {
            $options['class'] .= ' text-black';
            $link = $this->Html->link($tab[0], $tab[1], $options);
        }
        ?>
        <li class="nav-item">
            <?= $link ?>
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
