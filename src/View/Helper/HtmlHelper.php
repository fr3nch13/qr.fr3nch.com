<?php
declare(strict_types=1);

/**
 * Helps with the Html stuff
 */
namespace App\View\Helper;

use BootstrapUI\View\Helper\HtmlHelper as BootstrapUiHtmlHelper;
use Cake\Routing\Router;
use Psr\Http\Message\UriInterface;

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

    /**
     * Finds URL for specified action.
     *
     * This is just a wrapper for \Cake\Routing\Router::url()
     *
     * Returns a URL pointing to a combination of controller and action.
     *
     * ### Usage
     *
     * - `Router::url('/posts/edit/1');` Returns the string with the base dir prepended.
     *   This usage does not use reverser routing.
     * - `Router::url(['controller' => 'Posts', 'action' => 'edit']);` Returns a URL
     *   generated through reverse routing.
     * - `Router::url(['_name' => 'custom-name', ...]);` Returns a URL generated
     *   through reverse routing. This form allows you to leverage named routes.
     *
     * There are a few 'special' parameters that can change the final URL string that is generated
     *
     * - `_base` - Set to false to remove the base path from the generated URL. If your application
     *   is not in the root directory, this can be used to generate URLs that are 'cake relative'.
     *   cake relative URLs are required when using requestAction.
     * - `_scheme` - Set to create links on different schemes like `webcal` or `ftp`. Defaults
     *   to the current scheme.
     * - `_host` - Set the host to use for the link. Defaults to the current host.
     * - `_port` - Set the port if you need to create links on non-standard ports.
     * - `_full` - If true output of `Router::fullBaseUrl()` will be prepended to generated URLs.
     * - `#` - Allows you to set URL hash fragments.
     * - `_https` - Set to true to convert the generated URL to https, or false to force http.
     * - `_name` - Name of route. If you have setup named routes you can use this key
     *   to specify it.
     *
     * @param \Psr\Http\Message\UriInterface|array|string|null $url An array specifying any of the following:
     *   'controller', 'action', 'plugin' additionally, you can provide routed
     *   elements or query string parameters. If string it can be name any valid url
     *   string or it can be an UriInterface instance.
     * @param bool $full If true, the full base URL will be prepended to the result.
     *   Default is false.
     * @return string Full translated URL with base path.
     * @throws \Cake\Core\Exception\CakeException When the route name is not found
     */
    public function url(UriInterface|array|string|null $url = null, bool $full = false): string
    {
        return Router::url();
    }
}
