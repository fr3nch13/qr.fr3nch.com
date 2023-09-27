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

<!-- START: App.element/flash/warning -->

<div class="message warning" onclick="this.classList.add('hidden');"><?= $message ?></div>

<!-- END: App.element/flash/warning -->
