<?php

namespace ZfSnapPhpDebugBar\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Factory of the Resources controller.
 * 
 * @author Vasilij Belosludcev <https://github.com/bupy7>
 * @since 0.11.2
 */
class ResourcesFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $ctrl = new Resources;
        $ctrl->setServiceLocator($serviceLocator->getServiceLocator());
        return $ctrl;
    }
}

