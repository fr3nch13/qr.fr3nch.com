<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Source $source
 * @var \Cake\Collection\CollectionInterface|array<string> $qrCodes
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Form->create($source) ?>
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
    <div class="col">
        <?= $this->Form->control('description', [
            'required' => true,
            'spacing' => 'mb-2',
            'label' => __('Description'),
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col text-end btn-group" role="group" aria-label="Source Options">
        <?= $this->Html->link(__('Delete'), [
            'action' => 'delete',
            $source->id,
        ], [
            'class' => 'btn btn-warning',
            'confirm' => __('Are you sure you want to delete the image: {0}', [
                $source->name,
            ]),
        ]) ?>
        <?= $this->Form->button(__('Save'), [
            'type' => 'submit',
            'class' => 'btn btn-primary',
        ]); ?>
    </div>
</div>
<?= $this->Form->end() ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
