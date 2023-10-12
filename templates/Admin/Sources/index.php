<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Source> $sources
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="sources index content">
    <?= $this->Html->link(__('New Source'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Sources') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sources as $source) : ?>
                <tr>
                    <td><?= $this->Number->format($source->id) ?></td>
                    <td><?= h($source->name) ?></td>
                    <td><?= h($source->created) ?></td>
                    <td><?= h($source->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $source->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $source->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), [
                            'action' => 'delete',
                            $source->id,
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?', $source->id),
                        ]) ?>
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
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, ' .
            'showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
