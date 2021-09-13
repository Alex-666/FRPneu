<?php
class ModelExtensionTotalShipping extends Model {
	public function getTotal($total) {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
		    If ($this->session->data['shipping_method']["code"] == "pickupprague.pickupprague") {
                $address = !empty($this->session->data['simple']['shipping_address']) ? $this->session->data['simple']['shipping_address'] : array(
                    'address_id' => '',
                    'firstname' => '',
                    'lastname' => '',
                    'company' => '',
                    'address_1' => '',
                    'address_2' => '',
                    'city' => '',
                    'postcode' => '',
                    'zone_id' => '',
                    'country_id' => '',
                );
                $pickupprague = $this->{'model_shipping_pickupprague'}->getQuote($address);
                $cost_basis = $pickupprague["quote"]["pickupprague"]["cost_basis"];
                $zone_tariffs = $pickupprague["quote"]["pickupprague"]["data"];
                $first_title_prague = $pickupprague["quote"]["pickupprague"]['first_title_prague'];
            }
		    // Добавил $zone_tariffs и $cost_basis для раздельной цены доставки.
			$total['totals'][] = array(
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['title'],
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('total_shipping_sort_order'),
                'cost_basis' => $cost_basis,
                'zone_tariffs'  => $zone_tariffs,
                'first_title_prague' => $this->language->get('first_title_prague')
			);

			if ($this->session->data['shipping_method']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
						$total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total['total'] += $this->session->data['shipping_method']['cost'];
			//var_dump($this->{'model_shipping_pickupprague'}->getQuote($address));
		}
	}
}