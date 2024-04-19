<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request_pr_material_model extends BF_Model
{

  public function __construct()
  {
    parent::__construct();

    $this->ENABLE_ADD     = has_permission('PR_Material.Add');
    $this->ENABLE_MANAGE  = has_permission('PR_Material.Manage');
    $this->ENABLE_VIEW    = has_permission('PR_Material.View');
    $this->ENABLE_DELETE  = has_permission('PR_Material.Delete');
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

  public function data_side_approval_pr_material()
  {

    $requestData    = $_REQUEST;
    $fetch          = $this->get_query_approval_pr_material(
      $requestData['product'],
      $requestData['costcenter'],
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
    // $GET_USER = get_list_user();
    foreach ($query->result_array() as $row) {
      $total_data     = $totalData;
      $start_dari     = $requestData['start'];
      $asc_desc       = $requestData['order'][0]['dir'];
      if ($asc_desc == 'asc') {
        $nomor = ($total_data - $start_dari) - $urut2;
      }
      if ($asc_desc == 'desc') {
        $nomor = $urut1 + $start_dari;
      }

      $booking_by_name = '';
      $get_user_name = $this->db->select('full_name')->get_where('users', ['id_user' => $row['booking_by']])->row();
      if (!empty($get_user_name)) {
        $booking_by_name = $get_user_name->full_name;
      }

      $nestedData   = array();
      $nestedData[]  = "<div align='center'>" . $nomor . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper('PRODUCTION PLANNING ' . $row['so_number']) . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['so_number']) . "</div>";
      $nestedData[]  = "<div align='center'>" . strtoupper($row['no_pr']) . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['project']) . "</div>";
      $nama_user = $booking_by_name;
      $nestedData[]  = "<div class='text-left'>" . ucwords(strtolower($nama_user)) . "</div>";
      $nestedData[]  = "<div class='text-left'>" . date('d-M-Y', strtotime($row['booking_date'])) . "</div>";
      $nestedData[]  = "<div class='text-center text-primary text-bold'>" . $row['no_rev'] . "</div>";

      $getCheck = $this->db->get_where('material_planning_base_on_produksi_detail', array('so_number' => $row['so_number'], 'status_app' => 'N'))->result();

      $warna = (COUNT($getCheck) > 0) ? 'primary' : 'success';
      $status = (COUNT($getCheck) > 0) ? 'Waiting Approval' : 'Close';
      if ($row['reject_status'] == '1') {
        $warna = 'red';
        $status = 'Reject';
        $getCheck = 0;
      }
      $nestedData[]  = "<div align='left'><span class='badge badge-" . $warna . "'>" . $status . "</span></div>";
      $nestedData[]  = "<div align='left'>" . ($row['reject_status']) ? $row['reject_reason'] : null . "</div>";

      $print  = "<a href='" . base_url($this->uri->segment(1)) . '/print_new/' . $row['so_number'] . "' target='_blank' class='btn btn-sm btn-primary' blank='_blank'><i class='fa fa-print'></i></a>";
      $view   = "<a href='" . site_url($this->uri->segment(1)) . '/detail_planning/' . $row['so_number'] . "' class='btn btn-sm btn-warning' title='Detail PR' data-role='qtip'><i class='fa fa-eye'></i></a>";
      $edit   = "";
      if ($this->ENABLE_MANAGE) {
        if (COUNT($getCheck) > 0 || $row['reject_status'] == '1') {
          $edit   = "<a href='" . site_url($this->uri->segment(1)) . '/edit_planning/' . $row['so_number'] . "' class='btn btn-sm btn-info' title='Edit PR' data-role='qtip'><i class='fa fa-edit'></i></a>";
        }
      }


      if ($row['reject_status'] == '1') {
        $nestedData[]  = "<div align='left'>" . $view . " " . $edit . "</div>";
      } else {
        $nestedData[]  = "<div align='left'>" . $view . " " . $edit . " " . $print . "</div>";
      }
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

  public function get_query_approval_pr_material($product, $costcenter, $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
  {

    $costcenter_where = "";
    // if($costcenter != '0'){
    // $costcenter_where = " AND a.costcenter = '".$costcenter."'";
    // }

    $product_where = "";
    // if($product != '0'){
    // $product_where = " AND b.code_lv1 = '".$product."'";
    // }

    $sql = "SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              b.customer_name
            FROM
              material_planning_base_on_produksi a
              LEFT JOIN customers b ON a.id_customer=b.id_customer,
              (SELECT @row:=0) r
            WHERE 1=1 AND a.category in ('pr material','base on production') AND a.booking_date IS NOT NULL " . $costcenter_where . " " . $product_where . " AND (
              b.customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.so_number LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.project LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.no_pr LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.no_rev LIKE '%" . $this->db->escape_like_str($like_value) . "%'
            )
            ";
    // echo $sql; exit;

    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'so_number',
      2 => 'so_number',
      3 => 'no_pr',
      4 => 'so_number'
    );

    $sql .= " ORDER BY a.created_date DESC, " . $columns_order_by[$column_order] . " " . $column_dir . " ";
    $sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

  public function get_data_json_reorder_point()
  {
    // $controller			= ucfirst(strtolower($this->uri->segment(1)));
    // $Arr_Akses			= getAcccesmenu($controller);

    $requestData  = $_REQUEST;
    $fetch      = $this->query_data_json_reorder_point(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData    = $fetch['totalData'];
    $totalFiltered  = $fetch['totalFiltered'];
    $query      = $fetch['query'];

    // $GET_SATUAN = get_list_satuan();
    // $GET_OUTANDING_PR = get_pr_on_progress();

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

      $tgl_now = date('Y-m-d');
      $tgl_next_month = date('Y-m-' . '20', strtotime('+1 month', strtotime($tgl_now)));

      $nestedData   = array();
      $nestedData[]  = "<div align='center'>" . $nomor . "</div>";
      $nestedData[]  = "<div align='left'>" . $row['id_category1'] . "</div>";
      $nestedData[]  = "<div align='left'>" . $row['nama'] . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['nama_level1']) . "</div>";

      $konversi = $row['konversi'];
      $qty_pack = 0;
      if ($konversi > 0 and $row['qty_stock'] > 0) {
        $qty_pack = $row['qty_stock'] / $konversi;
      }

      $satuan = $row['satuan'];

      $nestedData[]  = "<div align='right'>" . number_format($qty_pack, 2) . "</div>";
      $nestedData[]  = "<div align='center'>" . $satuan . "</div>";
      $nestedData[]  = "<div align='center' class='konversi_" . $nomor . "'>" . number_format($konversi, 2) . "</div>";
      $nestedData[]  = "<div align='right'>" . number_format($row['qty_stock'], 2) . "</div>";
      $nestedData[]  = "<div align='right'>" . number_format($row['min_stok'], 2) . "</div>";
      $nestedData[]  = "<div align='right'>" . number_format($row['max_stok'], 2) . "</div>";

      // $outanding_pr   = (!empty($GET_OUTANDING_PR[$row['code_lv4']]) and $GET_OUTANDING_PR[$row['code_lv4']] > 0) ? $GET_OUTANDING_PR[$row['code_lv4']] : 0;
      $outanding_pr   = 0;
      $nestedData[]  = "<div align='right'>" . number_format($outanding_pr, 2) . "</div>";

      $QTY_PR = '';
      if ($row['qty_stock'] < $row['min_stok']) {
        $QTY_PR = ($row['max_stok'] - ($row['qty_stock'] + $outanding_pr));
        $QTY_PR = ($QTY_PR < 0) ? 0 : $QTY_PR;
      }

      // $kg_per_bulan 	= 0;
      // $reorder 		= ($row['min_stok']/30) * $kg_per_bulan;
      // $max_stock2 = ($row['max_stok']/30) * $kg_per_bulan;
      // $qtypr = 0; //semnetara NOL
      // $QTY_PR = $max_stock2 - ($row['qty_stock'] - $row['qty_booking']) - $qtypr;
      // if($QTY_PR < 0){
      // 	$QTY_PR = '';
      // }

      $purchase2 = (!empty($row['request'])) ? $row['request'] : $QTY_PR;
      $keterangan = (!empty($row['keterangan'])) ? $row['keterangan'] : '';

      $purchase_packing = 0;
      if ($konversi > 0 and $purchase2 > 0) {
        $purchase_packing = $purchase2 / $konversi;
      }

      $nestedData[]  = " <div align='left'>
                          <input type='text' name='purchase' id='purchase_" . $nomor . "' data-id_material='" . $row['id_category3'] . "' data-no='" . $nomor . "' class='form-control input-sm text-right maskM changeSave' style='width:100px;' value='" . $purchase2 . "'>
                        </div>";
      $nestedData[]  = " <div align='center' class='propose_packing'>" . number_format($purchase_packing, 2) . "</div>";
      $nestedData[]  = "<div align='center'>" . $satuan . "</div><script type='text/javascript'>$('.maskM').autoNumeric('init', {mDec: '2', aPad: false});</script>";
      $nestedData[]  = " <div align='left'>
                          <input type='text' name='keterangan' id='keterangan_" . $nomor . "' data-id_material='" . $row['id_category3'] . "' data-no='" . $nomor . "' class='form-control input-sm text-right changeSave' style='width:150px;' value='" . $keterangan . "'>
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

  public function query_data_json_reorder_point($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
  {
    $tanggal  = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
    $bulan    = ltrim(date('m', strtotime($tanggal)), '0');
    $tahun    = date('Y', strtotime($tanggal));

    $sql = "SELECT
              a.*,
              b.qty_stock,
              z.nama AS nama_level1,
              c.nama as satuan
            FROM
              ms_inventory_category3 a
              LEFT JOIN ms_inventory_category1 z ON a.id_category1 = z.id_category1
              LEFT JOIN ms_stock_material b ON a.id_category1 = b.id_category1 OR a.id_category3 = b.id_category3
              LEFT JOIN ms_satuan c ON c.id = a.packaging
            WHERE 1=1 
              AND (
                a.nama LIKE '%" . $this->db->escape_like_str($like_value) . "%'
                OR a.id_category1 LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              )
          ";
    // echo $sql; exit;

    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'code',
      2 => 'nama'
    );

    $sql .= " ORDER BY a.id, " . $columns_order_by[$column_order] . " " . $column_dir . " ";
    $sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }
}
