<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var string[]|\Cake\Collection\CollectionInterface $sources
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string[]|\Cake\Collection\CollectionInterface $tags
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $qrCode->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $qrCode->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List QR Codes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="qrCodes form content">
            <?= $this->Form->create($qrCode, ['method' => 'patch']) ?>
            <fieldset>
                <legend><?= __('Edit QR Code') ?></legend>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('description'); ?>

                <?= $this->Form->control('url'); ?>

                <?= $this->Form->control('bitly_id', [
                    'type' => 'text,'
                ]); ?>

                <?= $this->Form->control('source_id', ['options' => $sources]); ?>

                <?= $this->Form->control('categories._ids', ['options' => $categories]); ?>

                <?= $this->Form->control('tags._ids', ['options' => $tags]); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
