<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

// TODO: base this page on https://cube.webuildthemes.com/account.html
// labels: frontend, templates

$this->start('layout');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

    <div class="offcanvas-wrap">
        <section class="py-15 py-xl-15 pages-index">
            <div class="container mt-5">
                <?= $this->Flash->render() ?>
            </div>
            <?= $this->fetch('content') ?>
        </section>
    </div>
<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
