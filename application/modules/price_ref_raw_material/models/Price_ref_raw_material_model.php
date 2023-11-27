<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Price_ref_raw_material_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
    // function generate_id() {
    //     $kode 		    = 'M4'.date('y');
    //     $Query			= "SELECT MAX(id_category3) as maxP FROM ms_inventory_category3 WHERE id_category3 LIKE '".$kode."%' ";
    //     $resultIPP		= $this->db->query($Query)->result_array();
    //     $angkaUrut2		= $resultIPP[0]['maxP'];
    //     $urutan2		= (int)substr($angkaUrut2, 4, 6);
    //     $urutan2++;
    //     $urut2			= sprintf('%06s',$urutan2);
    //     $kode_id	    = $kode.$urut2;
    //     return $kode_id;
	// }

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
       return $this->db->get_where('ms_inventory_category3',array('id_category3' => $id))->row_array();
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
