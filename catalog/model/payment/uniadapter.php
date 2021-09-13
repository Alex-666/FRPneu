<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#


require_once(DIR_APPLICATION."../UniModul/UniModul.php");

class ModelPaymentUniAdapter extends Model {

	protected $uniModul;
	protected $lowCaseBinderName;
	
	public function __construct($registry, $uniModulName = null, $subMethod='') {
		parent::__construct($registry);
		if ($uniModulName===null) return; //bare adapter, preskocit
		BeginUniErr();
		$this->lowCaseBinderName = strtolower($uniModulName).'binder'.strtolower($subMethod);
		$uniFact = new UniModulFactory();
		$this->uniModul = $uniFact->createUniModul($uniModulName,null, $subMethod); // config je null, ale snad to pro ��ely tohoto p�iblbl�ho modelu nen� pot�eba, pokud by to bylo pot�eba tak asi p�ekop�rovat z controlleru
		EndUniErr();
	}

  	public function getMethod($address) {
		if ($this->uniModul===null) return; // jen protoze adapter je taky modul, TODO; delistovat ho
		
		BeginUniErr();
		if ($this->config->get((VERSION >=3 ? 'payment_' : '') . $this->lowCaseBinderName.'_status')) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get((VERSION >=3 ? 'payment_' : '') . $this->lowCaseBinderName.'_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get((VERSION >=3 ? 'payment_' : '') . $this->lowCaseBinderName.'_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}	
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
			$ctrlAdapter = $this->getControllerPaymentUniAdapter($this->registry, $this->uniModul->name, $this->uniModul->subMethod);
			$prePayGWInfo = $ctrlAdapter->getPrePayGWInfoForModel();

			if (isset($GLOBALS['OpenCart_PaymentLogos']) && $GLOBALS['OpenCart_PaymentLogos']!=0) {
				if (file_exists(DIR_APPLICATION."../UniModul/Uni".$this->uniModul->name.$this->uniModul->subMethod."Logo.png")) {
					$submetLogoPiece = $this->uniModul->subMethod;
				} else {
					$submetLogoPiece = "";
				}
				$logoimg = HTTPS_SERVER . "UniModul/Uni".urlencode($this->uniModul->name . $submetLogoPiece)."Logo.png";
				$logoimgel = "<img style='width:7ex;vertical-align:middle' src='".$logoimg."'/>" . " &nbsp; &nbsp; ";
			} else {
				$logoimgel = '';
			}
			
			if ($prePayGWInfo->isPossible) {
				$method_data = array( 
					'id'         => $this->lowCaseBinderName,    //pro ver 1.4
					'code'       => $this->lowCaseBinderName,  //pro ver 1.5
					'title'      => $logoimgel . $prePayGWInfo->paymentMethodName,
					'terms'      => '',
					'sort_order' => $this->config->get('payment_'.$this->lowCaseBinderName.'_sort_order')
				);
			}
    	}
   
    	return EndUniErr($method_data);
  	}
	
	protected function getControllerPaymentUniAdapter($registry, $uniModulName, $subMethod) {
		require_once(DIR_APPLICATION."controller/payment/uniadapter.php");
		return new ControllerPaymentUniAdapter($registry, $uniModulName, $subMethod);
	}
}
?>