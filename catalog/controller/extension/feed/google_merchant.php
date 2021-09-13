<?php
class ControllerExtensionFeedGoogleMerchant extends Controller {
    private $shop_name = "FR pneu";
    private $shop_link = "http://www.frpneu.cz";

    public function index() {
        $this->load->model('extension/feed/google_merchant');
        $products = $this->model_extension_feed_google_merchant->getProducts("AND p.pohoda_id REGEXP '^[0-9]+$'");
        $feed_products = [];
        foreach ($products as $product) {
            if ($product['category_id']!=''){
                $gf_product = [];
                $gf_product['g:id'] = $product['sku'];
                $gf_product['g:title'] = $product['name'];
                $gf_product['g:description'] = $product['description'];
                $gf_product['g:link'] = $product['link'];
                $gf_product['g:image_link'] = $product['image'];
                $gf_product['g:availability'] = ($product['quantity']) ? 'in stock' : 'out of stock';
                $gf_product['g:price'] = $this->tax->calculate($product['price'], $product['tax_class_id'])." ".$product['currency'];

                $gf_product['g:google_product_category'] = $product['category_id'];

                if ($product['special']){
                    $gf_product['g:sale_price'] = $this->tax->calculate($product['special'], $product['tax_class_id'])." ".$product['currency'];
                    $gf_product['g:sale_price_effective_date'] = $product['start_date']." / ".$product['end_date'];
                }

                $gf_product['g:brand'] = $product['manufacturer'];
                if ($product['ean']){
                     $gf_product['g:gtin'] = $product['ean'];
                }else{
                    $gf_product['g:identifier_exists'] = "no";
                }
                $gf_product['g:condition'] = "NEW";
                if ($product['delivery']!='')
                    $gf_product['g:shipping_label'] = $product['delivery'];
                $feed_products[] = $gf_product;
            }
        }
        $doc = new DOMDocument('1.0', 'UTF-8');

        $xmlRoot = $doc->createElement("rss");
        $xmlRoot = $doc->appendChild($xmlRoot);
        $xmlRoot->setAttribute('version', '2.0');
        $xmlRoot->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', "http://base.google.com/ns/1.0");

        $channelNode = $xmlRoot->appendChild($doc->createElement('channel'));
        $channelNode->appendChild($doc->createElement('title', $this->shop_name));
        $channelNode->appendChild($doc->createElement('link', $this->shop_link));

        foreach ($feed_products as $product) {
          $itemNode = $channelNode->appendChild($doc->createElement('item'));
          foreach($product as $key=>$value) {
            if ($value != "") {
              if (is_array($product[$key])) {
                $subItemNode = $itemNode->appendChild($doc->createElement($key));
                foreach($product[$key] as $key2=>$value2){
                  $subItemNode->appendChild($doc->createElement($key2))->appendChild($doc->createTextNode($value2));
                }
              } else {
                $itemNode->appendChild($doc->createElement($key))->appendChild($doc->createTextNode($value));
              }

            } else {

              $itemNode->appendChild($doc->createElement($key));
            }

          }
        }
        $doc->formatOutput = true;
        //echo $doc->saveXML();
        $basedir = (realpath(dirname(__FILE__))) . '/../../../../' ;
        $file = $basedir . 'google.xml';
        $doc->save($file);
    }
}
