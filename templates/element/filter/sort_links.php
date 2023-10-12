<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string> $sorts The list of sort links to create.
 */

if (!isset($class)) {
    $class = 'btn btn-light';
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?php foreach ($sorts as $key => $name) : ?>
    <?= $this->Paginator->sort($key,
        [
            'asc' => $name . ' <i class="bi bi-chevron-down"></i>',
            'desc' => $name . ' <i class="bi bi-chevron-up"></i>',
        ],
        [
            'escape' => false,
            'class' => $class,
        ]
    ) ?>
<?php endforeach; ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
