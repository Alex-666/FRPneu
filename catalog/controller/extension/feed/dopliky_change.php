<?php

class ControllerExtensionFeedDoplikyChange extends Controller
{

    public function index()
    {
        $this->load->model('catalog/category');

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $query = $this->db->query("SELECT p.product_id FROM  oc_product p LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '2' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '0' GROUP BY p.product_id ORDER BY (p.price) ASC, LCASE(pd.name)");
        foreach ($query->rows as $result_one) {
            $products_id[$result_one["product_id"]] = $result_one;
        }
        $categories = $this->model_catalog_category->getCategories("1715");

        foreach ($categories as $result_two) {

            $categories_one[$result_two["category_id"]] = $result_two;
        }

        $orig_categories = $this->model_catalog_category->getCategories("375");
        $count = 0;


        foreach ($products_id as $product_id) {
            //$product = $this->model_catalog_product->getProduct($product_id["product_id"]);
            /*
            var_dump($product['model']);
            continue;*/

            //if ($product['image'] != '') {
            $product_category = $this->model_catalog_product->getCategories($product_id["product_id"]);

            /*var_dump($product_category[0]["category_id"]);
            var_dump("---------------------------------------");

            */
            //var_dump($product_category[0]["category_id"]);die;
            foreach ($categories_one as $result_tree) {
                $categories_2 = $this->model_catalog_category->getCategories($result_tree["category_id"]);
                if ($categories_2) {
                    foreach ($categories_2 as $result_four) {
                        //$categories_two[$result_four['category_id']] = $result_four;
                        /*$count++;
                        If ($count > 1500) {
                            die;
                        }
                        var_dump($product_category[0]["category_id"]);
                        var_dump($result_four["category_id"]);
                        continue;*/
                        if ($product_category[0]["category_id"] === $result_four["category_id"]) {

                            foreach ($orig_categories as $orig_result) {
                                //var_dump($orig_result["name"]);
                                //var_dump($result_four["name"]);
                                if ($orig_result["name"] === $result_tree["name"]) {
                                    $orig_categories_2 = $this->model_catalog_category->getCategories($orig_result["category_id"]);

                                    foreach ($orig_categories_2 as $orig_result_two) {
                                        //$orig_categories_two[$orig_result_two['category_id']] = $orig_result_two;
                                        if ($orig_result_two["name"] === $result_four["name"]) {
                                            $count++;
                                            //$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id["product_id"] . "'");
                                            //$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id["product_id"] . "', category_id = '" . (int)$orig_result_two["category_id"] . "'");
                                            $this->db->query("UPDATE " . DB_PREFIX . "product_to_category SET category_id = '" . (int)$orig_result_two["category_id"] . "' WHERE product_id = '" . (int)$product_id["product_id"] . "'");

                                            $del_categories_id[] = $orig_result_two["category_id"];
                                            $move_product_id[] = $product_id["product_id"];

                                            /*var_dump($orig_result_two);
                                            var_dump('-----------------------------------');
                                            var_dump($result_four);
                                            var_dump('++++++++++++++++++++++++++++++++++++++');
                                            var_dump($product_category);
                                            var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=');
                                            var_dump($product);
                                            var_dump('======================================');
                                            var_dump($product_id);
                                            //$this->cache->delete('product');
                                            //die;
                                            */
                                        }
                                    }
                                }
                            }
                        }
                    }

                } else {
                    /*var_dump($result_tree["category_id"]);
                    var_dump('--------------------------------');
                    var_dump($product_category[0]["category_id"]);
                    die;*/
                    if ($product_category[0]["category_id"] === $result_tree["category_id"]) {

                        foreach ($orig_categories as $orig_result) {
                            //var_dump($orig_result["name"]);
                            //var_dump($result_tree["name"]);
                            if ($orig_result["name"] === $result_tree["name"]) {
                                $orig_categories_2 = $this->model_catalog_category->getCategories($orig_result["category_id"]);

                                $count++;
                                //$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id["product_id"] . "'");
                                //$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id["product_id"] . "', category_id = '" . (int)$orig_result_two["category_id"] . "'");
                                $this->db->query("UPDATE " . DB_PREFIX . "product_to_category SET category_id = '" . (int)$orig_result["category_id"] . "' WHERE product_id = '" . (int)$product_id["product_id"] . "'");

                                $del_categories_id[] = $orig_result["category_id"];
                                $move_product_id[] = $product_id["product_id"];

                                /*var_dump($orig_result_two);
                                var_dump('-----------------------------------');
                                var_dump($result_four);
                                var_dump('++++++++++++++++++++++++++++++++++++++');
                                var_dump($product_category);
                                var_dump('-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=');
                                var_dump($product);
                                var_dump('======================================');
                                var_dump($product_id);
                                //$this->cache->delete('product');
                                //die;
                                */
                            }
                        }
                    }
                }
            }
            //}
        }

        /*foreach ($del_categories_id as $del_result){
        if ($del_result){
            $this->admin_model_catalog_category->deleteCategory($del_result);
        }
        }*/

        $basedir = (realpath(dirname(__FILE__))) . '/../../../../';
        $file = $basedir . 'move_product_id.txt';
        $fp = fopen($file, "w9");
        fwrite($fp, $move_product_id);
        fclose($fp);
        $file_ = $basedir . 'del_categories_id.txt';
        $fp_ = fopen($file_, "w9");
        fwrite($fp_, $del_categories_id);
        fclose($fp_);

        echo 'END.';
        echo $count;
    }
}

?>
