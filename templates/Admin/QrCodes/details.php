<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}

$this->assign('page_title', $qrCode->name);
$this->assign('title', $this->fetch('page_title'));

if ($qrCode->id) {
    $this->start('page_options');

    $here = $this->getRequest()->getParam('controller') .
        '.' .
        $this->getRequest()->getParam('action');
    ?>
    <ul class="nav justify-content-end">
        <li class="nav-item">
            <?= $this->Html->link(__('Details'), [
                'controller' => 'QrCodes',
                'action' => 'view',
                $qrCode->id,
            ], [
                'class' => 'nav-link pe-0' .
                ($here == 'QrCodes.view' ? ' active' : ''),
            ]) ?>
        </li>
        <li class="nav-item">
            <?= $this->Html->link(__('Images'), [
                'controller' => 'QrImages',
                'action' => 'qrCode',
                $qrCode->id,
            ], [
                'class' => 'nav-link pe-0' .
                ($here == 'QrImages.qrCode' ? ' active' : ''),
            ]) ?>
        </li>
        <li class="nav-item dropdown dropdown-hover">
            <a
                class="nav-link"
                role="button"
                id="dropdownActions"
                data-bs-toggle="dropdown"
                aria-expanded="false"><?= __('Actions') ?> <?= $this->Html->icon('chevron-down') ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownActions">
                <li><?= $this->Html->link(__('Edit'), [
                    'controller' => 'QrCodes',
                    'action' => 'edit',
                    $qrCode->id,
                ], [
                    'class' => 'dropdown-item' .
                        ($here == 'QrCodes.edit' ? ' active' : ''),
                ]) ?></li>
                <li><?= $this->Html->link(__('Download Color'), [
                    'plugin' => false,
                    'prefix' => false,
                    'controller' => 'QrCodes',
                    'action' => 'show',
                    $qrCode->id,
                    '?' => [
                        'download' => true,
                    ],
                ], [
                    'class' => 'dropdown-item',
                ]) ?></li>
                <li><?= $this->Html->link(__('Download Dark'), [
                    'plugin' => false,
                    'prefix' => false,
                    'controller' => 'QrCodes',
                    'action' => 'show',
                    $qrCode->id,
                    '?' => [
                        'l' => false,
                        'download' => true,
                    ],
                ], [
                    'class' => 'dropdown-item',
                ]) ?></li>
                <li><?= $this->Html->link(__('Download Light'), [
                    'plugin' => false,
                    'prefix' => false,
                    'controller' => 'QrCodes',
                    'action' => 'show',
                    $qrCode->id,
                    '?' => [
                        'l' => true,
                        'download' => true,
                    ],
                ], [
                    'class' => 'dropdown-item',
                ]) ?></li>
                <li><?= $this->Html->link(__('Regenerate'), [
                    'controller' => 'QrCodes',
                    'action' => 'show',
                    $qrCode->id,
                    '?' => ['regen' => true],
                ], [
                    'class' => 'dropdown-item',
                ]) ?></li>
                <li><?= $this->Form->postLink(__('Delete'), [
                    'controller' => 'QrCodes',
                    'action' => 'delete',
                    $qrCode->id,
                ], [
                    'class' => 'dropdown-item text-red',
                    'confirm' => __('Are you sure you want to delete: {0}?', $qrCode->qrkey),
                ]); ?></li>
            </ul>
        </li>
    </ul>
    <?php
    $this->end(); // page_options
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<div class="card bg-opaque-white">
    <?php if (!$qrCode->is_active) : ?>
    <div class="ribbon red"><span><?= __('Inactive') ?></span></div>
    <?php endif; ?>
    <div class="card-body p-2 p-lg-5">
        <?= $this->fetch('content'); ?>
    </div>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
