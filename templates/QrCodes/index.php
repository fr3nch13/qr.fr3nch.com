<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

// TODO: Don;t use routing here, Wrap it with HtmlHelper::url()
use Cake\Routing\Router;

?>

<div class="offcanvas-wrap">
    <section class="py-15 py-xl-20">
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

                <div class="col-md-6 text-md-end">
                    <ul class="list-inline">
                    <li class="list-inline-item">
                        <div class="dropdown">
                        <a class="underline text-black" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Sort <i class="bi bi-chevron-down"></i>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><?= $this->Paginator->sort(
                                'name',
                                [
                                    'asc' => __('Name') . ' <i class="bi bi-chevron-down"></i>',
                                    'desc' => __('Name') . ' <i class="bi bi-chevron-up"></i>',
                                ],
                                ['class' => 'dropdown-item', 'escape' => false]
                            ); ?></li>
                            <li><?= $this->Paginator->sort(
                                'created',
                                [
                                    'asc' => __('Created') . ' <i class="bi bi-chevron-down"></i>',
                                    'desc' => __('Created') . ' <i class="bi bi-chevron-up"></i>',
                                ],
                                ['class' => 'dropdown-item', 'escape' => false]
                            ); ?></li>
                        </ul>
                        </div>
                    </li>
                    <li class="list-inline-item ms-2">
                        <a class=" underline text-black" data-bs-toggle="offcanvas" href="#offcanvasFilter" role="button"
                        aria-controls="offcanvasFilter">
                        Filters
                        </a>
                    </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-3 g-lg-5 justify-content-between products">

            <?php foreach ($qrCodes as $qrCode): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product">
                        <div class="product-title">
                            <?= $this->Html->link(
                                $qrCode->name,
                                ['action' => 'view', $qrCode->id],
                                ['class' => 'product-title']
                            ); ?>
                        </div>
                        <figure class="product-image">
                            <a href="<?= Router::url(['action' => 'view', $qrCode->id]) ?>">
                                <img src="<?= Router::url(['action' => 'show', $qrCode->id]) ?>">
                            </a>
                        </figure>
                        <div class="btn-group btn-block product-options" role="group" aria-label="Product Options">
                            <?= $this->Html->link(
                                __('Follow'),
                                ['action' => 'forward', $qrCode->qrkey],
                                ['class' => 'btn btn-light']
                            ); ?>
                            <?= $this->Html->link(
                                __('View'),
                                ['action' => 'view', $qrCode->id],
                                ['class' => 'btn btn-light']
                            ) ?>
                        </div>
                        <!--
                        <span class="product-price">$100 </span>
                        -->
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
    </section>
</div>

  <!-- offcanvas - filters -->
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


<!--

<div class="qrCodes index content">
    <?= $this->Html->link(__('New QR Code'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('QR Codes') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('qrkey') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('source_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($qrCodes as $qrCode): ?>
                <tr>
                    <td><?= $this->Number->format($qrCode->id) ?></td>
                    <td><?= h($qrCode->qrkey) ?></td>
                    <td><?= h($qrCode->name) ?></td>
                    <td><?= h($qrCode->created) ?></td>
                    <td><?= h($qrCode->modified) ?></td>
                    <td><?= $qrCode->hasValue('source') ? $this->Html->link($qrCode->source->name, ['controller' => 'Sources', 'action' => 'view', $qrCode->source->id]) : '' ?></td>
                    <td><?= $qrCode->hasValue('user') ? $this->Html->link($qrCode->user->name, ['controller' => 'Users', 'action' => 'view', $qrCode->user->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Follow'), ['action' => 'forward', $qrCode->qrkey]) ?>
                        <?= $this->Html->link(__('View'), ['action' => 'view', $qrCode->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $qrCode->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $qrCode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $qrCode->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
                -->
