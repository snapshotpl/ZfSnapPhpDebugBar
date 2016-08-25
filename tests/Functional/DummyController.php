<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DummyController extends AbstractActionController
{
    public function indexAction()
    {
        return (new ViewModel())->setTerminal(true)->setTemplate('dummy/index');
    }
}
