<?php

require_once __DIR__ . '/bootstrap.php';

if (!empty($_SERVER["HTTP_X_NITROPACK_REQUEST"])) {
    nitropackio\core\Nitropack::header("X-Nitro-Disabled: 1");

    if (isset($error, $errstr, $errfile, $errline)) {
        nitropackio\core\Nitropack::logDebugMessage('ERROR_HANDLER (X-Nitro-Disabled: 1) | PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }
}

nitropackio\core\Nitropack::$overrideStatus = false;
