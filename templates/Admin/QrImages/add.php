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
<div class="row">
    <div class="col">
        <div class="verify-sub-box">
                <div class="file-loading">
                    <input id="multiplefileupload" class="fileinput" type="file" accept=".jpg,.gif,.png" multiple>
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
