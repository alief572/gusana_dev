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

class Material_jenis extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Material_Jenis.View';
	protected $addPermission  	= 'Material_Jenis.Add';
	protected $managePermission = 'Material_Jenis.Manage';
	protected $deletePermission = 'Material_Jenis.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
		$this->load->model(array(
			'Material_jenis/Material_jenis_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url', 'json']);
		$this->template->title('Material Jenis');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Material_jenis_model->get_data_category2();
		$this->template->set('results', $data);
		$this->template->title('Material Jenis');
		$this->template->render('index');
	}
	public function editInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-edit');
		$inven = $this->db->get_where('ms_inventory_category2', array('id_category2' => $id))->result();
		$lvl1 = $this->Material_jenis_model->get_data('ms_inventory_type');
		$lvl2 = $this->Material_jenis_model->get_data('ms_inventory_category1');
		$data = [
			'inven' => $inven,
			'lvl1' => $lvl1,
			'lvl2' => $lvl2
		];
		$this->template->set('results', $data);
		$this->template->title('Inventory');
		$this->template->render('edit_inventory');
	}
	public function viewInventory()
	{
		$this->auth->restrict($this->viewPermission);
		$id 	= $this->input->post('id');
		$cust 	= $this->Material_jenis_model->getById($id);
		// echo "<pre>";
		// print_r($cust);
		// echo "<pre>";

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Material Jenis Data " . $cust['id_category2'] . " - " . $cust['nama'];
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

		$this->template->set('result', $cust);
		$this->template->render('view_inventory');
	}
	public function saveEditInventory()
	{
		$this->auth->restrict($this->editPermission);
		$post = $this->input->post();

		$this->db->trans_begin();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


		$desc = "Update Material Jenis Data " . $this->input->post('id_inventory') . " - " . $this->input->post('nm_inventory');
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

		// print_r(log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc));
		// exit;

		$data = [
			'id_type'		    => $post['inventory_1'],
			'id_category1'		=> $post['inventory_2'],
			'nama'      		=> $post['nm_inventory'],
			'aktif'				=> $post['status'],
			'modified_on'		=> date('Y-m-d H:i:s'),
			'modified_by'		=> $this->auth->user_id()
		];

		$this->db->update("ms_inventory_category2", $data, ['id_category2' => $post['id_inventory']]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Gagal Save Item. Thanks ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success Save Item. Thanks ...',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
	public function addInventory()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-pencil');
		$inventory_1 = $this->Material_jenis_model->get_data('ms_inventory_type');
		$inventory_2 = $this->Material_jenis_model->get_data('ms_inventory_category1');
		$data = [
			'inventory_1' => $inventory_1,
			'inventory_2' => $inventory_2
		];
		$this->template->set('results', $data);
		$this->template->title('Add Inventory');
		$this->template->render('add_inventory');
	}
	public function deleteInventory()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');

		$this->db->trans_begin();

		$get_data = $this->db->get_where('ms_inventory_category2', ['id_category2' => $id])->row();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Material Jenis Data " . $id . " - " . $get_data->nama;
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

		$this->db->delete("ms_inventory_category2", ['id_category2' => $id]);


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Gagal Save Item. Thanks ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success Save Item. Thanks ...',
				'status'	=> 1
			);
		}
		echo json_encode($status);
	}

	function get_inven2()
	{
		$inventory_1 = $_GET['inventory_1'];
		$data = $this->Material_jenis_model->level_2($inventory_1);

		// print_r($data);
		// exit();
		echo "<select id='inventory_2' name='inventory_2' class='form-control input-sm select2'>";
		echo "<option value=''>-- Material Category --</option>";
		foreach ($data as $key => $st) :
			echo "<option value='$st->id_category1' set_select('inventory_2', $st->id_category1, isset($data->id_category1) && $data->id_category1 == $st->id_category1)>$st->nama
                    </option>";
		endforeach;
		echo "</select>";
	}

	public function saveNewinventory()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();
		$code = $this->Material_jenis_model->generate_id();
		$this->db->trans_begin();
		// echo '<pre>';
		// var_dump($post);
		// echo '</pre>';
		// exit;
		$data = [
			'id_category2'		=> $code,
			'id_type'		    => $post['inventory_1'],
			'id_category1'		=> $post['inventory_2'],
			'nama'      		=> $post['nm_inventory'],
			'aktif'				=> 'aktif',
			'created_on'		=> date('Y-m-d H:i:s'),
			'created_by'		=> $this->auth->user_id(),
			'deleted'			=> '0'
		];

		$insert = $this->db->insert("ms_inventory_category2", $data);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Insert New Material Jenis Data " . $code . " - " . $post['nm_inventory'];
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
				'pesan'		=> 'Gagal Save Item. Thanks ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success Save Item. Thanks ...',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
}
