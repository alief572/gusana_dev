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

class Product_jenis extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Product_Jenis.View';
	protected $addPermission  	= 'Product_Jenis.Add';
	protected $managePermission = 'Product_Jenis.Manage';
	protected $deletePermission = 'Product_Jenis.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'upload', 'Image_lib'));
		$this->load->model(array(
			'Product_jenis/Product_jenis_model',
			'Aktifitas/aktifitas_model',
		));
		$this->template->title('Product Jenis');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Product_jenis_model->get_data_category2();
		$this->template->set('results', $data);
		$this->template->title('Product Jenis');
		$this->template->render('index');
	}
	public function editInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-edit');
		$inven = $this->db->get_where('ms_product_category2', array('id_category2' => $id))->row();
		$lvl1 = $this->db->get('ms_product_type')->result();
		$lvl2 = $this->db->get('ms_product_category1')->result();
		// echo '<pre>' . print_r($inven) . '</pre>';
		// exit;
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
		$cust 	= $this->Product_jenis_model->getById($id);
		if ($cust->aktif == 1) {
			$status = '<div class="badge badge-primary">Aktif</div>';
		} else {
			$status = '<div class="badge badge-danger">Non Aktif</div>';
		}
		$data = [
			'cust' => $cust,
			'status' => $status
		];
		$this->template->set('result', $data);
		$this->template->render('view_inventory');
	}
	public function saveEditInventory()
	{
		$this->auth->restrict($this->editPermission);
		$post = $this->input->post();
		// print_r($post);
		// exit();
		$this->db->trans_begin();
		$data = [
			'id_type'		    => $post['inventory_1'],
			'id_category1'		=> $post['inventory_2'],
			'nama'      		=> $post['nm_inventory'],
			'aktif'				=> $post['status'],
			'modified_on'		=> date('Y-m-d H:i:s'),
			'modified_by'		=> $this->auth->user_id()
		];

		$this->db->where('id_category2', $post['id_inventory'])->update("ms_product_category2", $data);

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
		$product_type = $this->db->get('ms_product_type')->result();
		$product_category = $this->db->get('ms_product_category1')->result();
		$data = [
			'product_type' => $product_type,
			'product_category' => $product_category
		];
		$this->template->set('results', $data);
		$this->template->title('Add Product Jenis');
		$this->template->render('add_inventory');
	}
	public function deleteInventory()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');

		$this->db->trans_begin();
		$this->db->delete('ms_product_category2', ['id_category2' => $id]);

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
		$inventory_1 = $_GET['product_type'];
		$data = $this->db->get_where('ms_product_category1', ['id_type' => $inventory_1])->result();

		// print_r($data);
		// exit();
		echo "<select id='product_category' name='product_category' class='form-control input-sm select2'>";
		echo "<option value=''>-- Product Category --</option>";
		foreach ($data as $key => $st) :
			echo "<option value='$st->id_category1' set_select('product_category', $st->id_category1, isset($data->id_category1) && $data->id_category1 == $st->id_category1)>$st->nama
                    </option>";
		endforeach;
		echo "</select>";
	}

	public function saveNewinventory()
	{
		$this->auth->restrict($this->addPermission);
		$post = $this->input->post();
		$code = $this->Product_jenis_model->generate_id();
		$this->db->trans_begin();
		if (isset($post['id_category2'])) {
			$data = [
				'id_type'		    => $post['product_type'],
				'id_category1'		=> $post['product_category'],
				'nama'      		=> $post['nm_product_jenis'],
				'aktif'				=> $post['aktif'],
				'diubah_oleh'		=> date('Y-m-d H:i:s'),
				'diubah_tgl'		=> $this->auth->user_id()
			];
			$update = $this->db->update("ms_product_category2", $data, ['id_category2' => $post['id_category2']]);
		} else {
			$data = [
				'id_category2'		=> $code,
				'id_type'		    => $post['product_type'],
				'id_category1'		=> $post['product_category'],
				'nama'      		=> $post['nm_product_jenis'],
				'aktif'				=> 1,
				'dibuat_oleh'		=> date('Y-m-d H:i:s'),
				'dibuat_tgl'		=> $this->auth->user_id()
			];
			$insert = $this->db->insert("ms_product_category2", $data);
		}


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
