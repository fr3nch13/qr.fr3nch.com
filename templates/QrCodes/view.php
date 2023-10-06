<?php
use Cake\Routing\Router;
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/view');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>



    <?php if (
        $this->ActiveUser->getUser() &&
        $this->ActiveUser->getUser('id') === $qrCode->user_id
) : ?>
    <!-- Page Actions -->
    <div class="row g-5 justify-content-center justify-content-lg-between">
        <div class="col-md-12 text-md-end">
            <ul class="list-inline">
                <li class="list-inline-item ms-2">
                    <?= $this->Html->link(__('Edit'), [
                        'controller' => 'QrCodes',
                        'action' => 'edit',
                        $qrCode->id,
                    ], [
                        'class' => 'underline text-black',
                    ]); ?>
                </li>
                <li class="list-inline-item ms-2">
                    <?= $this->Form->postLink(__('Delete'), [
                        'controller' => 'QrCodes',
                        'action' => 'delete',
                        $qrCode->id,
                    ], [
                        'confirm' => __('Are you sure you want to delete # {0}?', $qrCode->id),
                        'class' => 'underline text-red',
                    ]); ?>
                </li>
            </ul>
        </div>
    </div>
    <?php endif; // Page Actions ?>

    <!-- The QR Code Details -->
    <div class="row g-5 justify-content-center justify-content-lg-between">

        <!-- The QR Code's images -->
        <div class="col-lg-6 position-relative">
            <div class="row g-1">
                <div class="col-md-10 order-md-2">
                    <div class="carousel">
                    <div
                        data-carousel='{"mouseDrag": true, "navContainer": "#nav-images", "gutter": 8, "loop": true, "items": 1}'>
                        <div class="item text-center">
                            <img class="img-fluid" src="./assets/images/products/product-9.jpg" alt="Image">
                        </div>

                        <div class="item text-center">
                            <img class="img-fluid" src="./assets/images/products/product-9-2.jpg" alt="Image">
                        </div>

                        <div class="item text-center">
                            <img class="img-fluid" src="./assets/images/products/product-9-3.jpg" alt="Image">
                        </div>

                        <div class="item text-center">
                            <img class="img-fluid" src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>" alt="<?= __('The QR Code'); ?>">
                        </div>

                    </div>
                    </div>
                </div>
                <div class="col-md-2 order-md-1">
                    <div class="carousel-thumbs d-flex flex-row flex-md-column" id="nav-images">
                        <div>
                            <img class="img-fluid" src="./assets/images/products/product-9.jpg" alt="Image">
                        </div>
                        <div>
                            <img class="img-fluid" src="./assets/images/products/product-9-2.jpg" alt="Image">
                        </div>
                        <div>
                            <img class="img-fluid" src="./assets/images/products/product-9-3.jpg" alt="Image">
                        </div>
                        <div>
                            <img class="img-fluid" src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>" alt="<?= __('The QR Code'); ?>">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code details -->

        <div class="col-lg-6 col-xl-5">
            <h1 class="mb-1"><?= h($qrCode->name) ?></h1>

            <p class="text-secondary mb-3"><?= $this->Text->autoParagraph(h($qrCode->description)); ?></p>

            <div class="accordion mb-3" id="accordion-1">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-1-1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-1-1" aria-expanded="false" aria-controls="collapse-1-1">
                            <?= __('Additional Information') ?>
                        </button>
                    </h2>
                    <div id="collapse-1-1" class="accordion-collapse collapse" aria-labelledby="heading-1-1"
                    data-bs-parent="#accordion-1">
                        <div class="accordion-body">
                            <dl class="row">
                                <dt class="col-sm-3"><?= __('Key') ?></dt>
                                <dd class="col-sm-9"><?= h($qrCode->qrkey) ?></dd>

                                <dt class="col-sm-3"><?= __('Source') ?></dt>
                                <dd class="col-sm-9"><?= $qrCode->hasValue('source') ? $qrCode->source->name : '' ?></dd>


                                <dt class="col-sm-3"><?= __('Created') ?></dt>
                                <dd class="col-sm-9"><?= h($qrCode->created) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Items -->
            <div class="row g-1 align-items-center">
                <div class="col" aria-label="QR Code Options">
                    <div class="d-grid">
                        <?= $this->Html->link(
                            __('Follow Code'),
                            ['action' => 'forward', $qrCode->qrkey],
                            [
                                'class' => 'btn btn-primary btn-block rounded-pill',
                                'role' => 'button',
                            ]
                        ); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>


<!--
<div class="row">
    <div class="column column-80">
        <div class="qrCodes view content">
            <h3><?= h($qrCode->name) ?></h3>
            <div class="row">
                <div class="column column-50">
                        <table>
                            <tr>
                                <th><?= __('Key') ?></th>
                                <td><?= h($qrCode->qrkey) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Name') ?></th>
                                <td><?= h($qrCode->name) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Source') ?></th>
                                <td><?= $qrCode->hasValue('source') ? $this->Html->link($qrCode->source->name, ['controller' => 'Sources', 'action' => 'view', $qrCode->source->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('User') ?></th>
                                <td><?= $qrCode->hasValue('user') ? $this->Html->link($qrCode->user->name, ['controller' => 'Users', 'action' => 'view', $qrCode->user->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Id') ?></th>
                                <td><?= $this->Number->format($qrCode->id) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Created') ?></th>
                                <td><?= h($qrCode->created) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Modified') ?></th>
                                <td><?= h($qrCode->modified) ?></td>
                            </tr>
                        </table>
                </div>
                <div class="column column-50">
                    <img src="<?= Router::url([
                        'controller' => 'QrCodes',
                        'action' => 'show',
                        $qrCode->id,
                    ]) ?>">
                </div>
            </div>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($qrCode->description)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Url') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($qrCode->url)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Categories') ?></h4>
                <?php if (!empty($qrCode->categories)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($qrCode->categories as $categories) : ?>
                        <tr>
                            <td><?= h($categories->id) ?></td>
                            <td><?= h($categories->name) ?></td>
                            <td><?= h($categories->description) ?></td>
                            <td><?= h($categories->created) ?></td>
                            <td><?= h($categories->modified) ?></td>
                            <td><?= h($categories->parent_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Categories', 'action' => 'view', $categories->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Categories', 'action' => 'edit', $categories->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Categories', 'action' => 'delete', $categories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categories->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Tags') ?></h4>
                <?php if (!empty($qrCode->tags)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($qrCode->tags as $tags) : ?>
                        <tr>
                            <td><?= h($tags->id) ?></td>
                            <td><?= h($tags->name) ?></td>
                            <td><?= h($tags->created) ?></td>
                            <td><?= h($tags->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Tags', 'action' => 'view', $tags->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Tags', 'action' => 'edit', $tags->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tags', 'action' => 'delete', $tags->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tags->id)]) ?>
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
-->
<?= $this->Template->templateComment(false, __FILE__); ?>
