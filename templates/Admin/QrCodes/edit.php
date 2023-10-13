<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var \Cake\Collection\CollectionInterface|array<string> $sources
 * @var \Cake\Collection\CollectionInterface|array<string> $users
 * @var \Cake\Collection\CollectionInterface|array<string> $tags
 */

if (!$this->getRequest()->is('ajax')) {
    $this->extend('/Admin/QrCodes/details');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="card bg-opaque-white">
    <div class="card-body bg-white p-2 p-lg-5">
        <?= $this->Form->create($qrCode) ?>
        <div class="row">
            <div class="col-4">
                <?= $this->Form->control('qrkey', [
                    'readonly' => true,
                    'spacing' => 'mb-2',
                    'label' => __('Unique Key'),
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
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-4 form-switch mt-3">
                <?= $this->Form->control('is_active', [
                    'required' => true,
                    'spacing' => 'mb-2',
                    'label' => __('Active?'),
                ]); ?>
            </div>
            <div class="col-8">
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
            <div class="col">
                <?= $this->Form->button(__('Submit'), [
                    'type' => 'submit',
                    'class' => 'btn btn-lg btn-primary btn-block',
                ]); ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
