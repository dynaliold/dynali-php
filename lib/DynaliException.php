<?php

namespace Dynali;

use Exception;

/**
 * DynaliException
 *
 * Throws an exception with the Dynali error's code.
 */
class DynaliException extends Exception
{
    public function __construct($code, $message)
    {
        throw new Exception('[' . $code . '] ' . $message);
    }
}
