<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#


require_once(DIR_APPLICATION."../UniBoxModul/UniBoxModul.php");

class ModelModuleUniBoxAdapter extends Model {

	protected $uniModul;
	protected $lowCaseBinderName;

	public function __construct($registry, $uniModulName = null) {
		parent::__construct($registry);
		if ($uniModulName===null) return; //bare adapter, preskocit
		$this->lowCaseBinderName = strtolower($uniModulName).'boxbinder';
		$uniFact = new UniBoxModulFactory();
		$this->uniModul = $uniFact->createUniBoxModul($uniModulName,null); // config je null, ale snad to pro úèely tohoto pøiblblého modelu není potøeba, pokud by to bylo potøeba tak asi pøekopírovat z controlleru
	}

  	public function getMethod($address) {
		if ($this->uniModul===null) return; // jen protoze adapter je taky modul, TODO; delistovat ho
		//$this->load->language('module/uniboxadapter');
		
		if ($this->config->get($this->lowCaseBinderName.'_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get($this->lowCaseBinderName.'_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get($this->lowCaseBinderName.'_geo_zone_id')) {
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
			require_once(DIR_APPLICATION."controller/module/uniboxadapter.php");
			$ctrlAdapter = new ControllerModuleUniBoxAdapter($this->registry, $this->uniModul->name);
			$prePayGWInfo = $ctrlAdapter->getPrePayGWInfoForModel();

			if ($prePayGWInfo->isPossible) {
				$method_data = array( 
					'id'         => $this->lowCaseBinderName,    //pro ver 1.4
					'code'       => $this->lowCaseBinderName,  //pro ver 1.5
					'title'      => $prePayGWInfo->moduleMethodName,
					'sort_order' => $this->config->get($this->lowCaseBinderName.'_sort_order')
				);
			}
    	}
   
    	return $method_data;
  	}
}
