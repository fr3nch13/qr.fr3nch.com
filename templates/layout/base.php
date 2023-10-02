<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$body_options = [
    'class' => 'bg-light',
];
if ($this->get('body_options')) {
    $body_options = $this->get('body_options');
}

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
        'qr.css'
        ]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<?php //$this->Html->tag('body', null, $body_options); ?>

<?= $this->fetch('layout'); ?>
<?= $this->Html->script([
    'vendor.bundle',
    'index.bundle',
    ]) ?></body>
<?= $this->Template->templateComment(false, __FILE__); ?>
</html>
