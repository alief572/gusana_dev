<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class List_so_do_model extends BF_Model
{
    function generate_id($kode = '')
    {
        $ppb = $this->db->query("SELECT MAX(id_ppb) AS max_id FROM ms_penawaran WHERE id_ppb LIKE '%PPB-" . date('Ymd') . "-%'")->row();
        $kodeBarang = $ppb->max_id;
        $urutan = (int) substr($kodeBarang, 13, 5);
        $urutan++;
        $tahun = date('Ymd');
        $huruf = "PPB-";
        $kodecollect = $huruf . $tahun . '-' . sprintf("%05s", $urutan);
        return $kodecollect;
    }
}
