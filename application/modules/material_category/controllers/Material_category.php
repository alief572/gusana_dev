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

class Material_category extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Material_Category.View';
	protected $addPermission  	= 'Material_Category.Add';
	protected $managePermission = 'Material_Category.Manage';
	protected $deletePermission = 'Material_Category.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
		$this->load->model(array(
			'Material_category/Material_category_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url', 'json']);
		$this->template->title('Material Category');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Material_category_model->get_data_category1();
		$this->template->set('results', $data);
		$this->template->title('Material Category');
		$this->template->render('index');
	}
	public function editInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-edit');
		$material_category = $this->db->get_where('ms_inventory_category1', ['id_category1' => $id])->row();
		$inventory_1 = $this->db->get('ms_inventory_type')->result();
		$data = array();
		$data = [
			'material_category' => $material_category,
			'inventory_1' => $inventory_1
		];
		$this->template->set('results', $data);
		$this->template->title('Inventory');
		$this->template->render('edit_inventory');
	}
	public function viewInventory()
	{
		$this->auth->restrict($this->viewPermission);
		$material_category = $this->Material_category_model->get_material_category_type_by_id($this->input->post('id'));
		if ($material_category->aktif == "aktif") {
			$status = '<div class="badge badge-primary">Aktif</div>';
		} else {
			$status = '<div class="badge badge-danger">Non Aktif</div>';
		}
		$data = [
			'material_category' => $material_category,
			'status' => $status
		];

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Material Category Data " . $material_category->id_category1 . " - " . $material_category->nama;
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
		$inventory_1 = $this->Material_category_model->get_data('ms_inventory_type');
		$data = [
			'inventory_1' => $inventory_1
		];
		$this->template->set('results', $data);
		$this->template->title('Add Inventory');
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

		$material_category = $this->db->get_where('ms_inventory_category1', ['id_category1' => $id])->row();

		$data = [
			'deleted' 		=> '1',
			'deleted_by' 	=> $this->auth->user_id()
		];

		$this->db->trans_begin();
		$this->db->where('id_category1', $id)->update("ms_inventory_category1", $data);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Material Category Data " . $id . " - " . $material_category->nama;
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
		$code = $this->Material_category_model->generate_id();
		$this->db->trans_begin();

		$this->db->insert('ms_inventory_category1', [
			'id_category1' => $code,
			'id_type' => $this->input->post('inventory_1'),
			'nama' => $this->input->post('nm_inventory'),
			'aktif' => 'aktif',
			'deleted' => 0,
			'created_by' => $this->auth->user_id(),
			'created_on' => date("Y-m-d H:i:s")
		]);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Insert New Material Category Data " . $code . " - " . $this->input->post('nm_inventory');
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
			'id_type' => $this->input->post('inventory_1'),
			'nama' => $this->input->post('nm_inventory'),
			'aktif' => $this->input->post('aktif'),
			'modified_by' => $this->auth->user_id(),
			'modified_on' => date("Y-m-d H:i:s")
		];

		$this->db->update('ms_inventory_category1', $data, ['id_category1' => $this->input->post('id_category1')]);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Update Material Category Data " . $this->input->post('id_category1') . " - " . $this->input->post('nm_inventory');
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
		$code = $this->Material_category_model->generate_id();
		$this->db->trans_begin();
		$data = [
			'id_category1'	 	=> $code,
			'id_type'		    => $post['inventory_1'],
			'nama'		        => $post['nm_inventory'],
			'aktif'				=> 'aktif',
			'created_on'		=> date('Y-m-d H:i:s'),
			'created_by'		=> $this->auth->user_id(),
			'deleted'			=> '0'
		];

		$insert = $this->db->insert("ms_inventory_category1", $data);

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
