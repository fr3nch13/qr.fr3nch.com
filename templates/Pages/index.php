<?php
/**
 * @var \App\View\AppView $this
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/generic');
}
// see contact.html
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<h1>Index</h1>
<?= $this->Template->templateComment(false, __FILE__); ?>
