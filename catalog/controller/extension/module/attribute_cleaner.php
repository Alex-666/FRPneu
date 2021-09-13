<?php
class ControllerExtensionModuleAttributeCleaner extends Controller {
    public function index() {

        $attr_id_to_clean = array(7, 12);

        foreach($attr_id_to_clean as $key => $value) {

            // Odstranit např. 55R16
            $sql1 = "SELECT * FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$value . "' AND text REGEXP '([0-9]+)R([0-9]+)'";
            $result1 = $this->db->query($sql1);
            foreach($result1->rows as $row1) {
                echo "Původní: " . $row1['text'] . "<br>";
                $new_value = preg_replace('/(.*)R(.*)/', '$1', $row1['text']);
                echo "Nová hodnota: " . $new_value . "<br>";
                $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '" . $this->db->escape($new_value) . "', value = '" . $this->db->escape($new_value) . "' WHERE product_id = '" . (int)$row1['product_id'] . "' AND attribute_id = '" . (int)$row1['attribute_id'] . "'");
                echo "<hr />";
            }

            // Odstranit "Ano" nebo "0"
            $sql2 = "SELECT * FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$value . "' AND (text = 'Ano' OR text = '0')";
            $result2 = $this->db->query($sql2);
            foreach($result2->rows as $row2) {
                echo "Původní: " . $row2['text'] . "<br>";
                $new_value = '';
                echo "Nová hodnota: " . $new_value . "<br>";
                $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '', value = '' WHERE product_id = '" . (int)$row2['product_id'] . "' AND attribute_id = '" . (int)$row2['attribute_id'] . "'");
                echo "<hr />";
            }

            // Nahradit r16 za 16
            $sql3 = "SELECT * FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$value . "' AND text REGEXP '^r([0-9]+)'";
            $result3 = $this->db->query($sql3);
            foreach($result3->rows as $row3) {
                echo "Původní: " . $row3['text'] . "<br>";
                $new_value = preg_replace('/^r([0-9]+)/', '$1', $row3['text']);
                echo "Nová hodnota: " . $new_value . "<br>";
                $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '" . $this->db->escape($new_value) . "', value = '" . $this->db->escape($new_value) . "' WHERE product_id = '" . (int)$row3['product_id'] . "' AND attribute_id = '" . (int)$row3['attribute_id'] . "'");
                echo "<hr />";
            }

        }
        // trim first and last spaces
        echo 'Trim spaces<br>';
        $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = TRIM(text) WHERE text REGEXP '^ +(.+)$'");
        $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = TRIM(text) WHERE text REGEXP '^(.+) +$'");
        $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET value = TRIM(text) WHERE value REGEXP '^ +(.+)$'");
        $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET value = TRIM(text) WHERE value REGEXP '^(.+) +$'");
        echo "<hr />";

        // change . to ,
        $sql = "SELECT * FROM " . DB_PREFIX . "product_attribute WHERE text REGEXP '^ *[0-9]+,[0-9]+ *$'";
        //echo $sql;
        $result = $this->db->query($sql);
        foreach($result->rows as $row) {
            echo "Původní: " . $row['text'] . "<br>";
            $new_value = trim(str_replace(',', '.', $row['text']));
            echo "Nová hodnota: " . $new_value . "<br>";
            $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '" . $new_value . "', value = '" . $new_value . "' WHERE product_id = '" . (int)$row['product_id'] . "' AND attribute_id = '" . (int)$row['attribute_id'] . "'");
            echo "<hr />";
        }

        // change . to , in PCD
        $sql = "SELECT * FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = 2 and text REGEXP '^[0-9]+x[0-9]+,[0-9]+'";
        //echo $sql;
        $result = $this->db->query($sql);
        foreach($result->rows as $row) {
            echo "Původní: " . $row['text'] . "<br>";
            $new_value = trim(str_replace(',', '.', $row['text']));
            echo "Nová hodnota: " . $new_value . "<br>";
            $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '" . $new_value . "', value = '" . $new_value . "' WHERE product_id = '" . (int)$row['product_id'] . "' AND attribute_id = '" . (int)$row['attribute_id'] . "'");
            echo "<hr />";
        }

        // change 9,0 and 9,00 to 9 in width
        $sql = "SELECT * FROM `oc_product_attribute` where attribute_id in (3, 6) and text REGEXP '^[0-9]+\\\.0+$'";
        //echo $sql;
        $result = $this->db->query($sql);
        foreach($result->rows as $row) {
            echo "Původní: " . $row['text'] . "<br>";
            $new_value = str_replace('.00', '', $row['text']);
            $new_value = str_replace('.0', '', $new_value);
            echo "Nová hodnota: " . $new_value . "<br>";
            $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '" . $new_value . "', value = '" . $new_value . "' WHERE product_id = '" . (int)$row['product_id'] . "' AND attribute_id = '" . (int)$row['attribute_id'] . "'");
            echo "<hr />";
        }

        //delete cache
        $cache_key = "auto_attributes." . (int) $this->config->get("config_language_id") . "." . (int) $this->config->get("config_store_id");
        $this->cache->delete($cache_key);

    }

}
