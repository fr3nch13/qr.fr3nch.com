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
            <?= $this->Html->link(__('List Qr Codes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="qrCodes form content">
            <?= $this->Form->create($qrCode) ?>
            <fieldset>
                <legend><?= __('Edit Qr Code') ?></legend>
                <?php
                    echo $this->Form->control('key');
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('url');
                    echo $this->Form->control('bitly_id');
                    echo $this->Form->control('source_id', ['options' => $sources, 'empty' => true]);
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                    echo $this->Form->control('categories._ids', ['options' => $categories]);
                    echo $this->Form->control('tags._ids', ['options' => $tags]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
