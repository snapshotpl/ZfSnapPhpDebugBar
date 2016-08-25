<?php

$modules = [];
$zf3Modules = [
    'Zend\Db',
    'Zend\Router',
];
foreach ($zf3Modules as $module) {
    $dirModule = strtolower(str_replace('\\', '-', $module));
    if (class_exists($module .'\Module')) {
        $modules[] = $module;
    }
}
$modules[] = 'ZfSnapPhpDebugBar';

return [
    'modules' => $modules,
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/{global,local}.config.php',
        ],
    ],
];
