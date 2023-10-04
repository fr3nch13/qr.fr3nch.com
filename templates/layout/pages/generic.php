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
<section class="py-15 py-xl-20 pages-generic">
    <div class="container mt-5">
        <div class="row align-items-center justify-content-between">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </div>
</section>
<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
