<?php
/**
 * The default layout
 *
 * @var \App\View\AjaxView $this
 */

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->fetch('content'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
