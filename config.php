<?php

// HTTP

define('HTTP_SERVER', 'https://www.frpneu.cz/');



// HTTPS

define('HTTPS_SERVER', 'https://www.frpneu.cz/');



// DIR

define('DIR_APPLICATION', $_SERVER['DOCUMENT_ROOT'].'/catalog/');

define('DIR_SYSTEM', $_SERVER['DOCUMENT_ROOT'].'/system/');

define('DIR_DATABASE', $_SERVER['DOCUMENT_ROOT'].'/system/database/');

define('DIR_LANGUAGE', $_SERVER['DOCUMENT_ROOT'].'/catalog/language/');

define('DIR_TEMPLATE', $_SERVER['DOCUMENT_ROOT'].'/catalog/view/theme/');

define('DIR_CONFIG', $_SERVER['DOCUMENT_ROOT'].'/system/config/');

define('DIR_IMAGE', $_SERVER['DOCUMENT_ROOT'].'/image/');

//define('DIR_STORAGE', '/www/doc/www.frpneu.cz/home/storage_test/');
//define('DIR_STORAGE', '/www/doc/www.frpneu.cz/www/system/storage/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');


// DB

define('DB_DRIVER', 'mysqli');

define('DB_HOSTNAME', 'localhost');

define('DB_USERNAME', 'frpneucz');

define('DB_PASSWORD', '123');

define('DB_DATABASE', 'frpneucz');

define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

?>
