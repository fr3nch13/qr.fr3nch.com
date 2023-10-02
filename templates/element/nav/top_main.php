<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
      <!-- primary -->
      <div class="collapse navbar-collapse" id="navbar" data-bs-parent="#mainNav">
        <ul class="navbar-nav">
          <li class="nav-item"><?= $this->Html->link(__('QR Codes'), [
            'controller' => 'QrCodes',
            'action' => 'index'
          ], ['class' => 'nav-link']); ?></li>

          <li class="nav-item"><?= $this->Html->link(__('Categories'), [
            'controller' => 'Categories',
            'action' => 'index'
          ], ['class' => 'nav-link']); ?></li>

          <li class="nav-item"><?= $this->Html->link(__('Tags'), [
            'controller' => 'Tags',
            'action' => 'index'
          ], ['class' => 'nav-link']); ?></li>
        </ul>
      </div>
<?= $this->Template->templateComment(false, __FILE__); ?>
