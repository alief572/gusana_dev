<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Price_ref_barang_stok_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data($array_where = '')
    {
        if (!empty($array_where)) {
            $query = $this->db->get_where('ms_barang_stok', $array_where);
        } else {
            $query = $this->db->get('ms_barang_stok');
        }

        return $query->result();
    }

    function getById($id)
    {
        return $this->db->get_where('ms_barang_stok', array('id_barang_stok' => $id))->row_array();
    }
}
