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

  <!-- hero -->
  <section class="inverted bg-red overflow-hidden">
    <div class="d-flex flex-column container min-vh-100 py-20 level-3">
      <div class="row align-items-center justify-content-center justify-content-lg-between my-auto">
        <div class="col-lg-5 order-lg-2">
          <img class="img-fluid" src="<?= $this->Url->image('404.svg') ?>" alt="<?= __('404 Icon') ?>">
        </div>
        <div class="col-md-8 col-lg-6 order-lg-1 text-center text-lg-start">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
            <?= $this->Html->link(__('Go back to homepage'), [
                'controller' => 'QrCodes',
                'action' => 'index'
            ], [
                'class' => 'btn btn-rounded btn-outline-white rounded-pill',
            ]) ?>
        </div>
      </div>
    </div>
    <figure
        class="background background-overlay"
        style="opacity:.7; background-image: url('<?= $this->Url->image('login_bg.jpg'); ?>')">
    </figure>
  </section>

<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
