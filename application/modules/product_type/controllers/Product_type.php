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

class Product_type extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Product_Type.View';
	protected $addPermission  	= 'Product_Type.Add';
	protected $managePermission = 'Product_Type.Manage';
	protected $deletePermission = 'Product_Type.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
		$this->load->model(array(
			'Product_type/Product_type_model',
			'Aktifitas/aktifitas_model',
		));
		$this->load->helper(['url', 'json']);
		$this->template->title('Manage Inventory 1');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$this->template->title('Product Type');
		$this->template->render('index');
	}

	public function add()
	{

		// $id_product = Product::where('id_product', 'LIKE', '%PRO-' . date('m-y') . '%')->max('id_product');

		$data = [
			'id_product_type' => $this->Product_type_model->generate_id()
		];

		$this->template->set($data);
		$this->template->render('form');
	}

	public function save()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();

		$num_inventory = $this->db->query("SELECT nama FROM ms_product_type WHERE id_type = '" . $post['id_product_type'] . "'")->num_rows();

		$this->db->trans_begin();
		if ($num_inventory > 0) {
			$data = [
				'nama' => ($post['nama_product_type'] ?: null),
				'aktif' => $post['aktif'],
				'diubah_oleh' => $this->auth->user_id(),
				'diubah_tgl' => date("Y-m-d H:i:s")
			];
			$this->db->update('ms_product_type', $data, ['id_type' => $post['id_product_type']]);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Update Product Type Data " . $post['id_product_type'] . " - " . $post['nama_product_type'];
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
			$data = [
				'id_type' => ($post['id_product_type'] ?: null),
				'nama' => ($post['nama_product_type'] ?: null),
				'aktif' => 1,
				'dibuat_oleh' => $this->auth->user_id(),
				'dibuat_tgl' => date("Y-m-d H:i:s")
			];

			$this->db->insert('ms_product_type', $data);

			// Logging
			$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

			$desc = "Insert New Product Type Data " . $post['id_product_type'] . " - " . $post['nama_product_type'];
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
			$return    = array(
				'msg'        => 'Failed save data Product Type.  Please try again.',
				'status'    => 0
			);
			$keterangan     = "FAILED save data Product Type " . $data['id'] . ", Product Type : " . $data['nama'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return    = array(
				'msg'        => 'Success Save data Product Type.',
				'status'    => 1
			);
			$keterangan     = "SUCCESS save data Product Type " . $data['id'] . ", Product Type : " . $data['nama'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
	}

	public function edit($id)
	{
		$product_type = $this->db->get_where('ms_product_type', array('id' => $id))->row();
		$data = [
			'id_product_type' => $product_type->id_type,
			'product_type' => $product_type
		];
		$this->template->set($data);
		$this->template->render('form_edit');
	}

	public function view($id)
	{
		$product_type = $this->db->get_where('ms_product_type', array('id' => $id))->row();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "View Product Type Data " . $product_type->id_type . " - " . $product_type->nama;
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

		if ($product_type->aktif == 1) {
			$badgeAktif = '<div class="badge badge-success">Aktif</div>';
		} else {
			$badgeAktif = '<div class="badge badge-danger">Non Aktif</div>';
		}
		$data = [
			'id_product_type' => $product_type->id_type,
			'product_type' => $product_type,
			'status' => $badgeAktif
		];
		$this->template->set($data);
		$this->template->render('view');
	}

	public function delete()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('ms_product_type', ['id' => $id])->row_array();

		$this->db->trans_begin();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$desc = "Delete Product Type Data " . $data['id_type'] . " - " . $data['nama'];
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

		$sql = $this->db->delete('ms_product_type', ['id' => $id]);
		$errMsg = $this->db->error()['message'];
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$keterangan     = "FAILED " . $errMsg;
			$status         = 0;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
			$return    = array(
				'msg'        => "Failed delete data Product Type. Please try again. " . $errMsg,
				'status'    => 0
			);
		} else {
			$this->db->trans_commit();
			$return    = array(
				'msg'        => 'Delete data Product Type.',
				'status'    => 1
			);
			$keterangan     = "Delete data Product Type " . $data['id'] . ", Product Type : " . $data['nama'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		}
		simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
		echo json_encode($return);
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

		$string = $this->db->escape_like_str($search);
		$sql = "SELECT * FROM ms_product_type WHERE (id LIKE '%" . $string . "%' OR nama LIKE '%" . $string . "%')";

		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'id',
		);

		// $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
		$sql .= " LIMIT " . $start . " ," . $length . " ";
		$query  = $this->db->query($sql);

		$data  = array();
		$urut1  = 1;
		$urut2  = 0;

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

			$view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
			$edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
			$delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
			$buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;


			if ($row['aktif'] == "1") {
				$badgeAktif = '<div class="badge badge-success">Aktif</div>';
			} else {
				$badgeAktif = '<div class="badge badge-danger">Non Aktif</div>';
			}

			$nestedData   = array();
			$nestedData[]  = $nomor;
			$nestedData[]  = $row['nama'];
			$nestedData[] = $badgeAktif;
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
}
