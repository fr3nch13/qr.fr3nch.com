<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Source $source
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/view');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Source'), [
                'action' => 'edit',
                $source->id,
            ], [
                'class' => 'side-nav-item',
            ]) ?>
            <?= $this->Form->postLink(__('Delete Source'), [
                'action' => 'delete',
                $source->id,
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?', $source->id),
                'class' => 'side-nav-item',
            ]) ?>
            <?= $this->Html->link(__('List Sources'), [
                'action' => 'index',
            ], [
                'class' => 'side-nav-item',
            ]) ?>
            <?= $this->Html->link(__('New Source'), [
                'action' => 'add',
            ], [
                'class' => 'side-nav-item',
            ]) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="sources view content">
            <h3><?= h($source->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($source->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($source->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($source->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($source->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($source->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related QR Codes') ?></h4>
                <?php if (!empty($source->qr_codes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Key') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Source Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($source->qr_codes as $qrCodes) : ?>
                        <tr>
                            <td><?= h($qrCodes->id) ?></td>
                            <td><?= h($qrCodes->qrkey) ?></td>
                            <td><?= h($qrCodes->name) ?></td>
                            <td><?= h($qrCodes->description) ?></td>
                            <td><?= h($qrCodes->created) ?></td>
                            <td><?= h($qrCodes->modified) ?></td>
                            <td><?= h($qrCodes->url) ?></td>
                            <td><?= h($qrCodes->source_id) ?></td>
                            <td><?= h($qrCodes->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), [
                                    'controller' => 'QrCodes',
                                    'action' => 'view',
                                    $qrCodes->id,
                                    ]) ?>
                                <?= $this->Html->link(__('Edit'), [
                                    'controller' => 'QrCodes',
                                    'action' => 'edit',
                                    $qrCodes->id,
                                    ]) ?>
                                <?= $this->Form->postLink(__('Delete'), [
                                    'controller' => 'QrCodes',
                                    'action' => 'delete',
                                    $qrCodes->id,
                                    ], [
                                        'confirm' => __('Are you sure you want to delete # {0}?', $qrCodes->id),
                                    ]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
