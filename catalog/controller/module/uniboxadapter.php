<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#


require_once(DIR_APPLICATION."../UniModul/UniModul.php");

class ControllerModuleUniBoxAdapter extends Controller {

	protected $uniModul;
	protected $lowCaseBinderName;

	public function __construct($registry, $uniModulName = null) {
		parent::__construct($registry);
		$this->lowCaseBinderName = strtolower($uniModulName).'boxbinder';
		$uniFact = new UniModulFactory();
		//$configInfo = $uniFact->getConfigInfo($uniModulName);
		//$configSetting = $this->getConfigData($configInfo);
		$this->uniModul = $uniFact->createUniModul($uniModulName, null /*$configSetting*/);
	}

	/*
	function getConfigData($configInfo) {
		$configData = array();
		foreach ($configInfo->configFields as $configField) {
			$configData[$configField->name] = $this->config->get($this->lowCaseBinderName.'_'.$configField->name);
		}

		$uniModulConfig = new UniBoxModulConfig();
		$uniModulConfig->mysql_server = DB_HOSTNAME;
		$uniModulConfig->mysql_dbname = DB_DATABASE;
		$uniModulConfig->mysql_login = DB_USERNAME;
		$uniModulConfig->mysql_password = DB_PASSWORD;
		//DB_PREFIX ..
		if (DB_DRIVER != 'mysql') user_error('Eshop pouziva jinou nez mysql databazi');
		$cfgs = create_initialize_object('ConfigSetting', array('configData'=>$configData, 'uniModulConfig'=>$uniModulConfig));
		return $cfgs;
	}
	*/


	protected function index() {
		//???  $this->language->load('module/uniboxadapter');
		
		$infoBoxData = $this->uniModul->getInfoBoxData('OpenCart', $this->session->data['language']);
		$image = "UniModul/".$infoBoxData->image;

		$this->data['ibtitle'] = $infoBoxData->title;
		$this->data['ibimage'] = $image;
		$this->data['iblink'] = $infoBoxData->link;
		$this->data['platitiLink'] = $infoBoxData->platitiLink;
		$this->data['platitiLinkText'] = $infoBoxData->platitiLinkText;

		$this->id = $this->lowCaseBinderName;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/uniboxadapter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/uniboxadapter.tpl';
		} else {
			$this->template = 'default/template/module/uniboxadapter.tpl';
		}	
		$this->render();	
		
	}

}
?>