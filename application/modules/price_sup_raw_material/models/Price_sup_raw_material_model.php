<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Price_sup_raw_material_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
    function generate_id() {
        $id_bm1 = $this->db->query("SELECT MAX(id_category3) AS max_id FROM ms_product_category3")->row();
		$kodeBarang = $id_bm1->max_id;
		$urutan = (int) substr($kodeBarang, 3, 5);
		$urutan++;
		$tahun = date('y');
		$huruf = "P";
		$kodecollect = $huruf . $tahun . sprintf("%05s", $urutan);
		return $kodecollect;
	}

 	public function get_data($array_where = ''){
		if(!empty($array_where)){
			$query = $this->db->get_where('ms_inventory_category3', $array_where);
		}
        else{
			$query = $this->db->get('ms_inventory_category3');
		}
		
		return $query->result();
	}
	
    function getById($id)
    {
       return $this->db->get_where('ms_inventory_category3',['id_category3' => $id])->row_array();
    }

}
