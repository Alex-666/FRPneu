<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');
$data['avail_config_google_captcha_status'] = $this->config->get('avail_config_google_captcha_status');
					$data['google_captcha_key'] = $this->config->get('avail_config_google_captcha_public');
					$data['google_captcha_secret'] = $this->config->get('avail_config_google_captcha_secret');
			

		$data['informations'] = array();

		foreach (strpos($this->config->get('config_template'), 'journal2') === 0 ? array() : $this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		$data['text_policy_all'] = $this->language->get('text_policy_all');
        $data['notification_all'] = false;
        If ($this->config->get('config_store_id') == "0"){
            $data['text_notification_all'] = $this->language->get('text_notification_all');
            $data['notification_all'] = true;
        }


			// remarketing all in one 
			
			$this->load->model('tool/remarketing');
			if ($this->config->get('remarketing_status') && !$this->model_tool_remarketing->isBot()) {
				$data['remarketing_footer'] = $this->load->controller('common/remarketing/footer');
				$data['google_currency'] = $this->config->get('remarketing_google_currency');	
				$data['facebook_currency'] = $this->config->get('remarketing_facebook_currency');	
				$data['ecommerce_currency'] = $this->config->get('remarketing_ecommerce_currency');	
				$data['remarketing_currency'] = $this->session->data['currency'];	
				$data['remarketing_status'] = $this->config->get('remarketing_status');	
				$data['facebook_status'] = ($this->config->get('remarketing_facebook_status') && $this->config->get('remarketing_facebook_identifier'));	
				$data['facebook_depth'] = ($this->config->get('remarketing_facebook_depth') && $this->config->get('remarketing_facebook_depth_params'));	
				$data['facebook_depth_params'] = $this->config->get('remarketing_facebook_depth_params');	
				$data['google_status'] = ($this->config->get('remarketing_google_status') && $this->config->get('remarketing_google_identifier'));
				$data['google_identifier'] = $this->config->get('remarketing_google_identifier');
				$data['ecommerce_status'] = $this->config->get('remarketing_ecommerce_status');
				$data['ecommerce_ga4_status'] = $this->config->get('remarketing_ecommerce_ga4_status');
				$data['ecommerce_selector'] = $this->config->get('remarketing_ecommerce_selector');
				$data['ecommerce_ga4_selector'] = $this->config->get('remarketing_ecommerce_ga4_selector');
				$data['ecommerce_measurement_status'] = $this->config->get('remarketing_ecommerce_measurement_status');
				$data['ecommerce_measurement_selector'] = $this->config->get('remarketing_ecommerce_measurement_selector');
				$data['ecommerce_ga4_measurement_status'] = $this->config->get('remarketing_ecommerce_ga4_measurement_status');
				$data['ecommerce_ga4_measurement_selector'] = $this->config->get('remarketing_ecommerce_ga4_measurement_selector');
			}
			
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		

            $data['smartSupp'] = $this->load->controller('extension/module/smartsupp');
            
		return $this->load->view('common/footer', $data);
	}
}
