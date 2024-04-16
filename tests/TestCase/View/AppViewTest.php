<?php
declare(strict_types=1);

namespace App\Test\TestCase\View;

use App\Controller\QrCodesController;
use App\View\AppView;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

/**
 * App\View\AppView Test Case
 */
class AppViewTest extends TestCase
{
    /**
     * @var \App\Controller\QrCodesController to use to test the view stuff
     */
    public $QrCodesController;

    /**
     * @var \App\View\AppView The view instance to test.
     */
    public $View;

    /**
     * Setup an instance of the View
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $Request = new ServerRequest();
        $this->QrCodesController = new QrCodesController($Request);
        $this->QrCodesController->viewBuilder()->setClassName(AppView::class);
        /** @var \App\View\AppView $View */
        $View = $this->QrCodesController->createView();
        $this->View = $View;
    }

    /**
     * Test the layout path
     *
     * @return void
     */
    public function testLayoutPath(): void
    {
        $path = $this->View->getLayoutPath();
        $this->assertSame('', $path);
    }

    /**
     * Test the template path
     *
     * @return void
     */
    public function testTemplatePath(): void
    {
        $path = $this->View->getTemplatePath();
        $this->assertSame('', $path);
    }

    /**
     * Test the template content type
     *
     * @return void
     */
    public function testContentType(): void
    {
        $path = $this->View->contentType();
        $this->assertSame('text/html', $path);
    }
}
