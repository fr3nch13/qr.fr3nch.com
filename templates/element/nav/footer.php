<?php
/**
 * @var \App\View\AppView $this
 *
 */
?>
<?= $this->Template->templateComment(true, __FILE__); ?>
<!-- footer -->

<footer class="py-15 py-xl-20 bg-black inverted">
    <div class="container">
        <div class="row g-2 g-lg-6 mb-8">
            <div class="col-lg-6">
                <h4>Henderson, NV <br>89002</h4>
            </div>
            <div class="col-lg-6 text-lg-end">
                <span class="h5">qr@fr3nch.com</span>
            </div>
        </div>
        <div class="row mb-8">
            <div class="col">
                <hr>
            </div>
        </div>
        <div class="row g-0 g-lg-6 text-secondary">
            <div class="col-lg-6 text-lg-end order-lg-2">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a
                            href="https://www.facebook.com/people/Fr3nch/61552767548117/"
                            class="text-reset"><i title="facebook" class="bi bi-facebook"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://www.instagram.com/fr3nch_llc/"
                            class="text-reset"><i title="instagram" class="bi bi-instagram"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://www.pinterest.com/fr3nchllc/"
                            class="text-reset"><i title="pinterest" class="bi bi-pinterest"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://www.tiktok.com/@fr3nchllc"
                            class="text-reset"><i title="tiktok" class="bi bi-tiktok"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://twitter.com/Fr3nchLLC"
                            class="text-reset"><i title="twitter" class="bi bi-twitter-x"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://dribbble.com/fr3nchllc"
                            class="text-reset"><i title="dribbble" class="bi bi-dribbble"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://www.behance.net/fr3nchllc"
                            class="text-reset"><i title="behance" class="bi bi-behance"></i></a>
                    </li>
                    <li class="list-inline-item ms-1">
                        <a
                            href="https://www.linkedin.com/in/fr3nch-llc-8a252829a"
                            class="text-reset"><i title="linkedin" class="bi bi-linkedin"></i></a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 order-lg-1">
                <p><?= __('Copyrights') ?> © <?= date('Y'); ?></p>
            </div>
        </div>
    </div>
</footer>
<?= $this->Template->templateComment(false, __FILE__); ?>
