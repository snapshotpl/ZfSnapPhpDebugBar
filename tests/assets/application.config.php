<?php

$modules = array(
    'ZfSnapPhpDebugBar',
);
$zf3Modules = array(
    'Zend\Db',
    'Zend\Router',
);
foreach ($zf3Modules as $module) {
    $dirModule = strtolower(str_replace('\\', '-', $module));
    if (is_file(__DIR__ . "/../../vendor/zendframework/{$dirModule}/src/Module.php")) {
        array_unshift($modules, $module);
    }
}
return array(
    'modules' => $modules,
    'module_listener_options' => array(
        'config_glob_paths' => array(
            __DIR__ . '/{global,local}.config.php',
        ),
    ),
);