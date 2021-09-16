<?php

// HTTP

define('HTTP_SERVER', 'http://www.frpneu.cz/admin/');

define('HTTP_CATALOG', 'http://www.frpneu.cz/');



// HTTPS

define('HTTPS_SERVER', 'https://www.frpneu.cz/admin/');

define('HTTPS_CATALOG', 'https://www.frpneu.cz/');



// DIR

define('DIR_APPLICATION', $_SERVER['DOCUMENT_ROOT'].'/admin/');

define('DIR_SYSTEM', $_SERVER['DOCUMENT_ROOT'].'/system/');

define('DIR_DATABASE', $_SERVER['DOCUMENT_ROOT'].'/system/database/');

define('DIR_LANGUAGE', $_SERVER['DOCUMENT_ROOT'].'/admin/language/');

define('DIR_TEMPLATE', $_SERVER['DOCUMENT_ROOT'].'/admin/view/template/');

define('DIR_CONFIG', $_SERVER['DOCUMENT_ROOT'].'/system/config/');

define('DIR_IMAGE', $_SERVER['DOCUMENT_ROOT'].'/image/');

//define('DIR_STORAGE', '/www/doc/www.frpneu.cz/home/storage_test/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');

define('DIR_CACHE', DIR_STORAGE . 'cache/');

define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');

define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');

define('DIR_SESSION', DIR_STORAGE . 'session/');

define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

define('DIR_LOGS', DIR_STORAGE . 'logs/');

define('DIR_CATALOG', $_SERVER['DOCUMENT_ROOT'].'/catalog/');



// DB

define('DB_DRIVER', 'mysqli');

define('DB_HOSTNAME', 'localhost');

define('DB_USERNAME', 'frpneucz');

define('DB_PASSWORD', '123');

define('DB_DATABASE', 'frpneucz');

define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

define('OPENCART_SERVER', 'http://www.opencart.com/');
?>
