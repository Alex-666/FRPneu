<?php

namespace nitropackio\hook;

require_once DIR_SYSTEM . 'library/nitropackio/sdk/autoload.php';

// Init config
if (is_file(DIR_CONFIG . 'nitropackio/override.php')) {
    require_once DIR_CONFIG . 'nitropackio/override.php';
}

require_once DIR_CONFIG . 'nitropackio/default.php';

use nitropackio\core\Nitropack;
use nitropackio\core\exception\Domain as DomainException;

class Bootstrap {
    public static function nitropackWrapperMethod($callback) {
        Nitropack::executionBlock(function() use (&$callback) {
            try {
                $nitropack = Nitropack::getInstance();
            } catch (DomainException $e) {
                // Do nothing in case there is a domain exception error
                return;
            }

            $callback($nitropack);
        });
    }
}
