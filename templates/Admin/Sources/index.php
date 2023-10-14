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
<div class="sources index content">
    <?= $this->Html->link(__('New Source'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Sources') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sources as $source) : ?>
                <tr>
                    <td><?= $this->Number->format($source->id) ?></td>
                    <td><?= h($source->name) ?></td>
                    <td><?= h($source->created) ?></td>
                    <td><?= h($source->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $source->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $source->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), [
                            'action' => 'delete',
                            $source->id,
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?', $source->id),
                        ]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, ' .
            'showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>

<?php $this->start('page_options'); ?>
<ul class="list-inline">
    <li class="list-inline-item ms-2">
        <?= $this->Html->link(__('Add a Source'), [
            'controller' => 'Sources',
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
