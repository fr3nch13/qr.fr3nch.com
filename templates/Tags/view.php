<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Tag'), ['action' => 'edit', $tag->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Tag'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Tag'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tags view content">
            <h3><?= h($tag->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($tag->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($tag->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($tag->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($tag->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related QR Codes') ?></h4>
                <?php if (!empty($tag->qr_codes)) : ?>
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
                            <th><?= __('Bitly Id') ?></th>
                            <th><?= __('Source Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($tag->qr_codes as $qrCodes) : ?>
                        <tr>
                            <td><?= h($qrCodes->id) ?></td>
                            <td><?= h($qrCodes->key) ?></td>
                            <td><?= h($qrCodes->name) ?></td>
                            <td><?= h($qrCodes->description) ?></td>
                            <td><?= h($qrCodes->created) ?></td>
                            <td><?= h($qrCodes->modified) ?></td>
                            <td><?= h($qrCodes->url) ?></td>
                            <td><?= h($qrCodes->bitly_id) ?></td>
                            <td><?= h($qrCodes->source_id) ?></td>
                            <td><?= h($qrCodes->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'QrCodes', 'action' => 'view', $qrCodes->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'QrCodes', 'action' => 'edit', $qrCodes->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'QrCodes', 'action' => 'delete', $qrCodes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $qrCodes->id)]) ?>
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
