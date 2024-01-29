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
        $do = $this->db->query("SELECT MAX(id_do) AS max_id FROM ms_penawaran WHERE id_do LIKE '%DO-" . date('Ymd') . "-%'")->row();
        $kodeBarang = $do->max_id;
        $urutan = (int) substr($kodeBarang, 13, 5);
        $urutan++;
        $tahun = date('Ymd');
        $huruf = "DO-";
        $kodecollect = $huruf . $tahun . '-' . sprintf("%05s", $urutan);
        return $kodecollect;
    }
}
