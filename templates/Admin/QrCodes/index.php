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
                <!--
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active" aria-current="page">QR Codes</li>
                    </ol>
                </nav>
                -->
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
                        <form class="form-inline">
                            <label class="sr-only" for="inlineFormInputName2">Name</label>
                            <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="Jane Doe">

                            <label class="sr-only" for="inlineFormInputGroupUsername2">Username</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                <div class="input-group-text">@</div>
                                </div>
                                <input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Username">
                            </div>

                            <div class="form-check mb-2 mr-sm-2">
                                <input class="form-check-input" type="checkbox" id="inlineFormCheck">
                                <label class="form-check-label" for="inlineFormCheck">
                                Remember me
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        </form>
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
