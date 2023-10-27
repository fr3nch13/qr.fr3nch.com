<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Tag> $tags
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/index');
}
$this->assign('title', __('Tags'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<div class="container mt-5">
            <div class="row g-3 g-md-5 align-items-end mb-5">
                <div class="col-md-6">
                    <h1><?= __('Tags') ?></h1>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-3 g-lg-5 tags">
                <div class="col text-center">
                <?php foreach ($tags as $tag) : ?>
                    <?php
                    if (!$tag->hasValue('qr_codes')) {
                        continue;
                    }
                    ?>
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
                    <?= $this->element('nav/pagination') ?>
                </div>
            </div>
        </div>
<?= $this->Template->templateComment(false, __FILE__); ?>
