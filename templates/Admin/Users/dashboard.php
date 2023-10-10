<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/dashboard');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<h1><?= __('Dashboard') ?></h1>

<?= $this->Template->templateComment(false, __FILE__); ?>
