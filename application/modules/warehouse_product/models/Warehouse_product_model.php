<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Warehouse_product_model extends BF_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function get_product_stock($id_category3 = null)
  {
    if ($id_category3 !== null) {
      $get_material_stock = $this->db->query("
			SELECT
				a.*,
				b.nama as category_nm,
				c.nm_packaging,
				d.nm_unit,
				(SUM(e.qty_asli) / a.konversi) AS stock_unit
			FROM
				ms_product_category3 a
				LEFT JOIN ms_product_category1 b ON b.id_category1 = a.id_category1
				LEFT JOIN m_unit d ON d.id_unit = a.unit_id
				LEFT JOIN master_packaging c ON c.id = a.packaging
				LEFT JOIN ms_stock_product e ON e.id_product = a.id_category3
			WHERE
				1=1 AND a.id_category3 = '" . $id_category3 . "'
			GROUP BY a.id_category3
		")->row();
    } else {
      $get_material_stock = $this->db->query("
			SELECT
				a.*,
				b.nama as category_nm,
				c.nm_packaging,
				d.nm_unit,
				(SUM(e.qty_asli) / a.konversi) AS stock_unit
			FROM
				ms_product_category3 a
				LEFT JOIN ms_product_category1 b ON b.id_category1 = a.id_category1
				LEFT JOIN m_unit d ON d.id_unit = a.unit_id
				LEFT JOIN master_packaging c ON c.id = a.packaging
				LEFT JOIN ms_stock_product e ON e.id_product = a.id_category3
			WHERE
				1=1
			GROUP BY a.id_category3
		")->result();
    }

    return $get_material_stock;
  }
}
