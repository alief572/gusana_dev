<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
 * @author Ichsan
 * @copyright Copyright (c) 2019, Ichsan
 *
 * This is controller for Master Supplier
 */

class Product_category extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Product_Category.View';
	protected $addPermission  	= 'Product_Category.Add';
	protected $managePermission = 'Product_Category.Manage';
	protected $deletePermission = 'Product_Category.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib','user_agent','uri'));
		$this->load->model(array(
			'Product_category/Product_category_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url','json']);
		$this->template->title('Product Category');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Product_category_model->get_data_category1();
		$this->template->set('results', $data);
		$this->template->title('Product Category');
		$this->template->render('index');
	}
	public function editInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-edit');
		$product_category = $this->db->get_where('ms_product_category1', ['id_category1' => $id])->row();
		$product_type = $this->db->get('ms_product_type')->result();
		$data = array();
		$data = [
			'product_category' => $product_category,
			'product_type' => $product_type
		];
		$this->template->set('results', $data);
		$this->template->title('Inventory');
		$this->template->render('form_edit');
	}
	public function viewInventory()
	{
		$this->auth->restrict($this->viewPermission);
		$product_category = $this->Product_category_model->get_material_category_type_by_id($this->input->post('id'));
		if ($product_category->aktif == 1) {
			$status = '<div class="badge badge-primary">Aktif</div>';
		} else {
			$status = '<div class="badge badge-danger">Non Aktif</div>';
		}
		$data = [
			'product_category' => $product_category,
			'status' => $status
		];

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Product Category Data " . $product_category->id_category1 . " - " . $product_category->nama;
		$device_name = $this->agent->mobile(); // Returns the mobile device name
		if ($this->agent->is_browser()) {
			$device_name = $this->agent->browser(); // Returns the browser name
		} elseif ($this->agent->is_robot()) {
			$device_name = $this->agent->robot(); // Returns the robot/crawler name
		} elseif ($this->agent->is_mobile()) {
			$device_name = $this->agent->mobile(); // Returns the mobile device name
		} else {
			$device_name = 'Unidentified Device';
		}

		$id_user = $this->auth->user_id();
		$id_menu = $get_menu->id;
		$nm_menu = $get_menu->title;
		$device_type = $this->agent->platform();
		$os_type = $this->agent->browser();
		log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);

		$this->template->set('results', $data);
		$this->template->render('view_inventory');
	}


	public function addInventory()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-pencil');
		$product_type = $this->Product_category_model->get_data('ms_product_type');
		$data = [
			'product_type' => $product_type
		];
		$this->template->set('results', $data);
		$this->template->title('Add Product Category');
		$this->template->render('form');
	}

	public function delDetail()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');
		// print_r($id);
		// exit();
		$data = [
			'deleted' 		=> '1',
			'deleted_by' 	=> $this->auth->user_id()
		];

		$this->db->trans_begin();
		$this->db->where('id_compotition', $id)->update("ms_compotition", $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Save item failed !',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Save item success !',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}

	public function deleteInventory()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');
		// print_r($id);
		// exit();

		$product_category = $this->db->get_where('ms_product_category1',['id_category1' => $id])->row();

		$this->db->trans_begin();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Product Category Data " . $product_category->id_category1 . " - " . $product_category->nama;
		$device_name = $this->agent->mobile(); // Returns the mobile device name
		if ($this->agent->is_browser()) {
			$device_name = $this->agent->browser(); // Returns the browser name
		} elseif ($this->agent->is_robot()) {
			$device_name = $this->agent->robot(); // Returns the robot/crawler name
		} elseif ($this->agent->is_mobile()) {
			$device_name = $this->agent->mobile(); // Returns the mobile device name
		} else {
			$device_name = 'Unidentified Device';
		}

		$id_user = $this->auth->user_id();
		$id_menu = $get_menu->id;
		$nm_menu = $get_menu->title;
		$device_type = $this->agent->platform();
		$os_type = $this->agent->browser();
		log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);

		$this->db->delete("ms_product_category1", ['id_category1' => $id]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Save item failed',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Save item success !',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
	public function saveNewinventory()
	{
		$this->auth->restrict($this->addPermission);
		$session = $this->session->userdata('app_session');
		// $post = $_POST['hd1']['1']['produk'];
		$code = $this->Product_category_model->generate_id();
		$post = $this->input->post();
		$this->db->trans_begin();

		if (isset($post['id_category1'])) {
			$this->db->update('ms_product_category1', [
				'id_type' => $this->input->post('product_type'),
				'nama' => $this->input->post('nm_product_category'),
				'aktif' => $post['aktif'],
				'diubah_oleh' => $this->auth->user_id(),
				'diubah_tgl' => date("Y-m-d H:i:s")
			], ['id_category1' => $post['id_category1']]);

			// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Product Category Data " . $code . " - " . $this->input->post('nm_product_category');
		$device_name = $this->agent->mobile(); // Returns the mobile device name
		if ($this->agent->is_browser()) {
			$device_name = $this->agent->browser(); // Returns the browser name
		} elseif ($this->agent->is_robot()) {
			$device_name = $this->agent->robot(); // Returns the robot/crawler name
		} elseif ($this->agent->is_mobile()) {
			$device_name = $this->agent->mobile(); // Returns the mobile device name
		} else {
			$device_name = 'Unidentified Device';
		}

		$id_user = $this->auth->user_id();
		$id_menu = $get_menu->id;
		$nm_menu = $get_menu->title;
		$device_type = $this->agent->platform();
		$os_type = $this->agent->browser();
		log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);
		} else {
			$this->db->insert('ms_product_category1', [
				'id_category1' => $code,
				'id_type' => $this->input->post('product_type'),
				'nama' => $this->input->post('nm_product_category'),
				'aktif' => 1,
				'dibuat_oleh' => $this->auth->user_id(),
				'dibuat_tgl' => date("Y-m-d H:i:s")
			]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Save item failed !',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Save item success !',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
	public function saveEditinventory()
	{
		$this->auth->restrict($this->addPermission);
		$session = $this->session->userdata('app_session');
		$this->db->trans_begin();

		$data = array();
		$data = [
			'id_type' => $this->input->post('product_type'),
			'nama' => $this->input->post('nm_product_category'),
			'aktif' => $this->input->post('aktif'),
			'diubah_oleh' => $this->auth->user_id(),
			'diubah_tgl' => date("Y-m-d H:i:s")
		];

		$this->db->update('ms_product_category1', $data, ['id_category1' => $this->input->post('id_category1')]);


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Item save failed !',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Item save success !',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
	public function saveNewinventoryold()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();
		$code = $this->Product_category_model->generate_id();
		$this->db->trans_begin();
		$data = [
			'id_category1'	 	=> $code,
			'id_type'		    => $post['product_type'],
			'nama'		        => $post['nm_inventory'],
			'aktif'				=> 'aktif',
			'created_on'		=> date('Y-m-d H:i:s'),
			'created_by'		=> $this->auth->user_id(),
			'deleted'			=> '0'
		];

		$insert = $this->db->insert("ms_product_category1", $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Add Inventory Lv 2 Failed !',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Add Inventory Lv 2 Success !',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
}
