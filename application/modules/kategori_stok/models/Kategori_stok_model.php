<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Kategori_stok_model extends BF_Model
{
    public function generate_id()
    {
        $get_id = $this->db->query("SELECT MAX(id_kategori_stok) AS max_id FROM ms_kategori_stok WHERE id_kategori_stok LIKE '%KST-" . date('ym') . "%'")->row();
        $kodeBarang = $get_id->max_id;
        $urutan = (int) substr($kodeBarang, 10, 6);
        $urutan++;
        $tahun = date('ym');
        $huruf = "KST-";
        $id = $huruf . $tahun . sprintf("%06s", $urutan);

        return $id;
    }

    public function get_data_kategori_stok($id)
    {
        $getData = $this->db->get_where('ms_kategori_stok', ['id_kategori_stok' => $id])->row();

        return $getData;
    }
}
