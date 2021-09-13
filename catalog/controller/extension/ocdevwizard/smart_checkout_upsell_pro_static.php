<?php
##==================================================================##
## @author    : OCdevWizard                                         ##
## @contact   : ocdevwizard@gmail.com                               ##
## @support   : http://help.ocdevwizard.com                         ##
## @license   : http://license.ocdevwizard.com/Licensing_Policy.pdf ##
## @copyright : (c) OCdevWizard. Smart Checkout Upsell Pro, 2018    ##
##==================================================================##
class ControllerExtensionOcdevwizardSmartCheckoutUpsellProStatic extends Controller {

  private $_name     = 'smart_checkout_upsell_pro';
  private $_code     = 'smchup';
  private $_version  = '1.0.0';
  private $_session_currency;
  private $_currency_code;

  public function __construct($registry) {
    parent::__construct($registry);

    if (version_compare(VERSION, '2.1.0.2.1', '<=')) {
      $this->_session_currency = '';
      $this->_currency_code = $this->currency->getCode();
    } else {
      $this->_session_currency = $this->_currency_code = $this->session->data['currency'];
    }
  }

  public function index($setting) {
    $data = array();

    $models = array(
      'catalog/product',
      'tool/image',
      'extension/ocdevwizard/'.$this->_name,
      'extension/ocdevwizard/ocdevwizard_setting'
    );

    foreach ($models as $model) {
      $this->load->model($model);
    }

    $data = array_merge($data, $this->language->load('extension/ocdevwizard/'.$this->_name), $setting);

    $text_data = (array)$this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData($this->_name.'_text_data', (int)$this->config->get('config_store_id'));
    $upsell_data = (array)$this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData($this->_name.'_upsell_data', (int)$this->config->get('config_store_id'));

    $language_id = $this->{'model_extension_ocdevwizard_'.$this->_name}->getLanguageByCode($this->session->data['language']);

    if (isset($this->request->get['product_id'])) {
      $product_id = $this->request->get['product_id'];
    } else {
      $product_id = 0;
    }
    
    $products_in_cart = array();

    if ($product_id) {
      $products_in_cart[] = $product_id;

      $product_info = $this->model_catalog_product->getProduct($product_id);

      if ($product_info) {
        $tag_codes = array(
          '{product_name}'
        );

        $tag_codes_replace = array(
          $product_info['name']
        );

        if (isset($text_data[$language_id]['heading_static_widget'])) {
          $data['heading_title'] = html_entity_decode(str_replace($tag_codes, $tag_codes_replace, $text_data[$language_id]['heading_static_widget']), ENT_QUOTES, 'UTF-8');
        }
      }
    } else {
      foreach ($this->cart->getProducts() as $product) {
        $products_in_cart[] = $product['product_id'];
      }

      if (isset($text_data[$language_id]['alternative_heading_static_widget'])) {
        $data['heading_title'] = html_entity_decode($text_data[$language_id]['alternative_heading_static_widget'], ENT_QUOTES, 'UTF-8');
      }
    }

    $data['_name'] = $this->_name;
    $data['_code'] = $this->_code;

    if ($products_in_cart) {
      $upsell_products = array();

      foreach ($upsell_data as $result) {
        if ($result['cart_products'] && $result['type']) {
          foreach ($result['cart_products'] as $pr_id) {
            if ($pr_id != 0) {
              if (in_array($pr_id, $products_in_cart)) {
                if ($result['type'] == 1) {
                  $related_products = $this->model_catalog_product->getProductRelated($pr_id);

                  foreach ($related_products as $upsel_product) {
                    $upsell_products[] = $upsel_product['product_id'];
                  }
                }

                if ($result['type'] == 2 && $result['upsell_products']) {
                  foreach ($result['upsell_products'] as $upsel_product) {
                    $upsell_products[] = $upsel_product;
                  }
                }

                if ($result['type'] == 3 && $result['upsell_products']) {
                  $related_products = $this->model_catalog_product->getProductRelated($pr_id);

                  foreach ($related_products as $upsel_product) {
                    $upsell_products[] = $upsel_product['product_id'];
                  }

                  foreach ($result['upsell_products'] as $upsel_product) {
                    $upsell_products[] = $upsel_product;
                  }
                }

                $upsell_products = array_unique($upsell_products);
              }
            } else {
              if ($result['type'] == 1) {
                $related_products = $this->model_catalog_product->getProductRelated($product_id);

                foreach ($related_products as $upsel_product) {
                  $upsell_products[] = $upsel_product['product_id'];
                }
              }

              if ($result['type'] == 2 && $result['upsell_products']) {
                foreach ($result['upsell_products'] as $upsel_product) {
                  $upsell_products[] = $upsel_product;
                }
              }

              if ($result['type'] == 3 && $result['upsell_products']) {
                $related_products = $this->model_catalog_product->getProductRelated($product_id);

                foreach ($related_products as $upsel_product) {
                  $upsell_products[] = $upsel_product['product_id'];
                }

                foreach ($result['upsell_products'] as $upsel_product) {
                  $upsell_products[] = $upsel_product;
                }
              }

              $upsell_products = array_unique($upsell_products);
            }
          }
        }

        if ($setting['static_widget_randomize']) {
          shuffle($upsell_products);
        }
      }

      $data['products'] = array();

      if ($upsell_products) {
        $products = array_slice($upsell_products, 0, (int)$setting['static_widget_product_limit']);

        foreach ($products as $result_product_id) {
          $product_info = $this->model_catalog_product->getProduct($result_product_id);

          if ($product_info) {
            $image = ($product_info['image']) ? $this->model_tool_image->resize($product_info['image'], $setting['static_widget_main_image_width'], $setting['static_widget_main_image_height']) : $this->model_tool_image->resize('placeholder.png', $setting['static_widget_main_image_width'], $setting['static_widget_main_image_height']);
            $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->_session_currency) : false;
            $special = ((float)$product_info['special']) ? $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->_session_currency) : false;
            $tax = ($this->config->get('config_tax')) ? $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->_session_currency) : false;
            $rating = ($this->config->get('config_review_status')) ? $product_info['rating'] : false;

            $data['products'][] = array(
              'product_id'  => $product_info['product_id'],
              'thumb'       => $image,
              'name'        => $product_info['name'],
              'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['static_widget_product_description_limit']).'...',
              'price'       => $price,
              'special'     => $special,
              'tax'         => $tax,
              'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
              'rating'      => $rating,
              'href'        => $this->url->link('product/product', 'product_id='.$product_info['product_id'])
            );
          }
        }

        if (version_compare(VERSION, '2.1.0.2', '<=')) {
          if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/extension/ocdevwizard/'.$this->_name.'/static.tpl')) {
            return $this->load->view($this->config->get('config_template').'/template/extension/ocdevwizard/'.$this->_name.'/static.tpl', $data);
          } else {
            return $this->load->view('default/template/extension/ocdevwizard/'.$this->_name.'/static.tpl', $data);
          }
        } elseif (version_compare(VERSION, '3.0.0.0', '>=')) {
          return $this->load->view('extension/ocdevwizard/'.$this->_name.'/static', $data);
        } else {
          return $this->load->view('extension/ocdevwizard/'.$this->_name.'/static.tpl', $data);
        }
      }
    }
  }
}
?>
