<?php

class ControllerExtensionFeedLabelImage extends Controller
{

    public function index()
    {
        $count = 0;
        $query = $this->db->query("SELECT DISTINCT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN `" . DB_PREFIX . "product_attribute` pa38 ON (p.product_id = pa38.product_id AND pa38.attribute_id='38') WHERE pa38.attribute_id='38' GROUP BY p.product_id");

        foreach ($query->rows as $product_id) {

            $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_id['product_id']);
            foreach ($data['attribute_groups'][0]["attribute"] as $attribute) {

                If ($attribute["attribute_id"] == 38 && $attribute["text"] && $attribute["text"] != 0) {
                    $filepath = './image/Label/' . $attribute["text"] . '.png';

                    If (!file_exists($filepath)) {

                        $ch = curl_init('https://eprel.ec.europa.eu/api/products/tyres/' . $attribute["text"] . '/labels?format=png');
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36",
                        ));
                    #curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);

                        // ждать 1 (1000000) секунду
                        usleep(500000);

                        $data['eprel_atrib'] = $this->model_catalog_product->getImgEprel($product_id['product_id']);

                        If ($data['eprel_atrib']['atrib_eprel']) {
                            if ($data['eprel_atrib']['atrib_eprel'] == $attribute["text"]) {
                                If (file_exists($filepath) && filesize($filepath) > 1024) {
                                    continue;
                                }
                                else {
                                    $this->getEvroImage($attribute["text"], $filepath);

                                    // Размер плохого файла (без картинки) 455.
                                    If (file_exists($filepath) && filesize($filepath) > 1024) {
                                        $this->model_catalog_product->editImgEprel($product_id['product_id'],
                                            ($data['eprel_atrib']['image_eprel'] ? '/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png' : ('/image/label/' . $attribute["text"] . '.png')),
                                            ($data['eprel_atrib']['atrib_eprel'] ? $data['eprel_atrib']['atrib_eprel'] : $attribute["text"]), $filepath, $attribute["text"]);
                                        //unlink('/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png');
                                        $data['eprel_img'] .= $filepath;
                                        $count++;
                                    } else {
                                        unlink($filepath);

                                        $data["error"]['product_id'] .= $product_id['product_id'];
                                        $data["error"]['atrib_eprel'] .= $attribute["text"];
                                    }
                                }
                            } else {
                                $this->getEvroImage($attribute["text"], $filepath);

                                // Размер плохого файла (без картинки) 455.
                                If (file_exists($filepath) && filesize($filepath) > 1024) {
                                    $this->model_catalog_product->editImgEprel($product_id['product_id'],
                                        ($data['eprel_atrib']['image_eprel'] ? '/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png' : ('/image/label/' . $attribute["text"] . '.png')),
                                        ($data['eprel_atrib']['atrib_eprel'] ? $data['eprel_atrib']['atrib_eprel'] : $attribute["text"]), $filepath, $attribute["text"]);
                                    unlink('/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png');
                                    $data['eprel_img'] .= $filepath;
                                    $count++;
                                } else {
                                    unlink($filepath);

                                    $data["error"]['product_id'] .= $product_id['product_id'];
                                    $data["error"]['atrib_eprel'] .= $attribute["text"];
                                }
                            }

                        } else {
                            $this->getEvroImage($attribute["text"], $filepath);

                            // Размер плохого файла (без картинки) 455.
                            If (file_exists($filepath) && filesize($filepath) > 1024) {
                                $this->model_catalog_product->editImgEprel($product_id['product_id'],
                                    $filepath,
                                    ($data['eprel_atrib']['atrib_eprel'] ? $data['eprel_atrib']['atrib_eprel'] : $attribute["text"]), $filepath, $attribute["text"]);
                                //unlink('/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png');
                                $data['eprel_img'] .= $filepath;
                                $count++;
                            } else {
                                unlink($filepath);

                                $data["error"]['product_id'] .= $product_id['product_id'];
                                $data["error"]['atrib_eprel'] .= $attribute["text"];
                            }
                            $data["error"]["filesize"] .= filesize($filepath);

                        }
                    }
                    else {
                        $data['eprel_atrib'] = $this->model_catalog_product->getImgEprel($product_id['product_id']);


                    }
                }
            }
        }

        $basedir = ('./image/Label/');
        $file = $basedir . 'error_eprel.txt';
        $fp2 = fopen($file, "w9");
        fwrite($fp2, $data["error"]);
        fclose($fp2);

        echo 'END.';
        echo $count;

    }

    public function getEvroImage($attrib_text, $filepath)
    {

        $ch = curl_init('http://eprel.ec.europa.eu/label/Label_' . $attrib_text . '.png');
        $fp = fopen($filepath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36",
        ));
        #curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

    }

}

?>
