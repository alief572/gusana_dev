<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Bom_model extends BF_Model
{
    public function generate_id()
    {
        $generate_id = $this->db->query("SELECT MAX(id) AS max_id FROM ms_bom WHERE id LIKE '%PRO1-" . date('m-y') . "%'")->row();
        $kodeBarang = $generate_id->max_id;
        $urutan = (int) substr($kodeBarang, 11, 5);
        $urutan++;
        $tahun = date('m-y');
        $huruf = "PRO1-";
        $kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

        return $kodecollect;
    }

    public function generate_id_detail()
    {
        $generate_id = $this->db->query("SELECT MAX(id) AS max_id FROM ms_bom_detail_material WHERE id LIKE '%PRO2-" . date('m-y') . "%'")->row();
        $kodeBarang = $generate_id->max_id;
        $urutan = (int) substr($kodeBarang, 11, 5);
        $urutan++;
        $tahun = date('m-y');
        $huruf = "PRO2-";
        $kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

        return $kodecollect;
    }

    public function get_material_type()
    {
        $getData = $this->db->get('ms_inventory_type')->result();
        return $getData;
    }

    public function get_material_category()
    {
        $getData = $this->db->get_where('ms_inventory_category1', ['deleted' => 0])->result();
        return $getData;
    }

    public function get_product_master()
    {
        $getData = $this->db->get('ms_product_category3')->result();
        return $getData;
    }

    public function get_product_master_by_id($id)
    {
        $getData = $this->db->get_where('ms_product_category3', ['id_category3' => $id])->row();
        return $getData;
    }

    public function get_product_jenis()
    {
        $getData = $this->db->get('ms_product_category2')->result();
        return $getData;
    }

    public function get_product_category()
    {
        $getData = $this->db->get('ms_product_category1')->result();
        return $getData;
    }

    public function get_material_detail_by_id($id)
    {
        $getData = $this->db->query("
            SELECT
                a.*,
                b.nm_proses,
                c.nama as material_category
            FROM
                ms_bom_detail_material a
                LEFT JOIN m_proses b ON b.id_proses = a.id_proses
                LEFT JOIN ms_inventory_category1 c ON c.id_category1 = a.id_category1
            WHERE
                a.id_bom = '" . $id . "'
            ORDER BY a.id ASC
        ")->result();
        return $getData;
    }

    public function get_bom_by_id($id)
    {
        $getData = $this->db->query("
            SELECT 
                a.*,
                b.nama as nm_product_master
            FROM
                ms_bom a 
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
            WHERE
                a.id = '" . $id . "'
        ")->row();

        return $getData;
    }

    public function get_proses()
    {
        $getData = $this->db->get('m_proses')->result();

        return $getData;
    }
}
