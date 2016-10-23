<?php

namespace ZfSnapPhpDebugBar\Delegator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\DBAL\Logging\DebugStack;

/**
 * {@inheritdoc}
 */
class DoctrineConfigurationDelegatorFactory implements DelegatorFactoryInterface
{
    /**
    * {@inheritdoc}
    */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $doctrineConfiguration = $callback();
        $doctrineConfiguration->setSQLLogger(new DebugStack);
        return $doctrineConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        return $this($serviceLocator, $requestedName, $callback);
    }
}
