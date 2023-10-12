<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/dashboard');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
    <h1><?= __('QR Codes') ?></h1>

    <section>
        <div class="row">
            <div class="col">
            <ul class="list-inline">
                        <?php if ($this->ActiveUser->isLoggedIn()) : ?>
                        <li class="list-inline-item ms-2">
                            <?= $this->Html->link(__('Add a QR Code'), [
                                'controller' => 'QrCodes',
                                'action' => 'add',
                            ], [
                                'class' => 'underline text-black',
                            ]); ?>
                        </li>
                        <?php endif; ?>
                        <li class="list-inline-item">
                            <div class="dropdown">
                                <a
                                    class="underline text-black"
                                    href="#" role="button"
                                    id="indexPageOptions"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Sort <i class="bi bi-chevron-down"></i>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="indexPageOptions">
                                    <li><?= $this->Html->fixPaginatorSort($this->Paginator->sort(
                                        'QrCodes.name',
                                        [
                                            'asc' => '<i class="bi bi-chevron-down"></i> ' . __('Name'),
                                            'desc' => '<i class="bi bi-chevron-up"></i> ' . __('Name'),
                                        ],
                                        ['escape' => false]
                                    )); ?></li>
                                    <li><?= $this->Html->fixPaginatorSort($this->Paginator->sort(
                                        'QrCodes.created',
                                        [
                                            'asc' => '<i class="bi bi-chevron-down"></i> ' . __('Created'),
                                            'desc' => '<i class="bi bi-chevron-up"></i> ' . __('Created'),
                                        ],
                                        ['escape' => false]
                                    )); ?></li>
                                </ul>
                            </div>
                        </li>
                        <li class="list-inline-item ms-2">
                            <a
                                class=" underline text-black position-relative"
                                data-bs-toggle="offcanvas"
                                href="#offcanvasFilter"
                                role="button"
                                aria-controls="offcanvasFilter"><?= __('Filters') ?>
                                <?php if ($this->Search->isSearch()) : ?>
                                <span
                                    class="
                                        position-absolute
                                        top-0
                                        start-100
                                        translate-middle
                                        rounded-pill
                                        text-red
                                        p-1">
                                    <i class="bi bi-check filtering-applied"></i>
                                    <span class="visually-hidden"><?= __('Filters are applied') ?></span>
                                </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                <div class="card bg-opaque-white">
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"><?= __('Name') ?></th>
                                        <th scope="col"><?= __('Key') ?></th>
                                        <th scope="col"><?= __('Active') ?></th>
                                        <th scope="col"><?= __('Hits') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($qrCodes as $qrCode) : ?>
                                    <tr  class="<?= $qrCode->is_active ? '' : 'text-muted' ?>">
                                        <td>
                                            <?= $qrCode->name ?>
                                        </td>
                                        <td>
                                            <?= $qrCode->qrkey ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($qrCode->is_active) {
                                                echo '<i class="bi bi-check2 text-success fs-6"></i>';
                                            } else {
                                                echo '<i class="bi bi-x fs-6"></i>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill"><?= $qrCode->hits ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a
                                                    class="nav-icon"
                                                    href="#"
                                                    role="button"
                                                    id="actions<?= $qrCode->id ?>"
                                                    data-bs-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="bi bi-gear"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="actions<?= $qrCode->id ?>">
                                                    <li><a class="dropdown-item" href="#">Details</a></li>
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="#">Images</a></li>
                                                    <li><a class="dropdown-item" href="#">Toggle Active</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <nav aria-label="Pagination" class="text-center">
                                <ul class="pagination">
                                    <?= $this->Paginator->first('&laquo;', ['label' => 'First']) ?>
                                    <?= $this->Paginator->prev('<', ['label' => 'Previous']) ?>
                                    <?= $this->Paginator->numbers() ?>
                                    <?= $this->Paginator->next('>', ['label' => 'Next']) ?>
                                    <?= $this->Paginator->last('&laquo;', ['label' => 'Last']) ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?= $this->Template->templateComment(false, __FILE__); ?>
