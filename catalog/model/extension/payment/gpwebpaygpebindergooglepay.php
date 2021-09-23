<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#


require_once(dirname(__FILE__)."/../../payment/uniadapter.php");

class ModelExtensionPaymentGPWebPayGpeBinderGooglePay extends ModelPaymentUniAdapter {

	public function __construct($registry) {
		parent::__construct($registry, 'GPWebPayGpe', 'GooglePay');
	}


}
?>