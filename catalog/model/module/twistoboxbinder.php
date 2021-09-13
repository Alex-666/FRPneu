<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#


require_once("uniboxadapter.php");

class ModelModuleTwistoBoxBinder extends ModelModuleUniBoxAdapter {

	public function __construct($registry) {
		parent::__construct($registry, 'Twisto');
	}


}
?>