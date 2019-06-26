<?php

namespace Dynali;

use Exception;

class DynaliException extends Exception {
    public function __construct($code, $message) {
        throw new Exception('[' . $code . '] ' . $message);
    }
}
