<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Delivery_do_model extends BF_Model
{
    public function generate_id()
    {
        $do = $this->db->query("SELECT MAX(id) AS max_id FROM ms_detail_do WHERE id LIKE '%DD-" . date('Ymd') . "-%'")->row();
        $kodeBarang = $do->max_id;
        $urutan = (int) substr($kodeBarang, 13, 5);
        $urutan++;
        $tahun = date('Ymd');
        $huruf = "DD-";
        $kodecollect = $huruf . $tahun . '-' . sprintf("%05s", $urutan);
        return $kodecollect;
    }

    public function generate_id_print_do()
    {
        $do = $this->db->query("SELECT MAX(id_print_do) AS max_id FROM ms_detail_do WHERE id_print_do LIKE '%PDO-" . date('Ymd') . "-%'")->row();
        $kodeBarang = $do->max_id;
        $urutan = (int) substr($kodeBarang, 14, 5);
        $urutan++;
        $tahun = date('Ymd');
        $huruf = "PDO-";
        $kodecollect = $huruf . $tahun . '-' . sprintf("%05s", $urutan);
        return $kodecollect;
    }
}
