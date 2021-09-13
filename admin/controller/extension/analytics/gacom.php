<?php
class ControllerExtensionAnalyticsGAcom extends Controller {
	private $module_name = "gacom";
	private $error = array();
	private $location = [
		'module'		=> 'extension/analytics',
		'marketplace'	=> 'marketplace/extension'
	];
	private $names = [
		'token'		=> 'user_token',
		'setting'	=> 'analytics_gacom',
		'action'	=> 'action',
		'template_extension'	=> ''
	];

	
	private function send_registration($data) {
		$register_url = 'https://www.reactivem.com/service/register/' . $this->module_name;
		$data["opencart-version"] = VERSION;
		unset($data[$this->names["setting"] . '_register']);
		unset($data['sla']);
		// post data
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($register_url, false, $context);
	}

	
	private function breadcrumbs() {
		$this->load->language($this->location['module'] . '/' . $this->module);

		$breadcrumbs = [];
		$breadcrumbs[] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->names['token'] . '=' . $this->session->data[$this->names['token']], true)
		);

		$breadcrumbs[] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($this->location['module'], $this->names['token'] . '=' . $this->session->data[$this->names['token']], true)
		);

		$breadcrumbs[] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->location['module'] . '/' . $this->module_name, $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&store_id=' . $this->request->get['store_id'], true)
		);
		return $breadcrumbs;
	}


	public function index() {
		$this->load->language($this->location['module'] . '/' . $this->module_name);
		$this->load->model('setting/setting');
		$this->document->setTitle($this->language->get('heading_title'));

		// Initialize $data with values from the settings
		$data = [
			$this->names['setting'] . '_status' => 
				$this->model_setting_setting->getSettingValue($this->names['setting'] . '_status', $this->request->get['store_id']),
			$this->names['setting'] . '_trackingid' => 
				$this->model_setting_setting->getSettingValue($this->names['setting'] . '_trackingid', $this->request->get['store_id'])
		];

		// flag to denote if registration is complete
		$is_registered = $this->config->get($this->names['setting'] . '_register') == $this->module_name;
		if (!$is_registered)
			$this->response->redirect($this->url->link($this->location['module'] . '/'.$this->module_name.'/registration', $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&store_id='.$this->request->get['store_id'], true));


		// If form is submitted
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$settings = [
				$this->names['setting'] . '_register' => $this->module_name,
				$this->names['setting'] . '_status' => $this->request->post[ $this->names['setting'] . '_status' ],
				$this->names['setting'] . '_trackingid' => $this->request->post[ $this->names['setting'] . '_trackingid']
			];
			$this->model_setting_setting->editSetting($this->names['setting'], $settings, $this->request->get['store_id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link($this->location['marketplace'], $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&type=analytics', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// breadcrumbs
		$data['breadcrumbs'] = $this->breadcrumbs();

		// form
		$data[$this->names['action']] = $this->url->link($this->location['module'] . '/' . $this->module_name, $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&store_id=' . $this->request->get['store_id'], true);
		$data['cancel'] = $this->url->link($this->location['marketplace'], $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&type=module', true);

		// check if form submitted. Load settings posted if we reached this point.
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data[$this->names['setting'] . '_status'] = $this->request->post[$this->names['setting'] . '_status'];
			$data[$this->names['setting'] . '_trackingid'] = $this->request->post[$this->names['setting'] . '_trackingid'];
		}

		// Load translations
		if (VERSION < '3') {
			foreach ([
				'text_edit',
				'text_status', 
				'text_enabled',
				'text_disabled',
				'text_button_save',
				'text_button_cancel',
				'heading_title'
			] as $text) {
				$data[$text] = $this->language->get($text);
			}
		}
		$this->response->setOutput($this->load->view($this->location['module'] . '/' . $this->module_name . $this->names['template_extension'], $data));
	}

	public function registration() {
		$this->load->language($this->location['module'] . '/' . $this->module_name);
		$this->load->model('setting/setting');
		$this->load->model('localisation/country');
		$this->document->setTitle($this->language->get('heading_title'));

		$data = [];

		// If form is submitted
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ($this->validate_registration()) {
				$this->send_registration($this->request->post);
				$settings = [
					$this->names['setting'] . '_register' => $this->module_name,
					$this->names['setting'] . '_status' => 0
				];
				$this->model_setting_setting->editSetting($this->names['setting'], $settings, $this->request->get['store_id']);
				// redirect to status setting
				$this->response->redirect($this->url->link($this->location['module'] . '/' . $this->module_name, $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&store_id=' . $this->request->get['store_id'], true));
			}
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['breadcrumbs'] = $this->breadcrumbs();

		// form
		$data[$this->names['action']] = $this->url->link($this->location['module'] . '/'.$this->module_name.'/registration', $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&store_id=' . $this->request->get['store_id'], true);
		$data['cancel'] = $this->url->link($this->location['marketplace'], $this->names['token'] . '=' . $this->session->data[$this->names['token']] . '&type=module', true);

		// Form submitted but haven't registered yet. Possibly an error in the form
		// Load form with submitted data
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			foreach([
				'owner',
				'email',
				'telephone',
				'store_name',
				'url',
				'country',
				'sla'
			] as $key) {
				if (isset($this->request->post[$key]))
					$data[$key] = $this->request->post[$key];
			}
		} else { // form is not posted. We load initial details
			/*
			 * Load registration data
			 */
			$data = array_merge($data, [
				'owner' 	=> $this->config->get('config_owner'),
				'email' 	=> $this->config->get('config_email'),
				'store_name'	=> $this->config->get('config_name'),
				'telephone'	=> $this->config->get('config_telephone'),
				'opencart-version'	=> VERSION
			]);
			// get url
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$data['url'] = HTTPS_CATALOG;
			} else {
				$data['url'] = HTTP_CATALOG;
			}
			// get country iso_code
			$country_record = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
			$data['country'] = $country_record['iso_code_2'];
		}

		// Load translations
		if (VERSION < '3') {
			foreach ([
				'text_edit',
				'text_button_save',
				'text_button_cancel',
				'heading_title',

				'text_reg_title',
				'text_reg_owner',
				'text_reg_email',
				'text_reg_telephone',
				'text_reg_storename',
				'text_reg_url',
				'text_reg_country',
				'text_reg_sla',
				'text_reg_sla_description',
				'btn_register'
			] as $text) {
				$data[$text] = $this->language->get($text);
			}
		}
		$this->response->setOutput($this->load->view($this->location['module'] . '/'.$this->module_name.'_registration' . $this->names['template_extension'], $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->location['module'] . '/'.$this->module_name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post[ $this->names['setting'] . '_trackingid']) {
			$this->error['warning'] = 'Google Analytics code required';
		}

		return !$this->error;
	}

	protected function validate_registration() {
		if (empty($this->request->post["owner"]))
			$this->error['warning'] = $this->language->get('error_owner');
		if (!filter_var($this->request->post["email"], FILTER_VALIDATE_EMAIL))
			$this->error['warning'] = $this->language->get('error_email');
		if (empty($this->request->post["store_name"]))
			$this->error['warning'] = $this->language->get('error_store_name');
		if (empty($this->request->post["sla"]))
			$this->error['warning'] = $this->language->get('error_sla');
		return !$this->error;
	}
}
?>