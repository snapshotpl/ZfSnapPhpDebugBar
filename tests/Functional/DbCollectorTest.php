<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

/**
 * DbCollectorTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DbCollectorTest extends \Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase
{
    protected function setUp()
    {
        $this->setApplicationConfig(include __DIR__.'/../assets/application.config.php');
    }

    public function testDbIsNotConfigured()
    {
        $this->dispatch('/');

        $this->assertResponseStatusCode(200);
    }
}
