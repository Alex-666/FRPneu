<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#


require_once("uniadapter.php");

class ModelPaymentGPWebPayGpeBinderGooglePay extends ModelPaymentUniAdapter {

	public function __construct($registry) {
		parent::__construct($registry, 'GPWebPayGpe', 'GooglePay');
	}


}
?>