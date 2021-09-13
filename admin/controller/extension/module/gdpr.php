<?php
class ControllerExtensionModuleGdpr extends Controller {

	private $error = array();

	public function index()
	{
		$this->load->language('extension/module/gdpr');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['entry_email_footer'] = $this->language->get('entry_email_footer');
		$data['entry_email_header'] = $this->language->get('entry_email_header');
		$data['entry_free_email_text'] = $this->language->get('entry_free_email_text');
		$data['entry_paid_email_text'] = $this->language->get('entry_paid_email_text');
		$data['entry_locations_of_other_data'] = $this->language->get('entry_locations_of_other_data');
		$data['entry_locations_of_servers'] = $this->language->get('entry_locations_of_servers');
		$data['entry_max_requests_day'] = $this->language->get('entry_max_requests_day');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_confirmed_status'] = $this->language->get('entry_confirmed_status');
		$data['entry_emailed_status'] = $this->language->get('entry_emailed_status');
		$data['entry_account_deleted_status'] = $this->language->get('entry_account_deleted_status');
		$data['entry_right_to_be_forgotten'] = $this->language->get('entry_right_to_be_forgotten');
		$data['entry_data_sent'] = $this->language->get('entry_data_sent');
		$data['entry_store_policy_acceptance'] = $this->language->get('entry_store_policy_acceptance');
		$data['entry_forms_are_private'] = $this->language->get('entry_forms_are_private');

		$data['help_locations_of_other_data'] = $this->language->get('help_locations_of_other_data');
		$data['help_locations_of_servers'] = $this->language->get('help_locations_of_servers');

		$data['help_pending_status'] = $this->language->get('help_pending_status');
		$data['help_confirmed_status'] = $this->language->get('help_confirmed_status');
		$data['help_emailed_status'] = $this->language->get('help_emailed_status');
		$data['help_account_deleted_status'] = $this->language->get('help_account_deleted_status');
		$data['help_max_requests_day'] = $this->language->get('help_max_requests_day');
		$data['help_right_to_be_forgotten'] = $this->language->get('help_right_to_be_forgotten');
		$data['help_store_policy_acceptance'] = $this->language->get('help_store_policy_acceptance');
		$data['help_forms_are_private'] = $this->language->get('help_forms_are_private');

		$data['settings'] = $this->model_setting_setting->getSetting('module_gdpr');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('module_gdpr', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gdpr', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/gdpr', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['gdpr_status'])) {
			$data['gdpr_status'] = $this->request->post['gdpr_status'];
		} else {
			$data['gdpr_status'] = $this->config->get('gdpr_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gdpr', $data));
	}

	public function install() {

		/*
			Status:
				0 - requested, email not confirmed;
				1 - email confirmed;
				2 - processed
		 */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_request (
			`request_id` int(11) NOT NULL AUTO_INCREMENT,
			`email` varchar(255) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`request_type` tinyint(1) NOT NULL,
			`http_user_agent` text,
			`http_accept_language` text,
			`server_addr` varchar(255),
			`remote_addr` varchar(255),
			`code` varchar(255),
			`confirmation_string` varchar(50),
			`timestamp` int(11),
			`request_time` datetime,
			PRIMARY KEY (`request_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		/* Record Privacy Policy acceptance */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_policy_accepted (
			`policy_acceptance_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`customer_email` varchar(255) NOT NULL,
			`policy_id` int(11) NOT NULL,
			`policy_name` varchar(255),
			`policy_content` text,
			`date_accepted` datetime,
			PRIMARY KEY (`policy_acceptance_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		/* Record Data Breach Notification */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_breach_notification (
			`breach_id` int(11) NOT NULL AUTO_INCREMENT,
			`store_id` int(11) NOT NULL,
			`address_commissioner` varchar(1000) NOT NULL,
			`address_store` varchar(1000) NOT NULL,
			`email_commissioner` varchar(255) NOT NULL,
			`email_bcc` varchar(1000) NOT NULL,
			`subject` varchar(1000) NOT NULL,
			`subject_customers` varchar(1000) NOT NULL,
			`date_of_breach` varchar(1000) NOT NULL,
			`date_of_discovery` varchar(1000) NOT NULL,
			`contact_details_of_person_reporting` varchar(1000) NOT NULL,
			`contact_email` varchar(255) NOT NULL,
			`number_of_accounts_affected` int(11) NOT NULL,
			`message_incident` text,
			`message_incident_customers` text,
			`message_action` text,
			`message_action_customers` text,
			`name` varchar(1000) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`status_customers` tinyint(1) NOT NULL,
			`date_added` datetime,
			`date_updated` datetime,
			PRIMARY KEY (`breach_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		/* Customer notification records */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_breach_notification_customers_emailed (
			`customer_notification_id` int(11) NOT NULL AUTO_INCREMENT,
			`breach_id` int(11) NOT NULL,
			`customer_id` int(11) NOT NULL,
			`store_email` varchar(255) NOT NULL,
			`customer_email` varchar(255) NOT NULL,
			`firstname` varchar(255) NOT NULL,
			`lastname` varchar(1000) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`date_added` datetime,
			`date_updated` datetime,
			PRIMARY KEY (`customer_notification_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		/* 1.6 - Restriction of data processing */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_restriction_of_processing (
			`customer_id` int(11) NOT NULL,
			`restriction_status` tinyint(1) NOT NULL,
			`date_updated` datetime,
			PRIMARY KEY (`customer_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		$this->load->model('setting/setting');

		$config = $this->model_setting_setting->getSetting('config');

		$header = $config['config_name'] . ', ' . $config['config_address'];
		$footer = $config['config_name'] . ', ' . $config['config_address'];

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		$starter_settings = array(
			'module_gdpr_status' => 0,
			'module_gdpr_max_requests_day' =>  3,
			'module_gdpr_right_to_be_forgotten' =>  0,
			'module_gdpr_store_policy_acceptance' => 0,
			'module_gdpr_forms_are_private' => 1,
		);

		foreach($languages as $language) {
			$starter_settings['module_gdpr_locations_of_other_data'][$language['language_id']] = '';
			$starter_settings['module_gdpr_locations_of_servers'][$language['language_id']] = '';
			$starter_settings['module_gdpr_pending_status'][$language['language_id']] = 'Pending';
			$starter_settings['module_gdpr_confirmed_status'][$language['language_id']] = 'Confirmed';
			$starter_settings['module_gdpr_emailed_status'][$language['language_id']] = 'Emailed';
			$starter_settings['module_gdpr_account_deleted_status'][$language['language_id']] = 'Deleted';
			$starter_settings['module_gdpr_email_header'][$language['language_id']] = '';
			$starter_settings['module_gdpr_email_footer'][$language['language_id']] = '';
		}

		$this->model_setting_setting->editSetting('module_gdpr', $starter_settings);

		$this->load->language('extension/extension/module');

		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
		} else {
			$this->load->model('user/user_group');

			// Add permissions
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/report/customer_gdpr');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/report/customer_gdpr');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/report/customer_gdpr_policy');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/report/customer_gdpr_policy');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr/report_breach_commissioner');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr/report_breach_commissioner');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr/report_breach_customers');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr/report_breach_customers');

			$this->model_setting_setting->editSetting('module_gdpr', $starter_settings);

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

	}

 	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gdpr_breach_notification");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gdpr_breach_notification_customers_emailed");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gdpr_request");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gdpr_policy_accepted");

  }

	public function language() {
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		echo('<pre>');
		var_dump($languages);
	}

	public function update() {
		// 1.3.1
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_policy_accepted (
			`policy_acceptance_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`customer_email` varchar(255) NOT NULL,
			`policy_id` int(11) NOT NULL,
			`policy_name` varchar(255),
			`policy_content` text,
			`date_accepted` datetime,
			PRIMARY KEY (`policy_acceptance_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		// Add user permissions (1.3)
		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/report/customer_gdpr');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/report/customer_gdpr');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/report/customer_gdpr_policy');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/report/customer_gdpr_policy');

		// 1.3.2 Changes how settings are stored to support multilingual settings.
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		// Only update if the settings are in the old format
		if(!is_array($settings['module_gdpr_locations_of_other_data']))
		{
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();

			$starter_settings = array(
				'module_gdpr_status' => $settings['module_gdpr_status'],
				'module_gdpr_max_requests_day' =>  $settings['module_gdpr_max_requests_day'],
				'module_gdpr_right_to_be_forgotten' =>  $settings['module_gdpr_right_to_be_forgotten'],
				'module_gdpr_store_policy_acceptance' => 0,
				'module_gdpr_forms_are_private' => 1,
			);

			foreach($languages as $language) {
				$starter_settings['module_gdpr_locations_of_other_data'][$language['language_id']] = $settings['module_gdpr_locations_of_other_data'];
				$starter_settings['module_gdpr_locations_of_servers'][$language['language_id']] = $settings['module_gdpr_locations_of_servers'];
				$starter_settings['module_gdpr_pending_status'][$language['language_id']] = $settings['module_gdpr_pending_status'];
				$starter_settings['module_gdpr_confirmed_status'][$language['language_id']] = $settings['module_gdpr_confirmed_status'];
				$starter_settings['module_gdpr_emailed_status'][$language['language_id']] = $settings['module_gdpr_emailed_status'];
				$starter_settings['module_gdpr_account_deleted_status'][$language['language_id']] = $settings['module_gdpr_account_deleted_status'];
				$starter_settings['module_gdpr_email_header'][$language['language_id']] = $settings['module_gdpr_email_header'];
				$starter_settings['module_gdpr_email_footer'][$language['language_id']] = $settings['module_gdpr_email_footer'];
			}

			$this->model_setting_setting->editSetting('module_gdpr', $starter_settings);
		}

		// 1.5.0
		/* Record Data Breach Notification */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_breach_notification (
			`breach_id` int(11) NOT NULL AUTO_INCREMENT,
			`store_id` int(11) NOT NULL,
			`address_commissioner` varchar(1000) NOT NULL,
			`address_store` varchar(1000) NOT NULL,
			`email_commissioner` varchar(255) NOT NULL,
			`email_bcc` varchar(1000) NOT NULL,
			`subject` varchar(1000) NOT NULL,
			`subject_customers` varchar(1000) NOT NULL,
			`date_of_breach` varchar(1000) NOT NULL,
			`date_of_discovery` varchar(1000) NOT NULL,
			`contact_details_of_person_reporting` varchar(1000) NOT NULL,
			`contact_email` varchar(255) NOT NULL,
			`number_of_accounts_affected` int(11) NOT NULL,
			`message_incident` text,
			`message_incident_customers` text,
			`message_action` text,
			`message_action_customers` text,
			`name` varchar(1000) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`status_customers` tinyint(1) NOT NULL,
			`date_added` datetime,
			`date_updated` datetime,
			PRIMARY KEY (`breach_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		/* Customer notification records */
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_breach_notification_customers_emailed (
			`customer_notification_id` int(11) NOT NULL AUTO_INCREMENT,
			`breach_id` int(11) NOT NULL,
			`customer_id` int(11) NOT NULL,
			`store_email` varchar(255) NOT NULL,
			`customer_email` varchar(255) NOT NULL,
			`firstname` varchar(255) NOT NULL,
			`lastname` varchar(1000) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`date_added` datetime,
			`date_updated` datetime,
			PRIMARY KEY (`customer_notification_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		// Add user permissions (1.5)
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr/report_breach_commissioner');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr/report_breach_commissioner');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr/report_breach_customers');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr/report_breach_customers');

		// 1.6 Restriction of processing
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_restriction_of_processing (
			`customer_id` int(11) NOT NULL,
			`restriction_status` tinyint(1) NOT NULL,
			`date_updated` datetime,
			PRIMARY KEY (`customer_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
		);

		echo('GDPR tables and settings updated up to version 1.6.0');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gdpr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}



}
