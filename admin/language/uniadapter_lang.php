<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#

if (!function_exists ('uniadapterSetTexts')) { // kvuli Language editor od VAN studia
	function uniadapterSetTexts(&$_, $uniModulName, $language, $subMethod) {
		require_once(DIR_APPLICATION."../UniModul/UniModul.php");
		$uniFact = new UniModulFactory();
		$uniModul = $uniFact->createUniModul($uniModulName,null, $subMethod);
		$binderName = strtolower($uniModulName).'binder'.strtolower($subMethod);
		if ($subMethod == '') {
			$_['heading_title']      = $binderName. ': ' . $uniModul->dictionary->get('payment_method_name', $language);
		} else {
			$_['heading_title']      = $binderName. ': ' . $uniModul->dictionary->get('submethod_name_'.$subMethod, $language);
		}
	}
}

require_once(DIR_APPLICATION."../UniModul/UniModul.php");

if (empty($GLOBALS['UniAdapterOld_setTexts_DisableOldCompatibility'])) {
	function setTexts(&$_, $uniModulName, $language, $subMethod) {
		uniadapterSetTexts($_, $uniModulName, $language, $subMethod);
	}
}
