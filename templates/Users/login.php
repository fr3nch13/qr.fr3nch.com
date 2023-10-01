<?php

declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 */

$this->setLayout('login');

$this->assign('login_title', __('Sign In'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

              <div class="d-grid">
                <a href="" class="btn btn-outline-red btn-with-icon text-white-hover">Sign In with Google <i
                    class="bi bi-google"></i></a>
              </div>
              <div class="text-muted text-center small py-2">or use your email</div>
              <form action="#">
                <div class="form-floating mb-2">
                  <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                  <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating mb-2">
                  <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                  <label for="floatingPassword">Password</label>
                </div>
                <div class="d-grid mb-2">
                  <a href="" class="btn btn-lg btn-primary">Sign In</a>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                      <label class="form-check-label small text-secondary" for="defaultCheck1">
                        Remember me
                      </label>
                    </div>
                  </div>
                  <div class="col text-end">
                    <a href="forgot-password.html" class="underline small">Forgot password?</a>
                  </div>
                </div>
              </form>

<div class="users form">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>

        <?= $this->Form->control('email', ['required' => true]) ?>

        <?= $this->Form->control('password', ['required' => true]) ?>

    </fieldset>
    <?= $this->Form->submit(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
