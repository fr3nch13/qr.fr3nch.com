<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('pages/dashboard');

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->fetch('content') ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
