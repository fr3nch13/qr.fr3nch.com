<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($user, ['method' => 'patch']) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('email'); ?>

                <?= $this->Form->control('password'); ?>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
