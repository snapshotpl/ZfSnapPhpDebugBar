<?php

return [
    'controllers' => [
        'invokables' => [
            ZfSnapPhpDebugBar\Tests\Functional\DummyController::class => ZfSnapPhpDebugBar\Tests\Functional\DummyController::class,
        ],
    ],
    'php-debug-bar' => [
        'auto-append-assets' => true,
        'render-on-shutdown' => false,
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => ZfSnapPhpDebugBar\Tests\Functional\DummyController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Zend\Db\Adapter\Adapter::class => Zend\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_map' => [
            'dummy/index' => __DIR__ . '/views/view.phtml',
            'error' => __DIR__ . '/views/view.phtml',
        ],
    ],
];
