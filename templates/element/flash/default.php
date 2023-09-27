<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<!-- START: App.element/flash/default -->

<div class="<?= h($class) ?>" onclick="this.classList.add('hidden');"><?= $message ?></div>

<!-- START: App.element/flash/default -->
