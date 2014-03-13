<?php

namespace ZfSnapPhpDebugBar\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * DebugBarFactory
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBarFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return DebugBar
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();
        /* @var $debugbar \DebugBar\DebugBar */
        $debugbar = $sm->get('DebugBar');
        $renderer = $debugbar->getJavascriptRenderer();

        $renderer->setBaseUrl('/DebugBar/Resources/');
        $renderer->setBasePath('/DebugBar/Resources/');
        $renderer->renderOnShutdown(false);

        return new DebugBar($renderer);
    }

}
