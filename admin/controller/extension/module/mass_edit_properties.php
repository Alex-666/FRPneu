<?php
//error_reporting(0);
class ControllerExtensionModuleMassEditProperties extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/mass_edit_properties');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                        echo $this->request->post;
            //$this->session->data['success'] = $this->language->get('text_success');

            //$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/mass_edit_properties', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/mass_edit_properties/updateProducts', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

                if (isset($this->request->post['product_from_value'])) {
                    $data['product_from_value'] = $this->request->post['product_from_value'];
                } else {
                    $data['product_from_value'] = '';
                }

        $this->load->model('catalog/category');

        $data['categories'] = $this->model_catalog_category->getCategories(0);


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
                $data['user_token'] = $this->session->data['user_token'];

                $data['button_select_all_properties'] = $this->language->get('button_select_all_properties');
                $data['button_edit_selected'] = $this->language->get('button_edit_selected');
                $data['button_deselect_all_properties'] = $this->language->get('button_deselect_all_properties');
                $data['button_select_all_products'] = $this->language->get('button_select_all_products');
                $data['button_deselect_all_products'] = $this->language->get('button_deselect_all_products');
                $data['button_edit_selected'] = $this->language->get('button_edit_selected');
                $data['button_delete_selected'] = $this->language->get('button_delete_selected');

        $this->response->setOutput($this->load->view('extension/module/mass_edit_properties', $data));
    }

        public function endKey($array){
            end($array);
            return key($array);
        }
        public function getAll(){
            echo $this->request->get['category_id'];
            $json = array();
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');
            $filter_data = array('filter_category_id' => (int)$this->request->get['category_id']);
            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                    $option_data = array();
                    //$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);
                    $xtras = $this->model_catalog_product->getProduct($result['product_id']);
                    $product_seo_url = $this->model_catalog_product->getProductSeoUrls($result['product_id']);

                    //foreach ($product_options as $product_option) {
                            //$option_info = $this->model_catalog_option->getOption($product_option['option_id']);
                            //if ($option_info) {
                                    //$product_option_value_data = array();
                                    //foreach ($product_option['product_option_value'] as $product_option_value) {
                                            //$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
                                            //if ($option_value_info) {
                                                    //$product_option_value_data[] = array(
                                                            //'product_option_value_id' => $product_option_value['product_option_value_id'],
                                                            //'option_value_id'         => $product_option_value['option_value_id'],
                                                            //'name'                    => $option_value_info['name'],
                                                            //'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                                                            //'price_prefix'            => $product_option_value['price_prefix']
                                                    //);
                                            //}
                                    //}
                                    //$option_data[] = array(
                                            //'product_option_id'    => $product_option['product_option_id'],
                                            //'product_option_value' => $product_option_value_data,
                                            //'option_id'            => $product_option['option_id'],
                                            //'name'                 => $option_info['name'],
                                            //'type'                 => $option_info['type'],
                                            //'value'                => $product_option['value'],
                                            //'required'             => $product_option['required']
                                    //);
                            //}
                    //}
                    $p_array = array(
                            'product_id' => $result['product_id'],
                            'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                            'model'      => $result['model'],
                            'option'     => $option_data,
                            'xtras' => $xtras,
                            //'product_seo_url' => $product_seo_url,
                            //'keyword' => '',
                            //'tag' => $xtras['tag'],
                            //'meta_title' => $xtras['meta_title'],
                            //'meta_description' => $xtras['meta_description'],
                            //'meta_keyword' => $xtras['meta_keyword'],
                            //'points' => $xtras['points'],
                            //'subtract' => $xtras['subtract'],
                            //'minimum' => $xtras['minimum'],
                            //'quantity' => $xtras['quantity'],
                            //'sort_order' => $xtras['sort_order'],
                            'description' => htmlentities($xtras['description']),
                            //'price'      => $result['price']
                    );
                    if(count($product_seo_url) >0){
                        foreach($product_seo_url as $store=>$language){
                            foreach($language as $language_id=>$keyword){
                                $p_array['keyword'] = $keyword;
                            }
                        }
                    }
                    $json[] = $p_array;
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function editProduct($product_id, $data){
            $response = array('product_id'=>$product_id,'status'=>0);
            $response['postedurl'] = $data['keyword'];
            if(isset($data['keyword']) && !empty($data['keyword'])){
                $this->load->model('catalog/product');

                $product_seo_url = $this->model_catalog_product->getProductSeoUrls($product_id);
                $stores = $this->model_catalog_product->getProductStores($product_id);
                if($product_seo_url){//if seo url exists
                    foreach($product_seo_url as $store => $language){
                        foreach($language as $language_id => $keyword){
                            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
                            if($this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id={$store},language_id={$language_id},query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'")){
                                $response['code'] = 1;
                            }
                        }
                    }
                } else{//if not, add the seo url
                    foreach($stores as $store_id){
                        if($this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id=".(int)$store_id.",language_id={$data['language_id']},query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'")){
                            $response['code'] = 1;
                        }
                    }
                }
            }
            if(isset($data['detail_properties']) && !empty($data['detail_properties'])){
                $detail_query = "UPDATE " . DB_PREFIX . "product SET";
                foreach($data['detail_properties'] as $key=>$val){
                    $detail_query .= " ".$key." = '{$this->db->escape($val)}'";
                    if($key != $this->endKey($data['detail_properties'])){
                        $detail_query .= ',';
                    }
                }
                $detail_query.=", date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'";
                if($this->db->query($detail_query)){
                    $response['details'] = 1;
                }
            }
            if(isset($data['description_properties']) && !empty($data['description_properties'])){
                $description_query = "UPDATE " . DB_PREFIX . "product_description SET";
                foreach($data['description_properties'] as $key=>$val){
                    $description_query .= " ".$key." = '{$this->db->escape($val)}'";
                    if($key != $this->endKey($data['description_properties'])){
                        $description_query .= ',';
                    }
                }
                $description_query .= " WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$data['language_id']."'";
                if($this->db->query($description_query)){
                    $response['description'] = 1;
                }
            }
            return $response;
        }
        public function updateProducts(){
            $response = array();
            $this->language->load('extension/module/mass_edit_properties');
            $post = $this->request->post;
            if (empty($post['products'])) {
                return;
            }
            if(is_array($post['products'])){
                foreach($post['products'] as $product){
                    $response[] = $this->editProduct($product['id'], $product);
                }
            }
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($response));
        }
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/mass_edit_properties')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
