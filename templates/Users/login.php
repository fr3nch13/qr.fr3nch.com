<?php

declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('login');
}

$this->assign('title', __('Sign In'));

$this->assign('card_title', __('Sign In'));
$this->start('card_body');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="users form">
    <?= $this->Form->create() ?>
        <fieldset>
            <legend class="text-muted text-center small py-2"><?= __('Please enter your email and password') ?></legend>

            <?= $this->Form->control('email', [
                'required' => true,
                'spacing' => 'mb-2',
                'label' => ['floating' => true],
            ]) ?>

            <?= $this->Form->control('password', [
                'required' => true,
                'spacing' => 'mb-2',
                'label' => ['floating' => true],
            ]) ?>

        </fieldset>
        <div class="d-grid mb-2">
            <?= $this->Form->button(__('Sign In'), [
                'type' => 'submit',
                'class' => 'btn btn-lg btn-primary btn-block',
            ]); ?>
        </div>
        <div class="row">
            <div class="col">
                <?= $this->Form->control('remember_me', [
                    'type' => 'checkbox',
                    'label' => __('Remember Me?'),
                ]); ?>
            </div>
            <!--
            <div class="col text-end">
                <?php
                // TODO: Add logic for forgetting passwords.
                ?>
                <?= $this->Html->link(__('Forgot Password?'), [
                    'controller' => 'Users',
                    'action' => 'forgot_password',
                ]); ?>
            </div>
            -->
        </div>
    <?= $this->Form->end() ?>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
<?php
$this->end();
echo $this->element('card');
