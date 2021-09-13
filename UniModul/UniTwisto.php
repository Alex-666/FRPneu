<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#

require_once __DIR__ . '/UniModul.php';
require_once __DIR__ . '/UniTwistoForm.php';
require_once __DIR__ . '/vendor/autoload.php';


class UniTwistoConfig {

	public $twistoPublicKey;
	public $twistoSecretKey;
	public $twistoFeeCustomer;
	public $twistoVat;
	public $isTest;

}

class UniTwisto extends UniModul {

	public $twisto;

	/**
	 * UniTwisto constructor.
	 *
	 * @param $configSetting
	 * @param $subMethod
	 * @param string $name
	 */
	public function __construct( $configSetting, $subMethod, $name = "Twisto" ) {
		parent::__construct( $name, $configSetting, $subMethod );
		$this->config = new UniTwistoConfig();
		$this->twisto = new Twisto\Twisto();

		$this->setConfigFromData( $configSetting );
	}

	/**
	 * @param $configSetting
	 */
	public function setConfigFromData( $configSetting ) {

		if ( $configSetting != null && $configSetting->configData != null ) {
			$configData = $configSetting->configData;
			$this->twisto->setPublicKey( $configData['twistoPublicKey'] );
			$this->twisto->setSecretKey( $configData['twistoSecretKey'] );
			$this->config->twistoPublicKey = $configData['twistoPublicKey'];
		}
	}

	/**
	 * @param string $language
	 *
	 * @return ConfigInfo
	 */
	public function getConfigInfo( $language = 'en' ) {

		$dictionary = $this->dictionary;
		$dictionary->setDefaultLanguage( $language );

		$configInfo = new ConfigInfo();

		$configFields   = array();
		$configFields[] = create_initialize_object( 'ConfigField', array(
			'name'  => 'twistoPublicKey',
			'label' => $dictionary->get( 'twistoPublicKey' ),
			'type'  => ConfigFieldType::$text
		) );
		$configFields[] = create_initialize_object( 'ConfigField', array(
			'name'  => 'twistoSecretKey',
			'label' => $dictionary->get( 'twistoSecretKey' ),
			'type'  => ConfigFieldType::$text
		) );

		$configField        = new ConfigField();
		$configField->name  = 'orderStatusSuccessfull';
		$configField->label = $dictionary->get( 'orderStatusSuccessfull' );
		$configField->type  = ConfigFieldType::$orderStatus;
		$configFields[]     = $configField;

		$configField        = new ConfigField();
		$configField->name  = 'orderStatusPending';
		$configField->label = $dictionary->get( 'orderStatusPending' );
		$configField->type  = ConfigFieldType::$orderStatus;
		$configFields[]     = $configField;

		$configField        = new ConfigField();
		$configField->name  = 'orderStatusFailed';
		$configField->label = $dictionary->get( 'orderStatusFailed' );
		$configField->type  = ConfigFieldType::$orderStatus;
		$configFields[]     = $configField;

		$configInfo->configFields = $configFields;

		return $configInfo;
	}

	/**
	 * @param $orderToPayInfo
	 *
	 * @return PrePayGWInfo
	 */
	public function queryPrePayGWInfo( $orderToPayInfo ) {

		$prePayGWInfo                    = new PrePayGWInfo();
		$prePayGWInfo->paymentMethodName = $this->dictionary->get( 'payment_method_name', $orderToPayInfo->language );
		$prePayGWInfo->isPossible        = true;
		$prePayGWInfo->forexMessage      = null;

		return $prePayGWInfo;
	}


	/**
	 * @param $orderToPayInfo
	 *
	 * @return RedirectAction
	 */
	public function gatewayOrderRedirectAction( $orderToPayInfo ) {

		$transactionPK              = $this->writeOrderToDb( $orderToPayInfo->shopOrderNumber, $orderToPayInfo->shopPairingInfo, null, null, null, $orderToPayInfo->uniAdapterData, $orderToPayInfo );
		$payload                    = $this->_composePayload( $orderToPayInfo );
		$redirectAction             = new RedirectAction();

		$setBackToEshopUrl = ($orderToPayInfo->replyUrl . (strpos($orderToPayInfo->replyUrl, '?')===false ? '?' : '&'));
		$redirectAction->redirectForm = UniTwistoForm::CreateForm( $this->config->twistoPublicKey, $payload, $setBackToEshopUrl, $orderToPayInfo->uniModulDirUrl );
		$redirectAction->inlineForm = UniTwistoForm::CreateForm( $this->config->twistoPublicKey, $payload, $setBackToEshopUrl, $orderToPayInfo->uniModulDirUrl );

		if ( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}
		$_SESSION['UniTwisto_backToEshop_shopPairingInfo'] = $orderToPayInfo->shopPairingInfo;

		return $redirectAction;

	}


	/**
	 * @param string $language
	 *
	 * @return OrderReplyStatus
	 * @throws Exception
	 */
	public function gatewayReceiveReply( $language = 'en' ) {

		$orderReplyStatus   = new OrderReplyStatus();
		$orderStatus        = OrderStatus::$pending;
		$gwOrderNumberReply = null;

		if ( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}

		$shopPairingInfo   = $_SESSION['UniTwisto_backToEshop_shopPairingInfo'];
		$transactionRecord = $this->getOrderTransactionRecordFromDbLast( null, $shopPairingInfo );

		if ( $transactionRecord == null ) {
			$orderStatus = OrderStatus::$invalidReply;
		}

		$transaction_id = $_GET['transaction_id'];

		if ( $transaction_id == 'cancel' ) {
			$orderStatus = OrderStatus::$failedFinal;
		} else {
			$gwOrderNumberReply = $transaction_id;
			try {
				$invoice = Twisto\Invoice::create( $this->twisto, $transaction_id );
			} catch ( Twisto\Error $e ) {
				//muze nastat situace ze faktura pro tuto platbu jiz existuje.
				if ( $transactionRecord->orderStatus == 1 && $transactionRecord->gwOrderNumber == $e->data->transaction_id[1] ) {
					ResetUniErr();
					die( "Stav objednávky s platbou Twisto nezměněn" );
				} else {
					$orderStatus = OrderStatus::$failedFinal;
				}
			}

			$transactionData  = $transactionRecord->uniModulData;
			$orderReplyStatus = new OrderReplyStatus();

			if ( isset( $invoice->invoice_id ) ) {
				$this->logger->writeLog( "new TwistoInvoice" . print_r( $invoice, true ) );
				$this->validateOrder( $invoice, $transactionData );
				$orderStatus        = OrderStatus::$successful;
				$gwOrderNumberReply = $invoice->invoice_id;
			}
		}

		$orderReplyStatus->resultText      = null;
		$orderReplyStatus->orderStatus     = $orderStatus;
		$orderReplyStatus->gwOrderNumber   = $gwOrderNumberReply;
		$orderReplyStatus->shopOrderNumber = $transactionRecord->shopOrderNumber;
		$orderReplyStatus->shopPairingInfo = $transactionRecord->shopPairingInfo;
		$orderReplyStatus->forexNote       = $transactionRecord->forexNote;
		$orderReplyStatus->uniAdapterData  = $transactionRecord->uniAdapterData;

		$this->updateOrderReplyStatusGwOrdNumInDb( $orderReplyStatus, $transactionRecord->transactionPK );
		$this->logger->writeLog( "orderReplyStatus=" . $orderStatus);

		return $orderReplyStatus;

	}

	/**
	 * @param $invoice
	 * @param $transactionData
	 *
	 * @throws Exception
	 */
	public function validateOrder( $invoice, $transactionData ) {
		if ( $transactionData->amount != $invoice->total_price_vat ) {
			throw new Exception( "Payment Ammount invalid" );
		}
		if ( mb_strtolower($transactionData->customerData->email) != mb_strtolower($invoice->customer_email) ) {
			throw new Exception( "Payment Email invalid" );
		}
	}

	/**
	 * @param $uniAdapterName
	 * @param $language
	 *
	 * @return InfoBoxData
	 */
	public function getInfoBoxData( $uniAdapterName, $language ) {
		$infoBoxData        = parent::getInfoBoxData( $uniAdapterName, $language );
		$infoBoxData->title = $this->dictionary->get( 'infoBoxTitle', $language );
		$infoBoxData->link  = null;
		$infoBoxData->image = '';

		return $infoBoxData;
	}

	/**
	 * @param $orderToPayInfo
	 *
	 * @return mixed
	 */
	private function _composePayload( $orderToPayInfo ) {

		$customer         = new Twisto\Customer( $orderToPayInfo->customerData->email, $orderToPayInfo->customerData->first_name . ' ' . $orderToPayInfo->customerData->last_name, '', '', '' );
		$billing_address  = $this->_convertToTwistoAddress( $orderToPayInfo->customerData );
		$delivery_address = $this->_convertToTwistoAddress( $orderToPayInfo->customerData );

		$order_items[] = new Twisto\Item(
			Twisto\Item::TYPE_PAYMENT,
			'Twisto Faktura – platím až po vyzkoušení',
			'payment',
			1,
			0,// price_vat
			21
		);

		$total_cart = 0;

		$counter = 1;
		foreach ( $orderToPayInfo->cartItems as $cart_item ) {
			switch ( $cart_item->type ) {
				case UniCartItemType::commodity:
					$order_items[] = new Twisto\Item(
						Twisto\Item::TYPE_DEFAULT,
						trim( mb_substr( $cart_item->name, 0, 20, 'utf-8' ) ),
						$counter,
						$cart_item->quantity,
						round( $cart_item->unitPrice * $cart_item->quantity, 2 ),
						$cart_item->taxRate
					);
					break;             // break is used to end the switch statement
				case UniCartItemType::delivery:
					$order_items[] = new Twisto\Item(
						Twisto\Item::TYPE_SHIPMENT,
						trim( mb_substr( $cart_item->name, 0, 20, 'utf-8' ) ),
						'doprava-' . $counter,
						$cart_item->quantity,
						round( $cart_item->unitPrice * $cart_item->quantity, 2 ),
						$cart_item->taxRate
					);
					break;
				case UniCartItemType::discount:
					$order_items[] = new Twisto\Item(
						Twisto\Item::TYPE_DISCOUNT,
						trim( mb_substr( $cart_item->name, 0, 20, 'utf-8' ) ),
						'sleva-' . $counter,
						$cart_item->quantity,
						round( $cart_item->unitPrice * $cart_item->quantity, 2 ),
						$cart_item->taxRate
					);
					break;
				default:
					break;
			}
			$total_cart += round( $cart_item->unitPrice * $cart_item->quantity, 2 );
			$counter ++;
		}

		if( $orderToPayInfo->amount != $total_cart ){
			$rounded = $orderToPayInfo->amount - $total_cart;
			$order_items[] = new Twisto\Item(
				Twisto\Item::TYPE_ROUND,
				'Zaokrouhlení',
				'Zaokrouhlení',
				1,
				number_format((float)$rounded, 2, '.', ''),
				21
			);
		}

		$oldZone = date_default_timezone_get();
		date_default_timezone_set( 'Europe/Prague' );

		$order           = new Twisto\Order(
			new DateTime(),
			$billing_address,
			$delivery_address,
			$orderToPayInfo->amount,
			$order_items
		);
		$previous_orders = [];

		date_default_timezone_set($oldZone);

		$this->logger->writeLog( "new TwistoOrder" . print_r( $order, true ) );
		$this->logger->writeLog( "new TwistoCustommer" . print_r( $customer, true ) );

		return $this->twisto->getCheckPayload( $customer, $order, $previous_orders );
	}

	/**
	 * @param $address
	 *
	 * @return \Twisto\Address
	 */
	private function _convertToTwistoAddress( $address ) {

		//$country = CountryCore::getIsoById( $address->id_country );
		if ( ! empty( $address->phone_mobile ) ) {
			$phone = $address->phone_mobile;
		} else {
			$phone = '';
		}

		return new Twisto\Address(
			$address->first_name . ' ' . $address->last_name,
			$address->street,
			$address->city,
			preg_replace( '/\s+/', '', $address->post_code ),
			$address->country,
			$address->phone
		);
	}
}