<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Hikmat Aolia
 * @copyright Copyright (c) 2023, Hikmat Aolia
 *
 * This is model class for table "Supliers_model"
 */

class Suppliers_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'master_supplier';
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

    function generate_id($code = '')
    {
        $y = date('y');
        $count = 1;
        $maxID = $this->db->select("MAX(RIGHT(id,5)) as id")->from('master_supplier')->where(['SUBSTR(id,4,2)' => $y])->get()->row()->id;
        if ($maxID) {
            $count = $maxID + 1;
        }
        $newID = "SUP$y-" . str_pad($count, 5, "0", STR_PAD_LEFT);
        return $newID;
    }
}
