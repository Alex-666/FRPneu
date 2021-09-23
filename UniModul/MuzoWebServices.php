<?php
// Autor Miroslav Novak www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// $Id: MuzoWebServices.php,v 1.6 2011/01/11 06:52:08 mira Exp $

require_once('muzo.php');


class MuzoWebServices {

	var $MerchantNumber, $MuzoPublicKeyFile, $PrivateKeyFile, $PrivateKeyPass, $mws, $provider, $logger;

	function __construct(
		$wsdl,                    // soubor popisu webservice
		$serviceUrl,              // url webservice
		$provider,         	      // provider, typicky kod banky
		$merchantNumber,          // cislo obchodnika
		$muzoPublicKeyFile,       // soubor s verejnym klicem Muzo
		$privateKeyFile,          // soubor s privatnim klicem
		$privateKeyPass,          // heslo privatniho klice
		$logger)
	{
		$this->logger = $logger;
		$this->MerchantNumber = $merchantNumber;
		$this->MuzoPublicKeyFile = $muzoPublicKeyFile;
		$this->PrivateKeyFile = $privateKeyFile;
		$this->PrivateKeyPass = $privateKeyPass;
		$this->provider = $provider;
		$this->mws = new SoapClient($wsdl, ['cache_wsdl'=>WSDL_CACHE_NONE, 'trace'=>true, 'exceptions'=>true, 'location'=>$serviceUrl  ]);
	}

	// nasleduji jednotlive funkce poskytovane pres Muzo Webservices
	// navratova hodnota urcuje zda byl podpis muzo ok (true, false)
	// posledni argument $res je pole obsahujici navratove hodnoty funkce WebServices
	// popis jednotlivych funkci je uveden v souboru "PayMuzo - Web Services.pdf" dokumentace dodane od Muzo
	// chybu pri komunikaci se WebServices lze zjistit volanim funkce GetError, ktera pri chybe vrati neprazdny retezec s popisem chyby

	function getPaymentStatus($orderNumber) {
		return $this->callServiceSig('getPaymentStatus','paymentStatus',array('paymentNumber'=>$orderNumber));
	}

	function getPaymentDetail($orderNumber) {
		return $this->callServiceSig('getPaymentDetail','paymentDetail',array('paymentNumber'=>$orderNumber));
	}

	function processRecurringPayment(
			$paymentNumber, //orderNumberarray
			$masterPaymentNumber,
			$orderNumber, //merOrdNumarray
			$referenceNumber, //md
			$amount,
			$currencyCode,
			$captureFlag,
			&$res

		) {
			return $this->callServiceSig('processRecurringPayment', 'recurringPayment',array(
				'paymentNumber'=>$paymentNumber,
				'masterPaymentNumber'=>$masterPaymentNumber,
				'orderNumber'=>$orderNumber,
				'referenceNumber'=>$referenceNumber,
				'amount'=>$amount,
				'currencyCode'=>$currencyCode,
				'captureFlag'=>$captureFlag
			));
	}


	function processUsageBasedSubscriptionPayment(
			$paymentNumber, //orderNumberarray
			$masterPaymentNumber,
			$orderNumber, //merOrdNumarray
			$referenceNumber, //md
			$amount,
			$currencyCode,
			$captureFlag,
			&$res

		) {
			return $this->callServiceSig('processUsageBasedSubscriptionPayment', 'usageBasedSubscriptionPayment',array(
				'paymentNumber'=>$paymentNumber,
				'masterPaymentNumber'=>$masterPaymentNumber,
				'orderNumber'=>$orderNumber,
				'referenceNumber'=>$referenceNumber,
				'amount'=>$amount,
				'currencyCode'=>$currencyCode,
				'captureFlag'=>$captureFlag
			));
	}

	function callServiceSig($funcName, $parNameBase, $params) {
		$messageId = uniqid('xxxxx');
		$params = array('messageId'=>$messageId, 'provider'=>$this->provider, 'merchantNumber'=>$this->MerchantNumber) + $params;
		$sigstr = implode('|', $params);
		$sig = muzo_Sign($sigstr, $this->PrivateKeyFile, $this->PrivateKeyPass);
		$params['signature'] = base64_decode($sig);
		$params = array($parNameBase.'Request' => $params);
		try {
			$paramsLog = $params; unset($paramsLog[$parNameBase.'Request']['signature']);
			$this->logger->writeLogNoNewLines("GPWP SOAP request: " . $funcName . ' ' . print_r($paramsLog, true));
			$res = call_user_func([$this->mws, $funcName], $params);
		} catch(Exception $e) {
			$eLog = unserialize(serialize($e)); unset($eLog->detail->paymentServiceException->signature); unset($eLog->detail->serviceException->signature);
			$this->logger->writeLogNoNewLines("GPWP SOAP exception: " . print_r($eLog->detail, true));
			throw $e;
		}
		$eRes = unserialize(serialize($res)); unset($eRes->{$parNameBase.'Response'}->signature);
		$this->logger->writeLogNoNewLines("GPWP SOAP response: " . $funcName . ' ' . print_r($eRes, true));
		$res = $res->{$parNameBase.'Response'};
		$sigr = $res->signature;
		$resar = get_object_vars($res);
		unset($resar['signature']);
		$sigrstr = implode('|', $resar);
		if (!($messageId && muzo_Verify($sigrstr, base64_encode($sigr), $this->MuzoPublicKeyFile))) {
			$this->logger->writeLogNoNewLines("GPWP SOAP response validation error: neplatny podpis nebo messageId");
			throw new Exception("GPWP SOAP response validation error: neplatny podpis nebo messageId");
		}
		return $res;
	}

}

