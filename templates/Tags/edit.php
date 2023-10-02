<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 * @var string[]|\Cake\Collection\CollectionInterface $qrCodes
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $tag->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tags form content">
            <?= $this->Form->create($tag, ['method' => 'patch']) ?>
            <fieldset>
                <legend><?= __('Edit Tag') ?></legend>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('qr_codes._ids', ['options' => $qrCodes]); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
