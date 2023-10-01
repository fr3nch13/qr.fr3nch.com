<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
      <!-- mobile user menu -->
      <div class="collapse account-collapse" id="userNav" data-bs-parent="#mainNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="#">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="#">Settings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="#">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="#">Billing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-red" href="#">Log Out</a>
          </li>
        </ul>
      </div>
<?= $this->Template->templateComment(false, __FILE__); ?>
