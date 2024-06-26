<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Product_category_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'ms_product_category1';
    protected $key        = 'id';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_on';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'modified_on';

    /**
     * @var bool Set the created time automatically on a new record (if true)
     */
    protected $set_created = true;

    /**
     * @var bool Set the modified time automatically on editing a record (if true)
     */
    protected $set_modified = true;
    /**
     * @var string The type of date/time field used for $created_field and $modified_field.
     * Valid values are 'int', 'datetime', 'date'.
     */
    /**
     * @var bool Enable/Disable soft deletes.
     * If false, the delete() method will perform a delete of that row.
     * If true, the value in $deleted_field will be set to 1.
     */
    protected $soft_deletes = true;

    protected $date_format = 'datetime';

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = true;

    /**
     * Function construct used to load some library, do some actions, etc.
     */
    public function __construct()
    {
        parent::__construct();
    }


    function generate_id($kode = '')
    {
        $id_bm1 = $this->db->query("SELECT MAX(id_category1) AS max_id FROM ms_product_category1")->result_array();
        $kodeBarang = $id_bm1[0]['max_id'];
        $urutan = (int) substr($kodeBarang, 5, 5);
        $urutan++;
        $tahun = date('y');
        $bulan = date('m');
        $huruf = "P";
        $kodecollect = $huruf . $tahun . $bulan . sprintf("%05s", $urutan);

        return $kodecollect;
    }

    public function get_data($table, $where_field = '', $where_value = '')
    {
        if ($where_field != '' && $where_value != '') {
            $query = $this->db->get_where($table, array($where_field => $where_value));
        } else {
            $query = $this->db->get($table);
        }

        return $query->result();
    }


    public function get_data_category1()
    {
        $this->db->select('a.*, b.nama as nama_type');
        $this->db->from('ms_product_category1 a');
        $this->db->join('ms_product_type b', 'b.id_type=a.id_type');
        $query = $this->db->get();
        return $query->result();
    }
    public function getKomposisi($id)
    {
        $search = "deleted='0' and id_category1='$id'";
        $this->db->select('*');
        $this->db->from('ms_compotition');
        $this->db->where($search);
        $query = $this->db->get();
        return $query->result();
    }

    function getById($id)
    {
        return $this->db->get_where('ms_product_category1', array('id_category1' => $id))->row_array();
    }

    public function get_material_category_type_by_id($id)
    {
        $this->db->select('a.*,b.nama as nama_type');
        $this->db->from('ms_product_category1 a');
        $this->db->join('ms_product_type b', 'b.id_type = a.id_type', 'left');
        $this->db->where('a.id_category1', $id);
        $get_data = $this->db->get();

        return $get_data->row();
    }
}
