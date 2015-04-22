<?php

return array(
    'php-debug-bar' => array(

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
);
