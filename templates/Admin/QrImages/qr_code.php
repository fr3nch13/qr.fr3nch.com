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
<div class="card bg-opaque-white">
    <div class="card-body bg-white p-2 p-lg-5">
        <div class="row">
            <?php foreach ($qrImages as $qrImage) : ?>
                <div class="col">
                    <div class="card text-center border">
                        <div class="card-body">
                            <div class="card-title"><?= $qrImage->name ?></div>
                            <img
                                class="img-fluid"
                                src="<?= $this->Url->build([
                                    'action' => 'show',
                                    $qrImage->id,
                                    '?' => ['thumb' => 'sm'],
                                    ]) ?>"
                                alt="<?= $qrImage->name ?>">
                            <div class="card-text text-muted">
                                Options
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
