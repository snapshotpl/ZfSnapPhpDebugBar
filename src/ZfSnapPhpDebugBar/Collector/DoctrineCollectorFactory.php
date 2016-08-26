<?php

namespace ZfSnapPhpDebugBar\Collector;

use Interop\Container\ContainerInterface;
use DebugBar\Bridge\DoctrineCollector;
use Doctrine\DBAL\Logging\DebugStack;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory of `DoctrineCollector`.
 * @author Vasilij Belosludcev <https://github.com/bupy7>
 * @see DoctrineCollector
 */
class DoctrineCollectorFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $debugStack = new DebugStack;
        $entityManager = $container->get('Doctrine\ORM\EntityManager');
        $entityManager->getConnection()->getConfiguration()->setSQLLogger($debugStack);
        $collector = new DoctrineCollector($debugStack);
        return $collector;
    }

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, DoctrineCollector::class);
    }
}