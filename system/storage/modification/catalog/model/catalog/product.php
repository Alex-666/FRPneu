<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getRelatedByCategory($product_id)
    {
        $attributes_all = $this->getProductAttributes($product_id);
        foreach ($attributes_all as $attributes) {
            foreach ($attributes as $attribute) {
                if (is_array($attribute) || is_object($attribute)) {
                    foreach ($attribute as $attrib) {
                        // Радиус для Шины и Диска
                        if ($attrib["attribute_id"] == 8 or $attrib["attribute_id"] == 1) {
                            $radius = $attrib["text"];
                        }
                        //Шины
                        if ($attrib["attribute_id"] == 9) {
                            $sezon = $attrib["text"];
                        }
                        if ($attrib["attribute_id"] == 7) {
                            $profil = $attrib["text"];
                        }
                        if ($attrib["attribute_id"] == 6) {
                            $shirina = $attrib["text"];
                        }
                        //Диски
                        if ($attrib["attribute_id"] == 3) {
                            $disk_shirina = $attrib["text"];
                        }
                        if ($attrib["attribute_id"] == 2) {
                            $disk_otverst = $attrib["text"];
                        }
                        if ($attrib["attribute_id"] == 4) {
                            $disk_central = $attrib["text"];
                        }
                        if ($attrib["attribute_id"] == 5) {
                            $disk_et = $attrib["text"];
                        }
                        //Стекла
                        if ($attrib["attribute_id"] == 23) {
                            $steklo_marka = $attrib["text"];
                        }
                        if ($attrib["attribute_id"] == 24) {
                            $steklo_model = $attrib["text"];
                        }
                    }
                }
            }
        }

        $limit = $this->config->get('config_oc_related_limit') ? $this->config->get('config_oc_related_limit') : 4;
        $product_data = array();

        $first_limit = 0.4;
        $second_limit = 0.35;
        $third_limit = 0.25;
        $quarter_limit = 0.25;
        $first_limit_s = 0.5;


        if($attributes_all[0]["name"] == "Pneumatiky") {
            //$location = $this->getProduct($product_id)['location'];
            // Добавляем 40% шин (3 шт) из location = 'pohoda.
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa9 ON (p.product_id = pa9.product_id AND pa9.attribute_id='9')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa7 ON (p.product_id = pa7.product_id AND pa7.attribute_id='7')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa6 ON (p.product_id = pa6.product_id AND pa6.attribute_id='6')
                 WHERE p.location = 'pohoda' AND pa8.value = '" . $radius . "'
                  AND pa9.value = '" . $sezon . "'
                   AND pa7.value = '" . $profil . "'
                    AND pa6.value = '" . $shirina . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

            if (count($product_data) < round($limit*$first_limit)) {
                $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa9 ON (p.product_id = pa9.product_id AND pa9.attribute_id='9')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa7 ON (p.product_id = pa7.product_id AND pa7.attribute_id='7')
                 WHERE p.location = 'pohoda' AND pa8.value = '" . $radius . "'
                  AND pa9.value = '" . $sezon . "'
                   AND pa7.value = '" . $profil . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                foreach ($query->rows as $result) {
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                }
                if (count($product_data) < round($limit*$first_limit)) {
                    $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa9 ON (p.product_id = pa9.product_id AND pa9.attribute_id='9')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                 WHERE p.location = 'pohoda' AND pa8.value = '" . $radius . "'
                  AND pa9.value = '" . $sezon . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                    foreach ($query->rows as $result) {
                        $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                    }

                    if (count($product_data) < round($limit*$first_limit)) {
                        $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                 WHERE p.location = 'pohoda' AND pa8.value = '" . $radius . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                        foreach ($query->rows as $result) {
                            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                        }
                    }
                }
            }

            // Добавляем 35% шин (3 шт).
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa9 ON (p.product_id = pa9.product_id AND pa9.attribute_id='9')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa7 ON (p.product_id = pa7.product_id AND pa7.attribute_id='7')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa6 ON (p.product_id = pa6.product_id AND pa6.attribute_id='6')
                 WHERE pa8.value = '" . $radius . "'
                  AND pa9.value = '" . $sezon . "'
                   AND pa7.value = '" . $profil . "'
                    AND pa6.value = '" . $shirina . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$second_limit)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

            //Добавляем 25% дисков (2 шт.)
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                 WHERE pa1.value = '" . $radius . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$third_limit)));
            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }
            // Добавляем 25% стекол (2 шт.)
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa23 ON (p.product_id = pa23.product_id AND pa23.attribute_id='23')
                 WHERE pa23.attribute_id = '23' AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$quarter_limit)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }
        }
        //Для дисков
        elseif($attributes_all[0]["name"] == "Disky") {

            //Добавляем 40% дисков
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa3 ON (p.product_id = pa3.product_id AND pa3.attribute_id='3')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa2 ON (p.product_id = pa2.product_id AND pa2.attribute_id='2')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa4 ON (p.product_id = pa4.product_id AND pa4.attribute_id='4')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa5 ON (p.product_id = pa5.product_id AND pa5.attribute_id='5')
                 WHERE pa1.value = '" . $radius . "'
                  AND pa3.value = '" . $disk_shirina . "'
                   AND pa2.value = '" . $disk_otverst . "'
                    AND pa4.value = '" . $disk_central . "'
                    AND pa5.value = '" . $disk_et . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

            if (count($product_data) < (round($limit*$first_limit))) {
                $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa3 ON (p.product_id = pa3.product_id AND pa3.attribute_id='3')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa2 ON (p.product_id = pa2.product_id AND pa2.attribute_id='2')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa4 ON (p.product_id = pa4.product_id AND pa4.attribute_id='4')
                 WHERE pa1.value = '" . $radius . "'
                  AND pa3.value = '" . $disk_shirina . "'
                   AND pa2.value = '" . $disk_otverst . "'
                    AND pa4.value = '" . $disk_central . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                foreach ($query->rows as $result) {
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                }

                if (count($product_data) < (round($limit*$first_limit))) {
                    $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa3 ON (p.product_id = pa3.product_id AND pa3.attribute_id='3')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa2 ON (p.product_id = pa2.product_id AND pa2.attribute_id='2')
                 WHERE pa1.value = '" . $radius . "'
                  AND pa3.value = '" . $disk_shirina . "'
                   AND pa2.value = '" . $disk_otverst . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                    foreach ($query->rows as $result) {
                        $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                    }

                    if (count($product_data) < (round($limit*$first_limit))) {
                        $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa3 ON (p.product_id = pa3.product_id AND pa3.attribute_id='3')
                 WHERE pa1.value = '" . $radius . "'
                  AND pa3.value = '" . $disk_shirina . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                        foreach ($query->rows as $result) {
                            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                        }

                        if (count($product_data) < (round($limit*$first_limit))) {
                            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                 WHERE pa1.value = '" . $radius . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit-count($product_data))));

                            foreach ($query->rows as $result) {
                                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                            }
                        }
                    }
                }
            }

            // Добавляем 35% шин.
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                 WHERE pa8.value = '" . $radius . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$second_limit)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

            // Добавляем 25% стекол (2 шт.)
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa23 ON (p.product_id = pa23.product_id AND pa23.attribute_id='23')
                 WHERE pa23.attribute_id = '23' AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$quarter_limit)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }

        }

        // Для стекол
        elseif($attributes_all[0]["name"] == "Čelní skla") {
            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa23 ON (p.product_id = pa23.product_id AND pa23.attribute_id='23')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa24 ON (p.product_id = pa24.product_id AND pa24.attribute_id='24')
                 WHERE pa23.value = '" . $steklo_marka . "'
                   AND pa24.value = '" . $steklo_model . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit_s)));

            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }
            if (count($product_data) < (round($limit*$first_limit_s))) {
                $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa23 ON (p.product_id = pa23.product_id AND pa23.attribute_id='23')
                 WHERE pa23.value = '" . $steklo_marka . "'
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit_s-count($product_data))));

                foreach ($query->rows as $result) {
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                }
                if (count($product_data) < (round($limit*$first_limit_s))) {
                    $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa23 ON (p.product_id = pa23.product_id AND pa23.attribute_id='23')
                 WHERE pa23.attribute_id = '23' AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . (round($limit*$first_limit_s-count($product_data))));

                    foreach ($query->rows as $result) {
                        $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                    }

                }
            }

            $query = $this->db->query("SELECT DISTINCT(p.product_id) FROM `" . DB_PREFIX . "product` p
                LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa1 ON (p.product_id = pa1.product_id AND pa1.attribute_id='1')
                LEFT JOIN `" . DB_PREFIX . "product_attribute` pa8 ON (p.product_id = pa8.product_id AND pa8.attribute_id='8')
                 WHERE pa1.attribute_id = '1'
                      AND pa8.attribute_id = '8'                
                      AND p.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . ($limit - count($product_data)));
            foreach ($query->rows as $result) {
                $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
            }



        }
        else {

            $query = $this->db->query("SELECT `related_category` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");
            $category_list = $query->row['related_category'];
            if ($category_list == '') {
                $query = $this->db->query("SELECT DISTINCT(category_id) FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "'");
                $categories = array();
                foreach ($query->rows as $category) {
                    $categories[] .= $category['category_id'];
                }
                $category_list = implode(',', $categories);
            }
            if ($category_list != '') {
                $query = $this->db->query("SELECT DISTINCT(p2c.product_id) FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id) LEFT JOIN `" . DB_PREFIX . "product_attribute` pa ON (p.product_id = pa.product_id) WHERE pa.text = '" . $radius . "' AND p2c.category_id IN (" . $category_list . ") AND p2c.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . $limit);
                foreach ($query->rows as $result) {
                    $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                    $category_data[$result['product_id']] = $this->getCategories($result['product_id']);
                }
            }
            if (isset($category_data)) {
                if (count($query->rows) < $limit) {
                    foreach ($category_data as $results) {
                        foreach ($results as $resultend) {
                            $res[] .= $resultend['category_id'];
                            $category_minus = implode(',', $res);
                        }
                    }
                    $limit -= count($query->rows);
                    $query = $this->db->query("SELECT DISTINCT(p2c.product_id) FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id) WHERE p2c.category_id IN (" . $category_list . ") AND NOT p2c.category_id IN (" . $category_minus . ") AND p2c.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . $limit);
                    foreach ($query->rows as $result) {
                        $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                    }

                }
            } else {
                if ($category_list != '') {
                    $query = $this->db->query("SELECT DISTINCT(p2c.product_id) FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id) WHERE p2c.category_id IN ('" . $category_list . "') AND p2c.product_id != '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT " . $limit);
                    foreach ($query->rows as $result) {
                        $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
                    }
                }
            }
        }
        return $product_data;
    }

    public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        $price_dealer = $this->db->query("SELECT DISTINCT (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '7' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        $sklads = $this->db->query("SELECT DISTINCT * FROM shopsync_qty sq WHERE sq.product_id = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
                'sklad_tursko'     => ($sklads->row['tursko'] ? $sklads->row['tursko'] : 0),
                'sklad_brno'       => ($sklads->row['brno'] ? $sklads->row['brno']: 0),
                'stock_status'     => $query->row['stock_status'],

            'stock_status_id'     => $query->row['stock_status_id'],
        
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
                'price_dealer'     => ($price_dealer->row['discount'] ? $price_dealer->row['discount'] : ($query->row['discount'] ? $query->row['discount'] : $query->row['price'])),
                'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'heureka_delivery_date' => $query->row['heureka_delivery_date']
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added',
			'p.location',
            'p.location DESC, p.sort_order'
        );

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, 100-((ps.price/p.price)*100) as percent_special, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

		if(isset($data['category_ids'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		$sql .= " WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";

		if(isset($data['category_ids'])) {
			$sql .= " AND p2c.category_id IN(" . join(",", $data['category_ids']) . ")";
		}

		$sql .= " GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY percent_special DESC, LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
	
		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
	
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}
		
		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

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

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($product_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

		return $query->row;
	}


		public function getRemarketingCategories($product_id) {
			$category_data = '';
			
			$category_query = $this->db->query("SELECT DISTINCT cd.name FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "category_description` cd ON pc.category_id = cd.category_id LEFT JOIN `" . DB_PREFIX . "category_path` cp ON pc.category_id = cp.category_id WHERE pc.product_id = '" . (int)$product_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cp.level ASC LIMIT 5");
			
			foreach ($category_query->rows as $category) {
				$category_data .= $category['name'] . '/';
			}
			
			$category_data = rtrim($category_data, '/');
			
			return $category_data;
		}
		
	  
	public function getProfiles($product_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
    public function getImgEprel($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_eprel pe WHERE pe.product_id = '" . (int)$product_id . "'");

        return $query->rows;
    }

    public function editImgEprel($product_id, $old_image_eprel = null, $old_atrib_eprel = null, $image_eprel, $atrib_eprel) {
        $query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_eprel(product_id, image_eprel, atrib_eprel) VALUES ('" . $product_id . "', '" . $old_image_eprel . "', '" . $old_atrib_eprel . "') on duplicate key update image_eprel = '" . $image_eprel . "', atrib_eprel = '" . $atrib_eprel . "'");

        return $query->rows;
    }
}
