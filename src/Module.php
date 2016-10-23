<?php

namespace ZfSnapPhpDebugBar;

use DebugBar\DataCollector\MessagesCollector;
use RuntimeException;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\ModuleManager\Feature\BootstrapListenerInterface as Bootstrap;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use ZfSnapPhpDebugBar\Listener\MeasureListener;
use ZfSnapPhpDebugBar\Listener\RenderOnShutdownListener;
use ZfSnapPhpDebugBar\Listener\RouteListener;

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
        $baseConfig = include __DIR__ . '/../config/zfsnapphpdebugbar.config.php';

        if (!class_exists(ServiceLocatorAwareInterface::class)) {
            $baseConfig += include __DIR__ . '/../config/aliases.config.php';
        }

        return $baseConfig;
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
        $viewRenderer = $serviceManager->get('ViewRenderer');
        $debugbar = $serviceManager->get('DebugBar');
        $timeCollector = $debugbar['time'];
        $exceptionsCollector = $debugbar['exceptions'];
        self::$messageCollector = $debugbar['messages'];
        $rendedOnShutdown = $debugbarConfig['render-on-shutdown'];

        (new RenderOnShutdownListener($debugbar->getJavascriptRenderer(), $rendedOnShutdown))->attach($applicationEventManager);

        // Auto enable assets
        if ($debugbarConfig['auto-append-assets']) {
            $viewRenderer->plugin('debugbar')->appendAssets();
        }

        // Timeline
        $measureListener = new MeasureListener($timeCollector);
        $measureListener->attach($applicationEventManager);
        $measureListener->attach($viewEventManager);

        // Exceptions
        $exceptionListener = new Listener\ExceptionListener($exceptionsCollector);
        $exceptionListener->attach($applicationEventManager);
        $exceptionListener->attach($viewEventManager);

        // Route
        (new RouteListener($debugbar))->attach($applicationEventManager);
    }

    /**
     * @param string $message
     * @param string $type
     */
    public static function log($message, $type = 'debug')
    {
        if (!self::$messageCollector instanceof MessagesCollector) {
            throw new RuntimeException(sprintf('Invalid %s', MessagesCollector::class));
        }
        self::$messageCollector->addMessage($message, $type);
    }
}
