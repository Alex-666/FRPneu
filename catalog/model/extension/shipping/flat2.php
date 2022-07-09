<?php
class ModelExtensionShippingFlat2 extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/flat2');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_flat2_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_flat2_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
        // Если купон по категориям.
        $coupon_cost = false;
        $this->load->model('extension/total/coupon');
        $coupon = $this->model_extension_total_coupon->getCoupon($this->session->data['coupon']);

        foreach ($this->cart->getProducts() as $product) {
            if (is_array($coupon["product"])) {
                if (in_array((int)$product['product_id'], $coupon["product"]) && $coupon["type"] == "P") {
                    $coupon_cost = true;
                } else {
                    $coupon_cost = false;
                    break;
                }
            }else {
                if ($product['product_id'] == $coupon["product"]){
                    $coupon_cost = true;
                } else {
                    $coupon_cost = false;
                    break;
                }

            }
        }

        $method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['flat2'] = array(
				'code'         => 'flat2.flat2',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('shipping_flat2_cost'),
                'coupon_cost'  => (!$coupon_cost ? $this->config->get('shipping_flat2_cost') : 0),
				'tax_class_id' => $this->config->get('shipping_flat2_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('shipping_flat2_cost'), $this->config->get('shipping_flat2_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'flat2',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_flat2_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}