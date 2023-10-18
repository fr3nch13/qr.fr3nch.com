<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/view');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<!-- The QR Code Details -->
<div class="
    row
    g-5
    justify-content-center
    justify-content-lg-between
    product
    product-<?= ($qrCode->is_active ? 'active' : 'inactive')?>
    ">

    <!-- The QR Code's images -->
    <div class="col-lg-6 position-relative">
        <h1 class="
            d-block
            d-lg-none
            mb-1
            <?= ($qrCode->is_active ? 'active' : 'inactive')?>"><?= h($qrCode->name) ?></h1>
        <div class="row">

            <!-- smaller images -->
            <div class="col-md-2">
                <div
                    id="nav-images"
                    class="carousel-thumbs d-flex flex-row flex-md-column">
                    <?php foreach ($qrCode->qr_images as $qrImage) : ?>
                        <?php
                        // make sure it's active, and exists
                        if (!$qrImage->is_active || !$qrImage->path_sm || !$qrImage->path_lg) {
                            continue;
                        }
                        ?>

                        <?= $this->Template->objectComment('QrImage/show/thumb/sm') ?>
                    <div>
                        <img
                            class="img-thumbnail<?php // use `img-thumbnail`, not `img-fluid` as it's redundant. ?>"
                            src="<?= $this->Url->build([
                                'controller' => 'QrImages',
                                'action' => 'show',
                                $qrImage->id,
                                '?' => ['thumb' => 'sm'],
                                ]) ?>"
                            alt="<?= $qrImage->name ?>">
                    </div>
                    <?php endforeach; ?>

                    <?= $this->Template->objectComment('QrCode/show/thumb/sm') ?>
                    <div>
                        <img
                            class="img-thumbnail<?php // use `img-thumbnail`, not `img-fluid` as it's redundant. ?>"
                            src="<?= $this->Url->build([
                                'action' => 'show',
                                $qrCode->id,
                                '?' => ['thumb' => 'sm'],
                                ]) ?>"
                            alt="<?= __('The QR Code'); ?>">
                    </div>

                </div>
            </div>

            <!-- larger images -->
            <div class="col-md-10">
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
                        if (!$qrImage->is_active || !$qrImage->path_sm || !$qrImage->path_lg) {
                            continue;
                        }
                        ?>

                        <?= $this->Template->objectComment('QrImage/show/thumb/lg') ?>
                    <div class="item text-center">
                        <img
                            class="img-thumbnail<?php // use `img-thumbnail`, not `img-fluid` as it's redundant. ?>"
                            src="<?= $this->Url->build([
                                'controller' => 'QrImages',
                                'action' => 'show',
                                $qrImage->id,
                                '?' => ['thumb' => 'lg'],
                                ]) ?>"
                            alt="<?= $qrImage->name ?>">
                    </div>
                    <?php endforeach; ?>

                    <?= $this->Template->objectComment('QrCode/show/thumb/lg') ?>
                    <div class="item text-center">
                        <img
                            class="img-thumbnail<?php // use `img-thumbnail`, not `img-fluid` as it's redundant. ?>"
                            src="<?= $this->Url->build([
                                'action' => 'show',
                                $qrCode->id,
                                '?' => ['thumb' => 'lg'],
                            ]) ?>"
                            alt="<?= __('The QR Code'); ?>">
                    </div>

                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code details -->

    <div class="col-lg-6">
        <h1 class="
            d-none
            d-lg-block
            mb-0
            mb-lg-1
            <?= ($qrCode->is_active ? 'active' : 'inactive')?>"><?= h($qrCode->name) ?></h1>

        <div class="text-secondary mb-3"><?= $this->Text->autoParagraph(h($qrCode->description)) ?></div>

        <div class="accordion mb-3" id="accordion-1">

            <!-- Details -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-1-2">
                    <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-1-2"
                        aria-expanded="false"
                        aria-controls="collapse-1-2"><?= __('Additional Information') ?></button>
                </h2>
                <div
                    id="collapse-1-2"
                    class="accordion-collapse collapse"
                    aria-labelledby="heading-1-2"
                    data-bs-parent="#accordion-1">
                    <div class="accordion-body">
                        <dl class="row">
                            <dt class="col-4 col-md-3"><?= __('Key') ?></dt>
                            <dd class="col-8 col-md-9"><?= h($qrCode->qrkey) ?> </dd>

                            <dt class="col-4 col-md-3"><?= __('Source') ?></dt>
                            <dd class="col-8 col-md-9"><?php
                            if ($qrCode->hasValue('source')) {
                                echo $this->Html->link($qrCode->source->name, [
                                    'action' => 'index',
                                    '?' => ['s' => $qrCode->source->name],
                                ]);
                            } ?> </dd>

                            <dt class="col-4 col-md-3"><?= __('Last Hit') ?></dt>
                            <dd class="col-8 col-md-9"><?php
                            if ($qrCode->hasValue('last_hit')) {
                                echo $$qrCode->last_hit->format('M d, Y');
                            } ?> </dd>

                            <dt class="col-4 col-md-3"><?= __('Created') ?></dt>
                            <dd class="col-8 col-md-9"><?php
                            if ($qrCode->hasValue('created')) {
                                echo $$qrCode->created->format('M d, Y');
                            } ?> </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <?php if (!empty($qrCode->tags)) : ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-1-1">
                    <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-1-1"
                        aria-expanded="false"
                        aria-controls="collapse-1-1"><?= __('Tags') ?></button>
                </h2>
                <div
                    id="collapse-1-1"
                    class="accordion-collapse collapse"
                    aria-labelledby="heading-1-1"
                    data-bs-parent="#accordion-1">
                    <div class="accordion-body">
                        <div class="row g-1 align-items-center">
                            <div class="col text-center">
                                <?php foreach ($qrCode->tags as $tag) : ?>
                                    <?= $this->Html->link(
                                        $tag->name,
                                        [
                                            'action' => 'index',
                                            '?' => ['t' => $tag->name],
                                        ],
                                        [
                                            'class' => 'mr-1 mb-1 rounded-pill ' .
                                                'btn btn-sm btn-light btn-outline-secondary',
                                            'role' => 'button',
                                        ]
                                    ) ?>

                                <?php endforeach; ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Action Items -->
        <div class="row g-1 align-items-center">
            <div class="col">
                <div class="d-grid">
                <?= $this->Template->objectComment('QrCode/forward') ?>
                    <?= $this->Html->link(
                        __('Follow Code'),
                        ['action' => 'forward', $qrCode->qrkey],
                        [
                            'class' => 'btn btn-primary btn-block rounded-pill',
                            'role' => 'button',
                        ]
                    ) ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
