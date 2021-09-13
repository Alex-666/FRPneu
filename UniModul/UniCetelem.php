<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-14-g21587217:2020-02-29#


require_once(dirname(__FILE__)."/UniModul.php");
require_once(dirname(__FILE__)."/cetelem.php");

class UniCetelemConfig {
	public $kodProdejce;
	public $cetelemUrl;
	public $gwOrderNumberOffset;
	public $convertToCurrencyIfUnsupported; //jedna mena
}


class UniCetelem extends UniModul {
	
	var $minimumCetelemPriceCZK = 3000;
	
	public function __construct($configSetting, $subMethod) {
		parent::__construct("Cetelem", $configSetting, $subMethod);
		$this->setConfigFromData($configSetting);
	}

	function setConfigFromData($configSetting) {
		$this->config = new UniCetelemConfig();
		if ($configSetting != null && $configSetting->configData != null) {
			$configData = $configSetting->configData;
			$this->config->kodProdejce = $configData['kodProdejce'];
			$this->config->cetelemUrl = $configData['cetelemUrl'];
			$this->config->gwOrderNumberOffset = $configData['gwOrderNumberOffset'];
			$this->config->convertToCurrencyIfUnsupported = $configData['convertToCurrencyIfUnsupported'];
		}
	}

	function getConfigInfo($language='en') {
		
		$d = $this->dictionary;
		$d->setDefaultLanguage($language);
		
		$configInfo = new ConfigInfo();
		
		$configFields = array();
		
		$configField = new ConfigField();
		$configField->name = 'kodProdejce';
		$configField->type = ConfigFieldType::$text;
		$configField->label = $d->get('kodProdejce');
		$configFields[]=$configField;
		
		$configField = new ConfigField();
		$configField->name = 'cetelemUrl';
		$configField->label = $d->get('cetelemUrl');
		$configField->type = ConfigFieldType::$text;
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'gwOrderNumberOffset';
		$configField->label = $d->get('gwOrderNumberOffset');
		$configField->type = ConfigFieldType::$text;
		$configFields[]=$configField;

		
		$configField = new ConfigField();
		$configField->name = 'convertToCurrencyIfUnsupported';
		$configField->label = $d->get('convertToCurrencyIfUnsupported');
		$configField->type = ConfigFieldType::$text;
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'orderStatusSuccessfull';
		$configField->label = $d->get('orderStatusSuccessfull');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;
		
		$configField = new ConfigField();
		$configField->name = 'orderStatusFailed';
		$configField->label = $d->get('orderStatusFailed');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;
		
		$configField = new ConfigField();
		$configField->name = 'orderStatusPending';
		$configField->label = $d->get('orderStatusPending');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;

		$configInfo->configFields = $configFields;
		return $configInfo;
	}
	
	
	function queryPrePayGWInfo($orderToPayInfo) {
		$currencySupported = ($orderToPayInfo->currency == 'CZK');
		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = $this->fixCurrency($orderToPayInfo, $currencySupported);

		$prePayGWInfo = new PrePayGWInfo();
		$prePayGWInfo->paymentMethodName = $this->dictionary->get('payment_method_name', $orderToPayInfo->language);

		$prePayGWInfo->isPossible = $isPossible;
		$prePayGWInfo->forexMessage = $forexMessage;
		return $prePayGWInfo;
	}
	
	function gatewayOrderRedirectAction($orderToPayInfo) {
		$currencySupported = ($orderToPayInfo->currency == 'CZK');
		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = $this->fixCurrency($orderToPayInfo, $currencySupported);

		if (!$isPossible) {
			$transactionPK = $this->writeOrderToDb($orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, $forexNote, $orderReplyStatusFail->orderStatus, $orderToPayInfo->uniAdapterData);
			$this->logger->writeLog("CANNOT SEND ORDER ".print_r($orderToPayInfo, true)."   resultText:".$orderReplyStatusFail->resultText);
			
			$redirectActionFail = new RedirectAction();
			$redirectActionFail->orderReplyStatus = $orderReplyStatusFail;
			return $redirectActionFail;
		} 
		
		
		$transactionPK = $this->writeOrderToDb($orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, $forexNote, null, $orderToPayInfo->uniAdapterData);
		$gwOrderNumber = $transactionPK + $this->config->gwOrderNumberOffset;
		
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION['unimodul']['transactionPK'] = $transactionPK; 

		$replyUrl = $orderToPayInfo->replyUrl.(strstr($orderToPayInfo->replyUrl,'?')===FALSE?"?cetdummy=1":""); // cetelem za url dava vzdy &
		$cetelem = new Cetelem($this->config->kodProdejce, $this->config->cetelemUrl);
		$parametry = array();
		$parametry['cenaZbozi'] = round($newtotal);
		$parametry['doprava'] = 1; // povoli SMS podpis
		$redirectForm = $cetelem->submitOnlineUverExt2($parametry, 1, $replyUrl, $replyUrl, $gwOrderNumber, $transactionPK);
		$this->logger->writeLog("MAKING_ORDER_FORM ".$redirectForm."   ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['REQUEST_URI']);
		$redirectAction = new RedirectAction();
		$redirectAction->redirectForm = $redirectForm;

		$orderReplyStatus = new OrderReplyStatus();
		$orderReplyStatus->orderStatus = OrderStatus::$pending;
		$orderReplyStatus->resultText = null;
		$orderReplyStatus->gwOrderNumber = $gwOrderNumber;
		$orderReplyStatus->shopOrderNumber = $orderToPayInfo->shopOrderNumber;
		$orderReplyStatus->shopPairingInfo = $orderToPayInfo->shopPairingInfo;
		$orderReplyStatus->forexNote = $forexNote;
		$orderReplyStatus->uniAdapterData = $orderToPayInfo->uniAdapterData;
		$this->logger->writeLog("Immediate status orderStatus=".$orderReplyStatus->orderStatus);
		$redirectAction->orderReplyStatus = $orderReplyStatus;
		$this->updateOrderReplyStatusGwOrdNumInDb($orderReplyStatus, $transactionPK);

		return $redirectAction;
	}

	protected function fixCurrency($orderToPayInfo, $currencySupported=null) {
		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = parent::fixCurrency($orderToPayInfo, $currencySupported);
		if ($isPossible && ($newcur != 'CZK' || ($newtotal < $this->minimumCetelemPriceCZK && $newtotal!==null))) { //newtotal nekdy neni v dane fazi k dispozici, takze null je ok
			$isPossible = false;
			$resultText = $this->dictionary->get('amountTooLow', $orderToPayInfo->language);
	
			$orderReplyStatusFail = new OrderReplyStatus();
			$orderReplyStatusFail->orderStatus = OrderStatus::$failedRetriable;
			$orderReplyStatusFail->resultText = $resultText;
			$orderReplyStatusFail->shopOrderNumber = $orderToPayInfo->shopOrderNumber;
			//$orderReplyStatusFail->gwOrderNumber = $gwOrderNumber;
			$orderReplyStatusFail->shopPairingInfo = $orderToPayInfo->shopPairingInfo;
			$orderReplyStatusFail->uniAdapterData = $orderToPayInfo->uniAdapterData;
			
		}
		return array($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail);
	}

		
	public function gatewayReceiveReply($language='en') {

		$transactionPK = $_GET['numklient'];
		
		if (!isset($_SESSION)) {
			session_start();
		}
		if (!isset($_SESSION['unimodul']) || !isset($_SESSION['unimodul']['transactionPK']) || $transactionPK != $_SESSION['unimodul']['transactionPK'] || ($_GET['stav']!=1 && $_GET['stav']!=2)) {
			// kdyz se neoverilo pres session, tak to muze byt nejaky utok a proto vracime failedRetriable
			$orderStatus = OrderStatus::$failedRetriable;
			$errMsg = $this->dictionary->get("Cetelem_E_PENDING",$language);
			$transactionRecord = null;
		} else {
			$transactionRecord = $this->getOrderTransactionRecordFromDbUnique(null, $transactionPK);
			$result = $_GET['stav'];
			if ($result==1) {
				$orderStatus = OrderStatus::$successful;
				$gwPairingNote = ' AUT:'.$_GET['numaut'];
				$errMsg = $this->dictionary->get("Cetelem_E_SUCCESS",$language);
			} else {
				$orderStatus = OrderStatus::$failedFinal;
				$gwPairingNote = ' WRK:'.$_GET['numwrk'];
				$errMsg = $this->dictionary->get("Cetelem_E_PENDING",$language);
			}
		}
		
		$orderReplyStatus = new OrderReplyStatus();
		$orderReplyStatus->orderStatus = $orderStatus;
		$orderReplyStatus->resultText = $errMsg;
		if ($transactionRecord != null) {
			$orderReplyStatus->gwOrderNumber = $transactionRecord->gwOrderNumber . $gwPairingNote;
			$orderReplyStatus->shopOrderNumber = $transactionRecord->shopOrderNumber;
			$orderReplyStatus->shopPairingInfo = $transactionRecord->shopPairingInfo;
			$orderReplyStatus->forexNote = $transactionRecord->forexNote;
			$orderReplyStatus->uniAdapterData = $transactionRecord->uniAdapterData;
			$this->updateOrderReplyStatusGwOrdNumInDb($orderReplyStatus, $transactionPK);
		}
		$this->logger->writeLog("REPLY orderStatus=".$orderReplyStatus->orderStatus." ". $_SERVER['REMOTE_ADDR']." ".$_SERVER['REQUEST_URI']);
		return $orderReplyStatus;
	}

	public function getInfoBoxData($uniAdapterName, $language) {
		$infoBoxData = parent::getInfoBoxData($uniAdapterName, $language);
		$infoBoxData->link = 'http://www.hellobank.cz/';
		$infoBoxData->image = "Uni".$this->name."Logo.png"; // proverit zda je to opravdu to co se ma ukazovat dle Cetelem
		return $infoBoxData;
	}

	public function ProductGetInstallmentEmbedHtml($shopBaseUrl, $currency, $price, $language='cs') {
		if ($currency == 'CZK' && $price >= $this->minimumCetelemPriceCZK) {
			if ($this->baseConfig->funcGetCallbackUrl != null) {
				$calcUrl = call_user_func($this->baseConfig->funcGetCallbackUrl, 'installmentWindow', array('currency'=>$currency, 'price'=>$price));
			} else {
				return null;
			}

			$aTag = <<< EOT
<a href="{$calcUrl}" target="_blank" onclick="window.open('{$calcUrl}', '_blank', 'toolbar=0, resizable=1, status=1, width=600px, height=700px'); return false">
EOT;

			$imgSrc = $this->getModulSubMethodLogoImage($shopBaseUrl);
			$html = $aTag."<img src='{$imgSrc}'/><br/>Spočítat výši splátek"."</a><br/>";
			$html = '<div style="text-align:center;">'.$html.'</div>';
			//return $html;
			return null;
		} else {
			return null;
		}
	}
	
	public function processCallbackRequest($callbackName, $arguments) {
		if ($callbackName == 'installmentWindow') {
			$this->installmentWindowCallback($arguments['currency'], $arguments['price']);
		}
	}
	
	protected function installmentWindowCallback($currency, $price) {
		$cetelem = new Cetelem($this->config->kodProdejce, $this->config->cetelemUrl);

		if (strpos($this->config->cetelemUrl, 'mockCetelem') === false) {
			if (isset($_SESSION['cetelem_ciselniky'])) {
				list($baremCis, $pojisteniCis) = $_SESSION['cetelem_ciselniky'];
			} else {
				$baremCis=$cetelem->loadWebCiselnik('barem');
				$pojisteniCis=$cetelem->loadWebCiselnik('pojisteni');
				//$materialCis=$cetelem->loadWebCiselnik('material');
				$_SESSION['cetelem_ciselniky'] = array($baremCis, $pojisteniCis);
			}
		} else {
			$baremCis = array();
			$pojisteniCis = array();
			//$materialCis = array();
		}

		$parametry=array();
		$inputParams = array("kodBaremu", "kodPojisteni", "kodMaterialu", "cenaZbozi", "primaPlatba", "vyseUveru", "pocetSplatek", "odklad", "vyseSplatky", "cenaUveru", "ursaz", "celkovaCastka", "RPSN");
		foreach($inputParams as $param) {
			if (isset($_REQUEST[$param]) && !isset($_REQUEST['clear'])) {
				$parametry[$param]=$_REQUEST[$param];
			} else {
				$parametry[$param]='';
			}
		}
		if ($parametry["cenaZbozi"] == '') {
			$parametry["cenaZbozi"] = number_format($price, 0, ',', '');
		}

		
		?>
		<html>
		<head>
		  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
		  <meta name="viewport" content="width=device-width, initial-scale=1.0">
		  <style>
			body {font-family: Arial,Helvetica,sans-serif;}
			input[type="text"],select {
				background-color: white;
				border: 1px solid;
				padding: 5px 10px;
				border-color: rgb(169, 184, 192);
				width: 100%;
				padding-right: 35px;
			}
			.zadani {
				margin-top:15px;
				margin-bottom: 15px;
			}
			.zadani td span {
				position: absolute;
				right: 10px;
				color: grey;
				font-size: 12px;
			}
			.zadani tr td:last-child {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				position:relative;
			}
			
			.zadani input[type="submit"] {
				border-radius: 2px;
				padding: 0 15px;
				line-height: 33px;
				background-color: rgb(31, 187, 166);
				border: 0;
				color: white;
				float: right;
				font-weight: 600;
				font-size: 14px;
				margin-top: 15px;
			}
			
			table {width: 100%;}
			.vysledek tr {border-bottom: 1px dotted black;}
			.vysledek td:nth-child(2) {text-align: center; width: 10ex; background-color: white;}
			div {background-color: #efefef; padding: 2ex; border-radius: 1ex;}
			td[colspan="2"] {text-align: center; padding-top:2ex;}

		  </style>
		</head>
		<body>
		<img src="<?php echo $this->getModulSubMethodLogoImage2(); ?>">		
		<div class="zadani">
		
		<div class="body_text">
			<p style="margin-left:0cm; margin-right:0cm"><span style="font-size:11pt"><strong><span style="font-size:12.0pt"><span style="color:#4bacc6">N&aacute;kup na spl&aacute;tky je snadn&yacute;, zabere V&aacute;m jen p&aacute;r minut:</span></span></strong></span></p>

			<p style="margin-left:0cm; margin-right:0cm">&nbsp;</p>

			<ol>
				<li style="text-align:justify"><span style="font-size:11pt"><span style="font-size:10.0pt">Vložte zbož&iacute; do n&aacute;kupn&iacute;ho ko&scaron;&iacute;ku. </span></span></li>
				<li style="text-align:justify"><span style="font-size:11pt"><span style="font-size:10.0pt">Vyplňte v&scaron;echny požadovan&eacute; &uacute;daje pro objedn&aacute;vku. Způsob platby zvolte &bdquo;Na spl&aacute;tky_Hello n&aacute;kupy&quot;.</span></span></li>
				<li style="text-align:justify"><span style="font-size:11pt"><span style="font-size:10.0pt">Po odesl&aacute;n&iacute; objedn&aacute;vky se dostanete na str&aacute;nku pro v&yacute;počet spl&aacute;tek. Vyberte si variantu spl&aacute;cen&iacute; podle Va&scaron;ich možnost&iacute;. V</span>&nbsp;<span style="font-size:10.0pt">p</span><span style="font-size:10.0pt">ř&iacute;</span><span style="font-size:10.0pt">pad</span><span style="font-size:10.0pt">ě</span><span style="font-size:10.0pt">, </span><span style="font-size:10.0pt">ž</span><span style="font-size:10.0pt">e V</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">m navr</span><span style="font-size:10.0pt">ž</span><span style="font-size:10.0pt">en</span><span style="font-size:10.0pt">&eacute;</span><span style="font-size:10.0pt"> spl</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">tky vyhovuj</span><span style="font-size:10.0pt">&iacute;</span><span style="font-size:10.0pt">, stiskn</span><span style="font-size:10.0pt">ě</span><span style="font-size:10.0pt">te </span><span style="font-size:10.0pt">&bdquo;</span><span style="font-size:10.0pt">Odeslat k vy</span><span style="font-size:10.0pt">ř&iacute;</span><span style="font-size:10.0pt">zen</span><span style="font-size:10.0pt">&iacute;&ldquo;</span><span style="font-size:10.0pt">.</span></span></li>
				<li style="text-align:justify"><span style="font-size:11pt"><span style="font-size:10.0pt">V</span>&nbsp;<span style="font-size:10.0pt">dal</span><span style="font-size:10.0pt">&scaron;&iacute;</span><span style="font-size:10.0pt">m kroku vypl</span><span style="font-size:10.0pt">ň</span><span style="font-size:10.0pt">te formul</span><span style="font-size:10.0pt">&aacute;ř</span> <span style="font-size:10.0pt">ž&aacute;</span><span style="font-size:10.0pt">dosti o poskytnut</span><span style="font-size:10.0pt">&iacute;</span> <span style="font-size:10.0pt">&uacute;</span><span style="font-size:10.0pt">v</span><span style="font-size:10.0pt">ě</span><span style="font-size:10.0pt">ru. V</span><span style="font-size:10.0pt">&scaron;</span><span style="font-size:10.0pt">echny </span><span style="font-size:10.0pt">&uacute;</span><span style="font-size:10.0pt">daje zad</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">v</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">te p</span><span style="font-size:10.0pt">ř&iacute;</span><span style="font-size:10.0pt">mo na str</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">nk</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">ch BNP PARIBAS PERSONAL FINANCE (kter</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt"> v </span><span style="font-size:10.0pt">Č</span><span style="font-size:10.0pt">R u</span><span style="font-size:10.0pt">ž&iacute;</span><span style="font-size:10.0pt">v</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt"> obchodn</span><span style="font-size:10.0pt">&iacute;</span><span style="font-size:10.0pt"> zna</span><span style="font-size:10.0pt">č</span><span style="font-size:10.0pt">ku Hello bank! by Cetelem) a jsou považov&aacute;ny za př&iacute;sně důvěrn&eacute;.</span></span></li>
				<li style="text-align:justify"><span style="font-size:11pt"><span style="font-size:10.0pt">Okamžitě po vyplněn&iacute; a odesl&aacute;n&iacute; ž&aacute;dosti o poskytnut&iacute; &uacute;věru se V&aacute;m zobraz&iacute; předběžn&eacute; vyj&aacute;dřen&iacute; k</span>&nbsp;<span style="font-size:10.0pt">Va</span><span style="font-size:10.0pt">&scaron;&iacute;</span> <span style="font-size:10.0pt">ž&aacute;</span><span style="font-size:10.0pt">dosti od Hello bank!</span></span></li>
			</ol>

			<p style="margin-left:30pt; margin-right:0cm"><span style="font-size:10pt">Pokud byla Va&scaron;e ž&aacute;dost o &uacute;věr předběžně schv&aacute;lena, vytiskněte si smluvn&iacute; dokumentaci a seznamte se se smluvn&iacute;mi podm&iacute;nkami. Podepi&scaron;te př&iacute;slu&scaron;n&eacute; dokumenty dle uveden&yacute;ch instrukc&iacute; a s kopiemi v&scaron;ech požadovan&yacute;ch dokladů je za&scaron;lete po&scaron;tou na adresu: <span style="color:#4bacc6">&Uacute;věrov&eacute; oddělen&iacute; (spotřebitelsk&yacute; &uacute;věr), BNP Paribas Personal Finance SA, od&scaron;těpn&yacute; z&aacute;vod, Budova Nov&aacute; Karolina Park, 28.ř&iacute;jna 3348/65, 702 00 Ostrava &ndash; Moravsk&aacute; Ostrava</span></span></p>

			<ol start="6">
				<li style="text-align:justify"><span style="font-size:11pt"><span style="font-size:10.0pt">Po obdržen&iacute; v&scaron;ech dokumentů a dokladů V&aacute;s bude Hello bank! informovat o stavu Va&scaron;&iacute; ž&aacute;dosti o &uacute;věr a v</span>&nbsp;<span style="font-size:10.0pt">p</span><span style="font-size:10.0pt">ř&iacute;</span><span style="font-size:10.0pt">pad</span><span style="font-size:10.0pt">ě</span><span style="font-size:10.0pt"> jej</span><span style="font-size:10.0pt">&iacute;</span><span style="font-size:10.0pt">ho kone</span><span style="font-size:10.0pt">č</span><span style="font-size:10.0pt">n</span><span style="font-size:10.0pt">&eacute;</span><span style="font-size:10.0pt">ho schv</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">len</span><span style="font-size:10.0pt">&iacute;</span><span style="font-size:10.0pt"> V</span><span style="font-size:10.0pt">&aacute;</span><span style="font-size:10.0pt">s budeme informovat o term&iacute;nu dopravy a před&aacute;n&iacute; poř&iacute;zen&eacute;ho zbož&iacute;. </span></span></li>
			</ol>
		</div>
		
		
		<b>Vyplňte následující údaje</b><p/>
		<form method='post'>
		<table>


		<tr> <td> Cena zboží </td> <td> <input type='text' name='cenaZbozi' value='<?php echo $parametry['cenaZbozi']?>'> <span>Kč</span></td> </tr>
		<tr> <td> Typ úvěru </td> <td> <select name="kodBaremu"> <?php $this->installmentWindowCallback_writeChoices($baremCis, $parametry['kodBaremu'])?> </select> </td> </tr>
		<tr> <td> Typ pojištění </td> <td> <select name="kodPojisteni"> <?php $this->installmentWindowCallback_writeChoices($pojisteniCis, $parametry['kodPojisteni'])?> </select> </td> </tr>
		<tr> <td> Přímá platba </td> <td> <input type='text' name='primaPlatba' value='<?php echo $parametry['primaPlatba']?>'> <span>Kč</span></td> </tr>
		<tr> <td> Počet splátek... </td> <td> <input type='text' name='pocetSplatek' value='<?php echo $parametry['pocetSplatek']?>'> </td> </tr>
		<tr> <td> ...nebo výše splátky </td> <td> <input type='text' name='vyseSplatky' value='<?php echo $parametry['vyseSplatky']?>'> <span>Kč</span> </td> </tr>
		<tr><td></td><td style="justify-content: flex-end;"> <input type="submit" name="prepocitat" value="Přepočítat"> </td></tr>
		</table>

		</form>
		</div>

		<?php

		if (isset($_REQUEST['prepocitat']) && strpos($this->config->cetelemUrl, 'mockCetelem') === false) {
			$resParam = $parametry;
			$status = $cetelem->callWebCalc($resParam, $info);

			//var_dump($status, $info, $resParam);
			?>
			<?php if (trim($info) != '') { ?>
				Upozornění: <?php echo $info?><br/>
			<?php }

			if ($status == 'ok') { ?>
				<p/><div class="vysledek">
				<b>Parametry Vašeho úvěru</b></p>
				<table>
					<!--
					<tr> <td> Cena zboží </td> <td> <?php echo $resParam['cenaZbozi']?> Kč</td></tr>
					<tr> <td> Přímá platba </td> <td> <?php echo $resParam['primaPlatba']?> Kč</td></tr>
					-->
					<tr> <td> Celková výše úvěru </td> <td> <?php echo $resParam['vyseUveru']?> Kč</td></tr>
					<tr> <td> Počet měsíčních splátek </td> <td> <?php echo $resParam['pocetSplatek']?></td> </tr>
					<tr> <td> Celková výše měsíční splátky (včetně pojištění, má-li být sjednáno) </td> <td> <?php echo $resParam['vyseSplatky']?> Kč</td> </tr>
					<tr> <td> Cena úvěru (včetně pojištění, má-li být sjednáno) </td> <td> <?php echo $resParam['cenaUveru']?> Kč</td></tr>
					<tr> <td> RPSN </td> <td> <?php echo $resParam['RPSN']?>%</td></tr>
					<tr> <td> Roční úroková sazba </td> <td> <?php echo $resParam['ursaz']?>%</td> </tr>
					<tr> <td> Za úvěr celkem zaplatíte </td> <td> <?php echo $resParam['celkovaCastka']?> Kč</td> </tr>
				</table>
				</div>
				<?php
			} else {
				echo "Kalkulaci nelze provést. Opravte prosím parametry splátek.";
			}
		}

		?>
		</body>
		</html>
		<?php

	}
	
	function installmentWindowCallback_writeChoices($choices, $default) {
		//echo "<option value=''>prazdne</option>\n";
		foreach($choices as $chvalue=>$chtext) {
			$sel = ($chvalue==$default)?" selected ":"";
			echo "<option value='".$chvalue."' ".$sel.">".$chtext."</option>\n";
		}
	}

	

}


