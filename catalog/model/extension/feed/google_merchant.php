<?php
class ModelExtensionFeedGoogleMerchant extends Model {
    private $categories = array();
    private $category_map = array(
        139 => 6093,//911
        156 => 6090 //2932
    );
    private $delivery_categories = array();
    private $delivery_by_category = array(
        366 => 'heavy'
    );

    public function getProducts($where = "") {
        $this->getCategoryIDs();

        $sql = "SELECT p.product_id";
        $sql .= " FROM " . DB_PREFIX . "product p";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        $sql .= " " . $where;
        $sql .= " GROUP BY p.product_id";
        $sql .= " ORDER BY NULL";

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[] = $this->getProduct($result['product_id']);
        }

        return $product_data;
    }


    private function getCategoryIDs() {
        $sql =  "SELECT category_id, path_id FROM " . DB_PREFIX . "category_path where path_id in (139, 156)";
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $this->categories[$result['category_id']] = $result['path_id'];
        }

        $sql =  "SELECT category_id, path_id FROM " . DB_PREFIX . "category_path where path_id = 366";
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $this->delivery_categories[$result['category_id']] = $result['path_id'];
        }
    }


    public function getProduct($product_id) {
        $this->load->model('tool/image');
        $query = $this->db->query("SELECT DISTINCT *, pd.name AS name,p.sku, p.ean, p.mpn, p.upc, p.isbn, p.stock_status_id, p.jan, p.image, p.tax_class_id, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        if ($query->num_rows) {
            $category_id = '';
            $delivery = '';
            $sqlcat =  $this->db->query("SELECT parent_id, pc.category_id, pc2.name FROM " . DB_PREFIX . "product_to_category pc LEFT JOIN " . DB_PREFIX . "category_description pc2 ON (pc.category_id = pc2.category_id) LEFT JOIN " . DB_PREFIX . "category pcd ON (pc.category_id = pcd.category_id) WHERE pc.product_id = '".$query->row['product_id']."'");

            if ($sqlcat->num_rows) {
                $catresult =$sqlcat->rows;
                foreach($catresult as $catresult){
                    if (array_key_exists($catresult['category_id'], $this->categories)){
                        $category_id = $this->category_map[$this->categories[$catresult['category_id']]];
                    }
                    if (array_key_exists($catresult['category_id'], $this->delivery_categories)){
                        $delivery = $this->delivery_by_category[$this->delivery_categories[$catresult['category_id']]];
                    }
                }
            }
            if ($query->row['image']) {
                    $image = $this->model_tool_image->resize($query->row['image'], 800, 800);
                } else {
                    $image = '';
                }

            //if($query->row['weight_class_id'] == 1){
                //$unit = 'Kg';
            //}
            //if($query->row['weight_class_id'] == 2){
                //$unit = 'gram';
            //}
            //if($query->row['weight_class_id'] == 5){
                //$unit = 'Pound';
            //}
            //if($query->row['weight_class_id'] == 6){
                //$unit = 'Ounce';
            //}
            //if($query->row['length_class_id'] == 1){
                //$unitlength = 'cm';
            //}
            //if($query->row['length_class_id'] == 2){
                //$unitlength = 'mm';
            //}
            //if($query->row['length_class_id'] == 3){
                //$unitlength = 'inch';
            //}
                $decriptionhtmlentity_decode =html_entity_decode($query->row['description']);
                $decriptionremove_htmltag = strip_tags($decriptionhtmlentity_decode);
                $decription_removeline = trim(preg_replace('/\s+/', ' ', $decriptionremove_htmltag));
                $decription_removequot = str_replace('&quot;', '',$decription_removeline);
                $finaldecription = str_replace('"', '',$decription_removequot);
                $title_removequot = str_replace('&quot;', '',$query->row['name']);
                $title = str_replace('"', '',$title_removequot);
            // Get Additional Images
            $data['images'] = array();
            //$queryimage = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$query->row['product_id'] . "' ORDER BY sort_order ASC");
            //$count=1;
            //foreach ($queryimage->rows as $resultimage) {

                //if($resultimage['image']){
                //$data['images'][] = array(
                    //'image'.$count => $this->model_tool_image->resize($resultimage['image'], 800, 800),

                //);
                //$count++;
                //}
            //}

            // For geting option data

            $data['options'] = array();

            //foreach ($this->getProductOptions($query->row['product_id']) as $option) {
                //$product_option_value_data = array();

                //foreach ($option['product_option_value'] as $option_value) {
                    //if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        //if ((float)$option_value['price']) {
                            //$price = $option_value['price'];
                        //} else {
                            //$price = false;
                        //}

                        //$product_option_value_data[] = array(
                            //'option_value_id'         => $option_value['option_value_id'],
                            //'name'                    => $option_value['name'],
                            //'quantity'                => $option_value['quantity'],
                            //'price'                   => $price,
                            //'price_prefix'            => $option_value['price_prefix'],
                            //'weight'                  => $option_value['weight'],
                            //'weight_prefix'           => $option_value['weight_prefix']
                        //);
                    //}
                //}

                //$data['options'][] = array(
                    //'name'                 => $option['name'],
                    //'type'                 => $option['type'],
                    //'required'             => $option['required'],
                    //'product_option_value' => $product_option_value_data,
                //);
            //}

            // Stock status
            $stockstaus_name ='';
            $stockstaus =  $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '".$query->row['stock_status_id']."'");
            if ($stockstaus->num_rows) {
                $stockstaus_name = $stockstaus->row['name'];
            }

            // Special price
            $special = '';
            $start_date = '';
            $end_date = '';
            $specialprice = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = '".$query->row['product_id']."' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1");
            if ($specialprice->num_rows) {
                $special = $specialprice->row['price'];
                $start_date = $specialprice->row['date_start'];
                $end_date = $specialprice->row['date_end'];
            }

            return array(
                'product_id'       =>  $query->row['product_id'],
                'description'      =>  $finaldecription,
                'image'            =>  $image,
                'additional_images' => $data['images'],
                'link'             =>  $this->url->link('product/product', 'product_id=' . $query->row['product_id']),
                'name'             =>  $title,
                'sku'              =>  $query->row['sku'],
                'ean'              =>  $query->row['ean'],
                'upc'              =>  $query->row['upc'],
                'jan'              =>  $query->row['jan'],
                'isbn'             =>  $query->row['isbn'],
                'mpn'              =>  $query->row['mpn'],
                'manufacturer'     =>  $query->row['manufacturer'],
                'tag'              =>  $query->row['tag'],
                'variants'         =>  $data['options'],
                //'category_name'    =>  $category_name,
                'category_id'      =>  $category_id,
                'delivery'         =>  $delivery,
                'quantity'         =>  $query->row['quantity'],
                'status'           =>  $stockstaus_name,
                'price'            =>  ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
                'special'          =>  $special,
                'start_date'       =>  $start_date,
                'end_date'         =>  $end_date,
                'weight'           =>  $query->row['weight'],
                //'unit_weight'      =>  $unit,
                //'length'           =>  $query->row['length'],
                //'width'            =>  $query->row['width'],
                //'height'           =>  $query->row['height'],
                //'unit_dimension'   =>  $unitlength,
                'tax_class_id'     =>  $query->row['tax_class_id'],
                'currency'         =>  $this->config->get('config_currency')

            );
        } else {
            return false;
        }
    }

    //For Getting Option
    public function getProductOptions($product_id) {
        $product_option_data = array();

        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
            $product_option_value_data = array();

            $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

            foreach ($product_option_value_query->rows as $product_option_value) {
                $product_option_value_data[] = array(
                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                    'option_value_id'         => $product_option_value['option_value_id'],
                    'name'                    => $product_option_value['name'],
                    'image'                   => $product_option_value['image'],
                    'quantity'                => $product_option_value['quantity'],
                    'subtract'                => $product_option_value['subtract'],
                    'price'                   => $product_option_value['price'],
                    'price_prefix'            => $product_option_value['price_prefix'],
                    'weight'                  => $product_option_value['weight'],
                    'weight_prefix'           => $product_option_value['weight_prefix']
                );
            }

            $product_option_data[] = array(
                'product_option_id'    => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id'            => $product_option['option_id'],
                'name'                 => $product_option['name'],
                'type'                 => $product_option['type'],
                'value'                => $product_option['value'],
                'required'             => $product_option['required']
            );
        }

        return $product_option_data;
    }

}
