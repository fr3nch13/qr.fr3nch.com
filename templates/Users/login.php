<?php

declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 */

$this->setLayout('login');
?>

<!-- START: App.Users/login -->

<div class="users form">
    <?= $this->Flash->render() ?>
    <h3>Login</h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>

        <?= $this->Form->control('email', ['required' => true]) ?>

        <?= $this->Form->control('password', ['required' => true]) ?>

    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>


<!-- END: App.Users/login -->
