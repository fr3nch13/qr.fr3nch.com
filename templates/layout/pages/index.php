<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('base');

$this->start('layout');
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->element('nav/top'); ?>

    <div class="offcanvas-wrap">
        <section class="py-15 py-xl-15 bg-white overflow-hidden pages-index level-3">
            <div class="container mt-5">
                <?= $this->Flash->render() ?>
            </div>
            <?= $this->fetch('content') ?>
        </section>

        <figure
            class="background background-overlay"
            style="background-image: url('<?= $this->Url->image('login_bg.jpg'); ?>')">
        </figure>
    </div>
<?= $this->element('nav/footer'); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // layout ?>
