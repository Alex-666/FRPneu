<?php
class ControllerExtensionFeedJRZboziCz extends Controller {
    private $error = array();
    private $zbozi = array();
    private $exclude = array();
    private $depth = 0;
    
    public function index() {
        $this->load->language('extension/feed/jr_zbozi_cz');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->model_setting_setting->editSetting('feed_jr_zbozi_cz', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
        }
        
        $data['token'] = $this->session->data['user_token'];
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/feed/jr_zbozi_cz', 'user_token=' . $this->session->data['user_token'], true)
        );
        
        $data['feed_jr_zbozi_cz_delivery_date'] = array();
        
        if (isset($this->request->post['feed_jr_zbozi_cz_delivery_date'])) {
            $data['feed_jr_zbozi_cz_delivery_date'] = $this->request->post['feed_jr_zbozi_cz_delivery_date'];
        } elseif ($this->config->get('feed_jr_zbozi_cz_delivery_date')) {
            $data['feed_jr_zbozi_cz_delivery_date'] = $this->config->get('feed_jr_zbozi_cz_delivery_date');
        }
        
        $this->load->model('localisation/stock_status');
        
        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
        
        $data['action'] = $this->url->link('extension/feed/jr_zbozi_cz', 'user_token=' . $this->session->data['user_token'], 'SSL');
        
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);
        
        if (isset($this->request->post['feed_jr_zbozi_cz_status'])) {
            $data['feed_jr_zbozi_cz_status'] = $this->request->post['feed_jr_zbozi_cz_status'];
        } else {
            $data['feed_jr_zbozi_cz_status'] = $this->config->get('feed_jr_zbozi_cz_status');
        }
        
        if (isset($this->request->post['feed_jr_zbozi_cz_variants'])) {
            $data['feed_jr_zbozi_cz_variants'] = $this->request->post['feed_jr_zbozi_cz_variants'];
        } else {
            $data['feed_jr_zbozi_cz_variants'] = $this->config->get('feed_jr_zbozi_cz_variants');
        }
        
        $data['data_feed']  = HTTPS_CATALOG . 'zbozi.xml';
        $data['create_xml'] = HTTPS_CATALOG . 'zbozi.xml';
        
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/feed/jr_zbozi_cz', $data));
    }
    
    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/feed/jr_zbozi_cz')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>