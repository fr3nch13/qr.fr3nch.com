<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

$this->start('layout');

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

  <section class="bg-black overflow-hidden">
    <div class="py-15 py-xl-20 d-flex flex-column container level-3 min-vh-100">
      <div class="row align-items-center justify-content-center my-auto">
        <div class="col-md-10 col-lg-8 col-xl-5">

          <?= $this->Flash->render() ?>
          <?= $this->fetch('content') ?>

        </div>
      </div>
    </div>
    <figure class="background background-overlay" style="background-image: url('<?= $this->Url->image('login_bg.jpeg'); ?>')">
    </figure>
  </section>

<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
