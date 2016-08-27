<?php

return [
    'php-debug-bar' => [
        'view' => [
            'custom-style-path' => 'zf-snap-php-debug-bar.css',
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
            DebugBar\Bridge\DoctrineCollector::class => ZfSnapPhpDebugBar\Collector\DoctrineCollectorFactory::class,
        ],
        'delegators' => [
            'doctrine.configuration.orm_default' => [
                ZfSnapPhpDebugBar\Delegator\DoctrineConfigurationDelegatorFactory::class,
            ],
        ],
        'aliases' => [
            'DebugBar' => 'debugbar',
            'Debugbar' => 'debugbar',
        ],
    ],
    'controllers' => [
        'invokables' => [
            ZfSnapPhpDebugBar\Controller\Resources::class => ZfSnapPhpDebugBar\Controller\Resources::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'debugbar' => ZfSnapPhpDebugBar\View\Helper\DebugBarFactory::class,
        ],
        'aliases' => [
            'DebugBar' => 'debugbar',
            'Debugbar' => 'debugbar',
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
                    'regex' => '/ZfSnapPhpDebugBar/Resources/(?<resource>[a-zA-Z0-9_.\-/]+)',
                    'spec' => '/ZfSnapPhpDebugBar/Resources/%resource%',
                    'defaults' => [
                        'controller' => ZfSnapPhpDebugBar\Controller\Resources::class,
                        'action' => 'custom',
                    ],
                ],
            ],
        ],
    ],
];
