<?php
/**
 * The pages/dashboard layout
 * The primary layout for the admin section.
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

// TODO: base this page on https://cube.webuildthemes.com/account.html
// labels: frontend, templates

$this->start('layout');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

    <?= $this->Template->objectComment('OffCanvas/wrap') ?>
    <div class="offcanvas-wrap">
        <section class="split">
            <div class="container">
                <div class="row justify-content-between">
                    <?= $this->Flash->render() ?>
                    <aside class="col-lg-3 split-sidebar">
                        <nav class="sticky-top d-none d-lg-block">
                            <ul class="nav nav-minimal flex-column" id="dashboard-nav">
                                <li class="nav-item"><?= $this->Html->link('Dashboard', [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'dashboard',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link('QR Codes', [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'QrCodes',
                                    'action' => 'index',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link('Tags', [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Tags',
                                    'action' => 'index',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item">
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li class="nav-item"><?= $this->Html->link('Profile', [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'view',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link('Settings', [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'edit',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link('Sign Out', [
                                    'plugin' => false,
                                    'prefix' => false,
                                    'controller' => 'Users',
                                    'action' => 'logout',
                                ], ['class' => 'nav-link fs-lg text-red']) ?></li>
                            </ul>
                        </nav>
                    </aside>

                    <div class="col-lg-9 split-content">
                        <?= $this->fetch('content') ?>
                    </div>

                </div>
            </div>
        </section>
    </div>

<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
