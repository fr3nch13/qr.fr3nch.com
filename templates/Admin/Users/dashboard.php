<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/index');
}

$this->assign('page_title', __('Dashboard'));
$this->assign('title', $this->fetch('page_title'));
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="row">
    <div class="col">
        <h3><?= __('Hits') ?></h3>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card border bg-info pt-3 inverted card-hover-image-rise text-center">
            <h4 class="fs-1"><?= $stats['hour'] ?></h4>
            <div class="card-title">
                <?= __('Hour') ?>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border bg-info pt-3 inverted card-hover-image-rise text-center">
            <h4 class="fs-1"><?= $stats['day'] ?></h4>
            <div class="card-title">
                <?= __('Day') ?>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border bg-info pt-3 inverted card-hover-image-rise text-center">
            <h4 class="fs-1"><?= $stats['week'] ?></h4>
            <div class="card-title">
                <?= __('Week') ?>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border bg-info pt-3 inverted card-hover-image-rise text-center">
            <h4 class="fs-1"><?= $stats['month'] ?></h4>
            <div class="card-title">
                <?= __('Month') ?>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border bg-info pt-3 inverted card-hover-image-rise text-center">
            <h4 class="fs-1"><?= $stats['year'] ?></h4>
            <div class="card-title">
                <?= __('Year') ?>
            </div>
        </div>
    </div>

</div>

<?= $this->Template->templateComment(false, __FILE__); ?>
