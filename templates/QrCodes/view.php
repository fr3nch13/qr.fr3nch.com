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
        (
            $this->ActiveUser->getUser('id') === $qrCode->user_id ||
            $this->ActiveUser->getUser('is_admin') == true
        )
) : ?>
<div class="product-view">
    <!-- Page Actions -->
    <div class="row g-5 mb-4 justify-content-center justify-content-lg-between">
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
    <div class="
        row
        g-5
        justify-content-center
        justify-content-lg-between
        product
        product-<?= ($qrCode->is_active ? 'active' : 'inactive')?>
        ">
        <?php if (!$qrCode->is_active) : ?>
        <div class="ribbon red"><span><?= __('Inactive') ?></span></div>
        <?php endif; ?>
        <!-- The QR Code's images -->
        <div class="col-lg-6 position-relative">
            <div class="row g-1">
                <div class="col-md-10 order-md-2">
                    <div class="carousel">
                    <div
                        data-carousel='{
                            "mouseDrag": true,
                            "navContainer": "#nav-images",
                            "gutter": 8,
                            "loop": true,
                            "items": 1
                        }'>
                        <?php foreach ($qrCode->qr_images as $qrImage) : ?>
                            <?php
                            // make sure the image active, and exists
                            if (!$qrImage->is_active || !$qrImage->path) {
                                continue;
                            }
                            ?>

                            <?= $this->Template->objectComment('QrImage/show/large') ?>
                        <div class="item text-center">
                            <img
                                class="img-fluid img-thumbnail"
                                src="<?= $this->Url->build([
                                    'controller' => 'QrImages',
                                    'action' => 'show', $qrImage->id,
                                    ]) ?>"
                                alt="<?= $qrImage->name ?>">
                        </div>
                        <?php endforeach; ?>

                        <div class="item text-center">
                            <?= $this->Template->objectComment('QrCode/show') ?>
                            <img
                                class="img-fluid"
                                src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>"
                                alt="<?= __('The QR Code'); ?>">
                        </div>

                    </div>
                    </div>
                </div>
                <div class="col-md-2 order-md-1">
                    <div class="carousel-thumbs d-flex flex-row flex-md-column" id="nav-images">
                        <?php foreach ($qrCode->qr_images as $qrImage) : ?>
                            <?php
                            // make sure it's active, and exists
                            if (!$qrImage->is_active || !$qrImage->path) {
                                continue;
                            }
                            ?>
                            <?= $this->Template->objectComment('QrImage/show/thumb') ?>
                        <div>
                            <img
                                class="img-fluid"
                                src="<?= $this->Url->build([
                                    'controller' => 'QrImages',
                                    'action' => 'show', $qrImage->id,
                                    ]) ?>"
                                alt="<?= $qrImage->name ?>">
                        </div>
                        <?php endforeach; ?>
                        <div>
                            <?= $this->Template->objectComment('QrCode/show') ?>
                            <img
                                class="img-fluid"
                                src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>"
                                alt="<?= __('The QR Code'); ?>">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code details -->

        <div class="col-lg-6 col-xl-6">
            <h1 class="mb-1 <?= ($qrCode->is_active ? 'active' : 'inactive')?>"><?= h($qrCode->name) ?></h1>

            <p class="text-secondary mb-3"><?= $this->Text->autoParagraph(h($qrCode->description)); ?></p>

            <div class="accordion mb-3" id="accordion-1">

                <!-- Tags -->
                <?php if (!empty($qrCode->tags)) : ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-1-1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-1-1" aria-expanded="false" aria-controls="collapse-1-1">
                            <?= __('Tags') ?>
                        </button>
                    </h2>
                    <div id="collapse-1-1" class="accordion-collapse collapse" aria-labelledby="heading-1-1"
                    data-bs-parent="#accordion-1">
                        <div class="accordion-body">
                            <div class="row g-1 align-items-center">
                                <div class="col text-center">
                                    <?php foreach ($qrCode->tags as $tag) : ?>
                                        <?= $this->Html->link(
                                            $tag->name,
                                            [
                                                'action' => 'index',
                                                '?' => ['tag' => $tag->name],
                                            ],
                                            [
                                                'class' => 'me-1 btn btn-sm btn-light btn-outline-secondary rounded-pill',
                                                'role' => 'button',
                                            ]
                                        ); ?>
                                    <?php endforeach; ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Details -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-1-2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-1-2" aria-expanded="false" aria-controls="collapse-1-2">
                            <?= __('Additional Information') ?>
                        </button>
                    </h2>
                    <div id="collapse-1-2" class="accordion-collapse collapse" aria-labelledby="heading-1-2"
                    data-bs-parent="#accordion-1">
                        <div class="accordion-body">
                            <dl class="row">
                                <dt class="col-sm-3"><?= __('Key') ?></dt>
                                <dd class="col-sm-9"><?= h($qrCode->qrkey) ?></dd>

                                <dt class="col-sm-3"><?= __('Source') ?></dt>
                                <dd class="col-sm-9"><?= $qrCode->hasValue('source') ?
                                    $qrCode->source->name :
                                '' ?></dd>

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
                    <?= $this->Template->objectComment('QrCode/forward') ?>
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
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
