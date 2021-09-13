<?php
class ControllerExtensionModuleFastcommentstatus extends Controller {
    public function index() {
        $this->load->language('extension/module/fast_comment_status');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        }




        $this->getList();

    }

    public function delete() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->session->data['success'] = $this->language->get('text_success');

            $this->load->model('extension/module/fast_comment_status');

            foreach ($this->request->post['selected'] as $selected) {
                $this->model_extension_module_fast_comment_status->deleteComment($selected);
            }
            $this->response->redirect($this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        }
        $this->getList();
    }

    public function edit() {
        $this->load->language('extension/module/fast_comment_status');

        $this->load->model('extension/module/fast_comment_status');

        $this->document->setTitle($this->language->get('heading_edit_template'));
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->session->data['success'] = $this->language->get('text_success');


            $this->model_extension_module_fast_comment_status->edit($this->request->get['fast_comment_id'],$this->request->post);

            $this->response->redirect($this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        }
        $this->getForm();
    }

    public function add() {
        $this->load->language('extension/module/fast_comment_status');

        $this->load->model('extension/module/fast_comment_status');

        $this->document->setTitle($this->language->get('heading_add_template'));
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->session->data['success'] = $this->language->get('text_success');


            $this->model_extension_module_fast_comment_status->add($this->request->post);

            $this->response->redirect($this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        }

        $this->getForm();
    }

    public function getFastComment() {
        $json = array();
        $this->load->model('extension/module/fast_comment_status');
        $comment = $this->model_extension_module_fast_comment_status->getDescription($this->request->post['fast_comment_id']);
        $json['comment'] = $comment['template_comment'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/fast_comment_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }
    public function validateForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/fast_comment_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (empty($this->request->post['template_name'])) {
            $this->error['error']['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    public function validate_form() {
        if (!$this->user->hasPermission('modify', 'extension/module/fast_comment_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }
    public function getList() {

        $this->load->model('extension/module/fast_comment_status');


        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();


        $data['heading_title'] = $this->language->get('heading_title');
        $data['error_permission'] = $this->language->get('error_permission');
        $data['text_success'] = $this->language->get('text_success');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_comment'] = $this->language->get('text_comment');
        $data['text_comment_help'] = $this->language->get('text_comment_help');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_delete'] = $this->language->get('text_delete');
        $data['button_add'] = $this->language->get('button_add');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['button_edit'] = $this->language->get('button_edit');

        $data['add_action'] = $this->url->link('extension/module/fast_comment_status/add','user_token=' . $this->session->data['user_token'],'SSL');
        $data['action_delete'] = $this->url->link('extension/module/fast_comment_status/delete','user_token=' . $this->session->data['user_token'],'SSL');

        if (isset($this->request->get['page']))
            $page = $this->request->get['page'];
        else {
            $page = 1;
        }

        $data['fast_templates'] = $this->model_extension_module_fast_comment_status->getComments();
        foreach ($data['fast_templates'] as $key=>$temp) {
                $data['fast_templates'][$key]['edit'] = $this->url->link('extension/module/fast_comment_status/edit', 'user_token=' . $this->session->data['user_token'] . '&fast_comment_id=' . $temp['fast_comment_id'], 'SSL');
        }

        $templateTotal = $this->model_extension_module_fast_comment_status->getTotalComments();

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $pagination = new Pagination();
        $pagination->total = $templateTotal;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'] . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($templateTotal) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($templateTotal - $this->config->get('config_limit_admin'))) ? $templateTotal : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $templateTotal, ceil($templateTotal / $this->config->get('config_limit_admin')));


        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/fast_comment_status_list', $data));

    }

    public function getForm() {
        if (isset($this->request->get['fast_comment_id'])) {
            $data['heading_title'] = $this->language->get('heading_edit_template');
        } else  {
            $data['heading_title'] = $this->language->get('heading_add_template');
        }

        $data['text_edit'] = $this->language->get('text_edit');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['text_comment'] = $this->language->get('text_comment');
        $data['text_comment_help'] = $this->language->get('text_comment_help');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('extension/module/fast_comment_status', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        if (!isset($this->request->get['fast_comment_id'])) {
            $data['action'] = $this->url->link('extension/module/fast_comment_status/add', 'user_token=' . $this->session->data['user_token'], 'SSL');
        } else {
            $data['action'] = $this->url->link('extension/module/fast_comment_status/edit', 'user_token=' . $this->session->data['user_token'] . '&fast_comment_id=' . $this->request->get['fast_comment_id'], 'SSL');
        }

        if (isset($this->request->get['fast_comment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $template_info = $this->model_extension_module_fast_comment_status->getComment($this->request->get['fast_comment_id']);
        }

        if (isset($this->request->post['template_name'])) {
            $data['template_name'] = $this->request->post['template_name'];
        } elseif (!empty($template_info)) {
            $data['template_name'] = $template_info['name'];
        } else {
            $data['template_name'] = '';
        }

        if (isset($this->request->post['template_comment'])) {
            $data['template_comment'] = $this->request->post['template_comment'];
        } elseif (isset($this->request->get['fast_comment_id'])) {
            $data['template_comment'] = $this->model_extension_module_fast_comment_status->getDescriptions($this->request->get['fast_comment_id']);
        } else {
            $data['template_comment'] = array();
        }
//      print_r($data['template_comment'] );



        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/fast_comment_status_form', $data));
    }

    public function install() {
        $sql = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fast_comment` (
          `fast_comment_id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          PRIMARY KEY (`fast_comment_id`)
        ) ENGINE=MyIsam DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
        $this->db->query($sql);
        $sql ="
        CREATE TABLE IF NOT EXISTS  `" . DB_PREFIX . "fast_comment_description` (
          `fast_comment_id` int(11) NOT NULL,
          `language_id` int(11) NOT NULL,
          `template_comment` text NOT NULL,
          PRIMARY KEY (`fast_comment_id`,`language_id`)
        ) ENGINE=MyIsam DEFAULT CHARSET=utf8";
        $this->db->query($sql);
    }
}
