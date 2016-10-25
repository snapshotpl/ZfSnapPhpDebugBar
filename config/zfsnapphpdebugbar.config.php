<?php

return [
    'php-debug-bar' => [
        'view' => [
            'custom-style-path' => 'zf-snap-php-debug-bar.css',
            'debugbar-resources' => __DIR__.'/../../../maximebf/debugbar/src/DebugBar/Resources/',
            'custom-resources' => __DIR__.'/../assets/',
        ],
        'enabled' => true,
        'auto-append-assets' => true,
        'render-on-shutdown' => true,
        'zend-db-adapter-service-name' => Zend\Db\Adapter\Adapter::class,
        // ServiceManager service keys to inject collectors
        // http://phpdebugbar.com/docs/data-collectors.html
        'collectors' => [],
        // ServiceManager service key to inject storage
        // http://phpdebugbar.com/docs/storage.html
        'storage' => null,
    ],
    'service_manager' => [
        'invokables' => [
            DebugBar\DebugBar::class => DebugBar\StandardDebugBar::class,
        ],
        'factories' => [
            'debugbar' => ZfSnapPhpDebugBar\Service\PhpDebugBarFactory::class,
            ZfSnapPhpDebugBar\Log\Writer\PhpDebugBar::class => ZfSnapPhpDebugBar\Log\Writer\PhpDebugBarFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ZfSnapPhpDebugBar\Controller\Resources::class => ZfSnapPhpDebugBar\Controller\ResourcesFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'debugbar' => ZfSnapPhpDebugBar\View\Helper\DebugBarFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'phpdebugbar-resource' => [
                'type' => 'regex',
                'options' => [
                    'regex' => '/DebugBar/Resources/(?<resource>[a-zA-Z0-9_.\-/]+)',
                    'spec' => '/DebugBar/Resources/%resource%',
                    'defaults' => [
                        'controller' => ZfSnapPhpDebugBar\Controller\Resources::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'phpdebugbar-custom-resource' => [
                'type' => 'regex',
                'options' => [
                    'regex' => '/zfsnapphpdebugbar/resources/(?<resource>[a-zA-Z0-9_.\-/]+)',
                    'spec' => '/zfsnaphhpdebugbar/resources/%resource%',
                    'defaults' => [
                        'controller' => ZfSnapPhpDebugBar\Controller\Resources::class,
                        'action' => 'custom',
                    ],
                ],
            ],
        ],
    ],
];
