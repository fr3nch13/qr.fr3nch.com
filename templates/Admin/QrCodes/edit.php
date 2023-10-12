<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var \Cake\Collection\CollectionInterface|array<string> $sources
 * @var \Cake\Collection\CollectionInterface|array<string> $users
 * @var \Cake\Collection\CollectionInterface|array<string> $tags
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
            <?= $this->Form->create($qrCode) ?>
            <fieldset>
                <legend><?= __('Edit QR Code') ?></legend>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('description'); ?>

                <?= $this->Form->control('url'); ?>

                <?= $this->Form->control('source_id', ['options' => $sources]); ?>

                <?= $this->Form->control('tags._ids', ['options' => $tags]); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
