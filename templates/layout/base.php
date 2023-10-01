<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

?>
<!DOCTYPE html>
<?= $this->Template->templateComment(true, __FILE__); ?>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css([
        'libs.bundle',
        'libs.bundle.css.map',
        'index.bundle',
        'index.bundle.css.map',
        'qr.css'
        ]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="bg-light">

<?= $this->fetch('layout'); ?>

<?= $this->Html->script(['libs.bundle', 'index.bundle']) ?></body>
<?= $this->Template->templateComment(false, __FILE__); ?>
</html>
