<?php
class ControllerExtensionModuleDuplicates extends Controller {

	private $error = array();

	public function index() {

		//$this->load->language('extension/module/duplicates');
        $this->load->model('extension/module/duplicates');

		$this->load->model('setting/setting');
		$this->load->model('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));
var_dump($this->request->server['REQUEST_METHOD']);
var_dump($this->validate());
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_duplicates', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

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
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/duplicates', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/duplicates', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];
/*
		if (isset($this->request->post['amazon_login_button_type'])) {
			$data['amazon_login_button_type'] = $this->request->post['amazon_login_button_type'];
		} elseif ($this->config->get('amazon_login_button_type')) {
			$data['amazon_login_button_type'] = $this->config->get('amazon_login_button_type');
		} else {
			$data['amazon_login_button_type'] = 'LwA';
		}

		if (isset($this->request->post['amazon_login_button_colour'])) {
			$data['amazon_login_button_colour'] = $this->request->post['amazon_login_button_colour'];
		} elseif ($this->config->get('amazon_login_button_colour')) {
			$data['amazon_login_button_colour'] = $this->config->get('amazon_login_button_colour');
		} else {
			$data['amazon_login_button_colour'] = 'gold';
		}

		if (isset($this->request->post['amazon_login_button_size'])) {
			$data['amazon_login_button_size'] = $this->request->post['amazon_login_button_size'];
		} elseif ($this->config->get('amazon_login_button_size')) {
			$data['amazon_login_button_size'] = $this->config->get('amazon_login_button_size');
		} else {
			$data['amazon_login_button_size'] = 'medium';
		}
		*/

		if (isset($this->request->post['duplicates_status'])) {
			$data['duplicates_status'] = $this->request->post['duplicates_status'];
		} else {
			$data['duplicates_status'] = $this->config->get('duplicates_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $query = $this->db->query("SELECT `model`, COUNT(`model`) AS `count_model` FROM `" . DB_PREFIX . "product` GROUP BY `model` HAVING `count_model` > 1");
        var_dump($query);

//WHERE location = 'pohoda' AND LENGTH(TRIM(model)) > 0 AND LENGTH(image) > 0 AND status = 1

		$this->response->setOutput($this->load->view('extension/module/duplicates', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/duplicates')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
