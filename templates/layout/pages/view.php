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

    <div class="offcanvas-wrap">
        <section class="py-20 pages-view">
            <div class="container mt-5">
                <?= $this->Flash->render() ?>
            </div>
            <div class="container mt-10">
                <div class="row g-5 justify-content-center justify-content-lg-between">
                <?= $this->fetch('content') ?>
                </div>
            </div>
        </section>
    </div>
<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
