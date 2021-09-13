<?php
class ControllerExtensionAnalyticsGaCom extends Controller {
	protected function getCategoryPath($category_id) {
		$path = '';
		$category = $this->model_catalog_category->getCategory($category_id);

		if($category['parent_id'] != 0){
			$path .= $this->getCategoryPath($category['parent_id']) . ' / ';
		}

		$path .= $category['name'];
		return $path;
	}

	// Maps Opencart product data to Google Analytics product structure
	protected function getProduct($order_id, $product) {
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('checkout/order');

		$oc_product = $this->model_catalog_product->getProduct($product["product_id"]);
		
		// get product options
		$product["variant"] = '';
		$variants = $this->model_checkout_order->getOrderOptions($order_id, $product["order_product_id"]);
		foreach ($variants as $variant)
			$product["variant"] = $variant["value"] . " | ";
		if ($product["variant"])
			$product["variant"] = substr($product["variant"], 0, -3);

		// get category path	
		$oc_categories = $this->model_catalog_product->getCategories($product["product_id"]);
		$oc_category = [];
		if (sizeof($oc_categories) > 0) {
			$oc_category = $this->model_catalog_category->getCategory($oc_categories[0]["category_id"]);
			if (sizeof($oc_category) > 0)
				$oc_category["path"] = $this->getCategoryPath($oc_category['category_id']);
			else
				$oc_category["path"] = '';
		}


		// $this->log->write(print_r($this->model_checkout_order->getOrderOptions($order_id, $product["order_product_id"]), TRUE));

		$ga_product = [
			"id"			=> $product["product_id"],
			"name"			=> $product["name"],
			"SKU"			=> $oc_product["sku"],
			"brand"			=> $oc_product["manufacturer"],
			"category"		=> $oc_category["path"],
			"variant"		=> $product["variant"],
			"quantity"		=> $product["quantity"],
			"price"			=> $product["price"]
		];
		return $ga_product;
	}


	protected function getShipping($totals) {
		$shipping = 0.00;
		foreach ($totals as $total) {
			if ($total["code"] == 'shipping')
				$shipping += $total["value"];
		}
		return $shipping;
	}


	protected function getTax($totals) {
		$tax = 0.00;
		foreach ($totals as $total) {
			if ($total["code"] == 'tax')
				$tax += $total["value"];
		}
		return $tax;
	}


    public function index() {
    	$this->load->model('checkout/order');

    	// get Route
		$route = '';
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		}
		// get Tracking ID
    	$tracking_id = $this->config->get('analytics_gacom_trackingid');

    	// If page not success send simple pageview
    	if (!isset($this->session->data['ga_orderDetails']) || $route != 'checkout/success') {
			$tag = <<<TAG
				<script async src="https://www.googletagmanager.com/gtag/js?id=$tracking_id"></script>
				<script>
				  window.dataLayer = window.dataLayer || [];
				  function gtag(){dataLayer.push(arguments);}
				  gtag('js', new Date());
				  gtag('config', '$tracking_id');
				</script>
TAG;
			return $tag;
    	}

		$orderDetails = $this->session->data['ga_orderDetails'];
    	$order_id = $orderDetails["order_id"];
    	$order_totals = $this->model_checkout_order->getOrderTotals($order_id);
    	// $this->log->write(print_r($order_totals, TRUE));

		$ob_products = [];
		if (isset($this->session->data['ga_orderProducts'])) {
			foreach ($this->session->data['ga_orderProducts'] as $product)
				array_push($ob_products, $this->getProduct($order_id, $product));
		}

		$ob_order = [
			"transaction_id"		=> $order_id,
			"affiliation"			=> $orderDetails["store_name"],
			"value"					=> $orderDetails["total"],
			"currency"				=> $orderDetails["currency_code"],
			"tax"					=> $this->getTax($order_totals),
			"shipping"				=> $this->getShipping($order_totals),
			"items"					=> $ob_products
		];

		$purchase_event = json_encode($ob_order);
		$tag = <<<TAG
			<script async src="https://www.googletagmanager.com/gtag/js?id=$tracking_id"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());

			  gtag('config', '$tracking_id');
			  gtag('event', 'purchase', $purchase_event);
			</script>
TAG;

		unset($this->session->data['ga_orderDetails']);
		unset($this->session->data['ga_orderProducts']);

		return $tag;
	}
}
?>