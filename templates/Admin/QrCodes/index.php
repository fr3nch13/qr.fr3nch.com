<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('QR Codes'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="card bg-opaque-white">
    <div class="card-body p-2 p-lg-5">
        <div class="row justify-content-between">
        <?php foreach ($qrCodes as $qrCode) : ?>
            <div class="col-md-6">
                <div class="card mb-2 shadow-sm">

                    <?= $this->Template->objectComment('QrImages/entity'); ?>
                    <?php if (!$qrCode->is_active) : ?>
                    <div class="ribbon red"><span><?= __('Inactive') ?></span></div>
                        <?= $this->Template->objectComment('QrImages/entity/inactive'); ?>
                    <?php else : ?>
                        <?= $this->Template->objectComment('QrImages/entity/active'); ?>
                    <?php endif; ?>

                    <?php
                    $bgUrl = $this->Url->build([
                        'prefix' => false,
                        'action' => 'show',
                        $qrCode->id,
                        '?' => ['thumb' => 'md'],
                    ]);
                    if (!empty($qrCode->qr_images)) {
                        $bgUrl = $this->Url->build([
                            'prefix' => false,
                            'controller' => 'QrImages',
                            'action' => 'show',
                            $qrCode->qr_images[0]->id,
                            '?' => ['thumb' => 'md'],
                        ]);
                    }
                    ?>
                    <figure
                        class="background background-overlay"
                        style="background-image: url('<?= $bgUrl ?>')"></figure>

                    <div class="card-content level-2">
                        <div class="card-title text-center text-white"><?= $qrCode->name ?></div>
                        <div class="card-body text-white d-block py-5 py-md-10">
                            <div class="row">
                                <div class="col mb-2">
                                    <span class="
                                        badge
                                        bg-light
                                        text-dark
                                        rounded-pill
                                        "><i
                                            class="bi bi-qr-code-scan"></i>
                                            <?= $qrCode->hits ?>
                                            <?= $qrCode->last_hit ? ' - ' . $qrCode->last_hit->format('M d, Y') : null ?>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <span class="
                                            badge
                                            bg-light
                                            text-dark
                                            rounded-pill
                                            "><?= $qrCode->qrkey ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-muted p-0 btn-group">
                        <?= $this->Html->link(__('View'), [
                            'action' => 'view',
                            $qrCode->id,
                        ], [
                        'class' => 'btn btn-sm btn-light',
                        ]) ?>

                        <?= $this->Html->link(__('Edit'), [
                            'action' => 'edit',
                            $qrCode->id,
                        ], [
                        'class' => 'btn btn-sm btn-light',
                        ]) ?>


                        <?= $this->Html->link(__('Download'), [
                            'action' => 'show',
                            $qrCode->id,
                            '?' => ['download' => true],
                        ], [
                            'class' => 'btn btn-sm btn-light',
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container py-2">
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

<?php $this->start('page_options'); ?>
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
<?php $this->end(); // page_options ?>

<?php $this->start('offcanvas') ?>
<?= $this->Template->objectComment('OffCanvas/filters') ?>
<div class="offcanvas offcanvas-end p-3" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
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
        </div>

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

        <!-- Buttons -->
        <div class="widget mb-2 text-end">
            <div class="btn-group" role="group" aria-label="Filter Options">
                <?php if ($this->Search->isSearch()) : ?>
                    <?= $this->Search->resetLink(__('Clear'), [
                    'class' => 'btn btn-sm btn-light',
                ]); ?>
                <?php endif; ?>
                <?= $this->Form->button(__('Filter'), [
                    'type' => 'submit',
                    'class' => 'btn btn-sm btn-primary',
                    'escapeTitle' => false,
                ]); ?>
            </div>
        </div>
        <?= $this->Form->end(); ?>

        <!-- Sort -->
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
                'QrCodes.created' => __('Created'),
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
