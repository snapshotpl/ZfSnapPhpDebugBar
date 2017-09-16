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
        'view' => [
            'debugbar-resources' => __DIR__.'/../../vendor/maximebf/debugbar/src/DebugBar/Resources/',
        ],
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
            'home-partial' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/partial',
                    'defaults' => [
                        'controller' => ZfSnapPhpDebugBar\Tests\Functional\DummyController::class,
                        'action' => 'partial',
                    ],
                ],
            ],
            'error' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/error',
                    'defaults' => [
                        'controller' => ZfSnapPhpDebugBar\Tests\Functional\DummyController::class,
                        'action' => 'error',
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
            'layout/layout' => __DIR__ . '/views/layout.phtml',
            'dummy/index' => __DIR__ . '/views/view.phtml',
            'error' => __DIR__ . '/views/view.phtml',
        ],
    ],
];
