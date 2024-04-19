<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request_pr_material extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'PR_Material.View';
  protected $addPermission    = 'PR_Material.Add';
  protected $managePermission = 'PR_Material.Manage';
  protected $deletePermission = 'PR_Material.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array('Request_pr_material/request_pr_material_model'));
    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session  = $this->session->userdata('app_session');

    history("View index request pr material");
    $this->template->title('Purchasing Request / PR Material');
    $this->template->render('index');
  }

  public function data_side_approval_pr_material()
  {
    $this->request_pr_material_model->data_side_approval_pr_material();
  }

  public function add()
  {
    $this->template->title('Re-Order Point Material');
    $this->template->render('add');
  }

  public function server_side_reorder_point()
  {
    $this->request_pr_material_model->get_data_json_reorder_point();
  }

  public function clear_update_reorder()
  {
    $data = $this->input->post();
    $tgl_now = date('Y-m-d');
    $tgl_next_month = date('Y-m-' . '20', strtotime('+1 month', strtotime($tgl_now)));
    $get_materials   = $this->db->get_where('ms_inventory_category3', array('deleted_by' => null))->result_array();

    foreach ($get_materials as $key => $value) {
      $ArrUpdate[$key]['id_category3'] = $value['id_category3'];
      $ArrUpdate[$key]['request'] = 0;
      $ArrUpdate[$key]['tgl_dibutuhkan'] = $tgl_next_month;
    }

    $this->db->trans_start();
    $this->db->update_batch('ms_inventory_category3', $ArrUpdate, 'id_category3');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Save process failed. Please try again later ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Save process success. Thanks ...',
        'status'  => 1
      );
      history('Clear all propose request material');
    }
    echo json_encode($Arr_Data);
  }

  public function save_reorder_change()
  {
    $data = $this->input->post();

    $id_material   = $data['id_material'];
    $purchase     = str_replace(',', '', $data['purchase']);
    $tanggal       = $data['tanggal'];
    $keterangan       = $data['keterangan'];


    $ArrHeader = array(
      'request'       => $purchase,
      'tgl_dibutuhkan'   => $tanggal,
      'keterangan'   => $keterangan,
    );

    $this->db->trans_start();
    $this->db->where('id_category3', $id_material);
    $this->db->or_where('id_category1', $id_material);
    $this->db->update('ms_inventory_category3', $ArrHeader);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Save process failed. Please try again later ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Save process success. Thanks ...',
        'status'  => 1
      );
      history('Change propose request material ' . $id_material . ' / ' . $purchase . ' / ' . $tanggal);
    }
    echo json_encode($Arr_Data);
  }

  public function save_reorder_all()
  {
    $data = $this->input->post();

    $Ym         = date('ym');
    $qIPP        = "SELECT MAX(so_number) as maxP FROM material_planning_base_on_produksi WHERE so_number LIKE 'P" . $Ym . "%' ";
    $resultIPP  = $this->db->query($qIPP)->result_array();
    $angkaUrut2  = $resultIPP[0]['maxP'];
    $urutan2    = (int)substr($angkaUrut2, 5, 5);
    $urutan2++;
    $urut2      = sprintf('%05s', $urutan2);
    $so_number      = "P" . $Ym . $urut2;

    $getraw_materials   = $this->db->get_where('ms_inventory_category3', array('request >' => 0))->result_array();

    $ArrSaveDetail = [];
    $SUM = 0;
    foreach ($getraw_materials as $key => $value) {
      $SUM += $value['request'];
      $ArrSaveDetail[$key]['so_number'] = $so_number;
      $ArrSaveDetail[$key]['id_material'] = $value['id_category3'];
      $ArrSaveDetail[$key]['propose_purchase'] = $value['request'];
      $ArrSaveDetail[$key]['note'] = $value['keterangan'];
    }

    $ArrSaveHeader = array(
      'so_number'   => $so_number,
      'no_pr'   => generateNoPR(),
      'category'     => 'pr material',
      'tgl_so'     => date('Y-m-d'),
      'id_customer'   => 'C100-2401002',
      'project' => 'Pengisian Stok Internal',
      'qty_propose' => $SUM,
      'tgl_dibutuhkan' => $value['tgl_dibutuhkan'],
      'created_by'      => $this->id_user,
      'created_date'    => $this->datetime,
      'booking_by'      => $this->id_user,
      'booking_date'    => $this->datetime

    );

    // print_r($ArrSaveHeader);
    // print_r($ArrSaveDetail);
    // exit;

    $this->db->trans_start();
    $this->db->insert('material_planning_base_on_produksi', $ArrSaveHeader);
    if (!empty($ArrSaveDetail)) {
      $this->db->insert_batch('material_planning_base_on_produksi_detail', $ArrSaveDetail);
    }
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Save process failed. Please try again later ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Save process success. Thanks ...',
        'status'  => 1
      );
      history('Save pengajuan propose material all');
    }
    echo json_encode($Arr_Data);
  }

  public function save_reorder_change_date()
  {
    $data = $this->input->post();

    $tanggal     = $data['tanggal'];
    $get_materials   = $this->db->get_where('new_inventory_4', array('category' => 'material'))->result_array();

    foreach ($get_materials as $key => $value) {
      $ArrUpdate[$key]['code_lv4'] = $value['code_lv4'];
      $ArrUpdate[$key]['tgl_dibutuhkan'] = $tanggal;
    }

    $this->db->trans_start();
    $this->db->update_batch('new_inventory_4', $ArrUpdate, 'code_lv4');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Save process failed. Please try again later ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Save process success. Thanks ...',
        'status'  => 1
      );
      history('Change propose request material tgl dibutuhkan all ' . $tanggal);
    }
    echo json_encode($Arr_Data);
  }

  public function set_update_propose_reorder()
  {
    $data = $this->input->post();
    $tgl_now = date('Y-m-d');
    $GET_OUTANDING_PR = get_pr_on_progress();
    $tgl_next_month = date('Y-m-' . '20', strtotime('+1 month', strtotime($tgl_now)));
    $get_materials   = $this->db
      ->select('a.*, b.qty_stock')
      ->join('ms_stock_material b', 'a.id_category1 = b.id_catgeory1 OR a.id_category3 = b.id_category3', 'left')
      ->get_where('ms_inventory_category3 a', array('a.deleted_by' => null))
      ->result_array();

    foreach ($get_materials as $key => $value) {
      $outanding_pr   = (!empty($GET_OUTANDING_PR[$value['code_lv4']]) and $GET_OUTANDING_PR[$value['code_lv4']] > 0) ? $GET_OUTANDING_PR[$value['code_lv4']] : 0;

      $QTY_PR = NULL;
      if ($value['qty_stock'] < $value['min_stok']) {
        $QTY_PR = ($value['max_stok'] - ($value['qty_stock'] + $outanding_pr));
        $QTY_PR = ($QTY_PR < 0) ? NULL : $QTY_PR;
      }

      $ArrUpdate[$key]['code_lv4'] = $value['code_lv4'];
      $ArrUpdate[$key]['request'] = $QTY_PR;
      $ArrUpdate[$key]['tgl_dibutuhkan'] = $tgl_next_month;
    }

    $this->db->trans_start();
    $this->db->update_batch('new_inventory_4', $ArrUpdate, 'code_lv4');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Save process failed. Please try again later ...',
        'status'  => 0
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Save process success. Thanks ...',
        'status'  => 1
      );
      history('Set propose request material');
    }
    echo json_encode($Arr_Data);
  }













  public function approval_planning($so_number = null)
  {
    if ($this->input->post()) {
      $data         = $this->input->post();
      $session      = $this->session->userdata('app_session');

      $so_number        = $data['so_number'];
      $tgl_dibutuhkan    = (!empty($data['tgl_dibutuhkan'])) ? date('Y-m-d', strtotime($data['tgl_dibutuhkan'])) : NULL;
      $detail            = $data['detail'];


      $ArrPlanningDetail = [];
      $SUM_USE = 0;
      $SUM_PROPOSE = 0;
      if (!empty($detail)) {
        foreach ($detail as $key => $value) {
          //Planning
          $use_stock = str_replace(',', '', $value['use_stock']);
          $propose = str_replace(',', '', $value['propose']);

          $ArrPlanningDetail[$key]['id'] = $value['id'];
          $ArrPlanningDetail[$key]['stock_free'] = $value['stock_free'];
          $ArrPlanningDetail[$key]['min_stock'] = $value['min_stok'];
          $ArrPlanningDetail[$key]['max_stock'] = $value['max_stok'];
          $ArrPlanningDetail[$key]['use_stock'] = $use_stock;
          $ArrPlanningDetail[$key]['propose_purchase'] = $propose;

          $SUM_USE += $use_stock;
          $SUM_PROPOSE += $propose;
        }
      }

      $ArrHeader = array(
        'tgl_dibutuhkan'  => $tgl_dibutuhkan,
        'qty_use_stok'  => $SUM_USE,
        'qty_propose'  => $SUM_PROPOSE,
        'updated_by'      => $this->id_user,
        'updated_date'    => $this->datetime
      );

      // print_r($ArrBOMDetail);
      // exit;

      $this->db->trans_start();
      $this->db->where('so_number', $so_number);
      $this->db->update('material_planning_base_on_produksi', $ArrHeader);

      if (!empty($ArrPlanningDetail)) {
        $this->db->update_batch('material_planning_base_on_produksi_detail', $ArrPlanningDetail, 'id');
      }
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $Arr_Data  = array(
          'pesan'    => 'Save gagal disimpan ...',
          'status'  => 0
        );
      } else {
        $this->db->trans_commit();
        $Arr_Data  = array(
          'pesan'    => 'Save berhasil disimpan. Thanks ...',
          'status'  => 1
        );
        history("Create material planning  : " . $so_number);
      }
      echo json_encode($Arr_Data);
    } else {
      $header     = $this->db
        ->select('a.*, b.due_date, c.customer_name')
        ->join('so_internal b', 'a.so_number=b.so_number', 'left')
        ->join('customers c', 'a.id_customer=c.id_customer', 'left')
        ->get_where(
          'material_planning_base_on_produksi a',
          array(
            'a.so_number' => $so_number
          )
        )
        ->result_array();
      $detail     = $this->db
        ->select('a.*, b.max_stok, b.min_stok')
        ->join('new_inventory_4 b', 'a.id_material=b.code_lv4', 'left')
        ->get_where(
          'material_planning_base_on_produksi_detail a',
          array(
            'a.so_number' => $so_number
          )
        )
        ->result_array();

      $data = [
        'so_number' => $so_number,
        'header' => $header,
        'detail' => $detail,
        'GET_LEVEL4'   => get_inventory_lv4(),
        'GET_STOK_PUSAT' => getStokMaterial(1)
      ];

      $this->template->title('Approval PR - ' . $so_number);
      $this->template->render('approval_planning', $data);
    }
  }

  public function detail_planning($so_number = null)
  {
    $header     = $this->db
      ->select('a.*, b.due_date, c.customer_name')
      ->join('so_internal b', 'a.so_number=b.so_number', 'left')
      ->join('customers c', 'a.id_customer=c.id_customer', 'left')
      ->get_where(
        'material_planning_base_on_produksi a',
        array(
          'a.so_number' => $so_number
        )
      )
      ->result_array();
    $detail     = $this->db
      ->select('a.*, b.max_stok, b.min_stok, b.nama')
      ->join('ms_inventory_category3 b', 'a.id_material=b.id_category3', 'left')
      ->get_where(
        'material_planning_base_on_produksi_detail a',
        array(
          'a.so_number' => $so_number
        )
      )
      ->result_array();

    $data = [
      'so_number' => $so_number,
      'header' => $header,
      'detail' => $detail,
      // 'GET_LEVEL4'   => get_inventory_lv4(),
      // 'GET_STOK_PUSAT' => getStokMaterial(1)
    ];

    $this->template->title('Detail PR - ' . $so_number);
    $this->template->render('detail_planning', $data);
  }

  public function edit_planning($so_number = null)
  {
    $header     = $this->db
      ->select('a.*, b.due_date, c.customer_name')
      ->join('so_internal b', 'a.so_number=b.so_number', 'left')
      ->join('customers c', 'a.id_customer=c.id_customer', 'left')
      ->get_where(
        'material_planning_base_on_produksi a',
        array(
          'a.so_number' => $so_number
        )
      )
      ->result_array();
    $detail     = $this->db
      ->select('a.*, b.max_stok, b.min_stok, b.nama, b.material_code')
      ->join('ms_inventory_category3 b', 'a.id_material = b.id_category3', 'left')
      ->get_where(
        'material_planning_base_on_produksi_detail a',
        array(
          'a.so_number' => $so_number
        )
      )
      ->result_array();

    $data = [
      'so_number' => $so_number,
      'header' => $header,
      'detail' => $detail,
      // 'GET_LEVEL4'   => get_inventory_lv4(),
      // 'GET_STOK_PUSAT' => getStokMaterial(1)
    ];

    $this->template->title('Edit PR - ' . $so_number);
    $this->template->render('edit_planning', $data);
  }

  public function process_approval_satuan()
  {
    $data       = $this->input->post();
    $id          = $data['id'];
    $action      = $data['action'];
    $so_number  = $data['so_number'];
    $pr_rev      = str_replace(',', '', $data['pr_rev']);

    $ArrHeader = array(
      'propose_rev'  => ($action == 'approve') ? $pr_rev : NULL,
      'status_app'  => ($action == 'approve') ? 'Y' : 'D',
      'app_by'      => $this->id_user,
      'app_date'    => $this->datetime
    );

    // print_r($ArrBOMDetail);
    // exit;

    $this->db->trans_start();
    $this->db->where('id', $id);
    $this->db->update('material_planning_base_on_produksi_detail', $ArrHeader);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Process Failed !',
        'status'  => 0,
        'so_number'  => $so_number
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Process Success !',
        'status'  => 1,
        'so_number'  => $so_number
      );
      history($action . " satuan pr material  : " . $id);
    }
    echo json_encode($Arr_Data);
  }

  public function process_approval_all()
  {
    $data       = $this->input->post();
    $check      = $data['check'];
    $so_number  = $data['so_number'];

    $ArrUpdate = [];
    foreach ($check as $key => $value) {
      $ArrUpdate[$key]['id'] = $value;
      $ArrUpdate[$key]['propose_rev'] = str_replace(',', '', $data['pr_rev_' . $value]);
      $ArrUpdate[$key]['status_app'] = 'Y';
      $ArrUpdate[$key]['app_by'] = $this->id_user;
      $ArrUpdate[$key]['app_date'] = $this->datetime;
    }

    $this->db->trans_start();
    if (!empty($ArrUpdate)) {
      $this->db->update_batch('material_planning_base_on_produksi_detail', $ArrUpdate, 'id');
    }
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Process Failed !',
        'status'  => 0,
        'so_number'  => $so_number
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Process Success !',
        'status'  => 1,
        'so_number'  => $so_number
      );
      history("Approve pr material  : " . $so_number);
    }
    echo json_encode($Arr_Data);
  }

  public function process_update_all()
  {
    $data       = $this->input->post();
    $detail      = $data['detail'];
    $so_number  = $data['so_number'];

    $ArrUpdate = [];
    foreach ($detail as $key => $value) {
      $ArrUpdate[$key]['id'] = $value['id'];
      $ArrUpdate[$key]['propose_purchase'] = str_replace(',', '', $value['qty']);
      $ArrUpdate[$key]['note'] = $value['note'];
    }

    $get_pr = $this->db->get_where('material_planning_base_on_produksi', ['so_number' => $so_number])->row();


    $this->db->trans_start();
    $this->db->update('material_planning_base_on_produksi', ['no_rev' => ($get_pr->no_rev + 1), 'reject_status' => '0'], ['so_number' => $so_number]);
    if (!empty($ArrUpdate)) {
      $this->db->update_batch('material_planning_base_on_produksi_detail', $ArrUpdate, 'id');
    }
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $Arr_Data  = array(
        'pesan'    => 'Process Failed !',
        'status'  => 0,
        'so_number'  => $so_number
      );
    } else {
      $this->db->trans_commit();
      $Arr_Data  = array(
        'pesan'    => 'Process Success !',
        'status'  => 1,
        'so_number'  => $so_number
      );
      history("Update qty pr material  : " . $so_number);
    }
    echo json_encode($Arr_Data);
  }

  public function print_new()
  {
    $kode  = $this->uri->segment(3);
    $data_session  = $this->session->userdata;
    $session        = $this->session->userdata('app_session');
    $printby    = $session['id_user'];

    $data_url    = base_url();
    $Split_Beda    = explode('/', $data_url);
    $Jum_Beda    = count($Split_Beda);
    $Nama_Beda    = $Split_Beda[$Jum_Beda - 2];

    $getData        = $this->db->get_where('material_planning_base_on_produksi', array('so_number' => $kode))->result_array();
    // $getDataDetail  = $this->db->get_where('material_planning_base_on_produksi_detail', array('so_number' => $kode))->result_array();
    $getCustomer    = $this->db->get_where('customers', array('id_customer' => $getData[0]['id_customer']))->result_array();

    $this->db->select('a.*, b.nama, b.material_code');
    $this->db->from('material_planning_base_on_produksi_detail a');
    $this->db->join('ms_inventory_category3 b', 'b.id_category3 = a.id_material', 'left');
    $this->db->where('a.so_number', $kode);
    $getDataDetail = $this->db->get()->result_array();

    $data = array(
      'Nama_Beda' => $Nama_Beda,
      'printby' => $printby,
      'getData' => $getData,
      'getDataDetail' => $getDataDetail,
      'getCustomer' => $getCustomer,
      'kode' => $kode
    );
    $this->load->view('print_new', $data);
  }

  public function PrintH2()
  {
    ob_clean();
    ob_start();
    $this->auth->restrict($this->managePermission);
    $id = $this->uri->segment(3);
    $data['header'] = $this->db->query("SELECT a.*, b.customer_name, b.alamat, c.name as country_name, d.nm_pic, d.hp, d.email_pic, b.fax FROM material_planning_base_on_produksi as a LEFT JOIN material_planning_base_on_produksi x ON x.so_number = a.so_number LEFT JOIN customers b ON b.id_customer = a.id_customer LEFT JOIN country_all c ON c.iso3 = b.country_code LEFT JOIN customer_pic d ON d.id_pic = b.id_pic WHERE a.so_number = '" . $id . "' ")->result();
    $data['detail']  = $this->db->query("SELECT a.*, b.nama FROM material_planning_base_on_produksi_detail a 
		INNER JOIN new_inventory_4 b ON b.code_lv4 = a.id_material
		WHERE a.so_number = '" . $id . "' ")->result();
    // $data['detailsum'] = $this->db->query("SELECT AVG(width) as totalwidth, AVG(qty) as totalqty FROM dt_trans_po WHERE no_po = '" . $id . "' ")->result();
    $this->load->view('Print', $data);
    $html = ob_get_contents();

    // print_r($data['header']);
    // exit;

    require_once('./assets/html2pdf/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(10, 5, 10, 5));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->WriteHTML($html);
    ob_end_clean();
    $html2pdf->Output('Purchase Request.pdf', 'I');

    // $this->template->title('Testing');
    // $this->template->render('print2');
  }
}
