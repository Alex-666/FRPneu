<?php
class ControllerExtensionReportCustomerGdprPolicy extends Controller {

	public function index() {
		$this->load->language('extension/report/customer_gdpr');
		$this->load->language('extension/report/customer_gdpr_policy');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_customer_gdpr_policy', $this->request->post);

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
			'href' => $this->url->link('extension/report/customer_gdpr_policy', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/report/customer_gdpr_policy', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true);

		if (isset($this->request->post['report_customer_gdpr_policy_status'])) {
			$data['report_customer_gdpr_policy_status'] = $this->request->post['report_customer_gdpr_policy_status'];
		} else {
			$data['report_customer_gdpr_policy_status'] = $this->config->get('report_customer_gdpr_policy_status');
		}

		if (isset($this->request->post['report_customer_gdpr_policy_sort_order'])) {
			$data['report_customer_gdpr_policy_sort_order'] = $this->request->post['report_customer_gdpr_policy_sort_order'];
		} else {
			$data['report_customer_gdpr_policy_sort_order'] = $this->config->get('report_customer_gdpr_policy_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/report/customer_gdpr_policy_form', $data));
	}

	/**
	 * Generate a CSV version of a policy request record
	 * @return php browser output
	 */
	public function generateCsv() {

		if (isset($this->request->get['policy_acceptance_id'])) {
			$policy_acceptance_id = $this->request->get['policy_acceptance_id'];
		} else {
			exit;
		}

		$this->load->model('extension/report/gdpr');
		$this->load->language('extension/report/customer_gdpr_policy');

		$policy_acceptance_record = $this->model_extension_report_gdpr->getPolicyAcceptanceRecord($policy_acceptance_id);

		// Array with CSV formatted data
		$products_array = array();

		$headers = array(
			'policy_acceptance_id' => $this->language->get('column_policy_acceptance_id'),
			'customer_id'      => $this->language->get('column_customer_id'),
			'customer_email'       => $this->language->get('column_customer_email'),
			'policy_id'   => $this->language->get('column_policy_acceptance_id'),
			'policy_name'      => $this->language->get('column_policy_name'),
			'policy_content'		 => $this->language->get('column_policy_content'),
			'date_accepted'		 => $this->language->get('column_date_accepted'),
		);

		// Filename
		$date = date('Y-m-d');
		$filename = $date . '-policy-acceptance-ID' . $policy_acceptance_id . '.csv';

		ob_start();
		ob_clean();

		// Response headers
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: no-cache');
		// Generate CSV
		$out = fopen('php://output', 'w');
		fputcsv($out, $headers);
		foreach($policy_acceptance_record as $record_line) {
			fputcsv($out, $record_line);
		}
		fclose($out);

	}

	public function report() {
		$this->load->language('extension/report/customer_gdpr_policy');
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/customer_gdpr_policy', 'user_token=' . $this->session->data['user_token'] . $url, true),
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

		$gdpr_terms_records_total = $this->model_extension_report_gdpr->getTotalGdprPolicyRecords($filter_data);

		$results = $this->model_extension_report_gdpr->getGdprPolicyRecords($filter_data);

		foreach ($results as $result) {
			$data['gdpr_policy_records'][$result['policy_acceptance_id']] = array(
				'policy_acceptance_id' => $result['policy_acceptance_id'],
				'customer_email' => $result['customer_email'],
				'policy_id' => $result['policy_id'],
				'policy_name' => $result['policy_name'],
				'policy_content' => substr($result['policy_content'],0,250) . '...',
				'policy_full' => $result['policy_content'],
				'date_accepted' => date($this->language->get('datetime_format'), strtotime($result['date_accepted']))
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_policy_acceptance_id'] = $this->language->get('column_policy_acceptance_id');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_policy_id'] = $this->language->get('column_policy_id');
		$data['column_policy_name'] = $this->language->get('column_policy_name');
		$data['column_policy_content'] = $this->language->get('column_policy_content');
		$data['column_date_accepted'] = $this->language->get('column_date_accepted');
		$data['column_download'] = $this->language->get('column_download');

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
		$pagination->total = $gdpr_terms_records_total ;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'] . '&code=customer_gdpr_policy' . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gdpr_terms_records_total ) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gdpr_terms_records_total  - $this->config->get('config_limit_admin'))) ? $gdpr_terms_records_total  : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gdpr_terms_records_total , ceil($gdpr_terms_records_total  / $this->config->get('config_limit_admin')));

		$data['filter_customer_email'] = $filter_customer_email;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		return $this->load->view('extension/report/customer_gdpr_policy_info', $data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/report/customer_gdpr_policy')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
