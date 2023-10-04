<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var \Cake\Collection\CollectionInterface|string[] $sources
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $categories
 * @var \Cake\Collection\CollectionInterface|string[] $tags
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/form');
}
// TODO: make the add form for images
// labels: images, templates
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
