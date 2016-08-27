<?php

namespace ZfSnapPhpDebugBar\Collector;

use Interop\Container\ContainerInterface;
use DebugBar\Bridge\DoctrineCollector;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineCollectorFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $debugStack = $container->get('doctrine.configuration.orm_default')->getSQLLogger();
        return new DoctrineCollector($debugStack);
    }

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, DoctrineCollector::class);
    }
}