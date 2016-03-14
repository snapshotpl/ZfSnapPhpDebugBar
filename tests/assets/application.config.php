<?php

return array(
    'modules' => array(
        'ZfSnapPhpDebugBar',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            __DIR__ . '/{global,local}.config.php',
        ),
    ),
);