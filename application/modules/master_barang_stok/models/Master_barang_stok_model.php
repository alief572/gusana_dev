<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Master_barang_stok_model extends BF_Model
{
    public function generate_id()
    {
        $get_id = $this->db->query("SELECT MAX(id_barang_stok) AS max_id FROM ms_barang_stok WHERE id_barang_stok LIKE '%BST-" . date('ym') . "%'")->row();
        $kodeBarang = $get_id->max_id;
        $urutan = (int) substr($kodeBarang, 10, 6);
        $urutan++;
        $tahun = date('ym');
        $huruf = "BST-";
        $id = $huruf . $tahun . sprintf("%06s", $urutan);

        return $id;
    }

    public function get_kategori_stok_all()
    {
        $getData = $this->db->get('ms_kategori_stok')->result();

        return $getData;
    }

    public function get_common($table)
    {
        $getData = $this->db->get($table)->result();

        return $getData;
    }

    public function get_common_by_id($id, $table_id, $table)
    {
        $getData = $this->db->get_where($table, [$table_id => $id])->row();

        return $getData;
    }
}
