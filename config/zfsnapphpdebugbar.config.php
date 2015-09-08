<?php

return array(
    'php-debug-bar' => array(
        'view' => array(
            'custom-style-path' => 'zf-snap-php-debug-bar.css',
        ),
        // Enables/disables PHP Debug Bar
        'enabled' => true,

        'auto-append-assets' => true,

        'zend-db-adapter-service-name' => 'Zend\Db\Adapter\Adapter',

        // ServiceManager keys to inject collectors
        // http://phpdebugbar.com/docs/data-collectors.html
        'collectors' => array(),

        // ServiceManager key to inject storage
        // http://phpdebugbar.com/docs/storage.html
        'storage' => null,
    ),
    'controllers' => array(
        'invokables' => array(
            'ZfSnapPhpDebugBar\Controller\Resources' => 'ZfSnapPhpDebugBar\Controller\Resources',
        ),
    ),
    'router' => array(
        'routes' => array(
            'phpdebugbar-resource' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/DebugBar/Resources/(?<resource>[a-zA-Z0-9_.\-/]+)',
                    'spec' => '/DebugBar/Resources/%resource%',
                    'defaults' => array(
                        'controller' => 'ZfSnapPhpDebugBar\Controller\Resources',
                        'action' => 'index',
                    ),
                ),
            ),
            'phpdebugbar-custom-resource' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/ZfSnapPhpDebugBar/Resources/(?<resource>[a-zA-Z0-9_.\-/]+)',
                    'spec' => '/ZfSnapPhpDebugBar/Resources/%resource%',
                    'defaults' => array(
                        'controller' => 'ZfSnapPhpDebugBar\Controller\Resources',
                        'action' => 'custom',
                    ),
                ),
            ),
        ),
    ),
);
