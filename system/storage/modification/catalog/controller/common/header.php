<?php

    global $aFolder;
    global $modulesPath;

    
    
    if (!defined('HTTP_ADMIN')) {
        $root_dir = DIR_APPLICATION.'../';
        $folder_contents = scandir($root_dir);
                if (!(in_array('admin', $folder_contents) && file_exists($root_dir.'admin/config.php'))) {
                        foreach ($folder_contents as $value) {
                                if (is_dir($root_dir.$value) && $value != '.' && $value != '..'){
                                        if (file_exists($root_dir.$value.'/config.php')) {
                                                $admin_folder_name = $value;
                                                continue;
                                        }
                                }
                        }
                }
        if (isset($admin_folder_name)) {
                define('HTTP_ADMIN',$admin_folder_name);
        } else {
                define('HTTP_ADMIN','admin');
        }
    }
    
    $aFolder = preg_replace('/.*\/([^\/].*)\//is','$1',HTTP_ADMIN);
    
    if (version_compare(VERSION,'2.3','>=')) { //newer than 2.2.x
        $modulesPath = 'extension/module';
    } else {
        $modulesPath = 'module';
    }

    include (preg_match("/components\/com_(ayelshop|aceshop|mijoshop)\/opencart\//ims",__FILE__,$matches)?'components/com_'.$matches[1].'/opencart/':'').$aFolder.'/controller/'.$modulesPath.'/magictoolbox-module.inc';
    
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
      

		// remarketing all in one
		$this->load->model('tool/remarketing');
		if ($this->config->get('remarketing_status')) {
			$data['remarketing_head'] = $this->load->controller('common/remarketing/header');
		}
		if ($this->config->get('remarketing_status') && !$this->model_tool_remarketing->isBot()) {
            $data['remarketing_body'] = $this->load->controller('common/remarketing/body');
			$data['google_status'] = $this->config->get('remarketing_google_status');
			$data['google_code'] = $this->config->get('remarketing_google_identifier');
			$data['facebook_status'] = $this->config->get('remarketing_facebook_status');
			$data['ecommerce_selector'] = $this->config->get('remarketing_ecommerce_selector');
			$data['google_currency'] = $this->config->get('remarketing_google_currency');	
			$data['facebook_currency'] = $this->config->get('remarketing_facebook_currency');	
			$data['ecommerce_currency'] = $this->config->get('remarketing_ecommerce_currency');	
			$data['remarketing_currency'] = $this->session->data['currency'];	
			$data['google_identifier'] = $this->config->get('remarketing_google_identifier');
			$data['ecommerce_ga4_identifier'] = $this->config->get('remarketing_ecommerce_ga4_identifier');
			$data['ecommerce_measurement_selector'] = $this->config->get('remarketing_ecommerce_measurement_selector');
			
			$this->model_tool_remarketing->getCid();  
			$this->model_tool_remarketing->trackUtm();  
 			
			$this->document->addScript('catalog/view/javascript/sp_remarketing.js');
		}
			
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

        
                            $contents =  $this->load->view('common/header', $data);

    return setModuleHeaders($contents, $this);
    
	}
}
