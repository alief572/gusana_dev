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

class Inventory_1 extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Level_1.View';
	protected $addPermission  	= 'Level_1.Add';
	protected $managePermission = 'Level_1.Manage';
	protected $deletePermission = 'Level_1.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib'));
		$this->load->model(array(
			'Inventory_1/Inventory_1_model',
			'Aktifitas/aktifitas_model',
		));
		$this->template->title('Manage Inventory 1');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$this->template->title('Inventory Level 1');
		$this->template->render('index');
	}

	public function add()
	{
		$this->auth->restrict($this->viewPermission);
		// $id_product = Product::where('id_product', 'LIKE', '%PRO-' . date('m-y') . '%')->max('id_product');

		$data = [
			'id_inventory_level_1' => $this->Inventory_1_model->generate_id()
		];

		$this->template->set($data);
		$this->template->render('form');
	}

	public function save()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();

		$num_inventory = $this->db->query("SELECT nama FROM ms_inventory_type WHERE id_type = '" . $post['id_inventory_level_1'] . "'")->num_rows();

		$this->db->trans_begin();
		if ($num_inventory > 0) {
			$data = [
				'nama' => ($post['nama_inventory_level_1'] ?: null),
				'aktif' => ($post['aktif'] ?: null),
				'deleted' => '0',
				'modified_by' => $this->auth->user_id(),
				'modified_on' => date("Y-m-d H:i:s")
			];

			$this->db->update('ms_inventory_type', $data, ['id_type' => $post['id_inventory_level_1']]);
		} else {
			$data = [
				'id_type' => ($post['id_inventory_level_1'] ?: null),
				'nama' => ($post['nama_inventory_level_1'] ?: null),
				'aktif' => ($post['aktif'] ?: null),
				'deleted' => '0',
				'created_by' => $this->auth->user_id(),
				'created_on' => date("Y-m-d H:i:s")
			];

			$this->db->insert('ms_inventory_type', $data);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$return    = array(
				'msg'        => 'Failed save data Inventory.  Please try again.',
				'status'    => 0
			);
			$keterangan     = "FAILED save data Inventory " . $data['id'] . ", Inventory name : " . $data['nama'];
			$status         = 1;
			$nm_hak_akses   = $this->addPermission;
			$kode_universal = $data['id'];
			$jumlah         = 1;
			$sql            = $this->db->last_query();
		} else {
			$this->db->trans_commit();
			$return    = array(
				'msg'        => 'Success Save data Inventory.',
				'status'    => 1
			);
			$keterangan     = "SUCCESS save data Inventory " . $data['id'] . ", Inventory name : " . $data['nama'];
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
		$inventory = $this->db->get_where('ms_inventory_type', array('id' => $id))->row();
		$data = [
			'id_inventory_level_1' => $inventory->id_type,
			'inventory' => $inventory
		];
		$this->template->set($data);
		$this->template->render('form');
	}

	public function view($id)
	{
		$inventory = $this->db->get_where('ms_inventory_type', array('id' => $id))->row();
		$badgeAktif = "";
		if ($inventory->aktif == "aktif") {
			$badgeAktif = '<div class="badge badge-success">Aktif</div>';
		}
		if ($inventory->aktif == "nonaktif") {
			$badgeAktif = '<div class="badge badge-danger">Non Aktif</div>';
		}
		$data = [
			'id_inventory_level_1' => $inventory->id_type,
			'inventory' => $inventory,
			'status' => $badgeAktif
		];
		$this->template->set($data);
		$this->template->render('view');
	}

	public function delete()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('ms_inventory_type', ['id' => $id])->row_array();

		$this->db->trans_begin();
		$sql = $this->db->delete('ms_inventory_type', ['id' => $id]);
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
				'msg'        => "Failed delete data Inventory Level 1. Please try again. " . $errMsg,
				'status'    => 0
			);
		} else {
			$this->db->trans_commit();
			$return    = array(
				'msg'        => 'Delete data Inventory Level 1.',
				'status'    => 1
			);
			$keterangan     = "Delete data Inventory Level 1 " . $data['id'] . ", Inventory Level 1 name : " . $data['nama'];
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
		$sql = "SELECT * FROM ms_inventory_type WHERE (id LIKE '%" . $string . "%' OR nama LIKE '%" . $string . "%')";

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


			$badgeAktif = "";
			if ($row['aktif'] == "aktif") {
				$badgeAktif = '<div class="badge badge-success">Aktif</div>';
			}
			if ($row['aktif'] == "nonaktif") {
				$badgeAktif = '<div class="badge badge-danger">Non Aktif</div>';
			}

			$nestedData   = array();
			$nestedData[]  = $row['id_type'];
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
