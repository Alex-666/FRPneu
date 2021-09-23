<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#

require_once(DIR_APPLICATION . "controller/payment/uniadapter.php");

class ControllerExtensionPaymentGPWebPayGpeBinder extends ControllerPaymentUniAdapter {

	public function __construct($registry) {
		parent::__construct($registry, 'GPWebPayGpe', '');
	}


}
