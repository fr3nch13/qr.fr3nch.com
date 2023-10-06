<?php
declare(strict_types=1);

/**
 * Helps with the Html stuff
 */
namespace App\View\Helper;

use BootstrapUI\View\Helper\HtmlHelper as BootstrapUiHtmlHelper;

/**
 * Html helper library.
 */
class HtmlHelper extends BootstrapUiHtmlHelper
{
    /**
     * Fixes the paginator sort html
     *
     * @param string $html The generated html from the paginator helper
     * @return string The fixed html
     */
    public function fixPaginatorSort(string $html): string
    {
        $html = str_replace('<a ', '<a class="dropdown-item" ', $html);

        return $html;
    }
}
