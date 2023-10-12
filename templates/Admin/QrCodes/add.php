<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var \Cake\Collection\CollectionInterface|array<string> $sources
 * @var \Cake\Collection\CollectionInterface|array<string> $users
 * @var \Cake\Collection\CollectionInterface|array<string> $tags
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}

$this->assign('page_title', __('Add a QR Code'));

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="card bg-opaque-white">
    <div class="card-body bg-white p-2 p-lg-5">
        <div class="row">
            <div class="col">
                <?= $this->Form->create($qrCode) ?>

                <?= $this->Form->control('qrkey', [
                    'required' => true,
                    'spacing' => 'mb-2',
                    'label' => ['floating' => true],
                ]); ?>

                <?= $this->Form->control('name'); ?>

                <?= $this->Form->control('description'); ?>

                <?= $this->Form->control('url', ['type' => 'text']); ?>

                <?= $this->Form->control('source_id', ['options' => $sources]); ?>

                <?= $this->Form->control('tags._ids', ['options' => $tags]); ?>

                <?= $this->Form->button(__('Submit')) ?>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
