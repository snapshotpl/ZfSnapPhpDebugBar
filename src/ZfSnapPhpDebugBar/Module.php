<?php

namespace ZfSnapPhpDebugBar;

use DebugBar\DataCollector\MessagesCollector;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\ViewEvent;
use Zend\Mvc\MvcEvent;

/**
 * Module of PHP Debug Bar
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class Module implements ConfigProviderInterface, ServiceProviderInterface, AutoloaderProviderInterface, ViewHelperProviderInterface, BootstrapListenerInterface
{
    /**
     * @var MessagesCollector
     */
    static protected $messageCollector;

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/zfsnapphpdebugbar.config.php';
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'DebugBar\DebugBar' => 'DebugBar\StandardDebugBar',
            ),
            'factories' => array(
                'DebugBar' => 'ZfSnapPhpDebugBar\Service\PhpDebugBarFactory',
                'ZfSnapPhpDebugBar\Log\Writer\PhpDebugBar' => 'ZfSnapPhpDebugBar\Log\Writer\PhpDebugBarFactory',
            ),
        );
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'debugbar' => 'ZfSnapPhpDebugBar\View\Helper\DebugBarFactory',
            ),
        );
    }

    /**
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        /* @var $application \Zend\Mvc\Application */
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $config = $serviceManager->get('config');

        if ($config['php-debug-bar']['enabled'] !== true) {
            return;
        }
        $applicationEventManager = $application->getEventManager();
        $viewEventManager = $serviceManager->get('View')->getEventManager();
        $viewRenderer = $serviceManager->get('ViewRenderer');
        $debugbar = $serviceManager->get('debugbar');
        $timeCollector = $debugbar['time'];
        $exceptionCollector = $debugbar['exceptions'];
        self::$messageCollector = $debugbar['messages'];
        $lastMeasure = null;

        // Enable messages function
        require __DIR__ . '/Functions.php';

        // Auto enable assets
        $viewRenderer->plugin('DebugBar')->appendAssets();

        // Timeline
        $measureListener = function(EventInterface $e) use ($timeCollector, &$lastMeasure) {
            if ($lastMeasure !== null) {
                $timeCollector->stopMeasure($lastMeasure);
            }
            $lastMeasure = $e->getName();

            if ($e instanceof ViewEvent) {
                $model = $e->getParam('model');

                if ($model instanceof ModelInterface) {
                    $lastMeasure .= ' (' . $model->getTemplate() . ')';
                }
            }
            $timeCollector->startMeasure($lastMeasure, $lastMeasure);
        };
        $applicationEventManager->attach('*', $measureListener);
        $viewEventManager->attach('*', $measureListener);

        // Exceptions
        $exceptionListener = function (EventInterface $event) use ($exceptionCollector) {
            $exception = $event->getParam('exception');

            if ($exception instanceof \Exception) {
                $exceptionCollector->addException($exception);
            }
        };
        $applicationEventManager->attach('*', $exceptionListener);
        $viewEventManager->attach('*', $exceptionListener);

        // Route
        $applicationEventManager->attach(MvcEvent::EVENT_ROUTE, function (EventInterface $e) use ($debugbar) {
            $route = $e->getRouteMatch();
            $machedName = $route->getMatchedRouteName();
            $data = $route->getParams();
            $tabName = 'Route ' . $machedName;
            $debugbar->addCollector(new \DebugBar\DataCollector\ConfigCollector($data, $tabName));
        });
    }

    /**
     * @param string $message
     * @param string $type
     */
    public static function log($message, $type = 'debug')
    {
        if (self::$messageCollector instanceof MessagesCollector) {
            self::$messageCollector->addMessage($message, $type);
        } else {
            throw new Exception('Unknown type of MessageCollector');
        }
    }

}