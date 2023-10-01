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
          <li><?= $this->Html->link(__('QR Codes'), [
            'controller' => 'QrCodes',
            'action' => 'index'
          ], ['class' => 'nav-link']); ?></li>
          <li class="nav-item dropdown dropdown-hover">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Landings
            </a>

            <ul class="dropdown-menu dropdown-menu-md" aria-labelledby="navbarDropdown-1">
              <li><a class="dropdown-item " href="./startup.html">Startup</a></li>
              <li><a class="dropdown-item " href="./saas.html">Saas</a>
              </li>
              <li><a class="dropdown-item " href="./coworking.html">Coworking</a></li>
              <li><a class="dropdown-item " href="./job-board.html">Job Board</a></li>
              <li><a class="dropdown-item " href="./agency.html">Agency</a>
              </li>
              <li><a class="dropdown-item " href="./blog.html">Blog</a>
              </li>
              <li><a class="dropdown-item " href="./product.html">Product</a></li>
              <li><a class="dropdown-item " href="./app.html">App</a>
              </li>
              <li><a class="dropdown-item " href="./shop.html">Shop</a>
              </li>
              <li><a class="dropdown-item " href="./event.html">Event</a></li>
              <li><a class="dropdown-item " href="./course.html">Course</a>
              </li>
              <li><a class="dropdown-item " href="./service.html">Service</a></li>
              <li><a class="dropdown-item " href="./software.html">Software</a></li>
              <li><a class="dropdown-item " href="./documentation.html">Documentation</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown dropdown-hover">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Pages
            </a>
            <div class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDropdown-2">
              <div class="row g-0">
                <div class="col-6">
                  <div class="p-4">
                    <span class="dropdown-label">Company</span>
                    <a class="dropdown-item " href="./about.html">About</a>
                    <a class="dropdown-item " href="./pricing.html">Pricing</a>
                    <a class="dropdown-item " href="./faq.html">FAQ</a>
                    <a class="dropdown-item " href="./terms.html">Terms</a>
                    <a class="dropdown-item " href="./services.html">Services</a>
                    <a class="dropdown-item " href="./job-listing.html">Job Listing</a>
                    <a class="dropdown-item " href="./job-post.html">Job Post</a>
                    <span class="dropdown-label">Portfolio</span>
                    <a class="dropdown-item " href="./portfolio-grid.html">Grid</a>
                    <a class="dropdown-item " href="./portfolio-list.html">List</a>
                    <a class="dropdown-item " href="./case-study.html">Case Study</a>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-4">
                    <span class="dropdown-label">Blog</span>
                    <a class="dropdown-item " href="./blog-listing.html">Listing</a>
                    <a class="dropdown-item " href="./blog-post.html">Post</a>
                    <span class="dropdown-label">Contact</span>
                    <a class="dropdown-item " href="./contact.html">Classic</a>
                    <a class="dropdown-item " href="./contact-location.html">Location</a>
                    <span class="dropdown-label">Utilities</span>
                    <a class="dropdown-item " href="./404.html">404</a>
                    <a class="dropdown-item " href="./coming-soon.html">Coming Soon</a>
                  </div>
                </div>
                <div class="col-12">
                  <a href="" class="card card-arrow inverted bg-green">
                    <div class="card-footer mt-auto">
                      <h4 class="lead lh-3 fw-light">Buy Cube</h4>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown dropdown-hover">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-3" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
              Account
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown-3">
              <li><a class="dropdown-item active" href="./account.html">Dashboard</a></li>
              <li><a class="dropdown-item " href="./account-settings.html">Settings</a></li>
              <li><a class="dropdown-item " href="./account-orders.html">Orders</a></li>
              <li><a class="dropdown-item " href="./account-billing.html">Billing</a></li>
              <li><a class="dropdown-item " href="./sign-in.html">Sign
                  in</a></li>
              <li><a class="dropdown-item " href="./register.html">Register</a></li>
              <li><a class="dropdown-item " href="./forgot-password.html">Forgot Password</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown dropdown-hover">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-4" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
              Shop
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown-4">
              <li><a class="dropdown-item " href="./shop-product.html">Product</a></li>
              <li class="dropend dropend-hover">
                <a class="dropdown-item dropdown-toggle  " href="#" id="navbarDropend-1" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">Listing</a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropend-1">
                  <li><a class="dropdown-item " href="./shop-listing.html">Full width</a></li>
                  <li><a class="dropdown-item " href="./shop-listing-sidebar.html">Sidebar</a></li>
                </ul>
              </li>
              <li><a class="dropdown-item " href="./shop-cart.html">Cart</a></li>
              <li><a class="dropdown-item " href="./shop-checkout.html">Checkout</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown dropdown-hover">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-5" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Docs
            </a>
            <ul class="dropdown-menu dropdown-menu-detailed" aria-labelledby="navbarDropdown-5">
              <li><a class="dropdown-item " href="./docs/index.html">
                  <span>
                    <i class="bi bi-book"></i>
                    Get Started
                    <small>Customising and building</small>
                  </span>
                </a>
              </li>
              <li><a class="dropdown-item " href="./docs/accordion.html">
                  <span>
                    <i class="bi bi-grid"></i>
                    Components
                    <small>Full list of components</small>
                  </span>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item d-lg-none">
            <a href="https://themes.getbootstrap.com/product/cube-multipurpose-template-ui-kit/" class="nav-link text-primary">Buy Cube</a>
          </li>
        </ul>
      </div>
<?= $this->Template->templateComment(false, __FILE__); ?>
