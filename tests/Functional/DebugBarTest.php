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
        $this->setApplicationConfig(include __DIR__ . '/../assets/application.config.php');
    }
    
    public function testDebugBarNotFailByDefault()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
        $this->assertContains('var phpdebugbar = new PhpDebugBar.DebugBar();', $this->getResponse()->getContent());
    }
}
