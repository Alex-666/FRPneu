<?php
class ModelCheckoutOrder extends Model {
	public function addOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('extension/total/voucher');

		// Vouchers
		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$order_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_extension_total_voucher->addVoucher($order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
			}
		}

		// Totals
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}


	  $this->session->data['remarketing_order_id'] = $order_id;
	  setcookie('remarketing_order_id', $order_id, time() + 24 * 3600, '/');
	  $this->load->model('tool/remarketing');
	  $this->model_tool_remarketing->getOrderRemarketing($order_id);
	  
		return $order_id;
	}

	public function editOrder($order_id, $data) {
		// Void the order first
		$this->addOrderHistory($order_id, 0);

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(json_encode($data['custom_field'])) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(json_encode($data['payment_custom_field'])) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(json_encode($data['shipping_custom_field'])) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('extension/total/voucher');

		$this->model_extension_total_voucher->disableVoucher($order_id);

		// Vouchers
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$order_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_extension_total_voucher->addVoucher($order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
			}
		}

		// Totals
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}
	}

	public function deleteOrder($order_id) {
		// Void the order first
		$this->addOrderHistory($order_id, 0);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "order_recurring` `or`, `" . DB_PREFIX . "order_recurring_transaction` `ort` WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE order_id = '" . (int)$order_id . "'");

		// Gift Voucher
		$this->load->model('extension/total/voucher');

		$this->model_extension_total_voucher->disableVoucher($order_id);
	}


                public function getGTAG($order_id) {
                $order_query = $this->db->query("SELECT o.order_id, o.store_name, o.language_id, o.currency_code, o.currency_value, o.total FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");
                if ($order_query->num_rows) {
                $language_id = $order_query->row['language_id'];
                $currency_value = $order_query->row['currency_value'];

                $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
                foreach ($order_product_query->rows as $product) {
                $i = 0;
                $category_data = '';
                $category_query = $this->db->query("SELECT cd.name FROM `" . DB_PREFIX . "product_to_category` pc INNER JOIN `" . DB_PREFIX . "category_description` cd ON pc.category_id = cd.category_id WHERE pc.product_id = '" . (int)$product['product_id'] . "' AND cd.language_id = '" . (int)$language_id . "'");
                foreach ($category_query->rows as $category) { $i++;
                if ($i <= 5) {
                $category_data .= $category['name'] . '/';
                }
                }
                $category_data = rtrim($category_data, '/');

                $option_data = '';
                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
                foreach ($order_option_query->rows as $option) {
                if ($option['type'] != 'file') {
                $option_data .= $option['name'] . ': ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . ' - ';
                }
                }
                $option_data = rtrim($option_data, ' - ');

                $products[] = array(
                'id' => $product['model'],
                'name' => $product['name'],
                'category' => $category_data,
                'variant' => $option_data,
                'price' => (($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)) * $currency_value),
                'quantity' => $product['quantity']
                );
                }

                $order_shipping = 0;
                $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
                foreach ($order_total_query->rows as $total) {
                if ($total['value'] > 0) {
                if ($total['code'] == "shipping") {
                $order_shipping += $total['value'];
                }
                If ($total['code'] == "sub_total") {
                $value_s_total = $total['value'];
                }
                }
                }

                return array(
                'transaction_id' => $order_query->row['order_id'],
                'affiliation' => $order_query->row['store_name'],
                'value' => round($value_s_total * $currency_value),
                //'value' => $order_query->row['total'] * $currency_value,
                'currency' => $order_query->row['currency_code'],
                'shipping' => $order_shipping * $currency_value,
                'items' => $products
                );
                } else {
                return false;
                }
                }
                
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return false;
		}
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
		
		return $query->rows;
	}
	
	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->rows;
	}
	
	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
		
		return $query->rows;
	}	
			
	public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false, $override = false) {
		$order_info = $this->getOrder($order_id);
		
		if ($order_info) {
			// Fraud Detection
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

			if ($customer_info && $customer_info['safe']) {
				$safe = true;
			} else {
				$safe = false;
			}

			// Only do the fraud check if the customer is not on the safe list and the order status is changing into the complete or process order status
			if (!$safe && !$override && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Anti-Fraud
				$this->load->model('setting/extension');

				$extensions = $this->model_setting_extension->getExtensions('fraud');

				foreach ($extensions as $extension) {
					if ($this->config->get('fraud_' . $extension['code'] . '_status')) {
						$this->load->model('extension/fraud/' . $extension['code']);

						if (property_exists($this->{'model_extension_fraud_' . $extension['code']}, 'check')) {
							$fraud_status_id = $this->{'model_extension_fraud_' . $extension['code']}->check($order_info);
	
							if ($fraud_status_id) {
								$order_status_id = $fraud_status_id;
							}
						}
					}
				}
			}

			// If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Redeem coupon, vouchers and reward points
				$order_totals = $this->getOrderTotals($order_id);

				foreach ($order_totals as $order_total) {
					$this->load->model('extension/total/' . $order_total['code']);

					if (property_exists($this->{'model_extension_total_' . $order_total['code']}, 'confirm')) {
						// Confirm coupon, vouchers and reward points
						$fraud_status_id = $this->{'model_extension_total_' . $order_total['code']}->confirm($order_info, $order_total);
						
						// If the balance on the coupon, vouchers and reward points is not enough to cover the transaction or has already been used then the fraud order status is returned.
						if ($fraud_status_id) {
							$order_status_id = $fraud_status_id;
						}
					}
				}

				// Stock subtraction
				$order_products = $this->getOrderProducts($order_id);

				foreach ($order_products as $order_product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$order_options = $this->getOrderOptions($order_id, $order_product['order_product_id']);

					foreach ($order_options as $order_option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
				
				// Add commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
					$this->load->model('account/customer');

					if (!$this->model_account_customer->getTotalTransactionsByOrderId($order_id)) {
						$this->model_account_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
					}
				}
			}

			// Update the DB with the new statuses
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

	    // remarketing all in one
		$this->load->model('tool/remarketing');
		
		if ($this->config->get('remarketing_status') && !$this->model_tool_remarketing->isBot()) {
			
			$this->load->model('catalog/product');
			$ecommerce_currency = $this->config->get('remarketing_ecommerce_currency'); 
			$facebook_currency = $this->config->get('remarketing_facebook_currency'); 
			$ecommerce_info = $this->model_tool_remarketing->getOrderRemarketing($order_id);
			$fb_time = time(); 
			
	    if ($this->config->get('remarketing_ecommerce_measurement_status')) {
			$refund_status = $this->config->get('remarketing_refund_status');
			if (is_array($refund_status) && in_array($order_status_id, $refund_status)) {

			if (!empty($this->session->data['uuid'])) {
				$uuid = $this->session->data['uuid'];		
				$ecommerce_data = [
					'v'   => 1,
					'tid' => $this->config->get('remarketing_ecommerce_analytics_id'),
					'cid' => $uuid,
					't'   => 'event',
					'ti'  => $order_info['order_id'],
					'ec'  => 'Enhanced Ecommerce',
					'ea'  => 'Refund', 
					'ni'  => 1,
					'pa'  => 'refund'
				]; 
				 
				if ($order_info['customer_id']) {
					$ecommerce_data['uid'] = $order_info['customer_id']; 
					unset($ecommerce_data['cid']);
				}
				
				$this->model_tool_remarketing->sendEcommerce($ecommerce_data);
				}
			}
		
		$send_status = $this->config->get('remarketing_ecommerce_send_status');
		
		if (($this->config->get('remarketing_ecommerce_resend_status') != '0' && $this->config->get('remarketing_ecommerce_resend_status') == $order_status_id) || (is_array($send_status) && in_array($order_status_id, $send_status) && $ecommerce_info['sent_data']['ecommerce'] == '0000-00-00 00:00:00')) {
				$data['ecommerce_totalvalue'] = $this->currency->format($order_info['total'], $ecommerce_currency, '', false); 

				if (!empty($this->session->data['uuid'])) {
				$uuid = $this->session->data['uuid'];		
				$ecommerce_data = [
					'v'   => 1,
					'tid' => $this->config->get('remarketing_ecommerce_analytics_id'),
					'cid' => $uuid,
					't'   => 'event',
					'ti'  => $ecommerce_info['order_id'],
					'ta'  => $ecommerce_info['store_name'],
					'ts'  => $ecommerce_info['shipping'],
					'tr'  => $data['ecommerce_totalvalue'],
					'ec'  => 'Enhanced Ecommerce',
					'ea'  => 'Purchase', 
					'ni'  => 1,
					'cu'  => $ecommerce_currency,
					'pa'  => 'purchase'
				]; 
				
				if ($this->customer->isLogged()) {
					$ecommerce_data['uid'] = $this->customer->isLogged();
					unset($ecommerce_data['cid']);
				}
				
				if ($ecommerce_info['coupon']) {
					$ecommerce_data['tcc'] = $ecommerce_info['coupon'];
				}

				$i = 1;
				if ($ecommerce_info['products']) {
					foreach ($ecommerce_info['products'] as $product){
						$ecommerce_data['pr' . $i .'nm'] = $product['name'];
						$ecommerce_data['pr' . $i .'id'] = ($this->config->get('remarketing_ecommerce_measurement_id') == 'id') ? $product['product_id'] : $product['model'];
						$ecommerce_data['pr' . $i .'pr'] = $product['ecommerce_price'];
						$ecommerce_data['pr' . $i .'br'] = $product['product_info']['manufacturer'];
						$ecommerce_data['pr' . $i .'ca'] = $product['category'];
						if (!empty($product['variant'])) $ecommerce_data['pr' . $i .'va'] = $product['variant'];
						$ecommerce_data['pr' . $i .'qt'] = $product['quantity'];
						$i++;
					}
				}
				
					$this->model_tool_remarketing->sendEcommerce($ecommerce_data);
					$this->model_tool_remarketing->setSend($order_id, 'ecommerce');
				}
		}
		
		if ($this->config->get('remarketing_ecommerce_delete_status') != '0' && $this->config->get('remarketing_ecommerce_delete_status') == $order_status_id) {
			$data['ecommerce_totalvalue'] = $this->currency->format($order_info['total'], $ecommerce_currency, '', false); 

				if (!empty($this->session->data['uuid'])) {
				$uuid = $this->session->data['uuid'];		
				$ecommerce_data = [
					'v'   => 1,
					'tid' => $this->config->get('remarketing_ecommerce_analytics_id'),
					'cid' => $uuid,
					't'   => 'event',
					'ti'  => $ecommerce_info['order_id'],
					'ta'  => $ecommerce_info['store_name'],
					'ts'  => $ecommerce_info['shipping'] * -1,
					'tr'  => $data['ecommerce_totalvalue'] * -1,
					'ec'  => 'Enhanced Ecommerce',
					'ea'  => 'Purchase', 
					'ni'  => 1,
					'cu'  => $ecommerce_currency,
					'pa'  => 'purchase'
				]; 
				
				if ($this->customer->isLogged()) {
					$ecommerce_data['uid'] = $this->customer->isLogged();
					unset($ecommerce_data['cid']);
				}
				
				if ($ecommerce_info['coupon']) {
					$ecommerce_data['tcc'] = $ecommerce_info['coupon'];
				}

				$i = 1;
				if ($ecommerce_info['products']) {
					foreach ($ecommerce_info['products'] as $product){
						$ecommerce_data['pr' . $i .'nm'] = $product['name'];
						$ecommerce_data['pr' . $i .'id'] = ($this->config->get('remarketing_ecommerce_measurement_id') == 'id') ? $product['product_id'] : $product['model'];
						$ecommerce_data['pr' . $i .'pr'] = $product['ecommerce_price'];
						$ecommerce_data['pr' . $i .'br'] = $product['product_info']['manufacturer'];
						$ecommerce_data['pr' . $i .'ca'] = $product['category'];
						if (!empty($product['variant'])) $ecommerce_data['pr' . $i .'va'] = $product['variant'];
						$ecommerce_data['pr' . $i .'qt'] = $product['quantity'] * -1;
						$i++;
					}
				}
				
				$this->model_tool_remarketing->sendEcommerce($ecommerce_data);
				}
			}
		}
			if ($this->config->get('remarketing_ecommerce_ga4_measurement_status')) {
				$ga4_send_status = $this->config->get('remarketing_ecommerce_ga4_send_status');
				$ga4_refund_status = $this->config->get('remarketing_ecommerce_ga4_refund_status');
			
				$event_name = false;
				if (is_array($ga4_send_status) && in_array($order_status_id, $ga4_send_status) && $ecommerce_info['sent_data']['ecommerce_ga4'] == '0000-00-00 00:00:00') {
					$event_name = 'purchase';
				}
				if (is_array($ga4_refund_status) && in_array($order_status_id, $ga4_refund_status)) {
					$event_name = 'refund';
				}
				
				if ($event_name) {
					if (!empty($this->session->data['uuid'])) {
					$uuid = $this->session->data['uuid'];		 
					
					$params = [];
					$params['affiliation'] = $ecommerce_info['store_name'];
					if ($ecommerce_info['coupon']) {
						$params['coupon'] = $ecommerce_info['coupon'];
					}
					
					$params['currency'] = $ecommerce_currency;
					$items = [];
					foreach ($ecommerce_info['products'] as $product) {
						$item = [
							// Google refuses id 'item_id' => ($this->config->get('remarketing_ecommerce_ga4_measurement_id') == 'id') ? $product_info['product_id'] : $product_info['model'],
							'item_name' => $product['name'],
							'quantity' => $product['quantity'],
							'affiliation' => $ecommerce_info['store_name'],
							'price' => $product['ecommerce_price'],
							'currency' => $ecommerce_currency,
						];
						if (!empty($product['product_info']['manufacturer'])) {
							$item['item_brand'] = $product['product_info']['manufacturer'];
						}
						if (!empty($product['category'])) {
							$item['item_category'] = $product['category'];
						}
						if (!empty($product['variant'])) {
							$item['item_variant'] = $product['variant'];
						}
						
						$items[] = $item;
					}					
					$params['items'] = $items;
					
					$params['transaction_id'] = $ecommerce_info['order_id'];
					if ($ecommerce_info['shipping']) {
						$params['shipping'] = $ecommerce_info['shipping'];
					}
					$params['value'] = $ecommerce_info['ecommerce_total'];
					
					$ecommerce_data = [
						'client_id' => $uuid,
						'events' => [[
							'name' => $event_name,
							'params' => $params
							]]
					];
					
					
					$this->model_tool_remarketing->sendEcommerceGa4($ecommerce_data);
					$this->model_tool_remarketing->setSend($order_id, 'ecommerce_ga4');
				}
			}
		}

		$facebook_send_status = $this->config->get('remarketing_facebook_send_status');
		  
		if ($this->config->get('remarketing_facebook_status') && $this->config->get('remarketing_facebook_server_side') && $this->config->get('remarketing_facebook_token') && (($this->config->get('remarketing_facebook_resend_status') != '0' && $this->config->get('remarketing_facebook_resend_status') == $order_status_id) || ((is_array($facebook_send_status) && in_array($order_status_id, $facebook_send_status) && $ecommerce_info['sent_data']['facebook'] == '0000-00-00 00:00:00')))) {
			$facebook_data['event_name'] = 'Purchase';
			$fb_products = [];
			$num_items = 0;
            
			foreach ($ecommerce_info['products'] as $product) {
				$fb_products[] = [
					'id'         => ($this->config->get('remarketing_facebook_id') == 'id' ? $product['product_id'] : $product['model']),
					'quantity'   => $product['quantity'],
					'item_price' => $product['facebook_price']
				];
				$num_items += $product['quantity'];
			}
			$facebook_data['custom_data'] = [
				'value'        => $ecommerce_info['facebook_total'],
				'currency'     => $facebook_currency,
				'contents'     => $fb_products,
				'num_items'    => $num_items,
				'content_type' => 'product',
				'opt_out'      => false
			];
			
			$facebook_data['time'] = $fb_time;
			
			$this->model_tool_remarketing->sendFacebook($facebook_data, $ecommerce_info);
			
			if ($this->config->get('remarketing_facebook_lead')) {
				$facebook_data = [];	
				$facebook_data['event_name'] = 'Lead';
				$facebook_data['custom_data'] = [
					'value'        => $ecommerce_info['facebook_total'],
					'currency'     => $facebook_currency,
					'opt_out'      => false
				];
			
				$facebook_data['time'] = $fb_time;
				$this->model_tool_remarketing->sendFacebook($facebook_data, $ecommerce_info);
			}
			
			$this->model_tool_remarketing->setSend($order_id, 'facebook');
		} 
		
			if ($this->config->get('remarketing_telegram_status')) {
				$tg_send_status = $this->config->get('remarketing_telegram_send_status');
				if (is_array($tg_send_status) && in_array($order_status_id, $tg_send_status) && $ecommerce_info['sent_data']['telegram'] == '0000-00-00 00:00:00') {
					$this->model_tool_remarketing->sendTelegram($order_id);
					$this->model_tool_remarketing->setSend($order_id, 'telegram');
				}
			}
			
			if ($this->config->get('remarketing_esputnik_status')) {
				$event_type = false;
				$esputnik_status = false;
				$initialized_status = $this->config->get('remarketing_esputnik_initialized_status');
				if (is_array($initialized_status) && in_array($order_status_id, $initialized_status) && $ecommerce_info['sent_data']['esputnik'] == '0000-00-00 00:00:00') {
					$event_type = 'orderCreated';
					$esputnik_status = 'INITIALIZED';
				}
				
				$in_progress_status = $this->config->get('remarketing_esputnik_inprogress_status');
				if (is_array($in_progress_status) && in_array($order_status_id, $in_progress_status)) {
					$event_type = 'orderUpdated';
					$esputnik_status = 'IN_PROGRESS';
				}
				
				$delivered_status = $this->config->get('remarketing_esputnik_delivered_status');
				if (is_array($delivered_status) && in_array($order_status_id, $delivered_status)) {
					$event_type = 'orderDelivered';
					$esputnik_status = 'DELIVERED';
				}
				
				$cancelled_status = $this->config->get('remarketing_esputnik_cancelled_status');
				if (is_array($cancelled_status) && in_array($order_status_id, $cancelled_status)) {
					$event_type = 'orderCancelled';
					$esputnik_status = 'CANCELLED';
				}

				if ($event_type && $esputnik_status && !empty($ecommerce_info['email'])) {
					$event = new stdClass();
					$event->eventTypeKey = $event_type;
					$event->keyValue = $ecommerce_info['email'];
					$event->params = []; 
					$event->params[] = ['name' => 'phone', 'value' => $ecommerce_info['telephone']];
					$event->params[] = ['name' => 'externalOrderId', 'value' => $ecommerce_info['order_id']];
					if (!empty($ecommerce_info['customer_id'])) {
						$event->params[] = ['name' => 'externalCustomerId', 'value' => $ecommerce_info['customer_id']];
					}
					$event->params[] = ['name' => 'totalCost', 'value' => $ecommerce_info['default_total']];
					$event->params[] = ['name' => 'status', 'value' => $esputnik_status];
					$event->params[] = ['name' => 'date', 'value' => date('Y-m-d\TH:i:s') . '+02:00'];
					if (!empty($ecommerce_info['firstname'])) {
						$event->params[] = ['name' => 'firstName', 'value' => $ecommerce_info['firstname']];
					}
					if (!empty($ecommerce_info['lastname'])) {
						$event->params[] = ['name' => 'lastName', 'value' => $ecommerce_info['lastname']];
					}
					if (!empty($ecommerce_info['order_info'][$this->config->get('remarketing_esputnik_ttn_field')])) {
						$event->params[] = ['name' => 'ttn', 'value' => $ecommerce_info['order_info'][$this->config->get('remarketing_esputnik_ttn_field')]];
					}
					$event->params[] = ['name' => 'currency', 'value' => $this->session->data['currency']];
					if ($ecommerce_info['shipping']) {
						$event->params[] = ['name' => 'shipping', 'value' => $ecommerce_info['shipping']];
					}
					$event->params[] = ['name' => 'deliveryMethod', 'value' => $order_info['shipping_method']];
					$event->params[] = ['name' => 'paymentMethod', 'value' => $order_info['payment_method']];
					
					$format = $this->config->get('remarketing_esputnik_address_format');
					
					$find = [
						'{firstname}',
						'{lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}'
					];
	
					$replace = [ 
						'firstname' => $order_info['shipping_firstname'],
						'lastname'  => $order_info['shipping_lastname'],
						'company'   => $order_info['shipping_company'],
						'address_1' => $order_info['shipping_address_1'],
						'address_2' => $order_info['shipping_address_2'],
						'city'      => $order_info['shipping_city'],
						'postcode'  => $order_info['shipping_postcode'],
						'zone'      => $order_info['shipping_zone'],
						'zone_code' => $order_info['shipping_zone_code'],
						'country'   => $order_info['shipping_country']
					];
	
					$esputnik_address = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));
					$event->params[] = ['name' => 'deliveryAddress', 'value' => $esputnik_address];
					$items = [];
					
					$this->load->model('tool/image');
					foreach ($ecommerce_info['products'] as $product) {
						if ($product['product_info']['image']) {
							$product_image = $this->model_tool_image->resize($product['product_info']['image'], 200, 200);
						} else {
							$product_image = $this->model_tool_image->resize('no_image.jpg', 200, 200);
						}
						$items[] = [
							'externalItemId' => $product['product_id'],
							'name'           => $product['name'],
							'category'       => $product['category'],
							'quantity'       => $product['quantity'],
							'cost'           => $product['price'],
							'url'            => $this->url->link('product/product', 'product_id=' . $product['product_id']),
							'imageUrl'       => $product_image
						];
					}
					
					if (!isset($this->session->data['esputnik_uniq'])) {
						$this->session->data['esputnik_uniq'] = uniqid();
					}
					$event->params[] = ['name' => 'recycleStateId', 'value' => $this->session->data['esputnik_uniq']];
					$event->params[] = ['name' => 'items', 'value' => json_encode($items)];
					$this->model_tool_remarketing->sendEsputnik($event);
					if ($esputnik_status == 'INITIALIZED') {
						$this->model_tool_remarketing->setSend($order_id, 'esputnik');
					}
				}
			}
		}
		

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

			// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Restock
				$order_products = $this->getOrderProducts($order_id);

				foreach($order_products as $order_product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

					$order_options = $this->getOrderOptions($order_id, $order_product['order_product_id']);

					foreach ($order_options as $order_option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}

				// Remove coupon, vouchers and reward points history
				$order_totals = $this->getOrderTotals($order_id);
				
				foreach ($order_totals as $order_total) {
					$this->load->model('extension/total/' . $order_total['code']);

					if (property_exists($this->{'model_extension_total_' . $order_total['code']}, 'unconfirm')) {
						$this->{'model_extension_total_' . $order_total['code']}->unconfirm($order_id);
					}
				}

				// Remove commission if sale is linked to affiliate referral.
				if ($order_info['affiliate_id']) {
					$this->load->model('account/customer');
					
					$this->model_account_customer->deleteTransactionByOrderId($order_id);
				}
			}

			$this->cache->delete('product');
		}
	}
}