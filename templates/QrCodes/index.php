<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\QrCode> $qrCodes
 */

// TODO: Don;t use routing here, Wrap it with HtmlHelper::url()
use Cake\Routing\Router;

?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCart">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasCartLabel">Shopping Cart</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="list-unstyled">
        <li>
          <div class="row g-2 g-lg-3 align-items-center">
            <a href="" class="col-3"><img class="img-fluid" src="./assets/images/products/product-1.jpg"
                alt="Product"></a>
            <div class="col">
              <a href="" class="text-black text-primary-hover lead">Bluetooth Speaker</a>
              <ul class="list-inline text-muted">
                <li class="list-inline-item">Price: <span class="text-secondary">$90</span></li>
                <li class="list-inline-item">Color: <span class="text-secondary">Blue</span></li>
                <li class="list-inline-item">Qty:
                  <div class="counter text-secondary" data-counter="qty-1">
                    <span class="counter-minus bi bi-dash"></span>
                    <input type="number" name="qty-1" class="counter-value" value="0" min="0" max="10">
                    <span class="counter-plus bi bi-plus"></span>
                  </div>
                </li>
              </ul>
              <a href="" class="text-red underline">Remove</a>
            </div>
          </div>
        </li>
        <li class="mt-4">
          <div class="row g-2 g-lg-3 align-items-center">
            <a href="" class="col-3"><img class="img-fluid" src="./assets/images/products/product-2.jpg"
                alt="Product"></a>
            <div class="col">
              <a href="" class="text-black text-primary-hover lead">Bluetooth Speaker</a>
              <ul class="list-inline text-muted">
                <li class="list-inline-item">Price: <span class="text-secondary">$90</span></li>
                <li class="list-inline-item">Color: <span class="text-secondary">Blue</span></li>
                <li class="list-inline-item">Qty:
                  <div class="counter text-secondary" data-counter="qty-1">
                    <span class="counter-minus bi bi-dash"></span>
                    <input type="number" name="qty-1" class="counter-value" value="0" min="0" max="10">
                    <span class="counter-plus bi bi-plus"></span>
                  </div>
                </li>
              </ul>
              <a href="" class="text-red underline">Remove</a>
            </div>
          </div>
        </li>
        <li class="mt-4">
          <div class="row g-2 g-lg-3 align-items-center">
            <a href="" class="col-3"><img class="img-fluid" src="./assets/images/products/product-3.jpg"
                alt="Product"></a>
            <div class="col">
              <a href="" class="text-black text-primary-hover lead">Bluetooth Speaker</a>
              <ul class="list-inline text-muted">
                <li class="list-inline-item">Price: <span class="text-secondary">$90</span></li>
                <li class="list-inline-item">Color: <span class="text-secondary">Blue</span></li>
                <li class="list-inline-item">Qty:
                  <div class="counter text-secondary" data-counter="qty-1">
                    <span class="counter-minus bi bi-dash"></span>
                    <input type="number" name="qty-1" class="counter-value" value="0" min="0" max="10">
                    <span class="counter-plus bi bi-plus"></span>
                  </div>
                </li>
              </ul>
              <a href="" class="text-red underline">Remove</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="offcanvas-footer">
      <div class="d-grid gap-1">
        <a href="" class="btn btn-outline-light rounded-pill">View Cart</a>
        <a href="" class="btn btn-primary rounded-pill">Proceed to Checkout</a>
      </div>
    </div>
</div>


<div class="offcanvas-wrap">
    <section class="py-15 py-xl-20">
        <div class="container mt-5">
            <div class="row g-3 g-md-5 align-items-end mb-5">
                <div class="col-md-6">
                    <h1><?= __('QR Codes') ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Shop</a></li>
                            <li class="breadcrumb-item"><a href="#">Category</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Equipment</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-3 g-lg-5 justify-content-between">


            <?php foreach ($qrCodes as $qrCode): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product">
                        <figure class="product-image">
                            <a href="<?= Router::url(['action' => 'view', $qrCode->id]) ?>">
                                <img src="<?= Router::url(['action' => 'show', $qrCode->id]) ?>">
                            </a>
                        </figure>
                        <?= $this->Html->link(
                            $qrCode->name,
                            ['action' => 'view', $qrCode->id],
                            ['class' => 'product-title']
                        ); ?>
                        <!--
                        <span class="product-price">$100 </span>
                        -->
                    </div>
                </div>
            <?php endforeach; ?>


            </div>
        </div>
    </section>
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
