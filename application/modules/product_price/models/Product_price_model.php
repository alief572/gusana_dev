<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Product_price_model extends BF_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data($table, $where_field = '', $where_value = '')
	{
		if ($where_field != '' && $where_value != '') {
			$query = $this->db->get_where($table, array($where_field => $where_value));
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_data_where_array($table = null, $where = null)
	{
		if (!empty($where)) {
			$query = $this->db->get_where($table, $where);
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_data_group($table, $where_field = '', $where_value = '', $where_group = '')
	{
		if ($where_field != '' && $where_value != '') {
			$query = $this->db->group_by($where_group)->get_where($table, array($where_field => $where_value));
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	public function get_json_product_price()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		// $Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_product_price(
			$requestData['search']['value'],
			$requestData['order'][0]['column'],
			$requestData['order'][0]['dir'],
			$requestData['start'],
			$requestData['length']
		);
		$totalData			= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query					= $fetch['query'];

		$data	= array();
		$urut1  = 1;
		$urut2  = 0;
		foreach ($query->result_array() as $row) {
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if ($asc_desc == 'asc') {
				$nomor = $urut1 + $start_dari;
			}
			if ($asc_desc == 'desc') {
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>" . $nomor . "</div>";
			// $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nama_level1']))."</div>";
			$nestedData[]	= "<div align='left'>" . strtoupper(strtolower($row['product_master'])) . "</div>";

			$nestedData[]	= "<div align='right'>" . number_format($row['berat_material'], 4) . " Kg</div>";
			$nestedData[]	= "<div align='right'>" . number_format($row['price_material'], 2) . "</div>";
			$nestedData[]	= "<div align='right'>" . number_format($row['price_man_power'], 2) . "</div>";
			$nestedData[]	= "<div align='right'>" . number_format($row['price_total'], 2) . "</div>";


			$edit	= "";
			$delete	= "";
			$excel	= "";

			$edit	= "<a href='" . site_url($this->uri->segment(1)) . '/detail_costing/' . $row['no_bom'] . "' class='btn btn-sm btn-warning' title='Detail' data-role='qtip'><i class='fa fa-eye'></i></a>";
			// $edit	= "&nbsp;<a href='".site_url($this->uri->segment(1)).'/add/'.$row['no_bom']."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
			// $delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete work data' data-no_bom='".$row['no_bom']."'><i class='fa fa-trash'></i></button>";
			// $excel	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/excel_report_all_bom_detail/'.$row['no_bom'])."' class='btn btn-sm btn-success' target='_blank' title='Excel' data-role='qtip'><i class='fa fa-file-excel-o'></i></a>";

			$nestedData[]	= "	<div align='center'>
								" . $edit . "
								" . $excel . "
								" . $delete . "
								</div>";
			$data[] = $nestedData;
			$urut1++;
			$urut2++;
		}

		$json_data = array(
			"draw"            	=> intval($requestData['draw']),
			"recordsTotal"    	=> intval($totalData),
			"recordsFiltered" 	=> intval($totalFiltered),
			"data"            	=> $data
		);

		echo json_encode($json_data);
	}

	public function get_query_json_product_price($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{

		$sql = "SELECT
					(@row:=@row+1) AS nomor,
					a.*,
					b.nama AS nama_level4
				FROM
					product_price a 
					LEFT JOIN ms_inventory_category3 b ON a.code_lv4 = b.id_category3
				WHERE 1=1 AND
					(
						a.no_bom LIKE '%" . $this->db->escape_like_str($like_value) . "%'
						OR b.nama LIKE '%" . $this->db->escape_like_str($like_value) . "%'
					)
				GROUP BY a.code_lv4
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'b.nama',
			2 => 'c.nama'
		);

		$sql .= " ORDER BY a.no_bom DESC,  " . $columns_order_by[$column_order] . " " . $column_dir . " ";
		$sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}
}
