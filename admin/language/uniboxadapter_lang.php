<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#

if (!function_exists ('uniboxadapterSetTexts')) { // kvuli Language editor od VAN studia
	function uniboxadapterSetTexts(&$_, $uniModulName, $language) {
	
		require_once(DIR_APPLICATION."../UniModul/UniModul.php");
		$uniFact = new UniModulFactory();
		$uniModul = $uniFact->createUniModul($uniModulName,null);
	
		$_['heading_title']      = "Info Box - " .$uniModul->dictionary->get('payment_method_name', $language);
		$_['text_'.strtolower($uniModulName).'binder'] = '<a onclick="window.open(\'http://www.platiti.cz\');">www.platiti.cz</a>';  // to se pro normalni moduly nepouzije
	
	}
}

require_once(DIR_APPLICATION."../UniModul/UniModul.php");

if (empty($GLOBALS['UniAdapterOld_setTexts_DisableOldCompatibility'])) {
	function setTexts(&$_, $uniModulName, $language) {
		uniboxadapterSetTexts($_, $uniModulName, $language);
	}
}
