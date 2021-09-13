<?php

class ControllerExtensionFeedProduct extends Controller
{

    public function index()
    {
        $this->load->language('product/product');

        //$this->load->model('catalog/category');
        //$this->load->model('catalog/product');
        //$this->load->model('tool/image');

        $this->load->model('catalog/product');
        $this->load->model('journal2/product');

        $product_info = $this->model_catalog_product->getProduct("31521");
        var_dump($product_info);
        var_dump("\p");

        $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
        var_dump($data['price']);

        $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
        var_dump($data['special']);

        $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
        var_dump($data['tax']);
        var_dump('--------------------');
        var_dump($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
        var_dump($product_info['special']);
        var_dump($product_info['tax_class_id']);
        var_dump($this->config->get('config_tax'));
        var_dump($this->session->data['currency']);


    }
}

?>
