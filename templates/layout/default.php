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


<section class="py-20">
    <div class="container mt-10">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
</section>

<?= $this->element('nav/footer'); ?>

<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
