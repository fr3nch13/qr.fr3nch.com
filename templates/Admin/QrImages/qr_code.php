<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var iterable<\App\Model\Entity\QrImage> $qrImages
 */

if (!$this->getRequest()->is('ajax')) {
    $this->extend('/Admin/QrCodes/details');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>

<div class="row">
    <ul class="nav justify-content-end">
        <li class="nav-item">
            <?= $this->Html->link(__('Add Images'), [
                'action' => 'add',
                $qrCode->id,
            ], [
                'class' => 'nav-link pe-0 text-black',
            ]) ?>
        </li>
    </ul>
</div>

<div class="row">
    <?php foreach ($qrImages as $qrImage) : ?>
        <div class="col-md-6">
            <div class="card text-center border mb-2 shadow-sm">

            <?= $this->Template->objectComment('QrImages/entity'); ?>
                <?php if (!$qrImage->is_active) : ?>
                <div class="ribbon red"><span><?= __('Inactive') ?></span></div>
                    <?= $this->Template->objectComment('QrImages/entity/inactive'); ?>
                <?php else : ?>
                    <?= $this->Template->objectComment('QrImages/entity/active'); ?>
                <?php endif; ?>

                <div class="card-body py-5 py-md-10">
                <a
                    href="#"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-<?= $qrImage->id ?>">
                        <figure
                            class="background background-overlay"
                            style="background-image: url('<?= $this->Url->build([
                                'prefix' => false,
                                'action' => 'show',
                                $qrImage->id,
                                '?' => ['thumb' => 'md'],
                            ]) ?>')"></figure>
                    <div class="card-title text-white"><?= $qrImage->name ?></div>
                </a>
                </div>
                <div class="card-footer text-muted p-0 btn-group">
                    <?= $this->Html->link(__('Edit'), [
                        'action' => 'edit',
                        $qrImage->id,
                                ], [
                                'class' => 'btn btn-sm btn-light ajax-modal',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#edit-modal',
                                'data-ajax-target' => '#editModalBody',
                    ]) ?>
                    <?= $this->Html->link(__('Download'), [
                        'action' => 'show',
                        $qrImage->id,
                        '?' => ['download' => true],
                    ], [
                        'class' => 'btn btn-sm btn-light',
                    ]) ?>

                    <?= $this->Form->postLink(__('Delete'), [
                        'action' => 'delete',
                        $qrImage->id,
                    ], [
                        'class' => 'btn btn-sm btn-warning',
                        'confirm' => __('Are you sure you want to delete the image: {0}', [
                            $qrImage->name,
                        ]),
                    ]) ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php $this->start('modal') ?>

<?php foreach ($qrImages as $qrImage) : ?>
<!-- Image Viewer -->
<div
    class="modal fade"
    id="modal-<?= $qrImage->id ?>"
    tabindex="-1"
    aria-labelledby="modalLabel-<?= $qrImage->id ?>-Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" data-bs-dismiss="modal">
            <span id="modalLabel-<?= $qrImage->id ?>-Label" class="d-none"><?= $qrImage->name ?></span>
            <button
                type="button"
                class="bi bi-x modal-close text-white"
                aria-label="Close"></button>
            <div class="modal-body m-0 p-0">
                <div class="row m-0 p-0">
                    <div class="col m-0 p-0">
                        <img
                            class="img-fluid"
                            src="<?= $this->Url->build([
                                'action' => 'show',
                                $qrImage->id,
                                '?' => ['thumb' => 'lg'],
                                ]) ?>"
                            alt="<?= $qrImage->name ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<!-- Edit Modal -->

<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button
                type="button"
                class="bi bi-x modal-close"
                data-bs-dismiss="modal"
                aria-label="Close"></button>
            <div class="modal-body p-4">
                <span id="editModalLabel" class="invisible"><?= __('Edit') ?></span>
                <div id="editModalBody"></div>
            </div>
        </div>
    </div>
</div>

<?php $this->end(); // modal ?>
  <?= $this->Template->templateComment(false, __FILE__); ?>
