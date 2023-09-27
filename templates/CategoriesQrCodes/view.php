<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CategoriesQrCode $categoriesQrCode
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Categories QR Code'), ['action' => 'edit', $categoriesQrCode->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Categories QR Code'), ['action' => 'delete', $categoriesQrCode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoriesQrCode->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Categories QR Codes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Categories QR Code'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="categoriesQrCodes view content">
            <h3><?= h($categoriesQrCode->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= $categoriesQrCode->hasValue('category') ? $this->Html->link($categoriesQrCode->category->name, ['controller' => 'Categories', 'action' => 'view', $categoriesQrCode->category->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('QR Code') ?></th>
                    <td><?= $categoriesQrCode->hasValue('qr_code') ? $this->Html->link($categoriesQrCode->qr_code->name, ['controller' => 'QrCodes', 'action' => 'view', $categoriesQrCode->qr_code->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($categoriesQrCode->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
