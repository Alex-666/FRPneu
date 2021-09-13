<?php
class ControllerExtensionModuleGdprReportBreachCommissioner extends Controller {
	private $error = array();

	// Breach notification statuses
	public $status_unknown = 0;
	public $status_pending = 1;
	public $status_generated = 2;
	public $status_emailed = 3;
	public $status_failed = 6;

	// Data type stored in OC
	public $personal_data_types = array('email', 'name', 'address', 'credit_card', 'order_history');

	public function index() {
		$this->load->language('extension/module/gdpr/report_breach');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/gdpr');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/module/gdpr/report_breach');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'extension/module/gdpr/report_breach_commissioner')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if (!$this->request->post['address-commissioner']) {
				$json['error']['address_commissioner'] = $this->language->get('error_address_commissioner');
			}

			if (!$this->request->post['address-store']) {
				$json['error']['address_store'] = $this->language->get('error_address_store');
			}

			if (!$this->request->post['contact-details-of-person-reporting']) {
				$json['error']['contact_details_of_person_reporting'] = $this->language->get('error_contact_details_of_person_reporting');
			}

			if (!$this->request->post['contact-email']) {
				$json['error']['contact_email'] = $this->language->get('error_contact_email');
			}

			if (!$this->request->post['date-of-breach']) {
				$json['error']['date_of_breach'] = $this->language->get('error_date_of_breach');
			}

			if (!$this->request->post['date-of-discovery']) {
				$json['error']['date_of_discovery'] = $this->language->get('error_date_of_discovery');
			}

			if (!$this->request->post['message-action']) {
				$json['error']['message_action'] = $this->language->get('error_message_action');
			}

			if (!$this->request->post['message-action-customers']) {
				$json['error']['message_action_customers'] = $this->language->get('error_message_action_customers');
			}

			if (!$this->request->post['message-incident']) {
				$json['error']['message_incident'] = $this->language->get('error_message_incident');
			}

			if (!$this->request->post['message-incident-customers']) {
				$json['error']['message_incident_customers'] = $this->language->get('error_message_incident_customers');
			}

			if (!$this->request->post['name']) {
				$json['error']['name'] = $this->language->get('error_message_incident_name');
			}

			if (!$this->request->post['subject']) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if (!$this->request->post['subject-customers']) {
				$json['error']['subject_customers'] = $this->language->get('error_subject_customers');
			}


			if (!$json) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}

				$data = array(
					'store_id' => $this->request->post['store_id'],
					'store_name' => $store_name,
					'address_commissioner' => $this->request->post['address-commissioner'],
					'address_store' => $this->request->post['address-store'],
					'date_of_breach' => $this->request->post['date-of-breach'],
					'date_of_discovery' => $this->request->post['date-of-discovery'],
					'contact_details_of_person_reporting' => $this->request->post['contact-details-of-person-reporting'],
					'contact_email' => $this->request->post['contact-email'],
					'number_of_accounts_affected' => $this->request->post['number-of-accounts-affected'],
					'message_incident' => $this->request->post['message-incident'],
					'message_incident_customers' => $this->request->post['message-incident-customers'],
					'message_action' => $this->request->post['message-action'],
					'message_action_customers' => $this->request->post['message-action-customers'],
					'name' => $this->request->post['name'],
					'status' => 1,
					'status_customers' => 1,
					'subject' => $this->request->post['subject'],
					'subject_customers' => $this->request->post['subject-customers'],
					'date_added' => date("Y-m-d H:i:s"),
					'date_updated' => date("Y-m-d H:i:s"),
				);

				// Store record of the notification in the database???
				$this->load->model('extension/module/gdpr');
				$breach_notification_id = $this->model_extension_module_gdpr->addBreachNotification($data);

				if($breach_notification_id) {
					$json['success'] = $this->language->get('text_success_saved_record');
				} else {
					$json['error'] = $this->language->get('error_saving_breach_notification_failed');
				}

			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$json = array();

		$this->load->model('extension/module/gdpr');
		$this->load->model('setting/setting');
		$this->load->language('extension/module/gdpr/report_breach');

		$this->document->setTitle($this->language->get('heading_title'));

		$config = $this->model_setting_setting->getSetting('config');

		$this->load->language('extension/module/gdpr/report_breach');
		$this->load->model('extension/module/gdpr');

		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			$this->form();
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'extension/module/gdpr/report_breach_commissioner')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if (!$this->request->post['address-commissioner']) {
				$json['error']['address_commissioner'] = $this->language->get('error_address_commissioner');
			}

			if (!$this->request->post['address-store']) {
				$json['error']['address_store'] = $this->language->get('error_address_store');
			}

			if (!$this->request->post['contact-details-of-person-reporting']) {
				$json['error']['contact_details_of_person_reporting'] = $this->language->get('error_contact_details_of_person_reporting');
			}

			if (!$this->request->post['contact-email']) {
				$json['error']['contact_email'] = $this->language->get('error_contact_email');
			}

			if (!$this->request->post['date-of-breach']) {
				$json['error']['date_of_breach'] = $this->language->get('error_date_of_breach');
			}

			if (!$this->request->post['date-of-discovery']) {
				$json['error']['date_of_discovery'] = $this->language->get('error_date_of_discovery');
			}

			if (!$this->request->post['message-action']) {
				$json['error']['message_action'] = $this->language->get('error_message_action');
			}

			if (!$this->request->post['message-action-customers']) {
				$json['error']['message_action_customers'] = $this->language->get('error_message_action_customers');
			}

			if (!$this->request->post['message-incident']) {
				$json['error']['message_incident'] = $this->language->get('error_message_incident');
			}

			if (!$this->request->post['message-incident-customers']) {
				$json['error']['message_incident_customers'] = $this->language->get('error_message_incident_customers');
			}

			if (!$this->request->post['name']) {
				$json['error']['name'] = $this->language->get('error_message_incident_name');
			}

			if (!$this->request->post['subject']) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if (!$this->request->post['subject-customers']) {
				$json['error']['subject_customers'] = $this->language->get('error_subject_customers');
			}


			if (!$json) {
				$this->load->model('setting/store');

				$breach_id = $this->request->get['breach_id'];
				//$breach_id = $this->request->get['breach_id'];

				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}

				$data = array(
					'store_id' => $this->request->post['store_id'],
					'store_name' => $store_name,
					'address_commissioner' => $this->request->post['address-commissioner'],
					'address_store' => $this->request->post['address-store'],
					'subject_customers' => $this->request->post['subject-customers'],
					'date_of_breach' => $this->request->post['date-of-breach'],
					'date_of_discovery' => $this->request->post['date-of-discovery'],
					'contact_details_of_person_reporting' => $this->request->post['contact-details-of-person-reporting'],
					'contact_email' => $this->request->post['contact-email'],
					'number_of_accounts_affected' => $this->request->post['number-of-accounts-affected'],
					'message_incident' => $this->request->post['message-incident'],
					'message_incident_customers' => $this->request->post['message-incident-customers'],
					'message_action' => $this->request->post['message-action'],
					'message_action_customers' => $this->request->post['message-action-customers'],
					'name' => $this->request->post['name'],
					'status' => '',
					'status_customers' => '',
					'subject' => $this->request->post['subject'],
					'subject_customers' => $this->request->post['subject-customers'],
					'date_updated' => date("Y-m-d H:i:s"),
				);

				// Update of the notification
				$this->load->model('extension/module/gdpr');
				$updated = $this->model_extension_module_gdpr->editBreachNotification($breach_id, $data);

				if($updated) {
					$json['success'] = $this->language->get('text_success_saved_record');
				} else {
					$json['error'] = $this->language->get('error_saving_breach_notification_failed');
				}

			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}

	}

	public function form() {

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');
		$this->document->setTitle($this->language->get('heading_title'));

		$config = $this->model_setting_setting->getSetting('config');

		$breach = false;
		$store_name = '';
		if(!empty($this->request->get['breach_id'])) {
			$breach_id = $this->request->get['breach_id'];
			$breach = $this->model_extension_module_gdpr->getBreachNotification($breach_id);

			$store_info = $this->model_setting_store->getStore($breach['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
		}

		$data['heading_detailed'] = $this->language->get('heading_detailed');
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_add_breach'] = $this->language->get('text_add_breach');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_save_breach'] = $this->language->get('text_save_breach');
		$data['text_section_commissioner'] = $this->language->get('text_section_commissioner');
		$data['text_section_customer'] = $this->language->get('text_section_customer');
		$data['text_section_general'] = $this->language->get('text_section_general');
		$data['text_send_breach_notification'] = $this->language->get('text_send_breach_notification');
		$data['text_subject'] = sprintf($this->language->get('text_subject'), $config['config_name']);
		$data['text_total_customers'] = $this->language->get('text_total_customers');

		$data['entry_address_commissioner'] = $this->language->get('entry_address_commissioner');
		$data['entry_address_store'] = $this->language->get('entry_address_store');
		//$data['entry_breach_name'] = $this->language->get('entry_breach_name');
		$data['entry_contact_details_of_person_reporting'] = $this->language->get('entry_contact_details_of_person_reporting');
		$data['entry_contact_email'] = $this->language->get('entry_contact_email');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_email_commissioner'] = $this->language->get('entry_email_commissioner');
		$data['entry_date_of_breach'] = $this->language->get('entry_date_of_breach');
		$data['entry_date_of_discovery'] = $this->language->get('entry_date_of_discovery');
		//$data['entry_email_cc'] = $this->language->get('entry_email_cc');
		$data['entry_email_bcc'] = $this->language->get('entry_email_bcc');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_subject_customers'] = $this->language->get('entry_subject_customers');
		$data['entry_message_action'] = $this->language->get('entry_message_action');
		$data['entry_message_action_customers'] = $this->language->get('entry_message_action_customers');
		$data['entry_message_incident'] = $this->language->get('entry_message_incident');
		$data['entry_message_incident_customers'] = $this->language->get('entry_message_incident_customers');
		$data['entry_number_of_accounts_affected'] = $this->language->get('entry_number_of_accounts_affected');
		$data['entry_personal_data_types'] = $this->language->get('entry_personal_data_types');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_to'] = $this->language->get('entry_to');

		$data['help_address_commissioner'] = $this->language->get('help_address_commissioner');
		$data['help_address_store'] = $this->language->get('help_address_store');
		$data['help_name'] = $this->language->get('help_name');
		$data['help_contact_details_of_person_reporting'] = $this->language->get('help_contact_details_of_person_reporting');
		$data['help_contact_email'] = $this->language->get('help_contact_email');
		$data['help_data_commissioner'] = $this->language->get('help_data_commissioner');
		$data['help_date_of_breach'] = $this->language->get('help_date_of_breach');
		$data['help_date_of_discovery'] = $this->language->get('help_date_of_discovery');
		//$data['help_email_cc'] = $this->language->get('help_email_cc');
		$data['help_email_bcc'] = $this->language->get('help_email_bcc');
		$data['help_message_action'] = $this->language->get('help_message_action');
		$data['help_message_action_customers'] = $this->language->get('help_message_action_customers');
		$data['help_message_incident'] = $this->language->get('help_message_incident');
		$data['help_message_incident_customers'] = $this->language->get('help_message_incident_customers');
		$data['help_number_of_accounts_affected'] = $this->language->get('help_number_of_accounts_affected');
		$data['help_subject'] = $this->language->get('help_subject');
		$data['help_subject_customers'] = $this->language->get('help_subject_customers');

		// Number of all customer accounts in the system
		$this->load->model('customer/customer');
		$data['total_customers'] = $this->model_customer_customer->getTotalCustomers();

		if($breach) {
			$data['breach'] = $breach;
		} else {
			$data['breach'] = array(
				'breach_id' => false,
				'address_commissioner' => '',
				'address_store' => $config['config_name'] . "\n" . $config['config_address'],
				'store_id' => 0,
				'store_name' => '',
				'date_of_breach' => '',
				'date_of_discovery' => '',
				'contact_details_of_person_reporting' => '',
				'contact_email' => '',
				'name' => '',
				'number_of_accounts_affected' => $data['total_customers'],
				'message_incident' => '',
				'message_incident_customers' => '',
				'message_action' => '',
				'message_action_customers' => '',
				'status' => '',
				'subject' => $this->language->get('text_breach_email_subject') . ' - ' . $config['config_name'],
				'subject_customers' => $this->language->get('text_breach_email_subject') . ' - ' . $config['config_name'],
				'date_added' => date("Y-m-d H:i:s"),
			);
		}

		$data['button_add_breach'] = $this->language->get('button_add_breach');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save_breach'] = $this->language->get('button_save_breach');
		$data['button_send'] = $this->language->get('button_send');

		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['cancel'] = $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gdpr/report_breach_commissioner', $data));
	}

	public function getPdf() {

		require_once DIR_SYSTEM . "library/mpdf-6.0.0/mpdf.php";
		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');

		if(!empty($this->request->get['breach_id'])) {
			$breach_id = $this->request->get['breach_id'];
		} else {
			$json['error']['warning'] = $this->language->get('error_breach_id');
			$breach_id = false;
		}

		$this->load->model('setting/store');

		if($breach_id) {
			$breach = $this->model_extension_module_gdpr->getBreachNotification($breach_id);
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
				'entry_email_commissioner' => $this->language->get('entry_email_commissioner'),
				'entry_subject' => $this->language->get('entry_subject'),
				'entry_message_action' => $this->language->get('entry_message_action'),
				'entry_message_incident' => $this->language->get('entry_message_incident'),
				'entry_number_of_accounts_affected' => $this->language->get('entry_number_of_accounts_affected'),
				'entry_store' => $this->language->get('entry_store'),
				'entry_to' => $this->language->get('entry_to'),
				'heading_title' => $this->language->get('heading_title'),
				'heading_detailed' => $this->language->get('heading_detailed'),
				'text_email_commissioner_body' => $this->language->get('text_commissioner_notification_email_body'),
				'text_report_to_commissioner_actions_taken' => $this->language->get('text_report_to_commissioner_actions_taken'),
				'text_report_to_commissioner_contact'  => $this->language->get('text_report_to_commissioner_contact'),
				'text_report_to_commissioner_ending' => $this->language->get('text_report_to_commissioner_ending'),
				'text_report_to_commissioner_data_exposed' => $this->language->get('text_report_to_commissioner_data_exposed'),
				'text_report_to_commissioner_details' => sprintf($this->language->get('text_report_to_commissioner_details'), $breach['date_of_discovery']),
				'text_report_to_commissioner_intro' => $this->language->get('text_report_to_commissioner_intro'),
				'text_date' => $this->language->get('text_date'),
				'text_from' => $this->language->get('text_from'),
				'text_to' => $this->language->get('text_to'),
				'text_signature' => $this->language->get('text_signature'),
			),
			'breach_id' => $breach_id,
			'store_id' => $breach['store_id'],
			'store_name' => $store_name,
			'address_commissioner' => $breach['address_commissioner'],
			'address_store' => $breach['address_store'],
		  'subject' => $this->language->get('text_email_commissioner_subject') . ' - ' . $store_name,
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

		// Load mail template with data
		$html = $this->load->view('extension/module/gdpr/pdf/breach_commissioner', $data);

		$mpdf = new Mpdf([
			'default_font_size' => 12,
			'default_font' => 'dejavusans',
		]);
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	public function getPdfAttachment($data) {

		require_once DIR_SYSTEM . "library/mpdf-6.0.0/mpdf.php";

		$pdf_file = DIR_DOWNLOAD . date('Y-m-d') . '-' . $this->language->get('data_breach_pdf_filename') . '-' . $data['breach_id'] . '.pdf';

		// Load mail template with data
		$html = $this->load->view('extension/module/gdpr/pdf/breach_commissioner', $data);

		$mpdf = new Mpdf();
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_file, 'F');

		return $pdf_file;

	}

	public function getList() {

		$this->load->language('extension/module/gdpr/report_breach');

		$data['entry_email_commissioner'] = $this->language->get('entry_email_commissioner');
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
			$sort = 'br.breach_id';
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
			'href' => $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL')
		);

		$data['send'] = $this->url->link('extension/module/gdpr/report_breach_commissioner/send', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['download_pdf'] = $this->url->link('extension/module/gdpr/report_breach_commissioner/getPdf', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['add'] = $this->url->link('extension/module/gdpr/report_breach_commissioner/form', 'user_token=' . $this->session->data['user_token'], 'SSL');

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
				'email_commissioner' => !empty($result['email_commissioner']) ? $result['email_commissioner'] : '',
				'status'        => $result['status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_of_breach' => $result['date_of_breach'],
				'date_of_discovery' => $result['date_of_discovery'],
				'name' => $result['name'],
				'number_of_accounts_affected' => $result['number_of_accounts_affected'],
				'download_pdf'          => $this->url->link('extension/module/gdpr/report_breach_commissioner/getPdf', 'user_token=' . $this->session->data['user_token'] . '&breach_id=' . $result['breach_id'] . $url, 'SSL'),
				'send'          => $this->url->link('extension/module/gdpr/report_breach_commissioner/send', 'user_token=' . $this->session->data['user_token'] . '&breach_id=' . $result['breach_id'] . $url, 'SSL'),
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

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_list'] = $this->language->get('text_list');
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

		$data['entry_breach_notification_status'] = $this->language->get('entry_breach_notification_status');
		$data['entry_breach_id'] = $this->language->get('entry_breach_id');
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

		$data['sort_order'] = $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'] . '&sort=br.breach_id' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url, 'SSL');

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
		$pagination->url = $this->url->link('extension/module/gdpr/report_breach_commissioner', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($breach_notification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($breach_notification_total - $this->config->get('config_limit_admin'))) ? $breach_notification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $breach_notification_total, ceil($breach_notification_total / $this->config->get('config_limit_admin')));

		$data['filter_breach_notification_status'] = $filter_breach_notification_status;
		$data['filter_date_added'] = $filter_date_added;

		$this->load->model('localisation/order_status');

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['store'] = HTTPS_CATALOG;

		$data['breach_notification_statuses'] = array(
			$this->status_pending => array(
				'id' => $this->status_pending,
				'name' => $this->language->get('text_status_pending'),
			),
			$this->status_emailed => array(
				'id' => $this->status_emailed,
				'name' => $this->language->get('text_status_sent'),
			),
		);

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

		$this->response->setOutput($this->load->view('extension/module/gdpr/report_breach_commissioner_list', $data));
	}

	public function send() {

		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr/report_breach');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'extension/module/gdpr/report_breach_commissioner')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if(!empty($this->request->get['breach_id'])) {
				$breach_id = $this->request->get['breach_id'];
			} else {
				$json['error']['warning'] = $this->language->get('error_breach_id');
				$breach_id = false;
			}

			if(!empty($this->request->post['email-data-commissioner']) && $this->validateEmail($this->request->post['email-data-commissioner'])) {
				$email_commissioner = $this->request->post['email-data-commissioner'];
			} else {
				$json['error']['mail_data_commissioner'] = $this->language->get('error_mail_data_commissioner');
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
					$breach = $this->model_extension_module_gdpr->getBreachNotification($breach_id);
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
						'entry_email_commissioner' => $this->language->get('entry_email_commissioner'),
						'entry_subject' => $this->language->get('entry_subject'),
						'entry_message_action' => $this->language->get('entry_message_action'),
						'entry_message_incident' => $this->language->get('entry_message_incident'),
						'entry_number_of_accounts_affected' => $this->language->get('entry_number_of_accounts_affected'),
						'entry_store' => $this->language->get('entry_store'),
						'entry_to' => $this->language->get('entry_to'),
						'heading_title' => $this->language->get('heading_title'),
						'heading_detailed' => $this->language->get('heading_detailed'),
						'text_email_commissioner_body' => $this->language->get('text_email_commissioner_body'),
						'text_report_to_commissioner_actions_taken' => $this->language->get('text_report_to_commissioner_actions_taken'),
						'text_report_to_commissioner_contact'  => $this->language->get('text_report_to_commissioner_contact'),
						'text_report_to_commissioner_ending' => $this->language->get('text_report_to_commissioner_ending'),
						'text_report_to_commissioner_data_exposed' => $this->language->get('text_report_to_commissioner_data_exposed'),
						'text_report_to_commissioner_details' => sprintf($this->language->get('text_report_to_commissioner_details'), $breach['date_of_discovery']),
						'text_report_to_commissioner_intro' => $this->language->get('text_report_to_commissioner_intro'),
						'text_date' => $this->language->get('text_date'),
						'text_from' => $this->language->get('text_from'),
						'text_to' => $this->language->get('text_to'),
						'text_signature' => $this->language->get('text_signature'),
					),
					'breach_id' => $breach_id,
					'store_id' => $breach['store_id'],
					'store_name' => $store_name,
					'address_commissioner' => $breach['address_commissioner'],
					'address_store' => $breach['address_store'],
				  'subject' => $this->language->get('text_email_commissioner_subject') . ' - ' . $store_name,
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

				$this->load->model('extension/module/gdpr');

				$store_email = $this->config->get('config_email');

				// Load mail template with data
				$html = $this->load->view('extension/module/gdpr/mail/breach_notification_email_body', $data);

				if (!empty($email_commissioner)) {

					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $data['subject'] . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($html, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";

					$pdf_file = $this->getPdfAttachment($data);

					if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email_commissioner)) {
						$this->sendBreachEmail($store_email, $email_commissioner, $store_name, $message, $data['subject'], $pdf_file);
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
					$this->model_extension_module_gdpr->addEmailsToBreachNotification($breach_id, $email_commissioner, $email_bcc);
					// Update status of the notification record to emailed
					$this->model_extension_module_gdpr->updateBreachNotificationStatus($breach_id, $this->status_emailed);

					// Delete the PDF
					if (file_exists($pdf_file)) {
						//unlink($pdf_file);
					}


					$json['success'] = $this->language->get('text_success');
					$json['email_commissioner'] = $email_commissioner;
					$json['email_bcc'] = $email_bcc;

					$statuses = array(
						$this->status_pending => $this->language->get('text_status_pending'),
						$this->status_emailed => $this->language->get('text_status_sent'),
						$this->status_failed => $this->language->get('text_status_failed'),
					);

					$json['status'] = $statuses[$this->status_emailed];
				}

			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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


}
