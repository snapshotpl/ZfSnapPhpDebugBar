<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'ZfSnapPhpDebugBar\Tests\Functional\DummyController' => 'ZfSnapPhpDebugBar\Tests\Functional\DummyController',
        ),
    ),
    'php-debug-bar' => array(
        'auto-append-assets' => false,
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'ZfSnapPhpDebugBar\Tests\Functional\DummyController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);