<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * DebugBarTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBarTest extends AbstractHttpControllerTestCase
{
    public function testDebugBarNotFailByDefault()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
        $debugbar = $this->getApplicationServiceLocator()->get('debugbar');

        $this->assertInstanceOf('\DebugBar\DebugBar', $debugbar);
    }
    
    protected function setUp()
    {
        $this->setApplicationConfig(include __DIR__ . '/../assets/application.config.php');
    }
}
