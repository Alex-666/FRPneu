<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#


require_once(DIR_APPLICATION."../UniModul/UniModul.php");
require_once(DIR_APPLICATION."../UniModul/UniOCHelper.php");

define('_TOKEN', VERSION < 3 ? 'token' : 'user_token');

class ControllerPaymentUniAdapter extends Controller {
	private $error = array();

	protected $uniModul;
	protected $lowCaseBinderName;
	protected $configInfo;

	public function __construct($registry, $uniModulName = null, $subMethod='') {
		BeginUniErr();
		global $config;
		if (is_null($uniModulName)) {
			echo("This module cannot be used directly.");
			ResetUniErr();
			die();
		}
		parent::__construct($registry);
		$uniFact = new UniModulFactory();
		$this->configInfo = $uniFact->getConfigInfo($uniModulName, UniOCHelper::getLanguageWoCulture($this), $subMethod);
		if (!empty($subMethod)) {
			$this->configInfo->configFields = array();
		}
		$this->uniModul = $uniFact->createUniModul($uniModulName,null, $subMethod); //config tady není nutný...snad
		$this->lowCaseBinderName = strtolower($uniModulName).'binder'.strtolower($subMethod);
		EndUniErr();
	}



	public function index() {
		BeginUniErr();
		$this->load->language((VERSION < 3 ? 'payment/' : 'extension/payment/').'bank_transfer'); // pro standardni preklady typu poradi, geo zona, atp.
		$this->load->language((VERSION < 3 ? 'payment/' : 'extension/payment/').$this->lowCaseBinderName);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$oc3pref = VERSION >=3 ? 'payment_' : '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			// nechce se mi predelavat sablonu
			foreach ($this->request->post as $pkey=>$pval) {
				unset($this->request->post[$pkey]);
				$npkey = $oc3pref . str_replace('uniadapter_', $this->lowCaseBinderName.'_', $pkey);
				$this->request->post[$npkey] = $pval;
			}

			$this->model_setting_setting->editSetting($oc3pref . $this->lowCaseBinderName, $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			ResetUniErr();
			$url = UniOCHelper::link($this, VERSION < 3 ? 'extension/payment' : 'marketplace/extension',_TOKEN . '=' . $this->session->data[_TOKEN] . '&type=payment');
			if (VERSION < '2.0') {
				$this->redirect($url);
			} else {
				$this->response->redirect($url);
			}
		}
		// texty obecnych popisku
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
		$data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');

		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		// error hlasky
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		//bread crumbs
		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
			'href'      => UniOCHelper::link($this, 'common/home', _TOKEN . '=' . $this->session->data[_TOKEN]),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
			'href'      => UniOCHelper::link($this, 'extension/payment', _TOKEN . '=' . $this->session->data[_TOKEN]),
			'text'      => $this->language->get('text_payment'),
			'separator' => ' :: '
		);

		$this->document->breadcrumbs[] = array(
			'href'      => UniOCHelper::link($this, 'payment/'.$this->lowCaseBinderName, _TOKEN . '=' . $this->session->data[_TOKEN]),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		// verze
		$refl = new ReflectionObject($this->document);
		$data['breadcrumbs'] = $this->document->breadcrumbs;

		// url akcni
		$data['action'] = UniOCHelper::link($this, 'payment/'.$this->lowCaseBinderName, _TOKEN . '=' . $this->session->data[_TOKEN]);

		$data['cancel'] = UniOCHelper::link($this, 'extension/payment', _TOKEN . '=' . $this->session->data[_TOKEN]);


		// moje konfigurace
		$data['configInfo'] = $this->configInfo;  // zobrazene provedu kompletne v templatu
		// jen donactu data
		foreach ($this->configInfo->configFields as $configField) {
			if (isset($this->request->post['uniadapter_'.$configField->name])) {
				$data['uniadapter_'.$configField->name] = $this->request->post['uniadapter_'.$configField->name];
				$data['uniadapter_'][$configField->name] = $this->request->post['uniadapter_'.$configField->name];
			} else {
				$data['uniadapter_'.$configField->name] = $this->config->get($oc3pref . $this->lowCaseBinderName.'_'.$configField->name);
				$data['uniadapter_'][$configField->name] = $this->config->get($oc3pref . $this->lowCaseBinderName.'_'.$configField->name);
			}
		}



		// texty stavu objednavky
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		// geo zona, poradi a enable

		if (isset($this->request->post['uniadapter_geo_zone_id'])) {
			$data['uniadapter_geo_zone_id'] = $this->request->post['uniadapter_geo_zone_id'];
		} else {
			$data['uniadapter_geo_zone_id'] = $this->config->get($oc3pref . $this->lowCaseBinderName.'_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['uniadapter_status'])) {
			$data['uniadapter_status'] = $this->request->post['uniadapter_status'];
		} else {
			$data['uniadapter_status'] = $this->config->get($oc3pref . $this->lowCaseBinderName.'_status');
		}

		if (isset($this->request->post['uniadapter_sort_order'])) {
			$data['uniadapter_sort_order'] = $this->request->post['uniadapter_sort_order'];
		} else {
			$data['uniadapter_sort_order'] = $this->config->get($oc3pref . $this->lowCaseBinderName.'_sort_order');
		}


		// vyvolani templatu
		if (VERSION < '2.0') {
			$this->data = $data;
			$this->template = 'payment/uniadapter.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		} else {
			BeginUniErr(E_UNIERR_DEFAULT & ~E_USER_DEPRECATED);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$class = new ReflectionClass('ConfigFieldType');
			$data['ConfigFieldType'] = $class->getStaticProperties();
			$this->response->setOutput($this->load->view('payment/uniadapter2.tpl', $data));
			EndUniErr();
		}
		EndUniErr();
	}


	private function validate() {
		$ext = (VERSION >= '2.3') ? "extension/" : "";
		if (!$this->user->hasPermission('modify', $ext . 'payment/'.$this->lowCaseBinderName.'')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function install() {
		$sql = file_get_contents (DIR_APPLICATION."/../UniModul/UniModul.sql");
		$this->db->query($sql);

		if (VERSION >= '2.0' && VERSION < '3') {
			$this->load->model('extension/event');
			$this->model_extension_event->deleteEvent($this->lowCaseBinderName);
			$this->model_extension_event->addEvent($this->lowCaseBinderName, 'catalog/uniadapter/installmentembedhtml', 'payment/'.$this->lowCaseBinderName.'/installmentembedhtml');

			$this->load->controller("extension/modification/refresh");
		} else if (VERSION >= 3) {
			$this->load->model('setting/event');
			$this->model_setting_event->deleteEventByCode($this->lowCaseBinderName);
			$this->model_setting_event->addEvent($this->lowCaseBinderName, 'catalog/uniadapter/installmentembedhtml', 'payment/'.$this->lowCaseBinderName.'/installmentembedhtml');

			$this->load->controller("extension/modification/refresh");
		}
	}

	public function uninstall() {
		if (VERSION >= '2.0') {
			$this->load->model('extension/event');
			$this->model_extension_event->deleteEvent($this->lowCaseBinderName);
		} else if (VERSION >= 3) {
			$this->load->model('setting/event');
			$this->model_setting_event->deleteEventByCode($this->lowCaseBinderName);
		}

	}
}
