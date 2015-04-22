<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * DummyController
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DummyController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->getResponse();
    }
}
