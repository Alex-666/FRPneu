<?php

class ControllerExtensionFeedCronmarketplace extends Controller
{

    private $categories = array();
    private $top;

/*    public function getParentId($category_id)
    {
        $query = $this->db->query("SELECT `parent_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '$category_id'");
        if ($query->num_rows > 0) {
            return $query->row['parent_id'];
        }
        return 0;
    }
*/
    public function index()
    {
        $start = microtime(true);

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        //$this->build_cat();

        //Чешская
        $output = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $output .= '<ITEMS>' . "\n";
        $output2 = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $output2 .= '<AVAILABILITIES>' . "\n";

        //Венгерская
        $output_vengr_end = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $output_vengr_end .= '<ITEMS>' . "\n";
        $output_vengr_end2 = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $output_vengr_end2 .= '<AVAILABILITIES>' . "\n";

        //Словацкая
        $output_slov_end = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $output_slov_end .= '<ITEMS>' . "\n";
        $output_slov_end2 = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
        $output_slov_end2 .= '<AVAILABILITIES>' . "\n";


        $query = $this->db->query("SELECT DISTINCT `product_id` FROM `" . DB_PREFIX . "product` WHERE location = 'pohoda' AND LENGTH(TRIM(model)) > 0 AND LENGTH(image) > 0 AND status = 1");
        //$query = $this->db->query("SELECT DISTINCT `product_id` FROM `" . DB_PREFIX . "product` WHERE location = 'pohoda' AND  LENGTH(TRIM(`model`)) > 0 AND LENGTH(`image`) > 0");

        //$products = $this->model_catalog_product->getProducts((array_column($query->rows, "product_id")));

        foreach ($query->rows as $product_id) {
            unset($output_vengr);
            unset($output_vengr2);
            unset($output_slov);
            unset($output_slov2);
            //$query_p = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
            //
            $query_p = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = p.product_id AND language_id = 6) AS name_vengr, (SELECT name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = p.product_id AND language_id = 5) AS name_slov, (SELECT description FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = p.product_id AND language_id = 6) AS description_vengr, (SELECT description FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = p.product_id AND language_id = 5) AS description_slov, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id["product_id"] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity > '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
            $product = $query_p->row;

            //$product = $this->model_catalog_product->getProduct($product_id['product_id']);

            if ($product['image'] && trim($product['model']) != '') {

                $categories = $this->model_catalog_product->getCategories($product['product_id']);
                $category = $this->model_catalog_category->getCategory($categories[0]["category_id"]);
                $attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);

                $skad = preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']);

                //Добавил парент категорию 139, чтобы попадали промышленные ишни и для тракторов.
                if (in_array($category["parent_id"], array('297', '303', '383', '366', '1287', '139')) ||
                    $categories[0]["category_id"] == "1287" ||
                    ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']) === 1)) {

                    //Чешская
                    $output2 .= '<AVAILABILITY>' . "\n";
                    $output2 .= '<ID>' . $product["model"] . '</ID>';
                    $output2 .= '<IN_STOCK>' . $product['quantity'] . '</IN_STOCK>';
                    if ($product['status'] == 1) {
                        $output2 .= '<ACTIVE>true</ACTIVE>';
                    } else {
                        $output2 .= '<ACTIVE>false</ACTIVE>';
                    }

                    //Венгерская
                    $output_vengr2 .= '<AVAILABILITY>' . "\n";
                    $output_vengr2 .= '<ID>' . $product["model"] . '</ID>';
                    $output_vengr2 .= '<IN_STOCK>' . $product['quantity'] . '</IN_STOCK>';
                    if ($product['status'] == 1) {
                        $output_vengr2 .= '<ACTIVE>true</ACTIVE>';
                    } else {
                        $output_vengr2 .= '<ACTIVE>false</ACTIVE>';
                    }

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {
                        $output_slov2 .= '<AVAILABILITY>' . "\n";
                        $output_slov2 .= '<ID>' . $product["model"] . '</ID>';
                        $output_slov2 .= '<IN_STOCK>' . $product['quantity'] . '</IN_STOCK>';
                        if ($product['status'] == 1) {
                            $output_slov2 .= '<ACTIVE>true</ACTIVE>';
                        } else {
                            $output_slov2 .= '<ACTIVE>false</ACTIVE>';
                        }
                    }

                    //Чешская
                    $output .= '<ITEM>' . "\n";
                    $output .= '<ID>' . $product["model"] . '</ID>' . "\n";
                    $output .= '<STAGE>live</STAGE>' . "\n";
                    if ($category["parent_id"] == '297') {
                        $output .= '<CATEGORY_ID>MP054</CATEGORY_ID>';
                    } elseif ($category["parent_id"] == '303') {
                        $output .= '<CATEGORY_ID>MP055</CATEGORY_ID>';
                    } elseif ($category["parent_id"] == '383') {
                        $output .= '<CATEGORY_ID>MP056</CATEGORY_ID>';
                    } elseif ($category["parent_id"] == '366' && $category["parent_id"] == '139') {
                        $output .= '<CATEGORY_ID>MP057</CATEGORY_ID>';
                    } elseif ($categories[0]["category_id"] == "1287") {
                        $output .= '<CATEGORY_ID>ND562</CATEGORY_ID>';
                    } elseif ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']) === 1) {
                        $output .= '<CATEGORY_ID>ND570</CATEGORY_ID>';
                    }

                    //Венгерская
                    $output_vengr .= '<ITEM>' . "\n";
                    $output_vengr .= '<ID>' . $product["model"] . '</ID>' . "\n";
                    $output_vengr .= '<STAGE>live</STAGE>' . "\n";

                    //Шины  Osobní auta - Летние
                    if ($category["parent_id"] == '297') {
                        $output_vengr .= '<CATEGORY_ID>ND619</CATEGORY_ID>';
                    }
                    // Шины Osobní auta - зимние
                    elseif ($category["parent_id"] == '303') {
                        $output_vengr .= '<CATEGORY_ID>ND619</CATEGORY_ID>';
                    }
                    // Шины Osobní auta - Celoroční
                    elseif ($category["parent_id"] == '383') {
                        $output_vengr .= '<CATEGORY_ID>ND619</CATEGORY_ID>';
                    }
                    // Шины Nákladní auta - Детние
                    elseif ($category["parent_id"] == '366' && $category["parent_id"] == '139') {
                        $output_vengr .= '<CATEGORY_ID>MP059</CATEGORY_ID>';
                    }
                    // Čelní skla
                    elseif ($categories[0]["category_id"] == "1287") {
                        $output_vengr .= '<CATEGORY_ID>ND562</CATEGORY_ID>';
                    }
                    // Диски ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']) === 1)
                    elseif ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']) === 1) {
                        $output_vengr .= '<CATEGORY_ID>ND570</CATEGORY_ID>';
                    }

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {

                        $output_slov .= '<ITEM>' . "\n";
                        $output_slov .= '<ID>' . $product["model"] . '</ID>' . "\n";
                        $output_slov .= '<STAGE>live</STAGE>' . "\n";

                        //Шины  Osobní auta - Летние
                        if ($category["parent_id"] == '297') {
                            $output_slov .= '<CATEGORY_ID>MP010TR</CATEGORY_ID>';
                        } // Шины Osobní auta - зимние
                        elseif ($category["parent_id"] == '303') {
                            $output_slov .= '<CATEGORY_ID>MP011TR</CATEGORY_ID>';
                        } // Шины Osobní auta - Celoroční
                        elseif ($category["parent_id"] == '383') {
                            $output_slov .= '<CATEGORY_ID>MP012TR</CATEGORY_ID>';
                        } // Шины Nákladní auta - Детние
                        elseif ($category["parent_id"] == '366' && $category["parent_id"] == '139') {
                            $output_slov .= '<CATEGORY_ID>MP013TR</CATEGORY_ID>';
                        } // Čelní skla
                        elseif ($categories[0]["category_id"] == "1287") {
                            $output_slov .= '<CATEGORY_ID>ND562</CATEGORY_ID>';
                        } // Диски
                        elseif ($categories[0]["category_id"] == '577' && $skad === 1) {
                            $output_slov .= '<CATEGORY_ID>ND570</CATEGORY_ID>';
                        }
                    }

                    //Чешская
                    If (isset($product['manufacturer'])) {
                        $output .= "\n" . '<BRAND_ID><![CDATA[' . $product['manufacturer'] . ']]></BRAND_ID>' . "\n";
                    }

                    //Венгерская
                    If (isset($product['manufacturer'])) {
                        $output_vengr .= "\n" .  '<BRAND_ID>' . mb_strtoupper($product['manufacturer']) . '</BRAND_ID>' . "\n";
                    }

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {
                        If (isset($product['manufacturer'])) {
                            $output_slov .= "\n" . '<BRAND_ID>' . mb_strtoupper($product['manufacturer']) . '</BRAND_ID>' . "\n";
                        }
                    }

                    //Чешская
                    if ($categories[0]["category_id"] != "1287") {

                        $description = htmlspecialchars_decode($product['description'], ENT_XHTML);
                    } else {
                        $descr = htmlspecialchars_decode($product['description'], ENT_XHTML);
                        $description = str_replace("Uskutečňujeme i výměnu skla.",'', $descr);

                    }

                    if ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']) === 1) {
                        $output .= '<TITLE><![CDATA[Alu kola' . str_replace($product['manufacturer'], '', $product['name']) . ']]></TITLE>' . "\n";
                    } else {
                        $output .= '<TITLE><![CDATA[' . str_replace($product['manufacturer'], '', $product['name']) . ']]></TITLE>' . "\n";
                    }
                    $output .= '<SHORTDESC><![CDATA[' . mb_strimwidth($description, 0, 297, "...") . ']]></SHORTDESC>' . "\n";
                    $output .= '<LONGDESC><![CDATA[' . $description . ']]></LONGDESC>' . "\n";
                    //$output .= '<URL>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</URL>';
                    //$output .= '<ITEM_TYPE>new</ITEM_TYPE>';
                    $sort = $product['sort_order'] + 1;
                    $output .= '<PRIORITY>' . $sort . '</PRIORITY>' . "\n";

                    //Венгерская
                    $description_vengr = htmlspecialchars_decode($product['description_vengr'], ENT_XHTML);

                    if ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name_vengr']) === 1) {
                        $output_vengr .= '<TITLE><![CDATA[Alu kola' . str_replace($product['manufacturer'], '', $product['name_vengr']) . ']]></TITLE>' . "\n";
                    } else {
                        $output_vengr .= '<TITLE><![CDATA[' . str_replace($product['manufacturer'], '', $product['name_vengr']) . ']]></TITLE>' . "\n";
                    }
                    $output_vengr .= '<SHORTDESC><![CDATA[' . mb_strimwidth($description_vengr, 0, 297, "...") . ']]></SHORTDESC>' . "\n";
                    $output_vengr .= '<LONGDESC><![CDATA[' . $description_vengr . ']]></LONGDESC>' . "\n";
                    //$output .= '<URL>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</URL>';
                    //$output .= '<ITEM_TYPE>new</ITEM_TYPE>';
                    $sort = $product['sort_order'] + 1;
                    $output_vengr .= '<PRIORITY>' . $sort . '</PRIORITY>' . "\n";

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {
                        $description_slov = htmlspecialchars_decode($product['description_slov'], ENT_XHTML);

                        if ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name_vengr']) === 1) {
                            $output_slov .= '<TITLE><![CDATA[Alu kola' . str_replace($product['manufacturer'], '', $product['name_slov']) . ']]></TITLE>' . "\n";
                        } else {
                            $output_slov .= '<TITLE><![CDATA[' . str_replace($product['manufacturer'], '', $product['name_slov']) . ']]></TITLE>' . "\n";
                        }
                        $output_slov .= '<SHORTDESC><![CDATA[' . mb_strimwidth($description_slov, 0, 297, "...") . ']]></SHORTDESC>' . "\n";
                        $output_slov .= '<LONGDESC><![CDATA[' . $description_slov . ']]></LONGDESC>' . "\n";
                        //$output .= '<URL>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</URL>';
                        //$output .= '<ITEM_TYPE>new</ITEM_TYPE>';
                        $sort = $product['sort_order'] + 1;
                        $output_slov .= '<PRIORITY>' . $sort . '</PRIORITY>' . "\n";
                    }

                    //Чешская
                    // --------- РАзмер пака ------------
                    If ($category["parent_id"] == '366' || $categories[0]["category_id"] == "1287") {
                        $output .= '<PACKAGE_SIZE>bigbox</PACKAGE_SIZE>' . "\n";
                    } else {
                        $output .= '<PACKAGE_SIZE>smallbox</PACKAGE_SIZE>' . "\n";
                    }
                    // -------- Конец Размер пака ---------
                    if ($product['ean']) {
                        $output .= '<BARCODE>' . $product['ean'] . '</BARCODE>' . "\n";
                    }

                    //Венгерская
                    // --------- РАзмер пака ------------
                    If ($category["parent_id"] == '366' || $categories[0]["category_id"] == "1287") {
                        $output_vengr .= '<PACKAGE_SIZE>bigbox</PACKAGE_SIZE>' . "\n";
                    } else {
                        $output_vengr .= '<PACKAGE_SIZE>smallbox</PACKAGE_SIZE>' . "\n";
                    }
                    // -------- Конец Размер пака ---------
                    if ($product['ean']) {
                        $output_vengr .= '<BARCODE>' . $product['ean'] . '</BARCODE>' . "\n";
                    }

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {
                        // --------- РАзмер пака ------------
                        If ($category["parent_id"] == '366' || $categories[0]["category_id"] == "1287") {
                            $output_slov .= '<PACKAGE_SIZE>bigbox</PACKAGE_SIZE>' . "\n";
                        } else {
                            $output_slov .= '<PACKAGE_SIZE>smallbox</PACKAGE_SIZE>' . "\n";
                        }
                        // -------- Конец Размер пака ---------
                        if ($product['ean']) {
                            $output_slov .= '<BARCODE>' . $product['ean'] . '</BARCODE>' . "\n";
                        }
                    }

                    //Общее
                    $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                    //Чешская цена.
                    $price = str_replace(' ', '', $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), false, false, false));
                    $price_3 = ceil($price * 1.03);
                    //$this->tax->calculate($product['special'], $product['tax_class_id']);
                    $output .= '<PRICE>' . $price_3 . '</PRICE>' . "\n";
                    $output .= '<VAT>21</VAT>' . "\n";

                    // Для Венгерсокого отделения цена.

                    //$output .= '<PRICE_VAT>' . $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'EUR', null, false) . '</PRICE_VAT>';
                    $price_vengr = str_replace(' ', '', $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), 'HUF', false, false));
                    $price_3_vengr = ceil($price_vengr * 1.06);

                    $output_vengr .= '<PRICE>' . $price_3_vengr . '</PRICE>' . "\n";
                    $output_vengr .= '<VAT>27</VAT>' . "\n";

                    // Для Словацкого цена.

                    If ($categories[0]["category_id"] != '577') {
                        //$output .= '<PRICE_VAT>' . $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'EUR', null, false) . '</PRICE_VAT>';
                        $price_vengr = str_replace(' ', '', $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), 'EUR', false, false));
                        $price_3_vengr = ceil($price_vengr * 1.03);

                        $output_slov .= '<PRICE>' . $price_3_vengr . '</PRICE>' . "\n";
                        $output_slov .= '<VAT>20</VAT>' . "\n";
                    }


                    //1
                    if ($product['product_id'] == '46228') {
                        $output .= '<RRP>1803</RRP>';
                    }
                    //2
                    if ($product['product_id'] == '52983') {
                        $output .= '<RRP>1751</RRP>';
                    }
                    //3
                    if ($product['product_id'] == '34309') {
                        $output .= '<RRP>2678</RRP>';
                    }
                    //4
                    if ($product['product_id'] == '30714') {
                        $output .= '<RRP>1751</RRP>';
                    }
                    //5
                    if ($product['product_id'] == '31521') {
                        $output .= '<RRP>2266</RRP>';
                    }
                    //6
                    if ($product['product_id'] == '49221') {
                        $output .= '<RRP>2575</RRP>';
                    }
                    //7
                    if ($product['product_id'] == '52980') {
                        $output .= '<RRP>1906</RRP>';
                    }
                    //8
                    if ($product['product_id'] == '49849') {
                        $output .= '<RRP>1559</RRP>';
                    }
                    //9
                    if ($product['product_id'] == '58924') {
                        $output .= '<RRP>927</RRP>';
                    }
                    //10
                    if ($product['product_id'] == '61572') {
                        $output .= '<RRP>3778</RRP>';
                    }
                    //11
                    if ($product['product_id'] == '56236') {
                        $output .= '<RRP>1745</RRP>';
                    }

                    if (in_array($category["parent_id"], array('297', '303', '383', '366', '1287'))) {
                        foreach ($attributes as $attrib) {
                            foreach ($attrib["attribute"] as $att) {
                                if (isset($att["text"])) {
                                    if ($att['attribute_id'] == 6) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_WIDTH</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>WIDTH_INCH</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>MP_WIDTH</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    } elseif ($att['attribute_id'] == 7) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_PROFILE</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>HEIGHT_CM</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>MP_PROFILE</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    } elseif ($att['attribute_id'] == 8) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_DIAMETER</NAME>';
                                        $output .= '<VALUE>R' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>DIAMETER_INCH</NAME>';
                                        $output_vengr .= '<VALUE>R' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>MP_DIAMETER</NAME>';
                                        $output_slov .= '<VALUE>R' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    } elseif ($att['attribute_id'] == 18) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_FUEL_EFFICIENCY_CLASS</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                       /*
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>MP_FUEL_EFFICIENCY_CLASS</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";
*/
                                        //Словацкая
                                        if ($category["parent_id"] != '366') {
                                            $output_slov .= '<PARAM>' . "\n";
                                            $output_slov .= '<NAME>MP_FUEL_EFFICIENCY_CLASS</NAME>';
                                            $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                            $output_slov .= '</PARAM>' . "\n";
                                        }
                                    } elseif ($att['attribute_id'] == 19) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_ADHESION_CLASS</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        /*
                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>MP_ADHESION_CLASS</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";
                                        */

                                        //Словацкая
                                        if ($category["parent_id"] != '366') {
                                            $output_slov .= '<PARAM>' . "\n";
                                            $output_slov .= '<NAME>MP_ADHESION_CLASS</NAME>';
                                            $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                            $output_slov .= '</PARAM>' . "\n";
                                        }
                                    } elseif ($att['attribute_id'] == 21) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_ROLLING_NOISE_CLASS</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        /*
                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>MP_ROLLING_NOISE_CLASS</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";
                                        */

                                        //Словацкая
                                        if ($category["parent_id"] != '366') {

                                            $output_slov .= '<PARAM>' . "\n";
                                            $output_slov .= '<NAME>MP_ROLLING_NOISE_CLASS</NAME>';
                                            $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                            $output_slov .= '</PARAM>' . "\n";
                                        }
                                    } elseif ($att['attribute_id'] == 12) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_LOAD_INDEX</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        /*
                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>MP_LOAD_INDEX</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";
                                        */

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>MP_LOAD_INDEX</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    } elseif ($att['attribute_id'] == 13) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>MP_SPEED_INDEX</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        /*
                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>MP_SPEED_INDEX</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";
                                        */

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>MP_SPEED_INDEX</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($categories[0]["category_id"] == '577' && preg_match("/(SKAD drive|SKAD Sakura|SKAD Mitsar|SKAD Stiletto)/i", $product['name']) === 1) {
                        foreach ($attributes as $attrib) {
                            foreach ($attrib["attribute"] as $att) {
                                if (isset($att["text"])) {
                                    if ($att['attribute_id'] == 3) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>WIDTH_INCH</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>WIDTH_INCH</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        /*$output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>WIDTH_INCH</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";*/
                                    } elseif ($att['attribute_id'] == 4) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>CENTER_HOLE_MM</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>CENTER_HOLE_MM</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        /*$output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>CENTER_HOLE_MM</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";*/
                                    } elseif ($att['attribute_id'] == 5) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>ET_MM</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>ET_MM</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        /*$output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>ET_MM</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";*/
                                    } elseif ($att['attribute_id'] == 2) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>PITCH_MM</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>PITCH_MM</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        /*$output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>PITCH_MM</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";*/
                                    } elseif ($att['attribute_id'] == 1) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>DIAMETER_INCH</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>DIAMETER_INCH</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        /*$output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>DIAMETER_INCH</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";*/
                                    }
                                }
                            }
                            //Чешская
                            $output .= '<PARAM>' . "\n";
                            $output .= '<NAME>TYPE_OF_ND570</NAME>';
                            $output .= '<VALUE>Alu kola</VALUE>';
                            $output .= '</PARAM>' . "\n";

                            //Венгерская
                            $output_vengr .= '<PARAM>' . "\n";
                            $output_vengr .= '<NAME>TYPE_OF_ND570</NAME>';
                            $output_vengr .= '<VALUE>Alu kola</VALUE>';
                            $output_vengr .= '</PARAM>' . "\n";

                            //Словацкая
                            $output_slov .= '<PARAM>' . "\n";
                            $output_slov .= '<NAME>TYPE_OF_ND570</NAME>';
                            $output_slov .= '<VALUE>Alu kola</VALUE>';
                            $output_slov .= '</PARAM>' . "\n";
                        }
                    }

                    if ($categories[0]["category_id"] == "1287") {
                        foreach ($attributes as $attrib) {
                            foreach ($attrib["attribute"] as $att) {
                                if (isset($att["text"])) {
                                    if ($att['attribute_id'] == 23) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>CAR_BRAND</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>CAR_BRAND</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>CAR_BRAND</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    } elseif ($att['attribute_id'] == 24) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>CAR_MODEL_ND</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>CAR_MODEL_ND</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>CAR_MODEL_ND</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    } elseif ($att['attribute_id'] == 25) {
                                        //Чешская
                                        $output .= '<PARAM>' . "\n";
                                        $output .= '<NAME>YEAR_OF_MANUFACTURE</NAME>';
                                        $output .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output .= '</PARAM>' . "\n";

                                        //Венгерская
                                        $output_vengr .= '<PARAM>' . "\n";
                                        $output_vengr .= '<NAME>YEAR_OF_MANUFACTURE</NAME>';
                                        $output_vengr .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_vengr .= '</PARAM>' . "\n";

                                        //Словацкая
                                        $output_slov .= '<PARAM>' . "\n";
                                        $output_slov .= '<NAME>YEAR_OF_MANUFACTURE</NAME>';
                                        $output_slov .= '<VALUE>' . $att["text"] . '</VALUE>';
                                        $output_slov .= '</PARAM>' . "\n";
                                    }
                                }
                            }
                        }
                    }
                    //Общее
                    $this->has_items = true;
                    //var_dump(stristr ($product['image'], 'no_image', true));
                    //|| preg_match("/(no_image)/i", $product['image']) === 1

                    //$image = Journal2Utils::resizeImage($this->model_tool_image, $product['image'] ? $product['image'] : 'data/journal2/no_image_large.jpg', $this->data['image_width'], $this->data['image_height'], $this->data['image_resize_type']);

                    $image = Journal2Utils::resizeImage($this->model_tool_image, $product['image'] ? $product['image'] : 'data/journal2/no_image_large.jpg', $this->data['image_width'], $this->data['image_height'], $this->data['image_resize_type']);

                    //Чешская
                    $output .= '<MEDIA>' . "\n";
                    $output .= '<URL>' . $image . '</URL>';
                    $output .= '<MAIN>true</MAIN>';
                    $output .= '</MEDIA>' . "\n";

                    //Венгерская
                    $output_vengr .= '<MEDIA>' . "\n";
                    $output_vengr .= '<URL>' . $image . '</URL>';
                    $output_vengr .= '<MAIN>true</MAIN>';
                    $output_vengr .= '</MEDIA>' . "\n";

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {

                        $output_slov .= '<MEDIA>' . "\n";
                        $output_slov .= '<URL>' . $image . '</URL>';
                        $output_slov .= '<MAIN>true</MAIN>';
                        $output_slov .= '</MEDIA>' . "\n";
                    }

                    /*
                                            $img = urlencode($this->model_tool_image->resize($product['image'], 500, 500));
                                            $img = str_replace('%3A', ':', $img);
                                            $img = str_replace('%2F', '/', $img);
                                            $img = str_replace('+', '%20', $img);
                                            $output .= '<MEDIA>';
                                            $output .= '<URL>' . $image . '</URL>';
                                            $output .= '<MAIN>true</MAIN>';
                                            $output .= '</MEDIA>';
                    */

                    if ($product['special']) {
                        $spesialStock = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = " . $product['product_id'] . " AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1");

                        //Чешская
                        $special = str_replace(' ', '', $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), false, false, false));
                        $special_3 = ceil($special * 1.02);

                        $output .= '<PROMOTION>' . "\n";
                        $output .= '<PRICE>' . $special_3 . '</PRICE>';
                        $output .= '<FROM>' . $spesialStock->row['date_start'] . 'T00:00:00</FROM>';
                        $output .= '<TO>' . $spesialStock->row['date_end'] . 'T23:59:59</TO>';
                        $output .= '</PROMOTION>' . "\n";

                        //Венгерская
                        $special_vengr = str_replace(' ', '', $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), "HUF", false, false));
                        $special_vengr_3 = ceil($special_vengr * 1.02);

                        $output_vengr .= '<PROMOTION>' . "\n";
                        $output_vengr .= '<PRICE>' . $special_vengr_3 . '</PRICE>';
                        $output_vengr .= '<FROM>' . $spesialStock->row['date_start'] . 'T00:00:00</FROM>';
                        $output_vengr .= '<TO>' . $spesialStock->row['date_end'] . 'T23:59:59</TO>';
                        $output_vengr .= '</PROMOTION>' . "\n";

                        //Словацкая
                        If ($categories[0]["category_id"] != '577') {

                            $special_slov = str_replace(' ', '', $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), "EUR", false, false));
                            $special_slov_3 = ceil($special_slov * 1.02);

                            $output_slov .= '<PROMOTION>' . "\n";
                            $output_slov .= '<PRICE>' . $special_slov_3 . '</PRICE>';
                            $output_slov .= '<FROM>' . $spesialStock->row['date_start'] . 'T00:00:00</FROM>';
                            $output_slov .= '<TO>' . $spesialStock->row['date_end'] . 'T23:59:59</TO>';
                            $output_slov .= '</PROMOTION>' . "\n";
                        }
                    }

                    //Чешская
                    if ($product['location'] == 'pohoda') {
                        $output .= '<DELIVERY_DELAY>0</DELIVERY_DELAY>' . "\n";
                    } else {
                        if ($product['quantity']) {
                            $output .= '<DELIVERY_DELAY>3</DELIVERY_DELAY>' . "\n";
                        } else {
                            if ($product['heureka_delivery_date'] > 0) {
                                $output .= '<DELIVERY_DELAY>' . $product['heureka_delivery_date'] . '</DELIVERY_DELAY>' . "\n";
                            } else {
                                $output .= '<DELIVERY_DELAY>5</DELIVERY_DELAY>' . "\n";
                            }
                        }
                    }

                    //Венгерская
                    if ($product['location'] == 'pohoda') {
                        $output_vengr .= '<DELIVERY_DELAY>0</DELIVERY_DELAY>' . "\n";
                    } else {
                        if ($product['quantity']) {
                            $output_vengr .= '<DELIVERY_DELAY>3</DELIVERY_DELAY>' . "\n";
                        } else {
                            if ($product['heureka_delivery_date'] > 0) {
                                $output_vengr .= '<DELIVERY_DELAY>' . $product['heureka_delivery_date'] . '</DELIVERY_DELAY>' . "\n";
                            } else {
                                $output_vengr .= '<DELIVERY_DELAY>5</DELIVERY_DELAY>' . "\n";
                            }
                        }
                    }

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {
                        if ($product['location'] == 'pohoda') {
                            $output_slov .= '<DELIVERY_DELAY>0</DELIVERY_DELAY>' . "\n";
                        } else {
                            if ($product['quantity']) {
                                $output_slov .= '<DELIVERY_DELAY>3</DELIVERY_DELAY>' . "\n";
                            } else {
                                if ($product['heureka_delivery_date'] > 0) {
                                    $output_slov .= '<DELIVERY_DELAY>' . $product['heureka_delivery_date'] . '</DELIVERY_DELAY>' . "\n";
                                } else {
                                    $output_slov .= '<DELIVERY_DELAY>5</DELIVERY_DELAY>' . "\n";
                                }
                            }
                        }
                    }


                    $output .= '</ITEM>' . "\n";
                    $output2 .= '</AVAILABILITY>' . "\n";

                    //Венгерская
                    $output_vengr .= '</ITEM>' . "\n";
                    $output_vengr2 .= '</AVAILABILITY>' . "\n";

                    //Словацкая
                    If ($categories[0]["category_id"] != '577') {

                        $output_slov .= '</ITEM>' . "\n";
                        $output_slov2 .= '</AVAILABILITY>' . "\n";
                    }
                }
            }

            If ($categories[0]["category_id"] != '1287') {
                $output_vengr_end .= $output_vengr;
                $output_vengr_end2 .= $output_vengr2;

                $output_slov_end .= $output_slov;
                $output_slov_end2 .= $output_slov2;
            }


        }

        $output .= '</ITEMS>';
        $output2 .= '</AVAILABILITIES>';

        //Венгерская
        $output_vengr_end .= '</ITEMS>';
        $output_vengr_end2 .= '</AVAILABILITIES>';

        //Словацкая
        $output_slov_end .= '</ITEMS>';
        $output_slov_end2 .= '</AVAILABILITIES>';

        $basedir = (realpath(dirname(__FILE__))) . '/../../../../';

        //Чешская
        $file = $basedir . 'marketplace.xml';
        $file2 = $basedir . 'marketplaceavail.xml';
        $fp = fopen($file, "w9");
        $fp2 = fopen($file2, "w9");
        fwrite($fp, $output);
        fwrite($fp2, $output2);
        fclose($fp);
        fclose($fp2);
        //$this->response->addHeader('Content-Type: application/xml');
        //$this->response->setOutput($output);
        //$this->response->addHeader('Content-Type: application/xml');
        //$this->response->setOutput($output2);
        //  $this->response->addHeader('Content-Type: text/xml;');
        //    $this->response->setOutput($output);

        //Венгерская
        $file_vengr = $basedir . 'marketplace_vengr.xml';
        $file_vengr2 = $basedir . 'marketplaceavail_vengr.xml';
        $fp_vengr = fopen($file_vengr, "w9");
        $fp_vengr2 = fopen($file_vengr2, "w9");
        fwrite($fp_vengr, $output_vengr_end);
        fwrite($fp_vengr2, $output_vengr_end2);
        fclose($fp_vengr);
        fclose($fp_vengr2);

        //Словацкая
        $file_slov = $basedir . 'marketplace_slov.xml';
        $file_slov2 = $basedir . 'marketplaceavail_slov.xml';
        $fp_slov = fopen($file_slov, "w9");
        $fp_slov2 = fopen($file_slov2, "w9");
        fwrite($fp_slov, $output_slov_end);
        fwrite($fp_slov2, $output_slov_end2);
        fclose($fp_slov);
        fclose($fp_slov2);

        //Общее
        $finish = microtime(true) - $start;
        $file3 = $basedir . 'marketplace_time.xml';
        $fp3 = fopen($file3, "w9");
        fwrite($fp3, $finish);
        fclose($fp3);
        echo "OK";


    }
}

?>