<?php
class ControllerExtensionInformationGdprRequest extends Controller {
    private $error = array();

    public function index() {

        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('module_gdpr');

        // If forms are to be set private, force login
        if($settings['module_gdpr_forms_are_private']) {
            if (!$this->customer->isLogged()) {
                $this->session->data['redirect'] = $this->url->link('extension/information/gdpr_request', '', true);

                $this->response->redirect($this->url->link('account/login', '', true));
            }
        }

        $this->load->language('extension/information/gdpr_request');
        $this->load->model('extension/module/gdpr');
        $this->load->model('setting/setting');

        $request_types = array(
            'personal_data_report' => 1,
            'forget_me' => 2,
        );

        $this->document->setTitle($this->language->get('heading_title'));

        $settings = $this->model_setting_setting->getSetting('module_gdpr');

        // If module is disabled force redirect the GDPR request URL to home page
        //if(!$settings['module_gdpr_status']) {
        //  $this->response->redirect($this->url->link('common/home'));
        //}

        // Send the confirmation email
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            // Generate unique hash/code
            $confirmation_string = $this->model_extension_module_gdpr->generateRandomString(30);

            // Record the request
            $request_data = array(
                'confirmation_string' => $confirmation_string,
                'email' => $this->request->post['email'],
                'code' => '',
                'http_user_agent' => $this->request->server['HTTP_USER_AGENT'],
                'http_accept_language' => $this->request->server['HTTP_ACCEPT_LANGUAGE'],
                'server_addr' => $this->request->server['SERVER_ADDR'],
                'remote_addr' => $this->request->server['REMOTE_ADDR'],
                'timestamp' => $this->request->server['REQUEST_TIME'],
                'request_time' => date("Y-m-d H:i:s", $this->request->server['REQUEST_TIME']),
                'request_type' => $request_types['personal_data_report'],
            );

            $request_id = $this->model_extension_module_gdpr->addRequest($request_data);

            // Generate request code
            $request_code = $this->model_extension_module_gdpr->getRequestCode($request_id, $this->request->server['REQUEST_TIME']);

            // Store request code
            $this->model_extension_module_gdpr->updateRequestCode($request_id, $request_code);

            // Send email with a link (confirm_gdpr?email=$email&code=$code)

            $data = array(
                'store' => $this->config->get('config_name'),
                'confirmation_email_text_heading' => $this->language->get('confirmation_email_text_heading'),
                'confirmation_email_text_instructions' => $this->language->get('confirmation_email_text_instructions'),
                'confirmation_email_text_confirm' => $this->language->get('confirmation_email_text_confirm'),
                'confirmation_email_text_smallprint' => $this->language->get('confirmation_email_text_smallprint'),
                'confirmation_email_link' => HTTP_SERVER . 'index.php?route=extension/information/gdpr_request/confirm&request=' . $request_code . '&confirmation_code=' . $confirmation_string,
                'forget_me' => false,
            );

            // Load mail template with data
            $html = $this->load->view('extension/module/gdpr/mail_confirm', $data);

            $mail = new Mail($this->config->get('config_mail_engine'));
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
            $mail->setTo($this->request->post['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($this->language->get('confirmation_email_text_heading'), ENT_QUOTES, 'UTF-8'));
            $mail->setHtml($html);
            $mail->send();

            $this->response->redirect($this->url->link('extension/information/gdpr_request/success'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/information/gdpr_request')
        );

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_gdpr_request'] = $this->language->get('text_gdpr_request');
        $data['text_instructions'] = $this->language->get('text_instructions');

        $data['entry_email'] = $this->language->get('entry_email');

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['enquiry'])) {
            $data['error_enquiry'] = $this->error['enquiry'];
        } else {
            $data['error_enquiry'] = '';
        }

        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->url->link('extension/information/gdpr_request', '', 'SSL');

        $this->load->model('tool/image');

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = $this->customer->getEmail();
        }

        // Captcha
        if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status')) {
            $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
        } else {
            $data['captcha'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/information/gdpr_request', $data));

    }

    public function confirm() {

        $this->load->language('extension/information/gdpr_request');
        $this->load->model('extension/module/gdpr');

        $this->document->setTitle($this->language->get('heading_title'));

        if (!empty($this->request->get['request']) && !empty($this->request->get['confirmation_code'])) {
            // Check if request and confirmation string are Correct
            $request = $this->model_extension_module_gdpr->getConfirmationString($this->request->get['request']);

            if($request['confirmation_string'] === $this->request->get['confirmation_code']) {
                // Change request status to confirmed
                $this->model_extension_module_gdpr->updateRequestStatus($request['request_id'], 1);

                // Process Request
                $this->processRequest($request);

                // Redirect to success page
                $texts = array(
                    'heading_title' => $this->language->get('request_success_heading_title'),
                    'text_message' => $this->language->get('request_success_text_message'),
                );
                $this->success($texts);
            } else {
                // Confirmation failed
                $texts = array(
                    'heading_title' => $this->language->get('request_failed_heading_title'),
                    'text_message' => $this->language->get('request_failed_text_message'),
                );
                $this->failed($texts);
            }

        } else {
            $texts = array(
                'heading_title' => $this->language->get('request_failed_heading_title'),
                'text_message' => $this->language->get('request_failed_text_message'),
            );
            $this->failed($texts);
        }
    }

    public function failed($texts = array()) {
        $this->load->language('extension/information/gdpr_request');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/information/gdpr_request')
        );

        if(!empty($texts)) {
            $data['heading_title'] = $texts['heading_title'];
            $data['text_message'] = $texts['text_message'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_message'] = $this->language->get('text_message');
        }

        $data['button_continue'] = $this->language->get('button_continue');
        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/module/gdpr/gdpr_failed', $data));

    }

    public function success($texts = array()) {
        $this->load->language('extension/information/gdpr_request');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/information/gdpr_request')
        );

        if(!empty($texts)) {
            $data['heading_title'] = $texts['heading_title'];
            $data['text_message'] = $texts['text_message'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_message'] = $this->language->get('text_success');
        }

        $data['button_continue'] = $this->language->get('button_continue');
        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('common/success', $data));

    }

    protected function validate() {

        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('module_gdpr');
        $demo_mode = $this->model_setting_setting->getSetting('module_demo');

        // Email format
        if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
            $bad_email_format = true;
        }

        // Email submitted matches logged in user
        if($settings['module_gdpr_forms_are_private']) {
            if ($this->customer->isLogged() && ($this->customer->getEmail() !== $this->request->post['email'] && empty($bad_email_format))) {
                $this->error['email'] = $this->language->get('error_email_not_matching');
                $user_not_checking_their_own_address = true;
            }
        }

        // Email exists in the database
        if(empty($demo_mode['module_demo_gdpr']) || $demo_mode['module_demo_gdpr'] !== '1') {
            if(!$this->model_extension_module_gdpr->isExistingCustomerEmail($this->request->post['email']) && !$this->model_extension_module_gdpr->isExistingOrderEmail($this->request->post['email']) && empty($bad_email_format) && empty($user_not_checking_their_own_address)) {
                $this->error['email'] = $this->language->get('error_email_not_existing');
            }
        }

        // Customer did not exceed the daily limit of requests
        if(!$this->model_extension_module_gdpr->isAllowedGdprRequest($this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_max_requests_day');
        }

        // Captcha
        if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status')) {
            $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

            if ($captcha) {
                $this->error['captcha'] = $captcha;
            }
        }

        return !$this->error;
    }

    /**
     * Process GDPR request
     *
     * @param  [array] $request [request_id, email, confirmation_string]
     * @return [type]        [description]
     */
    private function processRequest($request) {
        $this->load->language('extension/information/gdpr_request');
        $this->load->model('extension/module/gdpr');
        $this->load->model('setting/setting');

        $settings = $this->model_setting_setting->getSetting('module_gdpr');

        $demo_mode = $this->model_setting_setting->getSetting('module_demo');
        $demo = false;
        if(!empty($demo_mode['module_demo_gdpr']) && $demo_mode['module_demo_gdpr'] === '1') {
            $demo = true;
        }

        $guest_only = false;
        if(!$this->model_extension_module_gdpr->isExistingCustomerEmail($request['email'])) {
            $guest_only = true;
        }

        // No guest requests are allowed in the demo mode
        if($demo) {
            $guest_only = false;
        }

        // Language settings
        $this->load->model('localisation/language');
        $current_language_code = $this->session->data['language'];
        $languages = $this->model_localisation_language->getLanguages();
        $language_id = $languages[$current_language_code]['language_id'];

        // Full report is only created for customers with an account
        if(!$guest_only) {
            // Get customer data and ID
            if(!$demo) {
                // Never attempt to process a request if email is not existing in the db
                if(!$this->model_extension_module_gdpr->isExistingCustomerEmail($request['email'])) {
                    exit($this->language->get('error_email_not_existing'));
                }
                $customer = $this->model_extension_module_gdpr->getCustomerData($request['email']);
            }

            if($demo) {
                $customer = $this->model_extension_module_gdpr->getCustomerData('demo@demo.ie');
            }

            $customer_id = $customer['customer_id'];
            // Get customer addresses
            $addresses = $this->model_extension_module_gdpr->getCustomerAddresses($customer_id);
            // Get customer history, ip, activity logs, login
            $info = $this->model_extension_module_gdpr->getCustomerInformation($customer_id, $request['email']);
            // Get customer rewards
            $rewards = $this->model_extension_module_gdpr->getCustomerRewards($customer_id);
            // Get customer transactions
            $transactions = $this->model_extension_module_gdpr->getCustomerTransactions($customer_id);
            // Get customer wishlist
            $wishlist = $this->model_extension_module_gdpr->getCustomerWishlist($customer_id);
            // Get customer orders
            $orders = $this->model_extension_module_gdpr->getCustomerOrders($customer_id);
            // Product reviews
            $product_reviews = $this->model_extension_module_gdpr->getCustomerProductReviews($customer_id);
        }

        if($guest_only) {
            $customer = array();
            $addresses = array();
            $info = array();
            $rewards = array();
            $transactions = array();
            $wishlist = array();
            $product_reviews = array();
            // Get guest orders
            $orders = $this->model_extension_module_gdpr->getCustomerOrdersByEmail($request['email']);
        }

        // Get customer GDPR requests recorded
        $gdpr_requests = $this->model_extension_module_gdpr->getCustomerGdprRequests($request['email']);

        // Get other locations of data
        $other_data = $settings['module_gdpr_locations_of_other_data'][$language_id];
        // Get server locations
        $server_locations = $settings['module_gdpr_locations_of_servers'][$language_id];
        // Email Header
        $email_header = $settings['module_gdpr_email_header'][$language_id];
        // Email Footer
        $email_footer = $settings['module_gdpr_email_footer'][$language_id];
        // Request Statuses
        $status = array(
            0 => $settings['module_gdpr_pending_status'][$language_id],
            1 => $settings['module_gdpr_confirmed_status'][$language_id],
            2 => $settings['module_gdpr_emailed_status'][$language_id],
            3 => $settings['module_gdpr_account_deleted_status'][$language_id],
        );

        $headers = array(
            'empty_record' => $this->language->get('gdpr_report_empty_record'),
            'heading_title' => $this->language->get('gdpr_report_heading_title'),
            'text_account_details' => $this->language->get('gdpr_report_text_account_details'),
            'text_addresses' => $this->language->get('gdpr_report_text_addresses'),
            'text_cart_contents' => $this->language->get('gdpr_report_text_cart_contents'),
            'text_info' => $this->language->get('gdpr_report_text_info'),
            'text_locations_of_other_data' => $this->language->get('gdpr_report_locations_of_other_data'),
            'text_locations_of_servers' => $this->language->get('gdpr_report_locations_of_servers'),
            'text_wishlist_contents' => $this->language->get('gdpr_report_text_wishlist_contents'),

            'customer' => array(
                'cart' => $this->language->get('cart'),
                'company' => $this->language->get('company'),
                'date_added' => $this->language->get('date_added'),
                'email' => $this->language->get('email'),
                'fax' => $this->language->get('fax'),
                'firstname' => $this->language->get('firstname'),
                'lastname' => $this->language->get('lastname'),
                'newsletter' => $this->language->get('newsletter'),
                'not_subscribed' => $this->language->get('not_subscribed'),
                'subscribed' => $this->language->get('subscribed'),
                'telephone' => $this->language->get('telephone'),
                'wishlist' => $this->language->get('wishlist'),
            ),
            'gdpr' => array(
                'gdpr_email' => $this->language->get('gdpr_email'),
                'gdpr_http_accept_language' => $this->language->get('gdpr_http_accept_language'),
                'gdpr_http_user_agent' => $this->language->get('gdpr_http_user_agent'),
                'gdpr_requests' => $this->language->get('gdpr_requests'),
                'gdpr_request_time' => $this->language->get('gdpr_request_time'),
                'gdpr_remote_addr' => $this->language->get('gdpr_remote_addr'),
                'gdpr_server_addr' => $this->language->get('gdpr_server_addr'),
                'gdpr_single_request' => $this->language->get('gdpr_single_request'),
                'gdpr_status' => $this->language->get('gdpr_status'),
            ),
            'info' => array(
                'activities' => $this->language->get('activities'),
                'comment' => $this->language->get('comment'),
                'customer_id' => $this->language->get('customer_id'),
                'data' => $this->language->get('data'),
                'date_added' => $this->language->get('date_added'),
                'date_modified' => $this->language->get('date_modified'),
                'email' => $this->language->get('email'),
                'history' => $this->language->get('history'),
                'id' => $this->language->get('id'),
                'ip' => $this->language->get('ip'),
                'ips' => $this->language->get('ips'),
                'key' => $this->language->get('key'),
                'logins' => $this->language->get('logins'),
                'online' => $this->language->get('online'),
                'single_activity' => $this->language->get('single_activity'),
                'single_history' => $this->language->get('single_history'),
                'single_ip' => $this->language->get('single_ip'),
                'single_login' => $this->language->get('single_login'),
                'referer' => $this->language->get('referer'),
                'total' => $this->language->get('total'),
                'url' => $this->language->get('url'),
            ),
            'order' => array(
                'address' => $this->language->get('address'),
                'city' => $this->language->get('city'),
                'comment' => $this->language->get('comment'),
                'company' => $this->language->get('company'),
                'country' => $this->language->get('country'),
                'currency_code' => $this->language->get('currency_code'),
                'date_added' => $this->language->get('date_added'),
                'date_modified' => $this->language->get('date_modified'),
                'email' => $this->language->get('email'),
                'fax' => $this->language->get('fax'),
                'firstname' => $this->language->get('firstname'),
                'invoice_no' => $this->language->get('invoice_no'),
                'ip' => $this->language->get('ip'),
                'lastname' => $this->language->get('lastname'),
                'order' => $this->language->get('order'),
                'postcode' => $this->language->get('postcode'),
                'payment_address' => $this->language->get('payment_address'),
                'payment_method' => $this->language->get('payment_method'),
                'products' => $this->language->get('products'),
                'shipping_address' => $this->language->get('shipping_address'),
                'shipping_method' => $this->language->get('shipping_method'),
                'store_name' => $this->language->get('store_name'),
                'telephone' => $this->language->get('telephone'),
                'total' => $this->language->get('total'),
                'user_agent' => $this->language->get('user_agent'),
            ),
            'product' => array(
                'model' => $this->language->get('model'),
                'name' => $this->language->get('name'),
                'price' => $this->language->get('price'),
                'reward' => $this->language->get('reward'),
                'total' => $this->language->get('total'),
                'tax' => $this->language->get('tax'),
                'quantity' => $this->language->get('quantity'),
            ),
            'product_review' => array(
                'date_added' => $this->language->get('date_added'),
                'name' => $this->language->get('name'),
                'rating' => $this->language->get('rating'),
                'reviews' => $this->language->get('button_reviews'),
                'text' => $this->language->get('comment'),
            ),
            'reward' => array(
                'customer_id' => $this->language->get('customer_id'),
                'date_added' => $this->language->get('date_added'),
                'description' => $this->language->get('reward_description'),
                'order_id' => $this->language->get('order_id'),
                'points' => $this->language->get('reward_points'),
                'rewards' => $this->language->get('rewards'),
                'single_reward' => $this->language->get('single_reward'),
            ),
            'transaction' => array(
                'amount' => $this->language->get('amount'),
                'customer_id' => $this->language->get('customer_id'),
                'date_added' => $this->language->get('date_added'),
                'description' => $this->language->get('description'),
                'order_id' => $this->language->get('order_id'),
                'single_transaction' => $this->language->get('single_transaction'),
                'transactions' => $this->language->get('transactions'),
            ),
            'wishlist' => array(
                'date_added' => $this->language->get('date_added'),
                'model' => $this->language->get('model'),
                'name' => $this->language->get('name'),
                'product' => $this->language->get('product'),
                'wishlist' => $this->language->get('wishlist'),
            ),
        );

        // Store all data
        $data = array(
            'addresses' => $addresses,
            'customer' => $customer,
            'email_header' => $email_header,
            'email_footer' => $email_footer,
            'gdpr_requests' => $gdpr_requests,
            'guest_only' => $guest_only,
            'headers' => $headers,
            'info' => $info,
            'orders' => $orders,
            'other_data' => $other_data,
            'product_reviews' => $product_reviews,
            'rewards' => $rewards,
            'server_locations' => $server_locations,
            'status' => $status,
            'transactions' => $transactions,
            'wishlist' => $wishlist,
        );

        // Load mail template with data
        $html = $this->load->view('extension/module/gdpr/gdpr_report', $data);

        $mail = new Mail($this->config->get('config_mail_engine'));
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
        $mail->setTo($request['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($this->language->get('gdpr_report_email_subject'), ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($html);
        $mail->send();

        // Update status of the request
        $this->model_extension_module_gdpr->updateRequestStatus($request['request_id'], 2);
    }
}
