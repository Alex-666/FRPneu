PrCategory<?php
class ControllerExtensionShippingprcategory123 extends Controller {
    private $error = array();

    private $messages_index = array(
        'heading_title',
        'text_enabled',
        'text_disabled',
        'entry_cost',
        'entry_geo_zone',
        'entry_status',
        'entry_sort_order',
        'entry_title',
        'entry_quote_title',
        'entry_rate',
        'entry_country',
        'entry_zone',
        'entry_enabled_cities',
        'entry_disabled_cities',
        'text_confirm',
        'tab_general',
        'tab_zones',
        'tab_geozones',
        'button_save',
        'button_cancel',
        'button_remove',
        'button_add',
        'button_edit');

    private $messages_edit = array(
        'entry_rate',
        'entry_country',
        'entry_zone',
        'entry_enabled_cities',
        'entry_disabled_cities',
        'entry_enabled_cities_help',
        'entry_disabled_cities_help',
        'entry_category',
        'entry_cost',
        'entry_cost_basis',
        'entry_geo_zone',
        'text_all_category',
        'text_tariffs_manual',
        'button_save',
        'button_cancel',
        'button_remove',
        'button_add');

    public function index() {

        $this->language->load('extension/shipping/prcategory123');
        $this->load->model('extension/shipping/prcategory123');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('shipping_prcategory123', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
        }

        foreach ($this->messages_index as $key){
            $data[$key] = $this->language->get($key);
        }

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $data['error_quote_title'] = isset($this->error['quote_title']) ? $this->error['quote_title'] : '';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_shipping'),
            'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/shipping/prcategory123', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('extension/shipping/prcategory123', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);
        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        }
        else {
            $data['success'] = '';
        }

        if (isset($this->request->post['shipping_prcategory123_title'])) {
            $data['shipping_prcategory123_title'] = $this->request->post['shipping_prcategory123_title'];
        }
        elseif (!is_null($this->config->get('shipping_prcategory123_title'))) {
            $data['shipping_prcategory123_title'] = $this->config->get('shipping_prcategory123_title');
        }
        else {
            $data['shipping_prcategory123_title'] = $this->language->get('text_default_title');
        }

        if (isset($this->request->post['shipping_prcategory123_quote_title'])) {
            $data['shipping_prcategory123_quote_title'] = $this->request->post['shipping_prcategory123_quote_title'];
        }
        elseif (!is_null($this->config->get('shipping_prcategory123_quote_title'))) {
            $data['shipping_prcategory123_quote_title'] = $this->config->get('shipping_prcategory123_quote_title');
        }
        else {
            $data['shipping_prcategory123_quote_title'] = $this->language->get('text_default_quote_title');
        }

        if (isset($this->request->post['shipping_prcategory123_status'])) {
            $data['shipping_prcategory123_status'] = $this->request->post['shipping_prcategory123_status'];
        } else {
            $data['shipping_prcategory123_status'] = $this->config->get('shipping_prcategory123_status');
        }

        if (isset($this->request->post['shipping_prcategory123_sort_order'])) {
            $data['shipping_prcategory123_sort_order'] = $this->request->post['shipping_prcategory123_sort_order'];
        } else {
            $data['shipping_prcategory123_sort_order'] = $this->config->get('shipping_prcategory123_sort_order');
        }

        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $this->load->model('localisation/geo_zone');

        $prcategory123_zones = $this->model_extension_shipping_prcategory123->getZones();

        foreach ($prcategory123_zones as & $quote_zone) {
            if (!empty($quote_zone['country_id']) && $country = $this->model_localisation_country->getCountry($quote_zone['country_id'])) {
                $quote_zone['country_name'] = $country['name'];
            }
            else {
                $quote_zone['country_name'] = '';
            }

            if (!empty($quote_zone['zone_id']) && $zone = $this->model_localisation_zone->getZone($quote_zone['zone_id'])) {
                $quote_zone['zone_name'] = $zone['name'];
            }
            else {
                $quote_zone['zone_name'] = '';
            }
        }

        $data['prcategory123_zones'] = $prcategory123_zones;

        $prcategory123_geo_zones = $this->model_extension_shipping_prcategory123->getGeoZones();

        foreach ($prcategory123_geo_zones as & $quote_geo_zone) {

            if (!empty($quote_geo_zone['geo_zone_id']) && $zone = $this->model_localisation_geo_zone->getGeoZone($quote_geo_zone['geo_zone_id'])) {
                $quote_geo_zone['geo_zone_name'] = $zone['name'];
            }
            else {
                $quote_geo_zone['geo_zone_name'] = '';
            }
        }

        $data['prcategory123_geo_zones'] = $prcategory123_geo_zones;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/prcategory123', $data));
    }

    public function edit() {

        $this->language->load('extension/shipping/prcategory123');
        $this->load->model('extension/shipping/prcategory123');

        $this->document->setTitle($this->language->get('heading_title'));

        $zone_id = isset($this->request->get['zone_id']) ? (int)$this->request->get['zone_id'] : 0;
        $type = isset($this->request->get['type']) ? $this->request->get['type'] : '';

        if ($zone_id) {

            if ($type == 'zone') {
                $zone = $this->model_extension_shipping_prcategory123->getZone($zone_id);
            }
            elseif ($type == 'geozone') {
                $zone = $this->model_extension_shipping_prcategory123->getGeoZone($zone_id);
            }
        }
        else {
            $zone = false;
        }

        if (!$type || !in_array($type, array('zone', 'geozone')) || ($zone_id && !$zone)) {
            $this->response->redirect($this->url->link('extension/shipping/prcategory123', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateZone($type)) {

            if ($type == 'zone') {
                $this->model_extension_shipping_prcategory123->editPrZone($zone_id, $this->request->post['zone']);
            }
            else {
                $this->model_extension_shipping_prcategory123->editPrGeoZone($zone_id, $this->request->post['zone']);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/shipping/prcategory123', 'user_token=' . $this->session->data['user_token'], true));
        }

        if ($zone) {

            if ($type == 'zone') {
                $categories = $this->model_extension_shipping_prcategory123->getCategoriesForZone($zone_id);
            }
            else {
                $categories = $this->model_extension_shipping_prcategory123->getCategoriesForGeoZone($zone_id);
            }

            $tariffs = array();

            foreach ($categories as $category) {

                if (!isset($tariffs[$category['tariff_id']])) {
                    $tariffs[$category['tariff_id']] = array(
                        'rate' => $category['rate'],
                        'cost_basis' => $category['cost_basis'],
                        'cost' => $category['cost'],
                        'categories' => array()
                    );
                }

                $tariffs[$category['tariff_id']]['categories'][] = $category['category_id'];
            }

            $zone['tariffs'] = $tariffs;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
                'separator' => false
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_shipping'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true),
                'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/shipping/prcategory123', 'user_token=' . $this->session->data['user_token'], true),
                'separator' => ' :: '
        );

        foreach ($this->messages_edit as $key){
            $data[$key] = $this->language->get($key);
        }

        $data['text_form'] = $type == 'zone' ? $this->language->get('tab_zones') : $this->language->get('tab_geozones');

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $data['error_zone'] = isset($this->error['zone']) ? $this->error['zone'] : '';

        $data['action'] = $this->url->link('extension/shipping/prcategory123/edit&type=' . $type . '&zone_id=' . $zone_id, 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/shipping/prcategory123', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];

        $data['type'] = $type;

        if (isset($this->request->post['zone'])) {
            $data['zone'] = $this->request->post['zone'];
        }
        elseif ($zone) {
            $data['zone'] = $zone;
        }
        else {
            $data['zone'] = array('country_id' => 176, 'zone_id' => 0, 'enabled_cities' => '', 'disabled_cities' => '',
            'rate' => '', 'cost_basis' => '', 'cost' => '', 'geo_zone_id' => '');
        }

        $this->load->model('localisation/country');
        $this->load->model('localisation/geo_zone');
        $data['countries'] = $this->model_localisation_country->getCountries();
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/prcategory123_edit', $data));

    }

    public function delete_zone() {

        $this->language->load('extension/shipping/prcategory123');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/shipping/prcategory123')) {
            $json['error'] = $this->language->get('error_permission');
        }
        else {
            $this->load->model('extension/shipping/prcategory123');

            $zone_id = isset($this->request->get['zone_id']) ? (int)$this->request->get['zone_id'] : 0;
            $type = isset($this->request->get['type']) ? $this->request->get['type'] : '';

            if ($type == 'zone') {
                $this->model_extension_shipping_prcategory123->removePrZone($zone_id);
            }
            else if ($type == 'geozone') {
                $this->model_extension_shipping_prcategory123->removePrGeoZone($zone_id);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/prcategory123')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!trim($this->request->post['shipping_prcategory123_quote_title'])) {
            $this->error['quote_title'] = $this->language->get('error_required');
        }

        return !$this->error;
    }

    protected function validateZone($type) {

        if (!$this->user->hasPermission('modify', 'extension/shipping/prcategory123')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($type == 'zone') {
            if (empty($this->request->post['zone']['zone_id'])) {
                $this->error['zone'] = $this->language->get('error_required');
            }
        }

        return !$this->error;
    }
}
