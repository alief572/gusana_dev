<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Price_sup_barang_stok_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
 	public function get_data($array_where = ''){
		if(!empty($array_where)){
			$query = $this->db->get_where('ms_barang_stok', $array_where);
		}
        else{
			$query = $this->db->get('ms_barang_stok');
		}
		
		return $query->result();
	}
	
    function getById($id)
    {
       return $this->db->get_where('ms_barang_stok',array('id_barang_stok' => $id))->row_array();
    }

    public function get_kurs_usd(){
		$get_kurs_usd = $this->db->query("SELECT * FROM ms_kurs WHERE curr_to_idr = 'USD' AND tgl_periode_akhir >= '".date("Y-m-d")."' ORDER BY tgl_periode_awal DESC LIMIT 1 ")->row();
		$kurs_usd = $get_kurs_usd->kurs;
		return $kurs_usd;
	}

	public function get_kurs_rmb(){
		$get_kurs_rmb = $this->db->query("SELECT * FROM ms_kurs WHERE curr_to_idr = 'RMB' AND tgl_periode_akhir >= '".date("Y-m-d")."' ORDER BY tgl_periode_awal DESC LIMIT 1")->row();
		$kurs_rmb = $get_kurs_rmb->kurs;
		return $kurs_rmb;
	}

}
