<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCodesTag $qrCodesTag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit QR Codes Tag'), ['action' => 'edit', $qrCodesTag->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete QR Codes Tag'), ['action' => 'delete', $qrCodesTag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $qrCodesTag->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List QR Codes Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New QR Codes Tag'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="qrCodesTags view content">
            <h3><?= h($qrCodesTag->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Tag') ?></th>
                    <td><?= $qrCodesTag->hasValue('tag') ? $this->Html->link($qrCodesTag->tag->name, ['controller' => 'Tags', 'action' => 'view', $qrCodesTag->tag->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('QR Code') ?></th>
                    <td><?= $qrCodesTag->hasValue('qr_code') ? $this->Html->link($qrCodesTag->qr_code->name, ['controller' => 'QrCodes', 'action' => 'view', $qrCodesTag->qr_code->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($qrCodesTag->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
