<?php

class ControllerExtensionFeedPneuGumy extends Controller
{

    private $categories = array();
    private $top;

    public function build_cat()
    {
        $results = $this->model_catalog_category->getCategories(0);

        $cats = array(139, 156);

        foreach ($results as $result) {
            if (in_array($result['category_id'], $cats)) {

                $this->top = $result['category_id'];


                $this->categories[$result['category_id']] = array(
                    'category_id' => $result['name'],
                    'name' => $result['category_id'],
                    'top' => $this->top
                );


                $this->getCategories($result['category_id']);

            }
        };

    }


    protected function getCategories($category_id)
    {

        $results = $this->model_catalog_category->getCategories($category_id);

        foreach ($results as $result) {

            $this->categories[$result['category_id']] = array(
                'category_id' => $result['name'],
                'name' => $result['category_id'],
                'top' => $this->top
            );

            $this->getCategories($result['category_id']);

        }

    }

    public function getParentId($category_id)
    {
        $query = $this->db->query("SELECT `parent_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '$category_id'");
        if ($query->num_rows > 0) {
            return $query->row['parent_id'];
        }
        return 0;
    }

    public function index() {
        $start = microtime(true);

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->build_cat();



        $output = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>';
        $output .= '<SHOP>';



        //$products = $this->model_catalog_product->getProducts();
        $cnotentered = 0;
        $notentered[] = 0;
        //$products_id[] = 0;
        /*
        $query = $this->db->query("SELECT DISTINCT product_id FROM `" . DB_PREFIX . "product` p WHERE LENGTH(TRIM(model)) > 0 AND LENGTH(image) > 0 AND status = 1");
        foreach ($query->rows as $pr_id) {
            $products_id[] .= $pr_id['product_id'];
        }
        */
        $sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.location = 'pohoda' AND p.quantity > 0 AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '0'";
        $products_id = $this->db->query($sql);

        foreach ($products_id->rows as $product_id) {
            //$product = $this->model_catalog_product->getProduct($product_id["product_id"]);
            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id[product_id] . "' AND pd.language_id = '4' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '1'");
            $product = $query->row;

            //$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id['product_id'] . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

            $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id['product_id'] . "' AND ad.language_id = '2'  AND pa.language_id = '2'");
            $categories = $this->model_catalog_product->getCategories($product['product_id']);
            if ($categories) {
                $category = $categories[0];

                $parent = $this->getParentId($category['category_id']);
            }
            //Парент категория 366 - Nákladní
            //Парент категория 2004 - Průmyslové
            //Парент категория 2001 - Zemědělské
            If (!in_array($parent, array('297', '303', '383'))) {
                continue;
            }

            foreach ($product_attribute_query->rows as $atr) {
                If ($atr["attribute_id"] == 1) {
                    continue 2;
                }
                if ($atr["attribute_id"] == 22 && $atr["text"] != 'dodávkové' && $atr["text"] != 'osobní') {
                    continue 2;
                }
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

            //$attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);

            //if ($product['image'] && trim($product['model']) != '') {
                /*if ($product['location'] != 'pohoda') {

                    //Находим где нужные атрибуты.

                    foreach ($product_attribute_query->rows as $atr) {
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
                */

                $output .= '<SHOPITEM>';
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
                    //if (preg_match('/^\d+/', $product['name'])) {
                    //$first_atrib = isset($attributes [0]["attribute"][6]["text"]) ? " " . $attributes [0]["attribute"][6]["text"] : '';
                    //$too_atrib = isset($attributes [0]["attribute"][0]["text"]) ? (($attributes [0]["attribute"][0]["text"] != 0) ? " " . $attributes [0]["attribute"][0]["text"] : '') : '';
                    //$tree_atrib = isset($attributes [0]["attribute"][1]["text"]) ? (($attributes [0]["attribute"][1]["text"] != 0) ? $attributes [0]["attribute"][1]["text"] : '') : '';
                    //$four_atrib = isset($attributes [0]["attribute"][2]["text"]) ? " " . $attributes [0]["attribute"][3]["text"] : '';
                    //$five_atrib = isset($attributes [0]["attribute"][3]["text"]) ? $attributes [0]["attribute"][4]["text"] : '';
                    //$model_atrib = isset($attributes [0]["attribute"][7]["text"]) ? " " . $attributes [0]["attribute"][7]["text"] : '';

                    $name = $product["manufacturer"] . $model_atrib . $too_atrib . "/" . $tree_atrib . $radius . $nosnost . $rychlost;
                    $output .= '<PRODUCTNAME><![CDATA[' . $name . ']]></PRODUCTNAME>';
                    /*if ($product['product_id'] == 2503) {
                        var_dump($attributes);
                        var_dump($name);
                        var_dump($radius);
                        die;
                    }*/
                    /*} else {
                        $output .= '<PRODUCTNAME><![CDATA[' . $product['name'] . ']]></PRODUCTNAME>';
                    }*/
                }
                $output .= '<DESCRIPTION><![CDATA[' . $product['description'] . ']]></DESCRIPTION>';
                $output .= '<URL>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</URL>';
                $output .= '<ITEM_TYPE>new</ITEM_TYPE>';
                $output .= '<QUANTITY>' . $product['quantity'] . '</QUANTITY>';
                $output .= '<MANUFACTURER><![CDATA[' . $product['manufacturer'] . ']]></MANUFACTURER>';

                $img = urlencode($this->model_tool_image->resize($product['image'], 500, 500));
                $img = str_replace('%3A', ':', $img);
                $img = str_replace('%2F', '/', $img);
                $img = str_replace('+', '%20', $img);

                $output .= '<IMGURL>' . $img . '</IMGURL>';

                if ($product['special']) {
                    $output .= '<PRICE_VAT>' . $this->tax->calculate($product['special'], $product['tax_class_id']) . '</PRICE_VAT>';
                } else {
                    $output .= '<PRICE_VAT>' . $this->tax->calculate($product['price'], $product['tax_class_id'])*0.9 . '</PRICE_VAT>';
                }

                if ($categories) {
                    $category = $categories[0];
                    if (isset($category['category_id'])) {
                        $heureka_category = 'Heureka.cz';
                        $heureka_category_id = 0;


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
                                    $heureka_category = 'Heureka.cz | Auto-moto | Pneumatiky nákladní';
                                } else {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Pneumatiky';
                                }
                                $heureka_category_id = 972;
                                break;
                            case '156':
                                $heureka_category = 'Heureka.cz | Auto-moto | Alu kola';
                                $heureka_category_id = 1471;
                                break;
                            case '1286':
                                $heureka_category = 'Heureka.cz | Auto-moto | Náplně a kapaliny | Maziva';
                                $heureka_category_id = 1471;
                                break;
                            case '376':
                                if (preg_match_all("/(Kabely startovací)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Autodoplňky | Startovací kabely';
                                } elseif (preg_match_all("/(Kapalina do)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Sport | Cyklistika | Oleje, vazelíny, čističe';
                                } elseif (preg_match_all("/(Žárovka)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Autodoplňky | Autožárovky';
                                } elseif (preg_match_all("/(Světlomet)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Autodíly | Přední světlomety';
                                } elseif (preg_match_all("/(Bezpecnostni matky|Klic M|Matice|Šroub|Šrouby na kola|Vymezovaci krouzky|Bezpecnostni srouby)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Autodoplňky | Kolové šrouby a matice';
                                } elseif (preg_match_all("/(filtr)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Autodíly | Vzduchové filtry pro automobily';
                                } elseif (preg_match_all("/(Senzor|Vložka vysoušeče)/", $product['name'])) {
                                    $heureka_category = 'Heureka.cz | Auto-moto | Autodíly | Váhy vzduchu';
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
                $output .= '</SHOPITEM>' . "\n";
        }

        $output .= '</SHOP>';

        $notentered = $cnotentered . $notentered;
        $basedir = (realpath(dirname(__FILE__))) . '/../../../../';
        $file = $basedir . 'pneugumy.xml';
        $filetwo = $basedir . 'notpneugumy.xml';

        $fp = fopen($file, "w9");
        $fptwo = fopen($filetwo, "w9");
        fwrite($fp, $output);
        fclose($fp);
        fwrite($fptwo, $notentered);
        fclose($fptwo);
        echo "ok";
        //$this->response->addHeader('Content-Type: application/xml');
        //$this->response->setOutput($output);
        //  $this->response->addHeader('Content-Type: text/xml;');
        //    $this->response->setOutput($output);
        $finish = microtime(true) - $start;
        $file3 = $basedir . 'pneugumy_time.xml';
        $fp3 = fopen($file3, "w9");
        fwrite($fp3, $finish);
        fclose($fp3);

    }
}

?>
