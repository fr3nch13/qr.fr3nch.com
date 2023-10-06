<?php
/**
 * The default layout
 *
 * @var \App\View\AppView $this
 */

?>
<?= $this->Template->templateComment(true, __FILE__); ?>
          <div class="card">
            <div class="card-header bg-white text-center pb-0">
              <h5 class="fs-4 mb-1"><?= $this->fetch('card_title'); ?></h5>
            </div>
            <div class="card-body bg-white">
                <?= $this->fetch('card_body'); ?>
            </div>
            <!--
            <div class="card-footer bg-opaque-black inverted text-center">
                <?= $this->fetch('card_footer'); ?>
            </div>
            -->
          </div>
<?= $this->Template->templateComment(false, __FILE__); ?>
