<?php
class ControllerExtensionModuleHeurekaVerifiedSk extends Controller {

    public function verify(&$route, &$args) {
        if (isset($args[0])) {
            $order_id = $args[0];
        } else {
            $order_id = 0;
        }

        if (isset($args[1])) {
            $order_status_id = $args[1];
        } else {
            $order_status_id = 0;
        }
        $order_info = $this->model_checkout_order->getOrder($order_id);

        if ($order_info && !$order_info['order_status_id'] && $order_status_id) {
            $this->load->model('setting/setting');
            if ($this->config->get('module_heureka_verified_sk_status')
                && $this->config->get('module_heureka_verified_sk_secret')) {
                require_once(DIR_SYSTEM . 'library/HeurekaOvereno.php');

                if ($order_info['shipping_country'] == 'Slovenská republika') {
                    $heureka = new HeurekaOvereno($this->config->get('module_heureka_verified_sk_secret'), HeurekaOvereno::LANGUAGE_SK);
                }
            }

            if (isset($heureka)) {

                // e-mail zakaznika
                $heureka->setEmail($order_info['email']);

                // prida produkty: nazev(produkt[]) + id(itemId[])
                $order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);
                foreach ($order_products as $product) {
                    $heureka->addProduct(trim($product['name']));
                    $heureka->addProductItemId($product['product_id']);
                }

                // Číslo objednávky musí být céle kladné číslo v rozsahu BigInteger (0 - 18446744073709551615)
                $heureka->addOrderId($order_info['order_id']);

                $heureka->send();
            }
        }
    }
}
