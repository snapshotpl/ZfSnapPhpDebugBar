<?php

namespace ZfSnapPhpDebugBar\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

/**
 * DebugBarFactory
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBarFactory implements FactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var $debugbar \DebugBar\DebugBar */
        $debugbar = $container->get('DebugBar');
        $renderer = $debugbar->getJavascriptRenderer();

        $renderer->setBaseUrl('/DebugBar/Resources/');
        $renderer->setBasePath('/DebugBar/Resources/');

        $config = $container->get('Config');
        $customStyle = $config['php-debug-bar']['view']['custom-style-path'];

        return new DebugBar($renderer, $customStyle);
    }

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), 'ZfSnapPhpDebugBar\View\Helper\DebugBar');
    }

}
