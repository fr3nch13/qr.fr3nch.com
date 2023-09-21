<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CategoriesQrCode> $categoriesQrCodes
 */
?>
<div class="categoriesQrCodes index content">
    <?= $this->Html->link(__('New Categories Qr Code'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Categories Qr Codes') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('category_id') ?></th>
                    <th><?= $this->Paginator->sort('qr_code_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categoriesQrCodes as $categoriesQrCode): ?>
                <tr>
                    <td><?= $this->Number->format($categoriesQrCode->id) ?></td>
                    <td><?= $categoriesQrCode->hasValue('category') ? $this->Html->link($categoriesQrCode->category->name, ['controller' => 'Categories', 'action' => 'view', $categoriesQrCode->category->id]) : '' ?></td>
                    <td><?= $categoriesQrCode->hasValue('qr_code') ? $this->Html->link($categoriesQrCode->qr_code->name, ['controller' => 'QrCodes', 'action' => 'view', $categoriesQrCode->qr_code->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $categoriesQrCode->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $categoriesQrCode->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $categoriesQrCode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoriesQrCode->id)]) ?>
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
