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
    <?= $this->Html->meta('icon') ?>
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/img/site.webmanifest">
    <title><?= $this->fetch('title') ?></title>

    <?= $this->Html->css([
        'libs.bundle',
        'index.bundle',
        '/assets/npm-asset/pootstrap5-tags/tags-pure.css',
        'qr.css',
        ]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<?= $this->fetch('layout'); ?>
<?= $this->fetch('offcanvas') ?>
<?= $this->Html->script([
    'vendor.bundle',
    'index.bundle',
    '/assets/npm-asset/jquery/dist/jquery.min.js',
    '/assets/npm-asset/pootstrap5-tags/tags.js',
    'qr',
    ]) ?></body>
<?= $this->Template->templateComment(false, __FILE__); ?>
</html>
