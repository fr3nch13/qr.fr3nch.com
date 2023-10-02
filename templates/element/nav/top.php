<?php
/**
 * @var \App\View\AppView $this
 *
 */

$navClasses = 'qr-navbar-top navbar navbar-expand-lg navbar-sticky navbar-light border-bottom';
if ($this->getLayout() === 'login') {
    $navClasses = 'qr-navbar-top navbar navbar-expand-lg navbar-sticky navbar-dark';
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<nav id="mainNav" class="<?= $navClasses; ?>">
    <div class="container">
      <?= $this->Html->link($this->Html->image('logo_dark.png', ['class' => 'logo-top']), '/', ['escape' => false]); ?>
      <?= $this->element('nav/top_right'); ?>
      <?= $this->element('nav/top_main'); ?>
      <?= $this->element('nav/top_mobile'); ?>
    </div>
  </nav>
<?= $this->Template->templateComment(false, __FILE__); ?>
