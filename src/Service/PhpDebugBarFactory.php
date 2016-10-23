<?php

namespace ZfSnapPhpDebugBar\Service;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PDO\TraceablePDO;
use DebugBar\DebugBar;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\Pdo\Pdo;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
final class PhpDebugBarFactory implements FactoryInterface
{
    /**
     * {@inehritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $debugBarConfig = $config['php-debug-bar'];
        $appConfig = $container->get('ApplicationConfig');
        /* @var $debugbar DebugBar */
        $debugbar = $container->get(DebugBar::class);
        $dbServiceName = $debugBarConfig['zend-db-adapter-service-name'];

        // Config Collectors
        $debugbar->addCollector(new ConfigCollector($config));
        $debugbar->addCollector(new ConfigCollector($appConfig, 'ApplicationConfig'));

        // Db profiler
        if ($container->has($dbServiceName) && isset($config['db']['driver'])) {
            $adapter = $container->get($dbServiceName);
            $this->prepareDbAdapter($adapter, $debugbar);
        }

        // Collectors
        $collectors = $debugBarConfig['collectors'];
        foreach ($collectors as $collectorName) {
            $collector = $container->get($collectorName);
            $debugbar->addCollector($collector);
        }

        // Storages
        $storageName = $debugBarConfig['storage'];
        if ($storageName !== null) {
            $storage = $container->get($storageName);
            $debugbar->setStorage($storage);
        }
        return $debugbar;
    }

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, DebugBar::class);
    }

    /**
     * @param AdapterInterface $adapter
     * @param DebugBar $debugbar
     */
    protected function prepareDbAdapter(AdapterInterface $adapter, DebugBar $debugbar)
    {
        $driver = $adapter->getDriver();

        if ($driver instanceof Pdo) {
            $pdo = $driver->getConnection()->getResource();
            $traceablePdo = new TraceablePDO($pdo);
            $pdoCollector = new PDOCollector($traceablePdo);

            $debugbar->addCollector($pdoCollector);
        }
    }

}
