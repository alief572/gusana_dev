<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
 * @author HIkmat Aolia
 * @copyright Copyright (c) 2019, HIkmat Aolia
 *
 * This is controller for Master Supplier
 */

class Suppliers extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Suppliers.View';
	protected $addPermission  	= 'Suppliers.Add';
	protected $managePermission = 'Suppliers.Manage';
	protected $deletePermission = 'Suppliers.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
		$this->load->model(array(
			'Suppliers/Suppliers_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url', 'json']);
		$this->template->title('Manage Suppliers');
		$this->template->page_icon('fas fa-truck');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function getData()
	{
		$requestData = $_REQUEST;
		$status = $requestData['status'];
		$search = $requestData['search']['value'];
		$column = $requestData['order'][0]['column'];
		$dir = $requestData['order'][0]['dir'];
		$start = $requestData['start'];
		$length = $requestData['length'];

		$where = '';
		$where = " AND `deleted` <> '1'";

		$string = $this->db->escape_like_str($search);

		$sql = "SELECT a.*,b.nm_category_supplier
        FROM master_supplier a
		LEFT JOIN category_suppliers b ON b.id_category = a.supplier_type 
		WHERE 1=1
        AND (a.supplier_name LIKE '%$string%'
        OR a.telephone LIKE '%$string%'
        OR a.address LIKE '%$string%'
        OR b.nm_category_supplier LIKE '%$string%'
        OR a.status LIKE '%$string%')";

		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();

		$columns_order_by = [
			0 => 'num',
			1 => 'supplier_name',
			2 => 'telephone',
			3 => 'country_code',
			4 => 'email',
			5 => 'address',
			6 => 'nm_category_supplier',
			7 => 'status',
		];

		$sql .= ' ORDER BY ' . $columns_order_by[$column] . ' ' . $dir . ' ';
		$sql .= ' LIMIT ' . $start . ' ,' . $length . ' ';
		$query = $this->db->query($sql);

		$data = [];
		$urut1 = 1;
		$urut2 = 0;

		$status = [
			'0' => '<span class="bg-danger tx-white pd-5 tx-11 tx-bold rounded-5">Inactive</span>',
			'1' => '<span class="bg-info tx-white pd-5 tx-11 tx-bold rounded-5">Active</span>',
		];

		/* Button */
		foreach ($query->result_array() as $row) {
			$buttons = '';
			$total_data = $totalData;
			$start_dari = $start;
			$asc_desc = $dir;
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

			$view = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
			$edit = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
			$delete = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
			$buttons = $view . '&nbsp;' . $edit . '&nbsp;' . $delete;

			$nestedData = [];
			$nestedData[] = $nomor;
			$nestedData[] = $row['supplier_name'];
			$nestedData[] = $row['telephone'];
			$nestedData[] = $row['address'];
			$nestedData[] = $row['nm_category_supplier'];
			$nestedData[] = $status[$row['status']];
			$nestedData[] = $buttons;
			$data[] = $nestedData;
			++$urut1;
			++$urut2;
		}

		$json_data = [
			'draw' => intval($requestData['draw']),
			'recordsTotal' => intval($totalData),
			'recordsFiltered' => intval($totalFiltered),
			'data' => $data,
		];

		echo json_encode($json_data);
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$this->template->render('index');
	}

	public function add()
	{
		$this->auth->restrict($this->addPermission);
		$countries = $this->db->get('countries')->result();
		$category_suppliers = $this->db->get('category_suppliers')->result();

		$data = [
			'countries' => $countries,
			'category_suppliers' => $category_suppliers,
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function edit($id)
	{
		$this->auth->restrict($this->managePermission);
		$supplier 				= $this->db->query("SELECT a.*,b.nm_category_supplier FROM master_supplier a LEFT JOIN category_suppliers b ON b.id_category = a.supplier_type WHERE a.id = '" . $id . "'")->row();
		$pic 					= $this->db->get_where('supplier_pic', ['supplier_id' => $id, 'status' => '1'])->result();
		$countries 				= $this->db->get_where('countries')->result();
		$states 				= $this->db->get_where('states', ['country_id' => $supplier->country_id])->result();
		$cities 				= $this->db->get_where('cities', ['state_id' => $supplier->state_id])->result();
		$supplier_types 		= $this->db->get('supplier_types')->result();
		$category_suppliers     = $this->db->get('category_suppliers')->result();

		$data = [
			'supplier'					=> $supplier,
			'countries' 				=> $countries,
			'PIC' 						=> $pic,
			'states' 					=> $states,
			'cities' 					=> $cities,
			'category_suppliers' 		=> $category_suppliers,
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function view($id)
	{
		$this->auth->restrict($this->managePermission);
		$supplier 			= $this->db->query("SELECT a.*,b.nm_category_supplier FROM master_supplier a LEFT JOIN category_suppliers b ON b.id_category = a.supplier_type WHERE a.id = '" . $id . "'")->row();
		$pic 				= $this->db->get_where('supplier_pic', ['supplier_id' => $id, 'status' => '1'])->result();
		$countries 			= $this->db->get_where('countries')->result_array();
		$states 			= $this->db->get_where('states', ['country_id' => $supplier->country_id])->result_array();
		$cities 			= $this->db->get_where('cities', ['state_id' => $supplier->state_id])->result_array();
		$supplier_types 	= $this->db->get('supplier_types')->result_array();

		$ArrCountries 		= array_column($countries, 'name', 'id');
		$ArrStates 			= array_column($states, 'name', 'id');
		$ArrCities 			= array_column($cities, 'name', 'id');
		$ArrSTypes 			= array_column($supplier_types, 'name', 'id');

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Suppliers Data " . $supplier->id . " - " . $supplier->supplier_name;
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
		// End Logging

		$data = [
			'supplier'		=> $supplier,
			'ArrCountries' 	=> $ArrCountries,
			'PIC' 			=> $pic,
			'ArrStates' 	=> $ArrStates,
			'ArrCities' 	=> $ArrCities,
			'ArrSTypes' 	=> $ArrSTypes,
		];
		$this->template->set($data);
		$this->template->render('view');
	}

	public function save()
	{
		$this->auth->restrict($this->managePermission);
		$post = $this->input->post();

		$data = $post;
		$data['id'] = $post['id'] ?: $this->Suppliers_model->generate_id();

		$dataPIC = isset($post['PIC']) ? $post['PIC'] : [];
		unset($data['PIC']);

		$this->db->trans_begin();
		if (isset($post['id']) && $post['id'] == '') {
			$data['created_at'] 			= date('Y-m-d H:i:s');
			$data['created_by'] 			= $this->auth->user_id();
			$this->db->insert('master_supplier', $data);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Insert New Suppliers Data " . $data['id'] . " - " . $data['supplier_name'];
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
			// End Logging
		} else {
			$data['modified_at'] 			= date('Y-m-d H:i:s');
			$data['modified_by'] 			= $this->auth->user_id();
			$this->db->update('master_supplier', $data, ['id' => $data['id']]);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Update Suppliers Data " . $data['id'] . " - " . $data['supplier_name'];
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
			// End Logging
		}

		$n = 0;
		if ($dataPIC) {
			foreach ($dataPIC as $pic) {
				$n++;
				$dataPic =  array(
					'supplier_id'	=> $data['id'],
					'name'			=> $pic['name'],
					'phone_number'	=> $pic['phone_number'],
					'email'			=> $pic['email'],
					'position'		=> $pic['position']
				);

				if (isset($pic['id']) && $pic['id']) {
					$check = $this->db->get_where('supplier_pic', ['id' => $pic['id']])->num_rows();
					if (
						$check > 0
					) {
						$this->db->update('supplier_pic', $pic, ['id' => $pic['id']]);
					}
				} else {
					$this->db->insert('supplier_pic', $dataPic);
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			print_r($this->db->error());
			exit;
			$this->db->trans_rollback();
			$errMsg = $this->db->error()['message'];
			$return	= array(
				'msg'		=> 'Failed save data Supplier.  Please try again. ' . $errMsg,
				'status'	=> 0
			);
			$keterangan     = "FAILED save data Supplier " . $data['id'] . ", Supplier name : " . $data['supplier_name'];
			$status         = 1;
			$nm_hak_akses   = $this->managePermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Success Save data Supplier.',
				'status'	=> 1
			);
			$keterangan     = "SUCCESS save data Supplier " . $data['id'] . ", Supplier name : " . $data['supplier_name'];
			$status         = 1;
			$nm_hak_akses   = $this->managePermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}

	function delete()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('master_supplier', ['id' => $id])->row_array();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Suppliers Data " . $data['id'] . " - " . $data['supplier_name'];
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
		// End Logging

		$this->db->trans_begin();
		$sql = $this->db->delete('master_supplier', ['id' => $id]);
		$errMsg = $this->db->error()['message'];
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$keterangan     = "FAILD " . $errMsg;
			$status         = 0;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
			$return	= array(
				'msg'		=> "Failed delete data Supplier. Please try again. " . $errMsg,
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Delete data Supplier.',
				'status'	=> 1
			);
			$keterangan     = "Delete data Supplier " . $data['id'] . ", Supplier name : " . $data['supplier_name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}

	function getProvince()
	{
		$country_id = $_GET['country_id'];
		$search 	= isset($_GET['q']) ? $_GET['q'] : '';
		$states 	= [];
		if (isset($country_id) && $country_id) {
			$states = $this->db->like(['name' => $search])->where(['country_id' => $country_id])->get('states')->result_array();
		}
		echo json_encode($states);
	}

	function getCities()
	{
		$state_id 	= $_GET['state_id'];
		$search 	= ($_GET['q']) ? $_GET['q'] : '';
		$cities 	= [];
		if (isset($state_id) && $state_id) {
			$cities = $this->db->like(['name' => $search])->where(['state_id' => $state_id])->get('cities')->result_array();
		}

		echo json_encode($cities);
	}

	/* PIC */

	function deletePic()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('supplier_pic', ['id' => $id])->row_array();

		$this->db->trans_begin();
		$sql = $this->db->update('supplier_pic', ['status' => '2', 'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->auth->user_id()], ['id' => $id]);
		$errMsg = $this->db->error()['message'];
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$keterangan     = "FAILD " . $errMsg;
			$status         = 0;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
			$return	= array(
				'msg'		=> "Failed delete data PIC Supplier. Please try again. " . $errMsg,
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Delete data PIC Supplier.',
				'status'	=> 1
			);
			$keterangan     = "Delete data PCI Supplier " . $data['id'] . ", PIC name : " . $data['name'];
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
