<?php
declare(strict_types=1);

/**
 * Helps with the template stuff
 */
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Template helper library.
 */
class TemplateHelper extends Helper
{
    /**
     * helpers
     *
     * @var array
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
        $comment = "\n\n" . '<!-- ' . ($start ? 'START' : 'END') . ':' . $prefix . '.';

        $path = str_replace(ROOT . DS . 'templates' . DS, '', $path);
        $path = str_replace('.php', '', $path);

        $comment .= $path . ' -->' . "\n\n";

        return $comment;
    }
}
