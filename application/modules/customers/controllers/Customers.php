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

class Customers extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Customers.View';
	protected $addPermission  	= 'Customers.Add';
	protected $managePermission = 'Customers.Manage';
	protected $deletePermission = 'Customers.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
		$this->load->model(array(
			'Customers/Customer_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url', 'json']);
		$this->template->title('Manage Customers');
		$this->template->page_icon('fa fa-building');

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
		$where = " AND `status` != '0'";

		$string = $this->db->escape_like_str($search);
		$sql = "SELECT *,(@row_number:=@row_number + 1) AS num
        FROM customers, (SELECT @row_number:=0) as temp WHERE 1=1 $where  
        AND (customer_name LIKE '%$string%'
        OR telephone LIKE '%$string%'
        OR `address` LIKE '%$string%'
        OR `status` LIKE '%$string%'
            )";

		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'num',
			1 => 'customer_name',
			2 => 'telephone',
			// 3 => 'email',
			3 => 'address',
			4 => 'status',
			// 6 => '',
		);

		$sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
		$sql .= " LIMIT " . $start . " ," . $length . " ";
		$query  = $this->db->query($sql);


		$data  = array();
		$urut1  = 1;
		$urut2  = 0;

		$status = [
			'0' => '<span class="bg-danger tx-white pd-5 tx-11 tx-bold rounded-5">Inactive</span>',
			'1' => '<span class="bg-info tx-white pd-5 tx-11 tx-bold rounded-5">Active</span>',
		];

		/* Button */
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

			$view 		= '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id_customer'] . '"><i class="fa fa-eye"></i></button>';
			$edit 		= '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_customer'] . '"><i class="fa fa-edit"></i></button>';
			$delete 	= '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_customer'] . '"><i class="fa fa-trash"></i></button>';
			$buttons 	= $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

			$nestedData   = array();
			$nestedData[]  = $nomor;
			$nestedData[]  = $row['customer_name'];
			$nestedData[]  = $row['telephone'];
			// $nestedData[]  = $row['email'];
			$nestedData[]  = $row['address'];
			$nestedData[]  = $status[$row['status']];
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
		$this->template->render('index');
	}

	public function add()
	{
		$this->auth->restrict($this->viewPermission);
		$countries = $this->Customer_model->get_data('countries');
		$marketing = $this->db->get_where('employees', array('division' => 'DIV002', 'status' => '1'))->result();
		$kategori_customer = $this->Customer_model->get_data('kategori_customer');
		$data = [
			'countries' => $countries,
			'marketing' => $marketing,
			'kategori_customer' => $kategori_customer
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function edit($id)
	{
		$this->auth->restrict($this->managePermission);
		$customer 				= $this->db->get_where('customers', array('id_customer' => $id))->row();
		$pic 					= $this->db->get_where('customer_pic', ['id_customer' => $id, 'status' => '1'])->result();
		$countries 				= $this->Customer_model->get_data('countries');
		$states 				= $this->Customer_model->get_data('states', 'country_id', $customer->country_id);
		$cities 				= $this->Customer_model->get_data('cities', 'state_id', $customer->state_id);
		$marketing 				= $this->db->get_where('employees', array('division' => 'DIV002', 'status' => '1'))->result();
		$kategori_customer = $this->db->get('kategori_customer')->result();
		$receive_invoice_day 	= json_decode($customer->receive_invoice_day);
		$invoicing_requirement 	= json_decode($customer->invoicing_requirement);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Edit Customer Data " . $customer->id_customer . " - " . $customer->customer_name;
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
			'customer'					=> $customer,
			'countries' 				=> $countries,
			'PIC' 						=> $pic,
			'states' 					=> $states,
			'cities' 					=> $cities,
			'marketing' 				=> $marketing,
			'receive_invoice_day' 		=> $receive_invoice_day,
			'invoicing_requirement' 	=> $invoicing_requirement,
			'kategori_customer'			=> $kategori_customer
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function view($id)
	{
		$this->auth->restrict($this->viewPermission);
		$customer 				= $this->db->get_where('customers', array('id_customer' => $id))->row();
		$pic 					= $this->db->get_where('customer_pic', ['id_customer' => $id, 'status' => '1'])->result();
		$countries 				= $this->db->get_where('countries')->result_array();
		$states 				= $this->db->get_where('states', ['country_id' => $customer->country_id])->result_array();
		$cities 				= $this->db->get_where('cities', ['state_id' => $customer->state_id])->result_array();
		$marketing 				= $this->db->get_where('employees', array('status' => '1'))->result_array();
		$receive_invoice_day 	= json_decode($customer->receive_invoice_day);
		$invoicing_requirement 	= json_decode($customer->invoicing_requirement);
		$ArrCountries 			= array_column($countries, 'name', 'id');
		$ArrStates 				= array_column($states, 'name', 'id');
		$ArrCities	 			= array_column($cities, 'name', 'id');
		$ArrMkt	 				= array_column($marketing, 'name', 'id');

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Customer Data " . $customer->id_customer . " - " . $customer->customer_name;
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
			'customer'					=> $customer,
			'ArrCountries' 				=> $ArrCountries,
			'PIC' 						=> $pic,
			'ArrStates' 				=> $ArrStates,
			'ArrCities' 				=> $ArrCities,
			'ArrMkt' 					=> $ArrMkt,
			'receive_invoice_day' 		=> $receive_invoice_day,
			'invoicing_requirement' 	=> $invoicing_requirement
		];
		$this->template->set($data);
		$this->template->render('view');
	}


	public function save()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();

		$data = $post;
		$data['id_customer'] 				= $post['id_customer'] ?: $this->Customer_model->generate_id();
		$data['receive_invoice_day'] 		= $post['receive_invoice_day'] ? json_encode($post['receive_invoice_day']) : NULL;
		$data['invoicing_requirement'] 		= $post['invoicing_requirement'] ? json_encode($post['invoicing_requirement']) : NULL;
		$data['down_payment_value'] 		= str_replace(",", "", $post['down_payment_value']);
		$data['remain_payment'] 			= str_replace(",", "", $post['remain_payment']);
		$data['start_receive_time_invoice'] = ($post['end_receive_time_invoice']) ?: null;
		$data['end_receive_time_invoice'] 	= ($post['end_receive_time_invoice']) ?: null;
		$data['telephone_alt'] 				= ($post['telephone_alt']) ?: null;
		$data['fax'] 						= ($post['fax']) ?: null;
		$data['zip_code'] 					= ($post['zip_code']) ?: null;
		$data['longitude'] 					= ($post['longitude']) ?: null;
		$data['latitude'] 					= ($post['latitude']) ?: null;
		$data['address_invoice'] 			= ($post['address_invoice']) ?: null;
		$data['npwp_number'] 				= ($post['npwp_number']) ?: null;
		$data['npwp_name'] 					= ($post['npwp_name']) ?: null;
		$data['npwp_address'] 				= ($post['npwp_address']) ?: null;
		$data['bank_name'] 					= ($post['bank_name']) ?: null;
		$data['bank_account_number'] 		= ($post['bank_account_number']) ?: null;
		$data['bank_account_name'] 			= ($post['bank_account_name']) ?: null;
		$data['bank_account_address'] 		= ($post['bank_account_address']) ?: null;
		$data['swift_code'] 				= ($post['swift_code']) ?: null;
		$data['cooperation_date'] 				= ($post['cooperation_date']) ?: null;
		$data['category_customer'] 				= ($post['category_customer']) ?: null;
		$data['status'] = ($post['status']) ?: null;

		$dataPIC = isset($post['PIC']) ? $post['PIC'] : [];
		unset($data['PIC']);
		unset($data['nominal_dp']);
		unset($data['sisa_pembayaran']);

		$this->db->trans_begin();
		if (isset($post['id_customer']) && $post['id_customer'] == '') {
			$data['created_at'] 			= date('Y-m-d H:i:s');
			$data['created_by'] 			= $this->auth->user_id();
			$this->db->insert('customers', $data);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Insert New Customer Data " . $data['id_customer'] . " - " . $data['customer_name'];
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
			log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);
			// End Logging
		} else {
			$data['modified_at'] 			= date('Y-m-d H:i:s');
			$data['modified_by'] 			= $this->auth->user_id();
			$this->db->update('customers', $data, ['id_customer' => $data['id_customer']]);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Update Customer Data " . $data['id_customer'] . " - " . $data['customer_name'];
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
			log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);
		}

		$n = 0;
		if ($dataPIC) {
			foreach ($dataPIC as $pic) {
				$n++;
				$dataPic =  array(
					'id_customer'	=> $data['id_customer'],
					'name'			=> $pic['name'],
					'phone_number'	=> $pic['phone_number'],
					'email'			=> $pic['email'],
					'position'		=> $pic['position']
				);

				if (isset($pic['id']) && $pic['id']) {
					$check = $this->db->get_where('customer_pic', ['id' => $pic['id']])->num_rows();
					if ($check > 0) {
						$this->db->update('customer_pic', $pic, ['id' => $pic['id']]);
					}
				} else {
					$this->db->insert('customer_pic', $dataPic);
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$return	= array(
				'msg'		=> 'Failed save data Customer.  Please try again.',
				'status'	=> 0
			);
			$keterangan     = "FAILED save data Customer " . $data['id_customer'] . ", Customer name : " . $data['customer_name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id_customer'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Success Save data Customer.',
				'status'	=> 1
			);
			$keterangan     = "SUCCESS save data Customer " . $data['id_customer'] . ", Customer name : " . $data['customer_name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id_customer'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}

	function delete()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('customers', ['id_customer' => $id])->row_array();

		$this->db->trans_begin();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Customer Data " . $data['id_customer'] . " - " . $data['customer_name'];
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
		log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);
		// End Logging

		$sql = $this->db->update('customers', ['status' => '0', 'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->auth->user_id()], ['id_customer' => $id]);
		$errMsg = $this->db->error()['message'];
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$keterangan     = "FAILD " . $errMsg;
			$status         = 0;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id_customer'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
			$return	= array(
				'msg'		=> "Failed delete data Customer. Please try again. " . $errMsg,
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Delete data Customer.',
				'status'	=> 1
			);
			$keterangan     = "Delete data Customer " . $data['id_customer'] . ", Customer name : " . $data['customer_name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id_customer'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}

	function getProvince()
	{
		$country_id = $_GET['country_id'];
		$search 	= $_GET['q'];
		$states 	= [];
		if (isset($country_id) && $country_id) {
			$states = $this->db->like(['name' => $search])->where(['country_id' => $country_id])->get('states')->result_array();
		}
		echo json_encode($states);
	}

	function getCities()
	{
		$state_id 	= $_GET['state_id'];
		$search 	= $_GET['q'];
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
		$data = $this->db->get_where('customer_pic', ['id' => $id])->row_array();

		$this->db->trans_begin();
		$sql = $this->db->update('customer_pic', ['status' => '2', 'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->auth->user_id()], ['id' => $id]);
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
				'msg'		=> "Failed delete data PIC Customer. Please try again. " . $errMsg,
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Delete data PIC Customer.',
				'status'	=> 1
			);
			$keterangan     = "Delete data PCI Customer " . $data['id'] . ", PIC name : " . $data['name'];
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
