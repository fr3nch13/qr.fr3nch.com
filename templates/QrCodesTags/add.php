<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCodesTag $qrCodesTag
 * @var \Cake\Collection\CollectionInterface|string[] $tags
 * @var \Cake\Collection\CollectionInterface|string[] $qrCodes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Qr Codes Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="qrCodesTags form content">
            <?= $this->Form->create($qrCodesTag) ?>
            <fieldset>
                <legend><?= __('Add Qr Codes Tag') ?></legend>

                <?= $this->Form->control('tag_id', ['options' => $tags]); ?>

                <?= $this->Form->control('qr_code_id', ['options' => $qrCodes]);?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
