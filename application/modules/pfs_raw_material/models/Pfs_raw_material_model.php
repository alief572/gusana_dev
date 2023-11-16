<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Pfs_raw_material_model extends BF_Model
{
    public function get_inven_4($id){
        $getData = $this->db->get_where('ms_inventory_category3',['id_category3' => $id])->row();

        return $getData;
    }

    public function generate_id_log(){
        $generate_id = $this->db->query("SELECT MAX(id) AS max_id FROM ms_price_ref_log WHERE id LIKE '%LOG-" . date('m-y') . "%'")->row();
        $kodeBarang = $generate_id->max_id;
        $urutan = (int) substr($kodeBarang, 10, 5);
        $urutan++;
        $tahun = date('m-y');
        $huruf = "LOG-";
        $kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

        return $kodecollect;
    }
}
