<?php

namespace nitropackio\compatibility\model;

use nitropackio\compatibility\Model as NitropackModel;
use nitropackio\compatibility\traits\CurrencyLoader;
use nitropackio\compatibility\traits\EventsLoader;
use nitropackio\core\EntityState;
use nitropackio\core\Nitropack;
use nitropackio\core\Tag;
use nitropackio\core\EventAction;
use NitroPack\PurgeType;

class Admin extends NitropackModel {
    use CurrencyLoader;
    use EventsLoader;

    public function __construct($registry) {
        parent::__construct($registry);

        if (!$this->registry->has("nitropack_entity_state")) {
            $this->registry->set("nitropack_entity_state", new EntityState($this->registry));
        }
    }

    public function updateEvents($new_events) {
        if (version_compare(VERSION, '<', '2')) {
            // Do nothing, as in OC 1.x, there are no events.
            return;
        }

        // Add missing events
        foreach ($new_events as $trigger => $actions) {
            foreach ((array)$actions as $action) {
                if (!$this->eventExists("nitropack", $trigger, $action)) {
                    $this->getEventModel()->addEvent("nitropack", $trigger, $action);
                }
            }
        }

        // Remove outdated events
        foreach ($this->getEventsByCode("nitropack") as $event) {
            if (!isset($new_events[$event['trigger']])) {
                $this->deleteEventsByTrigger($event['trigger']);
            } else if (!in_array($event['action'], (array)$new_events[$event['trigger']])) {
                $this->deleteEventsByAction($event['action']);
            }
        }
    }

    public function hookToIndex($index, $search, $replace) {
        if ($this->backupIndexPhp($index)) {
            $original_content = file_get_contents($index);

            if (!preg_match('~//\s+BEGIN\s+NitroPack~i', $original_content)) {
                $new_content = preg_replace(
                    $search,
                    $replace,
                    $original_content
                );

                file_put_contents($index, $new_content);
            }
        }
    }

    public function updateIndex($index) {
        if ($this->backupIndexPhp($index)) {
            $original_content = file_get_contents($index);

            if (preg_match('~vendor/isenselabs/nitropack~', $original_content)) {
                $new_content = preg_replace(
                    '~vendor/isenselabs/nitropack~',
                    'nitropackio',
                    $original_content
                );

                file_put_contents($index, $new_content);
            }
        }
    }

    public function unhookFromIndex($index) {
        $with_nitropack_content = file_get_contents($index);

        $without_nitropack_content = preg_replace('~//\s+BEGIN\s+NitroPack.*?//\s+END\s+NitroPack\s+~mis', '', $with_nitropack_content);

        file_put_contents($index, $without_nitropack_content);
    }

    public function initJsonSettings() {
        foreach ($this->getStores() as $store_id => $store) {
            Nitropack::executionBlock(function() use (&$store) {
                $nitropack = $this->nitropackFromStoreUrl($store['url']);

                $nitropack->setting->save();
            });
        }
    }

    public function purgeJsonSettings() {
        foreach ($this->getStores() as $store_id => $store) {
            Nitropack::executionBlock(function() use (&$store) {
                $nitropack = $this->nitropackFromStoreUrl($store['url'], false);

                $nitropack->setting->purge();
            });
        }
    }

    public function initDefaults() {
        $this->updateActiveLanguages();
        $this->updateDefaultLanguage();
        $this->updateDefaultCurrency();
        $this->updateMaintenanceMode();
        $this->updateStoreId();
    }

    public function excludeStandardPages($included = array()) {
        $routes = array();

        foreach ($included as $key) {
            $routes[] = Nitropack::$standard_routes[$key];
        }

        return array_values(array_diff(array_values(Nitropack::$standard_routes), $routes));
    }

    public function getStandardPages($excluded = array()) {
        $result = array();

        foreach (Nitropack::$standard_routes as $key => $route) {
            $result[] = array(
                'key' => $key,
                'route' => $route,
                'status' => !in_array($route, $excluded),
                'name' => $this->language->get('page_' . $key)
            );
        }

        return $result;
    }

    public function getWarmupRoutes() {
        return array(
            array('name' => 'Home', 'value' => 'common/home'),
            array('name' => 'Categories', 'value' => 'product/category'),
            array('name' => 'Informations', 'value' => 'information/information'),
            array('name' => 'Manufacturers', 'value' => 'product/manufacturer'),
            array('name' => 'Products', 'value' => 'product/product'),
            array('name' => 'Specials', 'value' => 'product/special'),
        );
    }

    public function getAllPages() {
        $dir = dirname(DIR_SYSTEM) . '/catalog/controller';

        $result = array();

        $this->findRoutes($dir, array(), $result);

        // Sort routes
        sort($result);

        return $result;
    }

    public function debugLog($message) {
        Nitropack::logDebugMessage($message);
    }

    public function isDescriptionFieldChanged($field, $before, $after) {
        $intersected_by_key = array_intersect_key($before, $after);

        if (count($intersected_by_key) != count($before)) {
            return true;
        }

        foreach ($before as $language_id => $data) {
            if (!isset($before[$language_id][$field]) || !isset($after[$language_id][$field]) || $before[$language_id][$field] !== $after[$language_id][$field]) {
                return true;
            }
        }

        return false;
    }

    public function isSeoUrlsChanged($before, $after) {
        foreach (array_keys($before) as $language_id) {
            if ($before[$language_id] !== $after[$language_id]) {
                return true;
            }
        }

        return false;
    }

    public function hasStateDifference($before, $after, &$difference_in = null, $context = array(), $excluded_keys = array()) {
        if (is_array($before) && is_array($after)) {
            $intersected_by_key = array_intersect_key($before, $after);

            if (count($intersected_by_key) != count($before)) {
                if (is_array($difference_in)) {
                    $difference_in[] = "Keys of " . implode('->', $context) . " | Before: " . implode(',', array_keys($before)) . ' | After: ' . implode(',', array_keys($after));
                }

                return true;
            }

            foreach ($before as $key_before => $value_before) {
                if (in_array($key_before, $excluded_keys, true)) {
                    // We do not care if there is an edition in the date_modified value
                    continue;
                }

                $new_context = $context;
                $new_context[] = $key_before;

                if ($this->hasStateDifference($value_before, $after[$key_before], $difference_in, $new_context, $excluded_keys)) {
                    return true;
                }
            }

            return false;
        } else if (!is_array($before) && !is_array($after)) {
            $beforeSerialized = serialize($before);
            $afterSerizlied = serialize($after);

            if ($beforeSerialized !== $afterSerizlied) {
                if (is_array($difference_in)) {
                    $difference_in[] = "Values of " . implode('->', $context) . " | Before (serialized): <<< " . $beforeSerialized . ' >>> | After (serialized): <<< ' . $afterSerizlied . ' >>>';
                }

                return true;
            } else {
                return false;
            }
        } else {
            if (is_array($difference_in)) {
                $difference_in[] = "Types of " . implode('->', $context) . " | Before: " . gettype($before) . ' | After: ' . gettype($after);
            }

            return true;
        }
    }

    protected function getBefore($type, $entity_id) {
        if ($this->registry->has("nitropack_entity_state") && $this->nitropack_entity_state->hasState($type, $entity_id)) {
            return $this->nitropack_entity_state->getState($type, $entity_id);
        }

        return null;
    }

    protected function checkDatabaseDate($date, $operator = ">=") {
        return (bool)$this->db->query('SELECT NOW() ' . $operator . ' "' . $date . '" as test')->row['test'];
    }

    public function updateActiveLanguages() {
        $active_languages = array();

        foreach ($this->getLanguages() as $language) {
            $active_languages[] = array(
                'code' => $language['code'],
                'locales' => array_values(array_filter(array_map('trim', explode(',', strtolower($language['locale'])))))
            );
        }

        $this->multiStoreWrapper(function($nitropack, $store) use (&$active_languages) {
            $nitropack->setting->set('active_languages', $active_languages);
            $nitropack->setting->save();
        });
    }

    public function getDefaultLanguage($store_id) {
        return $this->getSettingValue('config_language', $store_id);
    }

    public function getDefaultCurrency($store_id) {
        return $this->getSettingValue('config_currency', $store_id);
    }

    public function updateDefaultLanguage() {
        $this->multiStoreWrapper(function($nitropack, $store) {
            $nitropack->setting->set('default_language', $this->getDefaultLanguage($store['store_id']));
            $nitropack->setting->save();
        });
    }

    public function updateDefaultCurrency() {
        $this->multiStoreWrapper(function($nitropack, $store) {
            $nitropack->setting->set('default_currency', $this->getDefaultCurrency($store['store_id']));
            $nitropack->setting->save();
        });
    }

    public function updateMaintenanceMode() {
        $maintenance_mode = (bool)$this->getSettingValue('config_maintenance');

        $this->multiStoreWrapper(function($nitropack, $store) use (&$maintenance_mode) {
            $nitropack->setting->set('maintenance_mode', $maintenance_mode);
            $nitropack->setting->save();
        });
    }

    public function updateStoreId() {
        $this->multiStoreWrapper(function($nitropack, $store) {
            $nitropack->setting->set('store_id', (int)$store['store_id']);
            $nitropack->setting->save();
        });
    }

    public function getLastProductId() {
        $sql = "SELECT product_id FROM `" . DB_PREFIX . "product` ORDER BY product_id DESC LIMIT 0,1";

        return (int)$this->db->query($sql)->row['product_id'];
    }

    public function getVariationCookies($store_id) {
        $cookies = array();

        foreach ($this->getLanguages() as $language) {
            $cookies['language'][] = $language['code'];
        }

        foreach ($this->getCurrencies() as $currency) {
            $cookies['currency'][] = $currency['code'];
        }

        $uses_journal = $this->getSettingValue('config_template', $store_id) == 'journal2';

        if ($uses_journal) {
            foreach ($this->getEnabledJournalPopups() as $popup) {
                $cookies['popup-' . $popup['do_not_show_again_cookie']][] = 'true';
            }

            foreach ($this->getEnabledJournalHeaderNotices() as $header_notice) {
                $cookies['header_notice-' . $popup['do_not_show_again_cookie']][] = 'true';
            }
        }

        return $cookies;
    }

    protected function getEnabledJournalPopups() {
        $popups = array();

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE 'journal2_popup.%'");

        foreach ($query->rows as $result) {
            list($module_name, $module_id) = explode('.', $result['code']);
            if (!is_numeric($module_id)) continue;

            if ($this->config->get($module_name . '_' . $result['layout_module_id'] . '_status')) {
                $popups[$module_id] = $this->getJournalModuleData($module_id);
            }
        }

        return $popups;
    }

    protected function getEnabledJournalHeaderNotices() {
        $header_notices = array();

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE 'journal2_header_notice.%'");

        foreach ($query->rows as $result) {
            list($module_name, $module_id) = explode('.', $result['code']);
            if (!is_numeric($module_id)) continue;

            if ($this->config->get($module_name . '_' . $result['layout_module_id'] . '_status')) {
                $header_notices[$module_id] = $this->getJournalModuleData($module_id);
            }
        }

        return $header_notices;
    }

    private function getJournalModuleData($module_id) {
        $module_data_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "journal2_modules` WHERE module_id=$module_id");
        return $module_data_query->num_rows ? json_decode($module_data_query->row['module_data'], true) : NULL;
    }

    protected function getSeoUrls($query) {
        $seo_urls = array();

        foreach (array_keys($this->getStores()) as $store_id) {
            foreach (array_keys($this->getLanguages()) as $language_id) {
                if (version_compare(VERSION, '3', '>=')) {
                    // Use seo_url
                    $result = $this->db->query("SELECT keyword FROM `" . DB_PREFIX . "seo_url` WHERE `query`='" . $this->db->escape($query) . "' AND `store_id`=" . (int)$store_id . " AND `language_id`=" . (int)$language_id . " LIMIT 1");
                } else {
                    // Use url_alias
                    if ($this->urlAliasHasLanguageId()) {
                        $result = $this->db->query("SELECT keyword FROM `" . DB_PREFIX . "url_alias` WHERE `query`='" . $this->db->escape($query) . "' AND `language_id`=" . (int)$language_id . " LIMIT 1");
                    } else {
                        $result = $this->db->query("SELECT keyword FROM `" . DB_PREFIX . "url_alias` WHERE `query`='" . $this->db->escape($query) . "' LIMIT 1");
                    }
                }

                $seo_urls[$store_id][$language_id] = $result->num_rows > 0 ? $result->row["keyword"] : "";
            }
        }

        return $seo_urls;
    }

    private function urlAliasHasLanguageId() {
        return $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "url_alias` LIKE 'language_id'")->num_rows > 0;
    }

    private function backupIndexPhp($index) {
        if ($this->checkDir(dirname($index), true)) {
            $backup = dirname($index) . '/.index.nitropack-backup.php';

            clearstatcache(true);

            if (!is_file($backup)) {
                // Backup the index.php file in case a backup does not already exist
                file_put_contents($backup, file_get_contents($index));
            }

            // We are sure a backup exists, return true
            return true;
        }

        return false;
    }

    public function getCatalogIndexPhp() {
        $file = dirname(DIR_APPLICATION) . '/index.php';

        clearstatcache(true);

        if (is_file($file) && is_writable($file) && is_readable($file)) {
            return $file;
        }

        return null;
    }

    public function getAdminIndexPhp() {
        $file = DIR_APPLICATION . 'index.php';

        clearstatcache(true);

        if (is_file($file) && is_writable($file) && is_readable($file)) {
            return $file;
        }

        return null;
    }

    private function checkDir($dir, $check_writable = false) {
        clearstatcache(true);

        $dir = rtrim($dir, '\\/') . '/';

        return is_dir($dir) && (!$check_writable || is_writable($dir));
    }

    private function findRoutes($root, $base, &$result) {
        $dh = opendir($root);

        while (false !== $item = readdir($dh)) {
            if (in_array($item, array('.', '..'))) {
                continue;
            }

            $candidate = $root . '/' . $item;

            if (is_file($candidate) && preg_match('~(.*?)\.php$~i', $item, $matches)) {
                $route = array_merge($base, array(strtolower($matches[1])));

                $result_item = implode('/', $route);

                if (!$this->isDisallowed($result_item)) {
                    $result[] = implode('/', $route);
                }
            } else if (is_dir($candidate)) {
                $this->findRoutes($candidate, array_merge($base, array(strtolower($item))), $result);
            }
        }

        closedir($dh);
    }

    private function isDisallowed($item) {
        foreach (Nitropack::$disallowed_routes as $disallowed) {
            if (preg_match($disallowed, $item)) {
                return true;
            }
        }

        foreach (Nitropack::$standard_routes as $route) {
            if ($item == $route) {
                return true;
            }
        }

        return false;
    }

    public function hookToCatalogIndexSearchPattern() {
        $search = array();

        $search[] = '~DIR_SYSTEM\s*\.\s*(\'|\")startup.php(\'|\")[^;]+;~mi';

        if (version_compare(VERSION, '2.2', '<')) {
            $search[] = '~if \(isset\(\$request->get\[\'tracking\'\]\)\) {~mi';
            $search[] = '~if \(\$config->get\(\'config_error_display\'\)\) {~mi';
        }

        return $search;
    }

    public function hookToCatalogIndexReplacement() {
        $replace = array();

        $replace[] = '$0' . PHP_EOL . PHP_EOL . $this->indexCodeServeCache();

        if (version_compare(VERSION, '2.2', '<')) {
            $replace[] = $this->indexCodeTracking() . PHP_EOL . '$0';
            $replace[] = '$0' . PHP_EOL . $this->indexErrorHandler();
        }

        return $replace;
    }

    public function hookToAdminIndexSearchPattern() {
        $search = array();

        $search[] = '~DIR_SYSTEM\s*\.\s*(\'|\")startup.php(\'|\")[^;]+;~mi';

        return $search;
    }

    public function hookToAdminIndexReplacement() {
        $replace = array();

        $replace[] = '$0' . PHP_EOL . PHP_EOL . $this->indexCodeAdmin();

        return $replace;
    }

    private function indexCodeAdmin() {
        return <<<EOF
// BEGIN NitroPack
if (is_file(DIR_SYSTEM . 'library/nitropackio/hook/index_admin.php')) {
    require_once(DIR_SYSTEM . 'library/nitropackio/hook/index_admin.php');
}
// END NitroPack
EOF;
    }

    private function indexCodeServeCache() {
        return <<<EOF
// BEGIN NitroPack
if (is_file(DIR_SYSTEM . 'library/nitropackio/hook/index_serve_cache.php')) {
    require_once(DIR_SYSTEM . 'library/nitropackio/hook/index_serve_cache.php');
}
// END NitroPack
EOF;
    }

    private function indexCodeTracking() {
        return <<<EOF
// BEGIN NitroPack
if (!isset(\$_SERVER['HTTP_X_NITROPACK_REQUEST']))
// END NitroPack
EOF;
    }

    private function indexErrorHandler() {
        return <<<EOF
// BEGIN NitroPack
if (is_file(DIR_SYSTEM . 'library/nitropackio/hook/error_handler.php')) {
    require_once(DIR_SYSTEM . 'library/nitropackio/hook/error_handler.php');
}
// END NitroPack
EOF;
    }
}
