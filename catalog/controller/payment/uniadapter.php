<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#


require_once(DIR_APPLICATION."../UniModul/UniModul.php");
require_once(DIR_APPLICATION."../UniModul/UniOCHelper.php");

class ControllerPaymentUniAdapter extends Controller {

	protected $uniModul;
	protected $lowCaseBinderName;

	public function __construct($registry, $uniModulName = null, $subMethod='') {
		BeginUniErr();
		parent::__construct($registry);
		$this->lowCaseBinderName = strtolower($uniModulName).'binder'.strtolower($subMethod);
		$uniFact = new UniModulFactory();
		$configInfo = $uniFact->getConfigInfo($uniModulName, null, $subMethod);
		$this->configSetting = $this->getConfigData($configInfo, $uniModulName);
		$this->uniModul = $uniFact->createUniModul($uniModulName, $this->configSetting, $subMethod);
		EndUniErr();
	}

	function getConfigData($configInfo, $uniModulName) {
		$configData = array();
		foreach ($configInfo->configFields as $configField) {
			$baseBinder = strtolower($uniModulName).'binder';  // bez submetod
			$configData[$configField->name] = $this->config->get((VERSION >=3 ? 'payment_' : '') . $baseBinder.'_'.$configField->name);
		}
		if (isset($GLOBALS['UniModulExtraConfig'][$uniModulName][$_SERVER['HTTP_HOST']])) {
			$configData = $GLOBALS['UniModulExtraConfig'][$uniModulName][$_SERVER['HTTP_HOST']] + $configData;
		}

		$uniModulConfig = new UniModulConfig();
		$uniModulConfig->databaseConnection = new OpenCartDbConnection($this->db);
		$uniModulConfig->uniModulDirUrl = HTTPS_SERVER . "UniModul/";
		$uniModulConfig->funcGetCallbackUrl = array($this, 'getCallbackUrl');
		$uniModulConfig->funcProcessReplyStatus = array($this, 'funcProcessReplyStatus');

		//DB_PREFIX ..
		//if (DB_DRIVER != 'mysql') user_error('Eshop pouziva jinou nez mysql databazi');
		$uniModulConfig->adapterName = 'OpenCart';
		$cfgs = create_initialize_object('ConfigSetting', array('configData'=>$configData, 'uniModulConfig'=>$uniModulConfig));
		return $cfgs;
	}


	function getCallbackUrl($callbackName, $arguments) {
		return UniOCHelper::link($this, 'payment/'.$this->lowCaseBinderName.'/callbackcb', http_build_query(array('_callbackName' => $callbackName) + $arguments));
	}


	public function index() {
		BeginUniErr();
		$this->language->load('payment/uniadapter');
    	//$data['text_brand'] = $this->language->get('text_brand');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');

		$data['action'] = UniOCHelper::link($this, 'payment/'.$this->lowCaseBinderName.'/postback');
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$data['back'] = UniOCHelper::link($this, 'checkout/payment');
		} else {
			$data['back'] = UniOCHelper::link($this, 'checkout/guest_step_2');
		}

		if (isset($this->uniModul)) { //vopruz kvuli adapteru
			$orderToPayInfo = $this->getOrderToPayInfo();
			$prePayGWInfo = $this->uniModul->queryPrePayGWInfo($orderToPayInfo);
			$data['selectCsPayBrand'] = $prePayGWInfo->selectCsPayBrand;
			$data['selectCsPayBrandTitle'] = $prePayGWInfo->selectCsPayBrandTitle;
			$data['forexMessage'] = $prePayGWInfo->forexMessage;
			$data['minilogo'] = "<span style=\"background:url('https://www.platiti.cz/muzo/minilogo.png')\"></span>";
		}

		//$this->load->model('checkout/order');   //uz se vola v getOrderToPayInfo

		$this->id = 'payment';


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/uniadapter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/uniadapter.tpl';
		} else if (VERSION >= '2.2'){
			$this->template = 'payment/uniadapter.tpl';
		} else {
			$this->template = 'default/template/payment/uniadapter.tpl';
		}


		if (VERSION < '2.0') {
			$this->data = $data;
			$this->render();
			EndUniErr();
		} else {
			BeginUniErr(E_UNIERR_DEFAULT & ~E_USER_DEPRECATED);
			return EndUniErr(EndUniErr($this->load->view($this->template, $data)));
		}
	}

	public function getPrePayGWInfoForModel() {
		//var_dump($this->session->data); wwww();

		//session->data[order_id] jeste v tuto chvily neexistuje, takze orderInfo z controlleru nelze pouzit
		//$orderToPayInfo = $this->getOrderToPayInfo();

		$total_baseCur = $this->cart->getTotal();
		$total_actcur = $this->currency->format($total_baseCur, $this->session->data['currency'], '', false);   // prevede do aktualni meny

		$orderToPayInfo = new OrderToPayInfo();
		$orderToPayInfo->subMethod = $this->uniModul->subMethod;
		$orderToPayInfo->language = UniOCHelper::getLanguageWoCulture($this);
		$orderToPayInfo->currency = $this->session->data['currency'];
		$orderToPayInfo->currencyRates = $this->getCurrencyRates();
		$orderToPayInfo->amount = $total_actcur;

		$customerData = new CustomerData();

		if (isset($this->session->data['payment_address'])) {	// OC 2.0+
			$payment_address = $this->session->data['payment_address'];
		} else if (isset($this->session->data['guest']['payment'])) {	// OC 1.5  guest
			$payment_address = $this->session->data['guest']['payment'];
		} else if (isset($this->session->data['payment_address_id'])) {
			$this->load->model('account/address');		// OC 1.5  registrovany
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} else {
			$payment_address = null;
		}

		if ($payment_address != null) {
			$customerData->first_name = isset($payment_address['firstname']) ? $payment_address['firstname'] : null;
			$customerData->last_name = isset($payment_address['lastname']) ? $payment_address['lastname'] : null;
			//$customerData->email = $order_info['email'];  // zatim neni pro prepayinfo potreba (pro Agmo staci jen country)
			$customerData->street = (isset($payment_address['address_1']) ? $payment_address['address_1'] : null) .' ' . (isset($payment_address['address_2']) ? $payment_address['address_2'] : null);
			$customerData->city = isset($payment_address['city']) ? $payment_address['city'] : null;
			$customerData->post_code = isset($payment_address['postcode']) ? $payment_address['postcode'] : null;
			$customerData->country = isset($payment_address['iso_code_2']) ? $payment_address['iso_code_2'] : null;
			//$customerData->phone = $order_info['telephone'];
			//$customerData->identifier = $order_info['customer_id'] != 0 ? $order_info['customer_id'] : null;
		}
		$orderToPayInfo->customerData = $customerData;

		$prePayGWInfo = $this->uniModul->queryPrePayGWInfo($orderToPayInfo);
		return $prePayGWInfo;
	}

	protected function getCurrencyRates() {
		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		$currencyRates = array();
		foreach ($currencies as $code=>$cur) {
			$currencyRates[$code] = $cur['value'];
		}
		return $currencyRates;
	}

	protected function getOrderToPayInfo() {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (VERSION < '1.5') {
			$currency = $order_info['currency'];
			$value = $order_info['value'];
		} else {
		    If ($order_info['currency_code'] == "EUR") {
                $currency = "CZK";
                $value =  "1.00000000";
            }
		    else {
                $currency = $order_info['currency_code'];
                $value = $order_info['currency_value'];
            }
		}
		$order_total = $this->currency->format($order_info['total'], $currency, $value, FALSE);

		$orderToPayInfo = new OrderToPayInfo();
		$orderToPayInfo->subMethod = $this->uniModul->subMethod;
		$orderToPayInfo->shopOrderNumber = $this->session->data['order_id'];
		$orderToPayInfo->shopPairingInfo = $this->session->data['order_id'];
		$orderToPayInfo->amount = $order_total;
		$orderToPayInfo->currency = $currency;
		if (isset($_REQUEST['brand'])) {
			$orderToPayInfo->ccBrand = $_REQUEST['brand'];
		}


		$customerData = new CustomerData();
		$customerData->first_name = $order_info['firstname'];
		$customerData->last_name = $order_info['lastname'];
		$customerData->email = $order_info['email'];
		$customerData->street = $order_info['payment_address_1'].' '.(isset($order_info['payment_address_2']) ? $order_info['payment_address_2'] : null);
		$customerData->city = $order_info['payment_city'];
		$customerData->post_code = $order_info['payment_postcode'];
		$customerData->country = $order_info['payment_iso_code_2'];
		$customerData->phone = $order_info['telephone'];
		$customerData->identifier = $order_info['customer_id'] != 0 ? $order_info['customer_id'] : null;
		$orderToPayInfo->customerData = $customerData;

		$orderToPayInfo->language = UniOCHelper::getLanguageWoCulture($this);
		$orderToPayInfo->description = $this->config->get('config_name');

		$replyUrl = $replyUrl = UniOCHelper::link($this, 'payment/'.$this->lowCaseBinderName.'/callback');
		$orderToPayInfo->replyUrl = $replyUrl;

		$replyUrl = $replyUrl = UniOCHelper::link($this, 'payment/'.$this->lowCaseBinderName.'/notify');
		$orderToPayInfo->notifyUrl = $replyUrl;

		$orderToPayInfo->uniModulDirUrl = HTTPS_SERVER . 'UniModul/';

		$orderToPayInfo->currencyRates = $this->getCurrencyRates();



		$cartItems = [];
		foreach ($this->cart->getProducts() as $product) {
			$cartItem = new UniCartItem();
			$cartItem->type = UniCartItemType::commodity;
			$cartItem->name = $product['name'];
			$cartItem->quantity = $product['quantity'];

			$taxes = $this->tax->getRates($product['price'], $product['tax_class_id']);
			$rate = 0;
			foreach ($taxes as $tax) {
				if ($tax['type']!='P') continue;
				if (isset($tax['rate'])) {
					$rate += $tax['rate'];
				} else {
					$rate += round(100 * $tax['amount'] / $product['price']);  // pro OC 1.5
				}
			}
			$cartItem->taxRate = $rate;

			$cartItem->unitPrice = $this->currency->format($product['total'] / $product['quantity'] * (1 + $rate/100), $currency, $value, FALSE);
			$cartItem->unitTaxAmount = $this->currency->format($product['total'] / $product['quantity'], $currency, $value, FALSE) * ($rate/100);
			$cartItems[] = $cartItem;
		}

		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			$cartItem = new UniCartItem();
			$cartItem->type = UniCartItemType::delivery;
			$cartItem->name = 'shipping / doprava';
			$cartItem->quantity = 1;
			$taxes = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);
			$rate = 0;
			foreach ($taxes as $tax) {
				if ($tax['type']!='P') continue;
				if (isset($tax['rate'])) {
					$rate += $tax['rate'];
				} else {
					$rate += round(100 * $tax['amount'] / $product['price']);  // pro OC 1.5
				}
			}
			$cartItem->taxRate = $rate;
			$cartItem->unitPrice = $this->currency->format($this->session->data['shipping_method']['cost'] * (1 + $rate/100), $currency, $value, FALSE);
			$cartItem->unitTaxAmount = $this->currency->format($this->session->data['shipping_method']['cost'], $currency, $value, FALSE) * ($rate/100);
			$cartItems[] = $cartItem;
		}

		$rozdeleni = array();
		$celkem = 0;
		foreach ($cartItems as $ci) {
			$act = $ci->unitPrice * $ci->quantity;
			$cum = isset($rozdeleni[$ci->taxRate]) ? $rozdeleni[$ci->taxRate] : 0;
			$rozdeleni[($ci->taxRate)] = $cum + $act;
			$celkem += $ci->unitPrice * $ci->quantity;
		}

		// rovnomerne rozpocteni slevy na pasma
		$discountAmount = $orderToPayInfo->amount - $celkem;
		if ($discountAmount != 0) {
			foreach($rozdeleni as $tax=>$amt) {
				$cartItem = new UniCartItem();
				$cartItem->type = UniCartItemType::discount;
				$cartItem->name = 'discount / sleva';
				$cartItem->quantity = 1;
				$cartItem->unitPrice = $discountAmount * $amt / $celkem;
				$cartItem->taxRate = $tax;
				$cartItem->unitTaxAmount = $cartItem->unitPrice * ($tax / (100 + $tax));
				$cartItems[] = $cartItem;
			}
		}

		$orderToPayInfo->cartItems = $cartItems;

		return $orderToPayInfo;
	}

	public function postback() {
		BeginUniErr();
		$orderToPayInfo = $this->getOrderToPayInfo();
		$redirectAction = $this->uniModul->gatewayOrderRedirectAction($orderToPayInfo);
		if ($redirectAction->orderReplyStatus != null) {   // okamzita odpoved muze byt i zaroven s redirektem - napr. Cetelem
			$frontend_redir = $redirectAction->redirectUrl==null && $redirectAction->redirectForm==null;
			$this->processReply($redirectAction->orderReplyStatus, $frontend_redir);
		}
		if ($redirectAction->inlineForm) {
			$this->showInFrontEnd($redirectAction->inlineForm);
		} else if ($redirectAction->redirectUrl != null) {
			ResetUniErr();
			UniOCHelper::redirect($this, $redirectAction->redirectUrl);
		} else if ($redirectAction->redirectForm != null) {
			$this->uniModul->formRedirect($redirectAction->redirectForm);
		}
		EndUniErr();
	}

	public function callback() {
		BeginUniErr();
		$language = UniOCHelper::getLanguageWoCulture($this);
		$orderReplyStatus = $this->uniModul->gatewayReceiveReply($language);
		$this->processReply($orderReplyStatus, true);
		EndUniErr();
	}

	public function notify() {
		BeginUniErr();
		$orderReplyStatus = $this->uniModul->gatewayReceiveNotification();
		$this->processReply($orderReplyStatus, false);
		EndUniErr();
	}

	// skutecny univerzalni callback
	public function callbackcb() {
		BeginUniErr();
		$html = $this->uniModul->processCallbackRequest($_GET['_callbackName'], $_REQUEST);
		if ($html != '') {
			$this->showInFrontEnd($html);
		}
		EndUniErr();
	}


	public function installmentembedhtml($product_info) {
		BeginUniErr();
		if (!$this->config->get((VERSION >=3 ? 'payment_' : '') . $this->lowCaseBinderName.'_status')) return EndUniErr(null);  // geozonu zatim neresim

		if (!isset($product_info['price'])) {  // fix pro OC 2.1
			$product_info = $product_info[0];
		}

		$price = isset($product_info['special']) ? $product_info['special'] : $product_info['price'];
		$product_price = $this->currency->format($this->tax->calculate($price, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
		$embedHtml = $this->uniModul->ProductGetInstallmentEmbedHtml(HTTPS_SERVER, $this->session->data['currency'], $product_price);
		if ($embedHtml != null) {
			$GLOBALS['installmentembedhtml'][] = $embedHtml . "<br/>";
		}
		EndUniErr();
	}


	public function processReply($orderReplyStatus, $frontend_redir) {
		$cfg = $this->configSetting->configData;
		switch ($orderReplyStatus->orderStatus) {
			case OrderStatus::$successful:
				$newState = $cfg['orderStatusSuccessfull'];
				$guiOk = true;
				break;
			case OrderStatus::$pending:
				$newState = isset($cfg['orderStatusPending']) ? $cfg['orderStatusPending'] : null;
				$guiOk = true;
				break;
			case OrderStatus::$failedFinal:
			case OrderStatus::$failedRetriable;
				$newState = isset($cfg['orderStatusFailed']) ? $cfg['orderStatusFailed'] : null;
				$guiOk = false;
				break;
			case OrderStatus::$invalidReply;
				$newState = null;
				$guiOk = false;
			default:
		}

		BeginSynchronized();

		$order_id = $orderReplyStatus->shopPairingInfo;
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		if ($orderReplyStatus->orderStatus != OrderStatus::$invalidReply) {

			$comment = $order_info['comment'];
			if ($comment != '') {
				$comment .= "\n\n";
			}
			//$comment .= 'UniAdapter gwOrdnum ='.$orderReplyStatus->gwOrderNumber;
			$language = UniOCHelper::getLanguageWoCulture($this);  // je to potreba i nize, notifikace nevadi
			$comment .= $this->uniModul->dictionary->get('orderDetailGwOrdNum', $language).': '.$orderReplyStatus->gwOrderNumber;
			if ($orderReplyStatus->forexNote !== null) {
				$comment .= "<br/>".$this->uniModul->dictionary->get('forexNoteLabel', $language). ': ' . $orderReplyStatus->forexNote;
			}

			$oldState = $order_info['order_status_id'];
			if ($oldState == 0) {
				if ($orderReplyStatus->orderStatus != OrderStatus::$failedFinal && $orderReplyStatus->orderStatus != OrderStatus::$failedRetriable) {
					if (VERSION < '2.0') {
						$this->model_checkout_order->confirm($order_id, $newState, $comment, TRUE);
					} else {
						BeginUniErr(E_UNIERR_DEFAULT & ~E_USER_DEPRECATED);
						$this->model_checkout_order->addOrderHistory($order_id, $newState, $comment, TRUE);
						EndUniErr();
					}
					$this->cart->clear(); // mazeme kosik hlavne pro immediate status, pro standardni navrat to tak jako tak vola sablona checkout/success, pro notifikacni pripad tato funkce potrebuje znat session $this->session->getId(), ktere aktualne nemame
					$this->uniModul->logger->writeLog("ADAPTER: Creating shop order number ".$order_id.", new order state ".$newState);
				} else {
					$this->uniModul->logger->writeLog("ADAPTER: Won't create new failed order " . $order_id);
				}
			} else {
				$transientStates = array();
				if (isset($cfg['orderStatusPending'])) $transientStates[] = $cfg['orderStatusPending'];
				if (isset($cfg['orderStatusFailed'])) $transientStates[] = $cfg['orderStatusFailed'];
				if ($oldState != $newState  && in_array($oldState, $transientStates)) {
					if (VERSION < '2.0') {
						$this->model_checkout_order->update($order_id, $newState, $comment, TRUE);
					} else {
						$this->model_checkout_order->addOrderHistory($order_id, $newState, $comment, TRUE);
					}
					$this->uniModul->logger->writeLog("ADAPTER: Updating shop order number ".$order_id.", new order state ".$newState." old order state " . $oldState);
				} else {
					$this->uniModul->logger->writeLog("ADAPTER: Refusing update shop order number ".$order_id.", new order state ".$newState." old order state " . $oldState);
				}

			}
		} else {
			$this->uniModul->logger->writeLog("ADAPTER: Ignoring invalid reply");
		}

		EndSynchronized();

		if ($frontend_redir) {
			if ($guiOk) {
				if  (!empty($orderReplyStatus->successHtml)) {
					$this->language->load('checkout/success');
					$this->document->setTitle($this->language->get('heading_title'));
					$suh = "<h1>".$this->language->get('heading_title')."</h1>";
					$suh .= $orderReplyStatus->successHtml;
					$continue = UniOCHelper::link($this, 'common/home');
					$button_continue = $this->language->get('button_continue');
					$suh .= <<< EOT
	<div class="buttons">
		<div class="right"><a href="$continue" class="button"><span>$button_continue</span></a></div>
	</div>
EOT;
					$this->showInFrontEnd($suh);
				} else {
					ResetUniErr();
					UniOCHelper::redirectLink($this, 'checkout/success');
				}
			} else {
				$this->language->load('payment/uniadapter');
				$errMsg = $this->language->get('message_failure');
				if ($orderReplyStatus->resultText != null) {
					$errMsg .= '<br/>'.$orderReplyStatus->resultText;
				}

				if (VERSION < '1.5') {
					ResetUniErr();
					$this->session->data['error'] = $errMsg;
					UniOCHelper::redirectLink($this, 'checkout/payment');

				} else if (VERSION >= '1.5.3' && VERSION < '1.5.5') {
					$this->showFailurePage($errMsg);
				} else {
					$this->session->data['error'] = $errMsg;
					ResetUniErr();
					UniOCHelper::redirectLink($this, 'checkout/cart');    // zobrazuje error hlasku v 1.5.1 ale ne v 1.5.3

				}

			}
		}

	}

	public function funcProcessReplyStatus($orderReplyStatus) {
		$this->processReply($orderReplyStatus, false);
	}

	private function showFailurePage($errMsg) {

			$data['error_warning'] = $errMsg;
			$data['continue_text'] = $this->language->get('button_continue');
			$data['continue_url'] = UniOCHelper::link($this, 'checkout/checkout');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/uniadapter_failure.tpl')) {
				$template = $this->config->get('config_template') . '/template/payment/uniadapter_failure.tpl';
			} else if (VERSION >= '2.3'){
				$template = 'payment/uniadapter_failure.tpl';
			} else {
				$template = 'default/template/payment/uniadapter_failure.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			if (VERSION < '2.0') {
				$this->data = $data;
				$this->template = $template;
				$this->response->setOutput($this->render());
			} else {
				BeginUniErr(E_UNIERR_DEFAULT & ~E_USER_DEPRECATED);
				return EndUniErr($this->load->view($template, $data));
			}
	}


	private function showInFrontEnd($html) {

			$data['html_content'] = $html;
			$data['continue_text'] = $this->language->get('button_continue');
			$data['continue_url'] = UniOCHelper::link($this, 'checkout/checkout');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/uniadapter_infrontend.tpl')) {
				$template = $this->config->get('config_template') . '/template/payment/uniadapter_infrontend.tpl';
			} else if (VERSION >= '2.3'){
				$template = 'payment/uniadapter_infrontend.tpl';
			} else {
				$template = 'default/template/payment/uniadapter_infrontend.tpl';
			}

			if (VERSION < '2.0') {
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
				$this->data = $data;
				$this->template = $template;
				$this->response->setOutput($this->render());
			} else {
				BeginUniErr(E_UNIERR_DEFAULT & ~E_USER_DEPRECATED);
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				$outhmtl = $this->load->view($template, $data);
				if (VERSION >= '2.0') {
					$this->response->setOutput($outhmtl);
				}
				EndUniErr();
			}
	}


}


class OpenCartDbConnection implements IDatabaseConnection {

	protected $db; //* OpenCart's DB class */

	public function __construct($db /* OpenCart's DB class */) {
		$GLOBALS['DatabaseConnection_toSql_object'] = $this;
		$this->db = $db;
	}


	public function sqlQuery($sql) {
		$res = $this->db->query($sql);  // samo vyhodi exception pri chybe, kterou pak zalogujeme v ramci UniErru
		if ($res === true) {
			return null;	// nebyl select
		} else {
			return $res->rows;
		}
	}

	public function sqlExecute($sql) {
		$this->sqlQuery($sql);
	}

	function getInsertId() {
		return $this->db->getLastId();
	}

	function toSql($text) {
		if (is_null($text)) {
			return "null";
		} else {
			return "'".$this->db->escape($text)."'";
		}
	}

}
