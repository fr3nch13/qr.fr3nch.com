<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 * @var \Cake\Collection\CollectionInterface|string[] $parentCategories
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/form');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Categories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="categories form content">
            <?= $this->Form->create($category, ['method' => 'post']) ?>
            <fieldset>
                <legend><?= __('Add Category') ?></legend>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('description'); ?>

                <?= $this->Form->control('parent_id', ['options' => $parentCategories, 'empty' => true]); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
