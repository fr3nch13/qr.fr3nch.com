<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<nav id="mainNav" class="navbar navbar-expand-lg navbar-sticky navbar-light bg-light border-bottom">
    <div class="container">
      <?= $this->Html->link($this->Html->image('logo_dark.png'), '/', ['escape' => false]); ?>
      <?= $this->element('nav/top_right'); ?>
      <?= $this->element('nav/top_main'); ?>
      <?= $this->element('nav/top_mobile'); ?>
    </div>
  </nav>
<?= $this->Template->templateComment(false, __FILE__); ?>
