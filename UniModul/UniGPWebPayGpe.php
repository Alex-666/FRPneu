<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#

require_once(dirname(__FILE__)."/UniGPWebPay.php");

class UniGPWebPayGpe extends UniGPWebPay {

	public function __construct($configSetting, $subMethod) {
		parent::__construct($configSetting, $subMethod, "GPWebPayGpe");
	}

	function getConfigInfo($language='en') {
		$configInfo = parent::getConfigInfo($language);
		$configInfo->configFields = array_filter($configInfo->configFields, function ($v) {return !in_array($v->name, array('provider', 'cronSecret', 'convertToCurrencyIfUnsupported'));});
		return $configInfo;
	}
	function setConfigFromData($configSetting) {
		parent::setConfigFromData($configSetting);
		$this->config->convertToCurrencyIfUnsupported = '';
	}

	public function getInfoBoxData($uniAdapterName, $language) {
		$prev = $this->name;
		$this->name = "GPWebPay";
		$ret = parent::getInfoBoxData($uniAdapterName, $language);
		$this->name = $prev;
		return $ret;
	}

}
