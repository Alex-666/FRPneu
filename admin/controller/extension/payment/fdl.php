<?php

class ControllerExtensionPaymentFdl extends Controller {

    private $error = array();

    public function index() {

        $this->load->language('extension/payment/fdl');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_fdl', $this->request->post);

            $this->session->data['success'] = $this->language->get('success');

            $this->install();

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        //just for labels/text strings
        $labels = array(
            'heading_title', 'edit', 'enabled', 'disabled', 'closeday', 'button_save',
            'button_cancel', 'confirmation_url', 'closing_url', 'fdl_order_id', 'issue_refund'
        );

        foreach ($labels as $label) {
            $data['text_' . $label] = $this->language->get($label);
        }

        //save data and assign labels for configuration fields
        $config_fields = array(
            'fdl_config_cert', 'fdl_sort_order', 'fdl_total', 'fdl_closeday_token',
            'fdl_config_certpass', 'fdl_config_server', 'fdl_total',
            'fdl_order_status_id', 'fdl_refund_status_id', 'fdl_status', 'sort_order'
        );

        foreach ($config_fields as $field) {
            $data['text_' . $field] = $this->language->get($field);
            if (isset($this->request->post['payment_'.$field])) {
                $data[$field] = $this->request->post['payment_'.$field];
            } elseif ($field === 'fdl_closeday_token' && $this->config->get('payment_fdl_closeday_token') === null) {
                $data[$field] = md5(uniqid() . microtime(true));
            } else {
                $data[$field] = $this->config->get('payment_'.$field);
            }
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            $this->session->data['success'] = '';
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        //breadcrumbs
        $bc = array(
            'text_home' => 'common/dashboard',
            'text_payment' => 'marketplace/extension',
            'heading_title' => 'extension/payment/fdl'
        );

        $data['breadcrumbs'] = array();
        foreach ($bc as $key => $val) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get($key),
                'href' => $this->url->link($val, 'user_token=' . $this->session->data['user_token'], 'SSL')
            );
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['action'] = $this->url->link('extension/payment/fdl', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['refund'] = $this->url->link('extension/payment/fdl/refund', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['confirmation_url'] = str_replace('/admin', '', $this->url->link('extension/payment/fdl/confirm', '', 'SSL'));
        $data['closing_url'] = str_replace('/admin', '', $this->url->link('extension/payment/fdl/close_day/&secret=' . $data['fdl_closeday_token'], '', 'SSL'));

        $this->response->setOutput($this->load->view('extension/payment/fdl', $data));
    }

    /**
     * Issue refund
     */
    public function refund() {

        $this->load->model('extension/payment/fdl');

        $order_info = $this->model_extension_payment_fdl->getOrder($this->request->post['order_id']);

        require_once('../catalog/controller/extension/payment/fdl_merchant.php');

        //calculate amount
        $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        $amount = $amount * 100;

        //reverse transaction
        $this->Merchant = new Merchant($this->config->get('payment_fdl_config_server') . ":8443/ecomm/MerchantHandler", $this->config->get('payment_fdl_config_cert'), $this->config->get('payment_fdl_config_certpass'), 1);
        $resp = $this->Merchant->reverse($order_info['transaction']['transaction_id'], $amount);

        //update order status
        $this->model_extension_payment_fdl->saveStatus($order_info['order_id'], 'refunded');


        $this->session->data['success'] = $this->language->get('Success');
        $this->response->redirect($this->url->link('extension/payment/fdl', 'user_token=' . $this->session->data['user_token'], 'SSL'));
    }

    /**
     * Set up table column for FDL transaction ID
     */
    public function install() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fdl` (
              `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `order_id` int(10) unsigned NOT NULL,
              `transaction_id` varchar(28) NOT NULL,
              `status` varchar(255) NULL,
              `date` datetime NOT NULL
            ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "fdl`;");
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/fdl')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}
