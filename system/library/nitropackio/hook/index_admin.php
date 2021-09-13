<?php

require_once __DIR__ . '/bootstrap.php';

nitropackio\core\Nitropack::executionBlock(function() {
    ob_start(function($buf) {
        nitropackio\core\Nitropack::executeEventActions();

        return $buf;
    });

    register_shutdown_function("ob_end_flush");
});
