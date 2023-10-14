<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Tag> $tags
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('Tags'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<div class="container mt-5">
            <div class="row g-3 g-md-5 align-items-end mb-5">
                <div class="col-md-6">
                    <h1><?= __('Tags') ?></h1>
                </div>

                <?php
                // TODO: Create a page options element to generate page options.
                ?>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline">
                        <?php if ($this->ActiveUser->isLoggedIn()) : ?>
                        <li class="list-inline-item ms-2">
                            <?= $this->Html->link(__('Add a Tag'), [
                                'controller' => 'Tags',
                                'action' => 'add',
                            ], [
                                'class' => 'underline text-black',
                            ]); ?>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-3 g-lg-5 tags">
                <div class="col text-center">
                <?php foreach ($tags as $tag) : ?>
                    <?= $this->Html->link(
                        $tag->name,
                        [
                            'controller' => 'QrCodes',
                            'action' => 'index',
                            '?' => ['t' => $tag->name],
                        ],
                        [
                            'class' => 'my-2 mx-2 btn btn-light btn-outline-secondary rounded-pill',
                            'role' => 'button',
                        ]
                    ); ?>
                <?php endforeach; ?>
                </div>
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


<?php $this->start('page_options'); ?>
<ul class="list-inline">
    <li class="list-inline-item ms-2">
        <?= $this->Html->link(__('Add a Tag'), [
            'controller' => 'Tags',
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
                'Tags.name' => __('Name'),
                'Tags.created' => __('Created'),
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
