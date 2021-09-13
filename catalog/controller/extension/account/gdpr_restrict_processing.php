<?php
class ControllerExtensionAccountGdprRestrictProcessing extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('extension/account/gdpr_restrict_processing', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}


		$this->load->language('extension/account/gdpr');

		$this->document->setTitle($this->language->get('heading_gdpr_restrict_processing'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			// Store restriction of processing preference
			$this->load->model('extension/module/gdpr');
			$this->model_extension_module_gdpr->updateRestrictionOfProcessing($this->customer->getId(), $this->request->post['gdpr_restrict_processing']);

			// Add to activity log
			$this->load->model('account/activity');
			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
				'preference' => (int)$this->request->post['gdpr_restrict_processing'],
			);
			$this->model_account_activity->addActivity('gdpr_restriction_of_processing', $activity_data);

			$this->session->data['success'] = $this->language->get('text_success_gdpr_restriction_of_processing');

			$this->response->redirect($this->url->link('extension/account/gdpr_restrict_processing', '', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/account/gdpr', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_gdpr_restrict_processing'),
			'href' => $this->url->link('extension/account/gdpr_restrict_processing', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_gdpr_restrict_processing');

		$data['entry_gdpr_restrict_processing'] = $this->language->get('entry_gdpr_restrict_processing');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['help_gdpr_restrict_processing'] = $this->language->get('help_gdpr_restrict_processing');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['success'] = '';
		if(!empty($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		}
		$this->session->data['success'] = '';

		if (isset($this->error['gdpr_restrict_processing'])) {
			$data['error_gdpr_restrict_processing'] = $this->error['gdpr_restrict_processing'];
		} else {
			$data['error_gdpr_restrict_processing'] = '';
		}

		$data['action'] = $this->url->link('extension/account/gdpr_restrict_processing', '', true);

		if (isset($this->request->post['gdpr_restrict_processing'])) {
			$data['gdpr_restrict_processing'] = $this->request->post['gdpr_restrict_processing'];
		} else {
			$this->load->model('extension/module/gdpr');
			$data['gdpr_restrict_processing'] = $this->model_extension_module_gdpr->getRestrictionOfProcessingStatus($this->customer->getId());
		}

		$data['back'] = $this->url->link('extension/account/gdpr', '', 'SSL');

    $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

    $this->response->setOutput($this->load->view('extension/account/gdpr_restrict_processing', $data));

	}

	protected function validate() {
		return !$this->error;
	}
}
