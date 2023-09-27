<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCodesTag> $qrCodesTags
 */
?>
<div class="qrCodesTags index content">
    <?= $this->Html->link(__('New QR Codes Tag'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('QR Codes Tags') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('tag_id') ?></th>
                    <th><?= $this->Paginator->sort('qr_code_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($qrCodesTags as $qrCodesTag): ?>
                <tr>
                    <td><?= $this->Number->format($qrCodesTag->id) ?></td>
                    <td><?= $qrCodesTag->hasValue('tag') ? $this->Html->link($qrCodesTag->tag->name, ['controller' => 'Tags', 'action' => 'view', $qrCodesTag->tag->id]) : '' ?></td>
                    <td><?= $qrCodesTag->hasValue('qr_code') ? $this->Html->link($qrCodesTag->qr_code->name, ['controller' => 'QrCodes', 'action' => 'view', $qrCodesTag->qr_code->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $qrCodesTag->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $qrCodesTag->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $qrCodesTag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $qrCodesTag->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
