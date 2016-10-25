<?php

namespace ZfSnapPhpDebugBar\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

final class ResourcesFactory
{
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof ServiceLocatorAwareInterface) {
            $container = $container->getServiceLocator();
        }

        $config = $container->get('config')['php-debug-bar']['view'];

        return new Resources($config['debugbar-resources'], $config['custom-resources']);
    }
}
