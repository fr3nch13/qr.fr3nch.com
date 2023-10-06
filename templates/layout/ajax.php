<?php
/**
 * The default layout
 *
 * @var \App\View\AjaxView $this
 */

echo $this->Template->templateComment(true, __FILE__);
echo $this->fetch('content');
echo $this->Template->templateComment(false, __FILE__);
