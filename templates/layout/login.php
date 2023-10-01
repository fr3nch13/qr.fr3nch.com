<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

$this->start('layout');

?>
<body>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

<section class="bg-black overflow-hidden">
    <div class="py-15 py-xl-20 d-flex flex-column container level-3 min-vh-100">
      <div class="row align-items-center justify-content-center my-auto">
        <div class="col-md-10 col-lg-8 col-xl-5">

          <div class="card">
            <div class="card-header bg-white text-center pb-0">
              <h5 class="fs-4 mb-1"><?= $this->fetch('login_title'); ?></h5>
            </div>
            <div class="card-body bg-white">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
            <!--
            <div class="card-footer bg-opaque-black inverted text-center">
              <p class="text-secondary">Don't have an account yet? <a href="register.html"
                  class="underline">Register</a>
              </p>
            </div>
            -->
          </div>
        </div>
      </div>
    </div>
    <figure class="background background-overlay" style="background-image: url('<?= $this->Html->url(); ?>')">
    </figure>
  </section>

<?= $this->element('nav/footer'); ?>
<?= $this->Html->script(['libs.bundle', 'index.bundle']) ?></body>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
