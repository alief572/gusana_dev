<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Request_pr_stok_model extends BF_Model
{

  public function __construct()
  {
    parent::__construct();

    $this->ENABLE_ADD     = has_permission('PR_Stok.Add');
    $this->ENABLE_MANAGE  = has_permission('PR_Stok.Manage');
    $this->ENABLE_VIEW    = has_permission('PR_Stok.View');
    $this->ENABLE_DELETE  = has_permission('PR_Stok.Delete');
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

      $nama_user = '';
      $get_user = $this->db->select('full_name')->get_where('users', ['id_user' => $row['booking_by']])->row();
      if(!empty($get_user)){
        $nama_user = $get_user->full_name;
      }

      $nestedData   = array();
      $nestedData[]  = "<div align='center'>" . $nomor . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper('PRODUCTION PLANNING ' . $row['so_number']) . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['so_number']) . "</div>";
      $nestedData[]  = "<div align='center'>" . strtoupper($row['no_pr']) . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['project']) . "</div>";
      $nestedData[]  = "<div class='text-left'>" . ucwords(strtolower($nama_user)) . "</div>";
      $nestedData[]  = "<div class='text-left'>" . date('d-M-Y', strtotime($row['booking_date'])) . "</div>";
      $nestedData[]  = "<div class='text-left'>" . $row['no_rev'] . "</div>";

      $getCheck = $this->db->get_where('material_planning_base_on_produksi_detail', array('so_number' => $row['so_number'], 'status_app' => 'N'))->result();

      $warna = (COUNT($getCheck) > 0) ? 'primary' : 'success';
      $status = (COUNT($getCheck) > 0) ? 'Waiting Approval' : 'Close';
      $nestedData[]  = "<div align='left'><span class='badge badge-" . $warna . "'>" . $status . "</span></div>";

      $approve  = "";
      $view  = "<a href='" . site_url($this->uri->segment(1)) . '/detail_planning/' . $row['so_number'] . "' class='btn btn-sm btn-warning' title='Detail PR' data-role='qtip'><i class='fa fa-eye'></i></a>";
      $edit   = "";
      if($this->ENABLE_MANAGE AND COUNT($getCheck) > 0){
        $edit   = "<a href='" . site_url($this->uri->segment(1)) . '/edit_planning/' . $row['so_number'] . "' class='btn btn-sm btn-info' title='Edit PR' data-role='qtip'><i class='fa fa-edit'></i></a>";
      }
      $nestedData[]  = "<div align='left'>" . $view . " " . $edit . " " . $approve . "</div>";
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
              b.customer_name as nm_customer
            FROM
              material_planning_base_on_produksi a
              LEFT JOIN customers b ON a.id_customer=b.id_customer,
              (SELECT @row:=0) r
            WHERE 1=1 AND a.category='pr stok' AND a.booking_date IS NOT NULL AND (
              b.customer_name LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.so_number LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.project LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              OR a.no_pr LIKE '%" . $this->db->escape_like_str($like_value) . "%'
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
      $requestData['category'],
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

      $qty_stock    = $row['request'];
      $qty_booking  = 0;

      $nestedData   = array();
      $nestedData[]  = "<div align='center'>" . $nomor . "</div>";
      $nestedData[]  = "<div align='left'>" . $row['id_barang_stok'] . "</div>";
      $nestedData[]  = "<div align='left'>" . $row['nm_barang_stok'] . "</div>";
      $nestedData[]  = "<div align='left'>" . strtoupper($row['nm_kategori_stok']) . "</div>";

      $konversi = $row['konversi'];
      $qty_pack = 0;
      // if ($konversi > 0 and $qty_stock > 0) {
      //   $qty_pack = $qty_stock / $konversi;
      // }

      $nestedData[]  = "<div align='right'>" . number_format($qty_pack, 2) . "</div>";
      $nestedData[]  = "<div align='center'>" . $row['unit_nm'] . "</div>";
      $nestedData[]  = "<div align='center' class='konversi'>" . number_format($konversi, 2) . "</div>";
      $nestedData[]  = "<div align='right'>" . number_format($qty_stock, 2) . "</div>";

      $nestedData[]  = "<div align='right'>" . number_format($row['min_stok'], 2) . "</div>";
      $nestedData[]  = "<div align='right'>" . number_format($row['max_stok'], 2) . "</div>";

      $outanding_pr   = 0;
      $nestedData[]  = "<div align='right'>" . number_format($outanding_pr, 2) . "</div>";

      // $kg_per_bulan 	= 0;
      // $reorder 		= ($row['min_stok']/30) * $kg_per_bulan;
      // $max_stock2 = ($row['max_stok']/30) * $kg_per_bulan;
      // $qtypr = 0; //semnetara NOL
      // $QTY_PR = $max_stock2 - ($qty_stock - $qty_booking) - $qtypr;
      // if($QTY_PR < 0){
      // 	$QTY_PR = '';
      // }

      $QTY_PR = 0;
      // if ($qty_stock < $row['min_stok']) {
      //   $QTY_PR = ($row['max_stok'] - ($qty_stock + $outanding_pr));
      // }

      $purchase2 = (!empty($row['request'])) ? $row['request'] : $QTY_PR;

      $purchase_packing = 0;
      if ($konversi > 0 and $purchase2 > 0) {
        $purchase_packing = $purchase2 / $konversi;
      }

      $nestedData[]  = " <div align='left'>
									        <input type='text' name='purchase' id='purchase_" . $nomor . "' data-id_material='" . $row['id_barang_stok'] . "' data-no='" . $nomor . "' class='form-control input-sm text-right maskM changeSave' style='width:100%;' value='" . $purchase2 . "'>
								        </div>";
      $nestedData[]  = " <div align='center' class='propose_packing'>" . number_format($purchase_packing, 2) . "</div>
                          <script type='text/javascript'>$('.maskM').autoNumeric('init', {mDec: '2', aPad: false});</script>";
      $nestedData[]  = "<div align='center'>" . $row['nm_packaging'] . "</div>";

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

  public function query_data_json_reorder_point($category, $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
  {
    $tanggal  = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
    $bulan    = ltrim(date('m', strtotime($tanggal)), '0');
    $tahun    = date('Y', strtotime($tanggal));

    $product_where = "";
    if ($category != '0') {
      $product_where = " AND a.id_kategori_stok = '" . $category . "'";
    }

    $sql = "SELECT
              (@row:=@row+1) AS nomor,
              a.*,
              z.nm_kategori_stok AS nama_level1
            FROM
              ms_barang_stok a
              LEFT JOIN ms_kategori_stok z ON a.id_kategori_stok = z.id_kategori_stok,
              (SELECT @row:=0) r
            WHERE 1=1 
              AND (
                a.id_barang_stok LIKE '%" . $this->db->escape_like_str($like_value) . "%'
                OR a.nm_barang_stok LIKE '%" . $this->db->escape_like_str($like_value) . "%'
              )
          ";
    // echo $sql; exit;

    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'id_barang_stok',
      2 => 'nm_barang_stok'
    );

    $sql .= " ORDER BY a.id_barang_stok, " . $columns_order_by[$column_order] . " " . $column_dir . " ";
    $sql .= " LIMIT " . $limit_start . " ," . $limit_length . " ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }
}
