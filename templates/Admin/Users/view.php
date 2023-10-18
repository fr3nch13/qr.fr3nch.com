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
    <div class="card-body p-2 p-md-5">
        <div class="row">
            <div class="col-12 col-md-4 position-relative">
                    <?= $this->Html->avatar('xxl', $user) ?>
                    <a
                        href="https://gravatar.com"
                        target="gravatar"
                        class="
                            position-absolute
                            top-10
                            start-10
                            translate-middle
                            ">
                            <?= $this->Html->icon('camera') ?>
                    </a>
            </div>
            <div class="col-12 col-md-8">
                <dl class="row pt-4">
                    <dt class="col-4"><?= __('Name') ?></dt>
                    <dd class="col-8"><?= h($user->name) ?> </dd>

                    <dt class="col-4"><?= __('Email') ?></dt>
                    <dd class="col-8"><?= h($user->email) ?> </dd>

                    <dt class="col-4"><?= __('Created') ?></dt>
                    <dd class="col-8"><?= h($user->created ? $user->created->format('M d, Y') : null) ?> </dd>
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
