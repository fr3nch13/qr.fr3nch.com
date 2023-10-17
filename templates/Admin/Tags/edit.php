<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 * @var \Cake\Collection\CollectionInterface|array<string> $qrCodes
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}
?>
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
    <div class="col text-end btn-group" role="group" aria-label="Tag Options">
        <?= $this->Html->link(__('Delete'), [
            'action' => 'delete',
            $tag->id,
        ], [
            'class' => 'btn btn-warning',
            'confirm' => __('Are you sure you want to delete the image: {0}', [
                $tag->name,
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
