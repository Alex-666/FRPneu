<?php

class UniOCHelper {
	static function link($controller, $route, $args = '') {
		if (class_exists('Url')) {   // OC 1.5+
			if (VERSION >= '2.3') {
				if ($route=='extension/payment') {
					$route = 'extension/extension';
					$args = $args . ($args != '' ? '&' : '') . 'type=payment';
				}
				if (0 === strpos($route, 'payment/')) {
					$route = 'extension/' . $route;
				}
			}
			return $controller->url->link($route, $args, 'SSL');
		} else {	// OC 1.4-
			$url = HTTPS_SERVER . 'index.php?route='.$route;
			if ($args) {
				$url .= '&' . $args;
			}
			return $url;
		}
	}

	static function redirect($controller, $url) {
		if (VERSION < '2.0') {
			$r = new ReflectionMethod('Controller', 'redirect');
			$r->setAccessible(true);
			$r->invoke($controller, $url);
		} else {
			$controller->response->redirect($url);
		}
	}

	static function redirectLink($controller, $route, $args = '') {
		self::redirect($controller, self::link($controller, $route, $args));
	}

	static function getLanguageWoCulture($controller) {
		if (VERSION >= '2.2') {
			$lang = $controller->language->get('code');
		} else {
			$lang = substr($controller->config->get('config_language'), 0, 2);  // jen kod jazyka, z en-gb vytahne jen en
		}
		if ($lang == 'cz') return 'cs';  // oprava caste chyby v nastaveni jazyka eshopu
		else return $lang;
	}

}
