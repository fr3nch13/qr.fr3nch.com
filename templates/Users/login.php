<?php

declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('login');
}

$this->assign('card_title', __('Sign In'));
$this->start('card_body');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
                <!--
              <div class="d-grid">
                <a href="" class="btn btn-outline-red btn-with-icon text-white-hover">Sign In with Google <i
                    class="bi bi-google"></i></a>
              </div>
              <div class="text-muted text-center small py-2">or use your email</div>
              -->
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
                    <?= $this->Form->button('Sign In', [
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
                  <div class="col text-end">
                    <?= $this->Html->link('Forgit Password?', [
                        'controller' => 'Users',
                        'action' => 'forgot_password',
                    ]); ?>
                  </div>
                </div>
                <?= $this->Form->end() ?>
              </div>

              <!--
                <div class="d-grid mb-2">
                  <a href="" class="btn btn-lg btn-primary">Sign In</a>
                </div>
                <div class="submit"><input type="submit" class="btn-primary btn" value="Login"></div>


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
              -->


<?= $this->Template->templateComment(false, __FILE__); ?>
<?php
$this->end();
echo $this->element('card');
