<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoriesQrCode $categoriesQrCode
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string[]|\Cake\Collection\CollectionInterface $qrCodes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $categoriesQrCode->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $categoriesQrCode->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Categories Qr Codes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="categoriesQrCodes form content">
            <?= $this->Form->create($categoriesQrCode, ['method' => 'patch']) ?>
            <fieldset>
                <legend><?= __('Edit Categories Qr Code') ?></legend>
                <?php
                    echo $this->Form->control('category_id', ['options' => $categories]);
                    echo $this->Form->control('qr_code_id', ['options' => $qrCodes]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
