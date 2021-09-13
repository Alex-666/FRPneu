<?php
class ControllerExtensionModuleVideo extends Controller {
	private $error = array(); 

	public function index() {   
		$this->load->language('extension/module/video');

		$this->document->setTitle($this->language->get('page_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('video', $this->request->post);
			$this->model_setting_setting->editSetting('module_video', array('module_video_status'=>$this->request->post['video_status']));		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('page_title'),
			'href'      => $this->url->link('extension/module/video', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/module/video', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);

		$data['user_token'] = $this->session->data['user_token'];

		$data['modules'] = array();

		if (isset($this->request->post['video_status'])) {
			$data['video_status'] = $this->request->post['video_status'];
		} elseif ($this->config->get('video_status')) { 
			$data['video_status'] = $this->config->get('video_status');
		}

		if (isset($this->request->post['video_btn'])) {
			$data['video_btn'] = $this->request->post['video_btn'];
		} elseif ($this->config->get('video_btn')) { 
			$data['video_btn'] = $this->config->get('video_btn');
		}

		$this->load->model('tool/image');
		if (isset($this->request->post['video_image'])) {
			$data['video_image'] = $this->request->post['video_image'];
			$data['video_thumb'] = $this->model_tool_image->resize($this->request->post['video_image'],100,100);
		} elseif ($this->config->get('video_image')) { 
			$data['video_image'] = $this->config->get('video_image');
			$data['video_thumb'] = $this->model_tool_image->resize($this->config->get('video_image'),100,100);
		} else {
			$data['video_image'] = 'placeholder.png';
			$data['video_thumb'] = $this->model_tool_image->resize('placeholder.png',100,100);
		}

		if (isset($this->request->post['video_autoplay'])) {
			$data['video_autoplay'] = $this->request->post['video_autoplay'];
		} elseif ($this->config->get('video_autoplay')) { 
			$data['video_autoplay'] = $this->config->get('video_autoplay');
		}

		if (isset($this->request->post['video_loop'])) {
			$data['video_loop'] = $this->request->post['video_loop'];
		} elseif ($this->config->get('video_loop')) { 
			$data['video_loop'] = $this->config->get('video_loop');
		}

		if (isset($this->request->post['video_branding'])) {
			$data['video_branding'] = $this->request->post['video_branding'];
		} elseif ($this->config->get('video_branding')) { 
			$data['video_branding'] = $this->config->get('video_branding');
		}

		if (isset($this->request->post['video_rel'])) {
			$data['video_rel'] = $this->request->post['video_rel'];
		} elseif ($this->config->get('video_rel')) { 
			$data['video_rel'] = $this->config->get('video_rel');
		}

		if (isset($this->request->post['video_fs'])) {
			$data['video_fs'] = $this->request->post['video_fs'];
		} elseif ($this->config->get('video_fs')) { 
			$data['video_fs'] = $this->config->get('video_fs');
		}
                
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/video', $data));
	}
        
    public function install(){
        $query = $this->db->query("DESC `".DB_PREFIX."product_image` video");
        if (!$query->num_rows) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_image` ADD  `video` VARCHAR( 250 ) NULL AFTER  `image` ;");
        }
    }
        
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/video')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>