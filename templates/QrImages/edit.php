<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\QrCode $qrCode
 * @var string[]|\Cake\Collection\CollectionInterface $sources
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 * @var string[]|\Cake\Collection\CollectionInterface $tags
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/form');
}

// TODO: make the edit form for images
// labels: images, templates
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<?= $this->Template->templateComment(false, __FILE__); ?>
