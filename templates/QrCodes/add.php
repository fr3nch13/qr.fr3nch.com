<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var \Cake\Collection\CollectionInterface|string[] $sources
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $categories
 * @var \Cake\Collection\CollectionInterface|string[] $tags
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Qr Codes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="qrCodes form content">
            <?= $this->Form->create($qrCode) ?>
            <fieldset>
                <legend><?= __('Add Qr Code') ?></legend>

                <?= $this->Form->control('key'); ?>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('description'); ?>

                <?= $this->Form->control('url'); ?>

                <?= $this->Form->control('bitly_id'); ?>

                <?= $this->Form->control('source_id', ['options' => $sources]); ?>

                <?= $this->Form->control('categories._ids', ['options' => $categories]); ?>

                <?= $this->Form->control('tags._ids', ['options' => $tags]); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
