<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<nav aria-label="Pagination" class="text-center">
    <ul class="pagination">
        <?= $this->Paginator->first('&laquo;', ['label' => 'First', 'escape' => false]) ?>
        <?= $this->Paginator->prev('<', ['label' => 'Previous']) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('>', ['label' => 'Next']) ?>
        <?= $this->Paginator->last('&raquo;', ['label' => 'Last', 'escape' => false]) ?>
    </ul>
</nav>

<?= $this->Template->templateComment(false, __FILE__); ?>
