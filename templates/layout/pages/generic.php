<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('default');

$this->start('page_content');

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<section class="py-15 py-xl-20">
    <div class="container mt-5">
        <div class="row align-items-center justify-content-between">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </div>
</section>
<?= $this->Template->templateComment(false, __FILE__); ?>
<?php $this->end() // page_content ?>
