<?php

namespace ZfSnapPhpDebugBar\Tests\Functional;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DummyController extends AbstractActionController
{

    const EXCEPTION_MESSAGE = 'ExceptionMessage';

    public function indexAction()
    {
        return (new ViewModel())->setTemplate('dummy/index');
    }

    public function errorAction()
    {
        throw new \Exception(self::EXCEPTION_MESSAGE);
    }

    public function partialAction()
    {
        return (new ViewModel)->setTemplate('dummy/index')->setTerminal(true);
    }
}
