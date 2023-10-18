<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('Users'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>


<div class="card bg-opaque-white">
    <div class="card-body p-2 p-lg-5">
        <div class="row justify-content-between">
        <?php foreach ($users as $user) : ?>
            <div class="col-md-6">
                <div class="card mb-2 shadow-sm border bg-white">

                    <?= $this->Template->objectComment('QrImages/entity'); ?>
                    <?php if (!$user->is_active) : ?>
                    <div class="ribbon red"><span><?= __('Inactive') ?></span></div>
                        <?= $this->Template->objectComment('QrImages/entity/inactive'); ?>
                    <?php else : ?>
                        <?= $this->Template->objectComment('QrImages/entity/active'); ?>
                    <?php endif; ?>

                    <div class="card-content">
                        <div class="row px-4 py-3">
                            <div class="col-4">
                                <?= $this->Html->avatar('sm', $user) ?>
                            </div>
                            <div class="col-8">
                                <div class="card-title">
                                    <?= $user->name ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-2 border-top">
                            <dl class="row fs-80">
                                <dt class="col-12 col-md-4"><?= __('Email') ?></dt>
                                <dd class="col-12 col-md-8"><?= h($user->email) ?> </dd>

                                <dt class="col-4"><?= __('Admin') ?></dt>
                                <dd class="col-8"><?php
                                if ($user->is_admin) {
                                    echo $this->Html->icon('check');
                                } else {
                                    echo $this->Html->icon('x');
                                }
                                ?></dd>

                                <dt class="col-4"><?= __('Created') ?></dt>
                                <dd class="col-8"><?php
                                if ($user->created) {
                                    echo $user->created->format('M d, Y');
                                }
                                ?></dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card-footer text-muted p-0 btn-group">

                        <?= $this->Html->link(__('View'), [
                            'action' => 'view',
                            $user->id,
                                    ], [
                                    'class' => 'btn btn-sm btn-light',
                                    ]) ?>

                        <?= $this->Html->link(__('Edit'), [
                            'action' => 'edit',
                            $user->id,
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
        <?= $this->Html->link(__('Add a User'), [
            'controller' => 'Users',
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
                'Users.name' => __('Name'),
                'Users.email' => __('Email'),
                'Users.is_active' => __('Active'),
                'Users.is_admin' => __('Admin'),
                'Users.created' => __('Created'),
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
