<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Goods_release_order_model extends BF_Model
{
    function generate_id($kode = '')
    {
        $do = $this->db->query("SELECT MAX(id_do) AS max_id FROM ms_penawaran WHERE id_do LIKE '%GS" . date('Ym') . "%'")->row();
        $kodeBarang = $do->max_id;
        $urutan = (int) substr($kodeBarang, 8, 5);
        $urutan++;
        $tahun = date('Ym');
        $huruf = "GS";
        $kodecollect = $huruf . $tahun . sprintf("%03s", $urutan);
        return $kodecollect;
    }
}
