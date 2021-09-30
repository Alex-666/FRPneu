<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {

                $data['gtag_event'] = '';

                if (isset($this->session->data['order_id'])) {

            $this->session->data['ga_orderDetails'] = NULL;
            $this->session->data['ga_orderProducts'] = NULL;
            if (isset($this->session->data['order_id'])) {
                $this->load->model('checkout/order');
                $this->session->data['ga_orderDetails'] = $this->model_checkout_order->getOrder($this->session->data['order_id']);
                $this->session->data['ga_orderProducts'] = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
            }
            
                if ($this->config->get('analytics_google_status')) {
                $this->load->model('checkout/order');
                $event_info = $this->model_checkout_order->getGTAG($this->session->data['order_id']);

                if ($event_info) {
                $data['gtag_event'] .= '<script>'."\n";
                    $data['gtag_event'] .= 'gtag(\'event\', \'purchase\', {'."\n";
                    $data['gtag_event'] .= sprintf('"transaction_id": "%s", "affiliation": "%s", "value": %s, "currency": "%s", "shipping": %s,', $event_info['transaction_id'], $event_info['affiliation'], $event_info['value'], $event_info['currency'], $event_info['shipping'])."\n";
                    if (isset($event_info['items']) && is_array($event_info['items'])) {
                        $data['gtag_event'] .= '"items": [ '."\n";
                        foreach ($event_info['items'] as $item) {
                            $data['gtag_event'] .= sprintf('{ "id": "%s", "name": "%s", "category": "%s", "variant": "%s", "quantity": %s, "price": "%s" },', $item['id'], $item['name'], $item['category'], $item['variant'], $item['quantity'], $item['price'])."\n";
                        }
                        $data['gtag_event'] .= ' ]'."\n";
                    }
                    $data['gtag_event'] .= ' });'."\n";
                    $data['gtag_event'] .= '</script>';
                }
                }
                }
                
         if (isset($this->session->data['order_id'])) {
            include_once("ZboziKonverze.php");

            try {
                // inicializace
                $zbozi = new ZboziKonverze(109480, "u6UgbQzLPFcCM6cQMv9GrPrGVzr0X5Qs");

                // testovací režim
                //$zbozi->useSandbox(true);

                // nastavení informací o objednávce
                $zbozi->setOrder(array(
                    "orderId" => $this->session->data['order_id'],
                    "email" => $this->session->data['simple']['customer']['email'],
                    "deliveryType" => $this->session->data['shipping_method']['title'],
                    "deliveryPrice" => preg_replace("/[^0-9]/", '',($this->session->data['shipping_method']['text'])),
                    //"otherCosts" => 20,
                    "paymentType" => $this->session->data['payment_method']['title'],
                ));
                $data['products'] = $this->cart->getProducts();

                foreach ($data['products'] as $product_cart) {
                    $product_price = round($product_cart["price"] + ($product_cart["price"] * 21 / 100));
                    // přidáni zakoupené položky
                    $zbozi->addCartItem(array(
                        "itemId" => $product_cart["product_id"],
                        "productName" => $product_cart["name"],
                        "quantity" => $product_cart["quantity"],
                        "unitPrice" => $product_price,
                    ));
                }
                // odeslání
                $zbozi->send();
            } catch (ZboziKonverzeException $e) {
                // zalogování případné chyby
                error_log("Chyba konverze: " . $e->getMessage());
            }
        }

		$this->load->language('checkout/success');
        $data['store_id'] = $this->config->get('config_store_id');

        if (isset($this->session->data['order_id'])) {

            $this->session->data['ga_orderDetails'] = NULL;
            $this->session->data['ga_orderProducts'] = NULL;
            if (isset($this->session->data['order_id'])) {
                $this->load->model('checkout/order');
                $this->session->data['ga_orderDetails'] = $this->model_checkout_order->getOrder($this->session->data['order_id']);
                $this->session->data['ga_orderProducts'] = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
            }
            

			$this->session->data['remarketing_order_id'] = $this->session->data['order_id'];
	  

                $data['products'] = $this->cart->getProducts();
                $data['order_id'] = $this->session->data['order_id'];
            
$this->session->data['success_order_id'] = $this->session->data['order_id'];
			$this->cart->clear();
//xml
			$order_id=$this->session->data['order_id'];

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			//unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);

			//xml			
			$ordersuccess_status = $this->config->get('ordersucess_status');
			
			if($ordersuccess_status==1){
			$this->response->redirect($this->url->link('extension/ordersuccess', '&order_id='.$order_id, true));
			}			
			//xml
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}


		if (isset($this->session->data['success_order_id'])) {
			$data['text_message'] .= sprintf($this->language->get('text_order_id'), $this->url->link('account/order/info&order_id=' . $this->session->data['success_order_id'], '', true), $this->session->data['success_order_id']);
				unset($this->session->data['success_order_id']);
			}
			
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}