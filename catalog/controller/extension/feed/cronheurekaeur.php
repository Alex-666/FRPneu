<?php
class ControllerExtensionFeedCronheurekaeur extends Controller {

    private $categories = array();
    private $top;

   public function build_cat() {
           $this->load->model('catalog/category');

           $results = $this->model_catalog_category->getCategories(0);

           $cats = array(139, 156);

           foreach ($results as $result) {
               if (in_array($result['category_id'], $cats)) {

                    $this->top = $result['category_id'];


                   $this->categories[$result['category_id']] =  array(
                    'category_id' => $result['name'],
                    'name' => $result['category_id'],
                    'top' => $this->top
                    );


                    $this->getCategories($result['category_id']);

               }
            };

   }


    protected function getCategories($category_id) {

        $results = $this->model_catalog_category->getCategories($category_id);

        foreach ($results as $result) {

                $this->categories[$result['category_id']] =  array(
                'category_id' => $result['name'],
                'name' => $result['category_id'],
                'top' => $this->top
                );

                $this->getCategories($result['category_id']);

            }

    }

    public function getParentId($category_id) {
        $query = $this->db->query("SELECT `parent_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '$category_id'");
        if ($query->num_rows > 0){
            return $query->row['parent_id'];
        }
        return 0;
    }

    public function index()
    {
        $this->config->get('config_currency', 'EUR');
        $this->config->get('config_language', 'sk-sk');
        $this->config->set('config_store_id', '1');

        $this->build_cat();


        $output = '<?xml version=\'1.0\' encoding=\'UTF-8\' ?>';
        $output .= '<SHOP>';

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        //$products = $this->model_catalog_product->getProducts();

        $sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '4' AND p.quantity > 0 AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '1' GROUP BY p.product_id ";
        $products = $this->db->query($sql);

        $cnotentered = 0;
        $notentered[] = 0;

        foreach ($products->rows as $product_id) {
            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id[product_id] . "' AND pd.language_id = '4' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '1'");

            //$product = $this->model_catalog_product->getProduct($product_id['product_id']);

            $product = $query->row;

            $attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);
            $categories = $this->model_catalog_product->getCategories($product['product_id']);
            If ($categories[0]["category_id"] == 1287) {
                continue;
            }

            if ($product['image'] && trim($product['model']) != '') {
                if ($product["location"] != 'pohoda') {
                    //Находим где нужные атрибуты.
                    foreach ($attributes[0] as $attribute) {
                        foreach ($attribute as $atr) {
                            if ($atr["attribute_id"] == 6) {
                                $too_atrib = $atr["text"];
                            }
                            if ($atr["attribute_id"] == 7) {
                                $tree_atrib = $atr["text"];
                            }
                            if ($atr["attribute_id"] == 8) {
                                $radius = $atr["text"];
                            }
                            if ($atr["attribute_id"] == 12) {
                                $nosnost = $atr["text"];
                            }
                            if ($atr["attribute_id"] == 13) {
                                $rychlost = $atr["text"];
                            }
                            if ($atr["attribute_id"] == 15) {
                                $model_atrib = $atr["text"];
                            }

                        }
                    }

                    if (!$product["manufacturer"]) {
                        $cnotentered++;
                        $notentered .= 'manufacturer \n<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>\n';
                        continue;
                    }
                    if (!$too_atrib || $too_atrib == 0 || !$tree_atrib || $tree_atrib == 0) {
                        $cnotentered++;
                        $notentered .= 'attribute0 \n<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>\n';
                        continue;
                    } else {
                        $too_atrib = " " . $too_atrib;
                    }

                    if (!$radius || $radius == 0) {
                        $cnotentered++;
                        $notentered .= 'attribute_radius \n<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>\n';
                        continue;
                    } else {
                        $radius = " R" . $radius;
                    }
                    if (!$nosnost) {
                        $cnotentered++;
                        $notentered .= 'attribute_nosnost \n<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>\n';
                        continue;
                    } else {
                        $nosnost = " " . $nosnost;
                    }
                    if (!$rychlost || $rychlost === 0) {
                        $cnotentered++;
                        $notentered .= 'attribute_rychlost \n<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>\n';
                        continue;
                    }
                    if (!$model_atrib) {
                        $cnotentered++;
                        $notentered .= 'attribute7 \n<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>\n';
                        continue;
                    } else {
                        $model_atrib = " " . $model_atrib;
                    }
                }

                $output .= "\n" . '<SHOPITEM>';
                $output .= '<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>';
                $output .= '<EAN>' . $product['ean'] . '</EAN>';

                if ($product['location'] == 'pohoda') {
                    $output .= '<DELIVERY_DATE>0</DELIVERY_DATE>';
                } else {
                    if ($product['quantity']) {
                        $output .= '<DELIVERY_DATE>3</DELIVERY_DATE>';
                    } else {
                        if ($product['heureka_delivery_date'] > 0) {
                            $output .= '<DELIVERY_DATE>' . $product['heureka_delivery_date'] . '</DELIVERY_DATE>';
                        } else {
                            $output .= '<DELIVERY_DATE>5</DELIVERY_DATE>';
                        }
                    }
                }
                if ($product["location"] == 'pohoda') {
                    $output .= '<PRODUCTNAME><![CDATA[' . $product['name'] . ']]></PRODUCTNAME>';
                } else {
                    $name = $product["manufacturer"] . $model_atrib . $too_atrib . "/" . $tree_atrib . $radius . $nosnost . $rychlost;
                    $output .= '<PRODUCTNAME><![CDATA[' . $name . ']]></PRODUCTNAME>';

                }
                $output .= '<DESCRIPTION><![CDATA[' . $product['description'] . ']]></DESCRIPTION>';
                $url = str_replace('www.frpneu.cz', 'www.frpneu.sk', $this->url->link('product/product', 'product_id=' . $product['product_id']));
                $output .= '<URL><![CDATA[' . $url . ']]></URL>';
                $output .= '<ITEM_TYPE>new</ITEM_TYPE>';
                $output .= '<MANUFACTURER><![CDATA[' . $product['manufacturer'] . ']]></MANUFACTURER>';

                $img = str_replace('www.frpneu.cz', 'www.frpneu.sk', urlencode($this->model_tool_image->resize($product['image'], 500, 500)));
                $img = str_replace('%3A', ':', $img);
                $img = str_replace('%2F', '/', $img);
                $img = str_replace('+', '%20', $img);

                $output .= '<IMGURL>' . $img . '</IMGURL>';

                if ($product['special']) {
                    $output .= '<PRICE_VAT>' . $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'EUR', null, false) . '</PRICE_VAT>';
                } else {
                    $output .= '<PRICE_VAT>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), 'EUR', null, false) . '</PRICE_VAT>';
                }

                if ($categories){
                    $category = $categories[0];
                    if (isset($category['category_id'])){
                        $heureka_category = 'Heureka.sk';
                        $heureka_category_id = 0;


                        $parent = $this->getParentId($category['category_id']);
                        $category_description = $this->model_catalog_category->getCategory($category['category_id']);

                        if (!empty($this->categories[$category['category_id']])) {
                            $category_description['category_id'] = $this->categories[$category['category_id']]['top'];
                        }


                        /*
                        if ($parent != 0){
                            do {

                                $category_description = $this->model_catalog_category->getCategory($parent);
                                    if ($category_description){
                                        $parent = $this->getParentId($category_description['category_id']);
                                    };

                            } while ($parent != 0);

                        }
                        */
                        switch ($category_description['category_id']) {
                            case '139':
                                if ($parent == '366') {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Pneumatiky nákladní';
                                } else {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Pneumatiky';
                                }
                                $heureka_category_id = 972;
                                break;
                            case '156':
                                $heureka_category = 'Heureka.sk | Auto-moto | Alu kola';
                                $heureka_category_id = 1471;
                                break;
                            case '1286':
                                $heureka_category = 'Heureka.sk | Auto-moto | Náplně a kapaliny | Maziva';
                                $heureka_category_id = 1471;
                                break;
                            case '376':
                                if (preg_match_all("/(Kabely startovací)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Autodoplňky | Startovací kabely';
                                } elseif (preg_match_all("/(Kapalina do)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Sport | Cyklistika | Oleje, vazelíny, čističe';
                                } elseif (preg_match_all("/(Žárovka)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Autodoplňky | Autožárovky';
                                } elseif (preg_match_all("/(Světlomet)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Autodíly | Přední světlomety';
                                } elseif (preg_match_all("/(Bezpecnostni matky|Klic M|Matice|Šroub|Šrouby na kola|Vymezovaci krouzky|Bezpecnostni srouby)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Autodoplňky | Kolové šrouby a matice';
                                } elseif (preg_match_all("/(filtr)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Autodíly | Vzduchové filtry pro automobily';
                                } elseif (preg_match_all("/(Senzor|Vložka vysoušeče)/", $product['name'])) {
                                    $heureka_category = 'Heureka.sk | Auto-moto | Autodíly | Váhy vzduchu';
                                } else {
                                    $heureka_category = $category_description['name'];
                                }
                                break;
                            default:
                                $heureka_category = $category_description['name'];
                                break;
                        }

                        $output .= '<CATEGORYTEXT><![CDATA[' . $heureka_category . ']]></CATEGORYTEXT>';
                        //$output .= '<ITEMGROUP_ID>' . $heureka_category_id . '</ITEMGROUP_ID>';
                    }
                }
                $output .= '</SHOPITEM>';
            }
        }

        $output .= '</SHOP>';

        $notentered = $cnotentered . $notentered;
        $basedir = (realpath(dirname(__FILE__))) . '/../../../../';
        $file = $basedir . 'heurekaeur.xml';
        $filetwo = $basedir . 'notheurekaeur.xml';
        $fp = fopen ($file, "w9");
        $fptwo = fopen($filetwo, "w9");
        fwrite ($fp, $output);
        fclose($fp);
        fwrite($fptwo, $notentered);
        fclose($fptwo);
        echo "ok";
        //$this->response->addHeader('Content-Type: application/xml');
        //$this->response->setOutput($output);

        $this->config->get('config_currency', 'CZK');

      //  $this->response->addHeader('Content-Type: text/xml;');
      //    $this->response->setOutput($output);
    }
}
?>
