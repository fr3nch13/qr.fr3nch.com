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
                <div class="card bg-opaque-white">
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
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
                                            <span class="badge badge-pill badge-primary"><?= $qrCode->hits ?></span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a
                                                    class="btn btn-light"
                                                    href="#"
                                                    role="button"
                                                    id="actions<?= $qrCode->id ?>"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="bi bi-gear"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="actions<?= $qrCode->id ?>">
                                                    <a class="dropdown-item" href="#">Details</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Images</a>
                                                    <a class="dropdown-item" href="#">Toggle Active</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <nav aria-label="Pagination">
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
