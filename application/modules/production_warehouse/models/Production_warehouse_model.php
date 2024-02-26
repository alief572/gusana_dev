<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Production_warehouse_model extends BF_Model
{
	/**
	 * @var string  User Table Name
	 */
	protected $table_name = 'ms_inventory_category3';
	protected $key        = 'id';

	/**
	 * @var string Field name to use for the created time column in the DB table
	 * if $set_created is enabled.
	 */
	protected $created_field = 'created_on';

	/**
	 * @var string Field name to use for the modified time column in the DB
	 * table if $set_modified is enabled.
	 */
	protected $modified_field = 'modified_on';

	/**
	 * @var bool Set the created time automatically on a new record (if true)
	 */
	protected $set_created = true;

	/**
	 * @var bool Set the modified time automatically on editing a record (if true)
	 */
	protected $set_modified = true;
	/**
	 * @var string The type of date/time field used for $created_field and $modified_field.
	 * Valid values are 'int', 'datetime', 'date'.
	 */
	/**
	 * @var bool Enable/Disable soft deletes.
	 * If false, the delete() method will perform a delete of that row.
	 * If true, the value in $deleted_field will be set to 1.
	 */
	protected $soft_deletes = true;

	protected $date_format = 'datetime';

	/**
	 * @var bool If true, will log user id in $created_by_field, $modified_by_field,
	 * and $deleted_by_field.
	 */
	protected $log_user = true;

	/**
	 * Function construct used to load some library, do some actions, etc.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	function generate_id($kode = '')
	{
		$id_bm1 = $this->db->query("SELECT MAX(id_category3) AS max_id FROM ms_inventory_category3")->row();
		$kodeBarang = $id_bm1->max_id;
		$urutan = (int) substr($kodeBarang, 3, 5);
		$urutan++;
		$tahun = date('y');
		$huruf = "I";
		$kodecollect = $huruf . $tahun . sprintf("%05s", $urutan);
		return $kodecollect;
	}

	function level_2($inventory_1)
	{
		$search = "deleted='0' and id_type='" . $inventory_1 . "'";
		$this->db->where($search);
		$this->db->order_by('id_category1', 'ASC');
		return $this->db->from('ms_inventory_category1')
			->get()
			->result();
	}
	function level_3($id_inventory2)
	{
		$search = "deleted='0' and id_category1='" . $id_inventory2 . "'";
		$this->db->where($search);
		$this->db->order_by('id_category2', 'ASC');
		return $this->db->from('ms_inventory_category2')
			->get()
			->result();
	}
	function compotition($id_inventory2)
	{
		$search = "deleted='0' and id_category1='" . $id_inventory2 . "'";
		$this->db->where($search);
		$this->db->order_by('id_compotition', 'ASC');
		return $this->db->from('ms_compotition')
			->get()
			->result();
	}
	function bentuk($id_bentuk)
	{
		$search = "deleted='0' and id_bentuk='" . $id_bentuk . "'";
		$this->db->where($search);
		$this->db->order_by('id_dimensi', 'ASC');
		return $this->db->from('ms_dimensi')
			->get()
			->result();
	}
	function level_4($id_inventory3)
	{
		$this->db->where('id_category2', $id_inventory3);
		$this->db->order_by('id_category3', 'ASC');
		return $this->db->from('ms_inventory_category3')
			->get()
			->result();
	}

	public function get_data($table, $where_field = '', $where_value = '')
	{
		if ($where_field !== '' && $where_value !== '' && $where_field !== null && $where_value !== null) {
			$query = $this->db->get_where($table, array($where_field => $where_value));
		} else {
			$query = $this->db->get($table);
		}

		return $query->result();
	}

	function getById($id)
	{
		return $this->db->get_where('inven_lvl2', array('id_inventory2' => $id))->row_array();
	}

	public function get_data_category3()
	{
		$search = "a.deleted='0' ";
		$this->db->select('a.*, b.nama as nama_material_1, c.nama as nama_material_2, d.nama as nama_material_3, e.supplier_name as nama_supplier');
		$this->db->from('ms_inventory_category3 a');
		$this->db->join('ms_inventory_type b', 'b.id_type = a.id_type', 'left');
		$this->db->join('ms_inventory_category1 c', 'c.id_category1 = a.id_category1', 'left');
		$this->db->join('ms_inventory_category2 d', 'd.id_category2 = a.id_category2', 'left');
		$this->db->join('master_supplier e', 'e.id = a.supplier_utama', 'left');
		$this->db->group_by('a.id_category3');
		$this->db->where($search);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_category3_where($id)
	{
		$this->db->select('a.*, b.nama as nama_material_1, c.nama as nama_material_2, d.nama as nama_material_3, e.supplier_name as nama_supplier,f.nm_packaging as nama_packaging, g.nm_kategori_finish_goods, h.nm_unit');
		$this->db->from('ms_inventory_category3 a');
		$this->db->join('ms_inventory_type b', 'b.id_type = a.id_type', 'left');
		$this->db->join('ms_inventory_category1 c', 'c.id_category1 = a.id_category1', 'left');
		$this->db->join('ms_inventory_category2 d', 'd.id_category2 = a.id_category2', 'left');
		$this->db->join('master_supplier e', 'e.id = a.supplier_utama', 'left');
		$this->db->join('master_packaging f', 'f.id = a.packaging', 'left');
		$this->db->join('kategori_finish_goods g', 'g.id_kategori_finish_goods = a.kategori_fg_id', 'left');
		$this->db->join('m_unit h', 'h.id_unit = a.unit', 'left');
		$this->db->where('a.id_category3', $id);
		$this->db->group_by('a.id_category3');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_data_category3_alt_suppliers($id)
	{
		$this->db->select('a.*, b.supplier_name, c.nama');
		$this->db->from('ms_inventory_category3_alt_supplier a');
		$this->db->join('master_supplier b', 'b.id = a.id_supplier', 'left');
		$this->db->join('ms_inventory_category3 c', 'c.id_category3 = a.id_category3', 'left');
		$this->db->where('a.id_category3', $id);
		$this->db->group_by('a.id_supplier');
		$query = $this->db->get();
		return $query->result();
	}

	public function dimensy($id)
	{
		$search = "a.id_category3='" . $id . "'";
		$this->db->select('a.*, b.nm_dimensi as nm_dimensi');
		$this->db->from('child_inven_dimensi a');
		$this->db->join('ms_dimensi b', 'b.id_dimensi=a.id_dimensi');
		$this->db->where($search);
		$query = $this->db->get();
		return $query->result();
	}

	public function supl($id)
	{
		$search = "a.id_category3='" . $id . "'";
		$this->db->select('a.*, b.name_suplier as name_suplier');
		$this->db->from('child_inven_suplier a');
		$this->db->join('master_supplier b', 'b.id_suplier=a.id_suplier');
		$this->db->where($search);
		$query = $this->db->get();
		return $query->result();
	}

	public function kompos($id)
	{
		$search = "a.deleted='0' and a.id_category3='" . $id . "'";
		$this->db->select('a.*, b.name_compotition as name_compotition');
		$this->db->from('child_inven_compotition a');
		$this->db->join('ms_compotition b', 'b.id_compotition=a.id_compotition');
		$this->db->where($search);
		$query = $this->db->get();
		return $query->result();
	}

	public function getview($id)
	{
		$this->db->select('a.*, b.nama as nama_type, c.nama as nama_category1, d.nama as nama_category2');
		$this->db->from('ms_inventory_category3 a');
		$this->db->join('ms_inventory_type b', 'b.id_type=a.id_type');
		$this->db->join('ms_inventory_category1 c', 'c.id_category1 =a.id_category1');
		$this->db->join('ms_inventory_category2 d', 'd.id_category2 =a.id_category2');
		$this->db->where('a.id_category3', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function getedit($id)
	{
		$this->db->select('a.*');
		$this->db->from('ms_inventory_category3 a');
		$this->db->where('a.id_category3', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_child_compotition($id)
	{
		$this->db->select('a.*, b.name_compotition as name_compotition');
		$this->db->from('dt_material_compotition a');
		$this->db->join('ms_material_compotition b', 'b.id_compotition=a.id_compotition');
		$this->db->where('a.id_category3', $id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_child_dimention($id)
	{
		$this->db->select('a.*, b.dimensi_bentuk as dimensi_bentuk');
		$this->db->from('dt_material_dimensi a');
		$this->db->join('child_dimensi_bentuk b', 'b.id_dimensi_bentuk=a.id_dimensi_bentuk');
		$this->db->where('a.id_category3', $id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_child_suplier($id)
	{
		$this->db->select('a.*, b.name_supplier as name_supplier');
		$this->db->from('dt_material_supplier a');
		$this->db->join('master_supplier b', 'b.id_supplier=a.id_supplier');
		$this->db->where('a.id_category3', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function getSpek($id)
	{
		$this->db->select('a.*, b.name_compotition as name_compotition');
		$this->db->from('dt_material_compotition a');
		$this->db->join('ms_material_compotition b', 'b.id_compotition = a.id_compotition');
		$this->db->where('a.id_category3', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_material_stock($id_category1 = null)
	{
		if ($id_category1 !== null) {
			$get_material_stock = $this->db->query("
			SELECT
				a.*,
				b.nama as category_nm,
				c.nm_packaging,
				d.nm_unit,
				e.konversi,
				e.qty_stock,
				(SUM(e.qty_stock) / e.konversi) AS stock_unit
			FROM
				ms_inventory_category2 a
				LEFT JOIN ms_inventory_category1 b ON b.id_category1 = a.id_category1
				LEFT JOIN ms_inventory_category3 f ON f.id_category2 = a.id_category2
				LEFT JOIN m_unit d ON d.id_unit = f.unit
				LEFT JOIN master_packaging c ON c.id = f.packaging
				LEFT JOIN ms_stock_material_production e ON e.id_category1 = b.id_category1
			WHERE
				1=1 AND a.deleted = '0' AND a.id_category1 = '" . $id_category1 . "'
			GROUP BY a.id_category2
		")->row();
		} else {
			$get_material_stock = $this->db->query("
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
				LEFT JOIN ms_stock_material_production e ON e.id_category1 = b.id_category1
			WHERE
				1=1 AND a.deleted = '0'
			GROUP BY a.id_category2
		")->result();
		}

		return $get_material_stock;
	}

	public function generate_id_detail_request_material()
	{
		$code_so = $this->db->query("SELECT MAX(id) as max_id FROM ms_request_material_detail WHERE id LIKE '%RMD-" . date('Y') . "-" . date('m') . "-" . date('d') . "%'")->row();
		$kodeBarang = $code_so->max_id;
		$urutan = (int) substr($kodeBarang, 15, 6);
		$urutan++;
		$tahun = date('Y-m-d-');
		$huruf = "RMD-";
		$kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

		return $kodecollect;
	}
	public function generate_id_request()
	{
		$code_so = $this->db->query("SELECT MAX(id) as max_id FROM ms_request_material WHERE id LIKE '%RM-" . date('Y') . "-" . date('m') . "-" . date('d') . "%'")->row();
		$kodeBarang = $code_so->max_id;
		$urutan = (int) substr($kodeBarang, 14, 6);
		$urutan++;
		$tahun = date('Y-m-d-');
		$huruf = "RM-";
		$kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

		return $kodecollect;
	}

	public function get_request_material()
	{
		$get_data = $this->db->query('
			SELECT 
				a.*, 
				b.warehouse_nm,	
				SUM(c.request_qty) AS qty_packing,
				d.full_name AS by_acc
			FROM
				ms_request_material a
				LEFT JOIN m_warehouse b ON b.id = a.id_gudang_from
				LEFT JOIN ms_request_material_detail c ON c.id_request = a.id
				LEFT JOIN users d ON d.id_user = a.dibuat_oleh
			GROUP BY a.id
		')->result();

		return $get_data;
	}
}
