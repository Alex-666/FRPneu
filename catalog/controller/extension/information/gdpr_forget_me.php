<?php
class ControllerExtensionInformationGdprForgetMe extends Controller {
	private $error = array();

	public function index() {

		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		// If forms are to be set private, force login
		if($settings['module_gdpr_forms_are_private']) {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/information/gdpr_forget_me', '', true);

				$this->response->redirect($this->url->link('account/login', '', true));
			}
		}

		$this->load->language('extension/information/gdpr_request');
		$this->load->model('extension/module/gdpr');
		$this->load->model('setting/setting');

		$request_types = array(
			'personal_data_report' => 1,
			'forget_me' => 2,
		);

		$this->document->setTitle($this->language->get('heading_title_forget_me'));

		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		// If module is disabled force redirect the GDPR request URL to home page
		if(!$settings['module_gdpr_status'] || !$settings['module_gdpr_right_to_be_forgotten']) {
			$this->response->redirect($this->url->link('common/home'));
		}

		// Send the confirmation email
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			// Generate unique hash/code
			$confirmation_string = $this->model_extension_module_gdpr->generateRandomString(30);

			// Record the request
			$request_data = array(
				'confirmation_string' => $confirmation_string,
				'email' => $this->request->post['email'],
				'code' => '',
				'http_user_agent' => $this->request->server['HTTP_USER_AGENT'],
				'http_accept_language' => $this->request->server['HTTP_ACCEPT_LANGUAGE'],
				'server_addr' => $this->request->server['SERVER_ADDR'],
				'remote_addr' => $this->request->server['REMOTE_ADDR'],
				'timestamp' => $this->request->server['REQUEST_TIME'],
				'request_time' => date("Y-m-d H:i:s", $this->request->server['REQUEST_TIME']),
				'request_type' => $request_types['forget_me'],
			);

			$request_id = $this->model_extension_module_gdpr->addRequest($request_data);

			// Generate request code
			$request_code = $this->model_extension_module_gdpr->getRequestCode($request_id, $this->request->server['REQUEST_TIME']);

			// Store request code
			$this->model_extension_module_gdpr->updateRequestCode($request_id, $request_code);

			// Send email with a link (confirm_gdpr?email=$email&code=$code)

			$data = array(
				'store' => $this->config->get('config_name'),
				'confirmation_email_text_heading' => $this->language->get('forget_me_confirmation_email_text_heading'),
				'confirmation_email_text_instructions' => $this->language->get('forget_me_confirmation_email_text_instructions'),
				'confirmation_email_text_confirm' => $this->language->get('forget_me_confirmation_email_text_confirm'),
				'confirmation_email_text_smallprint' => $this->language->get('forget_me_confirmation_email_text_smallprint'),
				'confirmation_email_link' => HTTP_SERVER . 'index.php?route=extension/information/gdpr_forget_me/confirm&request=' . $request_code . '&confirmation_code=' . $confirmation_string,
				'forget_me' => true,
			);

			// Load mail template with data
			$html = $this->load->view('extension/module/gdpr/mail_confirm', $data);

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('forget_me_confirmation_email_text_heading'), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($html);
			$mail->send();

			$this->response->redirect($this->url->link('extension/information/gdpr_forget_me/success'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_forget_me'),
			'href' => $this->url->link('extension/information/gdpr_forget_me')
		);

		$data['heading_title'] = $this->language->get('heading_title_forget_me');
		$data['text_forget_me'] = $this->language->get('text_forget_me');
		$data['text_forget_me_instructions'] = $this->language->get('text_forget_me_instructions');

		$data['entry_email'] = $this->language->get('entry_email');

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['enquiry'])) {
			$data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$data['error_enquiry'] = '';
		}

		$data['button_submit'] = $this->language->get('button_submit');

		$data['action'] = $this->url->link('extension/information/gdpr_forget_me', '', 'SSL');

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = $this->customer->getEmail();
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status')) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		} else {
			$data['captcha'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/information/gdpr_forget_me', $data));

	}

	public function confirm() {

		$this->load->language('extension/information/gdpr_request');
		$this->load->model('extension/module/gdpr');

		$this->document->setTitle($this->language->get('heading_title_forget_me'));

		if (!empty($this->request->get['request']) && !empty($this->request->get['confirmation_code'])) {
			// Check if request and confirmation string are Correct
			$request = $this->model_extension_module_gdpr->getConfirmationString($this->request->get['request']);

			// Check if request already confirmed
			$request_status = $this->model_extension_module_gdpr->getRequestStatus($this->request->get['request']);
			// Request has already been processed (status ID 3)
			if($request_status === 3) {
				$texts = array(
					'heading_title' => $this->language->get('request_processed_heading_title'),
					'text_message' => $this->language->get('request_processed_text_message'),
				);
				$this->failed($texts);
			}

			if($request_status !== 3) {
				if($request['confirmation_string'] === $this->request->get['confirmation_code']) {
					// Change request status to confirmed
					$this->model_extension_module_gdpr->updateRequestStatus($request['request_id'], 1);

					// Process Request
					$this->processRequest($request);

					// Redirect to success page
					$texts = array(
						'heading_title' => $this->language->get('forget_me_request_confirmed_heading_title'),
						'text_message' => $this->language->get('forget_me_request_confirmed_text_message'),
					);
					$this->success($texts);
				} else {
					// Confirmation failed
					$texts = array(
						'heading_title' => $this->language->get('request_failed_heading_title'),
						'text_message' => $this->language->get('request_failed_text_message'),
					);
					$this->failed($texts);
				}
			}

		} else {
			$texts = array(
				'heading_title' => $this->language->get('request_failed_heading_title'),
				'text_message' => $this->language->get('request_failed_text_message'),
			);
			$this->failed($texts);
		}
	}

	public function failed($texts = array()) {
		$this->load->language('extension/information/gdpr_request');

		$this->document->setTitle($this->language->get('heading_title_forget_me'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_forget_me'),
			'href' => $this->url->link('extension/information/gdpr_forget_me')
		);

		if(!empty($texts)) {
			$data['heading_title'] = $texts['heading_title'];
			$data['text_message'] = $texts['text_message'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title_forget_me');
			$data['text_message'] = $this->language->get('text_message');
		}

		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/module/gdpr/gdpr_failed', $data));


	}

	public function success($texts = array()) {
		$this->load->language('extension/information/gdpr_request');

		$this->document->setTitle($this->language->get('heading_title_forget_me'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_forget_me'),
			'href' => $this->url->link('extension/information/gdpr_forget_me')
		);

		if(!empty($texts)) {
			$data['heading_title'] = $texts['heading_title'];
			$data['text_message'] = $texts['text_message'];
		} else {
			$data['heading_title'] = $this->language->get('forget_me_request_success_heading_title');
			$data['text_message'] = $this->language->get('forget_me_request_success_text_message');
		}

		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		// Logout customer
		$this->customer->logout();

		$this->response->setOutput($this->load->view('common/success', $data));

	}

	protected function validate() {

		// Email format
		if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
			$bad_email_format = true;
		}

		// Email submitted matches logged in user
		if ($this->customer->isLogged() && ($this->customer->getEmail() !== $this->request->post['email'] && empty($bad_email_format))) {
			$this->error['email'] = $this->language->get('error_email_not_matching');
			$user_not_checking_their_own_address = true;
		}

		// Email exists in the database
		if(!$this->model_extension_module_gdpr->isExistingCustomerEmail($this->request->post['email']) && empty($bad_email_format) && empty($user_not_checking_their_own_address)) {
			$this->error['email'] = $this->language->get('error_email_not_existing');
		}

		// Customer did not exceed the daily limit of requests
		if(!$this->model_extension_module_gdpr->isAllowedGdprRequest($this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_max_requests_day');
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status')) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		// DEMO
		if ($this->request->post['email'] == 'demo@demo.ie') {
			$this->error['email'] = 'You are not allowed to delete the demo account';
		}

		return !$this->error;
	}

	/**
	 * Process GDPR request
	 *
	 * @param  [array] $request [request_id, email, confirmation_string]
	 * @return [type]        [description]
	 */
	private function processRequest($request) {
		$this->load->language('extension/information/gdpr_request');
		$this->load->model('extension/module/gdpr');
		$this->load->model('setting/setting');

		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		// Get customer data and ID
		$customer = $this->model_extension_module_gdpr->getCustomerData($request['email']);
		$customer_id = $customer['customer_id'];

		// Anonymize customer data
		$this->model_extension_module_gdpr->anonymizeCustomerData($customer_id);
		// Anonymize customer addresses
		$this->model_extension_module_gdpr->deleteCustomerAddresses($customer_id);
		// Delete customer history, ip, activity logs, login
		$this->model_extension_module_gdpr->deleteCustomerHistory($customer_id);
		$this->model_extension_module_gdpr->deleteCustomerIps($customer_id);
		$this->model_extension_module_gdpr->deleteCustomerActivityLogs($customer_id);
		$this->model_extension_module_gdpr->deleteCustomerLoginLogs($request['email']);
		// Delete customer rewards
		$this->model_extension_module_gdpr->deleteCustomerRewards($customer_id);
		// Anonymize customer transactions
		$this->model_extension_module_gdpr->deleteCustomerTransactions($customer_id);
		if (VERSION > '2.1.0.0') {
			// Delete customer wishlist
			$this->model_extension_module_gdpr->deleteCustomerWishlist($customer_id);
		}
		// Delete customer GDPR requests recorded (except for the deletion request)
		$this->model_extension_module_gdpr->deleteCustomerGdprRequests($request['email']);
		// Update status of the request
		$this->model_extension_module_gdpr->updateRequestStatus($request['request_id'], 3);

	}
}
