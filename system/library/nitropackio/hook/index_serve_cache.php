<?php

require_once __DIR__ . '/bootstrap.php';

nitropackio\hook\Bootstrap::nitropackWrapperMethod(function($nitropack) {
    // Serve local cache
    $nitropack->serveLocalCache();

    // If there is no local cache add an output buffer for the purposes of cookie management
    if ($nitropack->setting->get('status', false)) {
        ob_start(function($buf) use ($nitropack) {
            $nitropack->languageFix();
            $nitropack->currencyFix();
            $nitropack->cookie();
            $nitropack->pushTags();

            nitropackio\core\Nitropack::executeEventActions();

            $nitropack->tracking($buf);

            return $buf;
        });

        register_shutdown_function("ob_end_flush");
    }
});
