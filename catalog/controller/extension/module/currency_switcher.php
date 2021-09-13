<?php
class ControllerExtensionModuleCurrencySwitcher extends Controller {
    // checks if there is 'currency_code' in $_GET vars
    // and if there is - sets it to session
    // then removes it from URI and redirects to the new URI
    public function before_view($setting = null) {
        $code = (isset($this->request->get['currency_code']) ?
                          trim($this->request->get['currency_code']) : '');
        if ($code) {
            $this->load->model('setting/setting');
            if ($this->config->get('module_currency_switcher_status')) {
                $this->load->model('localisation/currency');
                $currencies = $this->model_localisation_currency->getCurrencies();
                if (array_key_exists($code, $currencies)) {
                    $this->session->data['currency'] = $code;
                    $this->request->cookie['currency'] = $code;

                    $requestUri = parse_url($_SERVER['REQUEST_URI']);
                    $query = isset($requestUri['query']) ? $requestUri['query'] : '';

                    $queryItems = split('&', $query);
                    $currencyItem = 'currency_code=' . $code;
                    $newQuery = array();
                    foreach($queryItems as $v) {
                        if ($v != $currencyItem) {
                            array_push($newQuery, $v);
                        }
                    }
                    $newQueryString = '';
                    if ($newQuery && sizeof($newQuery)) {
                        $newQueryString = implode('&', $newQuery);
                    }
                    $path = isset($requestUri['path']) ? $requestUri['path'] : '';
                    $newPage = "https://" . $_SERVER['HTTP_HOST'] . $path . "?" . $newQueryString;
                    $this->response->redirect($newPage);
                }
            }
        }
    }

}
