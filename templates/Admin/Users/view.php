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
            <div class="col-lg-4">
                <?= $this->Html->avatar('lg') ?>
            </div>
            <div class="col-lg-8">
                <dl class="row">
                    <dt class="col-4 col-md-3"><?= __('Name') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($user->name) ?> </dd>

                    <dt class="col-4 col-md-3"><?= __('Email') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($user->email) ?> </dd>

                    <dt class="col-4 col-md-3"><?= __('Created') ?></dt>
                    <dd class="col-8 col-md-9"><?= h($user->created) ?> </dd>
                </dl>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-lg-12">
                <h5><?= __('Codes') ?></h5>
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


<!--
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), [
                'action' => 'delete',
                $user->id,
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
                'class' => 'side-nav-item',
            ]) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users view content">
            <h3><?= h($user->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($user->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
-->
<?= $this->Template->templateComment(false, __FILE__); ?>
