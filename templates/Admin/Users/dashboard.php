<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('Dashboard'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<p>Stats go here.</p>

<?= $this->Template->templateComment(false, __FILE__); ?>
