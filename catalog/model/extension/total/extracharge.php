<?php
class ModelExtensionTotalExtracharge extends Model {
	public function getTotal($total) {

	    $shippingname = explode('.', $this->session->data['shipping_method']['code'])[0];
	    //var_dump();
	    //var_dump(explode('.', $this->session->data['shipping_method']['code']));
	    //var_dump($this->cart->getTotal());
		if (($this->cart->getSubTotal() > $this->config->get('total_extracharge_total')) && ($this->cart->getSubTotal() > 0) && $this->session->data["payment_method"]['code'] == "fdl") {
			$this->load->language('extension/total/extracharge');
            $fee =  ($this->cart->getTotal() + $this->session->data['shipping_methods'][$shippingname]['quote'][$shippingname]['cost']) * $this->config->get('total_extracharge_fee') / 100;
			$total['totals'][] = array(
				'code'       => 'extracharge',
				'title'      => $this->language->get('text_extracharge'),
				'value'      => $fee,
				'sort_order' => $this->config->get('total_extracharge_sort_order')
			);

			if ($this->config->get('total_extracharge_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_extracharge_fee'), $this->config->get('total_extracharge_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
						$total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total['total'] += $fee;
		}
	}
}