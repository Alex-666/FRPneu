<?php
class ModelJournal2Search extends Model {

    public function search($data, $limit = 5, $include_description = false, $category_id = false) {

        $params = array(
            'filter_name'         => $data,
            'filter_tag'          => $data,
            'sort'                => 'p.location DESC, p.sort_order',
            'order'               => 'ASC',
            'start'               => 0,
            'limit'               => $limit
        );

        if ($include_description) {
            $params['filter_description'] = $data;
        }

        if ($category_id) {
            $params['filter_category_id'] = $category_id;
            $params['filter_sub_category'] = true; 
        }

        $this->load->model('catalog/product');

        return $this->model_catalog_product->getProducts($params);
    }
}
?>
