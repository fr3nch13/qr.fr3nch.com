<?php
declare(strict_types=1);

/**
 * Tests the Error Controller.
 */
namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\Constraint\Response\StatusCode;

/**
 * ErrorControllerTest class
 *
 * @uses \App\Controller\ErrorController
 */
class ErrorControllerTest extends BaseControllerTest
{
    /**
     * testDisplay method
     *
     * @return void
     */
    public function testError404()
    {
        // With Debug On
        Configure::write('debug', true);
        $this->get('https://localhost/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Controller');
        $this->assertResponseContains('<span>Missing Controller</span>');
        $this->assertResponseContains('Cake\Http\Exception\MissingControllerException');
        $this->assertResponseContains('<em>DontexistController</em> could not be found.');

        // With Debug Off
        Configure::write('debug', false);
        $this->get('https://localhost/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/dontexist');
    }
}
