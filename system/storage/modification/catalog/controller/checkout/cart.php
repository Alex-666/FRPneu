<?php
class ControllerCheckoutCart extends Controller {
    public function index() {

        // start: OCdevWizard SMCHUP
        $this->load->model('extension/ocdevwizard/ocdevwizard_setting');
        $this->load->model('extension/ocdevwizard/smart_checkout_upsell_pro');
        $this->load->model('catalog/product');
        $this->load->language('extension/ocdevwizard/smart_checkout_upsell_pro');

        $data['smchup_form_data'] = $smchup_form_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_form_data', (int)$this->config->get('config_store_id'));
        $smchup_text_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_text_data', (int)$this->config->get('config_store_id'));
        $smchup_upsell_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_upsell_data', (int)$this->config->get('config_store_id'));
        $smchup_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
        $smchup_customer_groups = isset($smchup_form_data['customer_groups']) ? $smchup_form_data['customer_groups'] : array();

        $language_id = $this->model_extension_ocdevwizard_smart_checkout_upsell_pro->getLanguageByCode($this->session->data['language']);

        $data['text_tax'] = $this->language->get('text_tax');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        // end: OCdevWizard SMCHUP
      
        $this->load->language('checkout/cart');

        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title_main'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('heading_title')
        );

        if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
            if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
                $data['error_warning'] = $this->language->get('error_stock');
            } elseif (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
                $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
            } else {
                $data['attention'] = '';
            }

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            $data['action'] = $this->url->link('checkout/cart/edit', '', true);

            if ($this->config->get('config_cart_weight')) {
                $data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
            } else {
                $data['weight'] = '';
            }

            $this->load->model('tool/image');
            $this->load->model('tool/upload');

            $data['products'] = array();

            $products = $this->cart->getProducts();

            foreach ($products as $product) {
                $product_total = 0;

                foreach ($products as $product_2) {
                    if ($product_2['product_id'] == $product['product_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                if ($product['minimum'] > $product_total) {
                    $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                }

                if ($product['image']) {
                    $image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
                }

                $option_data = array();

                foreach ($product['option'] as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                // Display prices
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

                    $price = $this->currency->format($unit_price, $this->session->data['currency']);
                    $total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
                } else {
                    $price = false;
                    $total = false;
                }

                $recurring = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year')
                    );

                    if ($product['recurring']['trial']) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }

                    if ($product['recurring']['duration']) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }


        // start: OCdevWizard SMCHUP
        $smchup_upsell_products_result = array();

        if (isset($smchup_form_data['activate']) && $smchup_form_data['activate'] && $smchup_form_data['activate_cart_widget'] && in_array($smchup_customer_group_id, $smchup_customer_groups)) {
          $tag_codes = array(
            '{product_name}'
          );

          $tag_codes_replace = array(
            $product['name']
          );

          if (version_compare(VERSION, '2.0.3.1', '<=')) {
            $_product_key = $product['key'];
          } else {
            $_product_key = $product['cart_id'];
          }

          if (isset($smchup_text_data[$language_id])) {
            $data['smchup_upsell_products_heading'][$_product_key] = html_entity_decode(str_replace($tag_codes, $tag_codes_replace, $smchup_text_data[$language_id]['heading_cart_widget']), ENT_QUOTES, 'UTF-8');
          }

          $smchup_upsell_products = array();

          if ($smchup_upsell_data) {
            foreach ($smchup_upsell_data as $result) {
              if ($result['cart_products'] && $result['type']) {
                foreach ($result['cart_products'] as $pr_id) {
                  if ($pr_id != 0) {
                    if ($pr_id == $product['product_id']) {
                      if ($result['type'] == 1) {
                        $related_products = $this->model_catalog_product->getProductRelated($pr_id);

                        foreach ($related_products as $upsel_product) {
                          $smchup_upsell_products[] = $upsel_product['product_id'];
                        }
                      }

                      if ($result['type'] == 2 && $result['upsell_products']) {
                        foreach ($result['upsell_products'] as $upsel_product) {
                          $smchup_upsell_products[] = $upsel_product;
                        }
                      }

                      if ($result['type'] == 3 && $result['upsell_products']) {
                        $related_products = $this->model_catalog_product->getProductRelated($pr_id);

                        foreach ($related_products as $upsel_product) {
                          $smchup_upsell_products[] = $upsel_product['product_id'];
                        }

                        foreach ($result['upsell_products'] as $upsel_product) {
                          $smchup_upsell_products[] = $upsel_product;
                        }
                      }

                      $smchup_upsell_products = array_unique($smchup_upsell_products);
                    }
                  } else {
                    if ($result['type'] == 1) {
                      $related_products = $this->model_catalog_product->getProductRelated($product['product_id']);

                      foreach ($related_products as $upsel_product) {
                        $smchup_upsell_products[] = $upsel_product['product_id'];
                      }
                    }

                    if ($result['type'] == 2 && $result['upsell_products']) {
                      foreach ($result['upsell_products'] as $upsel_product) {
                        $smchup_upsell_products[] = $upsel_product;
                      }
                    }

                    if ($result['type'] == 3 && $result['upsell_products']) {
                      $related_products = $this->model_catalog_product->getProductRelated($product['product_id']);

                      foreach ($related_products as $upsel_product) {
                        $smchup_upsell_products[] = $upsel_product['product_id'];
                      }

                      foreach ($result['upsell_products'] as $upsel_product) {
                        $smchup_upsell_products[] = $upsel_product;
                      }
                    }

                    $smchup_upsell_products = array_unique($smchup_upsell_products);
                  }
                }

                if ($smchup_form_data['cart_widget_randomize']) {
                  shuffle($smchup_upsell_products);
                }
              }
            }
          }

          if ($smchup_upsell_products) {
            if (version_compare(VERSION, '2.1.0.2.1', '<=')) {
          	  $_session_currency = '';
          	} else {
          	  $_session_currency = $this->session->data['currency'];
        	  }

            $smchup_products = array_slice($smchup_upsell_products, 0, (int)$smchup_form_data['cart_widget_product_limit']);

            foreach ($smchup_products as $upsell_product_id) {
              $product_info = $this->model_catalog_product->getProduct($upsell_product_id);

              if ($product_info) {
                $upsell_product_image = ($product_info['image']) ? $this->model_tool_image->resize($product_info['image'], $smchup_form_data['cart_widget_main_image_width'], $smchup_form_data['cart_widget_main_image_height']) : $this->model_tool_image->resize('placeholder.png',  $smchup_form_data['cart_widget_main_image_width'], $smchup_form_data['cart_widget_main_image_height']);

                $upsell_product_price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $_session_currency) : false;

                $upsell_product_special = ((float)$product_info['special']) ? $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $_session_currency) : false;

                $upsell_product_tax = ($this->config->get('config_tax')) ? $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $_session_currency) : false;

                $upsell_product_rating = ($this->config->get('config_review_status')) ? $product_info['rating'] : false;

                $smchup_upsell_products_result[] = array(
                  'product_id'  => $product_info['product_id'],
                  'thumb'       => $upsell_product_image,
                  'name'        => $product_info['name'],
                  'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $smchup_form_data['cart_widget_product_description_limit']).'...',
                  'price'       => $upsell_product_price,
                  'special'     => $upsell_product_special,
                  'tax'         => $upsell_product_tax,
                  'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
                  'rating'      => $upsell_product_rating,
                  'href'        => $this->url->link('product/product', 'product_id='.$product_info['product_id'])
                );
              }
            }
          }
        }
        // end: OCdevWizard SMCHUP
      
                $data['products'][] = array(

        // start: OCdevWizard SMCHUP
        'upsell_products' => $smchup_upsell_products_result,
        'product_key'     => $_product_key,
        // end: OCdevWizard SMCHUP
      
                    'cart_id'   => $product['cart_id'],
                    'thumb'     => $image,
                    'name'      => $product['name'],
                    'model'     => $product['model'],
                    'option'    => $option_data,
                    'recurring' => $recurring,
                    'quantity'  => $product['quantity'],
                    'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                    'price'     => $price,
                    'total'     => $total,
                    'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }

            // Gift Voucher
            $data['vouchers'] = array();

            if (!empty($this->session->data['vouchers'])) {
                foreach ($this->session->data['vouchers'] as $key => $voucher) {
                    $data['vouchers'][] = array(
                        'key'         => $key,
                        'description' => $voucher['description'],
                        'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
                        'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
                    );
                }
            }

            // Totals
            $this->load->model('setting/extension');

            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            // Because __call can not keep var references so we put them into an array.
            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );

            // Display prices
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $sort_order = array();

                $results = $this->model_setting_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get('total_' . $result['code'] . '_status')) {
                        $this->load->model('extension/total/' . $result['code']);

                        // We have to put the totals in an array so that they pass by reference.
                        $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                    }
                }

                $sort_order = array();

                foreach ($totals as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $totals);
            }

            $data['totals'] = array();

            foreach ($totals as $total) {
                $data['totals'][] = array(
                    'title' => $total['title'],
                    'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
                );
            }

            $data['continue'] = $this->url->link('common/home');

            $data['checkout'] = $this->url->link('checkout/checkout', '', true);

            $this->load->model('setting/extension');

            $data['modules'] = array();

            $files = glob(DIR_APPLICATION . '/controller/extension/total/*.php');

            if ($files) {
                foreach ($files as $file) {
                    $result = $this->load->controller('extension/total/' . basename($file, '.php'));

                    if ($result) {
                        $data['modules'][] = $result;
                    }
                }
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('checkout/cart', $data));
        } else {
            $data['text_error'] = $this->language->get('text_empty');

            $data['continue'] = $this->url->link('common/home');

            unset($this->session->data['success']);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function add() {
        $this->load->language('checkout/cart');

        $json = array();

        if (isset($this->request->post['product_id'])) {
            $product_id = (int)$this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            if (isset($this->request->post['quantity'])) {
                $quantity = (int)$this->request->post['quantity'];
            } else {
                $quantity = 1;
            }

            if (isset($this->request->post['option'])) {
                $option = array_filter($this->request->post['option']);
            } else {
                $option = array();
            }

            $product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
						
            foreach ($product_options as $product_option) {
                if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                    $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                }
            }

            if (isset($this->request->post['recurring_id'])) {
                $recurring_id = $this->request->post['recurring_id'];
            } else {
                $recurring_id = 0;
            }

            $recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

            if ($recurrings) {
                $recurring_ids = array();

                foreach ($recurrings as $recurring) {
                    $recurring_ids[] = $recurring['recurring_id'];
                }

                if (!in_array($recurring_id, $recurring_ids)) {
                    $json['error']['recurring'] = $this->language->get('error_recurring_required');
                }
            }

            $canAdd = $this->cart->checkStock($this->request->post['product_id'], $quantity);
            if(!$canAdd){
                //sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum'])

                $json['error']['quantity'] = sprintf($this->language->get('quantity_error'), $product_info['quantity'] );
            }

            if (!$json) {
                $this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);

        // start: OCdevWizard SMCHUP
        $this->load->model('extension/ocdevwizard/ocdevwizard_setting');

        $smchup_upsell_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_upsell_data', (int)$this->config->get('config_store_id'));
        $smchup_form_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_form_data', (int)$this->config->get('config_store_id'));
        $smchup_text_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_text_data', (int)$this->config->get('config_store_id'));
        $smchup_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
        $smchup_customer_groups = isset($smchup_form_data['customer_groups']) ? $smchup_form_data['customer_groups'] : array();

        if (isset($smchup_form_data['activate']) && $smchup_form_data['activate'] && $smchup_form_data['activate_popup_widget'] && in_array($smchup_customer_group_id, $smchup_customer_groups)) {
          $smchup_products_in_cart = array();

          foreach ($smchup_upsell_data as $result) {
            if ($result['cart_products'] && $result['type']) {
              foreach ($result['cart_products'] as $pr_id) {
                $smchup_products_in_cart[] = $pr_id;
              }
            }
          }

          if (in_array($this->request->post['product_id'], $smchup_products_in_cart)) {
            $json['smchup_status'] = 1;
          }
        }
        // end: OCdevWizard SMCHUP
      
				
				$cart_products = $this->cart->getProducts();
				//var_dump($cart_products);
				
				$sklo = 0;
				$sklo_in_cart = 0;				
				$sklo_in_recomend = 0;				
				$sklo_service_id = 72613;
				
				$make_all = 0;
				
				$tire_wheel_service_id = 72626;
				$tire_plus_wheel_in_cart = 0;
				$tire_plus_wheel = 0;				
				$is_tire = 0;
				$is_wheel = 0;
				
				foreach ($cart_products as $product) {                	
					$product_attr = $this->model_catalog_product->getProductAttributes($product['product_id']); 			
					foreach($product_attr as $attr){
						if($attr['attribute_group_id'] == 6) {
							if($product['quantity'] == 4) {								
								$is_tire = $product['product_id'];
							}
							$make_all = 1;
						}
						if($attr['attribute_group_id'] == 3) {
							if($product['quantity'] == 4) {
								$is_wheel = $product['product_id'];
							}
							$make_all = 1;
						}
						if($attr['attribute_group_id'] == 7) {
							$sklo = 1;
						}
					}
					if (($product['product_id'] == $sklo_service_id) && ($product['quantity'] > 0 )) $sklo_in_cart = 1;
					if (($product['product_id'] == $tire_wheel_service_id) && ($product['quantity'] > 0 )) $tire_plus_wheel_in_cart = 1;
				}
				
				
				$this->load->model('catalog/category');					
				$product_cat = $this->model_catalog_product->getCategories($is_tire);				
				$product_cat_parent = $this->model_catalog_category->getCategory($product_cat[0]['category_id']);
					
				if ($is_tire != 0 && $is_wheel != 0) {
					if ($product_cat_parent['parent_id'] !=	366) {
					
					$product_attr_w = $this->model_catalog_product->getProductAttributes($is_wheel);
					
					$w_r = 0;
					
					foreach ( $product_attr_w as $atr_g_w){
						if ($atr_g_w['attribute_group_id'] == 3){
							foreach ($atr_g_w['attribute'] as $a){
								if ($a['attribute_id'] == 1) 
								{
									$w_r = $a['text'];
								}
							}
						}
					}
					
					$product_attr_t = $this->model_catalog_product->getProductAttributes($is_tire);
					
					$t_r = 0;
					
					foreach ( $product_attr_t as $atr_g_t){
						if ($atr_g_t['attribute_group_id'] == 6){
							foreach ($atr_g_t['attribute'] as $a){
								if ($a['attribute_id'] == 8) $t_r = $a['text'];
							}
						}
					}
								
					if (($t_r == $w_r) && !$tire_plus_wheel_in_cart) $tire_plus_wheel = 1;	
					}
				}
					
				if ($sklo && !$sklo_in_cart) $sklo_in_recomend = 1;
				
				$json['tire_plus_wheel'] = $tire_plus_wheel;
				
				if ($make_all && !$tire_plus_wheel) {
					if ($product_cat_parent['parent_id'] !=	366) {
						$json['recomend_service'] = $this->language->get('service_recomend_tire');
					}
				}
				
				if ($tire_plus_wheel) {					
					$json['tire_wheel_service_id'] = $tire_wheel_service_id;					
					$product_tw = $this->model_catalog_product->getProduct($tire_wheel_service_id);
					$json['tw_name'] = $product_tw['name'].' ('.$this->currency->format($product_tw['price'], $this->session->data['currency']).')';
				}
				
				
				$json['sklo'] = $sklo_in_recomend;
				if ($sklo_in_recomend) {
					$json['sklo_service_id'] = $sklo_service_id;	
					$product_sklo = $this->model_catalog_product->getProduct($sklo_service_id);
					$json['sklo_name'] = $product_sklo['name'].' ('.$this->currency->format($product_sklo['price'], $this->session->data['currency']).')';
				}
				$json['service_title'] = $this->language->get('service_name');
				

                if (strpos($this->config->get('config_template'), 'journal2') === 0) {
                    $this->load->model('tool/image');
                    $json['image'] = Journal2Utils::resizeImage($this->model_tool_image, $product_info['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
                }
            
                $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

                // Unset all shipping and payment methods
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);

                // Totals
                $this->load->model('setting/extension');

                $totals = array();
                $taxes = $this->cart->getTaxes();
                $total = 0;

                // Because __call can not keep var references so we put them into an array.
                $total_data = array(
                    'totals' => &$totals,
                    'taxes'  => &$taxes,
                    'total'  => &$total
                );

                // Display prices
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $sort_order = array();

                    $results = $this->model_setting_extension->getExtensions('total');

                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                    }

                    array_multisort($sort_order, SORT_ASC, $results);

                    foreach ($results as $result) {
                        if ($this->config->get('total_' . $result['code'] . '_status')) {
                            $this->load->model('extension/total/' . $result['code']);

                            // We have to put the totals in an array so that they pass by reference.
                            $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                        }
                    }

                    $sort_order = array();

                    foreach ($totals as $key => $value) {
                        $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $totals);
                }

                $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
            } else {
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit() {
        $this->load->language('checkout/cart');

        $json = array();

        // Update
        if (!empty($this->request->post['quantity'])) {
            foreach ($this->request->post['quantity'] as $key => $value) {
                $this->cart->update($key, $value);
            }

            $this->session->data['success'] = $this->language->get('text_remove');

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function remove() {
        $this->load->language('checkout/cart');

        $json = array();

        // Remove
        if (isset($this->request->post['key'])) {
            $this->cart->remove($this->request->post['key']);

            unset($this->session->data['vouchers'][$this->request->post['key']]);

            $json['success'] = $this->language->get('text_remove');

            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['reward']);

            // Totals
            $this->load->model('setting/extension');

            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            // Because __call can not keep var references so we put them into an array.
            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );

            // Display prices
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $sort_order = array();

                $results = $this->model_setting_extension->getExtensions('total');

                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                }

                array_multisort($sort_order, SORT_ASC, $results);

                foreach ($results as $result) {
                    if ($this->config->get('total_' . $result['code'] . '_status')) {
                        $this->load->model('extension/total/' . $result['code']);

                        // We have to put the totals in an array so that they pass by reference.
                        $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                    }
                }

                $sort_order = array();

                foreach ($totals as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $totals);
            }

            $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
