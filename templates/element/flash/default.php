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
<?= $this->Template->templateComment(true, __FILE__); ?>
<section class="flash-message py-15 py-xl-15" onclick="this.classList.add('hidden');">
    <div class="<?= h($class) ?>"><?= $message ?></div>
</section>
<?= $this->Template->templateComment(false, __FILE__); ?>
