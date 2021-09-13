<?php
class ControllerExtensionModuleGdprReportBreachCustomers extends Controller {
	private $error = array();

	// Breach notification statuses
	public $status_unknown = 0;
	public $status_pending = 1;
	public $status_generated = 2;
	public $status_emailed = 3;
	public $status_failed = 6;

	public $batch_sizes = array(50,100,200,300,400,500,750,1000,2500,5000,10000);
	public $batch_size_default = 100;
	public $max_runtimes = array(30,40,50,60,120,180,240,300,360,420,480,540,600);
	public $max_runtime_default = 60;

	public $seconds_in_minute = 60;
	public $seconds_in_hour = 3600;
	public $microseconds_in_hour = 36000000000;

	// This should be configurable TODO
	private $key = 'hu3fd65HJ09iur5ZZ568971dort5t4y6sg89346sdngsu5';

	public function index() {
		$this->load->language('extension/module/gdpr/report_breach');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gdpr');

		$this->getList();
	}

	public function generateCustomerNotifications() {

		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');

		if(!empty($this->request->get['breach_id'])) {
			$breach_id = $this->request->get['breach_id'];
		} else {
			$json['error']['warning'] = $this->language->get('error_breach_id');
			$breach_id = false;
		}

		if($breach_id) {
			// Get breach details
			$breach = $this->model_extension_module_gdpr->getBreachNotification($breach_id);
			// Get all customers
			$customers = $this->model_extension_module_gdpr->getAllCustomers();
			// Get store email
			$store_email = $this->config->get('config_email');

			foreach($customers as $customer) {
				// Check if notification already there
				$is_existing_notification = $this->model_extension_module_gdpr->isExistingCustomerNotification($customer['customer_id'], $breach['breach_id']);

				if(!$is_existing_notification) {
					// Add breach notification record
					$recorded_notification_id = $this->model_extension_module_gdpr->addCustomerNotification($customer, $breach, $store_email, $this->status_pending);

					if(!$recorded_notification_id) {
						$json['error']['customer_notifications'][$breach_id] = $this->language->get('error_customer_notification_add');
						$json['error']['customer_notifications_add_text'] = $this->language->get('error_customer_notification_add');
					}
				} else {
					$json['error']['customer_notifications'][$breach_id] = $this->language->get('error_customer_notification_existing');
					$json['error']['customer_notifications_existing_text'] = $this->language->get('error_customer_notification_existing');
				}
			}
		}

		$statuses = array(
			$this->status_unknown => $this->language->get('text_status_unknown'),
			$this->status_pending => $this->language->get('text_status_pending'),
			$this->status_generated => $this->language->get('text_status_generated'),
			$this->status_emailed => $this->language->get('text_status_sent'),
			$this->status_failed => $this->language->get('text_status_failed'),
		);

		$json['status_customers'] = $statuses[$this->status_generated];
		if(empty($json['error'])) {
			$json['success'] = $this->language->get('text_success_generate_customer_notifications');

			// Update status of customer notifications on the breach record
			$this->model_extension_module_gdpr->updateBreachNotificationStatusCustomers($breach_id, $this->status_generated);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function getListOfCustomers() {

		$this->load->model('extension/module/gdpr');

		// Get all customers
		$customers = $this->model_extension_module_gdpr->getAllCustomers();

		$headers = array(
			'customer_id' => $this->language->get('customer_id'),
			'email' => $this->language->get('email'),
			'firstname' => $this->language->get('firstname'),
			'lastname' => $this->language->get('lastname'),
		);

		// Filename
		$date = date('Y-m-d');
		$filename = $date . '-all-customers-list.csv';
		// Response headers
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename='.$filename);
		// Generate CSV
		$out = fopen('php://output', 'w');
		fputcsv($out, $headers);

		if(!empty($customers)) {
			foreach($customers as $customer) {
				fputcsv($out, $customer);
			}
		}

		fclose($out);
	}

	public function getList() {

		$this->load->language('extension/module/gdpr/report_breach');

		$data['entry_email_customers'] = $this->language->get('entry_email_customers');
		$data['entry_email_bcc'] = $this->language->get('entry_email_bcc');
		$data['text_send_breach_notification'] = $this->language->get('text_send_breach_notification');

		if (isset($this->request->get['filter_breach_notification_status'])) {
			$filter_breach_notification_status = $this->request->get['filter_breach_notification_status'];
		} else {
			$filter_breach_notification_status = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'br.customers_breach_notification_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';



		if (isset($this->request->get['filter_breach_notification_status'])) {
			$url .= '&filter_breach_notification_status=' . $this->request->get['filter_breach_notification_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['send'] = $this->url->link('extension/module/gdpr/report_breach_customers/send', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['download_pdf'] = $this->url->link('extension/module/gdpr/report_breach_customers/getPdf', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['add'] = $this->url->link('extension/module/gdpr/report_breach_customers/form', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['breach_notifications'] = array();

		$filter_data = array(
			'filter_breach_notification_status'  => $filter_breach_notification_status,
			'filter_date_added'    => $filter_date_added,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$breach_notification_total = $this->model_extension_module_gdpr->getTotalBreachNotifications($filter_data);

		$results = $this->model_extension_module_gdpr->getBreachNotifications($filter_data);

		foreach ($results as $result) {
			$data['breach_notifications'][] = array(
				'id'      => $result['breach_id'],
				'email_bcc' => !empty($result['email_bcc']) ? $result['email_bcc'] : '',
				'email_customers' => !empty($result['email_customers']) ? $result['email_customers'] : '',
				'status'        => $result['status'],
				'status_customers'        => $result['status_customers'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_of_breach' => $result['date_of_breach'],
				'date_of_discovery' => $result['date_of_discovery'],
				'name' => $result['name'],
				'number_of_accounts_affected' => $result['number_of_accounts_affected'],
				'generate_customer_notifications'          => $this->url->link('extension/module/gdpr/report_breach_customers/generateCustomerNotifications', 'user_token=' . $this->session->data['user_token'] . '&breach_id=' . $result['breach_id'] . $url, 'SSL'),
				'edit'          => $this->url->link('extension/module/gdpr/report_breach_commissioner/form', 'user_token=' . $this->session->data['user_token'] . '&breach_id=' . $result['breach_id'] . $url, 'SSL'),
			);
		}

		$data['statuses'] = array(
			$this->status_unknown => $this->language->get('text_status_unknown'),
			$this->status_pending => $this->language->get('text_status_pending'),
			$this->status_generated => $this->language->get('text_status_generated'),
			$this->status_emailed => $this->language->get('text_status_sent'),
			$this->status_failed => $this->language->get('text_status_failed'),
		);

		$data['heading_title_customers'] = $this->language->get('heading_title_customers');

		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_list_customers'] = $this->language->get('text_list_customers');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_action'] = $this->language->get('column_action');
		$data['column_bcc_to'] = $this->language->get('column_bcc_to');
		$data['column_breach_id'] = $this->language->get('column_breach_id');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_of_breach'] = $this->language->get('column_date_of_breach');
		$data['column_date_of_discovery'] = $this->language->get('column_date_of_discovery');
		$data['column_breach_name'] = $this->language->get('column_breach_name');
		$data['column_number_of_accounts_affected'] = $this->language->get('column_number_of_accounts_affected');
		$data['column_sent_to'] = $this->language->get('column_sent_to');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_status_customers'] = $this->language->get('column_status_customers');

		$data['entry_breach_notification_status'] = $this->language->get('entry_breach_notification_status');
		$data['entry_customers_breach_notification_id'] = $this->language->get('entry_customers_breach_notification_id');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_of_breach'] = $this->language->get('entry_date_of_breach');
		$data['entry_date_of_discovery'] = $this->language->get('entry_date_of_discovery');
		$data['entry_number_of_accounts_affected'] = $this->language->get('entry_number_of_accounts_affected');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_download_pdf'] = $this->language->get('button_download_pdf');
		$data['button_generate_customer_notifications'] = $this->language->get('button_generate_customer_notifications');
		$data['button_send'] = $this->language->get('button_send');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_breach_notification_status'])) {
			$url .= '&filter_breach_notification_status=' . $this->request->get['filter_breach_notification_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . '&sort=br.customers_breach_notification_id' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_breach_notification_status'])) {
			$url .= '&filter_breach_notification_status=' . $this->request->get['filter_breach_notification_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $breach_notification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($breach_notification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($breach_notification_total - $this->config->get('config_limit_admin'))) ? $breach_notification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $breach_notification_total, ceil($breach_notification_total / $this->config->get('config_limit_admin')));

		$data['filter_breach_notification_status'] = $filter_breach_notification_status;
		$data['filter_date_added'] = $filter_date_added;

		$this->load->model('localisation/order_status');

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['store'] = HTTPS_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gdpr/report_breach_customers_list', $data));
	}

	public function getListOfEmailNotifications() {

		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');

		$data['entry_email_customers'] = $this->language->get('entry_email_customers');
		$data['entry_email_bcc'] = $this->language->get('entry_email_bcc');
		$data['text_send_breach_notification'] = $this->language->get('text_send_breach_notification');

		if (isset($this->request->get['filter_breach_customer_email_notification_status'])) {
			$filter_breach_customer_email_notification_status = $this->request->get['filter_breach_customer_email_notification_status'];
		} else {
			$filter_breach_customer_email_notification_status = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'br.customers_breach_notification_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_breach_customer_email_notification_status'])) {
			$url .= '&filter_breach_customer_email_notification_status=' . $this->request->get['filter_breach_customer_email_notification_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . $this->request->get['filter_customer_email'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['breach_notifications'] = array();

		$filter_data = array(
			'filter_breach_customer_email_notification_status'  => $filter_breach_customer_email_notification_status,
			'filter_date_added'    => $filter_date_added,
			'filter_customer_email' => $filter_customer_email,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$breach_notification_total = $this->model_extension_module_gdpr->getTotalBreachCustomerEmailNotifications($filter_data);

		$results = $this->model_extension_module_gdpr->getBreachCustomerEmailNotifications($filter_data);

		foreach($this->model_extension_module_gdpr->getBreachNotifications() as $breach) {
			$breaches[$breach['breach_id']] = $breach;
		}

		foreach ($results as $result) {
			$data['breach_notifications'][] = array(
				'id'      => $result['customer_notification_id'],
				'breach_id' => $result['breach_id'],
				'breach_name' => $breaches[$result['breach_id']]['name'],
				'customer_id' => $result['customer_id'],
				'customer_email' => !empty($result['customer_email']) ? $result['customer_email'] : '',
				'name' => $result['firstname'] . ' ' . $result['lastname'],
				'status'        => $result['status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_updated' => $result['date_updated'],
				'preview_notification'          => $this->url->link('extension/module/gdpr/report_breach_customers/previewCustomerNotification', 'user_token=' . $this->session->data['user_token'] . '&breach_id=' . $result['breach_id'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
			);
		}

		$data['statuses'] = array(
			$this->status_unknown => $this->language->get('text_status_unknown'),
			$this->status_pending => $this->language->get('text_status_pending'),
			$this->status_generated => $this->language->get('text_status_generated'),
			$this->status_emailed => $this->language->get('text_status_sent'),
			$this->status_failed => $this->language->get('text_status_failed'),
		);

		$data['notifications_pending'] = $this->model_extension_module_gdpr->getCustomerEmailNotificationsCount($this->status_pending);
		$data['notifications_emailed'] = $this->model_extension_module_gdpr->getCustomerEmailNotificationsCount($this->status_emailed);

		$data['heading_title_customers'] = $this->language->get('heading_title_customers');

		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_list_customers'] = $this->language->get('text_list_customers');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_notifications_emailed'] = $this->language->get('text_notifications_emailed');
		$data['text_notifications_pending'] = $this->language->get('text_notifications_pending');

		$data['column_action'] = $this->language->get('column_action');
		$data['column_bcc_to'] = $this->language->get('column_bcc_to');
		$data['column_breach_id'] = $this->language->get('column_breach_id');
		$data['column_breach_name'] = $this->language->get('column_breach_name');
		$data['column_customer_email'] = $this->language->get('column_customer_email');
		$data['column_customer_id'] = $this->language->get('column_customer_id');
		$data['column_customer_name'] = $this->language->get('column_customer_name');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_updated'] = $this->language->get('column_date_updated');
		$data['column_date_of_breach'] = $this->language->get('column_date_of_breach');
		$data['column_date_of_discovery'] = $this->language->get('column_date_of_discovery');

		$data['column_id'] = $this->language->get('column_id');
		$data['column_number_of_accounts_affected'] = $this->language->get('column_number_of_accounts_affected');
		$data['column_sent_to'] = $this->language->get('column_sent_to');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_status_customers'] = $this->language->get('column_status_customers');
		$data['column_status_notification'] = $this->language->get('column_status_notification');

		$data['entry_breach_customer_email_notification_status'] = $this->language->get('entry_breach_customer_email_notification_status');
		$data['entry_customer_email'] = $this->language->get('entry_customer_email');
		$data['entry_customers_breach_notification_id'] = $this->language->get('entry_customers_breach_notification_id');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_of_breach'] = $this->language->get('entry_date_of_breach');
		$data['entry_date_of_discovery'] = $this->language->get('entry_date_of_discovery');
		$data['entry_number_of_accounts_affected'] = $this->language->get('entry_number_of_accounts_affected');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_download_pdf'] = $this->language->get('button_download_pdf');
		$data['button_preview_customer_notification'] = $this->language->get('button_preview_customer_notification');
		$data['button_send'] = $this->language->get('button_send');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_breach_customer_email_notification_status'])) {
			$url .= '&filter_breach_customer_email_notification_status' . $this->request->get['filter_breach_customer_email_notification_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . '&sort=br.customers_breach_notification_id' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/module/gdpr/report_breach_customers', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_breach_customer_email_notification_status'])) {
			$url .= '&filter_breach_customer_email_notification_status=' . $this->request->get['filter_breach_customer_email_notification_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $breach_notification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/gdpr/report_breach_customers/getListOfEmailNotifications', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($breach_notification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($breach_notification_total - $this->config->get('config_limit_admin'))) ? $breach_notification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $breach_notification_total, ceil($breach_notification_total / $this->config->get('config_limit_admin')));

		$data['filter_breach_customer_email_notification_status'] = $filter_breach_customer_email_notification_status;
		$data['filter_customer_email'] = $filter_customer_email;
		$data['filter_date_added'] = $filter_date_added;

		$this->load->model('localisation/order_status');

		$data['breach_customer_email_notification_statuses'] = array(
			$this->status_pending => array(
				'id' => $this->status_pending,
				'name' => $this->language->get('text_status_pending'),
			),
			$this->status_emailed => array(
				'id' => $this->status_emailed,
				'name' => $this->language->get('text_status_sent'),
			),
		);

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['store'] = HTTPS_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gdpr/report_breach_customers_email_notifications_list', $data));

	}

	public function send() {

		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'extension/module/gdpr/report_breach_customers')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if(!empty($this->request->get['breach_id'])) {
				$breach_id = $this->request->get['breach_id'];
			} else {
				$json['error']['warning'] = $this->language->get('error_breach_id');
				$breach_id = false;
			}

			if(!empty($this->request->post['email-data-customers']) && $this->validateEmail($this->request->post['email-data-customers'])) {
				$email_customers = $this->request->post['email-data-customers'];
			} else {
				$json['error']['mail_data_customers'] = $this->language->get('error_mail_data_customers');
			}

			$email_bcc = '';
			if(!empty($this->request->post['email-bcc'])) {
				if($this->validateEmail($this->request->post['email-bcc'])) {
					$email_bcc = $this->request->post['email-bcc'];
				} else {
					$json['error']['mail_bcc'] = $this->language->get('error_mail_bcc');
				}
			}

			if (!$json) {
				$this->load->model('setting/store');

				if($breach_id) {
					$breach = $this->model_extension_module_gdpr->getcustomersBreachNotification($breach_id);
				}

				$store_info = $this->model_setting_store->getStore($breach['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}


				$data = array(
					'texts' => array(
						'entry_contact_details_of_person_reporting' => $this->language->get('entry_contact_details_of_person_reporting'),
						'entry_customer_group' => $this->language->get('entry_customer_group'),
						'entry_customer' => $this->language->get('entry_customer'),
						'entry_date_of_breach' => $this->language->get('entry_date_of_breach'),
						'entry_date_of_discovery' => $this->language->get('entry_date_of_discovery'),
						'entry_email_bcc' => $this->language->get('entry_email_bcc'),
						'entry_email_customers' => $this->language->get('entry_email_customers'),
						'entry_subject' => $this->language->get('entry_subject'),
						'entry_message_action' => $this->language->get('entry_message_action'),
						'entry_message_incident' => $this->language->get('entry_message_incident'),
						'entry_number_of_accounts_affected' => $this->language->get('entry_number_of_accounts_affected'),
						'entry_store' => $this->language->get('entry_store'),
						'entry_to' => $this->language->get('entry_to'),
						'heading_title' => $this->language->get('heading_title'),
						'heading_detailed' => $this->language->get('heading_detailed'),
						'text_smallprint' => 'Some smallprint text', // TODO
						'text_email_customers_body' => $this->language->get('text_email_customers_body'),
						'text_report_to_customers_actions_taken' => $this->language->get('text_report_to_customers_actions_taken'),
						'text_report_to_customers_contact'  => $this->language->get('text_report_to_customers_contact'),
						'text_report_to_customers_ending' => $this->language->get('text_report_to_customers_ending'),
						'text_report_to_customers_data_exposed' => $this->language->get('text_report_to_customers_data_exposed'),
						'text_report_to_customers_details' => sprintf($this->language->get('text_report_to_customers_details'), $breach['date_of_discovery']),
						'text_report_to_customers_intro' => $this->language->get('text_report_to_customers_intro'),
						'text_date' => $this->language->get('text_date'),
						'text_from' => $this->language->get('text_from'),
						'text_to' => $this->language->get('text_to'),
						'text_signature' => $this->language->get('text_signature'),
					),
					'breach_id' => $breach_id,
					'store_id' => $breach['store_id'],
					'store_name' => $store_name,
					'address_customers' => $breach['address_customers'],
					'address_store' => $breach['address_store'],
				  'subject' => $this->language->get('text_email_customers_subject') . ' - ' . $store_name,
				  'date_of_breach' => !empty($breach['date_of_breach']) ? $breach['date_of_breach'] : '',
				  'date_of_discovery' => !empty($breach['date_of_discovery']) ? $breach['date_of_discovery'] : '',
				  'contact_details_of_person_reporting' => $breach['contact_details_of_person_reporting'],
				  'number_of_accounts_affected' => $breach['number_of_accounts_affected'],
				  'message_incident' => $breach['message_incident'],
				  'message_action' => $breach['message_action'],
					'status' => $breach['status'],
					'date_added' => $breach['date_added'],
					'date_updated' => $breach['date_updated'],
					'now' => date('Y-m-d'),
				);

				// Store record of the notification in the database???
				$this->load->model('extension/module/gdpr');
				//$this->model_extension_module_gdpr->storecustomersBreachNotification($data);

				$store_email = $this->config->get('config_email');

				// Load mail template with data
				$html = $this->load->view('extension/module/gdpr/mail/breach_notification_email_body', $data);

				if (!empty($email_customers)) {

					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $data['subject'] . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($html, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";

					$pdf_file = $this->getPdfAttachment($data);

					if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email_customers)) {
						$this->sendBreachEmail($store_email, $email_customers, $store_name, $message, $data['subject'], $pdf_file);
					}

					// Send BCC emails
					if(!empty($email_bcc)) {
						$bcc_emails = explode(',', $email_bcc);
						if(!empty($bcc_emails)) {
							foreach($bcc_emails as $bcc) {
								if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $bcc)) {
									$this->sendBreachEmail($store_email, $bcc, $store_name, $message, $data['subject'], $pdf_file);
								}
							}
						}
					}

					// Add emails to the record
					$this->model_extension_module_gdpr->addEmailsTocustomersBreachNotification($breach_id, $email_customers, $email_bcc);
					// Update status of the notification record to emailed
					$this->model_extension_module_gdpr->updatecustomersBreachNotificationStatus($breach_id, $this->status_emailed);

					// Delete the PDF
					if (file_exists($pdf_file)) {
						//unlink($pdf_file);
					}


					$json['success'] = $this->language->get('text_success');
					$json['email_customers'] = $email_customers;
					$json['email_bcc'] = $email_bcc;

					$statuses = array(
						0 => $this->language->get('text_status_unknown'),
						1 => $this->language->get('text_status_pending'),
						2 => $this->language->get('text_status_sent'),
					);

					$json['status'] = $statuses[$this->status_emailed];
				}

			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function sendEmails() {

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			// Get the parameters
			if(!empty($this->request->post['batch-size'])) {
				$batch_size = $this->request->post['batch-size'];
			}

			if(!empty($this->request->post['max-runtime'])) {
				$max_runtime = $this->request->post['max-runtime'];
			}

			if($batch_size && $max_runtime) {
				// Call the mailer script asynchronously
				$this->async_http_request(HTTP_CATALOG . "index.php?route=extension/module/gdpr/gdpr_mailer&batch_size=" . $batch_size . '&max_runtime=' . $max_runtime . '&key=' . $this->key);

				// Display feedback for the customer
				$this->response->redirect($this->url->link('extension/module/gdpr/report_breach_customers/sendEmails', array('success' => '1', 'max_runtime' => $max_runtime, 'batch_size' => $batch_size, 'user_token' => $this->session->data['user_token']), 'SSL'));
			} else {
				// Display error feedback for the customer
				$this->response->redirect($this->url->link('extension/module/gdpr/report_breach_customers/sendEmails', array('error' => '1', 'user_token' => $this->session->data['user_token']), 'SSL'));
			}

		}

		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');

		$data['success'] = false;
		if(!empty($this->request->get['success'])) {
			$data['success'] = $this->request->get['success'];
			$data['feedback_class'] = 'success';
		}

		$data['error'] = false;
		if(!empty($this->request->get['error'])) {
			$data['success'] = $this->request->get['error'];
			$data['feedback_class'] = 'error';
		}

		$max_runtime_text = '';
		if(!empty($this->request->get['max_runtime'])) {
			$max_runtime = $this->request->get['max_runtime'];

			if($max_runtime < 60) {
				$max_runtime_text = $max_runtime . ' ' . $this->language->get('text_minutes');
			} else {
				$hour = ($max_runtime < 120) ? $this->language->get('text_hour') : $this->language->get('text_hours');
				$max_runtime_text = number_format(($max_runtime / 60), 0) . ' ' . $hour;
			}
		}

		$data['cron_url'] = 'wget -q -O /dev/null ' . HTTP_CATALOG . "index.php?route=extension/module/gdpr/gdpr_mailer&batch_size=" . $this->batch_size_default . '&max_runtime=' . $this->max_runtime_default . '&key=' . $this->key;
		$data['cron_base_url'] = 'wget -q -O /dev/null ' . HTTP_CATALOG . 'index.php?route=extension/module/gdpr/gdpr_mailer';
		$data['cron_key'] = $this->key;
		$data['log_path'] = '/logs/gdpr_breach_notificatons_emailed.txt';

		$data['button_email'] = $this->language->get('button_email');

		$data['entry_batch_size'] = $this->language->get('entry_batch_size');
		$data['entry_max_runtime'] = $this->language->get('entry_max_runtime');

		$data['error_email_batch'] = $this->language->get('error_email_batch');

		$data['heading_title_customers_send_emails'] = $this->language->get('heading_title_customers_send_emails');
		$data['heading_detailed_customers_send_emails'] = $this->language->get('heading_detailed_customers_send_emails');

		$data['help_batch_size'] = $this->language->get('help_batch_size');
		$data['help_max_runtime'] = $this->language->get('help_max_runtime');

		$data['text_header_cron'] = $this->language->get('text_header_cron');
		$data['text_header_log'] = $this->language->get('text_header_log');
		$data['text_instructions_customers_send_emails'] = $this->language->get('text_instructions_customers_send_emails');
		$data['text_instructions_cron'] = $this->language->get('text_instructions_cron');
		$data['text_instructions_log'] = $this->language->get('text_instructions_log');

		$data['text_default'] = $this->language->get('text_default');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_instructions'] = $this->language->get('text_instructions');
		$data['text_notifications_emailed'] = $this->language->get('text_notifications_emailed');
		$data['text_notifications_pending'] = $this->language->get('text_notifications_pending');
		$data['text_success_email_batch'] = $this->language->get('text_success_email_batch') . ' ' . $max_runtime_text;

		$data['notifications_pending'] = $this->model_extension_module_gdpr->getCustomerEmailNotificationsCount($this->status_pending);
		$data['notifications_emailed'] = $this->model_extension_module_gdpr->getCustomerEmailNotificationsCount($this->status_emailed);

		$data['batch_sizes'] = array();
		foreach($this->batch_sizes as $batch_size) {
			$data['batch_sizes'][$batch_size] = array(
				'id' => $batch_size,
				'name' => (string)$batch_size,
				'default' => false,
			);
		}
		$data['batch_sizes'][$this->batch_size_default]['default'] = true;

		$data['runtimes'] = array();
		foreach($this->max_runtimes as $max_runtime) {
			if($max_runtime < 60) {
				$data['runtimes'][$max_runtime] = array(
					'id' => $max_runtime,
					'name' => $max_runtime . ' ' . $this->language->get('text_minutes'),
					'default' => false,
				);
			} else {
				$hour = ($max_runtime < 120) ? $this->language->get('text_hour') : $this->language->get('text_hours');
				$data['runtimes'][$max_runtime] = array(
					'id' => $max_runtime,
					'name' => number_format(($max_runtime / 60), 0) . ' ' . $hour,
					'default' => false,
				);
			}
		}
		$data['runtimes'][$this->max_runtime_default]['default'] = true;

		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gdpr/report_breach_customers/sendEmails', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gdpr/report_breach_customers_send', $data));

	}

	public function sendBreachEmail($from, $to, $store_name, $html, $subject, $pdf_file) {

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($to);
		$mail->setFrom($from);
		$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($html);
		$mail->addAttachment($pdf_file);
		$mail->send();
	}

	public function validateEmail($email) {
		$emails = explode(',', $email);
		if(!empty($emails)) {

			if(!is_array($emails)) {
				$emails = array($emails);
			}

			foreach($emails as $email) {
				if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					return false;
				}
			}
		}
		return true;
	}

	public function async_http_request($url) {
	    $parts = parse_url($url);
	    $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
	    $out = "GET " . $parts['path'] . '?' . $parts['query'] . " HTTP/1.1\r\n";
	    $out.= "Host: " . $parts['host'] . "\r\n";
	    $out.= "Content-Length: 0" . "\r\n";
	    $out.= "Connection: Close\r\n\r\n";

	    fwrite($fp, $out);
	    fclose($fp);
	}


}
