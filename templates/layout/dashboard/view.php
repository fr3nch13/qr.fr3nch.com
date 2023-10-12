<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

$this->extend('dashboard/base');

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<h1><?= $this->fetch('page_title') ?></h1>

<?php if ($this->fetch('tabs')) : ?>
<ul class="nav nav-tabs">
    <?php foreach ($this->get('tabs', []) as $k => $tab) : ?>

    <li class="nav-item" id="navItem<?= $k ?>">
        <?php
        $class = ' nav-link';
        $options = isset($tab[2]) ? $tab[2] : [];
        if (!$options['class']) {
            $options['class'] = '';
        }
        $options['class'] .= $class;

        echo $this->Html->link($tab[0], $tab[1], $options);
        ?>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?= $this->fetch('content') ?>

<?= $this->Template->templateComment(false, __FILE__); ?>
