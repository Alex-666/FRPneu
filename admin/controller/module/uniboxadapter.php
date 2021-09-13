<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#


//require_once(DIR_APPLICATION."../UniBoxModul/UniBoxModul.php");
require_once(DIR_APPLICATION."../UniModul/UniOCHelper.php");

class ControllerModuleUniBoxAdapter extends Controller {
	private $error = array(); 
	
	protected $uniModul;
	protected $lowCaseBinderName;
	protected $configInfo;
	
	public function __construct($registry, $uniModulName = null) {
		global $config;
		if (is_null($uniModulName)) {
			echo("This module cannot be used directly.");
			die();
		}
		parent::__construct($registry);
		//$uniFact = new UniBoxModulFactory();
		//$this->configInfo = $uniFact->getConfigInfo($uniModulName, $config->get('config_admin_language'));
		//$this->uniModul = $uniFact->createUniBoxModul($uniModulName,null); //config tady není nutný...snad
		$this->lowCaseBinderName = strtolower($uniModulName).'boxbinder';
	}


	
	public function index() {

		$this->load->language('module/'.$this->lowCaseBinderName);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			// nechce se mi predelavat sablonu
			foreach ($this->request->post as $pkey=>$pval) {
				unset($this->request->post[$pkey]);
				$npkey = str_replace('uniboxadapter_', $this->lowCaseBinderName.'_', $pkey);
				$this->request->post[$npkey] = $pval;
			}

			$this->model_setting_setting->editSetting($this->lowCaseBinderName, $this->request->post);				
			$this->session->data['success'] = $this->language->get('text_success');
			UniOCHelper::redirectLink($this, 'extension/module', 'token=' . $this->session->data['token']);
		}

		// texty obecnych popisku
		
		// pro 1.4
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_left'] = $this->language->get('text_left');
		$data['text_right'] = $this->language->get('text_right');
		$data['text_home'] = $this->language->get('text_home');

		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_yes'] = $this->language->get( 'entry_yes' );
		$data['entry_no']	= $this->language->get( 'entry_no' );

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['entry_header'] = $this->language->get( 'entry_header' );
		$data['entry_title'] = $this->language->get( 'entry_title' );

		$data['token'] = $this->session->data['token'];


		$data['tab_general'] = $this->language->get('tab_general');

		
		
		/// jen pro 1.5   (mozne duplikace, ale necham)
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		
		
		// error hlasky
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		


		
		//bread crumbs
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => UniOCHelper::link($this, 'common/home', 'token=' . $this->session->data['token']),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => UniOCHelper::link($this, 'extension/module'. 'token=' . $this->session->data['token']),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => UniOCHelper::link($this, 'module/'.$this->lowCaseBinderName, 'token=' . $this->session->data['token']),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		// verze
		$refl = new ReflectionObject($this->document);
		$ver15 = !$refl->getProperty('title')->isPublic();
		$data['ver15']=$ver15;
		$data['breadcrumbs'] = $this->document->breadcrumbs;

		// url akcni
		$data['action'] = UniOCHelper::link($this, 'module/'.$this->lowCaseBinderName, 'token=' . $this->session->data['token']);
		
		$data['cancel'] = UniOCHelper::link($this, 'extension/module', 'token=' . $this->session->data['token']);
		

		// data
		
		if (!$ver15) {
			if (isset($this->request->post['uniboxadapter_position'])) {
				$data['uniboxadapter_position'] = $this->request->post['uniboxadapter_position'];
			} else {
				$data['uniboxadapter_position'] = $this->config->get($this->lowCaseBinderName.'_position'); 
			} 
			
			if (isset($this->request->post['uniboxadapter_status'])) {
				$data['uniboxadapter_status'] = $this->request->post['uniboxadapter_status'];
			} else {
				$data['uniboxadapter_status'] = $this->config->get($this->lowCaseBinderName.'_status');
			}
			
			if (isset($this->request->post['uniboxadapter_sort_order'])) {
				$data['uniboxadapter_sort_order'] = $this->request->post['uniboxadapter_sort_order'];
			} else {
				$data['uniboxadapter_sort_order'] = $this->config->get($this->lowCaseBinderName.'_sort_order');
			}
		} else {
		
		
		/// jen pro 1.5 TTTTTTT


			$data['uniboxadapter_module'] = array();
			if (isset($this->request->post['uniboxadapter_module'])) {
				$data['uniboxadapter_module'] = $this->request->post['uniboxadapter_module'];
			} else if ($this->config->get($this->lowCaseBinderName.'_module')!==null) {
				$data['uniboxadapter_module'] = $this->config->get($this->lowCaseBinderName.'_module');
			}

					
			$this->load->model('design/layout');
			$data['layouts'] = $this->model_design_layout->getLayouts();
		}
		
		// vyvolani templatu
		if (VERSION < '2.0') {
			$this->data = $data;
			$this->template = 'module/uniboxadapter.tpl';
			$this->children = array(
				'common/header',	
				'common/footer'	
			);
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		} else {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('module/uniboxadapter.tpl', $data));
		}

	}

	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/'.$this->lowCaseBinderName.'')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>