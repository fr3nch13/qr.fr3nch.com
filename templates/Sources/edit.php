<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Source $source
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $source->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $source->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Sources'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="sources form content">
            <?= $this->Form->create($source, ['method' => 'patch']) ?>
            <fieldset>
                <legend><?= __('Edit Source') ?></legend>
                <?php
                    echo $this->Form->control('key');
                    echo $this->Form->control('qr_code_key_field');
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
