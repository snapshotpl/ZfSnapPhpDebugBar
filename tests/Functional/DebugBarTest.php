<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

/**
 * DebugBarTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBarTest extends \Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase
{
    protected function setUp()
    {
        $this->setApplicationConfig(include __DIR__.'/../assets/application.config.php');
    }

    public function testDebugBarNotFailByDefault()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
        $debugbar = $this->getApplicationServiceLocator()->get('debugbar');

        $this->assertInstanceOf('\DebugBar\DebugBar', $debugbar);
    }
}
