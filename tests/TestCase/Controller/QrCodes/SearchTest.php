<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests to make sure the search/filters are working.
 *
 * @uses \App\Controller\QrCodesController
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
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexSearchGetQ(): void
    {
        $this->get('https://localhost/qr-codes?q=witch');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');
        $this->helperTestFilterElements(true);

        // test to see if filtering is actually applied.
        $this->helperTestObjectComment(1, 'QrCode/active');
        $this->helperTestObjectComment(0, 'QrCode/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(1, 'QrCode/view');
        $this->helperTestObjectComment(1, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(1, 'QrImage/active/first');

        $content = (string)$this->_response->getBody();

        // Should only return The Witching Hour
            $this->assertSame(1, substr_count($content, '<div ' .
                'class="card-title text-center text-white pt-5">The Witching Hour</div>'));

        // finally look for the input in the offcanvas that has the filter set.
        $this->assertSame(1, substr_count($content, '<input type="text" name="q" id="q" ' .
            'placeholder="What are you looking for ?" class="form-control" value="witch">'));
    }

    /**
     * Test filtering by tags from the query string.
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexSearchGetT(): void
    {
        $this->get('https://localhost/qr-codes?t=Pig');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');
        $this->helperTestFilterElements(true);

        // test to see if filtering is actually applied.
        $this->helperTestObjectComment(1, 'QrCode/active');
        $this->helperTestObjectComment(0, 'QrCode/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(1, 'QrCode/view');
        $this->helperTestObjectComment(1, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(1, 'QrImage/active/first');

        $content = (string)$this->_response->getBody();

        // Should only return Sow & Scribe
        $this->assertSame(1, substr_count($content, '<div ' .
            'class="card-title text-center text-white pt-5">Sow & Scribe</div>'));

        // finally look for the input in the offcanvas that has the filter set.
        $this->assertSame(1, substr_count($content, '<select name="t" id="t" class="form-select">'));
        $this->assertSame(1, substr_count($content, '<option value="Pig" selected="selected">Pig</option>'));
    }

    /**
     * Test filtering by source from the query string.
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexSearchGetS(): void
    {
        $this->get('https://localhost/qr-codes?s=Etsy');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');
        $this->helperTestFilterElements(true);

        // test to see if filtering is actually applied.
        $this->helperTestObjectComment(1, 'QrCode/active');
        $this->helperTestObjectComment(0, 'QrCode/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(1, 'QrCode/view');
        $this->helperTestObjectComment(1, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(1, 'QrImage/active/first');

        $content = (string)$this->_response->getBody();

        // Should return American Flag Charm (active)
        $this->assertSame(1, substr_count($content, '<div ' .
            'class="card-title text-center text-white pt-5">American Flag Charm</div>'));

        // finally look for the input in the offcanvas that has the filter set.
        $this->assertSame(1, substr_count($content, '<select name="s" id="s" class="form-select">'));
        $this->assertSame(1, substr_count($content, '<option value="Etsy" selected="selected">Etsy</option>'));
    }
}
