<?php

class ControllerExtensionFeedCronheurekaVO extends Controller
{

    private $categories = array();
    private $top;

    public function build_cat()
    {
        $this->load->model('catalog/category');

        $results = $this->model_catalog_category->getCategories(0);

        $cats = array(139, 156);

        foreach ($results as $result) {
            if (in_array($result['category_id'], $cats)) {

                $this->top = $result['category_id'];


                $this->categories[$result['category_id']] = array(
                    'category_id' => $result['name'],
                    'name' => $result['category_id'],
                    'top' => $this->top
                );


                $this->getCategories($result['category_id']);

            }
        };

    }


    protected function getCategories($category_id)
    {

        $results = $this->model_catalog_category->getCategories($category_id);

        foreach ($results as $result) {

            $this->categories[$result['category_id']] = array(
                'category_id' => $result['name'],
                'name' => $result['category_id'],
                'top' => $this->top
            );

            $this->getCategories($result['category_id']);

        }

    }

    public function getParentId($category_id)
    {
        $query = $this->db->query("SELECT `parent_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '$category_id'");
        if ($query->num_rows > 0) {
            return $query->row['parent_id'];
        }
        return 0;
    }

    public function index()
    {
        $this->config->set('config_customer_group_id', 5);
        $this->build_cat();


        $output = '<?xml version=\'1.0\' encoding=\'UTF-8\' ?>';
        $output .= '<SHOP>';

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');
        $this->load->model('tool/image');


        $products = $this->model_catalog_product->getProducts();
        foreach ($products as $product) {

            if ($product['image'] && trim($product['model']) != '') {

                $output .= '<SHOPITEM>';
                $output .= '<ITEM_ID>' . $product['product_id'] . '</ITEM_ID>';
                $output .= '<EAN>' . $product['ean'] . '</EAN>';

                if ($product['quantity']) {
                    $output .= '<DELIVERY_DATE>3</DELIVERY_DATE>';
                } else {
                    if ($product['heureka_delivery_date'] > 0) {
                        $output .= '<DELIVERY_DATE>' . $product['heureka_delivery_date'] . '</DELIVERY_DATE>';
                    } else {
                        $output .= '<DELIVERY_DATE>5</DELIVERY_DATE>';
                    }
                }

                $output .= '<PRODUCTNAME><![CDATA[ ' . $product['name'] . ' ]]></PRODUCTNAME>';
                $output .= '<DESCRIPTION><![CDATA[ ' . $product['description'] . ' ]]></DESCRIPTION>';
                $output .= '<URL>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</URL>';
                $output .= '<ITEM_TYPE>new</ITEM_TYPE>';
                $output .= '<MANUFACTURER><![CDATA[ ' . $product['manufacturer'] . ' ]]></MANUFACTURER>';
                $output .= '<STOCK>' . $product['quantity'] . '</STOCK>';

                $img = urlencode($this->model_tool_image->resize($product['image'], 500, 500));
                $img = str_replace('%3A', ':', $img);
                $img = str_replace('%2F', '/', $img);
                $img = str_replace('+', '%20', $img);

                $output .= '<IMGURL>' . $img . '</IMGURL>';

                if ($product['special']) {
                    $output .= '<PRICE_VAT>' . $this->tax->calculate($product['special'], $product['tax_class_id']) . '</PRICE_VAT>';
                    $output .= '<PRICE>' . $product['special'] . '</PRICE>';
                } else {
                    $output .= '<PRICE_VAT>' . $this->tax->calculate($product['price'], $product['tax_class_id']) . '</PRICE_VAT>';
                    $output .= '<PRICE>' . $product['price'] . '</PRICE>';
                }

                $categories = $this->model_catalog_product->getCategories($product['product_id']);
                if ($categories) {
                    $category = $categories[0];
                    if (isset($category['category_id'])) {
                        $heureka_category = 'Heureka.cz';
                        $heureka_category_id = 0;


                        $parent = $this->getParentId($category['category_id']);
                        $category_description = $this->model_catalog_category->getCategory($category['category_id']);

                        if (!empty($this->categories[$category['category_id']])) {
                            $category_description['category_id'] = $this->categories[$category['category_id']]['top'];
                        }


                        /*
                        if ($parent != 0){
                            do {

                                $category_description = $this->model_catalog_category->getCategory($parent);
                                    if ($category_description){
                                        $parent = $this->getParentId($category_description['category_id']);
                                    };

                            } while ($parent != 0);

                        }
                        */

                        switch ($category_description['category_id']) {
                            case '139':
                                $heureka_category = 'Heureka.cz | Auto-moto | Pneumatiky';
                                $heureka_category_id = 972;
                                break;
                            case '156':
                                $heureka_category = 'Heureka.cz | Auto-moto | Alu kola';
                                $heureka_category_id = 1471;
                                break;
                            default:
                                $heureka_category = $category_description['name'];
                                break;
                        }

                        $output .= '<CATEGORYTEXT><![CDATA[ ' . $heureka_category . ' ]]></CATEGORYTEXT>';
                        $output .= '<ITEMGROUP_ID>' . $heureka_category_id . '</ITEMGROUP_ID>';
                    }
                }
                $attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);

                foreach ($attributes as $attrib) {
                    foreach ($attrib["attribute"] as $att) {
                        $output .= "<PARAM>\n";
                        $output .= "<NAME><![CDATA[" . $att["name"] . "]]></NAME>\n";
                        $output .= "<VALUE><![CDATA[" . $att["text"] . "]]></VALUE>\n";
                        $output .= "</PARAM>\n";
                    }
                }

                $output .= '</SHOPITEM>';
            }
        }

        $output .= '</SHOP>';

        $basedir = (realpath(dirname(__FILE__))) . '/../../../../';
        $file = $basedir . 'zbozi_vo.xml';
        $file2 = $basedir . 'pohoda_mo.xml';
        $fp = fopen($file, "w9");
        $fp2 = fopen($file2, "w9");
        fwrite($fp, $output);
        fclose($fp);
        fwrite($fp2, $output);
        fclose($fp2);
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($output);

        $this->config->set('config_customer_group_id', 1);

        //  $this->response->addHeader('Content-Type: text/xml;');
        //    $this->response->setOutput($output);
    }
}

?>
