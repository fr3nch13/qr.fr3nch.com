<?php
/**
 * @var \App\View\AppView $this
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/generic');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<h1>About</h1>
<?= $this->Template->templateComment(false, __FILE__); ?>
