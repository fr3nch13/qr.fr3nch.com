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
    <?php // Build the title
    $title = [
        __('QR Fr3nch'),
    ];
    if ($this->getRequest()->getParam('plugin')) {
        $title[] = ucfirst($this->getRequest()->getParam('plugin'));
    }
    if ($this->getRequest()->getParam('prefix')) {
        $title[] = ucfirst($this->getRequest()->getParam('prefix'));
    }
    if ($this->fetch('title')) {
        $title[] = $this->fetch('title');
    }
    ?>
    <title><?= implode(' : ', $title) ?></title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8R77CBX3C8"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-8R77CBX3C8');
    </script>

    <?= $this->Html->css([
        'libs.bundle',
        'index.bundle',
        '/assets/npm-asset/bootstrap-icons/font/bootstrap-icons.css',
        '/assets/npm-asset/bootstrap5-tags/tags-pure.css',
        '/assets/npm-asset/bootstrap-fileinput/css/fileinput.css',
        'qr.css',
        ]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<?= $this->fetch('layout'); ?>
<?= $this->fetch('modal') ?>
<?= $this->fetch('offcanvas') ?>


<?= $this->Html->script([
    'vendor.bundle',
    'index.bundle',
    '/assets/npm-asset/jquery/dist/jquery.min.js',
    //'/assets/npm-asset/piexifjs/piexif.js',
    '/assets/npm-asset/sortablejs/Sortable.js',
    '/assets/npm-asset/bootstrap-fileinput/js/plugins/buffer.js',
    '/assets/npm-asset/bootstrap-fileinput/js/plugins/filetype.js',
    //'/assets/npm-asset/bootstrap-fileinput/js/plugins/sortable.js',
    '/assets/npm-asset/bootstrap-fileinput/js/fileinput.js',
    'qr',
    ]) ?>
<?= $this->Html->script('qr_module', [
    'type' => 'module',
]) ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</body>
<?= $this->Template->templateComment(false, __FILE__); ?>
</html>
