<?php

namespace ZfSnapPhpDebugBar\Log\Writer;

use DebugBar\DebugBar;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
final class PhpDebugBarFactory implements FactoryInterface
{
    /**
     * @return PhpDebugBar
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, PhpDebugBar::class);
    }

    /**
     * @return PhpDebugBar
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var $debugbar DebugBar */
        $debugbar = $container->get('debugbar');

        $messagesCollector = $debugbar['messages'];

        return new PhpDebugBar($messagesCollector);
    }

}
