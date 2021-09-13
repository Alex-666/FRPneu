<?php
class ControllerExtensionAccountGdpr extends Controller {
	public function index() {

		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/account/gdpr', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/account/gdpr');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_gdpr'),
			'href' => $this->url->link('extension/account/gdpr', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['text_download_account_data'] = $this->language->get('text_download_account_data');
		$data['text_download_address_data'] = $this->language->get('text_download_address_data');
		$data['text_download_order_data'] = $this->language->get('text_download_order_data');
		$data['text_download_gdpr_requests_data'] = $this->language->get('text_download_gdpr_requests_data');

		$data['text_gdpr_request'] = $this->language->get('text_gdpr_request');
		$data['text_gdpr_forget_me'] = $this->language->get('text_gdpr_forget_me');
		$data['text_right_to_be_forgotten'] = $this->language->get('text_right_to_be_forgotten');
		$data['text_right_to_data_access'] = $this->language->get('text_right_to_data_access');

		$data['text_download_header'] = $this->language->get('text_download_header');
		$data['text_download_help'] = $this->language->get('text_download_help');

		$data['button_continue'] = $this->language->get('button_continue');

		// GDPR Requests
		$data['gdpr_request'] = $this->url->link('extension/information/gdpr_request', '', true);
		$data['gdpr_forget_me'] = $this->url->link('extension/information/gdpr_forget_me', '', true);
		$data['gdpr_restrict_processing'] = $this->url->link('extension/account/gdpr_restrict_processing', '', true);

		// GDPR Settings
		$data['gdpr_settings'] = $settings;

    $data['download_account_data'] = $this->url->link('extension/account/gdpr/downloadAccount', '', true);
    $data['download_address_data'] = $this->url->link('extension/account/gdpr/downloadAddresses', '', true);
    $data['download_order_data'] = $this->url->link('extension/account/gdpr/downloadOrders', '', true);
    $data['download_gdpr_requests_data'] = $this->url->link('extension/account/gdpr/downloadGdprRequests', '', true);

		$data['edit_account'] = $this->url->link('account/edit', '', true);
		$data['edit_address'] = $this->url->link('account/address', '', true);
		$data['edit_password'] = $this->url->link('account/password', '', true);
		$data['edit_newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/account/gdpr', $data));
	}

	/**
	 * Generate a CSV document with user account data
	 * @return php browser output
	 */
	public function downloadAccount() {

		if(!$this->customer->isLogged()) {
			exit;
		}

		$this->load->model('extension/module/gdpr');

		// Get customer ID
		$customer_id = $this->customer->getId();
		// Get customer email
		$email = $this->customer->getEmail();

		// Get customer base data
		$customer = $this->model_extension_module_gdpr->getCustomerData($email);

		$customer_account_data[0] = array(
			'customer_id' => $customer['customer_id'],
			'firstname' => $customer['firstname'],
			'lastname' => $customer['lastname'],
			'email' => $customer['email'],
			'telephone' => $customer['telephone'],
			'fax' => $customer['fax'],
			'wishlist' => $customer['wishlist'],
			'newsletter' => $customer['newsletter'],
			'ip' => $customer['ip'],
			'custom_field' => $customer['custom_field'],
			'date_added' => $customer['date_added'],
		);

		// Get customer history, ip, activity logs, login
		$info = $this->model_extension_module_gdpr->getCustomerInformation($customer_id, $email);
		// Get customer rewards
		$rewards = $this->model_extension_module_gdpr->getCustomerRewards($customer_id);
		// Get customer transactions
		$transactions = $this->model_extension_module_gdpr->getCustomerTransactions($customer_id);
		// Get customer wishlist
		$wishlist = array();
		if (VERSION > '2.1.0.0') {
			$wishlist = $this->model_extension_module_gdpr->getCustomerWishlist($customer_id);
		}

		$all_data = $info;
		$all_data['customer_account_data'] = $customer_account_data;
		$all_data['rewards'] = $rewards;
		$all_data['transactions'] = $transactions;
		$all_data['wishlist'] = $wishlist;

		// Sort by key names
		ksort($all_data);

		$headers = array();
		$values = array();
		foreach($all_data as $index => $table) {
			if(!empty($table[0])) {
				foreach($table[0] as $key => $value) {
					$headers[$index][$key] = $key;
				}

				foreach($table as $num => $value) {
					$values[$index][$num] = $value;
				}
			}
		}

		// Filename
		$date = date('Y-m-d');
		$spacer = array('');
		$filename = $date . '-account-details-ID' . $customer_id . '.csv';
		// Response headers
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename='.$filename);
		// Generate CSV
		$out = fopen('php://output', 'w');

		foreach($headers as $table_key => $header_array) {
			// Add table headers for readability
			$table_name = array(ucwords(str_replace('_', ' ', $table_key)));
			fputcsv($out, $table_name);
			// Headers
			fputcsv($out, $header_array);
			// Values
			foreach($values[$table_key] as $record) {
				//var_dump($record);
				fputcsv($out, $record);
			}
			// Add empty rows for readability
			fputcsv($out, $spacer);
		}

		fclose($out);

	}

	/**
	 * Generate a CSV document with user addresses data
	 * @return php browser output
	 */
	public function downloadAddresses() {

		if(!$this->customer->isLogged()) {
			exit;
		}

		$this->load->model('extension/module/gdpr');

		// Get customer ID
		$customer_id = $this->customer->getId();
		// Get customer email
		$email = $this->customer->getEmail();
		// Get customer addresses
		$addresses = $this->model_extension_module_gdpr->getCustomerAddresses($customer_id);

		$headers = array(
			'address_id' => $this->language->get('address_id'),
			'customer_id' => $this->language->get('customer_id'),
			'firstname' => $this->language->get('firstname'),
			'lastname' => $this->language->get('lastname'),
			'company' => $this->language->get('company'),
			'address_1' => $this->language->get('address_1'),
			'address_2' => $this->language->get('address_2'),
			'city' => $this->language->get('city'),
			'postcode' => $this->language->get('postcode'),
			'country_id' => $this->language->get('country_id'),
			'zone_id' => $this->language->get('zone_id'),
			'custom_field' => $this->language->get('custom_field'),
			'text' => $this->language->get('text'),
		);

		// Filename
		$date = date('Y-m-d');
		$filename = $date . '-addresses-customer-' . $customer_id . '.csv';
		// Response headers
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename='.$filename);
		// Generate CSV
		$out = fopen('php://output', 'w');
		fputcsv($out, $headers);

		if(!empty($addresses)) {
			foreach($addresses as $address) {
				fputcsv($out, $address);
			}
		}

		fclose($out);

	}


		/**
		 * Generate a CSV document with user orders data
		 * @return php browser output
		 */
		public function downloadOrders() {

			if(!$this->customer->isLogged()) {
				exit;
			}

			$this->load->model('extension/module/gdpr');

			// Get customer ID
			$customer_id = $this->customer->getId();
			// Get customer email
			$email = $this->customer->getEmail();
			// Get customer orders
			$raw_orders = $this->model_extension_module_gdpr->getCustomerOrders($customer_id);

			$orders = array();
			// Merge order data and product data
			if(!empty($raw_orders)) {
				foreach($raw_orders as $index => $raw_order) {
					$orders[$index] = $raw_order['order'];
					$orders[$index]['products'] = serialize($raw_order['products']);
				}
			}

			$headers = array(
				'order_id' => $this->language->get('order_id'),
				'invoice_no' => $this->language->get('invoice_no'),
				'invoice_prefix' => $this->language->get('invoice_prefix'),
				'store_id' => $this->language->get('store_id'),
				'store_name' => $this->language->get('store_name'),
				'store_url' => $this->language->get('store_url'),
				'customer_id' => $this->language->get('customer_id'),
				'customer_group_id' => $this->language->get('customer_group_id'),
				'firstname' => $this->language->get('firstname'),
				'lastname' => $this->language->get('lastname'),
				'email' => $this->language->get('email'),
				'telephone' => $this->language->get('telephone'),
				'fax' => $this->language->get('fax'),
				'custom_field' => $this->language->get('custom_field'),
				'payment_firstname' => $this->language->get('payment_firstname'),
				'payment_lastname' => $this->language->get('payment_lastname'),
				'payment_company' => $this->language->get('payment_company'),
				'payment_address_1' => $this->language->get('payment_address_1'),
				'payment_address_2' => $this->language->get('payment_address_2'),
				'payment_city' => $this->language->get('payment_city'),
				'payment_postcode' => $this->language->get('payment_postcode'),
				'payment_country' => $this->language->get('payment_country'),
				'payment_country_id' => $this->language->get('payment_country_id'),
				'payment_zone' => $this->language->get('payment_zone'),
				'payment_zone_id' => $this->language->get('payment_zone_id'),
				'payment_address_format' => $this->language->get('payment_address_format'),
				'payment_custom_field' => $this->language->get('payment_custom_field'),
				'payment_method' => $this->language->get('payment_method'),
				'payment_code' => $this->language->get('payment_code'),
				'shipping_firstname' => $this->language->get('shipping_firstname'),
				'shipping_lastname' => $this->language->get('shipping_lastname'),
				'shipping_company' => $this->language->get('shipping_company'),
				'shipping_address_1' => $this->language->get('shipping_address_1'),
				'shipping_address_2' => $this->language->get('shipping_address_2'),
				'shipping_city' => $this->language->get('shipping_city'),
				'shipping_postcode' => $this->language->get('shipping_postcode'),
				'shipping_country' => $this->language->get('shipping_country'),
				'shipping_country_id' => $this->language->get('shipping_country_id'),
				'shipping_zone' => $this->language->get('shipping_zone'),
				'shipping_zone_id' => $this->language->get('shipping_zone_id'),
				'shipping_address_format' => $this->language->get('shipping_address_format'),
				'shipping_custom_field' => $this->language->get('shipping_custom_field'),
				'shipping_method' => $this->language->get('shipping_method'),
				'shipping_code' => $this->language->get('shipping_code'),
				'comment' => $this->language->get('comment'),
				'total' => $this->language->get('total'),
				'order_status_id' => $this->language->get('order_status_id'),
				'affiliate_id' => $this->language->get('affiliate_id'),
				'commission' => $this->language->get('commission'),
				'marketing_id' => $this->language->get('marketing_id'),
				'tracking' => $this->language->get('tracking'),
				'language_id' => $this->language->get('language_id'),
				'currency_id' => $this->language->get('currency_id'),
				'currency_code' => $this->language->get('currency_code'),
				'currency_value' => $this->language->get('currency_value'),
				'ip' => $this->language->get('ip'),
				'forwarded_ip' => $this->language->get('forwarded_ip'),
				'user_agent' => $this->language->get('user_agent'),
				'accept_language' => $this->language->get('accept_language'),
				'date_added' => $this->language->get('date_added'),
				'date_modified' => $this->language->get('date_modified'),
				'products' => $this->language->get('products'),
			);

			// Filename
			$date = date('Y-m-d');
			$filename = $date . '-addresses-customer-' . $customer_id . '.csv';
			// Response headers
			header( 'Content-Type: text/csv' );
			header( 'Content-Disposition: attachment;filename='.$filename);
			// Generate CSV
			$out = fopen('php://output', 'w');
			fputcsv($out, $headers);

			if(!empty($orders)) {
				foreach($orders as $order) {
					fputcsv($out, $order);
				}
			}

			fclose($out);

		}


			/**
			 * Generate a CSV document with user GDPR requests data
			 * @return php browser output
			 */
			public function downloadGdprRequests() {

				if(!$this->customer->isLogged()) {
					exit;
				}

				$this->load->model('extension/module/gdpr');

				// Get customer ID
				$customer_id = $this->customer->getId();
				// Get customer email
				$email = $this->customer->getEmail();
				// Get customer GDPR requests recorded
				$gdpr_requests = $this->model_extension_module_gdpr->getCustomerGdprRequests($email);

				if(!empty($gdpr_request)) {
					foreach($gdpr_requests as $index => $gdpr_request) {
						$gdpr_request['confirmation_string'] = '';
						//echo("'" . $index . "' => \$this->language->get('" . $index . "'),<br>");
						//echo("\$_['" . $index . "'] = '" . $index . "';<br>");
					}
				}

				$headers = array(
					'request_id' => $this->language->get('request_id'),
					'email' => $this->language->get('email'),
					'status' => $this->language->get('status'),
					'request_type' => $this->language->get('request_type'),
					'http_user_agent' => $this->language->get('http_user_agent'),
					'http_accept_language' => $this->language->get('http_accept_language'),
					'server_addr' => $this->language->get('server_addr'),
					'remote_addr' => $this->language->get('remote_addr'),
					'code' => $this->language->get('code'),
					'confirmation_string' => $this->language->get('confirmation_string'),
					'timestamp' => $this->language->get('timestamp'),
					'request_time' => $this->language->get('request_time'),
				);

				// Filename
				$date = date('Y-m-d');
				$filename = $date . '-gdpr-requests-customer-' . $customer_id . '.csv';
				// Response headers
				header( 'Content-Type: text/csv' );
				header( 'Content-Disposition: attachment;filename='.$filename);
				// Generate CSV
				$out = fopen('php://output', 'w');
				fputcsv($out, $headers);

				if(!empty($gdpr_request)) {
					foreach($gdpr_requests as $gdpr_request) {
						fputcsv($out, $gdpr_request);
					}
				}

				fclose($out);

			}
}
