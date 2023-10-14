<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrImage $qrImage
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}
// TODO: make the edit form for images
// labels: images, templates
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Form->create($qrImage) ?>
<div class="row">
    <div class="col-4 form-switch mt-4">
        <?= $this->Form->control('is_active', [
            'spacing' => 'mb-2',
            'label' => __('Active?'),
        ]); ?>
    </div>
    <div class="col-8">
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
