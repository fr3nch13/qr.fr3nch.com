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
    <section>
        <div class="row g-3 g-md-5 align-items-end mb-5">
            <div class="col-md-6">
                <h1><?= __('QR Codes') ?></h1>
            </div>

            <div class="col-md-6 text-md-end">
                <ul class="list-inline">
                    <li class="list-inline-item ms-2">
                        <?= $this->Html->link(__('Add a QR Code'), [
                            'controller' => 'QrCodes',
                            'action' => 'add',
                        ], [
                            'class' => 'underline text-black',
                        ]); ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card bg-opaque-white">
                    <div class="card-body bg-white">
                        <!-- saerch form -->
                        <div class="row g-3 g-md-5 align-items-end mb-5">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6 text-md-end">
                                <?= $this->Form->create(null, [
                                    'valueSources' => 'query',
                                ]); ?>
                                <div class="grouped-inputs p-1 border">
                                    <div class="row g-0">
                                        <div class="col">
                                            <?= $this->Form->input('q', [
                                                'type' => 'text',
                                                'class' => 'form-control form-control-xs text-primary',
                                                'placeholder' => __('Search'),
                                                'aria-label' => __('Search'),
                                                'aria-describedby' => 'buttonSearch',
                                            ]) ?>
                                        </div>
                                        <div class="col-auto d-grid">
                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-xs btn-icon rounded-circle"
                                            ><i class="bi bi-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <?= $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.name',
                                            [
                                                'asc' => __('Name') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Name') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.qrkey',
                                            [
                                                'asc' => __('Key') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Key') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.is_active',
                                            [
                                                'asc' => __('Active') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Active') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
                                        <th scope="col"><?= $this->Paginator->sort(
                                            'QrCodes.hits',
                                            [
                                                'asc' => __('Hits') . ' <i class="bi bi-chevron-down"></i>',
                                                'desc' => __('Hits') . ' <i class="bi bi-chevron-up"></i>',
                                            ],
                                            [
                                                'escape' => false,
                                                'class' => 'underline text-black',
                                            ]
                                        ) ?></th>
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
                                                    class="btn btn-sm btn-white btn-icon rounded-circle"
                                                    href="#"
                                                    role="button"
                                                    id="actions<?= $qrCode->id ?>"
                                                    data-bs-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i></i>
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
