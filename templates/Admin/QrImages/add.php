<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrImage $qrImage
 */
if (!$this->getRequest()->is('ajax')) {
    $this->extend('/Admin/QrCodes/details');
}
// TODO: make the add form for images
// labels: images, templates
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Form->create($qrImage, ['type' => 'file']) ?>
<?php if ($qrImage->hasErrors()) : ?>
<div class="row my-2">
    <div class="col">
        <?php foreach($qrImage->getErrors() as $field => $error) : ?>
            <?php foreach($error as $msg) : ?>
                <p class="text-danger"><?= $msg ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<div class="row">
    <div class="col">
        <div class="verify-sub-box">
                <div class="file-loading">
                    <?= $this->Form->file('newimages[]', [
                        'class' => 'fileinput',
                        'accept' => '.jpg,.jpeg,.svg,.gif,.png',
                        'multiple' => true,
                    ]) ?>
                </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col text-end">
        <?= $this->Form->button(__('Save'), [
            'type' => 'submit',
            'class' => 'btn btn-primary btn-block',
        ]); ?>
    </div>
</div>

<?= $this->Form->end() ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
