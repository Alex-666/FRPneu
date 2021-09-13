<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-14-g21587217:2020-02-29#

// trida pro praci s Webovou kalkulackou Cetelem pro online objednani uveru
class Cetelem {

	/*private*/var $kodProdejce;
	/*private*/var $cetelem_url;

	// konstruktor objektu Cetelem
	// argument $kodProdejce obsahuje realny kod prodejce nebo testovaciho prodejce s cislem 2044576
	// argument $cetelemUrl obsahuje url brany Cetelem, produkce: https://www.cetelem.cz    test: https://www.cetelem.cz:8654
	function __construct ($kodProdejce, $cetelemUrl) {
		$this->kodProdejce = $kodProdejce;
		$this->cetelem_url = $cetelemUrl;
	}

	// funkce submitOnlineUver presmeruje uzivatele na strany Cetelem pro sjednani uveru
	// funkce vytvori automaticky submitovany formular se skrytymi poli
	function submitOnlineUver ($parametry, $calc, $url_back_ok, $url_back_ko, $obj=null, $numklient=null) {
		$form = $this->submitOnlineUverExt2($parametry, $calc, $url_back_ok, $url_back_ko, $obj, $numklient);
		echo "<html><body>".$form."</body></html>";
	}

	function submitOnlineUverExt2 ($parametry, $calc, $url_back_ok, $url_back_ko, $obj=null, $numklient=null) {
		$form = '<form id="form" method="post" action="'.$this->getSubmitUrl().'">';
		$form .= $this->submitOnlineUverExt($parametry, $calc, $url_back_ok, $url_back_ko, $obj, $numklient);
		$form .= "<noscript>Kliknete prosim na tlacitko pro pokracovani / Please click the button to continue<br/><input type='submit' value='Pokracovat / Continue'></noscript>";
		//$form .= '<input type="submit" value="Odeslat">';
		$form .= "</form>";
		$form .= "<script>document.getElementById(\"form\").submit();</script>";
		return $form;
	}

	
	function submitOnlineUverExt ($parametry, $calc, $url_back_ok, $url_back_ko, $obj=null, $numklient=null) {
		$form = '';
		$form .= "<input type='hidden' name='kodProdejce' value='".$this->kodProdejce."'>\n";
		foreach ($parametry as $param=>$value) {
			$form .= "<input type='hidden' name='".$param."' value='".$value."'>\n";
		}
		$form .= "<input type='hidden' name='calc' value='".$calc."'>\n";
		$form .= "<input type='hidden' name='url_back_ok' value='".$url_back_ok."'>\n";
		$form .= "<input type='hidden' name='url_back_ko' value='".$url_back_ko."'>\n";
		$form .= "<input type='hidden' name='obj' value='".$obj."'>\n";
		$form .= "<input type='hidden' name='numklient' value='".$numklient."'>\n";
		return $form;
	}

	function getSubmitUrl() {
		return $this->cetelem_url.'/cetelem2_webshop.php/zadost-o-pujcku/on-line-zadost-o-pujcku';
	}


	// funkce callWebCalc prepocita parametry platby pomoci webove kalkulacky Cetelem
	// Vstupem je asociativni pole $parametry, do ktereho je treba vyplnit vsechny vstupni parametry podle dokumentace k Cetelem WebKalkucce, krome parametru kodProdejce
	// Hlavnim vystupem je aktualizovane pole $parametry. Dale je vracena hodnota status obsahujici hodnoty "ok" nebo "error", a textovy popis vypoctu vraceny argumentem $info. 
	function callWebCalc(&$parametry, &$info) {
		$allowedParams = array("kodBaremu", "kodPojisteni", "kodMaterialu", "cenaZbozi", "primaPlatba", "vyseUveru", "pocetSplatek", "odklad", "vyseSplatky", "cenaUveru", "ursaz", "celkovaCastka", "RPSN");
		$get="";
		foreach($parametry as $parnazev=>$parhodnota) {
			if (!in_array($parnazev, $allowedParams)) {
				user_error("Neplatny vstupni parametr: ".$parnazev);
			}
			$get.='&'.$parnazev."=".urlencode($parhodnota);
		}
		$url = $this->cetelem_url."/webkalkulacka.php?kodProdejce=".$this->kodProdejce.$get;

		$xmltext = $this->file_get_contents_curl($url);
		$doc = new DomDocument();
		$doc->loadXML($xmltext);
		$xpath = new DOMXPath($doc);

		$status = $xpath->evaluate('string(//status)');
		$info = $xpath->evaluate('string(//info)');

		$vysledky = $xpath->evaluate('//vysledek/*');
		foreach($vysledky as $vysledek) {
			$parametr = $vysledek->nodeName;
			$hodnota = $vysledek->textContent;
			$parametry[$parametr]=$hodnota;
		}

		return $status;
	}



	// funkce loadBaremInfo vraci popis jednotlivych baremu
	// vystup je asociativni pole [hodnota=>[0=>nazev, 1=>možný rozsah úvìru, 2=>pøímá platba, 3=>poèet splátek, 4=>možnost odkladu splátek]]
	// z vnitrniho pole je povinny jen 0=>nazev
	function loadBaremInfo() {
		$url = $this->cetelem_url."/bareminfo.php?kodProdejce=".$this->kodProdejce;
		$xmltext = $this->file_get_contents_curl($url);
		$doc = new DomDocument();
		$doc->loadXML($xmltext);
		$xpath = new DOMXPath($doc);
		$baremy = $xpath->evaluate('//barem');
		$baremInfo=array();
		foreach($baremy as $barem) {
			//$hodnota = $barem->attributes->getNamedItem('id')->value;
			$tmp = $barem->attributes->getNamedItem('id'); $hodnota = $tmp->value;
			
			$titul = $xpath->evaluate('string(titul)', $barem);
			$bardetail = array($titul);
			$infos = $xpath->evaluate('info', $barem);
			foreach($infos as $info) {
				$bardetail[]=$info->textContent;
			}
			$baremInfo[$hodnota]=$bardetail;
		}
		return $baremInfo;
	}


	// funkce loadWebCiselnik nacte ciselnik z cetelem a vrati je ve tvaru asociativniho pole [hodnota=>popis]
	// parametr typ muze obsahovat tyto hodnoty: "barem", "pojisteni"
	function loadWebCiselnik($typ) {
		if(!in_array($typ, array("barem", "pojisteni", "material"))) {
			user_error('Neplatny typ ciselniku: '.$typ);
		}
		$xmltext = $this->file_get_contents_curl($this->cetelem_url."/webciselnik.php?kodProdejce=".$this->kodProdejce."&typ=".$typ);
		$doc = new DomDocument();
		$doc->loadXML($xmltext);
		$xpath = new DOMXPath($doc);
		$moznosti = $xpath->evaluate('//moznost');
		$ciselnik=array();
		foreach($moznosti as $moznost) {
			//$hodnota = $moznost->attributes->getNamedItem('hodnota')->value;
			$tmp = $moznost->attributes->getNamedItem('hodnota'); $hodnota = $tmp->value;

			$text = $moznost->textContent;
			$ciselnik[$hodnota] = $text;
		}
		return $ciselnik;
	}


	/*private*/ function file_get_contents_curl($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);

		// pokud je potreba proxy
		if (isset($GLOBALS['curlopt_proxy']) && $GLOBALS['curlopt_proxy']) {
			curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['curlopt_proxy']);
		}

		// total vypnuti kontroly https certifikatu, NEBEZPECNE!
		if (isset($GLOBALS['curlopt_insecure']) && $GLOBALS['curlopt_insecure']) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,5);

		$data = curl_exec($ch);

		$err = curl_error($ch);
		if ($err != '') {
			user_error($err);
		}

		curl_close($ch);

		return $data;
	}
}

