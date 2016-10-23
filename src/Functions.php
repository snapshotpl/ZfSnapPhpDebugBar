<?php

use ZfSnapPhpDebugBar\Module;

if (!function_exists('debugbar_log')) {
    /**
     * @param string $message
     * @param string $type
     */
    function debugbar_log($message, $type = 'debug')
    {
        Module::log($message, $type);
    }
}
