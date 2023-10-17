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
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/QrCodes/index');
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
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/QrCodes/index');
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
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/QrCodes/index');
    }
}
