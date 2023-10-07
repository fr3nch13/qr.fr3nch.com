<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/index');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
        <div class="container mt-5">
            <div class="row g-3 g-md-5 align-items-end mb-5">
                <div class="col-md-6">
                    <h1><?= __('QR Codes') ?></h1>
                    <!--
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Shop</a></li>
                            <li class="breadcrumb-item"><a href="#">Category</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Equipment</li>
                        </ol>
                    </nav>
                    -->
                </div>

                <?php
                // TODO: Create a page options element to generate page options.
                ?>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline">
                        <?php if ($this->ActiveUser->getUser()) : ?>
                        <li class="list-inline-item ms-2">
                            <?= $this->Html->link(__('Add a QR Code'), [
                                'controller' => 'QrCodes',
                                'action' => 'add',
                            ], [
                                'class' => 'underline text-black',
                            ]); ?>
                        </li>
                        <?php endif; ?>
                        <li class="list-inline-item">
                            <div class="dropdown">
                                <a
                                    class="underline text-black"
                                    href="#" role="button"
                                    id="indexPageOptions"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Sort <i class="bi bi-chevron-down"></i>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="indexPageOptions">
                                    <li><?= $this->Html->fixPaginatorSort($this->Paginator->sort(
                                        'QrCodes.name',
                                        [
                                            'asc' => '<i class="bi bi-chevron-down"></i> ' . __('Name'),
                                            'desc' => '<i class="bi bi-chevron-up"></i> ' . __('Name'),
                                        ],
                                        ['escape' => false]
                                    )); ?></li>
                                    <li><?= $this->Html->fixPaginatorSort($this->Paginator->sort(
                                        'QrCodes.created',
                                        [
                                            'asc' => '<i class="bi bi-chevron-down"></i> ' . __('Created'),
                                            'desc' => '<i class="bi bi-chevron-up"></i> ' . __('Created'),
                                        ],
                                        ['escape' => false]
                                    )); ?></li>
                                </ul>
                            </div>
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
        </div>

        <div class="container">
            <div class="row g-3 g-lg-5 justify-content-between products">

            <?php foreach ($qrCodes as $qrCode) : ?>
                <?= $this->Template->objectComment('QrCodes/' . ($qrCode->is_active ? 'active' : 'inactive')) ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product<?= $qrCode->is_active ? ' active' : ' inactive' ?>">

                        <div class="product-title"><?= $this->Html->link($qrCode->name, [
                            'action' => 'view',
                            $qrCode->id,
                        ], ['class' => 'product-title']) ?></div>

                        <figure class="product-image">
                            <?php if (!$qrCode->is_active) : ?>
                            <div class="ribbon red"><span><?= __('Inactive') ?></span></div>
                            <?php endif; ?>
                            <a href="<?= $this->Url->build(['action' => 'view', $qrCode->id]) ?>">
                                <?php if (!empty($qrCode->qr_images)) : ?>
                                    <?= $this->Template->objectComment('QrImages/active/first') ?>
                                    <img
                                        class="product-qrimage"
                                        src="<?= $this->Url->build([
                                            'controller' => 'QrImages',
                                            'action' => 'show',
                                            $qrCode->qr_images[0]->id,
                                        ]) ?>"
                                        alt="<?= h($qrCode->qr_images[0]->name) ?>">
                                <?php endif; ?>
                                <?= $this->Template->objectComment('QrCode/show') ?>
                                <img
                                    class="product-qrcode"
                                    src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>"
                                    alt="<?= __('The QR Code') ?>">
                            </a>
                        </figure>
                        <div class="btn-group btn-block product-options" role="group" aria-label="Product Options">
                            <?= $this->Template->objectComment('QrCode/forward') ?>
                            <?= $this->Html->link(
                                __('Follow Code'),
                                ['action' => 'forward', $qrCode->qrkey],
                                ['class' => 'btn btn-light']
                            ); ?>
                            <?= $this->Template->objectComment('QrCode/view') ?>
                            <?= $this->Html->link(
                                __('Details'),
                                ['action' => 'view', $qrCode->id],
                                ['class' => 'btn btn-light']
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


            </div>
            <div class="row mt-6">
                <div class="col text-center">
                    <nav aria-label="Pagination">
                        <ul class="pagination">
                            <?= $this->Paginator->first('&laquo;', ['label' => 'First']) ?>
                            <?= $this->Paginator->prev('<', ['label' => 'Previous']) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('>', ['label' => 'Next']) ?>
                            <?= $this->Paginator->last('&laquo;', ['label' => 'Last']) ?>
                        </ul>
                        <!--
                            <p><?= $this->Paginator->counter(__('{{page}}/{{pages}}, {{current}} of {{count}}')) ?></p>
                        -->
                    </nav>
                </div>
            </div>
        </div>

    <?php $this->start('offcanvas') ?>
    <?= $this->Template->objectComment('OffCanvas/filters') ?>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasFilterLabel"><?= __('Filters') ?></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php if ($this->Search->isSearch()) : ?>
            <div class="widget mb-2">
                <?= $this->Search->resetLink(__('Clear'), [
                    'class' => 'btn btn-sm btn-light float-right rounded-pill',
                ]); ?>
            </div>
            <?php endif; ?>
            <?= $this->Form->create(null, [
                'valueSources' => 'query',
            ]); ?>
            <!-- Search Form -->
            <div class="widget mb-2">
                <span class="d-flex eyebrow text-muted mb-2"><?= __('Search') ?></span>
                <legend><?= __('Search') ?></legend>
                <div class="grouped-inputs p-1 rounded-pill border">
                    <div class="row g-0">
                        <div class="col">
                            <?= $this->Form->text('q', [
                                'class' => 'form-control form-control px-4 text-primary',
                                'placeholder' => __('What are you looking for ?'),
                            ]); ?>
                        </div>
                        <div class="col-auto d-grid">
                            <?= $this->Form->button('<i class="bi bi-search"></i>', [
                                'type' => 'submit',
                                'class' => 'btn btn-primary btn-icon rounded-circle',
                                'escapeTitle' => false,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->Form->end(); ?>
            <!-- Sources -->
            <!-- Tags
            <?= $this->Form->control('source_id', ['options' => $sources]); ?>
            <div class="widget">
                <span class="d-flex eyebrow text-muted mb-2">Tags</span>
            </div> -->

        </div>
    </div>
    <?php $this->end(); // offcanvas ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
