<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Source $source
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sources'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="sources form content">
            <?= $this->Form->create($source) ?>
            <fieldset>
                <legend><?= __('Add Source') ?></legend>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('description'); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
