<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('dashboard/base');

?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<div class="container mt-md-5 px-0 px-md-3">
    <div class="row align-items-end mb-2">
        <div class="col">
            <h1><?= $this->fetch('page_title') ?></h1>
        </div>
        <?php if ($this->fetch('page_options')) : ?>
        <div class="col text-end">
            <?= $this->fetch('page_options') ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col">
            <?= $this->fetch('content') ?>
        </div>
    </div>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
