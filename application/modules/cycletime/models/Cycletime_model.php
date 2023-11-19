<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cycletime_model extends BF_Model
{

  protected $table_name = 'cycletime_header';
  protected $key        = 'id';

  protected $created_field = 'created_on';
  protected $modified_field = 'modified_on';
  protected $set_created = true;
  protected $set_modified = true;
  protected $soft_deletes = true;
  protected $date_format = 'datetime';
  protected $log_user = true;

  public function __construct()
  {
    parent::__construct();
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

  public function get_data_group($table, $where_field = '', $where_value = '', $where_group = '')
  {
    if ($where_field != '' && $where_value != '') {
      $query = $this->db->group_by($where_group)->get_where($table, array($where_field => $where_value));
    } else {
      $query = $this->db->get($table);
    }

    return $query->result();
  }

  public function get_data_where_array($table = null, $where = null)
  {
    if (!empty($where)) {
      $query = $this->db->get_where($table, $where);
    } else {
      $query = $this->db->get($table);
    }

    return $query->result();
  }

  function get_name($table, $field, $where, $value)
  {
    $query = "SELECT " . $field . " FROM " . $table . " WHERE " . $where . "='" . $value . "' LIMIT 1";
    $result = $this->db->query($query)->result();

    return $result->$field;
  }

  public function get_json_cycletime()
  {
    $controller      = ucfirst(strtolower($this->uri->segment(1)));
    // $Arr_Akses			= getAcccesmenu($controller);
    $requestData    = $_REQUEST;
    $fetch          = $this->get_query_json_cycletime(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData      = $fetch['totalData'];
    $totalFiltered  = $fetch['totalFiltered'];
    $query          = $fetch['query'];

    $data  = array();
    $urut1  = 1;
    $urut2  = 0;
    foreach ($query->result_array() as $row) {
      $total_data     = $totalData;
      $start_dari     = $requestData['start'];
      $asc_desc       = $requestData['order'][0]['dir'];
      if ($asc_desc == 'asc') {
        $nomor = $urut1 + $start_dari;
      }
      if ($asc_desc == 'desc') {
        $nomor = ($total_data - $start_dari) - $urut2;
      }

      $nestedData   = array();
      $nestedData[]  = "<div align='left'>" . $nomor . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['nm_product']) . "</div>";

      $totalTime = 0;
      $TimeTotal = get_total_time_ct($row['id_product']);
      if ($TimeTotal > 0) {
        $totalTime = $TimeTotal / 60;
      }

      $nestedData[]  = "<div align='center'>" . number_format($totalTime, 2) . "</div>";

      $last_create = (!empty($row['updated_by'])) ? $row['updated_by'] : $row['created_by'];
      $nestedData[]  = "<div align='left'>" . strtolower(get_name('users', 'username', 'id_user', $last_create)) . "</div>";

      $last_date = (!empty($row['updated_date'])) ? $row['updated_date'] : $row['created_date'];
      $nestedData[]  = "<div align='left'>" . date('d-M-Y H:i', strtotime($last_date)) . "</div>";

      $edit  = "";
      $delete  = "";
      $print  = "";
      $approve = "";
      $download = "";

      $edit  = "&nbsp;<a href='" . site_url($this->uri->segment(1)) . '/edit/' . $row['id_time'] . "' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
      $delete  = "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete' data-id_time='" . $row['id_time'] . "'><i class='fa fa-trash'></i></button>";
      // $print	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/excel_ct_per_product/'.$row['id_time'])."' class='btn btn-sm btn-info' target='_blank' title='Print Sales Order' data-role='qtip'><i class='fa fa-print'></i></a>";

      $nestedData[]  = "<div align='left'>
                        <button type='button' class='btn btn-sm btn-warning detail' title='Detail' data-id_time='" . $row['id_time'] . "'><i class='fa fa-eye'></i></button>

                        " . $edit . "
                        " . $print . "
                        " . $approve . "
                        " . $download . "
                        " . $delete . "
                        </div>";
      $data[] = $nestedData;
      $urut1++;
      $urut2++;
    }

    $json_data = array(
      "draw"              => intval($requestData['draw']),
      "recordsTotal"      => intval($totalData),
      "recordsFiltered"   => intval($totalFiltered),
      "data"              => $data
    );

    echo json_encode($json_data);
  }

  public function get_query_json_cycletime($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
  {

    $sql = "SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              b.nama AS nm_product
            FROM
              cycletime_header a
              LEFT JOIN ms_product_category3 b ON a.id_product = b.id_category3,
              (SELECT @row:=0) r
            WHERE a.deleted_date IS NULL AND 
            (
              a.id_product LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR b.nama LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.updated_by LIKE '%" . $this->db->escape_like_str($like_value) . "%'
            )
    ";
    // echo $sql; exit;

    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'b.nama',
      2 => 'b.nama',
      3 => 'updated_by',
      4 => 'updated_date'
    );

    $sql .= " ORDER BY a.id DESC,  " . $columns_order_by[$column_order] . " " . $column_dir . " ";
    $sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }
}
