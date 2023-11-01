<?php
/**
 * @var \App\View\AppView $this
 */

$value ?? "";

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="input-group input-group-sm">
  <?= $this->Form->text('copy_text', [
    'value' => $value,
    'aria-label' => __('Copy value'),
]); ?>
<button
class="btn btn-outline-secondary copy-text"
data-bs-toggle="tooltip"
data-bs-trigger="manual"
title="Copied"
type="button"><?= __('Copy') ?></button>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
