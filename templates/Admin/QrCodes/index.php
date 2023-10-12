<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('QR Codes'));

$this->start('page_options');
?>
<ul class="list-inline">
    <li class="list-inline-item ms-2">
        <?= $this->Html->link(__('Add a QR Code'), [
            'controller' => 'QrCodes',
            'action' => 'add',
        ], [
            'class' => 'underline text-black',
        ]); ?>
    </li>

    <li class="list-inline-item ms-2">
        <a
            class=" underline text-black position-relative"
            data-bs-toggle="offcanvas"
            href="#offcanvasFilter"
            role="button"
            aria-controls="offcanvasFilter"><?= __('Filters') ?>
            <?php if ($this->Search->isSearch() || $this->Paginator->param('sort')) : ?>
            <span
                class="
                    position-absolute
                    top-0
                    start-100
                    translate-middle
                    rounded-pill
                    text-red
                    p-1">
                <i class="bi bi-check filtering-applied"></i>
                <span class="visually-hidden"><?= __('Filters are applied') ?></span>
            </span>
            <?php endif; ?>
        </a>
    </li>
</ul>
<?php
$this->end(); // page_options
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="container bg-white">
    <?php foreach ($qrCodes as $qrCode) : ?>
    <div class="row border-bottom py-1">
        <div class="col-10 qr-details <?= $qrCode->is_active ? '' : 'text-muted' ?>"">
            <h5><?= $qrCode->name ?> <small class="h6 text-muted"><?= $qrCode->qrkey ?></small></h5>
            <?php
            if ($qrCode->is_active) {
                echo '<span class="badge bg-primary rounded-pill"><i class="bi bi-check2 fs-4"></i></span>';
            } else {
                echo '<span class="badge bg-light text-dark rounded-pill"><i class="bi bi-x fs-4"></i></span>';
            }
            ?>
            <span class="badge bg-light text-dark rounded-pill"><?= $qrCode->hits ?></span>
        </div>
        <div class="col-1 qr-actions">
            <div class="dropdown">
                <a
                    class="btn btn-sm btn-white btn-icon rounded-circle"
                    href="#"
                    role="button"
                    id="actions<?= $qrCode->id ?>"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="actions<?= $qrCode->id ?>">
                    <li><?= $this->Html->link(__('Details'), [
                        'controller' => 'QrCodes',
                        'action' => 'view',
                        $qrCode->id,
                    ], ['class' => 'dropdown-item']) ?></li>
                    <li><?= $this->Html->link(__('Edit'), [
                        'controller' => 'QrCodes',
                        'action' => 'edit',
                        $qrCode->id,
                    ], ['class' => 'dropdown-item']) ?></li>
                    <li><?= $this->Html->link(__('Images'), [
                        'controller' => 'QrImages',
                        'action' => 'qrCode',
                        $qrCode->id,
                    ], ['class' => 'dropdown-item']) ?></li>
                    <li><?= $this->Html->link(__('Toggle Active'), [
                        'controller' => 'QrCodes',
                        'action' => 'toggleActive',
                        $qrCode->id,
                    ], ['class' => 'dropdown-item']) ?></li>
                </ul>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <nav aria-label="Pagination" class="text-center">
        <ul class="pagination">
            <?= $this->Paginator->first('&laquo;', ['label' => 'First']) ?>
            <?= $this->Paginator->prev('<', ['label' => 'Previous']) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('>', ['label' => 'Next']) ?>
            <?= $this->Paginator->last('&laquo;', ['label' => 'Last']) ?>
        </ul>
    </nav>
</div>

<?php $this->start('offcanvas') ?>
<?= $this->Template->objectComment('OffCanvas/filters') ?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasFilterLabel"><?= __('Filters') ?></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?= $this->Form->create(null, [
            'valueSources' => 'query',
        ]); ?>
        <!-- Search Form -->
        <div class="widget mb-2">
            <?= $this->Form->control('q', [
                'spacing' => 'mb-2',
                'label' => [
                    'floating' => true,
                    'text' => __('What are you looking for ?'),
                ],
            ]) ?>
        </div>
        <!-- Tags -->
        <div class="widget mb-2">
            <?= $this->Form->control('t', [
                'options' => $tags,
                'empty' => '[select]',
                'label' => [
                    'floating' => true,
                    'text' => __('Tags'),
                ],
            ]); ?>
        <!-- Sources -->
        <div class="widget mb-2">
            <?= $this->Form->control('s', [
                'options' => $sources,
                'empty' => '[select]',
                'label' => [
                    'floating' => true,
                    'text' => __('Sources'),
                ],
            ]); ?>
        </div>
        </div>
        <div class="widget mb-2 text-end">
            <div class="btn-group" role="group" aria-label="Filter Options">
                <?php if ($this->Search->isSearch()) : ?>
                    <?= $this->Search->resetLink(__('Clear'), [
                    'class' => 'btn btn-sm btn-light',
                ]); ?>
                <?php endif; ?>
                <?= $this->Form->button('Filter', [
                    'type' => 'submit',
                    'class' => 'btn btn-sm btn-primary',
                    'escapeTitle' => false,
                ]); ?>
            </div>
        </div>
        <?= $this->Form->end(); ?>
        <div class="widget pb-2 mb-2 border-bottom">
            <h5 class="offcanvas-title"><?= __('Sort') ?></h5>
        </div>

        <div class="widget mb-2">
        <?php
            $sorts = [
                'QrCodes.name' => __('Name'),
                'QrCodes.qrkey' => __('Key'),
                'QrCodes.hits' => __('Hits'),
                'QrCodes.is_active' => __('Active'),
            ];
            echo $this->element('filter/sort_links', [
                'sorts' => $sorts,
            ]);
        ?>
        </div>


    </div>
</div>
<?php $this->end(); // offcanvas ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
