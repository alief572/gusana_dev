<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author HIkmat Aolia
 * @copyright Copyright (c) 2023
 *
 * This is model class for table "Companies"
 */

class Companies_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'companies';
    protected $key        = 'id';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_at';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'modified_at';

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
        $y = date('y');
        $count = 1;
        $maxID = $this->db->select("MAX(RIGHT(id,5)) as id")->from('companies')->where(['SUBSTR(id,3,2)' => date('y')])->get()->row()->id;
        if ($maxID || $maxID > 0) {
            $count = $maxID + 1;
        }
        $newID = "CO$y" . "-" . str_pad($count, 5, "0", STR_PAD_LEFT);
        return $newID;
    }

    public function get_data($table, $where_field = '', $where_value = '', $order_field = '', $order = '')
    {
        if ($where_field != '' && $where_value != '' && $order_field != '' && $order != '') {
            $query = $this->db->order_by($order_field, $order)->get_where($table, array($where_field => $where_value));
        } else if ($where_field != '' && $where_value != '') {
            $query = $this->db->get_where($table, array($where_field => $where_value));
        } else {
            $query = $this->db->get($table);
        }

        return $query->result();
    }
}
