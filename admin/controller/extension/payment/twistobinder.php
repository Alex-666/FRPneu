<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#



require_once(DIR_APPLICATION . "controller/payment/uniadapter.php");

class ControllerExtensionPaymentTwistoBinder extends ControllerPaymentUniAdapter {

	public function __construct($registry) {
		parent::__construct($registry, 'Twisto', '');
	}


}
