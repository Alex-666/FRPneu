<?php
class ControllerExtensionReportCustomerGdpr extends Controller {

	public function index() {
		$this->load->language('extension/report/customer_gdpr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_customer_gdpr', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true));
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
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/report/customer_gdpr', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/report/customer_gdpr', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true);

		if (isset($this->request->post['report_customer_gdpr_status'])) {
			$data['report_customer_gdpr_status'] = $this->request->post['report_customer_gdpr_status'];
		} else {
			$data['report_customer_gdpr_status'] = $this->config->get('report_customer_gdpr_status');
		}

		if (isset($this->request->post['report_customer_gdpr_sort_order'])) {
			$data['report_customer_gdpr_sort_order'] = $this->request->post['report_customer_gdpr_sort_order'];
		} else {
			$data['report_customer_gdpr_sort_order'] = $this->config->get('report_customer_gdpr_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/report/customer_gdpr_form', $data));
	}

	public function report() {
		$this->load->language('extension/report/customer_gdpr');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$settings = $this->model_setting_setting->getSetting('module_gdpr');

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = null;
		}

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . urlencode($this->request->get['filter_customer_email']);
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$language_code = $this->model_setting_setting->getSettingValue('config_admin_language');
		$language_id = $languages[$language_code]['language_id'];

		$data['status'] = array(
			'0' => $settings['module_gdpr_pending_status'][$language_id],
			'1' => $settings['module_gdpr_confirmed_status'][$language_id],
			'2' => $settings['module_gdpr_emailed_status'][$language_id],
			'3' => $settings['module_gdpr_account_deleted_status'][$language_id],
		);

		$data['row_class'] = array(
			'0' => 'warning',
			'1' => 'info',
			'2' => 'success',
			'3' => 'warning',
		);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/customer_gdpr', 'user_token=' . $this->session->data['user_token'] . $url, true),
			'text' => $this->language->get('heading_title')
		);

		$this->load->model('extension/report/gdpr');

		$data['activities'] = array();

		$filter_data = array(
			'filter_customer_email'   => $filter_customer_email,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$gdpr_requests_total = $this->model_extension_report_gdpr->getTotalGdprRequests($filter_data);

		$results = $this->model_extension_report_gdpr->getGdprRequests($filter_data);

		foreach ($results as $result) {
			$data['gdpr_requests'][] = array(
				'request_id' => $result['request_id'],
				'email' => $result['email'],
				'status' => $result['status'],
				'http_user_agent' => $result['http_user_agent'],
				'http_accept_language' => $result['http_accept_language'],
				'server_addr' => $result['server_addr'],
				'remote_addr' => $result['remote_addr'],
				'code' => $result['code'],
				'confirmation_string' => $result['confirmation_string'],
				'timestamp' => $result['timestamp'],
				'comment'    => $result['http_user_agent'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['request_time']))
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_request_id'] = $this->language->get('column_request_id');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_http_user_agent'] = $this->language->get('column_http_user_agent');
		$data['column_http_accept_language'] = $this->language->get('column_http_accept_language');
		$data['column_server_addr'] = $this->language->get('column_server_addr');
		$data['column_remote_addr'] = $this->language->get('column_remote_addr');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['entry_customer_email'] = $this->language->get('entry_customer_email');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $gdpr_requests_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'] . '&code=customer_gdpr' . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gdpr_requests_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gdpr_requests_total - $this->config->get('config_limit_admin'))) ? $gdpr_requests_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gdpr_requests_total, ceil($gdpr_requests_total / $this->config->get('config_limit_admin')));

		$data['filter_customer_email'] = $filter_customer_email;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		return $this->load->view('extension/report/customer_gdpr_info', $data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/report/customer_gdpr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
