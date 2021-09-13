<?php
class ControllerJournal2Search extends Controller {

    private static $SHOW_PRICE = true;
    private static $SHOW_IMAGES = true;

    protected $data = array();

    protected function render() {
        if (version_compare(VERSION, '2.2', '<')) {
            $this->template = $this->config->get('config_template') . '/template/' . $this->template;
        }

        $this->template = str_replace($this->config->get('config_template') . '/template/' . $this->config->get('config_template') . '/template/', $this->config->get('config_template') . '/template/', $this->template);

        if (version_compare(VERSION, '3', '>=')) {
            return $this->load->view(str_replace('.tpl', '', $this->template), $this->data);
        }

        return Front::$IS_OC2 ? $this->load->view($this->template, $this->data) : parent::render();
    }

    public function __construct($reg) {
        parent::__construct($reg);
    }

    public function index() {
        $this->load->model('journal2/search');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $json = array('results' => array());

        if(isset($this->request->get['search'])) {

            $search_request = $this->request->get['search'];
            $search_exploded = false;

            $pos_l = strpos($search_request, 'letn');
            $pos_n = strpos($search_request, 'naklad');
            $pos_z = strpos($search_request, 'zemědělské');
            $pos_p = strpos($search_request, 'průmyslové');
            $pos_c = strpos($search_request, 'celoro');
            $pos_a = strpos($search_request, 'agro');
            $pos_zi = strpos($search_request, 'zimn');            

            if ($pos_l === false) {
                if ($pos_z === false) {
                    if ($pos_p === false) {
                        if ($pos_c === false) {
                            if ($pos_a === false) {
                               if ($pos_zi === false) {
                                   if ($pos_n === false) {
                                            
                                        } else {
                                          $search_exploded = true;
                                          $category_id =  '366'; 
                                        }       
                                    } else {
                                      $search_exploded = true;
                                      $category_id = '303';  
                                    }                 
                            } else {
                              $search_exploded = true;  
                            }                            
                        } else {
                          $search_exploded = true;
                          $category_id = '383';  
                        }                        
                    } else {
                      $search_exploded = true;
                      $category_id = '2004';  
                    }                    
                } else {
                 $search_exploded = true;
                 $category_id = '2001';   
                }
            } else {
                $search_exploded = true;
                $category_id = '297';
            }

            $search_result = explode(' ', $search_request);

       // $shiny = array("letn", "naklad", "zemědělské", "průmyslové", "celoro", "agro", "zimn");

        //if (isset($search_result[1]) && in_array($search_result[1], $shiny)) {
            if ($search_exploded) {
                $search_request = $search_result[0];
            } else {
                $category_id = false;
            }
       // }

          /*  var_dump('se'.$search_exploded);
            var_dump('re'.$search_request);
            var_dump('ca'.$category_id); */

            $results = $this->model_journal2_search->search($search_request, $this->journal2->settings->get('autosuggest_limit', 0), $this->journal2->settings->get('search_autocomplete_include_description', '1') === '1', $category_id);

            $image_width    = $this->journal2->settings->get('autosuggest_product_image_width', 50);
            $image_height   = $this->journal2->settings->get('autosuggest_product_image_height', 50);
            $image_type     = $this->journal2->settings->get('autosuggest_product_image_type', 'fit');

            foreach($results as $result) {
                $result = $this->model_catalog_product->getProduct($result['product_id']);
                if (self::$SHOW_IMAGES) {
                    $image = Journal2Utils::resizeImage($this->model_tool_image, $result['image'], $image_width, $image_height, $image_type);
                } else {
                    $image = null;
                }

                if (self::$SHOW_PRICE && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) {
                    $price = Journal2Utils::currencyFormat($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = null;
                }

                if (self::$SHOW_PRICE && (float)$result['special']) {
                    $special = Journal2Utils::currencyFormat($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = null;
                }

                $json['results'][] = array(
                    'name'  => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
                    'url'   => htmlspecialchars_decode($this->url->link('product/product', '&product_id=' . $result['product_id'])),
                    'image' => $image,
                    'price' => $price,
                    'special' => $special
                );
            }

            if ($json['results']) {
                $json['view_more_text'] = $this->journal2->settings->get('autosuggest_view_more_text', 'View More');
                if (VERSION === '1.5.4' || VERSION === '1.5.4.1') {
                    $json['view_more_url'] = $this->url->link('product/search', '&filter_name=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8')));
                } else {
                    $json['view_more_url'] = $this->url->link('product/search', '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8')) . ($this->journal2->settings->get('search_autocomplete_include_description', '1') === '1' ? '&description=true' : ''));
                }
            } else {
                $this->language->load('product/search');
                $json['view_more_text'] = $this->language->get('text_empty');
                $json['view_more_url'] = '';
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
