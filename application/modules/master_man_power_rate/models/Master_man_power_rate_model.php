<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Master_man_power_rate_model extends BF_Model
{
    function get_data_by_id($id)
    {
        $getData = $this->db->get_where('ms_komp_man_power_rate', ['id' => $id])->row();

        return $getData;
    }
}
