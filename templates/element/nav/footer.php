<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
    <!-- footer -->
    <footer class="py-15 py-xl-20 bg-black inverted">
      <div class="container">
        <div class="row g-4 g-lg-5 mb-10">
          <div class="col-md-3 text-center text-md-start">
            <?= $this->Html->link($this->Html->image('logo_light.png', ['class' => 'logo-bottom']), '/', ['escape' => false]); ?>
          </div>
          <div class="col-md-4 col-lg-5 text-center text-md-start">
            <ul class="list-unstyled">
              <li class="mb-1"><?= $this->Html->link('About', [
                'controller' => 'Pages',
                'action' => 'display',
                'about'
              ], [
                'class' => 'text-reset text-primary-hover',
              ]) ?><</li>
            </ul>
          </div>
          <div class="col-md-5 col-lg-4">
            <div class="grouped-inputs border p-1 rounded-pill">
              <div class="row">
                <div class="col">
                  <input type="text" class="form-control rounded-pill px-3" aria-label="Text input"
                    placeholder="Your email">
                </div>
                <div class="col-auto">
                  <a href="" class="btn btn-primary btn-icon rounded-circle"><i class="bi bi-arrow-return-left"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row align-items-center g-1 g-lg-6 text-muted">
          <div class="col-md-6 col-lg-5 order-lg-2 text-center text-md-start">
            <ul class="list-inline small">
              <li class="list-inline-item"><a href="" class="text-reset text-primary-hover">facebook</a></li>
              <li class="list-inline-item ms-1"><a href="https://twitter.com/Fr3nchLLC" class="text-reset text-primary-hover">twitter</a></li>
            </ul>
          </div>
          <div class="col-md-6 col-lg-4 text-center text-md-end order-lg-3">
            <span class="small">Henderson, NV 89002</span>
          </div>
          <div class="col-lg-3 order-lg-1 text-center text-md-start">
            <p class="small">Copyrights © <?= date('Y'); ?></p>
          </div>
        </div>
      </div>
    </footer>
<?= $this->Template->templateComment(false, __FILE__); ?>
