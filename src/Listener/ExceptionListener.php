<?php

namespace ZfSnapPhpDebugBar\Listener;

use DebugBar\DataCollector\ExceptionsCollector;
use Exception;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use ZfSnapPhpDebugBar\Exception\ThrowableException;

/**
 * @author witold
 */
final class ExceptionListener extends AbstractListenerAggregate
{

    private $exceptionsCollector;

    public function __construct(ExceptionsCollector $exceptionsCollector)
    {
        $this->exceptionsCollector = $exceptionsCollector;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach('*', [$this, 'collectException'], $priority);
    }

    public function collectException(EventInterface $event)
    {
        $exception = $event->getParam('exception');

        if ($exception instanceof Exception || $exception instanceof \Throwable) {
            $exception = $exception instanceof \Throwable ? new ThrowableException($exception) : $exception;

            $this->exceptionsCollector->addException($exception);
        }
    }
}
