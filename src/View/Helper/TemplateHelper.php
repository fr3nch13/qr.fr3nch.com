<?php
declare(strict_types=1);

/**
 * Helps with the template stuff
 */
namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Template helper library.
 *
 * @property \BootstrapUI\View\Helper\HtmlHelper $Html
 */
class TemplateHelper extends Helper
{
    /**
     * helpers
     *
     * @var array<int, string>
     */
    protected array $helpers = ['Html'];

    /**
     * Writes the template location coment.
     *
     * @param bool $start If were the start, or the end
     * @param string $path Absolute path to the Template.
     * @param string $prefix The prefix, mainly App, or a plugin identifier
     * @return string
     */
    public function templateComment(bool $start, string $path, string $prefix = 'App'): string
    {
        /*
        if (!Configure::read('debug')) {
            return '';
        }
        */

        $comment = "\n\n" . '<!-- ' . ($start ? 'START' : 'END') . ': ' . $prefix . '.';

        $path = str_replace(ROOT . DS . 'templates' . DS, '', $path);
        $path = str_replace('.php', '', $path);

        $comment .= $path . ' -->' . "\n\n";

        return $comment;
    }

    /**
     * Used for testing a part of a template within phpunit.
     *
     * @param string $string The string to include in the Html comment
     * @return string The formatted Html comment.
     */
    public function objectComment(string $string): string
    {
        /*
        if (!Configure::read('debug')) {
            return '';
        }
        */

        return "\n\n" . '<!-- objectComment: ' . $string . ' -->' . "\n\n";
    }
}
