<?php
class ModelExtensionShippingPrCategory123 extends Model {
    function getQuote($address) {
        $this->load->language('extension/shipping/prcategory123');
        $currency_code = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');

        $method_data = array();

        // Все геозоны для региона пользователя
        $geo_zones_query = $this->db->query("SELECT geo_zone_id FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0') ORDER BY zone_id DESC");

        $geo_zones_ids = array();

        foreach ($geo_zones_query->rows as $row) {
            $geo_zones_ids[] = $row['geo_zone_id'];
        }

        // Тарифы и другие данные для региона
        $query = $this->db->query("SELECT * FROM prcategory123_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND zone_id = '" . (int)$address['zone_id'] . "'");

        $tariff = $this->getTariff($query, $address, 'zone');

        if (!$tariff && $geo_zones_ids) {

            // Тарифы и другие данные для геозон
            $query = $this->db->query("SELECT * FROM prcategory123_geozone WHERE geo_zone_id IN (" . implode(', ', $geo_zones_ids) . ")");
            $tariff = $this->getTariff($query, $address, 'geozone');
        }

        if ($tariff) {

            $quote_data = array();

            $cost = $tariff['total'];

            $quote_data['prcategory123'] = array(
                    'code' => 'prcategory123.prcategory123',
                    'title' => $this->config->get('prcategory123_quote_title'),
                    'cost' => $cost,
                    'text' => $this->currency->format($cost, $currency_code),
                    'tax_class_id' => 0
            );

            $method_data = array(
                    'code' => 'prcategory123',
                    'title' => $this->language->get('text_title'),
                    'quote' => $quote_data,
                    'sort_order' => $this->config->get('shipping_prcategory123_sort_order'),
                    'error' => false
            );
        }

        return $method_data;
    }

    private function getTariff($query, $address, $type) {

        if ($query->num_rows) {

            foreach ($query->rows as $pr_zone) {

                $current_city = utf8_strtolower($address['city']);

                // Проверим города, для которых работает эта доставка
                if (!empty($pr_zone['enabled_cities'])) {

                    $enabled_cities = array();

                    foreach (explode(',', $pr_zone['enabled_cities']) as $city) {

                        $enabled_cities[utf8_strtolower(trim($city))] = 1;
                    }

                    if (!isset($enabled_cities[$current_city])) {
                        continue;
                    }
                }

                // Проверим города, для которых эта доставка отключена
                if (!empty($pr_zone['disabled_cities'])) {

                    $disabled_cities = array();

                    foreach (explode(',', $pr_zone['disabled_cities']) as $city) {

                        $disabled_cities[utf8_strtolower(trim($city))] = 1;
                    }

                    if (isset($disabled_cities[$current_city])) {
                        continue;
                    }
                }

                $tariffs = array();

                // Группировка товаров по тарифам в зависимости от категории
                foreach ($this->cart->getProducts() as $product) {

                    if ($product['shipping']) {

                        $category_tariff = false;

                        // Ищем тариф для основной категории
                        $main_category = $this->db->query("SELECT pc.category_id, c.parent_id FROM " . DB_PREFIX . "product_to_category pc LEFT JOIN " . DB_PREFIX . "category c ON pc.category_id = c.category_id WHERE product_id = '" . (int)$product['product_id'] . "' LIMIT 1")->row;

                        if ($main_category) {

                            $category_tariff = $this->getCategoryTariff($main_category['category_id'], $pr_zone, $type);

                            if (!$category_tariff) {

                                // Ищем тариф для родительских категорий
                                $parent_id = $main_category['parent_id'];

                                while ($parent_id > 0 && !$category_tariff) {

                                    $parent = $this->getCategory($parent_id);
                                    $category_tariff = $this->getCategoryTariff($parent['category_id'],
                                                                                $pr_zone,
                                                                                $type);
                                    $parent_id = $parent['parent_id'];
                                }
                            }
                        }

                        if ($category_tariff) {
                            $tariff = $category_tariff;
                        }
                        else {
                            $tariff = array('prcategory123_tariff_id' => 'default', 'rate' => $pr_zone['rate'], 'cost' => $pr_zone['cost'], 'cost_basis' => $pr_zone['cost_basis']);
                        }

                        if (isset($tariffs[$tariff['prcategory123_tariff_id']])) {
                            $tariffs[$tariff['prcategory123_tariff_id']]['quantity'] += $product['quantity'];
                        }
                        else {
                            $tariffs[$tariff['prcategory123_tariff_id']] = $tariff;
                            $tariffs[$tariff['prcategory123_tariff_id']]['quantity'] = $product['quantity'];
                        }
                    }
                }

                $total = 0;

                foreach ($tariffs as $tariff) {

                    $success = false;

                    if ($tariff['rate']) {

                        $rates = explode(',', $tariff['rate']);

                        // Найдем стоимость доставки в зависимости от веса
                        foreach ($rates as $rate) {
                            $data = explode(':', $rate);

                            if ($data[0] >= $tariff['quantity']) {
                                if (isset($data[1])) {
                                    $total += $data[1];
                                    $success = true;
                                    break;
                                }
                            }
                        }
                    }

                    if (!$success && $tariff['cost']) {

                        $total += ($tariff['cost'] * $tariff['quantity'] + $tariff['cost_basis']);
                        $success = true;
                    }

                    // Если не удалось рассчитать тариф
                    if (!$success) {
                        return false;
                    }
                }

                return array('total' => $total);
            }
        }

        return false;
    }

    private function getCategoryTariff($category_id, $pr_zone, $type) {

        if ($type == 'zone') {
            $table = 'prcategory123_zone_category';
            $field = 'prcategory123_zone_id';
        }
        else {
            $table = 'prcategory123_geozone_category';
            $field = 'prcategory123_geozone_id';
        }

        return $this->db->query("SELECT t.* FROM " . $table . " z LEFT JOIN prcategory123_tariff t ON z.tariff_id = t.prcategory123_tariff_id WHERE " . $field . " = '" . $pr_zone[$field] . "' AND category_id = " . $category_id . " LIMIT 1")->row;
    }

    private function getCategory($category_id) {

        return $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE category_id = " . $category_id)->row;
    }
}
