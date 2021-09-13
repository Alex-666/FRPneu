<?php
##==================================================================##
## @author    : OCdevWizard                                         ##
## @contact   : ocdevwizard@gmail.com                               ##
## @support   : http://help.ocdevwizard.com                         ##
## @license   : http://license.ocdevwizard.com/Licensing_Policy.pdf ##
## @copyright : (c) OCdevWizard. Module Helper Settings, 2014       ##
##==================================================================##
class ControllerExtensionOcdevwizardOcdevwizardSetting extends Controller {

  private $_version  = '2.0.3';

  public function modules() {
    $this->load->model('extension/ocdevwizard/ocdevwizard_setting');

    $json['modules'] = array();

    // start: OCdevWizard SMCA
    $smca_form_data         = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_cart_form_data');
    $smca_store_id          = (int)$this->config->get('config_store_id');
    $smca_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
    $smca_customer_groups   = isset($smca_form_data['customer_groups']) ? $smca_form_data['customer_groups'] : array();
    $smca_stores            = isset($smca_form_data['stores']) ? $smca_form_data['stores'] : array();

    if (isset($smca_form_data['activate']) && $smca_form_data['activate'] && !in_array($smca_customer_group_id, $smca_customer_groups) && !in_array($smca_store_id, $smca_stores)) {
      $json['modules']['smart_cart'] = 'smart_cart';
    }
    // end: OCdevWizard SMCA

    // start: OCdevWizard SMPCP
    $smpcp_form_data         = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_popup_cart_pro_form_data', (int)$this->config->get('config_store_id'));
    $smpcp_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
    $smpcp_customer_groups   = isset($smpcp_form_data['customer_groups']) ? $smpcp_form_data['customer_groups'] : array();

    if (isset($smpcp_form_data['activate']) && $smpcp_form_data['activate'] && in_array($smpcp_customer_group_id, $smpcp_customer_groups)) {
      $json['modules']['smart_popup_cart_pro'] = 'smart_popup_cart_pro';
    }
    // end: OCdevWizard SMPCP

    // start: OCdevWizard SMPCPP
    $smpcpp_form_data         = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_popup_cart_pro_plus_form_data', (int)$this->config->get('config_store_id'));
    $smpcpp_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
    $smpcpp_customer_groups   = isset($smpcpp_form_data['customer_groups']) ? $smpcpp_form_data['customer_groups'] : array();

    if (isset($smpcpp_form_data['activate']) && $smpcpp_form_data['activate'] && in_array($smpcpp_customer_group_id, $smpcpp_customer_groups)) {
      $json['modules']['smart_popup_cart_pro_plus'] = 'smart_popup_cart_pro_plus';
    }
    // end: OCdevWizard SMPCPP

    // start: OCdevWizard SMAC
    $smac_form_data         = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_abandoned_cart_form_data');
    $smac_store_id          = (int)$this->config->get('config_store_id');
    $smac_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
    $smac_customer_groups   = isset($smac_form_data['customer_groups']) ? $smac_form_data['customer_groups'] : array();
    $smac_stores            = isset($smac_form_data['stores']) ? $smac_form_data['stores'] : array();

    if (isset($smac_form_data['activate']) && $smac_form_data['activate'] && !in_array($smac_customer_group_id, $smac_customer_groups) && !in_array($smac_store_id, $smac_stores) && $smac_form_data['activate_popup']) {
      $this->load->model('ocdevwizard/smart_abandoned_cart');
      $smac_visitor_data = $this->model_ocdevwizard_smart_abandoned_cart->getRecord($this->session->getId());

      if (empty($smac_visitor_data['email']) && !$this->customer->isLogged() && !isset($this->request->cookie['ocdevwizard_smac'])) {
        $json['modules']['smart_abandoned_cart'] = 'smart_abandoned_cart';
      }
    }
    // end: OCdevWizard SMAC

    if (!isset($json['modules']['smart_cart']) && !isset($json['modules']['smart_popup_cart_pro']) && !isset($json['modules']['smart_popup_cart_pro_plus']) && !isset($json['modules']['smart_abandoned_cart'])) {
      // start: OCdevWizard SMCHUP
      $smchup_form_data         = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_form_data', (int)$this->config->get('config_store_id'));
      $smchup_upsell_data       = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_upsell_data', (int)$this->config->get('config_store_id'));
      $smchup_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
      $smchup_customer_groups   = isset($smchup_form_data['customer_groups']) ? $smchup_form_data['customer_groups'] : array();

      if (isset($smchup_form_data['activate']) && $smchup_form_data['activate'] && $smchup_form_data['activate_popup_widget'] && $smchup_upsell_data && in_array($smchup_customer_group_id, $smchup_customer_groups)) {
        $json['modules']['smart_checkout_upsell_pro'] = 'smart_checkout_upsell_pro';
      }
      // end: OCdevWizard SMCHUP

      // start: OCdevWizard SMCHUPP
      $smchupp_form_data         = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_plus_form_data', (int)$this->config->get('config_store_id'));
      $smchupp_upsell_data       = $this->model_extension_ocdevwizard_ocdevwizard_setting->getSettingData('smart_checkout_upsell_pro_plus_upsell_data', (int)$this->config->get('config_store_id'));
      $smchupp_customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
      $smchupp_customer_groups   = isset($smchupp_form_data['customer_groups']) ? $smchupp_form_data['customer_groups'] : array();

      if (isset($smchupp_form_data['activate']) && $smchupp_form_data['activate'] && $smchupp_form_data['activate_popup_widget'] && $smchupp_upsell_data && in_array($smchupp_customer_group_id, $smchupp_customer_groups)) {
        $json['modules']['smart_checkout_upsell_pro_plus'] = 'smart_checkout_upsell_pro_plus';
      }
      // end: OCdevWizard SMCHUPP
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
}
