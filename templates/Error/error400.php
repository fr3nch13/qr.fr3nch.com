<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Database\StatementInterface $error
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?php

if (Configure::read('debug')) :
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.php');

    $this->start('file');
    ?>
    <?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
    <?php endif; ?>
    <?php if (!empty($error->params)) : ?>
    <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
    <?php endif; ?>

    <?php
    echo $this->element('auto_table_warning');

    $this->end();
endif;
?>
<h1 class="display-2"><?= h($message) ?></h1>
<p>
    <strong><?= __d('cake', 'Error') ?>: <?=$code ?></strong>
    <?= __d('cake', 'The requested address {0} was not found.', "<strong>'{$url}'</strong>") ?>
</p>
<?= $this->Template->templateComment(false, __FILE__); ?>
