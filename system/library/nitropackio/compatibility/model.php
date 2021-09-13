<?php

namespace nitropackio\compatibility;

use \Model as OcModel;
use nitropackio\compatibility\traits\LanguageLoader;
use nitropackio\compatibility\traits\CurrencyLoader;
use nitropackio\compatibility\traits\MultiStoreWrapper;
use nitropackio\compatibility\traits\NitropackLoader;
use nitropackio\compatibility\traits\RouteLoader;
use nitropackio\compatibility\traits\StoreLoader;

class Model extends OcModel {
    use LanguageLoader;
    use CurrencyLoader;
    use MultiStoreWrapper;
    use NitropackLoader;
    use RouteLoader;
    use StoreLoader;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->nitropackAutoloader();

        $this->initNitroPackConfig();
    }

    public function getSettingValue($key, $store_id = 0) {
        if (version_compare(VERSION, '2.2', '>=')) {
            $this->load->model('setting/setting');

            return $this->model_setting_setting->getSettingValue($key, $store_id);
        } else {
            $query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "'");

            if ($query->num_rows) {
                return $query->row['value'];
            } else {
                return null;    
            }
        }
    }
}
