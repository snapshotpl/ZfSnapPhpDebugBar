<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBarTest extends AbstractHttpControllerTestCase
{
    protected function setUp()
    {
        $this->traceError = false;
        $this->setApplicationConfig(include __DIR__ . '/../assets/application.config.php');
    }

    public function testNotFailPage()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
    }

    public function testContainsScript()
    {
        $this->dispatch('/');

        $this->assertResponseContains('var phpdebugbar = new PhpDebugBar.DebugBar();');
    }

    public function testContainsRouteData()
    {
        $this->dispatch('/');

        $this->assertResponseContains('home');
        $this->assertResponseContains('DummyController');
    }

    public function testContainsMeasure()
    {
        $this->dispatch('/');

        $this->assertResponseContains('(dummy\/index)');
    }

    public function testContainLoggedMessage()
    {
        // Only for initialize module
        $this->getApplicationServiceLocator()->get('DebugBar');

        debugbar_log('fooboomessage');

        $this->dispatch('/');

        $this->assertResponseContains('fooboomessage');
    }

    public function testContainExceptionMessage()
    {
        $this->dispatch('/error');

        $this->assertResponseStatusCode(500);

        $this->assertResponseContains(DummyController::EXCEPTION_MESSAGE);
    }

    public function testContainsUriToDebugBarResource()
    {
        $this->dispatch('/');

        $this->assertResponseContains('/DebugBar/Resources/debugbar.js');
    }

    public function testContainsUriToCustomResource()
    {
        $this->dispatch('/');

        $this->assertResponseContains('/zfsnapphpdebugbar/resources/zf-snap-php-debug-bar.css');
    }

    public function testGetStaticResourceFromDebugBar()
    {
        $this->dispatch('/DebugBar/Resources/debugbar.js');

        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'text/javascript; charset=UTF-8');
    }

    public function testGetStaticCustomResource()
    {
        $this->dispatch('/zfsnapphpdebugbar/resources/zf-snap-php-debug-bar.css');

        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'text/css; charset=UTF-8');
    }

    public function testPartial()
    {
        $this->dispatch('/partial');
        $this->assertNotResponseContains('var phpdebugbar = new PhpDebugBar.DebugBar();');
    }

    private function assertResponseContains($string)
    {
        $this->assertContains($string, $this->getResponse()->getContent());
    }

    private function assertNotResponseContains($string)
    {
        $this->assertNotContains($string, $this->getResponse()->getContent());
    }
}
