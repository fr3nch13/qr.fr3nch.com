<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
      <!-- secondary -->
      <ul class="navbar-nav navbar-nav-secondary order-lg-3">
        <?php if ($this->ActiveUser->getUser()): ?>
        <li class="nav-item d-lg-none">
          <a class="nav-link nav-icon" href="" role="button" data-bs-toggle="collapse" data-bs-target="#userNav" aria-expanded="false">
            <i class="bi bi-person"></i>
          </a>
        </li>
        <!-- The user icon and dropdown for user-specific pages -->
        <li class="nav-item dropdown dropdown-hover d-none d-lg-block">
          <a class="nav-link nav-icon" role="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person"></i>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item active" href="./account.html">Dashboard</a></li>
            <li><a class="dropdown-item " href="./account-settings.html">Settings</a></li>
            <li><a class="dropdown-item " href="./account-orders.html">Orders</a></li>
            <li><a class="dropdown-item " href="./account-billing.html">Billing</a></li>
            <li><a class="dropdown-item text-red" href="#">Log Out</a></li>
          </ul>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link nav-icon" href="" role="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="bi bi-list"></span>
          </a>
        </li>
        <?php else: ?>
        <!-- Signup Link -->
        <li class="nav-item d-none d-lg-block">
          <?php
            $classes = 'btn btn-primary rounded-pill ms-2';
            if ($this->getLayout() === 'login') {
                $classes = 'btn btn-outline-white rounded-pill ms-2';
            }
            echo $this->Html->link(__('Sign In'), [
            'controller' => 'Users',
            'action' => 'login'
          ], ['class' => $classes]); ?>
        </li>
        <?php endif; ?>
      </ul>
<?= $this->Template->templateComment(false, __FILE__); ?>
