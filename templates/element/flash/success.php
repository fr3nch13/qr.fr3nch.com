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

<!-- START: App.element/flash/success -->

<div class="message success" onclick="this.classList.add('hidden')"><?= $message ?></div>

<!-- END: App.element/flash/success -->
