<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
    <!-- footer -->
    <footer class="py-15 py-xl-15 bg-black inverted">
        <div class="container">
            <div class="row align-items-center g-1 g-lg-6 text-muted">
                <div class="col-md-6 col-lg-5 order-lg-2 text-center text-md-start">
                    <ul class="list-inline small">
                        <li class="list-inline-item mb-1"><?= $this->Html->link('About', [
                            'plugin' => false,
                            'prefix' => false,
                            'controller' => 'Pages',
                            'action' => 'display',
                            'about',
                        ], [
                            'class' => 'text-reset text-primary-hover',
                        ]) ?></li>
                        <li class="list-inline-item mb-1"><a
                            href="https://twitter.com/Fr3nchLLC"
                            class="text-reset text-primary-hover"
                        >X (twitter)</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4 text-center text-md-end order-lg-3">
                    <span class="small">Henderson, NV 89002</span>
                </div>
                <div class="col-lg-3 order-lg-1 text-center text-md-start">
                    <p class="small"><?= __('Copyrights') ?> © <?= date('Y'); ?></p>
                </div>
            </div>
        </div>
    </footer>
<?= $this->Template->templateComment(false, __FILE__); ?>
