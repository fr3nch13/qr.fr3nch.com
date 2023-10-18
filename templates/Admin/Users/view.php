<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('dashboard/view');
}

$this->assign('page_title', $user->name);
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<div class="card bg-opaque-white">
    <div class="card-body p-2 p-lg-5">
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex justify-content-center  position-relative">
                    <?= $this->Html->avatar('lg', $user) ?>
                    <a
                        href="https://gravatar.com"
                        target="gravatar"
                        class="
                            position-absolute
                            top-80
                            start-80
                            translate-middle
                            pt-5
                            ps-0
                            ps-md-5
                            ">
                        <i class="bi bi-camera"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <dl class="row pt-4">
                    <dt class="col-4 col-md-3"><?= __('Name') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($user->name) ?> </dd>

                    <dt class="col-4 col-md-3"><?= __('Email') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($user->email) ?> </dd>

                    <dt class="col-4 col-md-3"><?= __('Created') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($user->created ? $user->created->format('M d, Y') : null) ?> </dd>
                </dl>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-md-12">
                <h5 class="border-bottom pb-2"><?= __('QR Codes') ?></h5>
                <?php foreach ($user->qr_codes as $qrCode) : ?>
                    <a
                        href="<?= $this->Url->build([
                            'plugin' => false,
                            'prefix' => 'Admin',
                            'controller' => 'QrCodes',
                            'action' => 'view',
                            $qrCode->id,
                        ]) ?>"
                        class="
                        my-1 my-md-2 mx-1 mx-md-2
                        btn btn-sm btn-light btn-outline-secondary
                        rounded-pill" role="button"><?= $qrCode->name ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Template->templateComment(false, __FILE__); ?>
