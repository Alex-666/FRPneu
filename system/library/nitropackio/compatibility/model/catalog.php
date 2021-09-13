<?php

namespace nitropackio\compatibility\model;

use \Action;
use NitroPack\PurgeType;
use nitropackio\compatibility\Model as NitropackModel;
use nitropackio\core\exception\Domain as DomainException;
use nitropackio\core\Nitropack;
use nitropackio\core\Tag;

class Catalog extends NitropackModel {
    // No need to place them a language file, as they will be displayed in an english-only context.
    const REASON_MANUAL_PURGE_URL = "Manual purge of the link %s from the NitroPack.io Dashboard.";
    const REASON_MANUAL_PAGE_CACHE_ONLY_ALL = "Manual page cache clearing of all store pages from the NitroPack.io Dashboard.";

    public function route($route) {
        $this->currentStoreWrapper(function($nitropack) use (&$route) {
            $nitropack->setRoute($route);
        });
    }

    public function tag($group, $entity) {
        $this->currentStoreWrapper(function($nitropack) use (&$group, &$entity) {
            if ($nitropack->sdk->isAllowedRequest(true)) {
                $nitropackTag = new Tag($group, $entity);

                $nitropack->pushTag($nitropackTag);
            }
        });
    }

    public function getProductStores($product_id) {
        $product_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

        foreach ($query->rows as $result) {
            $product_store_data[] = $result['store_id'];
        }

        return $product_store_data;
    }

    public function initRegistry() {
        $this->currentStoreWrapper(function($nitropack) {
            $nitropack->setRegistry($this->registry);
        });
    }

    public function fetchAndServeRemoteCache() {
        $this->currentStoreWrapper(function($nitropack) {
            if ($nitropack->isRouteIncluded($nitropack->getRoute()) && $nitropack->isEnabled()) {
                // Fetch remote file, if it exists and we are not in an excluded route
                $nitropack->sdk->hasRemoteCache($this->getRouteLayout($nitropack->getRoute()));

                if (isset($this->request->server['HTTP_X_NITRO_WARMUP'])) {
                    // This is a warmup request. No need to generate further content. Just exit.
                    exit;
                }

                // Serve HTML cache, if exists
                $nitropack->serveLocalCache();
            }
        });
    }

    public function canCachePage() {
        $status = false;

        $this->currentStoreWrapper(function($nitropack) use (&$status) {
            $status = $nitropack->canCachePage();
        });

        return $status;
    }

    public function fetchConfig() {
        if ($this->verifyWebhookToken()) {
            $this->currentStoreWrapper(function($nitropack) {
                $nitropack->sdk->fetchConfig();
            });
        }
    }

    public function clearPageCache() {
        if ($this->verifyWebhookToken()) {
            $this->currentStoreWrapper(function($nitropack) {
                if (isset($this->request->post['url'])) {
                    $nitropack->sdk->purgeCache($this->request->post['url'], null, PurgeType::PAGECACHE_ONLY, sprintf(self::REASON_MANUAL_PURGE_URL, $this->request->post['url']));
                } else {
                    $nitropack->sdk->clearPageCache(self::REASON_MANUAL_PAGE_CACHE_ONLY_ALL);
                }
            }, function($error_message) {
                Nitropack::header($this->request->server['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            });
        }
    }

    public function isRouteEnabled($route) {
        $status = false;

        $this->currentStoreWrapper(function($nitropack) use (&$route, &$status) {
            $status = (bool)$nitropack->isRouteIncluded($route);
        });

        return $status;
    }

    public function isCacheWarmupEnabled($route) {
        $status = false;

        $this->currentStoreWrapper(function($nitropack) use (&$route, &$status) {
            $status = in_array($route, $nitropack->setting->get("included_warmup_routes", array()));
        });

        return $status;
    }

    public function iterateCategories($store_id, $page, $callback) {
        $cache_key = 'category.nitropackio.' . (int)$store_id . '.' . (int)$page;

        $data = $this->cache->get($cache_key);

        if (!empty($data)) {
            return $callback($data);
        } else {
            $limit = 100;
            $result = $this->db->query("SELECT c.category_id FROM " . DB_PREFIX . "category_to_store c2s LEFT JOIN " . DB_PREFIX . "category c ON (c.category_id = c2s.category_id) WHERE c2s.store_id=" . (int)$store_id . " AND c.status=1 LIMIT " . ($page - 1) * $limit . "," . $limit);

            if ($result->num_rows > 0) {
                foreach ($result->rows as &$row) {
                    $parent_id = (int)$row['category_id'];
                    $path = array();
                    $used = array();

                    while ($parent_id > 0 && !in_array($parent_id, $used, true)) {
                        array_unshift($path, $parent_id);

                        $used[] = $parent_id;

                        $result_2 = $this->db->query("SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id='" . $parent_id . "'");

                        if ($result_2->num_rows) {
                            $parent_id = (int)$result_2->row['parent_id'];
                        }
                    };

                    $row['path'] = implode('_', $path);
                    unset($row['category_id']);
                }

                $this->cache->set($cache_key, $result->rows);

                return $callback($result->rows);
            }
        }

        return false;
    }

    public function iterateInformations($store_id, $page, $callback) {
        $cache_key = 'information.nitropackio.' . (int)$store_id . '.' . (int)$page;

        $data = $this->cache->get($cache_key);

        if (!empty($data)) {
            return $callback($data);
        } else {
            $limit = 100;
            $result = $this->db->query("SELECT i.information_id FROM " . DB_PREFIX . "information_to_store i2s LEFT JOIN " . DB_PREFIX . "information i ON (i.information_id = i2s.information_id) WHERE i2s.store_id=" . (int)$store_id . " AND i.status=1 LIMIT " . ($page - 1) * $limit . "," . $limit);

            if ($result->num_rows > 0) {
                $this->cache->set($cache_key, $result->rows);

                return $callback($result->rows);
            }
        }

        return false;
    }

    public function iterateProducts($store_id, $page, $callback) {
        $cache_key = 'product.nitropackio.' . (int)$store_id . '.' . (int)$page;

        $data = $this->cache->get($cache_key);

        if (!empty($data)) {
            return $callback($data);
        } else {
            $limit = 100;
            $result = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2s.product_id) WHERE p2s.store_id=" . (int)$store_id . " AND p.status=1 AND p.date_available <= NOW() LIMIT " . ($page - 1) * $limit . "," . $limit);

            if ($result->num_rows > 0) {
                $this->cache->set($cache_key, $result->rows);

                return $callback($result->rows);
            }
        }

        return false;
    }

    public function iterateManufacturers($store_id, $page, $callback) {
        $cache_key = 'manufacturer.nitropackio.' . (int)$store_id . '.' . (int)$page;

        $data = $this->cache->get($cache_key);

        if (!empty($data)) {
            return $callback($data);
        } else {
            $limit = 100;
            $result = $this->db->query("SELECT m.manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store m2s LEFT JOIN " . DB_PREFIX . "manufacturer m ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id=" . (int)$store_id . " LIMIT " . ($page - 1) * $limit . "," . $limit);

            if ($result->num_rows > 0) {
                $this->cache->set($cache_key, $result->rows);

                return $callback($result->rows);
            }
        }

        return false;
    }

    public function cartPlaceholder($cart_url) {
        $html = null;

        $this->currentStoreWrapper(function($nitropack) use (&$html, &$cart_url) {
            $html = $nitropack->getHtml('cart_placeholder', array(
                'cart_url' => $cart_url
            ));
        });

        return $html;
    }

    public function getRouteLayout($route) {
        $all_layouts = $this->db->query("SELECT TRIM(lr.route) as route, TRIM(l.name) as name FROM `" . DB_PREFIX . "layout_route` lr LEFT JOIN `" . DB_PREFIX . "layout` l ON (l.layout_id = lr.layout_id) WHERE lr.store_id=" . (int)$this->config->get('config_store_id'))->rows;

        // Stage 1 - find exact matches in layout_route
        foreach ($all_layouts as $layout) {
            if (stripos($layout['route'], '%') === false && $layout['route'] === $route) {
                return $layout['name'];
            }
        }

        // Stage 2 - in case no exact matches are found, compare with all partial routes (e.g. checkout/%) in layout_route
        foreach ($all_layouts as $layout) {
            if (stripos($layout['route'], '%') !== false && preg_match('~' . str_replace('%', '.*', $layout['route']) . '~', $route)) {
                return $layout['name'];
            }
        }

        // Stage 3 - if nothing is yet found, use the name of the route with the blank name
        foreach ($all_layouts as $layout) {
            if ($layout['route'] === '') {
                return $layout['name'];
            }
        }

        // Stage 4 - If no route is found, just return the default value
        return 'Default';
    }

    private function verifyWebhookToken() {
        $status = false;

        $this->currentStoreWrapper(function($nitropack) use (&$status) {
            $getTokenExists = isset($this->request->get['token']);
            $configTokenExists = $nitropack->setting->has('webhook_token');

            $status = $getTokenExists && $configTokenExists && $this->hashEquals($this->request->get['token'], $nitropack->setting->get('webhook_token'));
        });

        return $status;
    }

    private function hashEquals($known_string, $user_string) {
        $known_string = (string)$known_string;
        $user_string = (string)$user_string;

        if(strlen($known_string) != strlen($user_string)) {
            return false;
        } else {
            $res = $known_string ^ $user_string;
            $ret = 0;

            for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

            return !$ret;
        }
    }

    private function currentStoreWrapper($success_callback, $error_callback = null) {
        if (class_exists('nitropackio\\core\\Nitropack')) {
            Nitropack::executionBlock(function() use (&$success_callback, &$default) {
                try {
                    $nitropack = Nitropack::getInstance();

                    if ($nitropack->isConnected()) {
                        $success_callback($nitropack);
                    }
                } catch (DomainException $e) {
                    // Do nothing in case there is a domain exception error
                }
            }, $error_callback);
        }
    }
}
