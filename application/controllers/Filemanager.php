<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filemanager extends CI_Controller {

	var $_public = './public';

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation', 'pagination'));
		$this->load->helper(array('url', 'directory', 'file'));
	}

	public function index()
	{
		if ($this->input->get('directory')) {
			$directory = $this->_public.'/uploads/' . $this->input->get('directory') . '/';
		} else {
			$directory = $this->_public.'/uploads/';
		}

		if ($this->input->get('offset')) {
			$offset = $this->input->get('offset');
		} else {
			$offset = 0;
		}

		$images = $this->sort($directory, directory_map($directory, 1));

		if ($this->input->get('filter_name')) {
			$filter_name = basename(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8'));
			$images = array_filter($images, function ($var) use ($filter_name) {
				return (strpos(strtolower($var), strtolower($filter_name)) !== false);
			});
		} else {
			$filter_name = '';
		}
		
		$image_total = count($images);
		$images = array_splice($images, $offset, 8);
		$data['images'] = array();

		foreach ($images as $img) {
			$name = str_split(basename($img), 14);
			if (is_dir($directory . $img)) {
				$url = '';
				if ($this->input->get('target')) {
					$url .= '&target=' . $this->input->get('target');
				}
				if ($this->input->get('thumb')) {
					$url .= '&thumb=' . $this->input->get('thumb');
				}

				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ',$name),
					'type'  => 'directory',
					'path'  => ltrim($directory, './') . $img,
					'href'  => base_url('filemanager?directory=' . urlencode(trim($this->input->get('directory') . '/' . rtrim($img, '\\'), '/')) . $url)
				);
			} elseif (is_file($directory . $img)) {
				$data['images'][] = array(
					'thumb' => base_url(ltrim($directory, './') . $img),
					'name'  => implode(' ',$name),
					'type'  => 'image',
					'path'  => ltrim($directory, './') . $img,
					'href'  => base_url(ltrim($directory, './') . $img)
				);
			}
		}

		if ($this->input->get('directory')) {
			$data['directory'] = urlencode($this->input->get('directory'));
		} else {
			$data['directory'] = '';
		}

		if ($this->input->get('filter_name')) {
			$data['filter_name'] = basename(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8'));
		} else {
			$data['filter_name'] = '';
		}

		if ($this->input->get('target')) {
			$data['target'] = $this->input->get('target');
		} else {
			$data['target'] = '';
		}

		if ($this->input->get('thumb')) {
			$data['thumb'] = $this->input->get('thumb');
		} else {
			$data['thumb'] = '';
		}

		$url = '';

		if ($this->input->get('directory')) {
			$pos = strrpos($this->input->get('directory'), '/');
			if ($pos) {
				$url .= '?directory=' . urlencode(substr($this->input->get('directory'), 0, $pos));
			}
		}

		if ($this->input->get('target')) {
			$url .= !empty($url) ? '&target=' . $this->input->get('target') : '?target=' . $this->input->get('target');
		}

		if ($this->input->get('thumb')) {
			$url .= '&thumb=' . $this->input->get('thumb');
		}

		$data['parent'] = base_url('filemanager' . $url);

		$url = '';
		
		if ($this->input->get('directory')) {
			$url .= '?directory=' . urlencode($this->input->get('directory'));
		}

		if ($this->input->get('filter_name')) {
			$url = !empty($url) ? '&filter_name='. urlencode(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8')) : '?filter_name='. urlencode(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8'));
		}

		if ($this->input->get('target')) {
			$url .= !empty($url) ? '&target=' . $this->input->get('target') : '?target=' . $this->input->get('target');
		}

		if ($this->input->get('thumb')) {
			$url .= '&thumb=' . $this->input->get('thumb');
		}

		$data['refresh'] = base_url('filemanager' . $url);

		$url = '';
		
		if ($this->input->get('directory')) {
			$url .= '?directory=' . urlencode($this->input->get('directory'));
		}

		if ($this->input->get('filter_name')) {
			$url = !empty($url) ? '&filter_name='. urlencode(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8')) : '?filter_name='. urlencode(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8'));
		}

		if ($this->input->get('target')) {
			$url .= !empty($url) ? '&target=' . $this->input->get('target') : '?target=' . $this->input->get('target');
		}

		if ($this->input->get('thumb')) {
			$url .= '&thumb=' . $this->input->get('thumb');
		}

		$config['total_rows'] 			= $image_total;
		$config['per_page']   			= 8;
		$config['page_query_string']    = TRUE;
        $config['query_string_segment']	= 'offset';
		$config['base_url']   			= base_url('filemanager' . $url);

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('filemanager/filemanager', $data);
	}

	public function folder()
	{
		$this->form_validation->set_rules(array(
			array(
				'field' => 'folder', 
				'label' => 'Folder', 
				'rules' => 'required|min_length[3]|max_length[255]'
			),
		));

		if ($this->form_validation->run() === TRUE) 
		{
			if ($this->input->get('directory')) {
				$directory = $this->_public.'/uploads/' . $this->input->get('directory') . '/';
			} else {
				$directory = $this->_public.'/uploads/';
			}
	
			if (!is_dir($directory)) {
				$json['error'] = 'Error in derectory';
			}
	
			if ($this->input->post('folder')) {
				$folder = basename(html_entity_decode($this->input->post('folder'), ENT_QUOTES, 'UTF-8'));				
				if (is_dir($directory . $folder)) {
					$json['error'] = 'Warning: A file or directory with the same name already exists!';
				}
				if (!isset($json['error'])) {
					mkdir($directory . $folder, 0777);
					chmod($directory . $folder, 0777);
					$json['success'] = 'Success: Directory created!';
				}
			}
		}
		else
		{
			$json['error'] = 'Warning: Folder name must be between 3 and 255!';
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($json));
	}

	public function upload()
	{
		if ($this->input->get('directory')) {
			$directory = $this->_public.'/uploads/' . $this->input->get('directory') . '/';
		} else {
			$directory = $this->_public.'/uploads/';
		}

		if (!is_dir($directory)) {
			$json['error'] = 'Error in derectory';
		} else {
			$config['upload_path']		= $directory;
			$config['allowed_types']	= 'jpg|png';	
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$json['error'] = $this->upload->display_errors();
			} else {
				$json['success'] = 'File succesfully uploaded';
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($json));
	}

	public function sort($dir = FALSE, $map = array()) 
	{
		$sortedData = array();
		foreach($map as $m) {           
			if (is_file($dir . $m)) {
				array_push($sortedData, $m);
			} else {
				array_unshift($sortedData, $m);
			}
		}
		return $sortedData;
	}

	public function delete() 
	{
		if ($this->input->post('path')) {
			$paths = $this->input->post('path');
		} else {
			$paths = array();
		}

		if (!empty($paths)) {
			foreach ($paths as $path) {
				if (is_file($path)) {
					unlink($path);
				} elseif (is_dir($path)) {
					delete_files($path, TRUE);
					rmdir($path);
				}
			}
		}

		$json['success'] = 'Success: Your file or directory has been deleted!';

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($json));
	}
}
