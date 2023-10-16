<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Source> $sources
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('Sources'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="container bg-white">
    <div class="row py-2">
        <div class="col text-center">

        <?php foreach ($sources as $source) : ?>
            <?= $this->Html->link(
                $source->name . '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                ' . count($source->qr_codes) . '
                <span class="visually-hidden">unread messages</span>
              </span>',
                [
                    'action' => 'edit',
                    $source->id,
                ],
                [
                    'class' => 'my-2 mx-2 btn btn-light btn-outline-secondary position-relative ajax-modal',
                    'role' => 'button',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#edit-modal',
                    'data-ajax-target' => '#editModalBody',
                    'escape' => false,
                ]
            ); ?>
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
        <?= $this->Html->link(__('Add a Source'), [
            'controller' => 'Sources',
            'action' => 'add',
        ], [
            'class' => 'underline text-black ajax-modal',
            'role' => 'button',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#edit-modal',
            'data-ajax-target' => '#editModalBody',
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

<?php $this->start('modal') ?>
<!-- Edit Modal -->

<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button
                type="button"
                class="bi bi-x modal-close"
                data-bs-dismiss="modal"
                aria-label="Close"></button>
            <div class="modal-body p-4">
                <span id="editModalLabel" class="invisible"><?= __('Edit') ?></span>
                <div id="editModalBody"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->end(); // modal ?>

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
                'Sources.name' => __('Name'),
                'Sources.created' => __('Created'),
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
