<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#


require_once(dirname(__FILE__)."/UniModul.php");
require_once(dirname(__FILE__)."/muzo.php");
require_once(dirname(__FILE__)."/MuzoWebServices.php");

class UniGPWebPayConfig {
	public $isTest;
	var $publicKeyFile;
	var $privateKeyFile;
	var $privateKeyPass;
	public $merchantNumber;
	var $depositFlag;
	var $gwOrderNumberOffset;
	var $cronSecret;
	var $provider;
	public $convertToCurrencyIfUnsupported; // jedna mena
	public $subMethodsSelection; //array submetod
}


class UniGPWebPay extends UniModul {
	public $uniModulProperties = array('HonorsShopOrderNumberIsAlpha'=>true);

	public function __construct($configSetting, $subMethod, $name="GPWebPay") {
		$this->versionStr = "#Ver:PRV088-14-ge016fa1f:2021-08-25#";
		parent::__construct($name, $configSetting, $subMethod);
		$this->setConfigFromData($configSetting);
	}

	function setConfigFromData($configSetting) {
		$this->config = new UniGPWebPayConfig();
		if ($configSetting != null && $configSetting->configData != null) {
			$configData = $configSetting->configData;
			$this->config->isTest = $configData['isTest'];
			$this->config->publicKeyFile = dirname(__FILE__) . "/certs/" . ($this->config->isTest ? 'gpe.signing_test.pem' : 'gpe.signing_prod.pem');
			$this->config->privateKeyFile = dirname(__FILE__) . "/certs/" . $configData['privateKeyFile'];
			$this->config->privateKeyPass = $configData['privateKeyPass'];
			$this->config->merchantNumber = $configData['merchantNumber'];
			$this->config->depositFlag = $configData['depositFlag'];
			$this->config->gwOrderNumberOffset = $configData['gwOrderNumberOffset'];
			$this->config->supportedCurrencies = $configData['supportedCurrencies'];
			$this->config->convertToCurrencyIfUnsupported = isset($configData['convertToCurrencyIfUnsupported']) ? $configData['convertToCurrencyIfUnsupported'] : null;
			$this->config->provider = isset($configData['provider']) ? $configData['provider'] : null;
			$this->config->cronSecret = isset($configData['cronSecret']) ? $configData['cronSecret'] : null;
			$this->config->subMethodsSelection = explode(' ',$configSetting->configData['subMethodsSelection']);
		}
	}

	function getConfigInfo($language='en') {

		$d = $this->dictionary;
		$d->setDefaultLanguage($language);

		$configInfo = new ConfigInfo();

		$configFields = array();

		$configFields[] = create_initialize_object('ConfigField', array('name'=>'isTest', 'label'=>$d->get('isTest'), 'type'=>ConfigFieldType::$choice, 'choiceItems'=>array(1=>$d->get('yes'), 0=>$d->get('no'))));

        $configField        = new ConfigField();
        $configField->name  = 'merchantNumber';
        $configField->label = $d->get( 'merchantNumber' );
        $configField->type  = ConfigFieldType::$text;
        $configFields[]     = $configField;

		$configField = new ConfigField();
		$configField->name = 'privateKeyFile';
		$configField->label = $d->get('privateKeyFile');
		$configField->type = ConfigFieldType::$text;
		$configFields[] = $configField;

		$configField = new ConfigField();
		$configField->name = 'privateKeyPass';
		$configField->label = $d->get('privateKeyPass');
		$configField->type = ConfigFieldType::$text;
		$configFields[] = $configField;

		$configField = new ConfigField();
		$configField->name = 'depositFlag';
		$configField->label = $d->get('depositFlag');
		$configField->type = ConfigFieldType::$choice;
		$configField->choiceItems = array(1=>$d->get('deposit_1'), 0=>$d->get('deposit_0'));
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'provider';
		$configField->label = $d->get('provider');
		$configField->type = ConfigFieldType::$text;
		$configFields[] = $configField;

		$configField = new ConfigField();
		$configField->name = 'cronSecret';
		$configField->label = $d->get('cronSecret');
		$configField->type = ConfigFieldType::$text;
		$configFields[] = $configField;

		$configField = new ConfigField();
		$configField->name = 'gwOrderNumberOffset';
		$configField->label = $d->get('gwOrderNumberOffset');
		$configField->type = ConfigFieldType::$text;
		$configFields[] = $configField;

		$configField = new ConfigField();
		$configField->name = 'supportedCurrencies';
		$configField->label = $d->get('supportedCurrencies');
		$configField->type = ConfigFieldType::$text;
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'convertToCurrencyIfUnsupported';
		$configField->label = $d->get('convertToCurrencyIfUnsupported');
		$configField->type = ConfigFieldType::$text;
		$configFields[] = $configField;

		$configField = new ConfigField();
		$configField->name = 'orderStatusSuccessfull';
		$configField->label = $d->get('orderStatusSuccessfull');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'subMethodsSelection';
		$configField->label = $d->get('subMethodsSelection');
		$configField->type = ConfigFieldType::$subMethodsSelection;
		$configField->choiceItems = array('main'=>'');  //povolime obecnou submetodu
		$configFields[]=$configField;

		$configInfo->configFields = $configFields;
		return $configInfo;
	}


	protected $subMethods = array('GooglePay'=>'GPAY', 'ApplePay'=>'APAY');
	public function getSubMethods() {
		return array_keys($this->subMethods);
	}

	function queryPrePayGWInfo($orderToPayInfo) {
		if ($orderToPayInfo->subMethod==null) {  //fix pro subModuly
			$orderToPayInfo->subMethod = $this->subMethod;
		}

		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = $this->fixCurrency($orderToPayInfo);

		$prePayGWInfo = new PrePayGWInfo();
		$methodNameKey = ($orderToPayInfo->subMethod == '') ? 'payment_method_name' : 'submethod_name_'.$orderToPayInfo->subMethod;
		$prePayGWInfo->paymentMethodName = $this->dictionary->get($methodNameKey, $orderToPayInfo->language);
		$prePayGWInfo->isPossible = $isPossible;
		$prePayGWInfo->forexMessage = $forexMessage;

		if ($orderToPayInfo->subMethod != null) {
			$prePayGWInfo->subMethods = array($orderToPayInfo->subMethod);
		} else {
			$prePayGWInfo->subMethods = $this->config->subMethodsSelection;
		}
		$isSafari = strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') === false;
		if (!$isSafari) {
			$prePayGWInfo->subMethods = array_filter($prePayGWInfo->subMethods, function($v) {return $v != 'ApplePay';});
		}
		if (empty($prePayGWInfo->subMethods)) {
			$prePayGWInfo->isPossible = false;
		}
		return $prePayGWInfo;
	}

	function gatewayOrderRedirectAction($orderToPayInfo) {
		if ($orderToPayInfo->subMethod==null) {  //fix pro subModuly
			$orderToPayInfo->subMethod = $this->subMethod;
		}
		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = $this->fixCurrency($orderToPayInfo);

		if (!$isPossible) {
			$transactionPK = $this->writeOrderToDb($orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, $forexNote, $orderReplyStatusFail->orderStatus, $orderToPayInfo->uniAdapterData);
			$this->logger->writeLog("CANNOT SEND ORDER ".print_r($orderToPayInfo, true)."   resultText:".$orderReplyStatusFail->resultText);

			$redirectActionFail = new RedirectAction();
			$redirectActionFail->orderReplyStatus = $orderReplyStatusFail;
			return $redirectActionFail;
		}

		$amount = round($newtotal * 100);

		$currencyCodes = array("CZK"=>203, "EUR"=>978, "GBP"=>826, "USD"=>840, "PLN"=>985, "HUF"=>348, "RUB"=>643, "RON"=>946);
		$currency = $currencyCodes[$newcur];
		$merchantNumber = $this->config->merchantNumber;

		$transactionPK = $this->writeOrderToDb($orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, $forexNote, null, $orderToPayInfo->uniAdapterData);
		$gwOrderNumber = $transactionPK + $this->config->gwOrderNumberOffset;
		$this->updateGwOrderNumber($transactionPK, $gwOrderNumber);

		$isNumericShopOrderNumber = preg_match('/^[0-9]+$/', $orderToPayInfo->shopOrderNumber);
		$merOrderNum = $isNumericShopOrderNumber ? $orderToPayInfo->shopOrderNumber : $gwOrderNumber;
		$referenceNumber = preg_match('~^[ #$*+,-./0-9:;=@A-Z^_a-z]+$~', $orderToPayInfo->shopOrderNumber) ? $orderToPayInfo->shopOrderNumber : $gwOrderNumber;

		$description = '';
		if (!$isNumericShopOrderNumber && $orderToPayInfo->shopOrderNumber != '') $description .= "(Ord.No.:" . $orderToPayInfo->shopOrderNumber . ") ";
		$description .= $orderToPayInfo->description;
		require_once('toascii.php');
		$description = toASCII($description);
		$description = substr($description, 0, 255);

		if ($orderToPayInfo->recurrenceType == RecurrenceType::child) {

			$this->logger->writeLog("Vytvarim child platbu pro parent order " . $orderToPayInfo->recurrenceParentShopOrderNumber);
			$parentTransaction = $this->getOrderTransactionRecordFromDbLast($orderToPayInfo->recurrenceParentShopOrderNumber);
			if ($parentTransaction == null) {
				$redirectAction = $this->getImmediateReplyStatusRedirectAction($orderToPayInfo, OrderStatus::$failedFinal);
				$this->logger->writeLog("ERROR: parent order " . $orderToPayInfo->recurrenceParentShopOrderNumber . " not found.");
			} else {
				$masterPaymentNumber = $parentTransaction->gwOrderNumber;
				$srv = $this->createWebService();

				try {
					$srv->processUsageBasedSubscriptionPayment($gwOrderNumber, // messageId
					$masterPaymentNumber, $gwOrderNumber, $merOrderNum, $amount, $currency, $this->config->depositFlag, $res);
					// pokud bez vyjimky, tak ok
					$redirectAction = $this->getImmediateReplyStatusRedirectAction($orderToPayInfo, OrderStatus::$successful);
				} catch (SoapFault $e) {
					$redirectAction = $this->getImmediateReplyStatusRedirectAction($orderToPayInfo, OrderStatus::$failedFinal);

					if (false && $e->detail->paymentServiceException->primaryReturnCode == 15) {} else {
						$this->logger->writeLogNoNewLines('GPWebPay Webservice exception ' . $e->getMessage() . ' PRCODE:' . $e->detail->paymentServiceException->primaryReturnCode . ' SRCODE:' . $e->detail->paymentServiceException->secondaryReturnCode);
					}
				}
			}
		} else { // normalni ne-child transakce

			$urlMuzoCreateOrder = $this->config->isTest ? 'https://test.3dsecure.gpwebpay.com/pgw/order.do' : 'https://3dsecure.gpwebpay.com/pgw/order.do';
			$xaddInfo = $this->createAddinfo($orderToPayInfo);
			$redirectHtml = muzo_CreateOrderPostForm(
				$urlMuzoCreateOrder, // adresa kam posilat pozadavek do Muzo
				$orderToPayInfo->replyUrl, // adresa kam ma Muzo presmerovat odpoved
				$this->config->privateKeyFile, // soubor s privatnim klicem
				$this->config->privateKeyPass, // heslo privatniho klice
				$merchantNumber, // cislo obchodnika
				$gwOrderNumber, // cislo objednavky
				$amount, // hodnota objednavky v halerich
				$currency, // kod meny, CZK..203, EUR..978, GBP..826, USD..840, povolene meny zalezi na smlouve s bankou
				$this->config->depositFlag, // uhrada okamzite "1", nebo uhrada az z admin rozhrani
				$merOrderNum, // identifikace objednavky pro obchodnika
				$description, // popis nakupu, pouze ASCII
				"X", // data obchodnika, pouze ASCII
				$orderToPayInfo->language,
				$orderToPayInfo->customerData->email,
				$orderToPayInfo->recurrenceType == RecurrenceType::parent,
				$referenceNumber,
				empty($orderToPayInfo->subMethod) ? null : $this->subMethods[$orderToPayInfo->subMethod],
				$xaddInfo
				);
			$this->logger->writeLogNoNewLines("MAKING_ORDER_FORM " . $redirectHtml . "   " . $_SERVER['REMOTE_ADDR'] . " " . $_SERVER['REQUEST_URI']);

			$redirectAction = new RedirectAction();
			$redirectAction->redirectForm = $redirectHtml;
		}
		return $redirectAction;
	}

	private function createAddinfo($orderToPayInfo) {

		$isoCountryToNum = array('AF'=>'004','AX'=>'248','AL'=>'008','DZ'=>'012','AS'=>'016','AD'=>'020','AO'=>'024','AI'=>'660','AQ'=>'010','AG'=>'028','AR'=>'032','AM'=>'051','AW'=>'533','AU'=>'036','AT'=>'040','AZ'=>'031','BS'=>'044','BH'=>'048','BD'=>'050','BB'=>'052','BY'=>'112','BE'=>'056','BZ'=>'084','BJ'=>'204','BM'=>'060','BT'=>'064','BO'=>'068','BQ'=>'535','BA'=>'070','BW'=>'072','BV'=>'074','BR'=>'076','IO'=>'086','BN'=>'096','BG'=>'100','BF'=>'854','BI'=>'108','CV'=>'132','KH'=>'116','CM'=>'120','CA'=>'124','KY'=>'136','CF'=>'140','TD'=>'148','CL'=>'152','CN'=>'156','CX'=>'162','CC'=>'166','CO'=>'170','KM'=>'174','CG'=>'178','CD'=>'180','CK'=>'184','CR'=>'188','CI'=>'384','HR'=>'191','CU'=>'192','CW'=>'531','CY'=>'196','CZ'=>'203','DK'=>'208','DJ'=>'262','DM'=>'212','DO'=>'214','EC'=>'218','EG'=>'818','SV'=>'222','GQ'=>'226','ER'=>'232','EE'=>'233','SZ'=>'748','ET'=>'231','FK'=>'238','FO'=>'234','FJ'=>'242','FI'=>'246','FR'=>'250','GF'=>'254','PF'=>'258','TF'=>'260','GA'=>'266','GM'=>'270','GE'=>'268','DE'=>'276','GH'=>'288','GI'=>'292','GR'=>'300','GL'=>'304','GD'=>'308','GP'=>'312','GU'=>'316','GT'=>'320','GG'=>'831','GN'=>'324','GW'=>'624','GY'=>'328','HT'=>'332','HM'=>'334','VA'=>'336','HN'=>'340','HK'=>'344','HU'=>'348','IS'=>'352','IN'=>'356','ID'=>'360','IR'=>'364','IQ'=>'368','IE'=>'372','IM'=>'833','IL'=>'376','IT'=>'380','JM'=>'388','JP'=>'392','JE'=>'832','JO'=>'400','KZ'=>'398','KE'=>'404','KI'=>'296','KP'=>'408','KR'=>'410','KW'=>'414','KG'=>'417','LA'=>'418','LV'=>'428','LB'=>'422','LS'=>'426','LR'=>'430','LY'=>'434','LI'=>'438','LT'=>'440','LU'=>'442','MO'=>'446','MG'=>'450','MW'=>'454','MY'=>'458','MV'=>'462','ML'=>'466','MT'=>'470','MH'=>'584','MQ'=>'474','MR'=>'478','MU'=>'480','YT'=>'175','MX'=>'484','FM'=>'583','MD'=>'498','MC'=>'492','MN'=>'496','ME'=>'499','MS'=>'500','MA'=>'504','MZ'=>'508','MM'=>'104','NA'=>'516','NR'=>'520','NP'=>'524','NL'=>'528','NC'=>'540','NZ'=>'554','NI'=>'558','NE'=>'562','NG'=>'566','NU'=>'570','NF'=>'574','MK'=>'807','MP'=>'580','NO'=>'578','OM'=>'512','PK'=>'586','PW'=>'585','PS'=>'275','PA'=>'591','PG'=>'598','PY'=>'600','PE'=>'604','PH'=>'608','PN'=>'612','PL'=>'616','PT'=>'620','PR'=>'630','QA'=>'634','RE'=>'638','RO'=>'642','RU'=>'643','RW'=>'646','BL'=>'652','SH'=>'654','KN'=>'659','LC'=>'662','MF'=>'663','PM'=>'666','VC'=>'670','WS'=>'882','SM'=>'674','ST'=>'678','SA'=>'682','SN'=>'686','RS'=>'688','SC'=>'690','SL'=>'694','SG'=>'702','SX'=>'534','SK'=>'703','SI'=>'705','SB'=>'090','SO'=>'706','ZA'=>'710','GS'=>'239','SS'=>'728','ES'=>'724','LK'=>'144','SD'=>'729','SR'=>'740','SJ'=>'744','SE'=>'752','CH'=>'756','SY'=>'760','TW'=>'158','TJ'=>'762','TZ'=>'834','TH'=>'764','TL'=>'626','TG'=>'768','TK'=>'772','TO'=>'776','TT'=>'780','TN'=>'788','TR'=>'792','TM'=>'795','TC'=>'796','TV'=>'798','UG'=>'800','UA'=>'804','AE'=>'784','GB'=>'826','US'=>'840','UM'=>'581','UY'=>'858','UZ'=>'860','VU'=>'548','VE'=>'862','VN'=>'704','VG'=>'092','VI'=>'850','WF'=>'876','EH'=>'732','YE'=>'887','ZM'=>'894','ZW'=>'716');


		$xaddInfo = '<?xml version="1.0" encoding="UTF-8"?>
			<additionalInfoRequest xmlns="http://gpe.cz/gpwebpay/additionalInfo/request" version="4.0">
			  <cardholderInfo>
				<cardholderDetails/>
				<billingDetails/>
				<shippingDetails/>
			  </cardholderInfo>
			</additionalInfoRequest>
			';

		$name = $orderToPayInfo->customerData->first_name . ' ' . $orderToPayInfo->customerData->last_name;
		$name = mb_substr($name, 0, 45);
		if (strlen($name) < 2) $name = $name + ' ';

		$address = mb_substr($orderToPayInfo->customerData->street . ' ' . $orderToPayInfo->customerData->houseNumber, 0, 50);
		$city = mb_substr($orderToPayInfo->customerData->city, 0, 50);
		$postalCode = mb_substr($orderToPayInfo->customerData->post_code, 0, 16);
		$country = $isoCountryToNum[$orderToPayInfo->customerData->country];


		$xml = simplexml_load_string($xaddInfo);
		$xml->cardholderInfo->cardholderDetails->addChild("name", $name);
		$xml->cardholderInfo->cardholderDetails->addChild("email", $orderToPayInfo->customerData->email);

		$xml->cardholderInfo->billingDetails->addChild("name", $name);
		$xml->cardholderInfo->billingDetails->addChild("address1", $address);
		$xml->cardholderInfo->billingDetails->addChild("city", $city);
		$xml->cardholderInfo->billingDetails->addChild("postalCode", $postalCode);
		$xml->cardholderInfo->billingDetails->addChild("country", $country);

		$xml->cardholderInfo->shippingDetails->addChild("name", $name);
		$xml->cardholderInfo->shippingDetails->addChild("address1", $address);
		$xml->cardholderInfo->shippingDetails->addChild("city", $city);
		$xml->cardholderInfo->shippingDetails->addChild("postalCode", $postalCode);
		$xml->cardholderInfo->shippingDetails->addChild("country", $country);

		$str = $xml->asXML();

		return $str;
	}


	public function gatewayReceiveReply($language='en') {
		$sigValid = muzo_ReceiveReply($this->config->publicKeyFile, $gwOrderNumber, $merOrderNum, $md, $prCode, $srCode, $resultText, $this->config->merchantNumber);
		$paymentOk = ($sigValid && $prCode == 0 && $srCode == 0);

		$this->logger->writeLog("REPLY Signature result=".($paymentOk?"OK":"NOK")." signature: ".($sigValid?"VALID":"INVALID") . "  " . $_SERVER['REMOTE_ADDR']." ".$_SERVER['REQUEST_URI']);
		if ($paymentOk) {
			$errMsg = null;
		} else {
			$errClass = classify_error($sigValid, $prCode, $srCode);
			$errMsgMap = array(GPWP_E_3DSECURE=>'GPWP_E_3DSECURE', GPWP_E_BLOCKED=>'GPWP_E_BLOCKED', GPWP_E_LIMIT=>'GPWP_E_LIMIT', GPWP_E_TECHNICAL=>'GPWP_E_TECHNICAL',  GPWP_E_CANCELED=>'GPWP_E_CANCELED');
			$errMsg = $this->dictionary->get($errMsgMap[$errClass],$language);  // kde tady vzit jazyk?
		}

		$transactionRecord = $this->getOrderTransactionRecordFromDbUnique($gwOrderNumber);
		$orderReplyStatus = new OrderReplyStatus();
		$orderReplyStatus->orderStatus = ($paymentOk ? OrderStatus::$successful : OrderStatus::$failedRetriable);
		$orderReplyStatus->resultText = $errMsg;
		$orderReplyStatus->gwOrderNumber = $gwOrderNumber;
		$orderReplyStatus->shopOrderNumber = $transactionRecord->shopOrderNumber;
		$orderReplyStatus->shopPairingInfo = $transactionRecord->shopPairingInfo;
		$orderReplyStatus->forexNote = $transactionRecord->forexNote;
		$orderReplyStatus->uniAdapterData = $transactionRecord->uniAdapterData;
		$this->updateOrderReplyStatusGwOrdNumInDb($orderReplyStatus);

		$this->logger->writeLog("orderReplyStatus=" . $orderReplyStatus->orderStatus);
		return $orderReplyStatus;
	}

	public function processCallbackRequest($callbackName, $arguments) {
		if ($callbackName == "checkOrderStatuses") {
			$this->checkOrderStatuses();
		} else {
			parent::processCallbackRequest($callbackName, $arguments);
		}
	}

	function checkOrderStatuses() {
		$this->logger->writeLogNoNewLines("GPWebPay checkOrderStatuses started");
		if ($_GET["cronSecret"] != $this->config->cronSecret && !empty($this->config->cronSecret)) {
			$this->logger->writeLogNoNewLines("GPWebPay checkOrderStatuses wrong cronSecret");
			die("GPWebPay wrong cronSecret");
		}
		$pendingTrans = $this->getAllPendingOrderTransactionRecords(array(OrderStatus::$initiated, OrderStatus::$pending), new DateTime('3 days ago'));

		$srv = $this->createWebService();
		foreach ($pendingTrans as $shopTrans) {
			$this->logger->writeLogNoNewLines("GPWebPay open transaction " . print_r($shopTrans, true));
			if (is_numeric($shopTrans->uniModulData)) { // kompat s predchozi verzi s vice moznymi merchatny, a kdy zde bylo ulozeno konkretni merchantNumber
				$srv->MerchantNumber = $shopTrans->uniModulData;
			}
			try {
				$res = $srv->getPaymentDetail($shopTrans->gwOrderNumber);

				if (in_array($res->status, array('PENDING_CAPTURE', 'PENDING_SETTLEMENT', 'PROCESSED', 'CAPTURED', 'PENDING_ADJUSTMENT', 'PARTIAL_PAYMENT', 'VALID'))) {
					$newStatus = OrderStatus::$successful;
				} else if (in_array($res->status, array('PENDING_AUTHORIZATION', 'CREATED'))
						|| ($res->status == 'UNPAID' && in_array($res->subStatus, array('PGW_PAGE', '3DS_REDIRECT', '3DS_SUBMIT', 'PAYMENT_REDIRECT', 'MPS_SCH_REDIRECT', 'MPS_SCH_SUBMIT', 'MPS_SCH_CANCEL', 'DEFERRED_SUBMIT', 'PGW_ORDER')))) {
					$newStatus = null;
				} else if (in_array($res->status, array('EXPIRED', 'CANCELED', 'BLOCKED'
						, 'REVERSED', 'REFUNDED', 'CANCELED_BY_MERCHANT', 'CANCELED_BY_ISSUER', 'CANCELED_BY_CARDHOLDER', 'EXPIRED_CARD', 'EXPIRED_NO_PAYMENT'))
						|| ($res->status == 'UNPAID' && in_array($res->subStatus, array('CANCELED', 'TECHNICAL_PROBLEM', 'FRAUD', 'DECLINED'))))  {
					$newStatus = OrderStatus::$failedFinal;
				} else {
					$newStatus = null;
					$this->logger->writeLog("GPWebPay unknown transaction status: " . $res->status . ", subStatus: " . $res->subStatus);
				}
			} catch (SoapFault $e) {
				if ($e->detail->serviceException->primaryReturnCode == 15) {
					$newStatus = OrderStatus::$failedFinal;
				} else {
					$newStatus = null;
					$this->logger->writeLogNoNewLines('GPWebPay Webservice exception ' . $e->getMessage() . ' PRCODE:' . $e->detail->serviceException->primaryReturnCode . ' SRCODE:' . $e->detail->serviceException->secondaryReturnCode);
				}
			}

			$this->logger->writeLogNoNewLines("GPWebPay Webservice request " . $srv->mws->__getLastRequest());
			$this->logger->writeLogNoNewLines("GPWebPay Webservice response " . $srv->mws->__getLastResponse());

			$this->logger->writeLogNoNewLines("GPWebPay Updating gwOrderNumber " . $shopTrans->gwOrderNumber . " to state " . ($newStatus === null ? "(no change, still awaiting authorization)" : $newStatus));
			if ($newStatus !== null) {
				$orderReplyStatus = new OrderReplyStatus();
				$orderReplyStatus->gwOrderNumber = $shopTrans->gwOrderNumber;
				$orderReplyStatus->shopOrderNumber = $shopTrans->shopOrderNumber;
				$orderReplyStatus->shopPairingInfo = $shopTrans->shopPairingInfo;
				$orderReplyStatus->uniAdapterData = $shopTrans->uniAdapterData;
				$orderReplyStatus->orderStatus = $newStatus;
				$this->updateOrderReplyStatusGwOrdNumInDb($orderReplyStatus, $shopTrans->transactionPK);
				$orderReplyStatus->orderStatus = $newStatus;
				call_user_func($this->baseConfig->funcProcessReplyStatus, $orderReplyStatus);
			}

		}
		$this->logger->writeLogNoNewLines("GPWebPay checkOrderStatuses finished");
	}

	/**
	 */
	private function createWebService() {
		$serviceUrl = $this->config->isTest ? 'https://test.3dsecure.gpwebpay.com/pay-ws/v1/PaymentService' : 'https://3dsecure.gpwebpay.com/pay-ws/v1/PaymentService';
		$srv = new MuzoWebServices(dirname(__FILE__) . '/cws_v1.wsdl', $serviceUrl, $this->config->provider, $this->config->merchantNumber, $this->config->publicKeyFile, $this->config->privateKeyFile, $this->config->privateKeyPass, $this->logger);
		return $srv;
	}

	public function getInfoBoxData($uniAdapterName, $language) {
		$infoBoxData = parent::getInfoBoxData($uniAdapterName, $language);
		$infoBoxData->title = $this->dictionary->get('infoBoxTitle', $language);
		$infoBoxData->link = null;
		$infoBoxData->image = 'visamastersecure.png';
		return $infoBoxData;
	}

}



define('GPWP_E_OK', 0);
define('GPWP_E_3DSECURE', 1);
define('GPWP_E_BLOCKED', 2);
define('GPWP_E_LIMIT', 3);
define('GPWP_E_TECHNICAL', 4);
define('GPWP_E_CANCELED', 5);

function classify_error($valid, $prCode, $srCode) {
	if ($valid && $prCode == 0 && $srCode == 0) return GPWP_E_OK;
	if (!$valid) return GPWP_E_TECHNICAL;
	if ($prCode == 28 && false!==array_search($srCode, array(3000, 3002, 3004, 3005, 3008))) return GPWP_E_3DSECURE;
	else if ($prCode == 30 && false!==array_search($srCode, array(1001, 1002))) return GPWP_E_BLOCKED;
	else if ($prCode == 30 && false!==array_search($srCode, array(1003, 1005))) return GPWP_E_LIMIT;
	else if ($prCode == 50) return GPWP_E_CANCELED;
	else return GPWP_E_TECHNICAL;
}
