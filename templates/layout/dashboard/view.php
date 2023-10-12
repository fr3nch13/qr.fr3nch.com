<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('dashboard/base');

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<h1><?= $this->fetch('page_title') ?></h1>
<?= $this->fetch('content') ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
