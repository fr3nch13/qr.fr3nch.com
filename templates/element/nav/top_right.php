<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
      <!-- secondary -->
      <ul class="navbar-nav navbar-nav-secondary order-lg-3">
        <li class="nav-item">
          <a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button" aria-controls="offcanvasCart">
            <i class="bi bi-cart2"></i>
          </a>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link nav-icon" href="" role="button" data-bs-toggle="collapse" data-bs-target="#userNav" aria-expanded="false">
            <i class="bi bi-person"></i>
          </a>
        </li>
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
        <li class="nav-item d-none d-lg-block">
          <a href="" class="btn btn-primary rounded-pill ms-2">
            Buy Cube
          </a>
        </li>
      </ul>
<?= $this->Template->templateComment(false, __FILE__); ?>
