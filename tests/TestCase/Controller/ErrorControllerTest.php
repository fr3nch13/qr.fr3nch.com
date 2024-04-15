<?php
declare(strict_types=1);

/**
 * Tests the Error Controller.
 */
namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;

/**
 * ErrorControllerTest class
 *
 * @uses \App\Controller\ErrorController
 */
class ErrorControllerTest extends BaseControllerTest
{
    /**
     * test error method
     *
     * @return void
     */
    public function testError404DebugOn()
    {
        // With Debug On
        Configure::write('debug', true);
        $this->get('https://localhost/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Controller');
        $this->assertResponseContains('<span>Missing Controller</span>');
        $this->assertResponseContains('Cake\Http\Exception\MissingControllerException');
        $this->assertResponseContains('<em>DontexistController</em> could not be found.');
    }
    /**
     * test error method
     *
     * @return void
     */
    public function testError404DebugOff()
    {
        $this->get('https://localhost/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/dontexist');
    }
}
