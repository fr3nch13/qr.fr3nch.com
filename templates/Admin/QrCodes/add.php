<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var \Cake\Collection\CollectionInterface|array<string> $sources
 * @var \Cake\Collection\CollectionInterface|array<string> $users
 * @var \Cake\Collection\CollectionInterface|array<string> $tags
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/form');
}

$this->assign('page_title', __('Add a QR Code'));
$this->assign('title', $this->fetch('page_title'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="card bg-opaque-white">
    <div class="card-body p-2 p-lg-5">
        <?= $this->Form->create($qrCode) ?>
        <div class="row">
            <div class="col-4">
                <?= $this->Form->control('qrkey', [
                    'required' => true,
                    'spacing' => 'mb-2',
                    'label' => __('Unique Key'),
                    'help' => __('Can not be edited later.'),
                    'class' => 'lowercase',
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
            <div class="col">
                <?= $this->Form->control('url', [
                    'type' => 'text',
                    'required' => true,
                    'spacing' => 'mb-2',
                    'placeholder' => 'https://',
                    'label' => __('URL'),
                    'class' => 'lowercase',
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-4 form-switch mt-4">
                <?= $this->Form->control('is_active', [
                    'spacing' => 'mb-2',
                    'label' => __('Active?'),
                ]); ?>
            </div>
            <div class="col-4 form-switch">
                <?= $this->Form->control('color', [
                    'type' => 'color',
                    'spacing' => 'mb-2',
                    'label' => __('Code Color'),
                    'class' => 'w-100',
                ]); ?>
            </div>
            <div class="col-4">
                <?= $this->Form->control('source_id', [
                    'required' => true,
                    'spacing' => 'mb-2',
                    'empty' => __('Select a Source'),
                    'options' => $sources,
                    'label' => __('Source'),
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= $this->Form->control('description', [
                    'required' => true,
                    'spacing' => 'mb-2',
                    'placeholder' => __('Describe the QR Code'),
                    'label' => __('Description'),
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= $this->Form->control('tags._ids', [
                    'options' => $tags,
                    'class' => 'form-select tags-input',
                    'data-allow-new' => 'true',
                    'data-separator' => ' |,| ',
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col text-end">
                <?= $this->Form->button(__('Save'), [
                    'type' => 'submit',
                    'class' => 'btn btn-lg btn-primary btn-block',
                ]); ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
