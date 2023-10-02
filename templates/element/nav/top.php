<?php
/**
 * @var \App\View\AppView $this
 *
 */

$navClasses = 'qr-navbar-top navbar navbar-expand-lg navbar-sticky navbar-light border-bottom';
$logoImage = 'logo_dark.png';
if ($this->getLayout() === 'login') {
    $navClasses = 'qr-navbar-top navbar navbar-expand-lg navbar-sticky navbar-dark';
    $logoImage = 'logo_light.png';
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<nav id="mainNav" class="<?= $navClasses; ?>">
    <div class="container">
        <?= $this->Html->link($this->Html->image($logoImage, ['class' => 'logo-top']), '/', ['class' => 'navbar-brand', 'escape' => false]); ?>

        <!-- secondary -->
        <ul class="navbar-nav navbar-nav-secondary order-lg-3">
            <!-- Controls the Mobile Nav below -->
            <li class="nav-item d-lg-none">
                <a class="nav-link nav-icon" href="" role="button" data-bs-toggle="collapse" data-bs-target="#userNav" aria-expanded="false">
                    <i class="bi bi-person"></i>
                </a>
            </li>
            <?php if ($this->ActiveUser->getUser()): ?>
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
            <?php endif; ?>
            <li class="nav-item d-lg-none">
                <a class="nav-link nav-icon" href="" role="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="bi bi-list"></span>
                </a>
            </li>
            <?php if (!$this->ActiveUser->getUser()): ?>
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

        <!-- mobile user menu -->
        <div class="collapse account-collapse" id="userNav" data-bs-parent="#mainNav">
            <ul class="navbar-nav">
                <?php if ($this->ActiveUser->getUser()): ?>
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
                <?php else: ?>
                <li class="nav-item">
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
        </div>

    </div>
  </nav>
<?= $this->Template->templateComment(false, __FILE__); ?>
