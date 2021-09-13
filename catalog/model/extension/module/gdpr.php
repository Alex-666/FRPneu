<?php
class ModelExtensionModuleGdpr extends Model {

	/**
	 * Add a new GDPR request
	 * @param [array] $data
	 */
	public function addRequest($data) {
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "gdpr_request SET email = '" . $this->db->escape($data['email']) . "', status = '0', http_user_agent = '" . $this->db->escape($data['http_user_agent']) . "', http_accept_language = '" . $this->db->escape($data['http_accept_language']) . "', server_addr = '" . $this->db->escape($data['server_addr']) . "', remote_addr = '" . $this->db->escape($data['remote_addr']) . "', confirmation_string = '" . $this->db->escape($data['confirmation_string']) . "', request_type = '" . $data['request_type'] . "', request_time = '" . $this->db->escape($data['request_time']) . "'");

		$request_id = $this->db->getLastID();

		if($request_id) {
			return $request_id;
		} else {
			return false;
		}
	}

	/**
	 * Anonymize personal data stored in the customer table
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function anonymizeCustomerData($customer_id) {

		// Get customer data
		$query = $this->db->query("SELECT firstname, lastname, email, telephone, fax, cart, wishlist, custom_field, ip FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		$customer = $query->row;

		$garbled = array(
			'firstname' => $this->garble($customer['firstname']),
			'lastname' => $this->garble($customer['lastname']),
			'email' => $this->garble($customer['email']),
			'telephone' => $this->garble($customer['telephone']),
			'fax' => $this->garble($customer['fax']),
			'cart' => '',
			'wishlist' => '',
			'custom_field' => '',
			'ip' => $this->garble($customer['ip']),
		);

		// Update customer with anonymized data
		$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $garbled['firstname'] . "', lastname = '" . $garbled['lastname'] . "', email = '" . $garbled['email'] . "', telephone = '" . $garbled['telephone'] . "', fax = '" . $garbled['fax'] . "', cart = '" . $garbled['cart'] . "', wishlist = '" . $garbled['wishlist'] . "', custom_field = '" . $garbled['custom_field'] . "', ip = '" . $garbled['ip'] . "' WHERE customer_id = '" . (int)$customer_id . "'");

	}

	/**
	 * Delete customer activity logs
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerActivityLogs($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_activity WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete customer addresses
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerAddresses($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete customer GDPR requests
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerGdprRequests($customer_email) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "gdpr_request WHERE email = '" . $this->db->escape($customer_email) . "' AND request_type = '1'");
	}

	/**
	 * Delete customer history
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerHistory($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete customer IP addresses
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerIps($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete customer Login logs
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerLoginLogs($customer_email) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape($customer_email) . "'");
	}

	/**
	 * Delete customer rewards
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerRewards($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete customer transactions
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerTransactions($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete customer wishlist
	 * @param  [int] $customer_id
	 * @return void
	 */
	public function deleteCustomerWishlist($customer_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Breach Notifications by status
	 * @param  [int] $status
	 * @return [array]
	 */
	public function getBreachCustomerEmailNotificationByStatus($status) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gdpr_breach_notification_customers_emailed WHERE status='" . $status . "'");

		return $query->rows;
	}

	/**
	 * Get single record of breach notification for Data Protection Commissioner Office
	 * @param  [int] $breach_id
	 * @return [array]
	 */
	public function getBreachNotification($breach_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gdpr_breach_notification WHERE breach_id ='" . (int)$breach_id . "'");

		if(!empty($query->row)) {
			return $query->row;
		} else {
			return false;
		}
	}

	/**
	 * Get confirmation string of a request with a given request code
	 * @param  [string] $request_code [request_id + substr(timestamp * 7, 0, 12) + substr(request_id * 6, 0, 5)]
	 * @return [string|bool] [confirmation string if request code exist, else false]
	 */
	public function getConfirmationString($request_code) {
		$query = $this->db->query("SELECT request_id, email, confirmation_string FROM " . DB_PREFIX . "gdpr_request WHERE code = '" . $this->db->escape($request_code) . "'");

		if(!empty($query->row['confirmation_string'])) {
			return $query->row;
		} else {
			return false;
		}
	}

	/**
	 * Get customer addresses
	 * @param  [int] $customer_id
	 * @return [array]
	 */
	public function getCustomerAddresses($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . $customer_id .  "'");

		$customer_addresses = array();
		$index = 1;
		foreach($query->rows as $address) {
			$customer_addresses[$index] = $address;

			$name = $address['firstname'] . ' ' . $address['lastname'] . ', ';
			$company = !empty($address['company']) ? $address['company'] . ', ' : '';
			$address_text = $address['address_1'];
			if(!empty($address['address_2'])) {
				$address_text .= ', ' . $address['address_2'];
			}
			if(!empty($address['postcode'])) {
				$address_text .= ', ' . $address['postcode'];
			}
			if(!empty($address['city'])) {
				$address_text .= ', ' . $address['city'];
			}
			$zone_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = '" . $address['zone_id'] . "'");
			$country_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "country WHERE country_id = '" . $address['country_id'] . "'");

			$text_format = $name . $company . $address_text;

			if(!empty($zone_query->row['name'])) {
				$text_format .= ', ' . $zone_query->row['name'];
			}

			if(!empty($country_query->row['name'])) {
				$text_format .= ', ' . $country_query->row['name'];
			}

			$customer_addresses[$index]['text'] = $text_format;
			$index++;
		}

		return $customer_addresses;
	}

	/**
	 * Get customer data by email
	 * @param  [string] $email
	 * @return [array]  [customer data from oc_customer]
	 */
	public function getCustomerData($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) .  "'");

		if(!empty($query->row)) {
			return $query->row;
		} else {
			return array();
		}

	}

	/**
	 * Get customer GDPR requests history
	 * @param  [string] $email
	 * @return [array] Array of GDPR requests or empty array
	 */
	public function getCustomerGdprRequests($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gdpr_request WHERE email = '" . $this->db->escape($email) .  "'");

		if(!empty($query->rows)) {
			return $query->rows;
		} else {
			return array();
		}

	}

	/**
	 * Get customer information
	 * @param  [int] $customer_id
	 * @return [array] [customer information from: oc_customer_activity,
	 * oc_customer_history, oc_customer_ip, oc_customer_login, oc_customer_online ]
	 */
	public function getCustomerInformation($customer_id, $email) {

		// Activity logs
		$activity_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_activity WHERE customer_id = '" . $customer_id .  "'");
		// History
		$history_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . $customer_id .  "'");
		// IP
		$ip_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . $customer_id .  "'");
		// Login
		if (VERSION > '2.0.1.0') {
			$login_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape($email).  "'");
		}
		// Online
		$online_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_online WHERE customer_id = '" . $customer_id .  "'");

		$customer_information = array(
			'activity' => !empty($activity_query->rows) ? $activity_query->rows : array(),
			'history' => !empty($history_query->rows) ? $history_query->rows : array(),
			'ip' => !empty($ip_query->rows) ? $ip_query->rows : array(),
			'login' => !empty($login_query->rows) ? $login_query->rows : array(),
			'online' => !empty($online_query->rows) ? $online_query->rows : array(),
		);

		return $customer_information;
	}

	/**
	 * Get customer orders
	 * @param  [int] $customer_id
	 * @return [array] customer orders and product ordered
	 */
	public function getCustomerOrders($customer_id) {
		$customer_orders = array();

		// Get all customer orders
		$orders_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . $customer_id .  "'");

		if(!empty($orders_query->rows)) {
			foreach ($orders_query->rows as $order) {
				$customer_orders[$order['order_id']]['order'] = $order;

				// Get all order products
				$order_products_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order['order_id'] .  "'");
				$customer_orders[$order['order_id']]['products'] = $order_products_query->rows;
			}
		}

		return $customer_orders;
	}

	/**
	 * Get customer orders by email
	 * @param  [string] $email
	 * @return [array] customer orders and product ordered
	 */
	public function getCustomerOrdersByEmail($email) {
		$customer_orders = array();

		// Get all customer orders
		$orders_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE email = '" . $this->db->escape($email) .  "'");

		if(!empty($orders_query->rows)) {
			foreach ($orders_query->rows as $order) {
				$customer_orders[$order['order_id']]['order'] = $order;

				// Get all order products
				$order_products_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . $order['order_id'] .  "'");
				$customer_orders[$order['order_id']]['products'] = $order_products_query->rows;
			}
		}

		return $customer_orders;
	}

	/**
	 * Get customer product reviews
	 * @param  [int] $customer_id
	 * @return [array]
	 */
	public function getCustomerProductReviews($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "review WHERE customer_id = '" . (int)$customer_id .  "'");

		if(!empty($query->rows)) {
			return $query->rows;
		} else {
			return array();
		}
	}

	/**
	 * Get customer rewards
	 * @param  [int] $customer_id
	 * @return [array]
	 */
	public function getCustomerRewards($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . $customer_id .  "'");

		if(!empty($query->rows)) {
			return $query->rows;
		} else {
			return array();
		}
	}

	/**
	 * Get customer transactions
	 * @param  [int] $customer_id
	 * @return [array]
	 */
	public function getCustomerTransactions($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . $customer_id .  "'");

		if(!empty($query->rows)) {
			return $query->rows;
		} else {
			return array();
		}
	}

	/**
	 * Get customer wishlist
	 * @param  [int] $customer_id
	 * @return [array]
	 */
	public function getCustomerWishlist($customer_id) {
		$query = $this->db->query("SELECT cw.*, p.model, pd.name FROM " . DB_PREFIX . "customer_wishlist cw LEFT JOIN " . DB_PREFIX . "product p ON cw.product_id = p.product_id LEFT JOIN " . DB_PREFIX . "product_description pd ON cw.product_id = pd.product_id WHERE customer_id = '" . $customer_id .  "'");

		if(!empty($query->rows)) {
			return $query->rows;
		} else {
			return array();
		}
	}

	/**
	 * Get restriction of processing preference for the customer.
	 * @param  int $customer_id
	 * @return boolean
	 */
	public function getRestrictionOfProcessingStatus($customer_id) {
		$query = $this->db->query("SELECT restriction_status FROM " . DB_PREFIX . "gdpr_restriction_of_processing WHERE customer_id = '" . (int)$customer_id . "'");

		if(empty($query->row['restriction_status']) || !$query->row['restriction_status']) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	 * Get a request code generated with request_id and timestamp
	 * @param  [int] $request_id
	 * @param  [int] $timestamp  [timestamp of the request]
	 * @return [string]
	 */
	public function getRequestCode($request_id, $timestamp) {

			$request_code = $request_id . substr($timestamp * 7, 0, 12) . substr($request_id * 6, 0, 5);

			return $request_code;
	}

	/**
	 * Get GDPR request status
	 * @param  [string] $request_code
	 * @return [int]
	 */
	public function getRequestStatus($request_code) {
		$query = $this->db->query("SELECT status FROM " . DB_PREFIX . "gdpr_request WHERE code = '" . $this->db->escape($request_code) . "'");

		if(!empty($query->row['status'])) {
			return (int)$query->row['status'];
		} else {
			return 0;
		}
	}

	/**
	 * Check if customer is allowed to make a GDPR request and did not exceed the
	 * maximum of daily requests permitted.
	 * @param  [string]  $email
	 * @return boolean
	 */
	public function isAllowedGdprRequest($email) {
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		$query = $this->db->query("SELECT COUNT(request_id) as requests_made FROM " . DB_PREFIX . "gdpr_request WHERE email = '" . $this->db->escape($email) . "' AND request_time > NOW() - INTERVAL 1 DAY");

		// Number of requests in the last 24h
		$requests_made = $query->row['requests_made'];

		// Max allowed requests
		$max_allowed_requests = $settings['module_gdpr_max_requests_day'];

		if($requests_made >= $max_allowed_requests) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Check if email address exists in the customer table
	 * @param  [string]  $email
	 * @return boolean
	 */
	public function isExistingCustomerEmail($email) {
		$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");

		if(!empty($query->row['customer_id'])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if email address exists in the order table
	 * @param  [string]  $email
	 * @return boolean
	 */
	public function isExistingOrderEmail($email) {
		$query = $this->db->query("SELECT COUNT(order_id) AS existing_orders FROM " . DB_PREFIX . "order WHERE email = '" . $this->db->escape($email) . "'");

		if($query->row['existing_orders'] > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Store a record of the customer accepting the store policy when registering
	 * @param  [array] $data
	 * @return void
	 */
	public function storePolicyAcceptance($data) {
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "gdpr_policy_accepted SET customer_id = '" . $data['customer_id'] . "', customer_email = '" . $this->db->escape($data['customer_email']) . "', policy_id = '" . (int)$data['policy_id'] . "', policy_name = '" . $this->db->escape($data['policy_name']) . "', policy_content = '" . $this->db->escape($data['policy_content']) . "', date_accepted = '" . $data['date_accepted'] . "'");
	}

	/**
	 * Update Breach Notifications status
	 * @param  [int] $id
	 * @param  [int] $status
	 * @return void
	 */
	public function updateBreachCustomerEmailNotificationStatus($id, $status) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "gdpr_breach_notification_customers_emailed SET status = '" . (int)$status . "', date_updated = NOW() WHERE customer_notification_id='" . (int)$id . "'");
	}

	/**
	 * Update restriction of processing record for the customer
	 * @param  [int] $customer_id
	 * @param  [int] $restriction_status
	 * @return void
	 */
	public function updateRestrictionOfProcessing($customer_id, $restriction_status) {
		$this->db->query("REPLACE INTO "  . DB_PREFIX . "gdpr_restriction_of_processing SET customer_id = '" . (int)$customer_id . "', restriction_status = '" . $this->db->escape($restriction_status) . "', date_updated = NOW()");
	}

	/**
	 * Update the Request Code
	 * @param  [int] $request_id
	 * @param  [string] $request_code
	 */
	public function updateRequestCode($request_id, $request_code) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "gdpr_request SET code = '" . $this->db->escape($request_code) . "' WHERE request_id = '" . $request_id . "'");
	}

	/**
	 * Update the Request Status
	 * @param  [int] $request_id
	 * @param  [int] $status
	 */
	public function updateRequestStatus($request_id, $status) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "gdpr_request SET status = '" . $status . "' WHERE request_id = '" . $request_id . "'");
	}

	/********************* HELPERS **********************/

	/**
   * Turn a string into a random set of characters
   * @param  [string] $text [string to garble]
   * @return [string]       [garbled/anonymized string]
   */
  public function garble($text)
  {
    $random_string = '';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $text_length = strlen($text);
    for ($i = 0; $i < $text_length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }
    return $random_string;
  }

	/**
	 * Generate random string
	 * @param  integer $length
	 * @return string
	 */
	public function generateRandomString($length = 30)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return strtoupper($randomString);
	}
}
