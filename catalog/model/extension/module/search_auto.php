<?php
class ModelExtensionModuleSearchAuto extends Model{

    //private static $settings;

    //public function __construct($registry)
    //{
        //parent::__construct($registry);
        //self::$settings = $this->config->get("search_auto_module_setting");
        //print_r(self::$settings);
        //print 'xxxxxxxxxxxxxx';

    //}

    private static function sortOne($a, $b)
    {
        if( $a == $b )
        {
            return 0;
        }

        return ((double) $a < (double) $b ? -1 : 1);
    }

    private static function sortPcd($a, $b)
    {
        $item1 = explode("x", $a);
        $item2 = explode("x", $b);
        if( $item1[0] != $item2[0] )
        {
            return ((double) $item1[0] < (double) $item2[0] ? -1 : 1);
        }

        if( $item1[1] == $item2[1] )
        {
            return 0;
        }

        return ((double) $item1[1] < (double) $item2[1] ? -1 : 1);
    }

    public function getManufacturers($category_id)
    {
        $manufacturer_data = $this->cache->get("auto_manufacturer." . (int) $category_id . "." . (int) $this->config->get("config_store_id"));
        if( !$manufacturer_data )
        {
            $sql = "SELECT DISTINCT m.name FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id=p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id=m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE cp.path_id='" . (int) $category_id . "' AND p.status=1 AND m.name <> '' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY m.name";
            $query = $this->db->query($sql);
            $manufacturer_data = $query->rows;
            $this->cache->set("auto_manufacturer." . (int) $category_id . "." . (int) $this->config->get("config_store_id"), $manufacturer_data);
        }

        return $manufacturer_data;
    }

    public function getAttributes(){
        $cache_key = "auto_attributes." . (int) $this->config->get("config_language_id") . "." . (int) $this->config->get("config_store_id");
        $attributes = $this->cache->get($cache_key);
        if( !$attributes ){
            $settings = array();
            $settings_tire = array();
            $settings_disc = array();
            $tire_params = array('width', 'height', 'diameter', 'season', 'type', 'loadindex', 'speedindex');
            $disc_params = array('width', 'diameter', 'dia', 'pcd', 'et');

            foreach ($tire_params as $param){
                $settings['tire_'.$param] = $this->config->get('module_search_auto_tire_'.$param);
                $settings_tire[] =  $this->config->get('module_search_auto_tire_'.$param);
            }
            foreach ($disc_params as $param){
                $settings['disc_'.$param] = $this->config->get('module_search_auto_disc_'.$param);
                $settings_disc[] =  $this->config->get('module_search_auto_disc_'.$param);
            }

            $tire_category = $this->config->get('module_search_auto_tire_category');
            $disc_category = $this->config->get('module_search_auto_disc_category');

            $sql = "SELECT pa.attribute_id, pa.text FROM " . DB_PREFIX . "category_path cp
                LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id=p2c.category_id)
                LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id=p.product_id)
                LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id=pa.product_id)
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
                WHERE pa.language_id='" . (int) $this->config->get("config_language_id") . "' AND p.status='1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
                AND ((pa.attribute_id IN (" . implode(",", $settings_tire) . ") AND cp.path_id='" . (int) $tire_category . "') OR (pa.attribute_id IN (" . implode(",", $settings_disc) . ") AND cp.path_id='" . (int) $disc_category . "'))
                GROUP BY pa.attribute_id, pa.text ORDER BY NULL";
            $query = $this->db->query($sql);
            $rows = $query->rows;
            unset($query);
            $attributes = array();
            $i = 0;
            //var_dump($rows);
            for( $count = sizeOf($rows); $i < $count; $i++ )
            {
                $str = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $rows[$i]["text"]);
                if ($settings["tire_season"] ==  $rows[$i]["attribute_id"]){
                    $str = mb_strtolower($str);
                }
                if ($settings["tire_type"] ==  $rows[$i]["attribute_id"]){
                    $str = mb_strtolower($str);
                }
                if ($str != ""){
                    $attributes[$rows[$i]["attribute_id"]][] = $str;
                }
            }
            foreach( $attributes as $key => $group_attr )
            {
                if( $key == $settings["disc_pcd"] )
                {
                    uasort($attributes[$key], array( $this, "self::sortPcd" ));
                }
                elseif( $key == $settings["tire_speedindex"] )
                {
                    uasort($attributes[$key], "strcmp");
                }
                else
                {
                    uasort($attributes[$key], array( $this, "self::sortOne" ));
                }

            }
            $this->cache->set($cache_key, $attributes);
        }

        return $attributes;
    }

    //public function getAttributesSEO()
    //{
        //$attributes = $this->cache->get("auto_attributes_seo" . "." . (int) $this->config->get("config_language_id") . "." . (int) $this->config->get("config_store_id"));
        //if( !$attributes )
        //{
            //$attributes = array();
            //$attr = $this->getAttributes();
            //foreach( $attr[self::$settings["tire"]["season"]] as $season )
            //{
                //$attributes["tire"]["season"][preg_replace(array( "#[^a-z0-9-\\.]+#", "#-{2,}#", "#^-+|-+\$#" ), array( "-", "-", "" ), $this->translit(html_entity_decode($season), true))] = $season;
            //}
            //$tire_manufacturers = $this->getManufacturers(self::$settings["tire"]["category"]);
            //foreach( $tire_manufacturers as $manufacturer )
            //{
                //$attributes["tire"]["manufacturer"][preg_replace(array( "#[^a-z0-9-\\.]+#", "#-{2,}#", "#^-+|-+\$#" ), array( "-", "-", "" ), $this->translit(html_entity_decode($manufacturer["name"]), true))] = $manufacturer["name"];
            //}
            //$disc_manufacturers = $this->getManufacturers(self::$settings["disc"]["category"]);
            //foreach( $disc_manufacturers as $manufacturer )
            //{
                //$attributes["disc"]["manufacturer"][preg_replace(array( "#[^a-z0-9-\\.]+#", "#-{2,}#", "#^-+|-+\$#" ), array( "-", "-", "" ), $this->translit(html_entity_decode($manufacturer["name"]), true))] = $manufacturer["name"];
            //}
            //$this->cache->set("auto_attributes_seo" . "." . (int) $this->config->get("config_language_id") . "." . (int) $this->config->get("config_store_id"), $attributes);
        //}

        //return $attributes;
    //}

    public function querySEO($table, $value)
    {
        $query = $this->db->query("SELECT name FROM cars_" . $this->db->escape($table) . " WHERE seo='" . $this->db->escape($value) . "' LIMIT 1");
        if( $query->num_rows )
        {
            return $query->row["name"];
        }

        return $value;
    }

    public function getAuto($data = array())
    {
        $sql = "SELECT * FROM cars WHERE vendor='" . $this->db->escape($data["vendor"]) . "' AND model='" . $this->db->escape($data["model"]) . "' AND year='" . (int) $data["year"] . "' AND modification='" . $this->db->escape($data["mod"]) . "' LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getVendors()
    {
        $vendor_data = $this->cache->get("search_auto.vendors");
        if( !$vendor_data )
        {
            $sql = "SELECT DISTINCT vendor FROM cars ORDER BY vendor";
            $query = $this->db->query($sql);
            $vendor_data = $query->rows;
            $this->cache->set("search_auto.vendors", $vendor_data);
        }

        return $vendor_data;
    }

    private function getModelsQuery($vendor)
    {
        $sql = "SELECT DISTINCT model FROM cars WHERE vendor='" . $this->db->escape($vendor) . "' ORDER BY model";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getModels($vendor)
    {
        if( $this->config->get('module_search_auto_cache_models') )
        {
            $models = $this->cache->get("search_auto." . md5($vendor) . ".models");
            if( !$models )
            {
                $models = $this->getModelsQuery($vendor);
                $this->cache->set("search_auto." . md5($vendor) . ".models", $models);
            }

            return $models;
        }

        return $this->getModelsQuery($vendor);
    }

    private function getYearsQuery($vendor, $model)
    {
        $sql = "SELECT DISTINCT year FROM cars WHERE vendor='" . $this->db->escape($vendor) . "' AND model='" . $this->db->escape($model) . "' ORDER BY year DESC";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getYears($vendor, $model)
    {
        if( $this->config->get('module_search_auto_cache_years') )
        {
            $years = $this->cache->get("search_auto." . md5($vendor . $model) . ".years");
            if( !$years )
            {
                $years = $this->getYearsQuery($vendor, $model);
                $this->cache->set("search_auto." . md5($vendor . $model) . ".years", $years);
            }

            return $years;
        }

        return $this->getYearsQuery($vendor, $model);
    }

    private function getModsQuery($vendor, $model, $year)
    {
        $sql = "SELECT DISTINCT modification FROM cars WHERE vendor='" . $this->db->escape($vendor) . "' AND model='" . $this->db->escape($model) . "' AND year='" . (int) $year . "' ORDER BY modification";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getMods($vendor, $model, $year)
    {
        if( $this->config->get('module_search_auto_cache_mods') )
        {
            $mods = $this->cache->get("search_auto." . md5($vendor . $model) . "." . (int) $year . ".mods");
            if( !$mods )
            {
                $mods = $this->getModsQuery($vendor, $model, $year);
                $this->cache->set("search_auto." . md5($vendor . $model) . "." . (int) $year . ".mods", $mods);
            }

            return $mods;
        }

        return $this->getModsQuery($vendor, $model, $year);
    }

    public function getProducts($data)
    {
        $NOW = date("Y-m-d H:i") . ":00";
        $category = $this->config->get('module_search_auto_' . $data['type'] .'_category');
        foreach (array('et_range', 'width_range_minus', 'width_range_plus', 'dia_range') as $param){
            $settings[$param] = $this->config->get('module_search_auto_'.$param);
        }
        $settings['loadindex'] = 10;
        $settings['speedindex'] = (array('L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'H', 'V', 'W', 'Y', 'Z'));
        if( $this->customer->isLogged() )
        {
            //fix it!
            //$customer_group_id = $this->customer->getCustomerGroupId();
            $customer_group_id = $this->config->get("config_customer_group_id");
        }
        else
        {
            $customer_group_id = $this->config->get("config_customer_group_id");
        }

        $sql = "SELECT p.product_id, pd.name AS name,  p.model, p.image, p.location,p.quantity, p.price, p.tax_class_id, pd.description AS description, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id=p.product_id AND ps.customer_group_id='" . (int) $customer_group_id . "' AND ((ps.date_start='0000-00-00' OR ps.date_start < '" . $NOW . "') AND (ps.date_end='0000-00-00' OR ps.date_end > '" . $NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id=p.product_id AND pd2.customer_group_id='" . (int) $customer_group_id . "' AND pd2.quantity='1' AND ((pd2.date_start='0000-00-00' OR pd2.date_start < '" . $NOW . "') AND (pd2.date_end='0000-00-00' OR pd2.date_end > '" . $NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id=p.product_id AND r1.status='1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id=p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id=p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id=pd.product_id)";
        foreach( $data["attr"] as $name => $value )
        {
            $config_value = $this->config->get('module_search_auto_' . $data['type'] .'_' . $name);
            if( isset($value) && isset($config_value) )
            {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa" . (int) $config_value . " ON (p.product_id=pa" . (int) $config_value . ".product_id AND pa" . (int) $config_value . ".attribute_id='" . (int) $config_value . "' AND pa" . (int) $config_value . ".language_id='" . (int) $this->config->get("config_language_id") . "')";
            }

        }
        if( isset($data["manufacturer"]) )
        {
            $sql .= " LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id=m.manufacturer_id)";
        }
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

        $sql .= " WHERE cp.path_id='" . (int) $category . "' AND pd.language_id='" . (int) $this->config->get("config_language_id") . "' AND p.status=1";
        if( isset($data["manufacturer"]) )
        {
            $sql .= " AND m.name='" . $this->db->escape($data["manufacturer"]) . "'";
        }

        foreach( $data["attr"] as $name => $value )
        {
            $config_value = $this->config->get('module_search_auto_' . $data['type'] .'_' . $name);
            if( isset($value) && isset($config_value) )
            {
                if( $name === "dia" && $settings["dia_range"] )
                {
                    $sql .= " AND pa" . (int) $config_value . ".value >=" . (double) $value;
                }
                elseif ($name === "speedindex" && ctype_alpha($value)){
                    $tte = count($settings['speedindex']);
                    for ($tt = 0; $tt <= $tte; $tt++) {
                        if ($settings['speedindex'][$tt] === substr($value, 0, 1)){
                            break;
                        }
                        else {
                            unset($settings['speedindex'][$tt]);
                        }
                    }
                    $sql .= " AND (pa" . (int)$config_value . ".value REGEXP '[" . implode("", $settings['speedindex']) . "]')";
                }
                elseif ($name === "loadindex" && $settings['loadindex']) {
                        $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . ((double)$value) . " AND " . ((double)$value + (double)$settings['loadindex']) . ")";
                    } else {
                        if ($name === "width" && $data["type"] === "disc") {
                            $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . ((double)$value - (double)$settings["width_range_minus"]) . " AND " . ((double)$value + (double)$settings["width_range_plus"]) . ")";
                        } else {
                            if ($name === "et" && is_array($value)) {
                                if (!is_null($value["et2"])) {
                                    $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . (double)$value["et"] . " AND " . (double)$value["et2"] . ")";
                                } else {
                                    if (!is_null($value["et"])) {
                                        $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . ((double)$value["et"] - (double)$settings["et_range"]) . " AND " . (double)$value["et"] . ")";
                                    }

                                }

                            } else {

                                $ET_deviation_db = $this->db->query("SELECT * FROM `oc_setting` WHERE (CONVERT(`key` USING utf8) LIKE 'module_search_auto_et_range')");
                                $ET_deviation = $this->db->escape($value) - $ET_deviation_db->rows[0]["value"];

                                if ($config_value == 5) {
                                    $sql .= " AND pa" . (int)$config_value . ".value <= '" . $this->db->escape($value) . "' and pa" . (int)$config_value . ".value > '" . $ET_deviation . "'";
                                } else {
                                    $sql .= " AND pa" . (int)$config_value . ".value='" . $this->db->escape($value) . "'";
                                }

                            }

                        }


                }
            }

        }

        $sql .=" AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        $dataall["totalproduct"] = $this->db->query($sql)->num_rows;

        $sql .= " GROUP BY p.product_id";

        $sort_data = array( "pd.name", "p.model", "p.quantity", "p.price", "rating",  "p.location DESC, p.sort_order", "p.date_added" );

        if( isset($data["sort"]) && in_array($data["sort"], $sort_data) )
        {
            if( $data["sort"] == "pd.name" || $data["sort"] == "p.model" )
            {
                $sql .= " ORDER BY LCASE(" . $data["sort"] . ")";
            }
            else
            {
                if( $data["sort"] == "p.price" )
                {
                    $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
                }
                else
                {
                    $sql .= " ORDER BY " . $data["sort"];
                }

            }

        }
        else
        {
            $sql .= " ORDER BY p.location DESC, p.quantity DESC, p.sort_order";
        }

        if( isset($data["order"]) && $data["order"] == "DESC" )
        {
            $sql .= " DESC, LCASE(pd.name) DESC";
        }
        else
        {
            $sql .= " ASC, LCASE(pd.name) ASC";
        }

        if( isset($data["start"]) || isset($data["limit"]) )
        {
            if( $data["start"] < 0 )
            {
                $data["start"] = 0;
            }

            if( $data["limit"] < 1 )
            {
                $data["limit"] = 20;
            }

            $sql .= " LIMIT " . (int) $data["start"] . "," . (int) $data["limit"];
        }
        //echo $sql;
        $query = $this->db->query($sql);
        $dataall["query"] = $query->rows;
        return $dataall;
    }

/*
    public function getTotalProducts($data)
    {
        $NOW = date("Y-m-d H:i") . ":00";
        $category = $this->config->get('module_search_auto_' . $data['type'] .'_category');
        foreach (array('et_range', 'width_range_minus', 'width_range_plus', 'dia_range') as $param){
            $settings[$param] = $this->config->get('module_search_auto_'.$param);
        }
        $settings['loadindex'] = 10;
        $settings['speedindex'] = (array('L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'H', 'V', 'W', 'Y', 'Z'));

        if( $this->customer->isLogged() )
        {
            //fix it!
            //$customer_group_id = $this->customer->getCustomerGroupId();
            $customer_group_id = $this->config->get("config_customer_group_id");
        }
        else
        {
            $customer_group_id = $this->config->get("config_customer_group_id");
        }

        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "category_path cp
            LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id=p2c.category_id)
            LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id=p.product_id)";
        foreach( $data["attr"] as $name => $value )
        {
            $config_value = $this->config->get('module_search_auto_' . $data['type'] .'_' . $name);
            if( isset($value) && isset($config_value) )
            {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa" . (int) $config_value . " ON (p.product_id=pa" . (int) $config_value . ".product_id AND pa" .
                    (int) $config_value . ".attribute_id='" . (int) $config_value . "')";
            }

        }
        if( isset($data["manufacturer"]) )
        {
            $sql .= " LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id=m.manufacturer_id)";
        }

        $sql .= " WHERE cp.path_id='" . (int) $category . "' AND p.status=1";
        if( isset($data["manufacturer"]) )
        {
            $sql .= " AND m.name='" . $this->db->escape($data["manufacturer"]) . "'";
        }

        foreach( $data["attr"] as $name => $value )
        {
            $config_value = $this->config->get('module_search_auto_' . $data['type'] .'_' . $name);
            //var_dump($value);
            if( isset($value) && isset($config_value) )
            {
                if( $name === "dia" && $settings["dia_range"] )
                {
                    $sql .= " AND pa" . (int) $config_value . ".value >=" . (double) $value;
                }
                elseif ($name === "speedindex" && ctype_alpha($value)){
                    $tte = count($settings['speedindex']);
                    for ($tt = 0; $tt <= $tte; $tt++) {
                        if ($settings['speedindex'][$tt] === substr($value, 0, 1)){
                            break;
                        }
                        else {
                            unset($settings['speedindex'][$tt]);
                        }
                    }
                    $sql .= " AND (pa" . (int)$config_value . ".value REGEXP '[" . implode("", $settings['speedindex']) . "]')";
                }
                elseif ($name === "loadindex" && $settings['loadindex']) {
                        $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . ((double)$value) . " AND " . ((double)$value + (double)$settings['loadindex']) . ")";

                    } else {

                        if ($name === "width" && $data["type"] === "disc") {
                            $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . ((double)$value - (double)$settings["width_range_minus"]) . " AND " . ((double)$value + (double)$settings["width_range_plus"]) . ")";
                        } else {
                            if ($name === "et" && is_array($value)) {

                                if (!is_null($value["et2"])) {
                                    $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . (double)$value["et"] . " AND " . (double)$value["et2"] . ")";
                                } else {
                                    if (!is_null($value["et"])) {
                                        $sql .= " AND (pa" . (int)$config_value . ".value BETWEEN " . ((double)$value["et"] - (double)$settings["et_range"]) . " AND " . (double)$value["et"] . ")";
                                    }

                                }

                            } else {
                                $ET_deviation_db = $this->db->query("SELECT * FROM `oc_setting` WHERE (CONVERT(`key` USING utf8) LIKE 'module_search_auto_et_range')");
                                $ET_deviation = $this->db->escape($value) - $ET_deviation_db->rows[0]["value"];

                                if ($config_value == 5) {
                                    $sql .= " AND pa" . (int)$config_value . ".value <= '" . $this->db->escape($value) . "' and pa" . (int)$config_value . ".value > '" . $ET_deviation . "'";
                                } else {
                                    $sql .= " AND pa" . (int)$config_value . ".value='" . $this->db->escape($value) . "'";
                                }

                            }

                        }

                    }


            }

        }



        $query = $this->db->query($sql);
//echo $sql;
        return $query->row["total"];
    }
*/


    public function getFilteredAttributes($data)
    {
        $params =[
            'tire'=> array('width', 'height', 'diameter', 'season', 'type', 'loadindex', 'speedindex', 'model'),
            'disc' => array('width', 'diameter', 'dia', 'pcd', 'et')
        ];

        $mask = [];

        foreach($params[$data['type']] as $name){
            $mask[ $this->config->get('module_search_auto_' . $data['type'] .'_' . $name)] = $name;
        }

        $category = $this->config->get('module_search_auto_' . $data['type'] .'_category');
        foreach (array('et_range', 'width_range_minus', 'width_range_plus', 'dia_range') as $param){
            $settings[$param] = $this->config->get('module_search_auto_'.$param);
        }

        if( $this->customer->isLogged() )
        {
            $customer_group_id = $this->config->get("config_customer_group_id");
        }
        else
        {
            $customer_group_id = $this->config->get("config_customer_group_id");
        }

        $sql = "SELECT DISTINCT p.product_id FROM " . DB_PREFIX . "category_path cp
            LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id=p2c.category_id)
            LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id=p.product_id)";

        foreach( $data["attr"] as $name => $value )
        {
            $config_value = $this->config->get('module_search_auto_' . $data['type'] .'_' . $name);

            $mask[$config_value] = $name;
            if( isset($value) && isset($config_value) )
            {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa" . (int) $config_value . " ON (p.product_id=pa" . (int) $config_value . ".product_id AND pa" .
                    (int) $config_value . ".attribute_id='" . (int) $config_value . "')";
            }

        }

        if( isset($data["manufacturer"]) )
        {
            $sql .= " LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id=m.manufacturer_id)";
        }

        $sql .= " WHERE cp.path_id='" . (int) $category . "' AND p.status=1";
        if( isset($data["manufacturer"]) )
        {
            $sql .= " AND m.name='" . $this->db->escape($data["manufacturer"]) . "'";
        }

        foreach( $data["attr"] as $name => $value )
        {
            $config_value = $this->config->get('module_search_auto_' . $data['type'] .'_' . $name);
            if( isset($value) && isset($config_value) )
            {
                if( $name === "dia" && $settings["dia_range"] )
                {
                    $sql .= " AND pa" . (int) $config_value . ".value >=" . (double) $value;
                }
                else
                {
                    if( $name === "width" && $data["type"] === "disc" )
                    {
                        $sql .= " AND (pa" . (int) $config_value . ".value BETWEEN " . ((double) $value - (double) $settings["width_range_minus"]) . " AND " . ((double) $value + (double) $settings["width_range_plus"]) . ")";
                    }
                    else
                    {
                        if( $name === "et" && is_array($value) )
                        {
                            if( !is_null($value["et2"]) )
                            {
                                $sql .= " AND (pa" . (int) $config_value . ".value BETWEEN " . (double) $value["et"] . " AND " . (double) $value["et2"] . ")";
                            }
                            else
                            {
                                if( !is_null($value["et"]) )
                                {
                                    $sql .= " AND (pa" . (int) $config_value . ".value BETWEEN " . ((double) $value["et"] - (double) $settings["et_range"]) . " AND " . (double) $value["et"] . ")";
                                }

                            }

                        }
                        else
                        {
                            $ET_deviation_db = $this->db->query("SELECT * FROM `oc_setting` WHERE (CONVERT(`key` USING utf8) LIKE 'module_search_auto_et_range')");
                            $ET_deviation = $this->db->escape($value) - $ET_deviation_db->rows[0]["value"];

                            if ($config_value == 5) {
                                $sql .= " AND pa" . (int)$config_value . ".value <= '" . $this->db->escape($value) . "' and pa" . (int)$config_value . ".value > '" . $ET_deviation . "'";
                            }
                            else {
                                $sql .= " AND pa" . (int)$config_value . ".value='" . $this->db->escape($value) . "'";
                            }
                        }

                    }

                }

            }

        }

        $result= [];

        //$sql_params = 'select `main_val`.`value`, `main_val`.`attribute_id`  from '. DB_PREFIX  . 'product_attribute main_val WHERE product_id in ('.$sql.') and language_id = 3 GROUP BY `main_val`.`value`, `main_val`.`attribute_id`';
        $sql_params = 'select DISTINCT `main_val`.`value`, (SELECT DISTINCT  `main_val`.`attribute_id`) AS attribute_id from '. DB_PREFIX  . 'product_attribute main_val WHERE product_id in ('.$sql.') and language_id = 3';

        $query = $this->db->query($sql_params);
        foreach ($query->rows as $row){

            if(isset($mask[$row['attribute_id']]) && $row['value'])
            $result[$mask[$row['attribute_id']]][] = $row['value'];
        }
        $sql_manufacturers = 'select m.`name` FROM oc_product p LEFT JOIN oc_manufacturer m ON p.manufacturer_id = m.manufacturer_id WHERE p.product_id in ('. $sql .') GROUP BY m.`name`';

        $query = $this->db->query($sql_manufacturers);
        foreach ($query->rows as $row){
                if( $row['name']){
                    $result['manufacturer'][] = $row['name'];
                }

        }
        foreach ($result as $key => $value){
            sort($result[$key]);
        }
        //$result['sql_params'] = $sql_params;
        //$result['sql_manufacturers'] = $sql_manufacturers;
        $result['params'] = $params[$data['type']];
        $result['mask'] = $mask;
        $result['config_value'] = $config_value;
        return $result;
    }

    public function translit($mixed, $lower = false)
    {
        $dictionary = array( "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "zh", "з" => "z", "и" => "i", "й" => "j", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shch", "ъ" => "", "ы" => "y", "ь" => "", "э" => "eh", "ю" => "yu", "я" => "ya" );
        if( is_array($mixed) )
        {
            foreach( $mixed as $key => $value )
            {
                if( $lower )
                {
                    $mixed[$key] = strtr(mb_strtolower($value, "utf-8"), $dictionary);
                }
                else
                {
                    $mixed[$key] = strtr($value, $dictionary);
                }

            }
            return $mixed;
        }
        else
        {
            if( $lower )
            {
                return strtr(mb_strtolower($mixed, "utf-8"), $dictionary);
            }

            return strtr($mixed, $dictionary);
        }

    }

}
?>

