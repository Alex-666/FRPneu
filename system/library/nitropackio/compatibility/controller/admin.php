<?php

namespace nitropackio\compatibility\controller;

use NitroPack\PurgeType;
use NitroPack\Url as NitropackUrl;
use NitroPack\WebhookException;
use nitropackio\compatibility\Controller as NitropackController;
use nitropackio\compatibility\traits\EventsLoader;
use nitropackio\compatibility\traits\NitropackLoader;
use nitropackio\compatibility\traits\StoreLoader;
use nitropackio\core\Domain;
use nitropackio\core\exception\Domain as DomainException;
use nitropackio\core\exception\Setting as SettingException;
use nitropackio\core\Nitropack;
use nitropackio\core\Tag;
use nitropackio\core\Url as CatalogUrl;

class Admin extends NitropackController {
    use EventsLoader;
    use NitropackLoader;
    use StoreLoader;

    const URL_DOCUMENTATION = 'https://docs.isenselabs.com/nitropack.io';
    const URL_EXTERNAL_SETTINGS = 'https://nitropack.io/user/dashboard';
    const URL_NITROPACK_IO = 'https://nitropack.io/pricing';
    const URL_TUTORIAL_CREDENTIALS = 'https://nitropack.io/blog/post/how-to-get-your-site-id-and-site-secret';
    const URL_MESSENGER = 'https://m.me/getnitropack';
    
    // No need to place them a language file, as they will be displayed in an english-only context.
    const REASON_MANUAL_INVALIDATE_TYPE = "Manual invalidation of the %s %s.";
    const REASON_MANUAL_INVALIDATE_ALL = "Manual invalidation of all store pages.";
    const REASON_MANUAL_PURGE_TYPE = "Manual purge of the %s %s.";
    const REASON_MANUAL_PURGE_ALL = "Manual purge of all store pages.";
    const DISCONNECT_REASON_UNINSTALL = "Uninstalled the whole NitroPack.io extension.";
    const DISCONNECT_REASON_DISCONNECT = "Disconnected the NitroPack.io extension.";

    protected $store_id;
    protected $catalog_url;
    protected $store;
    protected $error = array();

    public function __construct($registry) {
        parent::__construct($registry);

        if ($this->routeCompare($this->ext->route->module->nitropack)) {
            $this->document->addStyle('view/stylesheet/vendor/nitropackio/bootstrap.css?nitropack_version=' . $this->ext->version);
        }

        $this->store_id = isset($this->request->get['store_id']) ? (int)$this->request->get['store_id'] : 0;
        $this->store = $this->getStore($this->store_id);

        $this->catalog_url = new CatalogUrl($this->store['url'], $this->store['ssl']);

        $this->loadLanguage($this->ext->route->module->nitropack);

        $this->loaded->language['text_heading'] = sprintf($this->language->get('text_heading_version'), $this->ext->version);
        $this->loaded->language['version'] = sprintf($this->language->get('text_version'), $this->ext->version);

        $this->load->model($this->ext->route->module->nitropack);
        $this->load->model($this->ext->route->event->category);
        $this->load->model($this->ext->route->event->information);
        $this->load->model($this->ext->route->event->manufacturer);
        $this->load->model($this->ext->route->event->product);
        $this->load->model($this->ext->route->event->review);

        $this->loaded->model->module_nitropack = $this->{$this->ext->model->module->nitropack};
        $this->loaded->model->event_category = $this->{$this->ext->model->event->category};
        $this->loaded->model->event_information = $this->{$this->ext->model->event->information};
        $this->loaded->model->event_manufacturer = $this->{$this->ext->model->event->manufacturer};
        $this->loaded->model->event_product = $this->{$this->ext->model->event->product};
        $this->loaded->model->event_review = $this->{$this->ext->model->event->review};
    }

    /* START ADMIN PANEL METHODS */

    public function install() {
        $this->loaded->model->module_nitropack->initJsonSettings();

        // Update active languages, default language and default currency
        $this->loaded->model->module_nitropack->initDefaults();

        $this->deleteEvents("nitropack");

        $this->addEvents("nitropack", $this->ext->event);
    }

    public function uninstall() {
        if (null !== $catalog_index = $this->loaded->model->module_nitropack->getCatalogIndexPhp()) {
            $this->loaded->model->module_nitropack->unhookFromIndex($catalog_index);
        }

        if (null !== $admin_index = $this->loaded->model->module_nitropack->getAdminIndexPhp()) {
            $this->loaded->model->module_nitropack->unhookFromIndex($admin_index);
        }

        foreach ($this->getStores() as $store) {
            Nitropack::executionBlock(function() use (&$store) {
                $nitropack = $this->nitropackFromStoreUrl($store['url']);

                if ($nitropack->isConnected()) {
                    // Clear the webhooks
                    $types = array(
                        'config',
                        'cache_clear',
                        'sitemap'
                    );

                    foreach ($types as $type) {
                        $nitropack->sdk->getApi()->unsetWebhook($type);
                    }

                    $nitropack->sendExtensionNotification('disconnect', $this->ext->version, $this->request->server['HTTPS'] ? $this->store['ssl'] : $this->store['url'], array(
                        'reason' => self::DISCONNECT_REASON_UNINSTALL
                    ));
                }
            });
        }

        $this->loaded->model->module_nitropack->purgeJsonSettings();

        $this->deleteEvents("nitropack");
    }

    public function index() {
        if (null !== $catalog_index = $this->loaded->model->module_nitropack->getCatalogIndexPhp()) {
            $this->loaded->model->module_nitropack->hookToIndex(
                $catalog_index,
                $this->loaded->model->module_nitropack->hookToCatalogIndexSearchPattern(),
                $this->loaded->model->module_nitropack->hookToCatalogIndexReplacement()
            );

            $this->loaded->model->module_nitropack->updateIndex($catalog_index);
        }

        if (null !== $admin_index = $this->loaded->model->module_nitropack->getAdminIndexPhp()) {
            $this->loaded->model->module_nitropack->hookToIndex(
                $admin_index,
                $this->loaded->model->module_nitropack->hookToAdminIndexSearchPattern(),
                $this->loaded->model->module_nitropack->hookToAdminIndexReplacement()
            );

            $this->loaded->model->module_nitropack->updateIndex($admin_index);
        }

        $this->loaded->model->module_nitropack->updateEvents((array)$this->ext->event);

        Nitropack::executionBlock(function() {
            $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

            if ($nitropack->isConnected()) {
                $this->showManage();
            } else {
                $this->showConnect();
            }
        }, function($error) {
            $this->session->data['error'] = $error;

            $this->showConnect();
        });
    }

    public function disconnect() {
        if (!$this->validatePermissions()) {
            $this->session->data['error'] = $this->error['warning'];
        } else {
            Nitropack::executionBlock(function() {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);
                $nitropack->sendExtensionNotification('disconnect', $this->ext->version, $this->request->server['HTTPS'] ? $this->store['ssl'] : $this->store['url'], array(
                    'reason' => self::DISCONNECT_REASON_DISCONNECT
                ));

                $nitropack->setting->delete('site_id');
                $nitropack->setting->delete('site_secret');

                $nitropack->setting->save();

                $this->session->data['success'] = $this->language->get('success_disconnect');
            }, function($error) {
                $this->session->data['error'] = $error;
            });
        }

        $this->responseRedirect($this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
    }

    public function connect() {
        if (!$this->validatePermissions() || !$this->validateConnect()) {
            $this->showConnect();
        } else {
            $store = $this->getStore((int)$this->request->post['store_id']);

            Nitropack::executionBlock(function() use (&$store) {
                $catalog_url = new CatalogUrl($store['url'], $store['ssl']);

                // Default statuses
                $initial_oc_status = '1';
                $initial_warmup_status = '1';
                $initial_allow_cart = '1';
                $initial_compression = '1';

                $nitropack = $this->nitropackFromStoreUrl($store['url']);

                $nitropack->setting->set('site_id', trim($this->request->post['site_id']));
                $nitropack->setting->set('site_secret', trim($this->request->post['site_secret']));
                $nitropack->setting->set('status', (bool)$initial_oc_status);
                $nitropack->setting->set('warmup', (bool)$initial_warmup_status);
                $nitropack->setting->set('allow_cart', (bool)$initial_allow_cart);
                $nitropack->setting->set('compression', (bool)$initial_compression);
                $nitropack->setting->set('integration_version', $this->ext->version);

                $default_excluded_languages = array();
                $default_language = $this->loaded->model->module_nitropack->getDefaultLanguage($store['store_id']);
                foreach ($this->loaded->model->module_nitropack->getLanguages() as $language) {
                    if ($language['code'] != $default_language) {
                        $default_excluded_languages[] = $language['code'];
                    }
                }
                $nitropack->setting->set('excluded_warmup_languages', $default_excluded_languages);

                $default_excluded_currencies = array();
                $default_currency = $this->loaded->model->module_nitropack->getDefaultCurrency($store['store_id']);
                foreach ($this->loaded->model->module_nitropack->getCurrencies() as $currency) {
                    if ($currency['code'] != $default_currency) {
                        $default_excluded_currencies[] = $currency['code'];
                    }
                }
                $nitropack->setting->set('excluded_warmup_currencies', $default_excluded_currencies);

                $default_included_routes = array('common/home', 'product/category');
                $nitropack->setting->set('included_warmup_routes', $default_included_routes);

                $webhookToken = md5(uniqid());

                $nitropack->setting->set('webhook_token', $webhookToken);
                
                $nitropack->setting->save();

                // Reload the library to take into account site_id and site_secret
                $nitropack->reload();

                try {
                    // Log the connect event
                    $nitropack->sendExtensionNotification('connect', $this->ext->version, $this->request->server['HTTPS'] ? $this->store['ssl'] : $this->store['url']);

                    // Log the enabled event
                    $nitropack->sendExtensionNotification('enable_extension', $this->ext->version, $this->request->server['HTTPS'] ? $this->store['ssl'] : $this->store['url']);

                    // Push the webhooks
                    $urls = array(
                        'config' => new NitropackUrl($this->htmlEntityDecode($catalog_url->link($this->ext->route->module->nitropack . '/webhook_config', '&token=' . $webhookToken, $this->getUrlSsl()))),
                        'cache_clear' => new NitropackUrl($this->htmlEntityDecode($catalog_url->link($this->ext->route->module->nitropack . '/webhook_cache_clear', '&token=' . $webhookToken, $this->getUrlSsl())))
                    );

                    foreach ($urls as $type => $url) {
                        $nitropack->sdk->getApi()->setWebhook($type, $url);
                    }

                    // Push the variation cookies
                    foreach ($this->loaded->model->module_nitropack->getVariationCookies($store['store_id']) as $name => $values) {
                        $group = null;

                        if ($name == 'language') {
                            $values = array_values(array_diff($values, $nitropack->setting->get('excluded_warmup_languages', array())));
                            $group = 1;
                        }

                        if ($name == 'currency') {
                            $values = array_values(array_diff($values, $nitropack->setting->get('excluded_warmup_currencies', array())));
                            $group = 1;
                        }

                        $nitropack->sdk->getApi()->setVariationCookie($name, $values, $group);
                    }

                    // Warmup
                    $nitropack->sdk->getApi()->setWarmupSitemap($this->htmlEntityDecode($catalog_url->link($this->ext->route->module->nitropack . '/sitemap', '', $this->getUrlSsl())));
                    $nitropack->sdk->getApi()->enableWarmup();
                    $nitropack->sdk->getApi()->resetWarmup();

                    // Fetch the new config
                    $nitropack->sdk->fetchConfig();
                } catch (WebhookException $e) {
                    // This did not work. Disconnect the extension.
                    $nitropack->setting->delete('site_id');
                    $nitropack->setting->delete('site_secret');
                    $nitropack->setting->save();

                    // Reload the library to take into account site_id and site_secret
                    $nitropack->reload();

                    throw new \Exception($e->getMessage());
                }

                // Update active languages, default language and default currency
                $this->loaded->model->module_nitropack->initDefaults();

                // Save the NitroPack status in the settings
                $this->load->model('setting/setting');

                $initial_oc_settings = array();
                $initial_oc_settings[$this->ext->oc->field->status] = $initial_oc_status;

                $this->model_setting_setting->editSetting($this->ext->oc->setting_code, $initial_oc_settings, $store['store_id']);

                $this->session->data['success'] = $this->language->get('success_connect');
            }, function($errorMessage) {
                $this->session->data['error'] = $errorMessage;
            });

            $this->responseRedirect($this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $store['store_id'], $this->getUrlSsl()));
        }
    }

    public function error_log() {
        if (!$this->validatePermissions()) {
            $this->session->data['error'] = $this->error['warning'];

            $this->responseRedirect($this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        } else {
            $this->streamFile(Nitropack::ERROR_LOG_FILENAME);
        }
    }

    public function debug_log() {
        if (!$this->validatePermissions()) {
            $this->session->data['error'] = $this->error['warning'];

            $this->responseRedirect($this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        } else {
            $this->streamFile(Nitropack::DEBUG_LOG_FILENAME);
        }
    }

    private function streamFile($filename) {
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Content-Description: File Transfer');
        header('Content-Type: plain/text');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');

        $file = DIR_LOGS . $filename;

        clearstatcache(true);

        if (file_exists($file)) {
            readfile($file);
        }

        exit;
    }

    public function manage() {
        $json = array(
            'type' => 'info',
            'message' => '',
            'warmup_details' => null,
            'warmup_stats' => null
        );

        if (!$this->validatePermissions()) {
            $json['type'] = 'danger';
            $json['message'] = $this->error['warning'];
        } else {
            Nitropack::executionBlock(function() use (&$json) {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

                $this->load->model('setting/setting');

                $this->model_setting_setting->editSetting($this->ext->oc->setting_code, $this->request->post, $this->store_id);

                // Log the enabled/disabled event
                $new_status = !empty($this->request->post[$this->ext->oc->field->status]);
                
                if ((bool)$nitropack->setting->get('status', false) != $new_status) {
                    $nitropack->sendExtensionNotification($new_status ? 'enable_extension' : 'disable_extension', $this->ext->version, $this->request->server['HTTPS'] ? $this->store['ssl'] : $this->store['url']);
                }

                // Log the update event
                if ($nitropack->setting->get('integration_version', 'N/A') != $this->ext->version) {
                    $nitropack->sendExtensionNotification('update', $this->ext->version, $this->request->server['HTTPS'] ? $this->store['ssl'] : $this->store['url'], array(
                        'previous_version' => $nitropack->setting->get('integration_version', 'N/A')
                    ));
                }

                // Integration version
                $nitropack->setting->set('integration_version', $this->ext->version);

                // Status
                $nitropack->setting->set('status', $new_status);
                
                // Preset
                $nitropack->setting->set('local_preset', (int)$this->request->post['local_preset']);
                
                // Cache Warmup
                $warmup_status = !empty($this->request->post['warmup']);
                $excluded_warmup_languages = !empty($this->request->post['excluded_warmup_languages']) ? $this->request->post['excluded_warmup_languages'] : array();
                $excluded_warmup_currencies = !empty($this->request->post['excluded_warmup_currencies']) ? $this->request->post['excluded_warmup_currencies'] : array();
                $included_warmup_routes = !empty($this->request->post['included_warmup_routes']) ? $this->request->post['included_warmup_routes'] : array();

                $warmup_changed =
                    $nitropack->setting->get('warmup', false) !== $warmup_status ||
                    $this->loaded->model->module_nitropack->hasStateDifference($nitropack->setting->get('excluded_warmup_languages', array()), $excluded_warmup_languages) ||
                    $this->loaded->model->module_nitropack->hasStateDifference($nitropack->setting->get('excluded_warmup_currencies', array()), $excluded_warmup_currencies) ||
                    $this->loaded->model->module_nitropack->hasStateDifference($nitropack->setting->get('included_warmup_routes', array()), $included_warmup_routes);
                
                $nitropack->setting->set('warmup', $warmup_status);
                $nitropack->setting->set('excluded_warmup_languages', $excluded_warmup_languages);
                $nitropack->setting->set('excluded_warmup_currencies', $excluded_warmup_currencies);
                $nitropack->setting->set('included_warmup_routes', $included_warmup_routes);
                
                if ($warmup_changed) {
                    if ($warmup_status) {
                        $nitropack->sdk->getApi()->setWarmupSitemap($this->htmlEntityDecode($this->catalog_url->link($this->ext->route->module->nitropack . '/sitemap', '', $this->getUrlSsl())));
                        $nitropack->sdk->getApi()->enableWarmup();
                    } else {
                        $nitropack->sdk->getApi()->unsetWarmupSitemap();
                        $nitropack->sdk->getApi()->disableWarmup();
                    }

                    $nitropack->sdk->getApi()->resetWarmup();
                }

                // Ignore Cart
                $nitropack->setting->set('allow_cart', !empty($this->request->post['allow_cart']));

                // Compression
                $nitropack->setting->set('compression', !empty($this->request->post['compression']));

                // Auto Cache Clear
                $nitropack->setting->set('auto_cache_clear_admin_product', !empty($this->request->post['auto_cache_clear_admin_product']));
                $nitropack->setting->set('auto_cache_clear_admin_category', !empty($this->request->post['auto_cache_clear_admin_category']));
                $nitropack->setting->set('auto_cache_clear_admin_information', !empty($this->request->post['auto_cache_clear_admin_information']));
                $nitropack->setting->set('auto_cache_clear_admin_manufacturer', !empty($this->request->post['auto_cache_clear_admin_manufacturer']));
                $nitropack->setting->set('auto_cache_clear_admin_review', !empty($this->request->post['auto_cache_clear_admin_review']));
                $nitropack->setting->set('auto_cache_clear_order', !empty($this->request->post['auto_cache_clear_order']));

                // Standard Pages
                $nitropack->setting->set(
                    'excluded_standard_pages',
                    $this->loaded->model->module_nitropack->excludeStandardPages(
                        !empty($this->request->post['standard_page']) && is_array($this->request->post['standard_page']) ?
                        array_keys($this->request->post['standard_page']) :
                        array()
                    )
                );

                // Custom Pages
                $included_custom_pages = 
                    !empty($this->request->post['custom_page']) && is_array($this->request->post['custom_page']) ?
                        array_values($this->request->post['custom_page']) :
                        array();

                foreach ($included_custom_pages as &$page) {
                    if (!isset($page['status'])) {
                        $page['status'] = false;
                    }
                }

                $nitropack->setting->set(
                    'included_custom_pages',
                    $included_custom_pages
                );

                $nitropack->setting->save();

                // Push the variation cookies
                foreach ($this->loaded->model->module_nitropack->getVariationCookies($this->store_id) as $name => $values) {
                    $group = null;

                    if ($name == 'language') {
                        $values = array_values(array_diff($values, $nitropack->setting->get('excluded_warmup_languages', array())));
                        $group = 1;
                    }

                    if ($name == 'currency') {
                        $values = array_values(array_diff($values, $nitropack->setting->get('excluded_warmup_currencies', array())));
                        $group = 1;
                    }

                    $nitropack->sdk->getApi()->setVariationCookie($name, $values, $group);
                }

                // Update active languages, default language and default currency
                $this->loaded->model->module_nitropack->initDefaults();

                // Fetch the new config
                $nitropack->sdk->fetchConfig();

                $json['message'] = $this->language->get('success_save');
                $json['warmup_details'] = $this->getWarmupDetails($nitropack->setting->get('excluded_warmup_languages', array()), $nitropack->setting->get('excluded_warmup_currencies', array()), $nitropack->setting->get('included_warmup_routes', array()));
                $json['warmup_stats'] = $this->getWarmupStats($nitropack);
                $json['type'] = 'success';
            }, function($error) use (&$json) {
                $json['type'] = 'danger';
                $json['message'] = $error;
            });
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function warmup_stats() {
        $json = array(
            'status' => false,
            'warmup_stats' => null,
            'message' => ''
        );

        Nitropack::executionBlock(function() use (&$json) {
            $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

            if (!$nitropack->isConnected()) {
                throw new \Exception($this->language->get('error_disconnected'));
            }

            $json['warmup_stats'] = $this->getWarmupStats($nitropack);
            $json['status'] = true;
        }, function($errorMessage) use (&$json) {
            $json['status'] = false;
            $json['message'] = $errorMessage;
        });

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function warmup_start() {
        $json = array(
            'status' => false,
            'message' => ''
        );

        if (!$this->validatePermissions()) {
            $json['status'] = false;
            $json['message'] = $this->error['warning'];
        } else {
            Nitropack::executionBlock(function() use (&$json) {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

                if (!$nitropack->isConnected()) {
                    throw new \Exception($this->language->get('error_disconnected'));
                }

                $nitropack->sdk->getApi()->setWarmupSitemap($this->htmlEntityDecode($this->catalog_url->link($this->ext->route->module->nitropack . '/sitemap', '', $this->getUrlSsl())));
                $nitropack->sdk->getApi()->enableWarmup();

                $stats = $nitropack->sdk->getApi()->getWarmupStats();

                if ($stats['pending'] == 0) {
                    $nitropack->sdk->getApi()->runWarmup();
                }

                $json['status'] = true;
            }, function($errorMessage) use (&$json) {
                $json['status'] = false;
                $json['message'] = $errorMessage;
            });
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function warmup_pause() {
        $json = array(
            'status' => false,
            'message' => ''
        );

        if (!$this->validatePermissions()) {
            $json['status'] = false;
            $json['message'] = $this->error['warning'];
        } else {
            Nitropack::executionBlock(function() use (&$json) {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

                if (!$nitropack->isConnected()) {
                    throw new \Exception($this->language->get('error_disconnected'));
                }

                $nitropack->sdk->getApi()->disableWarmup();

                $json['status'] = true;
            }, function($errorMessage) use (&$json) {
                $json['status'] = false;
                $json['message'] = $errorMessage;
            });
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function warmup() {
        $json = array(
            'status' => false,
            'html' => '',
            'message' => ''
        );

        Nitropack::executionBlock(function() use (&$json) {
            $data = $this->loaded->language;

            $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

            if (!$nitropack->isConnected()) {
                throw new \Exception($this->language->get('error_disconnected'));
            }

            $data['languages'] = array();
            $default_language = $this->loaded->model->module_nitropack->getDefaultLanguage($this->store_id);

            foreach ($this->loaded->model->module_nitropack->getLanguages() as $storeLanguage) {
                $is_default = $storeLanguage['code'] == $default_language;

                $data['languages'][] = array(
                    'status' => !in_array($storeLanguage['code'], $nitropack->setting->get('excluded_warmup_languages', array())),
                    'code' => $storeLanguage['code'],
                    'text' => $storeLanguage['name'] . ' (' . $storeLanguage['code'] . ')',
                    'default' => $is_default,
                    'disabled' => $is_default
                );
            }

            usort($data['languages'], function($v) { return !$v['default']; });

            $data['currencies'] = array();
            $default_currency = $this->loaded->model->module_nitropack->getDefaultCurrency($this->store_id);

            foreach ($this->loaded->model->module_nitropack->getCurrencies() as $storeCurrency) {
                $is_default = $storeCurrency['code'] == $default_currency;

                $data['currencies'][] = array(
                    'status' => !in_array($storeCurrency['code'], $nitropack->setting->get('excluded_warmup_currencies', array())),
                    'code' => $storeCurrency['code'],
                    'text' => $storeCurrency['title'] . ' (' . $storeCurrency['code'] . ')',
                    'default' => $is_default,
                    'disabled' => $is_default
                );
            }

            usort($data['currencies'], function($v) { return !$v['default']; });

            $data['routes'] = array();
            foreach ($this->loaded->model->module_nitropack->getWarmupRoutes() as $warmupRoute) {
                $data['routes'][] = array(
                    'status' => in_array($warmupRoute['value'], $nitropack->setting->get('included_warmup_routes', array())),
                    'text' => $warmupRoute['name'] . ' (' . $warmupRoute['value'] . ')',
                    'value' => $warmupRoute['value']
                );
            }

            $json['status'] = true;
            $json['html'] = $this->loadView($this->ext->route->module->nitropack . '_warmup', $data);
        }, function($errorMessage) use (&$json) {
            $json['status'] = false;
            $json['message'] = $errorMessage;
        });

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function purge() {
        $json = array(
            'type' => 'info',
            'message' => ''
        );

        if (!$this->validatePermissions()) {
            $json['type'] = 'danger';
            $json['message'] = $this->error['warning'];
        } else {
            Nitropack::executionBlock(function() use (&$json) {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

                $purge_type = isset($this->request->get['purge_type']) && in_array($this->request->get['purge_type'], array('route')) ? $this->request->get['purge_type'] : null;
                $purge_value = isset($this->request->get['purge_value']) ? $this->request->get['purge_value'] : null;

                if ($purge_type !== null && $purge_value !== null) {
                    $tag = new Tag($purge_type, $purge_value);

                    $result = $nitropack->sdk->purgeCache(null, $tag->getText(), PurgeType::PAGECACHE_ONLY, sprintf(self::REASON_MANUAL_PURGE_TYPE, $purge_type, $purge_value));

                    $json['message'] = sprintf($this->language->get('success_purge_' . $purge_type), $purge_value);
                } else {
                    $result = $nitropack->sdk->purgeCache(null, null, PurgeType::COMPLETE, self::REASON_MANUAL_PURGE_ALL);

                    $json['message'] = $this->language->get('success_purge');
                }

                if ($result) {
                    $json['type'] = 'success';
                } else {
                    $json['type'] = 'danger';
                    $json['message'] = $this->language->get('error_purge');
                }
            }, function($error) use (&$json) {
                $json['type'] = 'danger';
                $json['message'] = $error;
            });
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function invalidate() {
        $json = array(
            'type' => 'info',
            'message' => ''
        );

        if (!$this->validatePermissions()) {
            $json['type'] = 'danger';
            $json['message'] = $this->error['warning'];
        } else {
            Nitropack::executionBlock(function() use (&$json) {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

                $invalidate_type = isset($this->request->get['invalidate_type']) && in_array($this->request->get['invalidate_type'], array('route')) ? $this->request->get['invalidate_type'] : null;
                $invalidate_value = isset($this->request->get['invalidate_value']) ? $this->request->get['invalidate_value'] : null;

                if ($invalidate_type !== null && $invalidate_value !== null) {
                    $tag = new Tag($invalidate_type, $invalidate_value);

                    $result = $nitropack->sdk->invalidateCache(null, $tag->getText(), sprintf(self::REASON_MANUAL_INVALIDATE_TYPE, $invalidate_type, $invalidate_value));

                    $json['message'] = sprintf($this->language->get('success_invalidate_' . $invalidate_type), $invalidate_value);
                } else {
                    $result = $nitropack->sdk->invalidateCache(null, null, self::REASON_MANUAL_INVALIDATE_ALL);

                    $json['message'] = $this->language->get('success_invalidate');
                }

                if ($result) {
                    $json['type'] = 'success';
                } else {
                    $json['type'] = 'danger';
                    $json['message'] = $this->language->get('error_invalidate');
                }
            }, function($error) use (&$json) {
                $json['type'] = 'danger';
                $json['message'] = $error;
            });
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /* END ADMIN PANEL METHODS */

    /* START EVENTS */

    // Events - Products
    public function productAfterAdd($route = null, $args = null, $output = null) {
        $product_id = $this->getEventEntityIdAfterAdd($route, $args, $output);

        $this->loaded->model->event_product->afterAdd($product_id);
    }

    public function productBeforePersist($route = null, $args = null, $output = null) {
        $product_id = $this->getEventEntityIdBeforeEdit($route, $args, $output);

        $this->loaded->model->event_product->beforePersist($product_id);
    }

    public function productAfterEdit($route = null, $args = null, $output = null) {
        $product_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_product->afterEdit($product_id);
    }

    public function productAfterDelete($route = null, $args = null, $output = null) {
        $product_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_product->afterDelete($product_id);
    }

    public function productAfterCopy($route = null, $args = null, $output = null) {
        $product_id = $this->loaded->model->module_nitropack->getLastProductId();

        $this->loaded->model->event_product->afterAdd($product_id);
    }

    // Events - Categories

    public function categoryAfterAdd($route = null, $args = null, $output = null) {
        $category_id = $this->getEventEntityIdAfterAdd($route, $args, $output);

        $this->loaded->model->event_category->afterAdd($category_id);
    }

    public function categoryBeforePersist($route = null, $args = null, $output = null) {
        $category_id = $this->getEventEntityIdBeforeEdit($route, $args, $output);

        $this->loaded->model->event_category->beforePersist($category_id);
    }

    public function categoryAfterEdit($route = null, $args = null, $output = null) {
        $category_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_category->afterEdit($category_id);
    }

    public function categoryAfterDelete($route = null, $args = null, $output = null) {
        $category_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_category->afterDelete($category_id);
    }

    // Events - Manufacturers

    public function manufacturerAfterAdd($route = null, $args = null, $output = null) {
        $manufacturer_id = $this->getEventEntityIdAfterAdd($route, $args, $output);

        $this->loaded->model->event_manufacturer->afterAdd($manufacturer_id);
    }

    public function manufacturerBeforePersist($route = null, $args = null, $output = null) {
        $manufacturer_id = $this->getEventEntityIdBeforeEdit($route, $args, $output);

        $this->loaded->model->event_manufacturer->beforePersist($manufacturer_id);
    }

    public function manufacturerAfterEdit($route = null, $args = null, $output = null) {
        $manufacturer_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_manufacturer->afterEdit($manufacturer_id);
    }

    public function manufacturerAfterDelete($route = null, $args = null, $output = null) {
        $manufacturer_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_manufacturer->afterDelete($manufacturer_id);
    }

    // Events - Informations

    public function informationAfterAdd($route = null, $args = null, $output = null) {
        $information_id = $this->getEventEntityIdAfterAdd($route, $args, $output);

        $this->loaded->model->event_information->afterAdd($information_id);
    }

    public function informationBeforePersist($route = null, $args = null, $output = null) {
        $information_id = $this->getEventEntityIdBeforeEdit($route, $args, $output);

        $this->loaded->model->event_information->beforePersist($information_id);
    }

    public function informationAfterEdit($route = null, $args = null, $output = null) {
        $information_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_information->afterEdit($information_id);
    }

    public function informationAfterDelete($route = null, $args = null, $output = null) {
        $information_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_information->afterDelete($information_id);
    }

    // Events - Reviews

    public function reviewAfterAdd($route = null, $args = null, $output = null) {
        $review_id = $this->getEventEntityIdAfterAdd($route, $args, $output);

        $this->loaded->model->event_review->afterAdd($review_id);
    }

    public function reviewBeforePersist($route = null, $args = null, $output = null) {
        $review_id = $this->getEventEntityIdBeforeEdit($route, $args, $output);

        $this->loaded->model->event_review->beforePersist($review_id);
    }

    public function reviewAfterEdit($route = null, $args = null, $output = null) {
        $review_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_review->afterEdit($review_id);
    }

    public function reviewAfterDelete($route = null, $args = null, $output = null) {
        $review_id = $this->getEventEntityIdAfterEdit($route, $args, $output);

        $this->loaded->model->event_review->afterDelete($review_id);
    }


    public function updateActiveLanguages($route = null, $args = null, $output = null) {
        $this->loaded->model->module_nitropack->updateActiveLanguages();
    }

    public function updateDefaults($route = null, $args = null, $output = null) {
        $this->loaded->model->module_nitropack->updateDefaultLanguage();
        $this->loaded->model->module_nitropack->updateDefaultCurrency();
        $this->loaded->model->module_nitropack->updateMaintenanceMode();
        $this->loaded->model->module_nitropack->updateStoreId();
    }

    // Only works for OpenCart 2.3.x+
    public function menuItem(&$route, &$args, &$output) {
        if (!$this->user->hasPermission('access', $this->ext->route->module->nitropack)) {
            return;
        }

        foreach ($args['menus'] as &$menu) {
            if (isset($menu['id']) && $menu['id'] == 'menu-system') {
                $children = array();

                foreach ($this->getStores() as $store) {
                    $children[] = array(
                        'name' => $store['name'],
                        'children' => array(),
                        'href' => $this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $store['store_id'], $this->getUrlSsl())
                    );
                }

                array_push($menu['children'], array(
                    'name' => $this->language->get('heading_title'),
                    'children' => $children,
                    'href' => ''
                ));

                return;
            }
        }
    }

    /* END EVENTS */

    protected function getEventEntityIdAfterAdd($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            return (int)$output;
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            return (int)$args;
        } else {
            return (int)$route;
        }
    }

    protected function getEventEntityIdBeforeEdit($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            return (int)$args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            return (int)$args;
        } else {
            return (int)$route;
        }
    }

    protected function getEventEntityIdAfterEdit($route = null, $args = null, $output = null) {
        if (version_compare(VERSION, '2.3.0.0', '>=')) {
            return (int)$args[0];
        } else if (version_compare(VERSION, '2.2.0.0', '>=')) {
            return (int)$output;
        } else {
            return (int)$route;
        }
    }

    protected function validatePermissions() {
        if (!$this->user->hasPermission('modify', $this->ext->route->module->nitropack)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return empty($this->error);
    }

    protected function validateConnect() {
        $stores = $this->getStores();
        
        if (!isset($this->request->post['store_id']) || !isset($stores[(int)$this->request->post['store_id']])) {
            $this->error['store_id'] = $this->language->get('text_error_store_id');
        }

        if (empty($this->request->post['site_id']) || trim($this->request->post['site_id']) == '') {
            $this->error['site_id'] = $this->language->get('text_error_site_id');
        }

        if (empty($this->request->post['site_secret']) || trim($this->request->post['site_secret']) == '') {
            $this->error['site_secret'] = $this->language->get('text_error_site_secret');
        }

        if (!empty($this->error)) {
            $this->error['warning'] = $this->language->get('error_form');
        } else {
            Nitropack::executionBlock(function() {
                $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

                $nitropack->validate(trim($this->request->post['site_id']), trim($this->request->post['site_secret']));
            }, function($error) {
                $this->error['warning'] = sprintf($this->language->get('error_connect'), $error);
            });
        }

        return empty($this->error);
    }

    protected function showConnect() {
        $data = array();

        Nitropack::executionBlock(function() use (&$data) {
            $nitropack = $this->nitropackFromStoreUrl($this->store['url']);

            $this->loadDataField($nitropack, $data, 'store_id');
            $this->loadDataField($nitropack, $data, 'site_id');
            $this->loadDataField($nitropack, $data, 'site_secret');
        }, function($error) use (&$data) {
            $this->error['warning'] = $error;

            $this->loadDataFieldNoNitro($data, 'store_id');
            $this->loadDataFieldNoNitro($data, 'site_id');
            $this->loadDataFieldNoNitro($data, 'site_secret');
        });

        $this->document->addStyle('view/stylesheet/vendor/nitropackio/nitropack.css?nitropack_version=' . $this->ext->version);
        // $this->document->addStyle('view/stylesheet/vendor/nitropackio/connect.css?nitropack_version=' . $this->ext->version);
        $this->document->addScript('view/javascript/vendor/nitropackio/connect.js?nitropack_version=' . $this->ext->version);

        $this->document->setTitle($this->language->get('heading_title_connect'));

        $this->loadDataCommon($data);

        $this->loadErrorField($data, 'store_id');
        $this->loadErrorField($data, 'site_id');
        $this->loadErrorField($data, 'site_secret');

        $data['text_connect'] = sprintf($this->language->get('text_connect'), $this->store['name']);
        $data['button_connect'] = sprintf($this->language->get('button_connect'), $this->store['name']);
        $data['info_connect_register_info'] = sprintf($this->language->get('info_connect_register_info'), self::URL_NITROPACK_IO);
        $data['info_connect_credentials_info'] = sprintf($this->language->get('info_connect_credentials_info'), self::URL_TUTORIAL_CREDENTIALS);
        $data['connect'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/connect', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));

        $this->response->setOutput($this->loadView($this->ext->route->module->nitropack . '_connect', $data));
    }

    protected function showManage() {
        $nitropack = null;

        Nitropack::executionBlock(function() use (&$nitropack) {
            $nitropack = $this->nitropackFromStoreUrl($this->store['url']);
        }, function($error) {
            $this->session->data['error'] = $error;

            $this->responseRedirect($this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        });

        $this->document->addStyle('view/stylesheet/vendor/nitropackio/nitropack.css?nitropack_version=' . $this->ext->version);
        $this->document->addStyle('view/stylesheet/vendor/nitropackio/manage.css?nitropack_version=' . $this->ext->version);
        $this->document->addScript($nitropack->sdk->embedJsUrl() . '?nitropack_version=' . $this->ext->version);
        $this->document->addScript('view/javascript/vendor/nitropackio/manage.js?nitropack_version=' . $this->ext->version);

        $this->document->setTitle($this->language->get('heading_title_manage'));

        $data = array();

        $this->loadDataCommon($data);

        $data['standard_pages'] = $this->loaded->model->module_nitropack->getStandardPages($nitropack->setting->get('excluded_standard_pages', array()));
        $data['custom_pages'] = $nitropack->setting->get('included_custom_pages', array());
        $data['local_preset'] = $nitropack->setting->get('local_preset', null);
        $data['all_pages'] = $this->loaded->model->module_nitropack->getAllPages();
        $this->loadDataField($nitropack, $data, 'allow_cart', 1);
        $this->loadDataField($nitropack, $data, 'compression', 1);
        $this->loadDataField($nitropack, $data, 'auto_cache_clear_admin_product', 1);
        $this->loadDataField($nitropack, $data, 'auto_cache_clear_admin_category', 1);
        $this->loadDataField($nitropack, $data, 'auto_cache_clear_admin_information', 1);
        $this->loadDataField($nitropack, $data, 'auto_cache_clear_admin_manufacturer', 1);
        $this->loadDataField($nitropack, $data, 'auto_cache_clear_admin_review', 1);
        $this->loadDataField($nitropack, $data, 'auto_cache_clear_order', 1);
        $this->loadDataField($nitropack, $data, 'warmup', 1);
        $this->loadDataField($nitropack, $data, 'excluded_warmup_languages', array());
        $this->loadDataField($nitropack, $data, 'excluded_warmup_currencies', array());
        $this->loadDataField($nitropack, $data, 'included_warmup_routes', array());

        $data['text_manage'] = sprintf($this->language->get('text_manage'), $this->store['name']);
        $data['quicksetup'] = $nitropack->sdk->integrationUrl("quicksetup");
        $data['beforeafter'] = $nitropack->sdk->integrationUrl("beforeafter");
        $data['optimizations'] = $nitropack->sdk->integrationUrl("optimizations");
        $data['plan'] = $nitropack->sdk->integrationUrl("plan");
        $data['site_id'] = $nitropack->setting->get("site_id");
        $data['status'] = $nitropack->setting->get('status', '0');
        $data['field_status'] = $this->ext->oc->field->status;
        
        $data['info_auto_cache_clear'] = sprintf($this->language->get('info_auto_cache_clear'), self::URL_DOCUMENTATION);

        $site = null;

        Nitropack::executionBlock(function() use (&$site) {
            $site = Domain::parse($this->store['url']);
        }, function($error) use (&$site) {
            $site = $this->store['url'];
        });

        $data['site'] = $site;

        $data['button_disconnect'] = sprintf($this->language->get('button_disconnect'), $this->store['name']);
        $data['disconnect'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/disconnect', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['manage'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/manage', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['purge'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/purge', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['invalidate'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/invalidate', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['error_log'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/error_log', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['debug_log'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/debug_log', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['warmup_form'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/warmup', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['warmup_stats_url'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/warmup_stats', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['warmup_start'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/warmup_start', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['warmup_pause'] = $this->htmlEntityDecode($this->url->link($this->ext->route->module->nitropack . '/warmup_pause', $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl()));
        $data['warmup_details'] = $this->getWarmupDetails($nitropack->setting->get('excluded_warmup_languages', array()), $nitropack->setting->get('excluded_warmup_currencies', array()), $nitropack->setting->get('included_warmup_routes', array()));
        $data['warmup_stats'] = $this->getWarmupStats($nitropack);

        $this->response->setOutput($this->loadView($this->ext->route->module->nitropack . '_manage', $data));
    }

    protected function getWarmupStats($nitropack) {
        $result = array(
            'text_warmup_status' => $this->language->get('text_warmup_status_unknown'),
            'is_warmup_active' => false,
            'status' => $nitropack->setting->get('warmup', false),
            'details' => array()
        );

        if ($nitropack->setting->get('warmup', false)) {
            $stats = $nitropack->sdk->getApi()->getWarmupStats();
            
            if ((bool)$stats['status']) {
                $result['details'][] = array(
                    'key' => $this->language->get('text_warmup_detail_status'),
                    'value' => $this->language->get('text_warmup_detail_enabled')
                );
            } else {
                $result['details'][] = array(
                    'key' => $this->language->get('text_warmup_detail_status'),
                    'value' => $this->language->get('text_warmup_detail_paused')
                );
            }

            $result['is_warmup_active'] = (bool)$stats['status'] && (bool)$stats['pending'];

            $result['details'][] = array(
                'key' => $this->language->get('text_warmup_detail_pending'),
                'value' => $stats['pending']
            );

            $result['details'][] = array(
                'key' => $this->language->get('text_warmup_detail_request_count'),
                'value' => $stats['request_count']
            );

            $result['details'][] = array(
                'key' => $this->language->get('text_warmup_detail_avg_ttfb'),
                'value' => number_format($stats['avg_ttfb'], 0, ',', '') . 'ms'
            );

            if ((bool)$stats['status']) {
                if ($stats['pending'] > 0) {
                    $result['text_warmup_status'] = sprintf($this->language->get('text_warmup_status_enabled_working'), (string)$stats['pending']);
                } else {
                    $result['text_warmup_status'] = $this->language->get('text_warmup_status_enabled_not_working');
                }
            } else {
                if ($stats['pending'] > 0) {
                    $result['text_warmup_status'] = sprintf($this->language->get('text_warmup_status_paused_working'), (string)$stats['pending']);
                } else {
                    $result['text_warmup_status'] = $this->language->get('text_warmup_status_paused_not_working');
                }
            }
        } else {
            $result['text_warmup_status'] = $this->language->get('text_warmup_status_disabled');
        }

        return $result;
    }

    protected function getWarmupDetails($excluded_languages, $excluded_currencies, $included_routes) {
        $languages = array();

        foreach ($this->loaded->model->module_nitropack->getLanguages() as $storeLanguage) {
            if (!in_array($storeLanguage['code'], $excluded_languages)) {
                $languages[] = $storeLanguage['code'];
            }
        }

        $currencies = array();

        foreach ($this->loaded->model->module_nitropack->getCurrencies() as $storeCurrency) {
            if (!in_array($storeCurrency['code'], $excluded_currencies)) {
                $currencies[] = $storeCurrency['code'];
            }
        }

        $routes = array();

        foreach ($this->loaded->model->module_nitropack->getWarmupRoutes() as $warmupRoute) {
            if (in_array($warmupRoute['value'], $included_routes)) {
                $routes[] = $warmupRoute['name'];
            }
        }

        $details = array();

        if ($languages) {
            $details[] = sprintf($this->language->get('text_warmup_detail_language'), implode(', ', $languages));
        }

        if ($currencies) {
            $details[] = sprintf($this->language->get('text_warmup_detail_currency'), implode(', ', $currencies));
        }

        if ($routes) {
            $details[] = sprintf($this->language->get('text_warmup_detail_route'), implode(', ', $routes));
        }

        return implode('<br />', $details);
    }

    protected function loadChildren(&$data) {
        if (version_compare(VERSION, '2.0.0.0', '>=')) {
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
        } else {
            $data['column_left'] = '';

            $this->children = array(
                'common/header',
                'common/footer'
            );
        }
    }

    protected function loadDataField($nitropack, &$data, $key, $default = null) {
        if (isset($this->request->post[$key])) {
            $data[$key] = trim($this->request->post[$key]);
        } else {
            $data[$key] = $nitropack->setting->get($key, $default);
        }
    }

    protected function loadDataFieldNoNitro(&$data, $key, $default = null) {
        if (isset($this->request->post[$key])) {
            $data[$key] = trim($this->request->post[$key]);
        } else {
            $data[$key] = $default;
        }
    }

    protected function loadErrorField(&$data, $key) {
        if (isset($this->error[$key])) {
            $data['error_' . $key] = $this->error[$key];
        } else {
            $data['error_' . $key] = null;
        }
    }

    protected function loadDataCommon(&$data) {
        $data = array_merge($data, $this->loaded->language);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $this->getParamToken(), $this->getUrlSsl())
        );

        $cancel = $this->url->link($this->ext->oc->route->extension, $this->getParamToken() . '&type=module', $this->getUrlSsl());

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $cancel
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $this->store_id, $this->getUrlSsl())
        );

        $data['cancel'] = $cancel;
        $data['documentation'] = self::URL_DOCUMENTATION;
        $data['external_settings'] = self::URL_EXTERNAL_SETTINGS;
        $data['messenger'] = self::URL_MESSENGER;

        $data['stores'] = array();
        $data['store'] = null;

        foreach ($this->getStores() as $store_id => $store) {
            $new_store = $store;
            $is_current_store = $store_id == $this->store_id;
            
            $new_store['current'] = $is_current_store;
            $new_store['admin_href'] = $this->url->link($this->ext->route->module->nitropack, $this->getParamToken() . '&store_id=' . $store_id, $this->getUrlSsl());

            $data['stores'][] = $new_store;

            if ($is_current_store) {
                $data['store'] = $store;
            }
        }

        $data['error'] = "";

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (isset($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        if ($this->isIE()) {
            $data['error'] = $this->language->get('error_internet_explorer');
        }

        if (!$this->isModificationApplied()) {
            $data['error'] = sprintf($this->language->get('error_modification'), $this->url->link($this->ext->oc->route->modification, $this->getParamToken(), $this->getUrlSsl()));
        }

        $data['success'] = "";

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['warning'] = "";

        if ($this->loaded->model->module_nitropack->getSettingValue('config_maintenance', 0)) {
            $data['warning'] = sprintf($this->language->get('error_maintenance_mode'), $this->url->link('setting/setting', $this->getParamToken(), $this->getUrlSsl()));
        }

        $this->loadChildren($data);
    }

    private function isModificationApplied() {
        $file = DIR_MODIFICATION . '/' . $this->ext->modified_file;

        if (file_exists($file)) {
            $matches = array();

            if (preg_match('~CURRENT_VERSION - ([0-9\.]+)~', file_get_contents($file), $matches)) {
                return $matches[1] == $this->ext->version; 
            }
        }

        return false;
    }

    private function isIE() {
        $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        
        if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false)) {
            return true;
        }

        return false;
    }
}
