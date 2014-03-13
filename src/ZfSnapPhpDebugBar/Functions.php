<?php

/**
 * @param string $message
 * @param string $type
 */
function debugbar_log($message, $type = 'debug') {
     \ZfSnapPhpDebugBar\Module::log($message, $type);
}