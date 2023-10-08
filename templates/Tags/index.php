<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Tag> $tags
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/index');
}
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
                        <?php if ($this->ActiveUser->getUser()) : ?>
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
<?= $this->Template->templateComment(false, __FILE__); ?>
