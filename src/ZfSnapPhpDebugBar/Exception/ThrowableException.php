<?php

namespace ZfSnapPhpDebugBar\Exception;

use Exception;

/**
 * @author witold
 */
class ThrowableException extends Exception
{

    public function __construct(\Throwable $exception)
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }
}
