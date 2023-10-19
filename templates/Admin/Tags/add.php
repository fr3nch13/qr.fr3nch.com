<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 * @var \Cake\Collection\CollectionInterface|array<string> $qrCodes
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}

$this->assign('page_title', __('Add a Tag'));
$this->assign('title', $this->fetch('page_title'));
?><del></del>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Form->create($tag) ?>
<div class="row">
    <div class="col">
        <?= $this->Form->control('name', [
            'required' => true,
            'spacing' => 'mb-2',
            'label' => __('Name'),
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col text-end">
        <?= $this->Form->button(__('Save'), [
            'type' => 'submit',
            'class' => 'btn btn-primary btn-block',
        ]); ?>
    </div>
</div>
<?= $this->Form->end() ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
