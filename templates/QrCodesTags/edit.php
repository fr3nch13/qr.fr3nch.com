<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCodesTag $qrCodesTag
 * @var string[]|\Cake\Collection\CollectionInterface $tags
 * @var string[]|\Cake\Collection\CollectionInterface $qrCodes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $qrCodesTag->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $qrCodesTag->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Qr Codes Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="qrCodesTags form content">
            <?= $this->Form->create($qrCodesTag, ['method' => 'patch']) ?>
            <fieldset>
                <legend><?= __('Edit Qr Codes Tag') ?></legend>
                <?php
                    echo $this->Form->control('tag_id', ['options' => $tags]);
                    echo $this->Form->control('qr_code_id', ['options' => $qrCodes]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
