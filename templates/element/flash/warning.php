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
<section class="flash-message py-15 py-xl-15" onclick="this.classList.add('hidden');">
    <div class="message warning"><?= $message ?></div>
</section>
<?= $this->Template->templateComment(false, __FILE__); ?>
