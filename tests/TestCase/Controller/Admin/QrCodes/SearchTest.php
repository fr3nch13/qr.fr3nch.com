<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrCodesController Test Case
 *
 * Tests to make sure the search/filters are working.
 *
 * @uses \App\Controller\Admin\QrCodesController
 */
class SearchTest extends BaseControllerTest
{
    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();
    }

    /**
     * Test filtering by a string from the query string.
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndexSearchGetQ(): void
    {
        $this->get('https://localhost/admin/qr-codes?q=witch');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Admin/QrCodes/index');

        /*
        // TODO: Add this back once we have the admin frontend figured out.
        // labels: templates, admin, frontent
        $this->helperTestFilterElements(true);

        // test to see if filtering is actually applied.
        $this->helperTestObjectComment(1, 'QrCodes/active');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(1, 'QrCode/show');
        $this->helperTestObjectComment(1, 'QrCode/forward');
        $this->helperTestObjectComment(1, 'QrCode/view');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(1, 'QrImages/active/first');

        $content = (string)$this->_response->getBody();

        // Should only return The Witching Hour
        $this->assertSame(1, substr_count($content, '<div class="product-title">' .
            '<a href="/qr-codes/view/2" class="product-title">The Witching Hour</a></div>'));

        // finally look for the input in the offcanvas that has the filter set.
        $this->assertSame(1, substr_count($content, '<input type="text" name="q" id="q" placeholder="What are you looking for ?" class="form-control" value="witch">'));
        */
    }

    /**
     * Test filtering by tags from the query string.
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndexSearchGetT(): void
    {
        $this->get('https://localhost/admin/qr-codes?t=Pig');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Admin/QrCodes/index');

        /*
        // TODO: Add this back once we have the admin frontend figured out.
        // labels: templates, admin, frontent
        $this->helperTestFilterElements(true);

        // test to see if filtering is actually applied.
        $this->helperTestObjectComment(1, 'QrCodes/active');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(1, 'QrCode/show');
        $this->helperTestObjectComment(1, 'QrCode/forward');
        $this->helperTestObjectComment(1, 'QrCode/view');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(1, 'QrImages/active/first');

        $content = (string)$this->_response->getBody();

        // Should only return Sow & Scribe
        $this->assertSame(1, substr_count($content, '<div class="product-title">' .
            '<a href="/qr-codes/view/1" class="product-title">Sow &amp; Scribe</a></div>'));

        // finally look for the input in the offcanvas that has the filter set.
        $this->assertSame(1, substr_count($content, '<select name="t" id="t" class="form-select">'));
        $this->assertSame(1, substr_count($content, '<option value="Pig" selected="selected">Pig</option>'));
        */
    }

    /**
     * Test filtering by source from the query string.
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndexSearchGetS(): void
    {
        $this->get('https://localhost/admin/qr-codes?s=Etsy');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Admin/QrCodes/index');

        /*
        // TODO: Add this back once we have the admin frontend figured out.
        // labels: templates, admin, frontent
        $this->helperTestFilterElements(true);

        // test to see if filtering is actually applied.
        $this->helperTestObjectComment(1, 'QrCodes/active');
        $this->helperTestObjectComment(1, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(2, 'QrCode/show');
        $this->helperTestObjectComment(2, 'QrCode/forward');
        $this->helperTestObjectComment(2, 'QrCode/view');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(1, 'QrImages/active/first');

        $content = (string)$this->_response->getBody();

        // Should return American Flag Charm (active)
        $this->assertSame(1, substr_count($content, '<div class="product-title">' .
            '<a href="/qr-codes/view/3" class="product-title">American Flag Charm</a></div>'));

        // Should return Inactive Code (inactive)
        $this->assertSame(1, substr_count($content, '<div class="product-title">' .
            '<a href="/qr-codes/view/4" class="product-title">Inactive Code</a></div>'));

        // finally look for the input in the offcanvas that has the filter set.
        $this->assertSame(1, substr_count($content, '<select name="s" id="s" class="form-select">'));
        $this->assertSame(1, substr_count($content, '<option value="Etsy" selected="selected">Etsy</option>'));
        */
    }
}
