<?php
/**
 * The pages/dashboard layout
 * The primary layout for the admin section.
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

$this->start('layout');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

    <?= $this->Template->objectComment('OffCanvas/wrap') ?>
    <div class="offcanvas-wrap">
        <section class="split">
            <div class="container">
                <div class="row justify-content-between">

                    <aside class="col-lg-3 split-sidebar pt-lg-20">
                        <nav class="sticky-top d-none d-lg-block">
                            <?php
                            // make sure this matches the user dropdown in element/nav/top
                            // search for 'DashboardMenu'
                            ?>
                            <ul class="nav nav-minimal flex-column" id="dashboard-nav">
                                <li class="nav-item"><?= $this->Html->link(__('Dashboard'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'dashboard',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link(__('QR Codes'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'QrCodes',
                                    'action' => 'index',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link(__('Tags'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Tags',
                                    'action' => 'index',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link(__('Sources'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Sources',
                                    'action' => 'index',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link(__('Users'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'index',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item">
                                    <div class="border-bottom"></div>
                                </li>
                                <li class="nav-item"><?= $this->Html->link(__('Profile'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'view',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link(__('Settings'), [
                                    'plugin' => false,
                                    'prefix' => 'Admin',
                                    'controller' => 'Users',
                                    'action' => 'edit',
                                ], ['class' => 'nav-link fs-lg']) ?></li>
                                <li class="nav-item"><?= $this->Html->link(__('Sign Out'), [
                                    'plugin' => false,
                                    'prefix' => false,
                                    'controller' => 'Users',
                                    'action' => 'logout',
                                ], ['class' => 'nav-link fs-lg text-red']) ?></li>
                            </ul>
                        </nav>
                    </aside>

                    <div class="col-lg-9 split-content pt-lg-20 px-2 px-lg-10">
                        <?= $this->Flash->render() ?>
                        <?= $this->fetch('content') ?>
                    </div>

                </div>
            </div>
        </section>
    </div>

<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
