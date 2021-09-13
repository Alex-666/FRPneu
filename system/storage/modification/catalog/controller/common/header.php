<?php
class ControllerCommonHeader extends Controller {

        // start: OCdevWizard
        public function ocdevwizard_js_create($data) {
          if ($data) {
            $ocdevwizard_script  = "var ocdev_modules = [];\r\n";

            if (isset($data['smca_status']) && $data['smca_status'] == 1) {
              $ocdevwizard_script .= "ocdev_modules.push({\r\n";
              $ocdevwizard_script .= "  src: 'index.php?route=ocdevwizard/smart_cart',\r\n";
              $ocdevwizard_script .= "  type:'ajax'\r\n";
              $ocdevwizard_script .= "});\r\n";
            }

            if (isset($data['smpcp_status']) && $data['smpcp_status'] == 1) {
              $ocdevwizard_script .= "ocdev_modules.push({\r\n";
              $ocdevwizard_script .= "  src: 'index.php?route=ocdevwizard/smart_popup_cart_pro',\r\n";
              $ocdevwizard_script .= "  type:'ajax'\r\n";
              $ocdevwizard_script .= "});\r\n";
            }

            if (isset($data['smpcpp_status']) && $data['smpcpp_status'] == 1) {
              $ocdevwizard_script .= "ocdev_modules.push({\r\n";
              $ocdevwizard_script .= "  src: 'index.php?route=ocdevwizard/smart_popup_cart_pro_plus',\r\n";
              $ocdevwizard_script .= "  type:'ajax'\r\n";
              $ocdevwizard_script .= "});\r\n";
            }

            if (isset($data['smac_status']) && $data['smac_status'] == 1 && $data['smart_abandoned_cart'] == 1) {
              $ocdevwizard_script .= "ocdev_modules.push({\r\n";
              $ocdevwizard_script .= "  src: 'index.php?route=ocdevwizard/smart_abandoned_cart',\r\n";
              $ocdevwizard_script .= "  type:'ajax'\r\n";
              $ocdevwizard_script .= "});\r\n";
            }

            if (isset($data['smchup_status']) && $data['smchup_status'] == 1) {
              $ocdevwizard_script .= "ocdev_modules.push({\r\n";
              $ocdevwizard_script .= "  src: 'index.php?route=extension/ocdevwizard/smart_checkout_upsell_pro',\r\n";
              $ocdevwizard_script .= "  type:'ajax'\r\n";
              $ocdevwizard_script .= "});\r\n";
            }

            if (isset($data['smchupp_status']) && $data['smchupp_status'] == 1) {
              $ocdevwizard_script .= "ocdev_modules.push({\r\n";
              $ocdevwizard_script .= "  src: 'index.php?route=extension/ocdevwizard/smart_checkout_upsell_pro_plus',\r\n";
              $ocdevwizard_script .= "  type:'ajax'\r\n";
              $ocdevwizard_script .= "});\r\n";
            }

            if (!file_exists(DIR_APPLICATION.'view/javascript/ocdevwizard/ocdevwizard.js')) {
              file_put_contents(DIR_APPLICATION.'view/javascript/ocdevwizard/ocdevwizard.js', $ocdevwizard_script);
            }
          }
        }
        // end: OCdevWizard
      

        // start: OCdevWizard SMCHUP
        public function smchup_js_create($data) {
          if ($data) {
            $script  = "";
            $insert_widget = explode('\r\n', stripslashes($data['insert_widget']));
            if ($data['activate_product_widget'] && $insert_widget) {
              $script .= "$(function() {\r\n";
              $script .= "  var product_id_in_page = $('".$data['main_product_id_selector']."').val();\r\n";
              $script .= "  if (typeof(product_id_in_page) != 'undefined') {\r\n";
              foreach ($insert_widget as $widget) {
              $script .= html_entity_decode($widget)."\r\n";
              }
              $script .= "  }\r\n";
              $script .= "});\r\n";
            }
            if ($data['activate_popup_widget']) {
              $script .= "function smchup_get_product_id(data) {\r\n";
              $script .= "  var product_id = 0;\r\n";
              $script .= "  var arr = data.split('&');\r\n";
              $script .= "  for (var i = 0; i < arr.length; i++) {\r\n";
              $script .= "    var product_id = arr[i].split('=');\r\n";
              $script .= "    if (product_id[0] === 'product_id') {\r\n";
              $script .= "      return product_id[1];\r\n";
              $script .= "    }\r\n";
              $script .= "  }\r\n";
              $script .= "}\r\n";
              $script .= "$(document).ajaxSuccess(function(event, xhr, settings) {\r\n";
              $script .= "  if (settings.url == '".$data['route_to_system_add_method']."') {\r\n";
              $script .= "    var smchup_response_data = JSON.parse(xhr.responseText);\r\n";
              $script .= "    if (xhr.responseText.indexOf('error') <= 0 && smchup_response_data['smchup_status'] == 1) {\r\n";
              $script .= "      getOCwizardModal_smchup(smchup_get_product_id(settings.data));\r\n";
              $script .= "      $('html, body').stop();\r\n";
              $script .= "    }\r\n";
              $script .= "  }\r\n";
              $script .= "});\r\n";
              $script .= "function getOCwizardModal_smchup(product_id) {\r\n";
              $script .= "  $.magnificPopup.open({\r\n";
              $script .= "    tLoading: '<img src=\"catalog/view/theme/default/stylesheet/ocdevwizard/smart_checkout_upsell_pro/loading.svg\" alt=\"\" />',\r\n";
              $script .= "    items: {\r\n";
              $script .= "      src: 'index.php?route=extension/ocdevwizard/smart_checkout_upsell_pro&product_id='+product_id,\r\n";
              $script .= "      type:'ajax'\r\n";
              $script .= "    },\r\n";
              $script .= "    showCloseBtn: false,\r\n";
              $script .= "    gallery: {\r\n";
              $script .= "      enabled: true\r\n";
              $script .= "    },\r\n";
              $script .= "    callbacks: {\r\n";
              $script .= "      open: function() {\r\n";
              $script .= "        $('.mfp-content').addClass('mfp-with-anim');\r\n";
              $script .= "      },\r\n";
              $script .= "      close: function() {\r\n";
              $script .= "        $.ajax({\r\n";
              $script .= "          type: 'get',\r\n";
              $script .= "          url:  'index.php?route=extension/ocdevwizard/ocdevwizard_setting/modules',\r\n";
              $script .= "          dataType: 'json',\r\n";
              $script .= "          success: function(json) {\r\n";
              $script .= "            ocdev_modules.length = 0;\r\n";
              $script .= "            if (json['modules']) {\r\n";
              $script .= "              $.each(json['modules'], function(i, value) {\r\n";
              $script .= "                if (value == 'smart_checkout_upsell_pro' || value == 'smart_checkout_upsell_pro_plus') {\r\n";
              $script .= "                  ocdev_modules.push({\r\n";
              $script .= "                    src: 'index.php?route=extension/ocdevwizard/'+value+'&product_id='+product_id,\r\n";
              $script .= "                    type:'ajax'\r\n";
              $script .= "                  });\r\n";
              $script .= "                } else {\r\n";
              $script .= "                  ocdev_modules.push({\r\n";
              $script .= "                    src: 'index.php?route=extension/ocdevwizard/'+value,\r\n";
              $script .= "                    type:'ajax'\r\n";
              $script .= "                  });\r\n";
              $script .= "                }\r\n";
              $script .= "              });\r\n";
              $script .= "            }\r\n";
              $script .= "          }\r\n";
              $script .= "        });\r\n";
              $script .= "      }\r\n";
              $script .= "    }\r\n";
              $script .= "  });\r\n";
              $script .= "  $('.mfp-bg').css({\r\n";
              $script .= "    'background': 'url(image/catalog/ocdevwizard/smart_checkout_upsell_pro/background/".$data['style_background'].")',\r\n";
              $script .= "    'opacity': '".(($data['background_opacity'] == 0) ? $data['background_opacity'] : $data['background_opacity']/10)."'\r\n";
              $script .= "  });\r\n";
              $script .= "}\r\n";
            }

            if (!file_exists(DIR_APPLICATION.'view/javascript/ocdevwizard/smart_checkout_upsell_pro/main.js')) {
              file_put_contents(DIR_APPLICATION.'view/javascript/ocdevwizard/smart_checkout_upsell_pro/main.js', $script);
            }
          }
        }
        // end: OCdevWizard SMCHUP
      
	public function index() {

        // start: OCdevWizard SMCHUP
        $this->load->model('extension/ocdevwizard/ocdevwizard_setting');

        $smchup_form_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_form_data', (int)$this->config->get('config_store_id'));
        $smchup_upsell_data = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_upsell_data', (int)$this->config->get('config_store_id'));

        $smchup_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');

        $smchup_customer_groups = isset($smchup_form_data['customer_groups']) ? $smchup_form_data['customer_groups'] : array();

        if (isset($smchup_form_data['activate']) && $smchup_form_data['activate'] && $smchup_upsell_data && in_array($smchup_customer_group_id, $smchup_customer_groups)) {
          $this->document->addScript("catalog/view/javascript/ocdevwizard/smart_checkout_upsell_pro/jquery.magnific-popup.min.js?v=".$smchup_form_data['front_module_version']);
          $this->document->addStyle("catalog/view/javascript/ocdevwizard/smart_checkout_upsell_pro/magnific-popup.css?v=".$smchup_form_data['front_module_version']);
          $this->document->addStyle("catalog/view/theme/default/stylesheet/ocdevwizard/smart_checkout_upsell_pro/stylesheet.css?v=".$smchup_form_data['front_module_version']);

          $this->load->model('extension/ocdevwizard/smart_checkout_upsell_pro');

          $language_id = $this->model_extension_ocdevwizard_smart_checkout_upsell_pro->getLanguageByCode($this->session->data['language']);

          if (isset($smchup_form_data['direction_type'][$language_id]) && $smchup_form_data['direction_type'][$language_id] == '2') {
            $this->document->addStyle("catalog/view/theme/default/stylesheet/ocdevwizard/smart_checkout_upsell_pro/stylesheet_rtl.css?v=".$smchup_form_data['front_module_version']);
          }

          $this->smchup_js_create($smchup_form_data);

          if (file_exists(DIR_APPLICATION.'view/javascript/ocdevwizard/smart_checkout_upsell_pro/main.js')) {
            $this->document->addScript("catalog/view/javascript/ocdevwizard/smart_checkout_upsell_pro/main.js?v=".$smchup_form_data['front_module_version']);
          }

          $smchup_status = 1;
        }
        // end: OCdevWizard SMCHUP
      

        // start: OCdevWizard
        $ocdevwizard_modules = array();

        if (isset($smca_status)) {
          $ocdevwizard_modules['smca_status'] = $smca_status;
        }

        if (isset($smpcp_status)) {
          $ocdevwizard_modules['smpcp_status'] = $smpcp_status;
        }

        if (isset($smpcpp_status)) {
          $ocdevwizard_modules['smpcpp_status'] = $smpcpp_status;
        }

        if (isset($smac_status)) {
          $ocdevwizard_modules['smac_status'] = $smac_status;
          $ocdevwizard_modules['smart_abandoned_cart'] = $smart_abandoned_cart;
        }

        if (!isset($smca_status) && !isset($smpcp_status) && !isset($smpcpp_status) && !isset($smac_status)) {
          if (isset($smchup_status)) {
            $ocdevwizard_modules['smchup_status'] = $smchup_status;
          }

          if (isset($smchupp_status)) {
            $ocdevwizard_modules['smchupp_status'] = $smchupp_status;
          }
        }

        $this->ocdevwizard_js_create($ocdevwizard_modules);

        if (file_exists(DIR_APPLICATION.'view/javascript/ocdevwizard/ocdevwizard.js')) {
          $this->document->addScript("catalog/view/javascript/ocdevwizard/ocdevwizard.js");
        }
        // end: OCdevWizard
      
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

        /** Load Pagination Format **/
        $data['load_format_pagination'] = $this->load->controller('common/load_format_pagination');
      
		$data['load_format_pagination'] = $this->load->controller('common/load_format_pagination');
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

                $this->load->language('extension/module/feedback');

                $data['callback_active'] = $this->config->get('module_feedback_header');
                
                if ($this->customer->isLogged()) {
                    $data['name_callback'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
                } else {
                    $data['name_callback'] = '';
                }

                if ($this->customer->isLogged()) {
                    $data['phone_callback'] = $this->customer->getTelephone();
                } else {
                    $data['phone_callback'] = '';
                }
            
        If ($this->config->get('config_store_id') == "0"){
            $data['hader_text_twelve'] = $this->language->get('hader_text_twelve');
            $data['text_twelve'] = true;
        }
        $data['store_id'] = (int)$this->config->get('config_store_id');

        return $this->load->view('common/header', $data);
	}

                public function write() {
                    $this->load->language('extension/module/feedback');

                    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                        $data['error_name'] =  $this->language->get('error_name');
                        $data['error_phone'] =  $this->language->get('error_phone');
                        $data['text_success'] = $this->language->get('text_success');
                        
                        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                            $json['error'] = $data['error_name'];
                        }

                        if ((utf8_strlen($this->request->post['phone']) < 3) || (utf8_strlen($this->request->post['phone']) > 25)) {
                            $json['error'] = $data['error_phone'];
                        }
                        
                        if (!isset($json['error'])) {
                            $json['success'] = $data['text_success'];
                            
                            $mail = new Mail($this->config->get('config_mail_engine'));
                            $mail->parameter = $this->config->get('config_mail_parameter');
                            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                
                            $mail->setTo($this->config->get('config_email'));
                            $mail->setFrom($this->config->get('config_email'));
                            $mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
                            $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject_callback'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
                            $mail->setText($this->request->post['name'] . ' ' . $this->request->post['phone']);
                            $mail->send();
                        }
                    }

                    $this->response->addHeader('Content-Type: application/json');
                    $this->response->setOutput(json_encode($json));
                }
            
}
