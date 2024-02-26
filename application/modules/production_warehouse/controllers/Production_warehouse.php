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

class Production_warehouse extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Production_Warehouse.View';
	protected $addPermission  	= 'Production_Warehouse.Add';
	protected $managePermission = 'Production_Warehouse.Manage';
	protected $deletePermission = 'Production_Warehouse.Delete';

	protected $viewPermission_request 	= 'Request_Material.View';
	protected $addPermission_request  	= 'Request_Material.Add';
	protected $managePermission_request = 'Request_Material.Manage';
	protected $deletePermission_request = 'Request_Material.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('Mpdf', 'Image_lib', 'input', 'user_agent', 'uri'));
		$this->load->library('upload');
		$this->load->helper(['form', 'url', 'json']);

		$this->load->model(array(
			'Production_warehouse/Production_warehouse_model',
			'Warehouse_material/Warehouse_material_model',
			'Aktifitas/aktifitas_model',
		));
		$this->template->title('Manage Data Supplier');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	public function index()
	{
		$id_bentuk = $this->uri->segment(3);
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Production_warehouse_model->get_material_stock();
		$this->template->set('results', [
			'list_material_stock' => $data
		]);
		$this->template->title('Production Warehouse Stock');
		$this->template->render('list');
	}

	public function request()
	{
		$this->auth->restrict($this->viewPermission_request);
		$session = $this->session->userdata('app_session');

		$this->template->title('Request Material');
		$this->template->render('request');
	}

	public function add_request()
	{
		$id_bentuk = $this->uri->segment(3);
		$this->auth->restrict($this->viewPermission_request);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$deleted = '0';
		$data = $this->Production_warehouse_model->get_material_stock();
		$get_warehouse_material = $this->db->get_where('m_warehouse', ['warehouse_type' => '1'])->result();
		$get_warehouse_production = $this->db->get_where('m_warehouse', ['warehouse_type' => '2'])->result();
		$this->template->set('results', [
			'list_material_stock' => $data,
			'list_warehouse_material' => $get_warehouse_material,
			'list_warehouse_production' => $get_warehouse_production
		]);
		$this->template->title('Add Request Material');
		$this->template->render('request_modal');
	}

	public function add_material()
	{
		$data = $this->db->query("
		SELECT
			a.*,
			b.nama as category_nm,
			c.nm_packaging,
			d.nm_unit,
			f.konversi,
			(SUM(e.qty_stock) / f.konversi) AS stock_unit
		FROM
			ms_inventory_category2 a
			LEFT JOIN ms_inventory_category1 b ON b.id_category1 = a.id_category1
			LEFT JOIN ms_inventory_category3 f ON f.id_category2 = a.id_category2
			LEFT JOIN m_unit d ON d.id_unit = f.unit
			LEFT JOIN master_packaging c ON c.id = f.packaging
			LEFT JOIN ms_stock_material e ON e.id_category1 = b.id_category1
		WHERE
			1=1 AND a.deleted = '0' AND (SELECT COUNT(aa.id) FROM ms_request_material_detail aa WHERE aa.id_request = '" . $this->input->post('id_request') . "' AND aa.id_category1 = a.id_category1) <= 0
		GROUP BY a.id_category2
		")->result();

		$this->template->set('results', [
			'list_material_stock' => $data,
			'id_request' => $this->input->post('id_request')
		]);
		$this->template->title('Request Material');
		$this->template->render('list_material');
	}

	public function save_material()
	{
		$post = $this->input->post();

		$id_request = $post['id_request'];
		$id_category1 = $post['id_category1'];
		$request_qty = $post['request_qty'];
		$keterangan = $post['keterangan'];

		$get_category2 = $this->db->get_where('ms_inventory_category2', ['id_category1' => $id_category1])->row();

		$this->db->trans_begin();

		$this->db->insert('ms_request_material_detail', [
			'id' => $this->Production_warehouse_model->generate_id_detail_request_material(),
			'id_request' => $id_request,
			'id_category1' => $id_category1,
			'id_category2' => $get_category2->id_category2,
			'request_qty' => $request_qty,
			'keterangan' => $keterangan,
			'dibuat_oleh' => $this->auth->user_id(),
			'dibuat_tgl' => date('Y-m-d H:i:s')
		]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	public function refresh_detail_table()
	{
		$post = $this->input->post();

		$id_request = $post['id_request'];

		$hasil = '';

		$get_detail = $this->db->query('
			SELECT 
				a.*, 
				b.nama AS category_nm, 
				c.nama as nm_material, 
				e.nm_packaging 
			FROM 
				ms_request_material_detail a 
				LEFT JOIN ms_inventory_category1 b ON b.id_category1 = a.id_category1 
				LEFT JOIN ms_inventory_category2 c ON c.id_category2 = a.id_category2 
				LEFT JOIN ms_inventory_category3 d ON d.id_category2 = a.id_category2
				LEFT JOIN master_packaging e ON e.id = d.packaging
			WHERE
				a.id_request = "' . $id_request . '"
			')->result();

		$no = 1;
		foreach ($get_detail as $detail) :
			$hasil = $hasil . '
				<tr>
					<td class="text-center">' . $no . '</td>
					<td class="text-center">' . $detail->category_nm . '</td>
					<td class="text-center">' . $detail->nm_material . '</td>
					<td class="text-center">' . $detail->nm_packaging . '</td>
					<td class="text-center">' . number_format($detail->request_qty) . '</td>
					<td class="text-center">' . $detail->keterangan . '</td>
					<td class="text-center">
						<button type="button" class="btn btn-sm btn-danger del_req_material del_req_material_' . $detail->id . '" data-id="' . $detail->id . '"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
			';

			$no++;
		endforeach;

		echo json_encode([
			'hasil' => $hasil
		]);
	}

	public function del_material()
	{
		$post = $this->input->post();

		$id = $post['id'];

		$this->db->trans_begin();

		$this->db->delete('ms_request_material_detail', ['id' => $id]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
	}

	public function save()
	{
		$post = $this->input->post();

		$id_request = $post['id_request'];
		if ($id_request == $this->auth->user_id()) {
			$id_request = $this->Production_warehouse_model->generate_id_request();
		}

		// print_r($post);
		// exit;

		$this->db->trans_begin();

		if ($post['type_post'] == 'INSERT') {
			$this->db->insert('ms_request_material', [
				'id' => $id_request,
				'id_gudang_from' => $post['request_dari'],
				'id_gudang_to' => $post['request_ke'],
				'tgl_request' => date('Y-m-d'),
				'keterangan' => $post['keterangan'],
				'dibuat_oleh' => $this->auth->user_id(),
				'dibuat_tgl' => date('Y-m-d H:i:s')
			]);

			$this->db->update('ms_request_material_detail', ['id_request' => $id_request], ['id_request' => $this->auth->user_id()]);
		} else {
			$this->db->update('ms_request_material', [
				'id_gudang_from' => $post['request_dari'],
				'id_gudang_to' => $post['request_ke'],
				'tgl_request' => date('Y-m-d'),
				'keterangan' => $post['keterangan'],
				'diubah_oleh' => $this->auth->user_id(),
				'diubah_tgl' => date('Y-m-d H:i:s')
			], [
				'id' => $id_request
			]);
		}

		if ($this->db->trans_status() === FALSE) {
			$valid = 0;
			$this->db->trans_rollback();
		} else {
			$valid = 1;
			$this->db->trans_commit();
		}

		echo json_encode([
			'status' => $valid
		]);
	}

	public function view()
	{
		$post = $this->input->post();

		$this->db->select('a.*, b.warehouse_nm AS nm_wr_from, c.warehouse_nm AS nm_wr_to');
		$this->db->from('ms_request_material a');
		$this->db->join('m_warehouse b', 'b.id = a.id_gudang_from', 'left');
		$this->db->join('m_warehouse c', 'c.id = a.id_gudang_to', 'left');
		$this->db->where('a.id', $post['id']);
		$data_request = $this->db->get()->row();

		// print_r($data_request);
		// exit;

		$this->db->select('a.*, b.nama AS category_nm, c.nama, e.nm_packaging');
		$this->db->from('ms_request_material_detail a');
		$this->db->join('ms_inventory_category1 b', 'b.id_category1 = a.id_category1', 'left');
		$this->db->join('ms_inventory_category2 c', 'c.id_category2 = a.id_category2', 'left');
		$this->db->join('ms_inventory_category3 d', 'd.id_category2 = a.id_category2', 'left');
		$this->db->join('master_packaging e', 'e.id = d.packaging', 'left');
		$this->db->where('a.id_request', $post['id']);
		$data_request_detail = $this->db->get()->result();

		// $this->template->title('Request Material');
		$this->template->set('results', [
			'data_request' => $data_request,
			'data_request_detail' => $data_request_detail
		]);
		$this->template->render('view_request');
	}

	public function getData_request()
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
		$sql = "
		SELECT 
			a.*, 
			b.warehouse_nm,	
			(SELECT SUM(aa.request_qty) FROM ms_request_material_detail aa WHERE aa.id_request = a.id) AS qty_packing,
			d.full_name AS by_acc
		FROM
			ms_request_material a
			LEFT JOIN m_warehouse b ON b.id = a.id_gudang_from
			LEFT JOIN users d ON d.id_user = a.dibuat_oleh
		WHERE
			1=1 AND (
				a.id LIKE '%" . $string . "%' OR
				b.warehouse_nm LIKE '%" . $string . "%' OR
				(SELECT SUM(aa.request_qty) FROM ms_request_material_detail aa WHERE aa.id_request = a.id) LIKE '%" . $string . "%' OR
				d.full_name LIKE '%" . $string . "%' OR
				DATE_FORMAT(a.tgl_request, '%d %F %Y') LIKE '%" . $string . "%'
			)
		GROUP BY a.id
		";

		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'id',
			1 => 'id'
		);

		$sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
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

			$sts = '<div class="badge badge-warning text-light">Open</div>';
			if ($row['status'] == '1') {
				$sts = '<div class="badge badge-success">Closed</div>';
			}
			if ($row['status'] == '2') {
				$sts = '<div class="badge badge-danger">Rejected</div>';
			}

			$view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
			$buttons     = $view;

			$nestedData   = array();
			$nestedData[]  = $nomor;
			$nestedData[]  = $row['id'];
			$nestedData[]  = $row['warehouse_nm'];
			$nestedData[]  = number_format($row['qty_packing']);
			$nestedData[]  = $row['by_acc'];
			$nestedData[]  = date('d F Y', strtotime($row['tgl_request']));
			$nestedData[]  = $sts;
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

	public function history_material()
	{
		$id_category1 = $this->input->post('id_category1');

		$get_data_stock_material = $this->Warehouse_material_model->get_material_stock($id_category1);

		$stock_awal = 0;

		$get_stock_awal_pr = $this->db->query('SELECT SUM(a.qty) AS stock_awal_pr FROM ms_pr_material_detail a JOIN ms_pr_material b ON b.id = a.id_pr WHERE a.id_category1 = "' . $id_category1 . '" AND b.tgl < "' . date('Y-m-d') . '" AND b.sts = "1"')->row();

		$get_stock_awal_outgoing = $this->db->query('SELECT SUM(a.request_qty) AS stock_awal_outgoing FROM ms_request_material_detail a JOIN ms_request_material b ON b.id = a.id_request WHERE a.id_category1 = "' . $id_category1 . '" AND b.tgl_request < "' . date('Y-m-d') . '" AND b.status = "1" ')->row();

		$stock_awal = ($get_stock_awal_pr->stock_awal_pr - $get_stock_awal_outgoing->stock_awal_outgoing);
		// print_r($get_stock_awal_pr->stock_awal_pr . ' - ' . $get_stock_awal_outgoing->stock_awal_outgoing);
		// exit;

		$get_history = $this->db->query("

        select
        	'Outgoing' as xx,
            a.tgl_request AS history_date,
            b.full_name AS nm_by,
            e.warehouse_nm AS dari_gudang,
            c.warehouse_nm AS ke_gudang,
            '0' AS qty_up,
			f.konversi,
            SUM(d.request_qty) AS qty_down,
            a.id AS no_transaksi,
            a.keterangan AS keterangan,
            d.id_category1 AS id_category1,
            a.status AS status
        FROM
            ms_request_material a
            LEFT JOIN users b ON b.id_user = a.dibuat_oleh
            LEFT JOIN m_warehouse c ON c.id = a.id_gudang_to
            LEFT JOIN ms_request_material_detail d ON d.id_request = a.id
            LEFT JOIN m_warehouse e ON e.id = a.id_gudang_from
			LEFT JOIN ms_inventory_category3 f ON f.id_category1 = d.id_category1
        WHERE
             d.id_category1 = '" . $id_category1 . "' AND a.status = '1' AND a.id IS NOT NULL
         group by no_transaksi
        
		")->result();

		// print_r("");
		// exit;

		$this->template->set('results', [
			'data_stock_material' => $get_data_stock_material,
			'stock_awal' => $stock_awal,
			'list_history' => $get_history
		]);
		// print_r($get_history);
		// exit;
		$this->template->render('view');
	}
}
