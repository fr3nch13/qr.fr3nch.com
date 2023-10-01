<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

$this->start('layout');

?>
<body class="bg-light">

<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>

<?= $this->element('nav/footer'); ?>

<?= $this->Html->script(['libs.bundle', 'index.bundle']) ?></body>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
