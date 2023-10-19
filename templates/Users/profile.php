<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/view');
}
$this->assign('title', __('User') . ':' . $user->name);
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <h1>Profile</h1>
    <p>Coming Soon.</p>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
