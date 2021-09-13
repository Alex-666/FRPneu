<?php
class ControllerExtensionModuleGdprGdprMailer extends Controller {

	// Breach notification statuses
	public $status_unknown = 0;
	public $status_pending = 1;
	public $status_generated = 2;
	public $status_emailed = 3;
	public $status_failed = 6;

	public $minutes_in_hour = 60;
	public $seconds_in_minute = 60;
	public $seconds_in_hour = 3600;
	public $miliseconds_in_second = 1000000;

	public $part_size = 50;

	private $key = 'hu3fd65HJ09iur5ZZ568971dort5t4y6sg89346sdngsu5';

	public function index() {

		if(empty($this->request->get['batch_size']) || empty($this->request->get['max_runtime']) || empty($this->request->get['key'])) {
			echo('Access Denied - request is incomplete.');
			exit;
		}

		if(!empty($this->request->get['batch_size'])) {
			$batch_size = $this->request->get['batch_size'];
		}

		if(!empty($this->request->get['max_runtime'])) {
			$max_runtime = $this->request->get['max_runtime'];
		}

		if(!empty($this->request->get['key'])) {
			$key = $this->request->get['key'];
		}

		ignore_user_abort(true);
		set_time_limit((int) $max_runtime * $this->seconds_in_minute);

		$this->load->model('extension/module/gdpr');
		$this->load->language('extension/module/gdpr_breach');
		$email_log = new Log('gdpr_breach_notificatons_emailed.txt');

		if($key != $this->key) {
			echo('Access Denied - authentication key required.');
			exit;
		}

		if($batch_size && $max_runtime && ($key == $this->key)) {

			// Get maximum time interval between emails
			$interval = ($this->seconds_in_hour * $this->miliseconds_in_second) / (int)$batch_size;
			// If runtime is less then hour speed it up
			if($max_runtime < $this->minutes_in_hour) {
				$interval = $interval / ($this->minutes_in_hour / $max_runtime);
			}

			/**
			* Split the batch into parts so the database is queried for each part.
			* This is to prevent duplicate email in case the script is initiated more than
			* once (by keeping the list of pending notification up to date)
			**/
			$parts = $batch_size / $this->part_size;
			$email_log->write(sprintf($this->language->get('text_batch_size'), $batch_size, $parts) . "\r\n");
			for ($i = 1; $i <= $parts; $i++) {
				$email_log->write($this->language->get('text_processing_part') . " " . $i . "\r\n");
				// Get pending notifications
				$notifications = $this->model_extension_module_gdpr->getBreachCustomerEmailNotificationByStatus($this->status_pending);
				$email_log->write($this->language->get('text_unprocessed_email_notifications') . " " . sizeof($notifications) . "\r\n");

				$index = 0;
				// foreach notification
				foreach($notifications as $notification) {

					// Send email
					if(!$this->validateEmail($notification['customer_email'])) {
						$email_log->write($this->language->get('error_emailing') . " " . $notification['customer_notification_id'] . " " . $this->language->get('error_email_not_valid') . "\r\n");

						// Update notification
						$this->model_extension_module_gdpr->updateBreachCustomerEmailNotificationStatus($notification['customer_notification_id'], $this->status_failed);
					} else {
						$email_log->write($this->language->get('text_emailing') . " " . $notification['customer_notification_id'] . "\r\n");

						$breach = $this->model_extension_module_gdpr->getBreachNotification($notification['breach_id']);

						$this->load->model('setting/store');
						$this->load->model('setting/setting');
						$stores = $this->model_setting_store->getStores();

						if ($breach['store_id'] > 0 && $stores) {
							foreach($stores as $store) {
								if($store['store_id'] == $breach['store_id']) {
									$store_name = $store['name'];
									$store_url = $store['url'];
									$store_settings = $this->model_setting_setting->getSetting('config', $store['store_id']);
									$store_address = $store_settings['config_address'];
								}
							}

						} else {
							$store_name = $this->config->get('config_name');
							$store_url = $this->config->get('config_url');
							$store_address = $this->config->get('config_address');
						}

						$data = array(
							'breach' => $breach,
							'notification' => $notification,
							'store_name' => $store_name,
							'store_url' => $store_url,
							'store_address' => $store_address,
							'text_contact_info' => $this->language->get('text_contact_info'),
							'text_email' => $this->language->get('text_email'),
							'text_report_to_customer_contact' => $this->language->get('text_report_to_customer_contact'),
							'text_greeting' => $this->language->get('text_greeting'),
							'text_report_to_customer_intro' => $this->language->get('text_report_to_customer_intro'),
							'text_report_to_customer_details' => sprintf($this->language->get('text_report_to_customer_details'), $breach['date_of_breach']),
							'text_report_to_customer_data_exposed' => $this->language->get('text_report_to_customer_data_exposed'),
							'text_report_to_customer_actions_taken' => $this->language->get('text_report_to_customer_actions_taken'),
							'text_report_to_customer_data_ending' => $this->language->get('text_report_to_customer_data_ending'),
							'text_subject' => $this->language->get('text_subject'),
						);

						// Load mail template with data
						$html = $this->load->view('extension/module/gdpr/breach_customer', $data);

						$subject = $data['text_subject'] . ' ' . $store_name;

						$demo_mode = $this->model_setting_setting->getSetting('module_demo');

						if(empty($demo_mode['module_demo_gdpr']) || $demo_mode['module_demo_gdpr'] !== '1') {
							$this->sendEmail($notification['store_email'], $notification['customer_email'], $store_name, $html, $subject);
						}

						// Update notification
						$this->model_extension_module_gdpr->updateBreachCustomerEmailNotificationStatus($notification['customer_notification_id'], $this->status_emailed);
					}

					usleep($interval);
					$index++;
				}
			}

		}

	}

	/**
	 * Send email
	 * @param  [string] $from
	 * @param  [string] $to
	 * @param  [string] $store_name
	 * @param  [string] $html
	 * @param  [string] $subject
	 * @param  [string] $file
	 * @return void
	 */
	public function sendEmail($from, $to, $store_name, $html, $subject, $file = null) {

		$mail = new Mail($this->config->get('config_mail_engine'));
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

		if($file) {
			$mail->addAttachment($file);
		}

		$mail->send();
	}

	/**
	 * Validate if a string is an email address
	 * @param  [string] $email
	 * @return [bool]
	 */
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
