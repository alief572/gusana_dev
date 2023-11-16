<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
 * @author Hikmat Aolia
 * @copyright Copyright (c) 2023, Hikmat Aolia
 *
 * This is controller for Master Employee
 */

class Employees extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Employees.View';
	protected $addPermission  	= 'Employees.Add';
	protected $managePermission = 'Employees.Manage';
	protected $deletePermission = 'Employees.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
		$this->load->model(array(
			'Employees/Employees_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url', 'json']);
		$this->template->title('Employees Manager');
		$this->template->page_icon('fas fa-user-tie');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function getData()
	{
		$requestData    = $_REQUEST;
		$status         = $requestData['status'];
		$search         = $requestData['search']['value'];
		$column         = $requestData['order'][0]['column'];
		$dir            = $requestData['order'][0]['dir'];
		$start          = $requestData['start'];
		$length         = $requestData['length'];

		$where = "";
		// $where = " AND `status` <> 'D'";

		$string = $this->db->escape_like_str($search);
		$sql = "SELECT *
        FROM employees WHERE 1=1  
        AND (`name` LIKE '%$string%'
        OR employee_code LIKE '%$string%')";

		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'num',
			1 => 'employee_code',
			2 => 'name',
			3 => 'division',
			4 => 'address',
			5 => 'job_description',
			6 => 'status'
		);

		// $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
		$sql .= " LIMIT " . $start . " ," . $length . " ";
		$query  = $this->db->query($sql);


		$data  = array();
		$urut1  = 1;
		$urut2  = 0;

		$status = [
			'0' => '<span class="bg-danger tx-white pd-5 tx-11 tx-bold rounded-5">Inactive</span>',
			'1' => '<span class="bg-info tx-white pd-5 tx-11 tx-bold rounded-5">Active</span>',
		];

		$empType = [
			'Tetap' => '<span class="bg-indigo tx-white pd-5 tx-11 tx-bold rounded-5">Tetap</span>',
			'Kontrak' => '<span class="bg-success tx-white pd-5 tx-11 tx-bold rounded-5">Kontrak</span>',
		];

		$gender = [
			'L' => 'Laki-laki',
			'P' => 'Perempuan',
		];

		foreach ($query->result_array() as $row) {
			$buttons = '';
			$total_data     = $totalData;
			$start_dari     = $start;
			$asc_desc       = $dir;
			if (
				$asc_desc == 'asc'
			) {
				$nomor = $urut1 + $start_dari;
			}
			if (
				$asc_desc == 'desc'
			) {
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$view 		= '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
			$edit 		= '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
			$delete 	= '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
			$buttons 	= $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

			$nestedData   = array();
			$nestedData[]  = $nomor;
			$nestedData[]  = $row['employee_code'];
			$nestedData[]  = $row['name'];
			$nestedData[]  = $row['division'];
			$nestedData[]  = $row['address'];
			$nestedData[]  = $row['job_description'];
			$nestedData[]  = $status['' . $row['status'] . ''];
			$nestedData[]  = $buttons;
			$data[] = $nestedData;
			$urut1++;
			$urut2++;
		}

		$json_data = array(
			"draw"              => intval($requestData['draw']),
			"recordsTotal"      => intval($totalData),
			"recordsFiltered"   => intval($totalFiltered),
			"data"              => $data
		);

		echo json_encode($json_data);
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$this->template->title('Employees');
		$this->template->render('index');
	}

	public function add()
	{
		$this->auth->restrict($this->addPermission);
		$divisions = $this->db->get_where('divisions')->result();
		$religions = $this->db->get_where('religions')->result();

		$data = [
			'divisions' => $divisions,
			'religions' => $religions
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function edit($id)
	{
		$this->auth->restrict($this->managePermission);
		$employee = $this->db->get_where('employees', array('id' => $id))->row();
		$divisions = $this->Employees_model->get_data('divisions');
		$religions = $this->Employees_model->get_data('religions');

		$this->template->set([
			'employee' => $employee,
			'divisions' => $divisions,
			'religions' => $religions
		]);
		$this->template->render('form');
	}

	public function view($id)
	{
		$this->auth->restrict($this->viewPermission);
		$employee = $this->db->get_where('employees', array('id' => $id))->row();
		$divisions = $this->db->get('divisions')->result_array();
		$religions = $this->db->get('religions')->result_array();

		$ArrDiv = array_column($divisions, 'name', 'id');
		$ArrRel = array_column($religions, 'name_religion', 'id');

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Employee Data " . $employee->id . " - " . $employee->name;
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

		$this->template->set([
			'employee' 	=> $employee,
			'ArrDiv' 	=> $ArrDiv,
			'ArrRel' 	=> $ArrRel
		]);
		$this->template->render('view');
	}

	public function delete()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');
		$employee = $this->db->get_where('employees',['id' => $id])->row_array();
		$data = [
			'status' 		=> '0',
			'deleted_at' 	=> date('Y-m-d H:i:s'),
			'deleted_by' 	=> $this->auth->user_id()
		];

		$this->db->trans_begin();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Employee Data " . $employee['id'] . " - " . $employee['name'];
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

		$this->db->where('id', $id)->delete("employees");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$return	= array(
				'msg'		=> 'Failed delete data Employee.  Please try again.',
				'status'	=> 0
			);
			$keterangan     = "FAILD delete data Employee " . $employee['id'] . ", Employee name : " . $employee['name'];
			$status         = 1;
			$nm_hak_akses   = $this->deletePermission;
			$kode_universal = $employee['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Success delete data Employee.',
				'status'	=> 1
			);
			$keterangan     = "SUCCESS delete data Employee " . $employee['id'] . ", Employee name : " . $employee['name'];
			$status         = 1;
			$nm_hak_akses   = $this->deletePermission;
			$kode_universal = $employee['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}



	public function save()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();
		$data = $post;
		$data['id'] = isset($post['id']) && $post['id'] ? $post['id'] : $this->Employees_model->generate_id();

		$this->db->trans_begin();
		if (isset($post['id']) && $post['id']) {
			$data['modified_at']	= date('Y-m-d H:i:s');
			$data['modified_by']	= $this->auth->user_id();
			$this->db->where('id', $post['id'])->update("employees", $data);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Update Employee Data " . $data['id'] . " - " . $data['name'];
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
			$data['created_at']		= $data['modified_at'] = date('Y-m-d H:i:s');
			$data['created_by']		= $data['modified_by'] = $this->auth->user_id();
			$this->db->insert("employees", $data);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Insert New Employee Data " . $data['id'] . " - " . $data['name'];
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
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$return	= array(
				'msg'		=> 'Failed save data Employee.  Please try again.',
				'status'	=> 0
			);
			$keterangan     = "FAILED save data Employee " . $data['id'] . ", Employee name : " . $data['name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Success Save data Employee.',
				'status'	=> 1
			);
			$keterangan     = "SUCCESS save data Employee " . $data['id'] . ", Employee name : " . $data['name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}
}
