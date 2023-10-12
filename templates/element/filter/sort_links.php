<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string> $sorts The list of sort links to create.
 */

if (!isset($class)) {
    $class = 'btn btn-light rounded-pill mx-1 my-1';
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?php foreach ($sorts as $key => $name) {
    $nameAsc = $name . ' <i class="bi bi-chevron-down"></i>';
    $nameDesc = $name . ' <i class="bi bi-chevron-up"></i>';

    if (str_contains($class, 'btn')) {
        $nameAsc = $this->Html->tag('span', $nameAsc, [
            'class' => $class,
            'role' => 'button',
        ]);

        $nameDesc = $this->Html->tag('span', $nameDesc, [
            'class' => $class,
            'role' => 'button',
        ]);
    }


    echo $this->Paginator->sort($key, ['asc' => $nameAsc, 'desc' => $nameDesc], ['escape' => false]);
}
?>
<?= $this->Template->templateComment(false, __FILE__); ?>
