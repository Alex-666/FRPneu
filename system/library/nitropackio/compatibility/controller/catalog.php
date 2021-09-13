<?php

namespace nitropackio\compatibility\controller;

use nitropackio\compatibility\Controller as NitropackController;
use nitropackio\core\Nitropack;
use nitropackio\core\OrderHistory;

class Catalog extends NitropackController {
    const SKIP_ACTION_ORDER = "SKIPPING product <%s> ORDERED action for store <%s>.";
    const ORDERED_IN_STORE = "{O.U.01} The product <%s> quantity CHANGED in store <%s> as a result of an ORDER <%s> add/edit.";
    const REASON_INVALIDATE_ORDER = "Automatic invalidation of affected store pages after placing/editing order #%s.";

    public function __construct($registry) {
        parent::__construct($registry);

        $this->load->model($this->ext->route->module->nitropack);

        $this->loaded->model->module_nitropack = $this->{$this->ext->model->module->nitropack};
    }

    public function postSeoUrl() {
        $this->loaded->model->module_nitropack->initRegistry();

        if (null !== $route = $this->getRequestRouteBase()) {
            $this->loaded->model->module_nitropack->route($route);

            $this->loaded->model->module_nitropack->tag('route', $route);

            if ($this->loaded->model->module_nitropack->canCachePage()) {
                $this->loaded->model->module_nitropack->fetchAndServeRemoteCache();
            } else {
                Nitropack::header("X-Nitro-Disabled: 1");
            }
        }
    }

    public function tracking() {
        if (isset($this->request->post['tracking'])) {
            $tracking = htmlspecialchars_decode($this->request->post['tracking'], ENT_QUOTES);

            $this->logTracking($tracking);
        }
    }

    public function webhook_config() {
        $this->loaded->model->module_nitropack->fetchConfig();
    }

    public function webhook_cache_clear() {
        $this->loaded->model->module_nitropack->clearPageCache();
    }

    public function sitemap() {
        $ssl = !!$this->config->get('config_secure');

        Nitropack::header("Content-Type: application/xml");
        Nitropack::header("Cache-Control: no-cache");

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Provide a sitemap only in case NitroPack is enabled
        if ($this->loaded->model->module_nitropack->isSettingEnabled('status', (int)$this->config->get('config_store_id'))) {

            // Home
            if ($this->loaded->model->module_nitropack->isCacheWarmupEnabled('common/home') && $this->loaded->model->module_nitropack->isRouteEnabled('common/home')) {
                $home = $this->getHomeRoute();
                $usedHomeRoutes = array();

                if ($ssl) {
                    $base = $this->config->get('config_ssl');
                } else {
                    $base = $this->config->get('config_url');
                }

                $this->outputSitemapUrl($base);
                $usedHomeRoutes[] = $base;

                $manualHomeRoute = $base . 'index.php?route=' . $home;

                $this->outputSitemapUrl($manualHomeRoute);
                $usedHomeRoutes[] = $manualHomeRoute;

                $seoHomeRoute = $this->url->link($home, '', $ssl);

                if (!in_array($seoHomeRoute, $usedHomeRoutes)) {
                    $this->outputSitemapUrl($seoHomeRoute);
                    $usedHomeRoutes[] = $seoHomeRoute;
                }
            }

            // Categories
            if ($this->loaded->model->module_nitropack->isCacheWarmupEnabled('product/category') && $this->loaded->model->module_nitropack->isRouteEnabled('product/category')) {
                $this->iterateEntities('product/category', $ssl, array($this->loaded->model->module_nitropack, 'iterateCategories'));
            }

            // Informations
            if ($this->loaded->model->module_nitropack->isCacheWarmupEnabled('information/information') && $this->loaded->model->module_nitropack->isRouteEnabled('information/information')) {
                $this->iterateEntities('information/information', $ssl, array($this->loaded->model->module_nitropack, 'iterateInformations'));
            }

            // Products
            if ($this->loaded->model->module_nitropack->isCacheWarmupEnabled('product/product') && $this->loaded->model->module_nitropack->isRouteEnabled('product/product')) {
                $this->iterateEntities('product/product', $ssl, array($this->loaded->model->module_nitropack, 'iterateProducts'));
            }

            // Specials
            if ($this->loaded->model->module_nitropack->isCacheWarmupEnabled('product/special') && $this->loaded->model->module_nitropack->isRouteEnabled('product/special')) {
                $this->outputSitemapUrl($this->url->link('product/special', '', $ssl));
            }

            // Manufacturers
            if ($this->loaded->model->module_nitropack->isCacheWarmupEnabled('product/manufacturer') && $this->loaded->model->module_nitropack->isRouteEnabled('product/manufacturer')) {
                $this->outputSitemapUrl($this->url->link('product/manufacturer', '', $ssl));

                $this->iterateEntities('product/manufacturer/info', $ssl, array($this->loaded->model->module_nitropack, 'iterateManufacturers'));
            }
        }

        echo '</urlset>';

        exit;
    }

    private function iterateEntities($route, $ssl, $iterate_method) {
        $page = 1;

        do {
            $success = $iterate_method($this->config->get('config_store_id'), $page++, function($batch) use (&$route, &$ssl) {
                foreach ($batch as $entity) {
                    $this->outputSitemapUrl($this->url->link($route, http_build_query($entity), $ssl));
                }

                return true;
            });
        } while ($success);
    }

    private function outputSitemapUrl($url) {
        echo '<url>';
        echo '<loc>';
        echo $url;
        echo '</loc>';
        echo '</url>';
    }

    /* START EVENTS */

    public function cartPlaceholder($route = null, $args = null, $output = null) {
        $do_override = !isset($this->request->get['route']) || $this->request->get['route'] != 'common/cart/info';

        if ($do_override && $this->loaded->model->module_nitropack->isSettingEnabled('allow_cart', $this->config->get('config_store_id')) && $this->loaded->model->module_nitropack->isSettingEnabled('status', $this->config->get('config_store_id'))) {
            $url = $this->url->link('common/cart/info', '', $this->getUrlSsl());

            return $this->loaded->model->module_nitropack->cartPlaceholder($url);
        }
    }

    public function afterGetProduct($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $product_id = $args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $product_id = !empty($args['product_id']) ? $args['product_id'] : 0;
        } else {
            $product_id = $route;
        }

        if (!empty($product_id) && is_numeric($product_id) && !$this->routeCompare($this->getHomeRoute())) {
            $this->loaded->model->module_nitropack->tag('product', (int)$product_id);
        }
    }

    // In OpenCart 2.1 and older, this method is not used because everything goes through afterGetProduct()
    public function afterGetProducts($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $products = $output;
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $products = $args;
        } else {
            $products = $route;
        }

        if (!$this->routeCompare($this->getHomeRoute())) {
            foreach ($products as $product) {
                if (is_array($product) && !empty($product['product_id']) && is_numeric($product['product_id'])) {
                    $this->loaded->model->module_nitropack->tag('product', (int)$product['product_id']);
                }
            }
        }
    }

    public function afterGetCategory($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $category_id = $args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $category_id = !empty($args['category_id']) ? $args['category_id'] : 0;
        } else {
            $category_id = $route;
        }

        if (!empty($category_id) && is_numeric($category_id) && !$this->routeCompare($this->getHomeRoute()) && $this->routeCompare('product/category') && isset($this->request->get['path'])) {
            $path_categories = array_filter(explode('_', $this->request->get['path']));
            $final_category_id = end($path_categories);

            if ($final_category_id == $category_id) {
                $this->loaded->model->module_nitropack->tag('category', (int)$category_id);
            }
        }
    }

    // Deprecated
    public function afterGetCategories($route = null, $args = null, $output = null) {
        // if (version_compare(VERSION, '2.3.0.0', '>=')) {
        //     $categories = $output;
        // } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
        //     $categories = $args;
        // } else {
        //     $categories = $route;
        // }

        // if ($this->routeCompare('product/category') && !$this->routeCompare($this->getHomeRoute())) {
        //     foreach ($categories as $category) {
        //         $this->loaded->model->module_nitropack->tag('category', $category['category_id']);
        //     }
        // }
    }

    public function afterGetManufacturer($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $manufacturer_id = $args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $manufacturer_id = !empty($args['manufacturer_id']) ? $args['manufacturer_id'] : 0;
        } else {
            $manufacturer_id = $route;
        }

        if (!empty($manufacturer_id) && is_numeric($manufacturer_id) && !$this->routeCompare($this->getHomeRoute())) {
            $this->loaded->model->module_nitropack->tag('manufacturer', (int)$manufacturer_id);
        }
    }

    public function afterGetManufacturers($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $manufacturers = $output;
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $manufacturers = $args;
        } else {
            $manufacturers = $route;
        }

        if (!$this->routeCompare($this->getHomeRoute())) {
            foreach ($manufacturers as $manufacturer) {
                if (is_array($manufacturer) && !empty($manufacturer['manufacturer_id']) && is_numeric($manufacturer['manufacturer_id'])) {
                    $this->loaded->model->module_nitropack->tag('manufacturer', (int)$manufacturer['manufacturer_id']);
                }
            }
        }
    }

    public function afterGetInformation($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $information_id = $args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $information_id = !empty($args['information_id']) ? $args['information_id'] : 0;
        } else {
            $information_id = $route;
        }

        if (!empty($information_id) && is_numeric($information_id) && !$this->routeCompare($this->getHomeRoute()) && $this->routeCompare('information/information') && isset($this->request->get['information_id']) && $this->request->get['information_id'] == $information_id) {
            $this->loaded->model->module_nitropack->tag('information', (int)$information_id);
        }
    }

    // Deprecated
    public function afterGetInformations($route = null, $args = null, $output = null) {
        // if (version_compare(VERSION, '2.3.0.0', '>=')) {
        //     $informations = $output;
        // } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
        //     $informations = $args;
        // } else {
        //     $informations = $route;
        // }

        // if ($this->routeCompare('information/information') && !$this->routeCompare($this->getHomeRoute())) {
        //     foreach ($informations as $information) {
        //         $this->loaded->model->module_nitropack->tag('information', $information['information_id']);
        //     }
        // }
    }

    public function beforeAddOrderHistory($route, $args = null) {
        if (!$this->registry->has('nitropack_order_history')) {
            $this->registry->set('nitropack_order_history', new OrderHistory($this->registry));
        }

        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $order_id = $args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $order_id = $args;
        } else if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $order_id = $route['order_id'];
        } else {
            $order_id = $route;
        }

        if (is_numeric($order_id)) {
            $this->nitropack_order_history->persistOrderStock($order_id);
        }
    }

    public function afterAddOrderHistory($route, $args = null, $output = null) {
        if (!$this->registry->has('nitropack_order_history')) {
            return;
        }

        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            $order_id = $args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            $order_id = $output;
        } else {
            $order_id = $route;
        }

        if (!is_numeric($order_id)) {
            return;
        }

        foreach ($this->nitropack_order_history->getProductIdsWithDifference($order_id) as $product_id) {
            foreach ($this->loaded->model->module_nitropack->getProductStores($product_id) as $store_id) {
                if ($this->loaded->model->module_nitropack->isSettingEnabled('auto_cache_clear_order', (int)$this->config->get('config_store_id'))) {
                    Nitropack::logDebugMessage(sprintf(self::ORDERED_IN_STORE, $product_id, $store_id, $order_id));

                    $this->loaded->model->module_nitropack->invalidate("product", $product_id, $store_id, sprintf(self::REASON_INVALIDATE_ORDER, $order_id));
                } else {
                    Nitropack::logDebugMessage(sprintf(self::SKIP_ACTION_ORDER, $product_id, $store_id));
                }
            }
        }
    }
    /* END EVENTS */

    public function logTracking($tracking) {
        if (!headers_sent()) {
            setcookie('tracking', $tracking, time() + 3600 * 24 * 1000, '/');

            if (version_compare(VERSION, '2', '>=')) {
                $this->db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $this->db->escape($tracking) . "'");
            }
        }
    }
}
