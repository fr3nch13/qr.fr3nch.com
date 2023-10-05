<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

if (!$this->getRequest()->is('ajax')) {
    $this->setLayout('pages/index');
}
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
        <div class="container mt-5">
            <div class="row g-3 g-md-5 align-items-end mb-5">
                <div class="col-md-6">
                    <h1><?= __('QR Codes') ?></h1>
                    <!--
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Shop</a></li>
                            <li class="breadcrumb-item"><a href="#">Category</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Equipment</li>
                        </ol>
                    </nav>
                    -->
                </div>

                <?php
                // TODO: Create a page options element to generate page options.
                ?>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <div class="dropdown">
                                <a class="underline text-black" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Sort <i class="bi bi-chevron-down"></i>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
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
                        <?php if ($this->ActiveUser->getUser()): ?>
                        <li class="list-inline-item ms-2">
                            <?= $this->Html->link(__('Add a QR Code'), [
                                'controller' => 'QrCodes',
                                'action' => 'add',
                            ], [
                                'class' => 'underline text-black',
                            ]); ?>
                        </li>
                        <?php endif; ?>
                        <!--
                        // TODO: Will add back when I include friendsofcake/search
                        // labels: frontend
                        <li class="list-inline-item ms-2">
                            <a class=" underline text-black" data-bs-toggle="offcanvas" href="#offcanvasFilter" role="button"
                            aria-controls="offcanvasFilter">
                            Filters
                            </a>
                        </li>
                        -->
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-3 g-lg-5 justify-content-between products">

            <?php foreach ($qrCodes as $qrCode): ?>
                <?= $this->Template->objectComment('QrCodes/' . ($qrCode->is_active ? 'active' : 'inactive')) ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product<?= $qrCode->is_active ? ' active' : ' inactive' ?>">
                        <div class="product-title">
                            <?= $this->Html->link(
                                $qrCode->name,
                                ['action' => 'view', $qrCode->id],
                                ['class' => 'product-title']
                            ); ?>
                        </div>
                        <figure class="product-image">
                            <a href="<?= $this->Url->build(['action' => 'view', $qrCode->id]) ?>">
                                <?php if (!empty($qrCode->qr_images)) : ?>
                                    <?= $this->Template->objectComment('QrImages/active/first') ?>
                                    <img class="product-qrimage" src="<?= $this->Url->build([
                                        'controller' => 'QrImages',
                                        'action' => 'show',
                                        $qrCode->qr_images[0]->id,
                                        ]) ?>" alt="<?= $qrCode->qr_images[0]->name ?>">
                                <?php endif; ?>
                                <?= $this->Template->objectComment('QrCode/show') ?>
                                <img class="product-qrcode" src="<?= $this->Url->build(['action' => 'show', $qrCode->id]) ?>" alt="<?= __('The QR Code') ?>">
                            </a>
                        </figure>
                        <div class="btn-group btn-block product-options" role="group" aria-label="Product Options">
                            <?= $this->Html->link(
                                __('Follow Code'),
                                ['action' => 'forward', $qrCode->qrkey],
                                ['class' => 'btn btn-light']
                            ); ?>
                            <?= $this->Html->link(
                                __('Details'),
                                ['action' => 'view', $qrCode->id],
                                ['class' => 'btn btn-light']
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


            </div>
            <div class="row mt-6">
                <div class="col text-center">
                    <nav aria-label="Pagination">
                        <ul class="pagination">
                            <?= $this->Paginator->first('&laquo;', ['label' => 'First']) ?>
                            <?= $this->Paginator->prev('<', ['label' => 'Previous']) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next('>', ['label' => 'Next']) ?>
                            <?= $this->Paginator->last('&laquo;', ['label' => 'Last']) ?>
                        </ul>
                        <!--
                            <p><?= $this->Paginator->counter(__('{{page}}/{{pages}}, {{current}} of {{count}}')) ?></p>
                        -->
                    </nav>
                </div>
            </div>
        </div>

  <!-- offcanvas - filters -->
  <!-- Will add back when I include friendsofcake/search
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasFilterLabel">Filters</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

      <div class="widget">
        <span class="d-flex eyebrow text-muted mb-2">Brands</span>
        <ul class="list-unstyled">
          <li>
            <div class="form-check form-check-minimal">
              <input class="form-check-input" type="checkbox" value="" id="brand-1">
              <label class="form-check-label" for="brand-1">
                Vans
              </label>
            </div>
          </li>
          <li class="mt-1">
            <div class="form-check form-check-minimal">
              <input class="form-check-input" type="checkbox" value="" id="brand-2">
              <label class="form-check-label" for="brand-2">
                Carhart WIP
              </label>
            </div>
          </li>
          <li class="mt-1">
            <div class="form-check form-check-minimal">
              <input class="form-check-input" type="checkbox" value="" id="brand-3">
              <label class="form-check-label" for="brand-3">
                Carhart WIP
              </label>
            </div>
          </li>
        </ul>
      </div>


      <div class="widget mt-5">
        <span class="d-flex eyebrow text-muted mb-2">Color</span>
        <ul class="list-unstyled">
          <li>
            <div class="form-check form-check-color">
              <input class="form-check-input" type="checkbox" value="" id="color-1">
              <label class="form-check-label" for="color-1">
                <span class="bg-red"></span> Red
              </label>
            </div>
          </li>
          <li class="mt-1">
            <div class="form-check form-check-color">
              <input class="form-check-input" type="checkbox" value="" id="color-2">
              <label class="form-check-label" for="color-2">
                <span class="bg-blue"></span> Blue
              </label>
            </div>
          </li>
          <li class="mt-1">
            <div class="form-check form-check-color">
              <input class="form-check-input" type="checkbox" value="" id="color-3">
              <label class="form-check-label" for="color-3">
                <span class="bg-green"></span> Green
              </label>
            </div>
          </li>
          <li class="mt-1">
            <div class="form-check form-check-color">
              <input class="form-check-input" type="checkbox" value="" id="color-4">
              <label class="form-check-label" for="color-4">
                <span class="bg-yellow"></span> Yellow
              </label>
            </div>
          </li>
        </ul>
      </div>

      <div class="widget mt-5">
        <span class="d-flex eyebrow text-muted mb-2">Price</span>
        <div class="range-slider" data-range='{"decimals": 0,"step": 1,"connect": true, "start" : [20,80], "range" : {"min": 0, "max" :
          100}}'></div>
        <div class="range-slider-selection">Price: <span class="range-slider-value" id="range-min"></span>
          &mdash; <span class="range-slider-value" id="range-max"></span></div>
      </div>

    </div>
  </div>
  -->
<?= $this->Template->templateComment(false, __FILE__); ?>
