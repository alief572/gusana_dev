<?php

use Mpdf\Tag\Pre;

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/*
 * @author Ichsan
 * @copyright Copyright (c) 2019, Ichsan
 *
 * This is controller for Master Supplier
 */

class Material_master extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Material_Master.View';
	protected $addPermission  	= 'Material_Master.Add';
	protected $managePermission = 'Material_Master.Manage';
	protected $deletePermission = 'Material_Master.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'Image_lib', 'input', 'user_agent', 'uri'));
		$this->load->library('upload');
		$this->load->helper(['form', 'url', 'json']);

		$this->load->model(array(
			'Material_master/Material_master_model',
			'Aktifitas/aktifitas_model',
		));
		$this->template->title('Manage Data Supplier');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function addAltSupplier()
	{
		$dataSupplier = $this->db->get_where('master_supplier', ['id' => $this->input->post('id_supplier')])->row();
		$dataCategory3 = $this->db->get_where('ms_inventory_category3', ['id_category3' => $this->input->post('alt_material_id')])->row();

		// $id_bm1 = BarangMasuk::where('id_bm1', 'LIKE', '%BM1-' . date('m-y') . '%')->max('id_bm1');
		$id_bm1 = $this->db->query("SELECT MAX(id) AS max_id FROM ms_inventory_category3_alt_supplier WHERE id LIKE '%ALC3-" . date('m-y') . "%'")->result();
		$kodeBarang = $id_bm1[0]->max_id;
		$urutan = (int) substr($kodeBarang, 11, 5);
		$urutan++;
		$tahun = date('m-y');
		$huruf = "ALC3-";
		$generate_id = $huruf . $tahun . sprintf("%06s", $urutan);

		$this->db->trans_begin();

		$this->db->insert('ms_inventory_category3_alt_supplier', [
			'id' => $generate_id,
			'id_category3' => $this->input->post('id_category3'),
			'id_supplier' => $this->input->post('id_supplier'),
			'nm_supplier' => $dataSupplier->supplier_name,
			'lead_time' => $this->input->post('lead_time'),
			'moq' => $this->input->post('moq'),
			'alt_material_id' => $this->input->post('alt_material_id'),
			'alt_material_nm' => $dataCategory3->nama,
			'keterangan' => $this->input->post('keterangan'),
			'dibuat_oleh' => $this->auth->user_id(),
			'dibuat_tgl' => date("Y-m-d H:i:s")
		]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$hasil = '';

		$dataGetAltSupplier = $this->db->get_where('ms_inventory_category3_alt_supplier', ['id_category3' => $this->input->post('id_category3')])->result();
		foreach ($dataGetAltSupplier as $altSupplier) {
			$hasil = $hasil . '
				<tr>
					<td>
						<select name="" id="" class="form-control form-control-sm alt_supplier_' . $altSupplier->id . '">
							<option value="">-- Nama Supplier --</option>
						';

			$dataGetSupplier = $this->db->get_where('master_supplier')->result();
			foreach ($dataGetSupplier as $dataSupplier) {

				$selected = '';
				if ($this->input->post('id_supplier') == $dataSupplier->id) {
					$selected = 'selected';
				}

				$hasil = $hasil . '
					<option value="' . $dataSupplier->id . '" ' . $selected . '>' . $dataSupplier->supplier_name . '</option>
				';
			}

			$hasil = $hasil . '
						</select>
					</td>
					<td>
						<input type="text" name="" id="" class="form-control form-control-sm alt_lead_time_' . $altSupplier->id . '" placeholder="Lead Time" value="' . $altSupplier->lead_time . '">
					</td>
					<td>
						<input type="text" name="" id="" class="form-control form-control-sm alt_moq_' . $altSupplier->id . '" placeholder="MOQ" value="' . $altSupplier->moq . '">
					</td>
					<td>
						<select name="" id="" class="form-control form-control-sm alt_material_' . $altSupplier->id . '">
							<option value="">-- Alternatif Material --</option>
					';

			$dataGetInventory4 = $this->db->get('ms_inventory_category3')->result();
			foreach ($dataGetInventory4 as $inventory4) {

				$selected = '';
				if ($this->input->post('alt_material_id') == $inventory4->id_category3) {
					$selected = 'selected';
				}

				$hasil = $hasil . '
					<option value="' . $inventory4->id_category3 . '" ' . $selected . '>' . $inventory4->nama . '</option>
				';
			}

			$hasil = $hasil . '
						</select>
					</td>
					<td>
						<input type="text" name="" id="" class="form-control form-control-sm alt_keterangan_' . $altSupplier->id . '" placeholder="Keterangan" value="' . $altSupplier->keterangan . '">
					</td>
					<td>
						<button type="button" class="btn btn-sm btn-danger del_inventory_category3_alt_supp" data-id="' . $altSupplier->id . '">
							<i class="fa fa-trash"></i>
						</button>
					</td>
				</tr>
			';
		}
		echo $hasil;
	}

	public function delAltSupplier()
	{
		$this->db->trans_begin();

		$this->db->delete('ms_inventory_category3_alt_supplier', ['id' => $this->input->post('id')]);

		// print_r($this->db->trapns_status);

		if ($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();

			$hasil = '';

			$dataGetAltSupplier = $this->db->get_where('ms_inventory_category3_alt_supplier', ['id_category3' => $this->input->post('id_category3')])->result();
			foreach ($dataGetAltSupplier as $altSupplier) {
				$hasil = $hasil . '
					<tr>
						<td>
							<select name="" id="" class="form-control form-control-sm alt_supplier_' . $altSupplier->id . '">
								<option value="">-- Nama Supplier --</option>
							';

				$dataGetSupplier = $this->db->get_where('master_supplier')->result();
				foreach ($dataGetSupplier as $dataSupplier) {

					$selected = '';
					if ($altSupplier->id_supplier == $dataSupplier->id) {
						$selected = 'selected';
					}

					$hasil = $hasil . '
						<option value="' . $dataSupplier->id . '" ' . $selected . '>' . $dataSupplier->supplier_name . '</option>
					';
				}

				$hasil = $hasil . '
							</select>
						</td>
						<td>
							<input type="text" name="" id="" class="form-control form-control-sm alt_lead_time_' . $altSupplier->id . '" placeholder="Lead Time" value="' . $altSupplier->lead_time . '">
						</td>
						<td>
							<input type="text" name="" id="" class="form-control form-control-sm alt_moq_' . $altSupplier->id . '" placeholder="MOQ" value="' . $altSupplier->moq . '">
						</td>
						<td>
							<select name="" id="" class="form-control form-control-sm alt_material_' . $altSupplier->id . '">
								<option value="">-- Alternatif Material --</option>
						';

				$dataGetInventory4 = $this->db->get('ms_inventory_category3')->result();
				foreach ($dataGetInventory4 as $inventory4) {

					$selected = '';
					if ($altSupplier->alt_material_id == $inventory4->id_category3) {
						$selected = 'selected';
					}

					$hasil = $hasil . '
						<option value="' . $inventory4->id_category3 . '" ' . $selected . '>' . $inventory4->nama . '</option>
					';
				}

				$hasil = $hasil . '
							</select>
						</td>
						<td>
							<input type="text" name="" id="" class="form-control form-control-sm alt_keterangan_' . $altSupplier->id . '" placeholder="Keterangan" value="' . $altSupplier->keterangan . '">
						</td>
						<td>
							<button type="button" class="btn btn-sm btn-danger del_inventory_category3_alt_supp" data-id="' . $altSupplier->id . '">
								<i class="fa fa-trash"></i>
							</button>
						</td>
					</tr>
				';
			}
			echo $hasil;
		}
	}

	public function index1()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Material_master_model->get_data('ms_bentuk', 'deleted', $deleted);
		$this->template->set('results', $data);
		$this->template->title('Material');
		$this->template->render('index');
	}
	public function index()
	{
		$id_bentuk = $this->uri->segment(3);
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Material_master_model->get_data_category3();
		$this->template->set('results', $data);
		$this->template->title('Material');
		$this->template->render('list');
	}
	public function editInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-pencil');

		$id_type = $this->input->post('id_type');

		$deleted = '0';

		$inventory_4 = $this->Material_master_model->get_data_category3_where($id);
		$inventory_1 = $this->Material_master_model->get_data('ms_inventory_type');
		$inventory_2 = $this->db->get_where('ms_inventory_category1', ['id_type' => $inventory_4->id_type, 'deleted' => 0])->result();
		$inventory_3 = $this->db->get_where('ms_inventory_category2', ['id_category1' => $inventory_4->id_category1, 'deleted' => 0])->result();
		$inv_4_alt = $this->db->get_where('ms_inventory_category3', ['id_category2' => $inventory_4->id_category2, 'deleted' => 0])->result();
		$alt_suppliers = $this->Material_master_model->get_data_category3_alt_suppliers($id);
		$packaging = $this->db->get('master_packaging')->result();
		$suppliers = $this->db->get('master_supplier')->result();
		$unit = $this->db->get('m_unit')->result();
		$kategori_fg = $this->db->get('kategori_finish_goods')->result();
		$material_master = $this->db->get('ms_inventory_category3')->result();

		$data = [
			'inventory_1' => $inventory_1,
			'inventory_2' => $inventory_2,
			'inventory_3' => $inventory_3,
			'inventory_4' => $inventory_4,
			'inv_4_alt' => $inv_4_alt,
			'alt_suppliers' => $alt_suppliers,
			'packaging' => $packaging,
			'suppliers' => $suppliers,
			'unit' => $unit,
			'kategori_fg' => $kategori_fg,
			'material_master' => $material_master
		];



		$this->template->set('results', $data);
		$this->template->title('Edit Inventory');
		$this->template->render('edit_inventory');
	}
	public function copyInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-pencil');
		$deleted = '0';
		$inven = $this->Material_master_model->getedit($id);
		$komposisiold = $this->Material_master_model->get_data('child_inven_compotition', 'id_category3', $id);
		$komposisi = $this->Material_master_model->kompos($id);
		$dimensiold = $this->Material_master_model->get_data('child_inven_dimensi', 'id_category3', $id);
		$dimensi = $this->Material_master_model->dimensy($id);
		$supl = $this->Material_master_model->supl($id);
		$inventory_1 = $this->Material_master_model->get_data('ms_inventory_type', 'deleted', $deleted);
		$inventory_2 = $this->Material_master_model->get_data('ms_inventory_category1', 'deleted', $deleted);
		$inventory_3 = $this->Material_master_model->get_data('ms_inventory_category2', 'deleted', $deleted);
		$maker = $this->Material_master_model->get_data('negara');
		$id_bentuk = $this->Material_master_model->get_data('ms_bentuk');
		$id_supplier = $this->Material_master_model->get_data('master_supplier');
		$id_surface = $this->Material_master_model->get_data('ms_surface');
		$dt_suplier = $this->Material_master_model->get_data('child_inven_suplier', 'id_category3', $id);
		$data = [
			'inventory_1' => $inventory_1,
			'inventory_2' => $inventory_2,
			'inventory_3' => $inventory_3,
			'komposisi' => $komposisi,
			'dimensi' => $dimensi,
			'id_bentuk' => $id_bentuk,
			'inven' => $inven,
			'maker' => $maker,
			'supl' => $supl,
			'id_surface' => $id_surface,
			'id_supplier' => $id_supplier,
			'dt_suplier' => $dt_suplier
		];
		$this->template->set('results', $data);
		$this->template->title('Add Inventory');
		$this->template->render('copy_inventory');
	}
	public function viewInventory($id)
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-pencil');
		$deleted = '0';
		$inventory_1 = $this->Material_master_model->get_data('ms_inventory_type', 'deleted', $deleted);
		$inventory_2 = $this->Material_master_model->get_data('ms_inventory_category1', 'deleted', $deleted);
		$inventory_3 = $this->Material_master_model->get_data('ms_inventory_category2', 'deleted', $deleted);
		$inventory_4 = $this->Material_master_model->get_data_category3_where($id);
		$alt_suppliers = $this->Material_master_model->get_data_category3_alt_suppliers($id);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


		$desc = "View Material Master Data " . $id . " - " . $inventory_4->nama;
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

		$data = [
			'inventory_1' => $inventory_1,
			'inventory_2' => $inventory_2,
			'inventory_3' => $inventory_3,
			'inventory_4' => $inventory_4,
			'alt_suppliers' => $alt_suppliers
		];
		$this->template->set('results', $data);
		$this->template->title('Add Inventory');
		if ($inventory_4->id_type == "I2300003") {
			$this->template->render('view_fg');
		} else {
			$this->template->render('view_inventory');
		}
	}
	public function viewBentuk($id)
	{
		$this->auth->restrict($this->viewPermission);
		$id 	= $this->input->post('id');
		$bentuk = $this->db->get_where('ms_bentuk', array('id_bentuk' => $id))->result();
		$dimensi = $this->Bentuk_model->getDimensi($id);
		$data = [
			'bentuk' => $bentuk,
			'dimensi' => $dimensi,
		];
		$this->template->set('results', $data);
		$this->template->render('view_bentuk');
	}


	public function addInventory()
	{
		$id_data 	= $this->input->post('id');
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-pencil');

		$post = $this->input->post();

		$deleted = '0';
		$inventory_1 = $this->Material_master_model->get_data('ms_inventory_type', 'deleted', $deleted);
		$maker = $this->Material_master_model->get_data('negara');
		$dimensi = $this->Material_master_model->get_data('ms_dimensi', 'id_bentuk', $id_data);
		$id_bentuk = $this->Material_master_model->get_data('ms_bentuk', 'id_bentuk', $id_data);
		$id_supplier = $this->Material_master_model->get_data('master_supplier', 'status', '1');
		$id_surface = $this->Material_master_model->get_data('ms_surface');
		$supplier = $this->Material_master_model->get_data('master_supplier', 'status', '1');
		$packaging = $this->Material_master_model->get_data('master_packaging');
		$unit = $this->Material_master_model->get_data('m_unit');
		$inventory_4 = $this->Material_master_model->get_data('ms_inventory_category3', 'deleted', $deleted);
		$inventory_3 = $this->Material_master_model->get_data('ms_inventory_category2', 'deleted', $deleted);
		$kategori_fg = $this->Material_master_model->get_data('kategori_finish_goods');
		$inv_lv_1 = "";
		if (isset($post['inv_lv_1'])) {
			$inv_lv_1 = $this->input->post('inv_lv_1');
		}

		$inv_2 = '';
		$inv_3 = '';
		if ($inv_lv_1 !== "") {
			$inv_2 = $this->Material_master_model->get_data('ms_inventory_category1', 'id_type', $inv_lv_1);
		}

		$material_master = $this->db->get('ms_inventory_category3')->result();

		$data = [
			'inventory_1' => $inventory_1,
			'id_bentuk' => $id_bentuk,
			'dimensi' => $dimensi,
			'maker' => $maker,
			'id_surface' => $id_surface,
			'id_supplier' => $id_supplier,
			'supplier' => $supplier,
			'packaging' => $packaging,
			'unit' => $unit,
			'inventory_4' => $inventory_4,
			'inventory_3' => $inventory_3,
			'id_category3' => $this->auth->user_id(),
			'inv_lv_1' => $inv_lv_1,
			'inv_2' => $inv_2,
			'kategori_fg' => $kategori_fg,
			'material_master' => $material_master
		];

		$this->template->set('results', $data);
		$this->template->title('Add Inventory');

		if ($inv_lv_1 == "I2300003") {
			$this->template->render('add_inventory_fg');
		} else {
			$this->template->render('add_inventory');
		}
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
		$this->db->where('id_dimensi', $id)->update("ms_dimensi", $data);

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

	public function deleteInventory()
	{
		$this->auth->restrict($this->deletePermission);
		$id = $this->input->post('id');
		$data = [
			'deleted' 		=> '1',
			'deleted_by' 	=> $this->auth->user_id()
		];

		$this->db->trans_begin();

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

		$get_data = $this->db->get_where('ms_inventory_category3', ['id_category3' => $id])->row();

		$desc = "Delete New Material Master Data " . $id . " - " . $get_data->nama;
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

		$this->db->delete("ms_inventory_category3_alt_supplier", ['id_category3' => $this->input->post('id')]);
		$this->db->delete("ms_inventory_category3", ['id_category3' => $this->input->post('id')]);

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
		$data = $this->Material_master_model->level_2($inventory_1);
		echo "<select id='inventory_2' name='inventory_2' class='form-control onchange='get_inv3()'  input-sm select2'>";
		echo "<option value=''>-- Pilih Material Category --</option>";
		foreach ($data as $key => $st) :
			echo "<option value='" . $st->id_category1 . "' set_select('inventory_2', $st->id_category1, isset($data->id_category1) && $data->id_category1 == $st->id_category1)>$st->nama
                    </option>";
		endforeach;
		echo "</select>";
	}

	function get_namainven2()
	{
		$inventory_1 = $_POST['inventory_1'];

		$data = $this->db->query("SELECT * from ms_inventory_category2 WHERE id_category2 = '" . $inventory_1 . "'")->result_array();

		echo $data->nama;
	}
	function get_inven3()
	{
		$inventory_2 = $_GET['inventory_2'];
		$data = $this->Material_master_model->level_3($inventory_2);

		// print_r($data);
		// exit();
		echo "<select id='inventory_3' name='inventory_3' class='form-control input-sm select2'>";
		echo "<option value=''>-- Pilih Material Jenis --</option>";
		foreach ($data as $key => $st) :
			echo "<option value='" . $st->id_category2 . "' set_select('inventory_3', $st->id_category2, isset($data->id_category2) && $data->id_category2 == $st->id_category2)>$st->nama
                    </option>";
		endforeach;
		echo "</select>";
	}
	public function saveNewInventory()
	{
		$this->auth->restrict($this->addPermission);
		$session = $this->session->userdata('app_session');

		$config['upload_path'] = './uploads/'; //path folder
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|doc|docx|xls|xlsx|ppt|pptx|pdf|rar|zip|vsd'; //type yang dapat diakses bisa anda sesuaikan
		$config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
		$config['encrypt_name'] = FALSE; // Encrypt the uploaded file's name.
		$config['remove_spaces'] = FALSE; // Remove spaces from the file name.

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('msds')) {
			$data = 'Upload Error';
		} else {
			$data = $this->upload->data();
			$data = '/uploads/' . $data['file_name'];
		}
		// exit;



		$code = $this->Material_master_model->generate_id();

		$this->db->trans_begin();
		$numb1 = 0;
		//$head = $_POST['hd1'];
		$this->db->update('ms_inventory_category3_alt_supplier', ['id_category3' => $code], ['id_category3' => $this->auth->user_id()]);
		$this->db->insert('ms_inventory_category3', [
			'id_category3' => $code,
			'id_type' => $this->input->post('inventory_1'),
			'id_category1' => $this->input->post('inventory_2'),
			'id_category2' => $this->input->post('inventory_3'),
			'nama' => $this->input->post('nm_lv_4'),
			'supplier_utama' => $this->input->post('supplier_utama'),
			'lead_time' => $this->input->post('lead_time'),
			'moq' => $this->input->post('moq'),
			'packaging' => $this->input->post('packaging'),
			'konversi' => $this->input->post('konversi'),
			'unit' => $this->input->post('unit'),
			'material_code' => $this->input->post('material_code'),
			'max_stok' => $this->input->post('max_stok'),
			'min_stok' => $this->input->post('min_stok'),
			'dim_length' => $this->input->post('dim_length'),
			'dim_width' => $this->input->post('dim_width'),
			'dim_height' => $this->input->post('dim_height'),
			'cbm' => $this->input->post('cbm'),
			'msds' => $data,
			'deleted' => 0,
			'aktif' => 'aktif',
			'created_by' => $this->auth->user_id(),
			'created_on' => date("Y-m-d H:i:s")
		]);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


		$desc = "Insert New Material Master Data " . $code . " - " . $this->input->post('nm_lv_4');
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
	public function saveEditInventory()
	{
		$this->auth->restrict($this->addPermission);
		$session = $this->session->userdata('app_session');
		$code = $this->Material_master_model->generate_id();
		$this->db->trans_begin();


		$config['upload_path'] = './uploads/'; //path folder
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|doc|docx|xls|xlsx|ppt|pptx|pdf|rar|zip|vsd'; //type yang dapat diakses bisa anda sesuaikan
		$config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
		$config['encrypt_name'] = FALSE; // Encrypt the uploaded file's name.
		$config['remove_spaces'] = FALSE; // Remove spaces from the file name.

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('msds')) {
			$data = 'Upload Error';
		} else {
			$data = $this->upload->data();
			$data = '/uploads/' . $data['file_name'];
		}
		$numb1 = 0;
		$post = $this->input->post();

		$this->db->update("ms_inventory_category3", [
			'id_type' => $this->input->post('inventory_1'),
			'id_category1' => $this->input->post('inventory_2'),
			'id_category2' => $this->input->post('inventory_3'),
			'nama' => $this->input->post('nm_lv_4'),
			'supplier_utama' => $this->input->post('supplier_utama'),
			'lead_time' => $this->input->post('lead_time'),
			'moq' => $this->input->post('moq'),
			'packaging' => $this->input->post('packaging'),
			'konversi' => $this->input->post('konversi'),
			'unit' => $this->input->post('unit'),
			'material_code' => $this->input->post('material_code'),
			'max_stok' => $this->input->post('max_stok'),
			'min_stok' => $this->input->post('min_stok'),
			'dim_length' => $this->input->post('dim_length'),
			'dim_width' => $this->input->post('dim_width'),
			'dim_height' => $this->input->post('dim_height'),
			'cbm' => $this->input->post('cbm'),
			'msds' => $data,
			'aktif' => $this->input->post('aktif'),
			'modified_on' => date('Y-m-d H:i:s'),
			'modified_by' => $this->auth->user_id(),
			'deleted' => '0'
		], ['id_category3' => $post['id_category3']]);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


		$desc = "Update Material Master Data " . $this->input->post('id_category3') . " - " . $this->input->post('nm_lv_4');
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
	function get_compotition_new()
	{
		$inventory_2 = $_GET['inventory_2'];
		$comp = $this->Material_master_model->compotition($inventory_2);
		$numb = 0;
		// print_r($data);
		// exit();
		foreach ($comp as $key => $cmp) : $numb++;
			echo "<tr>
					  <td hidden align='left'>
					  <input type='text' name='compo[$numb][id_compotition]' readonly class='form-control'  value='" . $cmp->id_compotition . "'>
					  </td>
					  <td align='left'>
					  ";

			echo $cmp->name_compotition;

			echo "
					  </td>
					  <td align='left'>
					  <input type='text' name='compo[$numb][jumlah_kandungan]' class='form-control'>
					  </td>
					  <td align='left'>%</td>
                    </tr>";
		endforeach;
		echo "</select>";
	}
	function get_dimensi()
	{
		$id_bentuk = $_GET['id_bentuk'];
		$dim = $this->Material_master_model->bentuk($id_bentuk);
		$numb = 0;
		// print_r($data);
		// exit();
		foreach ($dim as $key => $ensi) : $numb++;
			echo "<tr>
					  <td align='left' hidden>
					  <input type='text' name='dimens[$numb][id_dimensi]' readonly class='form-control'  value='" . $ensi->id_dimensi . "'>
					  </td>
					  <td align='left'>
					  $ensi->nm_dimensi
					  </td>
					  <td align='left'>
					  <input type='text' name='dimens[$numb][nilai_dimensi]' class='form-control'>
					  </td>
                    </tr>";
		endforeach;
		echo "</select>";
	}
	function get_compotition_old()
	{
		$inventory_2 = $_GET['inventory_2'];
		$comp = $this->Material_master_model->compotition_edit($inventory_2);
		$numb = 0;
		// print_r($data);
		// exit();
		foreach ($comp as $key => $cmp) : $numb++;
			echo "<tr>
					  <td hidden align='left'>
					  <input type='text' name='compo[$numb][id_compotition]' readonly class='form-control'  value='" . $cmp->id_compotition . "'>
					  </td>
					  <td align='left'>
					  $cmp->name_compotition
					  </td>
					  <td align='left'>
					  <input type='text' name='compo[$numb][jumlah_kandungan]' class='form-control'>
					  </td>
					  <td align='left'>%</td>
                    </tr>";
		endforeach;
		echo "</select>";
	}
	function get_dimensi_old()
	{
		$id_bentuk = $_GET['id_bentuk'];
		$dim = $this->Material_master_model->bentuk_edit($id_bentuk);
		$numb = 0;
		// print_r($data);
		// exit();
		foreach ($dim as $key => $ensi) : $numb++;
			echo "<tr>
					  <td hidden align='left'>
					  <input type='text' name='dimens[$numb][id_dimensi]' readonly class='form-control'  value='" . $ensi->id_dimensi . "'>
					  </td>
					  <td align='left'>
					  $ensi->nm_dimensi
					  </td>
					  <td align='left'>
					  <input type='text' name='dimens[$numb][nilai_dimensi]' class='form-control'>
					  </td>
                    </tr>";
		endforeach;
		echo "</select>";
	}
	public function saveEditInventorylama()
	{
		$this->auth->restrict($this->addPermission);
		$session = $this->session->userdata('app_session');
		$code = $this->Material_master_model->generate_id();
		$this->db->trans_begin();
		$id = $_POST['hd1']['1']['id_category3'];
		$numb1 = 0;
		//$head = $_POST['hd1'];
		foreach ($_POST['hd1'] as $h1) {
			$numb1++;

			$header1 =  array(
				'id_type'		        => $h1['inventory_1'],
				'id_category1'		    => $h1['inventory_2'],
				'id_category2'		    => $h1['inventory_3'],
				'nama'		        	=> $h1['nm_inventory'],
				'maker'		        	=> $h1['maker'],
				'density'		        => $h1['density'],
				'hardness'		        => $h1['hardness'],
				'id_bentuk'		        => $h1['id_bentuk'],
				'id_surface'		    => $h1['id_surface'],
				'mountly_forecast'		=> $h1['mountly_forecast'],
				'safety_stock'		    => $h1['safety_stock'],
				'order_point'		    => $h1['order_point'],
				'maksimum'		    	=> $h1['maksimum'],
				'aktif'					=> 'aktif',
				'created_on'		=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->auth->user_id(),
				'deleted'			=> '0'
			);
			//Add Data
			$this->db->where('id_category3', $id)->update("ms_inventory_category3", $header1);
		}
		$this->db->delete('child_inven_suplier', array('id_category3' => $id));
		if (empty($_POST['data1'])) {
		} else {
			$numb2 = 0;
			foreach ($_POST['data1'] as $d1) {
				$numb2++;
				$data1 =  array(
					'id_category3' => $code,
					'id_suplier' => $d1['id_supplier'],
					'lead' => $d1['lead'],
					'minimum' => $d1['minimum'],
					'deleted' => '0',
					'created_on' => date('Y-m-d H:i:s'),
					'created_by' => $this->auth->user_id(),
				);
				//Add Data
				$this->db->insert('child_inven_suplier', $data1);
			}
		}
		if (empty($_POST['compo'])) {
		} else {
			$numb3 = 0;
			foreach ($_POST['compo'] as $c1) {
				$numb3++;
				$comp =  array(
					'id_category3' => $code,
					'id_compotition' => $c1['id_compotition'],
					'nilai_compotition' => $c1['jumlah_kandungan'],
					'deleted' => '0',
					'created_on' => date('Y-m-d H:i:s'),
					'created_by' => $this->auth->user_id(),
				);
				//Add Data
				$this->db->insert('child_inven_compotition', $comp);
			}
		}
		if (empty($_POST['dimens'])) {
		} else {
			$numb4 = 0;
			foreach ($_POST['dimens'] as $dm) {
				$numb4++;
				$dms =  array(
					'id_category3' => $code,
					'id_dimensi' => $dm['id_dimensi'],
					'nilai_dimensi' => $dm['nilai_dimensi'],
					'deleted' => '0',
					'created_on' => date('Y-m-d H:i:s'),
					'created_by' => $this->auth->user_id(),
				);
				//Add Data
				$this->db->insert('child_inven_dimensi', $dms);
			}
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
				'pesan'		=> 'Success Save Item. invenThanks ...',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
	public function saveEditInventoryOld()
	{
		$this->auth->restrict($this->addPermission);
		$session = $this->session->userdata('app_session');
		$code = $this->Material_master_model->generate_id();
		$this->db->trans_begin();
		$id = $_POST['hd1']['1']['id_category3'];
		$numb1 = 0;
		//$head = $_POST['hd1'];
		foreach ($_POST['hd1'] as $h1) {
			$numb1++;

			$header1 =  array(
				'id_type'		        => $h1['inventory_1'],
				'id_category1'		    => $h1['inventory_2'],
				'id_category2'		    => $h1['inventory_3'],
				'nama'		        	=> $h1['nm_inventory'],
				'maker'		        	=> $h1['maker'],
				'density'		        => $h1['density'],
				'hardness'		        => $h1['hardness'],
				'id_bentuk'		        => $h1['id_bentuk'],
				'id_surface'		    => $h1['id_surface'],
				'mountly_forecast'		=> $h1['mountly_forecast'],
				'safety_stock'		    => $h1['safety_stock'],
				'order_point'		    => $h1['order_point'],
				'maksimum'		    	=> $h1['maksimum'],
				'aktif'					=> 'aktif',
				'created_on'		=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->auth->user_id(),
				'deleted'			=> '0'
			);
			//Add Data
			$this->db->where('id_category3', $id)->update("ms_inventory_category3", $header1);
		}
		if (empty($_POST['data1'])) {
		} else {
			$numb2 = 0;
			foreach ($_POST['data1'] as $d1) {
				$numb2++;
				$data1 =  array(
					'id_category3' => $id,
					'id_suplier' => $d1['id_supplier'],
					'lead' => $d1['lead'],
					'minimum' => $d1['minimum'],
					'deleted' => '0',
					'created_on' => date('Y-m-d H:i:s'),
					'created_by' => $this->auth->user_id(),
				);
				//Add Data
				$this->db->insert('child_inven_suplier', $data1);
			}
		}
		if (empty($_POST['compo'])) {
		} else {
			$numb3 = 0;
			foreach ($_POST['compo'] as $c1) {
				$numb3++;
				$comp =  array(
					'id_category3' => $id,
					'id_compotition' => $c1['id_compotition'],
					'nilai_compotition' => $c1['jumlah_kandungan'],
					'deleted' => '0',
					'created_on' => date('Y-m-d H:i:s'),
					'created_by' => $this->auth->user_id(),
				);
				//Add Data
				$this->db->insert('child_inven_compotition', $comp);
			}
		}
		if (empty($_POST['dimens'])) {
		} else {
			$numb4 = 0;
			foreach ($_POST['dimens'] as $dm) {
				$numb4++;
				$dms =  array(
					'id_category3' => $id,
					'id_dimensi' => $dm['id_dimensi'],
					'nilai_dimensi' => $dm['nilai_dimensi'],
					'deleted' => '0',
					'created_on' => date('Y-m-d H:i:s'),
					'created_by' => $this->auth->user_id(),
				);
				//Add Data
				$this->db->insert('child_inven_dimensi', $dms);
			}
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
				'pesan'		=> 'Success Save Item. invenThanks ...',
				'status'	=> 1
			);
		}

		echo json_encode($status);
	}
	function get_compotition()
	{
		$inventory_2 = $_GET['inventory_2'];
		$comp = $this->Material_master_model->compotition($inventory_2);
		$numb = 0;
		// print_r($data);
		// exit();
		foreach ($comp as $key => $cmp) : $numb++;
			echo "<tr>
					  <td hidden align='left'>
					  <input type='text' name='compo[$numb][id_compotition]' readonly class='form-control'  value='" . $cmp->id_compotition . "'>
					  </td>
					  <td align='left'>
					  $cmp->name_compotition
					  </td>
					  <td align='left'>
					  <input type='text' name='compo[$numb][jumlah_kandungan]' class='form-control'>
					  </td>
					  <td align='left'>%</td>
                    </tr>";
		endforeach;
		echo "</select>";
	}



	public function update_material()
	{
		$sql = $this->db->query("SELECT * FROM ms_inventory_new")->result();



		// print_r($sql);
		// exit;

		$n = 0;

		foreach ($sql as $val => $valx) {

			$n++;




			$this->db->query("UPDATE ms_inventory_category33 
							SET nama='" . $valx->nama . "',maker='" . $valx->maker . "',density='" . $valx->density . "',hardness='" . $valx->hardness . "',thickness='" . $valx->thickness . "',spek='" . $valx->spek . "',alloy='" . $valx->alloy . "'
							WHERE id_category3='" . $valx->id_category3 . "'");

			echo "$n";

			//$this->db->where('id', $valx->id )->update("ms_inventory_category32",$data);

		}
	}

	public function addKompMaterial()
	{
		$material = $this->db->get_where('ms_inventory_category2', ['id_category2' => $this->input->post('kode_material')])->row();
		$inv_lv4 = $this->db->get_where('ms_inventory_category3', ['id_inventory3' => $this->input->post('material')])->row();

		$get_id = $this->db->query("SELECT MAX(id_komp_material) AS max_id FROM ms_inventory_category3_komp_material WHERE id_komp_material LIKE '%KML3-" . date('m-y') . "%'")->row();
		$kodeBarang = $get_id->max_id;
		$urutan = (int) substr($kodeBarang, 11, 5);
		$urutan++;
		$tahun = date('m-y');
		$huruf = "KML3-";
		$generate_id = $huruf . $tahun . sprintf("%06s", $urutan);

		$this->db->trans_begin();

		$this->db->insert('ms_inventory_category3_komp_supplier', [
			'id_komp_material' => $generate_id,
			'id_category3' => $this->auth->user_id(),
			'id_material' => $material->id_category2,
			'nm_material' => $material->nama,
			'id_category4' => $inv_lv4->id_category3,
			'nm_category4' => $inv_lv4->nama,
			'persen_penggunaan' => $this->input->post('persen_penggunaan'),
			'keterangan' => $this->input->post('keterangan'),
			'dibuat_oleh' => $this->auth->user_id(),
			'dibuat_tgl' => date("Y-m-d H:i:s")
		]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$hasil = '';

		$getData = $this->db->get_where('ms_inventory_category3_komp_material', ['id_category3' => $this->auth->user_id()])->result();
		foreach ($getData as $data) {
			$hasil = $hasil . '
				<tr>
					<td>
						<select name="" id="" class="form-control form-control-sm kode_material_' . $data->id_komp_material . '">
						';

			$get_inv_lv3 = $this->db->get_where('ms_inventory_category2')->result();
			foreach ($get_inv_lv3 as $inv_lv3) {
				$hasil = $hasil . '
						<option value="' . $inv_lv3->id_category2 . '" ' . ($data->id_material == $inv_lv3->id_category2) ? 'selected' : null . '>' . $inv_lv3->nama . '</option>
					';
			}

			$hasil = $hasil . '
						</select>
					</td>
					<td>
						<select name="" id="" class="form-control form-control-sm kode_material_' . $data->id_komp_material . '">
						';

			$get_inv_lv4 = $this->db->get_where('ms_inventory_category2')->result();
			foreach ($get_inv_lv4 as $inv_lv4) {
				$hasil = $hasil . '
						<option value="' . $inv_lv4->id_category3 . '" ' . ($data->id_category4 == $inv_lv4->id_category3) ? 'selected' : null . '>' . $inv_lv4->nama . '</option>
					';
			}

			$hasil = $hasil . '
						</select>
					</td>
					<td>
						<input type="number" name="" id="" class="form-control form-control-sm persen_penggunaan_' . $data->id_komp_material . '" value="' . $data->persen_penggunaan . '">
					</td>
				</tr>
			';
		}
	}

	public function gabung_nama()
	{
		$this->auth->restrict($this->addPermission);
		$inv_lv_1_id = $this->input->post('inv_lv_1');
		$inv_lv_2_id = $this->input->post('inv_lv_2');
		$inv_lv_3_id = $this->input->post('inv_lv_3');

		$get_inv_lv_1 = $this->db->get_where('ms_inventory_type', ['id_type' =>  $this->input->post('inv_lv_1')])->row();
		$get_inv_lv_2 = $this->db->get_where('ms_inventory_category1', ['id_category1' =>  $this->input->post('inv_lv_2')])->row();
		$get_inv_lv_3 = $this->db->get_where('ms_inventory_category2', ['id_category2' =>  $this->input->post('inv_lv_3')])->row();

		$gabung_nama = $get_inv_lv_1->nama . ' ' . $get_inv_lv_2->nama . '; ' . $get_inv_lv_3->nama;

		echo $gabung_nama;
	}

	function get_surface()
	{
		$idsurface = $_GET['idsurface'];
		$surface = $this->db->query("SELECT * FROM ms_surface WHERE id_surface='" . $idsurface . "'")->row();
		echo "$surface->nm_surface";
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
        $sql = "SELECT a.*, b.nama as nama_material_1, c.nama as nama_material_2, d.nama as nama_material_3, e.nm_packaging FROM ms_inventory_category3 a 
		LEFT JOIN ms_inventory_type b ON b.id_type = a.id_type
		LEFT JOIN ms_inventory_category1 c ON c.id_category1 = a.id_category1
		LEFT JOIN ms_inventory_category2 d ON d.id_category2 = a.id_category2
		LEFT JOIN master_packaging e ON e.id = a.packaging
		WHERE 
			1=1 AND
			(
				a.id_category3 LIKE '%".$string."%' OR 
				a.nama LIKE '%".$string."%' OR 
				b.nama LIKE '%".$string."%' OR 
				c.nama LIKE '%".$string."%' OR 
				d.nama LIKE '%".$string."%'
			)
		GROUP BY a.id_category3
		";

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

            $view         = '<a class="btn btn-primary btn-sm view" href="javascript:void(0)" title="View" data-id_inventory3="'.$row['id_category3'].'"><i class="fa fa-eye"></i>
			</a>';
            $edit         = '<a class="btn btn-success btn-sm edit" href="javascript:void(0)" title="Edit" data-id_inventory3="'.$row['id_category3'].'" data-id_type="'.$row['id_type'].'"><i class="fa fa-edit"></i>
			</a>';
            $delete     = '<a class="btn btn-danger btn-sm delete" href="javascript:void(0)" title="Delete" data-id_inventory3="'.$row['id_category3'].'"><i class="fa fa-trash"></i>
			</a>';
            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

			if($row['aktif'] == 'aktif'){
				$status = '<div class="badge badge-success">Aktif</div>';
			}else{
				$status = '<div class="badge badge-danger">Non Aktif</div>';
			}

            $nestedData   = array();
            $nestedData[]  = $row['id_category3'];
            $nestedData[]  = $row['nama_material_1'];
            $nestedData[]  = $row['nama_material_2'];
            $nestedData[]  = $row['nama_material_3'];
            $nestedData[]  = $row['nama'];
            $nestedData[]  = $status;
            // $nestedData[]  = $row['email'];
            // $nestedData[]  = $row['address'];
            // $nestedData[]  = $status[$row['status']];
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
