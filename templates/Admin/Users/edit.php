<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}
// TODO: Hold out until cakedc/users is CakePHP 5 compatible
// then switch to it.
// labels: User Management, CakeDC

$this->assign('page_title', __('Settings'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Form->create($user) ?>
<div class="card bg-opaque-white">
    <div class="card-body p-2 p-lg-5">
        <div class="row">
            <div class="col">
                <?= $this->Form->control('name', [
                    'required' => true,
                    'spacing' => 'mb-2',
                    'label' => __('Name'),
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $this->Form->control('email', [
                    'type' => 'text',
                    'required' => true,
                    'spacing' => 'mb-2',
                    'placeholder' => 'user@email.com',
                    'label' => __('Login Email'),
                ]); ?>
            </div>
            <div class="col-6">
                <?= $this->Form->control('gravatar_email', [
                    'type' => 'text',
                    'required' => false,
                    'spacing' => 'mb-2',
                    'placeholder' => 'gravatar@email.com',
                    'label' => __('Gravatar Email'),
                    'help' => __('We use {1} for our avatar images. ' .
                        'If your {0} email is different from your login email, enter it here.', [
                            __('Gravatar'),
                            $this->Html->link(__('Gravatar'), 'https://gravatar.com', ['target' => 'gravatar']),
                        ]),
                ]); ?>
            </div>
        </div>
        <?php if ($this->ActiveUser->isAdmin() || $this->ActiveUser->isMe($user)) : ?>
        <div class="row">
            <div class="col">
                <?= $this->Form->control('password', [
                    'required' => false,
                    'spacing' => 'mb-2',
                    'label' => __('Change Password'),
                    'value' => '',
                    'help' => __('To change the password, enter a new one here. Otherwise leave it blank.'),
                ]); ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($this->ActiveUser->isAdmin()) : ?>
        <div class="row">
            <div class="col-6 form-switch mt-4">
                <?= $this->Form->control('is_active', [
                    'spacing' => 'mb-2',
                    'label' => __('Active?'),
                ]); ?>
            </div>
            <div class="col-6 form-switch mt-4">
                <?= $this->Form->control('is_admin', [
                    'spacing' => 'mb-2',
                    'label' => __('Admin?'),
                ]); ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col text-end">
                <?= $this->Form->button(__('Save'), [
                    'type' => 'submit',
                    'class' => 'btn btn-lg btn-primary btn-block',
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
