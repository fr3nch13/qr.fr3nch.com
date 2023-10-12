<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/dashboard');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
    <section>
        <div class="row g-3 g-md-5 align-items-end mb-5">
            <div class="col-md-6">
                <h1><?= __('QR Codes') ?></h1>
            </div>

            <div class="col-md-6 text-md-end">
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
                                <?php if ($this->Search->isSearch()) : ?>
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
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card bg-opaque-white">
                    <div class="card-body bg-white">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.name',
                                            [
                                                'asc' => __('Name') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Name') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.qrkey',
                                            [
                                                'asc' => __('Key') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Key') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.is_active',
                                            [
                                                'asc' => __('Active') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Active') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.hits',
                                            [
                                                'asc' => __('Hits') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Hits') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($qrCodes as $qrCode) : ?>
                                    <tr  class="<?= $qrCode->is_active ? '' : 'text-muted' ?>">
                                        <td>
                                            <?= $qrCode->name ?>
                                        </td>
                                        <td>
                                            <?= $qrCode->qrkey ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($qrCode->is_active) {
                                                echo '<i class="bi bi-check2 text-success fs-6"></i>';
                                            } else {
                                                echo '<i class="bi bi-x fs-6"></i>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill"><?= $qrCode->hits ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a
                                                    class="btn btn-sm btn-white btn-icon rounded-circle"
                                                    href="#"
                                                    role="button"
                                                    id="actions<?= $qrCode->id ?>"
                                                    data-bs-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i></i>
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
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
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
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            <div class="widget text-end">
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
            <!-- Tags
            <div class="widget">
                <span class="d-flex eyebrow text-muted mb-2">Tags</span>
            </div> -->

        </div>
    </div>
    <?php $this->end(); // offcanvas ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
