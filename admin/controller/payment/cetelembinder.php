<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-14-g21587217:2020-02-29#



require_once("uniadapter.php");

class ControllerPaymentCetelemBinder extends ControllerPaymentUniAdapter {

	public function __construct($registry) {
		parent::__construct($registry, 'Cetelem', '');
	}


}
