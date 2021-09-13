<?php
class ControllerExtensionModuleSearchAuto extends Controller {
    private $error = array();
    private $module_name = 'module_search_auto';

    private $defaults = array(
        'tire_width' => '',
        'tire_height' => '',
        'tire_diameter' => '',
        'tire_season' => '',
        'tire_type' => '',
        'tire_loadindex' => '',
        'tire_speedindex' => '',
        'tire_category' => '',
        'tire_model' => '',
        'tire_status' => '',
        'disc_width' => '',
        'disc_diameter' => '',
        'disc_pcd' => '',
        'disc_dia' => '',
        'disc_et' => '',
        'disc_category' => '',
        'disc_status' => '',
        'et_range' => 2,
        'width_range_minus' => 0,
        'width_range_plus' => 0.5,
        'dia_range' => 0,
        'cache_models' => 1,
        'cache_years' => 0,
        'cache_mods' => 0,
        'status' => '',
    );
    private $messages = array(
        'heading_title',
        'tab_settings',
        'tab_license',
        'text_enabled',
        'text_disabled',
        'text_content_top',
        'text_content_bottom',
        'text_column_left',
        'text_column_right',
        'text_search_tire',
        'text_search_disc',
        'text_search_auto',
        'text_support',
        'text_support_contact',
        'text_settings_search',
        'text_settings_cache',
        'text_seo_hook',
        'text_seo_help',
        'text_cache_help',
        'text_none',
        'entry_tire_width',
        'entry_tire_height',
        'entry_tire_diameter',
        'entry_tire_season',
        'entry_tire_type',
        'entry_tire_loadindex',
        'entry_tire_speedindex',
        'entry_tire_category',
        'entry_tire_model',
        'entry_disc_width',
        'entry_disc_diameter',
        'entry_disc_pcd',
        'entry_disc_dia',
        'entry_disc_et',
        'entry_disc_category',
        'entry_et_range',
        'entry_width_range',
        'entry_dia_range',
        'entry_cache_models',
        'entry_cache_years',
        'entry_cache_mods',
        'entry_layout',
        'entry_position',
        'entry_forms',
        'entry_template',
        'entry_status',
        'entry_sort_order',
        'help_tire_width',
        'help_tire_height',
        'help_tire_diameter',
        'help_tire_season',
        'help_tire_type',
        'help_tire_loadindex',
        'help_tire_speedindex',
        'help_tire_category',
        'help_tire_model',
        'help_disc_width',
        'help_disc_diameter',
        'help_disc_pcd',
        'help_disc_dia',
        'help_disc_et',
        'help_disc_category',
        'help_et_range',
        'help_disc_width',
        'help_dia_range',
        'text_yes',
        'text_no',
        'button_save',
        'button_cancel',
        'button_add_module',
        'button_remove',
        'template_tabs',
        'template_row',
        'template_column');

    public function index() {
        $this->load->language('extension/module/search_auto');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting($this->module_name, $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
            
        }

        foreach ($this->messages as $key){
            $data[$key] = $this->language->get($key);
        }

        $this->load->model('catalog/attribute_group');
        $this->load->model('catalog/attribute');

        $attributes = array();

        $data = array(
            'sort'  => 'ag.sort_order',
            'order' => 'ASC',
            'start' => 0,
            'limit' => 100
        );

        $attr_groups = $this->model_catalog_attribute_group->getAttributeGroups($data);

        foreach ($attr_groups as $group) {
            $data = array(
                'filter_attribute_group_id' => $group['attribute_group_id'],
                'sort'  => 'a.sort_order',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 100
            );

            $items = array();

            $results = $this->model_catalog_attribute->getAttributes($data);

            foreach ($results as $result) {
                $items[] = array(
                    'attribute_id'    => $result['attribute_id'],
                    'attribute_name'  => $result['name'],
                );
            }

            $attributes[] = array(
                'group_id'   => $group['attribute_group_id'],
                'group_name' => $group['name'],
                'items'      => $items
            );
        }

        $data['attributes'] = $attributes;

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        } else {
            $data['error'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/search_auto', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/search_auto', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


        foreach ($this->defaults as $key=>$value){
            $key = $this->module_name.'_'.$key;
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = $this->config->get($key,  $value);
            }
        }

        $this->load->model('catalog/category');

        $data['categories'] = $this->model_catalog_category->getCategories(0);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/search_auto', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/search_auto')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        //else if(
            //$this->request->post['search_auto_module_setting']['disc']['category'] == '-' ||
            //$this->request->post['search_auto_module_setting']['disc']['width'] == '-' ||
            //$this->request->post['search_auto_module_setting']['disc']['diameter'] == '-' ||
            //$this->request->post['search_auto_module_setting']['disc']['pcd'] == '-' ||
            //$this->request->post['search_auto_module_setting']['disc']['dia'] == '-' ||
            //$this->request->post['search_auto_module_setting']['disc']['et'] == '-' ||
            //$this->request->post['search_auto_module_setting']['tire']['category'] == '-' ||
            //$this->request->post['search_auto_module_setting']['tire']['width'] == '-' ||
            //$this->request->post['search_auto_module_setting']['tire']['height'] == '-' ||
            //$this->request->post['search_auto_module_setting']['tire']['diameter'] == '-' ||
            //$this->request->post['search_auto_module_setting']['tire']['season'] == '-') {
            //$this->error['warning'] = $this->language->get('error_warning');
        //}

        return !$this->error;
    }
}
