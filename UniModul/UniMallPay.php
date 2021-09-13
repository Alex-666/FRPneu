<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-14-g21587217:2020-02-29#

require_once(dirname(__FILE__) . "/UniModul.php");
require_once(dirname(__FILE__) . '/Unirest.php');



class UniMallPayConfig {
	public $apiUsername;
	public $apiPassword;
	public $apiUrl;
	public $itemType;
	public $gwOrderNumberOffset;
	public $supportedCurrencies; // mezerou oddelene, napr "CZK EUR"
	public $convertToCurrencyIfUnsupported; //jedna mena
}


class UniMallPay extends UniModul {

	public function __construct($configSetting, $subMethod, $name="MallPay") {
		parent::__construct($name, $configSetting, $subMethod);
		$this->setConfigFromData($configSetting);
	}

	public function setConfigFromData($configSetting) {
		$this->config = new UniMallPayConfig();
		if ($configSetting != null && $configSetting->configData != null) {
			$configData = $configSetting->configData;
			$this->config->apiUsername = $configData['apiUsername'];
			$this->config->apiPassword = $configData['apiPassword'];
			$this->config->apiUrl = $configData['apiUrl'];
			$this->config->itemType = $configData['itemType'];
			$this->config->gwOrderNumberOffset = $configData['gwOrderNumberOffset'];
			$this->config->supportedCurrencies = $configData['supportedCurrencies'];
			$this->config->convertToCurrencyIfUnsupported = $configSetting->configData['convertToCurrencyIfUnsupported'];
		}
	}


	public function getConfigInfo($language='en') {

		$d = $this->dictionary;
		$d->setDefaultLanguage($language);


		$configInfo = new ConfigInfo();

		$configFields = array();
		$configFields[] = create_initialize_object('ConfigField', array('name'=>'apiUsername', 'label'=>$d->get('apiUsername'), 'type'=>ConfigFieldType::$text));
		$configFields[] = create_initialize_object('ConfigField', array('name'=>'apiPassword', 'label'=>$d->get('apiPassword'), 'type'=>ConfigFieldType::$text));
		$configFields[] = create_initialize_object('ConfigField', array('name'=>'apiUrl', 'label'=>$d->get('apiUrl'), 'type'=>ConfigFieldType::$text));

		$configField = new ConfigField();
		$configField->name = 'itemType';
		$configField->label = $d->get('itemType');
		$configField->type = ConfigFieldType::$choice;
		$configField->choiceItems = array('PHYSICAL'=>'PHYSICAL', 'DIGITAL'=>'DIGITAL');
		$configFields[]=$configField;

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
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'orderStatusSuccessfull';
		$configField->label = $d->get('orderStatusSuccessfull');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'orderStatusPending';
		$configField->label = $d->get('orderStatusPending');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;

		$configField = new ConfigField();
		$configField->name = 'orderStatusFailed';
		$configField->label = $d->get('orderStatusFailed');
		$configField->type = ConfigFieldType::$orderStatus;
		$configFields[]=$configField;


		$configInfo->configFields = $configFields;
		return $configInfo;
	}

	protected $subMethods = array(

	);


	public function getSubMethods() {
		return array_keys($this->subMethods);
	}

	public function queryPrePayGWInfo($orderToPayInfo) {
		if ($orderToPayInfo->subMethod==null) {  //fix pro subModuly
			$orderToPayInfo->subMethod = $this->subMethod;
		}
		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = $this->fixCurrency($orderToPayInfo);

		$d = $this->dictionary;
		$prePayGWInfo = new PrePayGWInfo();
		$methodNameKey = ($orderToPayInfo->subMethod == '') ? 'payment_method_name' : 'submethod_name_'.$orderToPayInfo->subMethod;
		$prePayGWInfo->paymentMethodName = $this->dictionary->get($methodNameKey, $orderToPayInfo->language);
		$prePayGWInfo->isPossible = $isPossible;
		$prePayGWInfo->forexMessage = $forexMessage;

		return $prePayGWInfo;
	}


	function gatewayOrderRedirectAction($orderToPayInfo) {
		$this->logger->writeLogNoNewLines("NEW_ORDER");
		$vychoziOrderToPayInfo = unserialize(serialize($orderToPayInfo));
		if ($orderToPayInfo->subMethod==null) {  //fix pro subModuly
			$orderToPayInfo->subMethod = $this->subMethod;
		}



		list($isPossible, $newcur, $newtotal, $forexMessage, $forexNote, $orderReplyStatusFail) = $this->fixCurrency($orderToPayInfo);
		if (!$isPossible) {
			$transactionPK = $this->writeOrderToDb($orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, $forexNote, $orderReplyStatusFail->orderStatus, $orderToPayInfo->uniAdapterData, null);
			$this->logger->writeLogNoNewLines("CANNOT SEND ORDER ".print_r($orderToPayInfo, true)."   resultText:".$orderReplyStatusFail->resultText);

			$redirectActionFail = new RedirectAction();
			$redirectActionFail->orderReplyStatus = $orderReplyStatusFail;
			return $redirectActionFail;
		}

		$description = mb_substr($orderToPayInfo->description , 0, 255, 'utf-8');
		$shopOrderNumberNonEmpty = $orderToPayInfo->shopOrderNumber != '' ? $orderToPayInfo->shopOrderNumber : $orderToPayInfo->shopPairingInfo;

		$uniModulData = array('currency'=>$newcur, 'amount'=>$newtotal, 'description'=>$description, 'shopOrderNumberNonEmpty'=>$shopOrderNumberNonEmpty);

		$transactionPK = $this->writeOrderToDb($orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, $forexNote, null, $orderToPayInfo->uniAdapterData, $uniModulData);

		$isNumericShopOrderNumber = preg_match('/^[0-9]+$/', $shopOrderNumberNonEmpty);
		if ($isNumericShopOrderNumber) {
			$varSymbol = $shopOrderNumberNonEmpty;
		} else {
			$varSymbol = $transactionPK + $this->config->gwOrderNumberOffset;
		}

		$mkPrice = function ($amount) use ($newcur) {return array('amount' => (int)round(100*$amount), 'currency' => $newcur);};
		$mkVat = function ($amount, $rate) use ($newcur) {return array('amount' => (int)round(100*$amount), 'currency' => $newcur, 'vatRate' => $rate);};

		$replyUrl = $orderToPayInfo->replyUrl . (strpos($orderToPayInfo->replyUrl, '?')===false ? '?' : '&') . "unipairinfo=" . urlencode($orderToPayInfo->shopPairingInfo);


		$applicData = array(
			'customer' => array(
				'firstName' => $orderToPayInfo->customerData->first_name,
				'lastName' => $orderToPayInfo->customerData->last_name,
				'email' => $orderToPayInfo->customerData->email,
				'phone' => $orderToPayInfo->customerData->phone,  // TODO +420
				// tin ICO
				// vatin DIC
				// ipAddress
			),
			'order' => array(
				'number' => $shopOrderNumberNonEmpty,
				'variableSymbols' => array($varSymbol),
				'totalPrice' => $mkPrice($newtotal),
				'totalVat' => 'XXX', // doplneno nize
				'addresses' => array(
					array(
						'name' => $orderToPayInfo->customerData->first_name . " " . $orderToPayInfo->customerData->last_name,
						'country' => $orderToPayInfo->customerData->country,
						'city' => $orderToPayInfo->customerData->city,
						'streetAddress' => $orderToPayInfo->customerData->street,
						'streetNumber' => $orderToPayInfo->customerData->houseNumber,
						'zip' => $orderToPayInfo->customerData->post_code,
						'addressType'=> 'BILLING'
					)
				),
				'items' => 'XXX', // doplneno nize
			),
			'type' => 'DEFERRED_PAYMENT',
			'merchantUrls'=> array(
				'approvedRedirect'=> $replyUrl,
				'rejectedRedirect'=> $replyUrl,
				'notificationEndpoint'=> $orderToPayInfo->notifyUrl,
			)
		);


		$items = array();
		if (empty($orderToPayInfo->cartItems)) {
			// JE TO PRO MALLPAY POUZITELNE ???????
			$defaultVat = 21;
			$applicData['items'] = array(array(
				'code' => $description,
				'name' => $description,
				'totalPrice' => $mkPrice($newtotal),
				'totalVat' => $mkVat($newtotal * (1 - 100/(100 + $defaultVat)), $defaultVat),
			));
		} else {
			$this->normalizeCartItems($orderToPayInfo);
			$i = 0;
			foreach($vychoziOrderToPayInfo->cartItems as $item) {
				$i++;
				if ((int)$item->unitPrice != 0) {
					$itemName = (string)$item->name;
					if ($item->type == UniCartItemType::delivery) $itemType = 'SHIPPING_FEE';
					else if ($item->type == UniCartItemType::discount) $itemType = 'DISCOUNT';
					else if ($item->type == UniCartItemType::commodity) $itemType = $this->config->itemType;
					else {
						$itemType = 'PHYSICAL';
						user_error("Neznamy UniCartItemType: " . $item->type);
					}
					$items[] = array(
						'code' => $orderToPayInfo->shopPairingInfo . '-' . $i,
						'name' => $item->name,
						'quantity' => round($item->quantity),
						'type' => $itemType,
						'totalPrice' => $mkPrice($item->unitPrice * $item->quantity),
						'totalVat' => $mkVat($item->unitTaxAmount * $item->quantity, $item->taxRate),
					);
				}
			}
		}
		$applicData['order']['items'] = $items;

		$totalVat = array();
		$eetCastkySazby = $this->getEetRozdeleni($orderToPayInfo, false);
		if (!empty($eetCastkySazby->dan1)) $totalVat[] = $mkVat($eetCastkySazby->dan1, 21);
		if (!empty($eetCastkySazby->dan2)) $totalVat[] = $mkVat($eetCastkySazby->dan2, 15);
		if (!empty($eetCastkySazby->dan3)) $totalVat[] = $mkVat($eetCastkySazby->dan3, 10);
		$applicData['order']['totalVat'] = $totalVat;


		try {
			$mallpay = $this->initMallPay();
			$response = $mallpay->callApi('POST', '/financing/v1/applications', $applicData);
			$this->updateGwOrderNumber($transactionPK, $response->id);
		} catch (Exception $e) {
			$msg = "Chyba MallPay CreatePayment: " . get_class($e) . ' ' . $e->getMessage();
			$this->logger->writeLog($msg);
			$redirectAction = $this->getImmediateReplyStatusRedirectAction($orderToPayInfo, OrderStatus::$failedFinal, "Technická chyba / Technical error");
			return $redirectAction;
		}

		if (false && $response['state'] != 'PROCESSING') {
			// mozna by bylo lepsi tam ty zakazniky presmerovavat vzdy, aspon v basic integration
			if ($response['state'] == 'READY') {
				$status = OrderStatus::$successful;
			} else if ($response['state'] == 'REJECTED' || $response['state'] == 'CANCELLED') {
				$status = OrderStatus::$failedFinal;
			} else {
				user_error("neznamy mallpay state");
				$status = OrderStatus::$invalidReply;
			}
			$redirectAction = $this->getImmediateReplyStatusRedirectAction($orderToPayInfo, OrderStatus::$successful, null);
			$redirectAction->orderReplyStatus->gwOrderNumber = $response['id'];
			return $redirectAction;

		} else {

			$redirectAction = new RedirectAction();
			$redirectAction->redirectUrl = $response->gatewayRedirectUrl;
			return $redirectAction;
		}
	}

	protected function initMallPay() {
		$mallpay = new MallPay($this->config->apiUsername, $this->config->apiPassword, $this->config->apiUrl, $this->logger);
		$mallpay->login();
		return $mallpay;
	}

	public function gatewayReceiveReply($language='en') {
		$this->logger->writeLogNoNewLines("REPLY   ".$_SERVER["REQUEST_URI"]);
		$transactionRecord = $this->getOrderTransactionRecordFromDbLast(null, $_GET['unipairinfo']);
		$orderReplyStatus = $this->callAndProcessMallPayGet($transactionRecord);
		return $orderReplyStatus;
	}

	function gatewayReceiveNotification() {
		$post = file_get_contents("php://input");
		$this->logger->writeLogNoNewLines("NOTIFY   ".$_SERVER["REQUEST_URI"] . " PostData: " . $post);
		$data = json_decode($post);
		$transactionRecord = $this->getOrderTransactionRecordFromDbUnique($data->id);
		$orderReplyStatus = $this->callAndProcessMallPayGet($transactionRecord);
		return $orderReplyStatus;
	}


	function callAndProcessMallPayGet($transactionRecord) {
		BeginSynchronized();
		if ($transactionRecord != null) {

			$mallpay = $this->initMallPay();

			try {
				$response = $mallpay->callApi('GET', '/financing/v1/applications/' . $transactionRecord->gwOrderNumber);
				$result = $response->state;

				if ($result == 'READY') {
					$orderStatus = OrderStatus::$successful;
				} else if ($result == 'PROCESSING') {
					$orderStatus = OrderStatus::$pending;
				} else if ($result == 'REJECTED' || $result == 'CANCELLED') {
					$orderStatus = OrderStatus::$failedFinal;
				} else {
					$msg = "neznama odpoved mallpay " . $result;
					$this->logger->writeLog($msg);
					$orderStatus = OrderStatus::$invalidReply;
				}

			} catch (Exception $e) {
				$msg = "Chyba MallPay getStatus: " . $e->getMessage();
				$this->logger->writeLog($msg);
				$orderStatus = OrderStatus::$invalidReply;
			}
		} else {
			$msg = "objednavka nenalezena";
			$this->logger->writeLog($msg);
			$orderStatus = OrderStatus::$invalidReply;
		}

		$this->logger->writeLogNoNewLines("IsPaymentDone uniStatus=".$orderStatus. (isset($result) ? "  goResult=".print_r($result, true) : ""));

		$orderReplyStatus = new OrderReplyStatus();
		$orderReplyStatus->orderStatus = $orderStatus;
		$orderReplyStatus->resultText = null; //preklad atp
		$orderReplyStatus->gwOrderNumber = $transactionRecord->gwOrderNumber;
		if ($orderStatus != OrderStatus::$invalidReply) {
			$orderReplyStatus->shopOrderNumber = $transactionRecord->shopOrderNumber;
			$orderReplyStatus->shopPairingInfo = $transactionRecord->shopPairingInfo;
			$orderReplyStatus->forexNote = $transactionRecord->forexNote;
			$orderReplyStatus->uniAdapterData = $transactionRecord->uniAdapterData;
			$this->updateOrderReplyStatusGwOrdNumInDb($orderReplyStatus);
		}
		EndSynchronized();
		$orderReplyStatus->orderStatus = $this->ensureGlobalPairingInfoStatusUpgradeOnly($orderReplyStatus);
		return $orderReplyStatus;
	}


	public function getInfoBoxData($uniAdapterName, $language) {
		$infoBoxData = parent::getInfoBoxData($uniAdapterName, $language);
		$infoBoxData->link = 'http://www.mallpay.cz';
		$infoBoxData->image = 'mallpay_bannery-modre.png';
		return $infoBoxData;
	}

}

class MallPay {
	private $apiUsername;
	private $apiPassword;
	private $apiUrl;
	private $token;
	private $headers;
	private $logger;

	function __construct($apiUsername, $apiPassword, $apiUrl, $logger) {
		$this->apiUsername = $apiUsername;
		$this->apiPassword = $apiPassword;
		$this->apiUrl = $apiUrl;
		$this->headers = array('Content-Type' => 'application/json; charset=utf-8');
		$this->logger = $logger;
	}

	function login() {
		$data = array('username' => $this->apiUsername, 'password' => $this->apiPassword);
		$response = $this->callApi('POST', '/authentication/v1/partner', $data);
		$this->token = $response->accessToken;
		$this->headers["Authorization"] = "Bearer " . $this->token;
	}

	function callApi($method, $path, $data = null) {
		$body = Unirest\Request\Body::json($data);
		$response = Unirest\Request::send($method, $this->apiUrl . $path, $body, $this->headers);
		$this->logger->writeLog("MallPayApiCall: " . $method . " " . $this->apiUrl . $path . ' ' . $body . " --->>> " . $response->code. " \n" . $response->raw_body);
		if ($response->code >= 200 && $response->code <= 299) {
			return $response->body;
		} else {
			throw new MallPayException($response);
		}
	}
}


class MallPayException extends Exception {
	var $response;

	function __construct($response) {
		$this->response = $response;
		$this->code = $response->code;
		$this->message = var_export($response->body->errors, true);
	}

}