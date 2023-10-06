<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="message warning" onclick="this.classList.add('hidden');"><?= $message ?></div>
<?= $this->Template->templateComment(false, __FILE__); ?>
