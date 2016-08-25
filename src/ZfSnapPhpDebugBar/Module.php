<?php

namespace ZfSnapPhpDebugBar;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MessagesCollector;
use Exception;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\BootstrapListenerInterface as Bootstrap;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;
use Zend\View\ViewEvent;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
final class Module implements ConfigProviderInterface, Bootstrap
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

    public function onBootstrap(EventInterface $event)
    {
        /* @var $application Application */
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $config = $serviceManager->get('config');
        $request = $application->getRequest();
        $debugbarConfig = $config['php-debug-bar'];

        if ($debugbarConfig['enabled'] !== true || !($request instanceof Request)) {
            return;
        }
        $applicationEventManager = $application->getEventManager();
        $viewEventManager = $serviceManager->get('View')->getEventManager();
        $debugbarViewHelper = $serviceManager->get('ViewHelperManager')->get('debugbar');
        $viewRenderer = $serviceManager->get('ViewRenderer');
        $debugbar = $serviceManager->get('DebugBar');
        $timeCollector = $debugbar['time'];
        $exceptionCollector = $debugbar['exceptions'];
        self::$messageCollector = $debugbar['messages'];
        $lastMeasure = null;
        $rendedOnShutdown = $debugbarConfig['render-on-shutdown'];

        $applicationEventManager->attach(MvcEvent::EVENT_FINISH, function (MvcEvent $event) use ($debugbar, $rendedOnShutdown, $debugbarViewHelper) {
            $response = $event->getResponse();

            if (!$response instanceof Response) {
                return;
            }
            $contentTypeHeader = $response->getHeaders()->get('Content-type');

            if ($contentTypeHeader && $contentTypeHeader->getFieldValue() !== 'text/html') {
                return;
            }
            
            if ($rendedOnShutdown) {
                $renderer = $debugbar->getJavascriptRenderer();
                $renderer->renderOnShutdown(false);
            }
        });

        // Auto enable assets
        if ($debugbarConfig['auto-append-assets']) {
            $viewRenderer->plugin('debugbar')->appendAssets();
        }

        // Timeline
        $measureListener = function(EventInterface $event) use ($timeCollector, &$lastMeasure) {
            if ($lastMeasure !== null && $timeCollector->hasStartedMeasure($lastMeasure)) {
                $timeCollector->stopMeasure($lastMeasure);
            }
            $lastMeasure = $event->getName();

            if ($event instanceof ViewEvent) {
                $model = $event->getParam('model');

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

            if ($exception instanceof Exception) {
                $exceptionCollector->addException($exception);
            }
        };
        $applicationEventManager->attach('*', $exceptionListener);
        $viewEventManager->attach('*', $exceptionListener);

        // Route
        $applicationEventManager->attach(MvcEvent::EVENT_ROUTE, function (EventInterface $event) use ($debugbar) {
            $route = $event->getRouteMatch();
            $data = [
                'route_name' => $route->getMatchedRouteName(),
                'params' => $route->getParams(),
            ];
            $debugbar->addCollector(new ConfigCollector($data, 'Route'));
        });
    }

    /**
     * @param string $message
     * @param string $type
     */
    public static function log($message, $type = 'debug')
    {
        if (!self::$messageCollector instanceof MessagesCollector) {
            throw new Exception('Unknown type of MessageCollector');
        }
        self::$messageCollector->addMessage($message, $type);
    }

}
