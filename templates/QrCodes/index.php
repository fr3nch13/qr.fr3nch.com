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
            <div class="col-12 col-md-6">
                <h1><?= __('QR Codes') ?></h1>
            </div>

            <div class="col-12 col-md-6 text-end">
                <ul class="list-inline">
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
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-3 g-lg-5 justify-content-between">

        <?php foreach ($qrCodes as $qrCode) : ?>
            <div class="col-md-6 col-lg-4">
                <div class="card mb-2 border shadow-sm">

                    <?php
                    $bgUrl = $this->Url->build([
                        'action' => 'show',
                        $qrCode->id,
                        '?' => ['thumb' => 'md'],
                    ]);
                    if (!empty($qrCode->qr_images)) {
                        $bgUrl = $this->Url->build([
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

                    <div class="card-title text-center text-white pt-5"><?= $qrCode->name ?></div>

                    <div class="card-body text-white d-block level-2 py-5 py-md-10">

                    </div>

                    <div class="card-footer text-muted p-0 btn-group">
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
