<?php

namespace ZfSnapPhpDebugBar\Log\Writer;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * PhpDebugBarFactory
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class PhpDebugBarFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PhpDebugBar
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $debugbar \DebugBar\DebugBar */
        $debugbar = $serviceLocator->get('debugbar');

        $messagesCollector = $debugbar['messages'];

        return new PhpDebugBar($messagesCollector);
    }

}
