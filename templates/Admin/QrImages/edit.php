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
<?= $this->Form->create($qrImage, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit QR Image') ?></legend>

        <?= $this->Form->control('name'); ?>

        <?= $this->Form->control('file', ['type' => 'file']); ?>

    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
