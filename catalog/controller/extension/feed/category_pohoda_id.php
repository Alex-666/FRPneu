<?php

class ControllerExtensionFeedCategoryPohodaId extends Controller
{

    public function index()
    {
        /*ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '500M');*/

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $query = $this->db->query("SELECT p.product_id FROM  " . DB_PREFIX . "product p");
        foreach ($query->rows as $result_one) {
            $products_id[$result_one["product_id"]] = $result_one;
        }
        $categories = $this->model_catalog_category->getCategories("1704");
        //$categories = $this->model_catalog_category->getCategories("1411");

        foreach ($categories as $result_two) {

            $categories_one[$result_two["category_id"]] = $result_two;
        }

        $orig_categories = $this->model_catalog_category->getCategories("139");
        //$orig_categories = $this->model_catalog_category->getCategories("139");

        $count = 0;


        foreach ($categories_one as $first_category) {
            $categories_2 = $this->model_catalog_category->getCategories($first_category["category_id"]);

            foreach ($orig_categories as $first_orig) {
                if ($first_category["name"] == $first_orig["name"]) {
                    if ($first_category["pohoda_id"]) {
                        $count++;
                        $this->db->query("UPDATE " . DB_PREFIX . "category SET pohoda_id = '" . $first_category["pohoda_id"] . "' WHERE category_id = '" . $first_orig["category_id"] . "'");
                        $this->db->query("UPDATE " . DB_PREFIX . "category SET pohoda_id = null WHERE category_id = '" . $first_category["category_id"] . "'");
                        $change_cat[] = $first_orig["category_id"];
                        $clear_cat[] = $first_category["category_id"];
                    }
                    foreach ($categories_2 as $two_category) {
                        $orig_categories_2 = $this->model_catalog_category->getCategories($first_orig["category_id"]);
                        foreach ($orig_categories_2 as $orig_two) {
                            if ($two_category["name"] == $orig_two["name"]) {
                                if ($two_category["pohoda_id"]) {
                                    $count++;
                                    $this->db->query("UPDATE " . DB_PREFIX . "category SET pohoda_id = '" . $two_category["pohoda_id"] . "' WHERE category_id = '" . $orig_two["category_id"] . "'");
                                    $this->db->query("UPDATE " . DB_PREFIX . "category SET pohoda_id = null WHERE category_id = '" . $two_category["category_id"] . "'");
                                    $change_cat[] = $orig_two["category_id"];
                                    $clear_cat[] = $two_category["category_id"];
                                }

                            }
                        }
                    }

                }
            }

            /*foreach ($categories_one as $result_tree) {
                $categories_2 = $this->model_catalog_category->getCategories($result_tree["category_id"]);
                foreach ($categories_2 as $result_four) {

                    foreach ($product_categories as $product_category) {
                        if ($product_category["category_id"] != 1330) {
                            if ($product_category["category_id"] == $result_four["category_id"]) {

                                foreach ($orig_categories as $orig_result) {
                                    if (mb_strtolower($orig_result["name"]) === mb_strtolower($result_tree["name"])) {
                                        $orig_categories_2 = $this->model_catalog_category->getCategories($orig_result["category_id"]);

                                        foreach ($orig_categories_2 as $orig_result_two) {
                                            //$orig_categories_two[$orig_result_two['category_id']] = $orig_result_two;
                                            if (mb_strtolower($orig_result_two["name"]) === mb_strtolower($result_four["name"])) {
                                                $count++;
                                                $this->db->query("UPDATE " . DB_PREFIX . "product_to_category SET category_id = '" . (int)$orig_result_two["category_id"] . "' WHERE product_id = '" . (int)$product_id["product_id"] . "' AND category_id = '" . $product_category["category_id"] . "'");

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }*/
        }
        var_dump($change_cat);
        var_dump("---------------------------------");
        var_dump($clear_cat);


        echo 'END.';
        echo $count;
    }
}

?>
