<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

/**
 * Used to test the layouts.
 *
 * Make sure specific layout templates exist, or not.
 * Also test to make sure html nodes are there, or not.
 *
 * @property \Cake\Http\Response $_response
 */
trait HtmlTrait
{
    /**
     * Tests the Layout is there.
     *
     * @return void
     */
    public function helperTestLayoutNormal(): void
    {
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/default -->'));
    }

    /**
     * Tests the Layout when an Ajax request is made.
     *
     * @param string $content The html content to test.
     * @return void
     */
    public function helperTestLayoutAjax(string $content): void
    {
        $this->assertSame(0, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(0, substr_count($content, '<!-- END: App.layout/default -->'));

        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/ajax -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/ajax -->'));
    }
}
