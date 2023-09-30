<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */
?>
<div class="qrCodes index content">
    <?= $this->Html->link(__('New QR Code'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('QR Codes') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('qrkey') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('source_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($qrCodes as $qrCode): ?>
                <tr>
                    <td><?= $this->Number->format($qrCode->id) ?></td>
                    <td><?= h($qrCode->qrkey) ?></td>
                    <td><?= h($qrCode->name) ?></td>
                    <td><?= h($qrCode->created) ?></td>
                    <td><?= h($qrCode->modified) ?></td>
                    <td><?= $qrCode->hasValue('source') ? $this->Html->link($qrCode->source->name, ['controller' => 'Sources', 'action' => 'view', $qrCode->source->id]) : '' ?></td>
                    <td><?= $qrCode->hasValue('user') ? $this->Html->link($qrCode->user->name, ['controller' => 'Users', 'action' => 'view', $qrCode->user->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Follow'), ['action' => 'forward', $qrCode->qrkey]) ?>
                        <?= $this->Html->link(__('View'), ['action' => 'view', $qrCode->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $qrCode->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $qrCode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $qrCode->id)]) ?>
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
