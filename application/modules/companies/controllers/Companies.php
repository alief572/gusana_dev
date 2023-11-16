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

class Companies extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Companies.View';
	protected $addPermission  	= 'Companies.Add';
	protected $managePermission = 'Companies.Manage';
	protected $deletePermission = 'Companies.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('mpdf', 'upload', 'Image_lib'));
		$this->load->model(array(
			'Companies/Companies_model',
			'Aktifitas/aktifitas_model',
		));
		$this->template->title('Manage Companies');
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
		$where = " AND `status` = '$status'";

		$string = $this->db->escape_like_str($search);
		$sql = "SELECT *,(@row_number:=@row_number + 1) AS num
        FROM companies, (SELECT @row_number:=0) as temp WHERE 1=1 $where  
        AND (company_name LIKE '%$string%'
        OR telephone LIKE '%$string%'
        OR `address` LIKE '%$string%'
        OR `api_type` LIKE '%$string%'
        OR `status` LIKE '%$string%'
            )";

		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'num',
			1 => 'company_name',
			2 => 'telephone',
			3 => 'address',
			4 => 'api_type',
			5 => 'status',
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

			$view 		= '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
			$edit 		= '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
			$delete 	= '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
			$buttons 	= $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

			$documents = isset($row['documents']) ? implode(", ", json_decode($row['documents'])) : '-';


			$nestedData   = array();
			$nestedData[]  = $nomor;
			$nestedData[]  = $row['company_name'];
			$nestedData[]  = $row['telephone'];
			$nestedData[]  = $row['address'];
			$nestedData[]  = $row['api_type'];
			$nestedData[]  = $documents;
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
		$countries = $this->Companies_model->get_data('countries');
		$marketing = $this->db->get_where('employees', array('division' => 'DIV002', 'status' => 1))->result();
		$data = [
			'countries' => $countries,
			'marketing' => $marketing
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function edit($id)
	{
		$this->auth->restrict($this->editPermission);
		$company 				= $this->db->get_where('companies', array('id' => $id))->row();
		$pic 					= $this->db->get_where('companies_pic', ['company_id' => $id, 'status' => '1'])->result();
		$countries 				= $this->Companies_model->get_data('countries');
		$states 				= $this->Companies_model->get_data('states', 'country_id', $company->country_id);
		$cities 				= $this->Companies_model->get_data('cities', 'state_id', $company->state_id);
		$marketing 				= $this->db->get_where('employees', array('division' => 'DIV002', 'status' => 1))->result();
		$receive_invoice_day 	= json_decode($company->receive_invoice_day);
		$invoicing_requirement 	= json_decode($company->invoicing_requirement);
		$path					= 'assets/img/letter-head/';

		$data = [
			'company'					=> $company,
			'countries' 				=> $countries,
			'PIC' 						=> $pic,
			'path' 						=> $path,
			'states' 					=> $states,
			'cities' 					=> $cities,
			'marketing' 				=> $marketing,
			'receive_invoice_day' 		=> $receive_invoice_day,
			'invoicing_requirement' 	=> $invoicing_requirement
		];
		$this->template->set($data);
		$this->template->render('form');
	}


	public function view($id)
	{
		$this->auth->restrict($this->viewPermission);
		$company 				= $this->db->get_where('companies', array('id' => $id))->row();
		$pic 					= $this->db->get_where('companies_pic', ['company_id' => $id, 'status' => '1'])->result();
		$countries 				= $this->db->get_where('countries')->result_array();
		$states 				= $this->db->get_where('states', ['country_id' => $company->country_id])->result_array();
		$cities 				= $this->db->get_where('cities', ['state_id' => $company->state_id])->result_array();
		$receive_invoice_day 	= json_decode($company->receive_invoice_day);
		$invoicing_requirement 	= json_decode($company->invoicing_requirement);

		$ArrCountry 			= array_column($countries, 'name', 'id');
		$ArrStates 				= array_column($states, 'name', 'id');
		$ArrCity 				= array_column($cities, 'name', 'id');
		$path					= 'assets/img/letter-head/';

		$data = [
			'company'					=> $company,
			'PIC' 						=> $pic,
			'path' 						=> $path,
			'ArrCountry' 				=> $ArrCountry,
			'ArrStates' 				=> $ArrStates,
			'ArrCity' 					=> $ArrCity,
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
		$header		= $_FILES['header'];
		$watermark	= $_FILES['watermark'];
		$footer		= $_FILES['footer'];

		$data = $post;
		$data['id'] 						= $post['id'] ?: $this->Companies_model->generate_id();
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
		$data['documents'] 					= ($post['documents']) ? json_encode($post['documents']) : null;
		$dataPIC 							= $post['PIC'];

		unset($data['PIC']);
		unset($data['nominal_dp']);
		unset($data['sisa_pembayaran']);
		unset($data['remove_lh']);

		$config['upload_path'] 				= './assets/img/letter-head/';
		$config['allowed_types'] 			= 'jpg|png';
		$config['max_size']     			= '2048';
		$config['max_width'] 				= '3124';
		$config['max_height'] 				= '712';

		if ($post['remove_lh']) {
			$remove = array_unique(explode(",", $post['remove_lh']));
			foreach ($remove as $rm) {
				$this->db->update('companies', ["$rm" => null], ['id' => $data['id']]);
			}
		}

		if ($header['name']) {
			$path 	= $header['name'];
			$ext 	= pathinfo($path, PATHINFO_EXTENSION);
			$config['file_name'] = $post['id'] . "-" . 'header.' . $ext;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			$this->upload->overwrite = true;
			if (!$this->upload->do_upload('header')) {
				$err 	= "<b>Header</b> -" . $this->upload->display_errors();
				$return = [
					'status' => 0,
					'msg' 	 => $err
				];
				echo json_encode($return);
				return false;
			} else {
				$data_file 			= $this->upload->data();
				$ext 				= $data_file['file_ext'];
				$file_name 			= $data_file['file_name'] = $post['id'] . "-" . 'header' . $ext;
				$data['header'] 	= $file_name;
			}
		}

		if ($watermark['name']) {
			$config['max_height'] 			= '4212';
			$path 	= $watermark['name'];
			$ext 	= pathinfo($path, PATHINFO_EXTENSION);
			$config['file_name'] = $post['id'] . "-" . 'watermark.' . $ext;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			$this->upload->overwrite = true;
			if (!$this->upload->do_upload('watermark')) {
				$err 	= "watermark -" . $this->upload->display_errors();
				$return = [
					'status' => 0,
					'msg' 	 => $err
				];
				echo json_encode($return);
				return false;
			} else {
				$data_file 			= $this->upload->data();
				$ext 				= $data_file['file_ext'];
				$file_name 			= $data_file['file_name'] = $post['id'] . "-" . 'watermark' . $ext;
				$data['watermark'] 	= $file_name;
			}
		}

		if ($footer['name']) {
			$path 	= $footer['name'];
			$ext 	= pathinfo($path, PATHINFO_EXTENSION);
			$config['file_name'] = $post['id'] . "-" . 'footer.' . $ext;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			$this->upload->overwrite = true;
			if (!$this->upload->do_upload('footer')) {
				$err 	= "<b>Footer</b> -" . $this->upload->display_errors();
				$return = [
					'status' => 0,
					'msg' 	 => $err
				];
				echo json_encode($return);
				return false;
			} else {
				$data_file 			= $this->upload->data();
				$ext 				= $data_file['file_ext'];
				$file_name 			= $data_file['file_name'] = $post['id'] . "-" . 'footer' . $ext;
				$data['footer'] 	= $file_name;
			}
		}

		$this->db->trans_begin();
		if (isset($post['id']) && $post['id'] == '') {
			$data['created_at'] 			= date('Y-m-d H:i:s');
			$data['created_by'] 			= $this->auth->user_id();
			$this->db->insert('companies', $data);
		} else {
			$data['modified_at'] 			= date('Y-m-d H:i:s');
			$data['modified_by'] 			= $this->auth->user_id();
			$this->db->update('companies', $data, ['id' => $data['id']]);
		}

		$n = 0;
		if ($dataPIC) {
			foreach ($dataPIC as $pic) {
				$n++;
				$dataPic =  array(
					'company_id'	=> $data['id'],
					'name'			=> $pic['name'],
					'phone_number'	=> $pic['phone_number'],
					'email'			=> $pic['email'],
					'position'		=> $pic['position']
				);

				if (isset($pic['id']) && $pic['id']) {
					$check = $this->db->get_where('companies_pic', ['id' => $pic['id']])->num_rows();
					if ($check > 0) {
						$this->db->update('companies_pic', $pic, ['id' => $pic['id']]);
					}
				} else {
					$this->db->insert('companies_pic', $dataPic);
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$return	= array(
				'msg'		=> 'Failed save data Customer.  Please try again.',
				'status'	=> 0
			);
			$keterangan     = "FAILED save data Customer " . $data['id'] . ", Customer name : " . $data['customer_name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Success Save data Customer.',
				'status'	=> 1
			);
			$keterangan     = "SUCCESS save data Customer " . $data['id'] . ", Customer name : " . $data['customer_name'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}

	function deleteCustomer()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('companies', ['id' => $id])->row_array();

		$this->db->trans_begin();
		$sql = $this->db->update('companies', ['status' => 'X', 'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->auth->user_id()], ['id' => $id]);
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
				'msg'		=> "Failed delete data Customer. Please try again. " . $errMsg,
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$return	= array(
				'msg'		=> 'Delete data Customer.',
				'status'	=> 1
			);
			$keterangan     = "Delete data Customer " . $data['id'] . ", Customer name : " . $data['customer_name'];
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

	public function viewCustomer($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-edit');
		$aktif = 'active';
		$cus = $this->db->get_where('master_companies', array('id' => $id))->result();
		$pic = $this->db->get_where('companies_pic', array('id' => $id))->result();
		$cate = $this->db->get_where('child_category_customer', array('id' => $id))->result();
		$category = $this->Companies_model->get_data('child_customer_category', 'activation', $aktif);
		$prof = $this->Companies_model->get_data('provinsi');
		$kota = $this->Companies_model->get_data('kota');
		$karyawan = $this->db->get_where('employees', array('deleted_at' => null, '', 'divisi' => 2))->result();

		$data = [
			'cus'	=> $cus,
			'category' => $category,
			'cate' => $cate,
			'kota' => $kota,
			'prof' => $prof,
			'pic' => $pic,
			'karyawan' => $karyawan
		];
		$this->template->set('results', $data);
		$this->template->title('View Customer');
		$this->template->render('view_customer');
	}


	/* PIC */

	function deletePic()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('companies_pic', ['id' => $id])->row_array();

		$this->db->trans_begin();
		$sql = $this->db->update('companies_pic', ['status' => '2', 'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->auth->user_id()], ['id' => $id]);
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
