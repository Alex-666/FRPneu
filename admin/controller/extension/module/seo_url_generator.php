<?php

class ControllerExtensionModuleSeoUrlGenerator extends Controller
{
    private $error = array();

    public function install()
    {
        // enable the module and set default settings
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('seo_url_generator', array(
            'seo_url_generator_categories_template' => '[category_name]-[category_id]',
            'seo_url_generator_products_template' => '[product_name]-[product_id]',
            'seo_url_generator_manufacturers_template' => '[manufacturer_name]-[manufacturer_id]',
           
        ));
        $this->model_setting_setting->editSetting('module_seo_url_generator', array(
            'module_seo_url_generator_status' => 1,
            'status' => 1,
        ));
    }

    public function index()
    {
        $this->load->language('extension/module/seo_url_generator');
        $this->document->setTitle($this->language->get('page_title'));

        $this->load->model('extension/module/seo_url_generator');
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$this->load->model('setting/setting');
        $store_settings_config = $this->model_setting_setting->getSetting("config", $this->config->get('config_store_id'));
        $lang_suffix  = $store_settings_config['config_language']; // Тут язык по-умолчанию
		$lang_id_suffix  = (int)$this->config->get('config_language_id');
		$data['lan_code'] = $store_settings_config['config_language'];
		$data['lan_id'] = (int)$this->config->get('config_language_id');

$products_suffix = $lang_suffix;
$categories_suffix = $lang_suffix;
$manufacturers_suffix = $lang_suffix;
$do_transliteration = 1;


		
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            if (isset($this->request->post['categories'])) {
                $this->model_extension_module_seo_url_generator->generateCategories($this->request->post['categories_template'], $categories_suffix, $this->request->post['overwrite_categories'] == 'overwrite', $do_transliteration);
            }

            if (isset($this->request->post['products'])) {
                $this->model_extension_module_seo_url_generator->generateProducts($this->request->post['products_template'], $products_suffix, $this->request->post['overwrite_products'] == 'overwrite', $do_transliteration);
            }
			
            if (isset($this->request->post['manufacturers'])) {
                $this->model_extension_module_seo_url_generator->generateManufacturers($this->request->post['manufacturers_template'], $manufacturers_suffix, $this->request->post['overwrite_manufacturers'] == 'overwrite', $do_transliteration);
            }
			
            $this->model_setting_setting->editSetting('seo_url_generator', array(
                'seo_url_generator_categories_template' => $this->request->post['categories_template'],
                'seo_url_generator_products_template' => $this->request->post['products_template'],
                'seo_url_generator_manufacturers_template' => $this->request->post['manufacturers_template'],

            ));
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['success'] = $this->language->get('text_success');
            }
        }


        if (isset($this->request->post['categories_template'])) {
            $data['categories_template'] = $this->request->post['categories_template'];
        } else {
            $data['categories_template'] = $this->config->get('seo_url_generator_categories_template');
        }

        if (isset($this->request->post['products_template'])) {
            $data['products_template'] = $this->request->post['products_template'];
        } else {
            $data['products_template'] = $this->config->get('seo_url_generator_products_template');
        }

        if (isset($this->request->post['manufacturers_template'])) {
            $data['manufacturers_template'] = $this->request->post['manufacturers_template'];
        } else {
            $data['manufacturers_template'] = $this->config->get('seo_url_generator_manufacturers_template');
        }

        
        $data['languages'] = $this->model_extension_module_seo_url_generator->getLanguages();

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
			'href' => $this->url->link('extension/module/seo_url_generator', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/seo_url_generator', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true); 
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/seo_url_generator', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/seo_url_generator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
} 