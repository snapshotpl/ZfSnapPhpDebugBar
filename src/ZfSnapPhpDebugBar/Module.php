<?php

namespace ZfSnapPhpDebugBar;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MessagesCollector;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface as Autoloader;
use Zend\ModuleManager\Feature\BootstrapListenerInterface as Bootstrap;
use Zend\ModuleManager\Feature\ConfigProviderInterface as Config;
use Zend\ModuleManager\Feature\ServiceProviderInterface as Service;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface as ViewHelper;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;
use Zend\View\ViewEvent;

/**
 * Module of PHP Debug Bar
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class Module implements Config, Service, Autoloader, ViewHelper, Bootstrap
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
        /* @var $application Application */
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $config = $serviceManager->get('config');
        $request = $application->getRequest();
        $debugbarConfig = $config['php-debug-bar'];

        if ($debugbarConfig['enabled'] !== true || !($request instanceof Request)) {
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

        $applicationEventManager->attach(MvcEvent::EVENT_FINISH, function (MvcEvent $event) use ($debugbar) {
            $response = $event->getResponse();

            if (!$response instanceof Response) {
                return;
            }
            $contentTypeHeader = $response->getHeaders()->get('Content-type');

            if ($contentTypeHeader && $contentTypeHeader->getFieldValue() !== 'text/html') {
                return;
            }

            $renderer = $debugbar->getJavascriptRenderer();
            $renderer->renderOnShutdown(false);
        });

        // Enable messages function
        require __DIR__ . '/Functions.php';

        // Auto enable assets
        if ($debugbarConfig['auto-append-assets']) {
            $viewRenderer->plugin('DebugBar')->appendAssets();
        }

        // Timeline
        $measureListener = function(EventInterface $e) use ($timeCollector, &$lastMeasure) {
            if ($lastMeasure !== null && $timeCollector->hasStartedMeasure($lastMeasure)) {
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
            $data = array(
                'route_name' => $route->getMatchedRouteName(),
                'params' => $route->getParams(),
            );
            $debugbar->addCollector(new ConfigCollector($data, 'Route'));
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
