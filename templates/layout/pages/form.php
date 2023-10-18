<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

$this->start('layout');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

    <?= $this->Template->objectComment('OffCanvas/wrap') ?>
    <div class="offcanvas-wrap">
        <section class="py-20 pages-form">
            <h2 class="d-none"><?= __('Main COntent') ?></h2>
            <div class="container mt-5">
                <?= $this->Flash->render() ?>
            </div>
            <div class="container mt-10">
                <?= $this->fetch('content') ?>
            </div>
        </section>
    </div>
<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
