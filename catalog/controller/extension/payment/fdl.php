<?php
class ControllerExtensionPaymentFdl extends Controller {

    public function index() {

        require_once('fdl_merchant.php');

        $this->load->model('extension/payment/fdl');

        $order_info = $this->model_extension_payment_fdl->getOrder($this->session->data['order_id']);
        $ip_address = $_SERVER['REMOTE_ADDR'];

        //payment description, visible to user
        $description = "Order " . $this->session->data['order_id'];

        //lang passed to FDL
        $lang = 'en';

        $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        $currency = $this->model_extension_payment_fdl->mapCurrency($order_info['currency_code']);
        $amount = $amount*100;

        //initialize FDL payment
        $this->Merchant = new Merchant($this->config->get('payment_fdl_config_server') . ":8443/ecomm/MerchantHandler", $this->config->get('payment_fdl_config_cert'), $this->config->get('payment_fdl_config_certpass'), 1);
        $resp = $this->Merchant->startDMSAuth($amount, $currency, $ip_address, $description, $lang);

        //if fdl response is successful, save transaction id and redirect user to fdl
        if (substr($resp, 0, 14) == "TRANSACTION_ID") {

            //extract and save payment ID
            $payment_id = substr($resp, 16, 28);
            $this->model_extension_payment_fdl->saveTransId($order_info['order_id'], $payment_id);
            $this->model_extension_payment_fdl->saveStatus($order_info['order_id'], 'started');

            //link to FDL form
            $data['button_confirm'] = $this->language->get('button_confirm');
            $data['url_confirm'] = $this->config->get('payment_fdl_config_server') . "/ecomm/ClientHandler?trans_id=" . urlencode($payment_id);

        } else {
            error_log("FirstData transaction error: " . $resp);
        }

        if (!isset($data)) {
            error_log("FirstData transaction error: " . $resp);
            return '<span style="color: red;">There was error while trying to init payment. Please try again later.</span>';
        }

        return $this->load->view('extension/payment/fdl', $data);

    }

    /**
     * Payment confirmation - marks successful payment in the database
     */
    public function confirm() {
        if (!empty($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'fdl') {

            require_once('fdl_merchant.php');

            $this->load->model('checkout/order');
            $this->load->model('extension/payment/fdl');

            $order_info = $this->model_extension_payment_fdl->getOrder($this->session->data['order_id']);

            if (empty($order_info['transaction'])) {
                error_log("FirstData transaction error: transaction id is null");
                $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));

            }

            $ip_address = $_SERVER['REMOTE_ADDR'];

            //lang passed to FDL
            $lang = 'en';

            //payment description, visible to user
            $description = "Order " . $this->session->data['order_id'];

            $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
            $currency = $this->model_extension_payment_fdl->mapCurrency($order_info['currency_code']);
            $amount = $amount*100;

            // check transaction status
            $this->Merchant = new Merchant($this->config->get('payment_fdl_config_server') . ":8443/ecomm/MerchantHandler", $this->config->get('payment_fdl_config_cert'), $this->config->get('payment_fdl_config_certpass'), 1);

            $resp = $this->Merchant->getTransResult($order_info['transaction']['transaction_id'], $ip_address);

            if (strpos($resp, "RESULT: OK") === false) {

                // unsucessful payment, return to basket view
                error_log("FirstData transaction error: " . $resp);
                $this->model_extension_payment_fdl->saveStatus($order_info['order_id'], $resp);
                $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
            } else {

                // payment reserved
                $this->Merchant = new Merchant($this->config->get('payment_fdl_config_server') . ":8443/ecomm/MerchantHandler", $this->config->get('payment_fdl_config_cert'), $this->config->get('payment_fdl_config_certpass'), 1);
                $resp = $this->Merchant->makeDMSTrans($order_info['transaction']['transaction_id'], $amount, $currency, $ip_address, $description, 'EUR');

                if (!stristr($resp, "RESULT: OK")) {

                    // unsucessful payment,return to basket view
                    error_log("FirstData transaction error: " . $resp);
                    $this->model_extension_payment_fdl->saveStatus($order_info['order_id'], $resp);
                    $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
                } else {
                    // if no error, check result, just in case:
                    $resp = $this->Merchant->getTransResult($order_info['transaction']['transaction_id'], $ip_address);
                    if (!stristr($resp, "RESULT: OK")) {

                        // unsucessful payment,return to basket view
                        error_log("FirstData transaction error: " . $resp);
                        $this->model_extension_payment_fdl->saveStatus($order_info['order_id'], $resp);
                        $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
                    } else {

                        //update order status
                        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_fdl_order_status_id'));
                        $this->model_extension_payment_fdl->saveStatus($order_info['order_id'], 'success');
                        $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
                    }
                }
            }
        }
    }

    /**
     * Close day
     */
    public function close_day() {
        if (!empty($this->request->get['secret']) && $this->request->get['secret'] === $this->config->get('payment_fdl_closeday_token')) {

            require_once('fdl_merchant.php');
            $this->Merchant = new Merchant($this->config->get('payment_fdl_config_server') . ":8443/ecomm/MerchantHandler", $this->config->get('payment_fdl_config_cert'), $this->config->get('payment_fdl_config_certpass'), 1);
            $resp = $this->Merchant->closeDay();

            //admin button pressed
            if(!empty($this->request->get['return'])) {
                $this->session->data['success'] = $this->language->get('Success');
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                echo '0';
            }

            exit;

        } else {
            die("Invalid token!");
        }
    }

}
