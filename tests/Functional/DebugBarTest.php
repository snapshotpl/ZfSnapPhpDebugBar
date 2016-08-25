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
    
    public function testNotFailPage()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
    }
    
    public function testContainsScript()
    {
        $this->dispatch('/');

        $this->assertContains('var phpdebugbar = new PhpDebugBar.DebugBar();', $this->getResponse()->getContent());
    }
    
    public function testContainLoggedMessage()
    {
        // Only for initialize module
        $this->getApplicationServiceLocator()->get('DebugBar');
        
        debugbar_log('fooboomessage');
        
        $this->dispatch('/');

        $this->assertContains('fooboomessage', $this->getResponse()->getContent());
    }
}
