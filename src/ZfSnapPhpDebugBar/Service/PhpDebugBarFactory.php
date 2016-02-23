<?php

namespace ZfSnapPhpDebugBar\Service;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DebugBar;
use DebugBar\DataCollector\PDO\TraceablePDO;
use DebugBar\DataCollector\PDO\PDOCollector;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface as Locator;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\Pdo\Pdo;

/**
 * PhpDebugBarFactory
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class PhpDebugBarFactory implements FactoryInterface
{

    /**
     *
     * @param Locator $serviceLocator
     * @return DebugBar
     */
    public function createService(Locator $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $debugBarConfig = $config['php-debug-bar'];
        $appConfig = $serviceLocator->get('ApplicationConfig');
        /* @var $debugbar DebugBar */
        $debugbar = $serviceLocator->get('DebugBar\DebugBar');
        $dbServiceName = $debugBarConfig['zend-db-adapter-service-name'];

        // Config Collectors
        $debugbar->addCollector(new ConfigCollector($config));
        $debugbar->addCollector(new ConfigCollector($appConfig, 'ApplicationConfig'));

        // Db profiler
        if ($serviceLocator->has($dbServiceName) && isset($config['db']['driver'])) {
            $adapter = $serviceLocator->get($dbServiceName);
            $this->prepareDbAdapter($adapter, $debugbar);
        }

        // Collectors
        $collectors = $debugBarConfig['collectors'];
        foreach ($collectors as $collectorName) {
            $collector = $serviceLocator->get($collectorName);
            $debugbar->addCollector($collector);
        }

        // Storages
        $storageName = $debugBarConfig['storage'];
        if ($storageName !== null) {
            $storage = $serviceLocator->get($storageName);
            $debugbar->setStorage($storage);
        }
        return $debugbar;
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
