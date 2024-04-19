<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request_pr_stok extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'PR_Stok.View';
  protected $addPermission    = 'PR_Stok.Add';
  protected $managePermission = 'PR_Stok.Manage';
  protected $deletePermission = 'PR_Stok.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array('Request_pr_stok/request_pr_stok_model'));
    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session  = $this->session->userdata('app_session');

    history("View index request pr stok");
    $this->template->title('Purchasing Request / PR Stok');
    $this->template->render('index');
  }

  public function data_side_approval_pr_material()
  {
    $this->request_pr_stok_model->data_side_approval_pr_material();
  }

  public function add()
  {
    $data = [
      'category' => $this->db->get('ms_kategori_stok')->result_array()
    ];
    $this->template->title('Re-Order Point Stok');
    $this->template->render('add', $data);
  }

  public function server_side_reorder_point()
  {
    $this->request_pr_stok_model->get_data_json_reorder_point();
  }

  public function clear_update_reorder()
  {
    $data = $this->input->post();
    $tgl_now = date('Y-m-d');
    $tgl_next_month = date('Y-m-' . '20', strtotime('+1 month', strtotime($tgl_now)));


    $get_materials   = $this->db->get('ms_barang_stok')->result_array();

    foreach ($get_materials as $key => $value) {
      $ArrUpdate[$key]['id_barang_stok'] = $value['id_barang_stok'];
      $ArrUpdate[$key]['request'] = 0;
      $ArrUpdate[$key]['tgl_dibutuhkan'] = $tgl_next_month;
    }

    $this->db->trans_start();
    $this->db->update_batch('ms_barang_stok', $ArrUpdate, 'id_barang_stok');
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


    $ArrHeader = array(
      'request'       => $purchase,
      'tgl_dibutuhkan'   => $tanggal
    );

    $this->db->trans_start();
    $this->db->where('id_barang_stok', $id_material);
    $this->db->update('ms_barang_stok', $ArrHeader);
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

    // $id_category     = $data['category'];
    $getraw_materials   = $this->db->get_where('ms_barang_stok', array('request >'=>0))->result_array();

    $ArrSaveDetail = [];
    $SUM = 0;
    foreach ($getraw_materials as $key => $value) {
      $SUM += $value['request'];
      $ArrSaveDetail[$key]['so_number'] = $so_number;
      $ArrSaveDetail[$key]['id_material'] = $value['id_barang_stok'];
      $ArrSaveDetail[$key]['propose_purchase'] = $value['request'];
    }

    $ArrSaveHeader = array(
      'so_number'   => $so_number,
      'no_pr'   => generateNoPR(),
      'category'     => 'pr stok',
      'tgl_so'     => date('Y-m-d'),
      'id_customer'   => '',
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

    $tanggal         = $data['tanggal'];
    $id_category     = $data['id_category'];
    $get_materials   = $this->db->get_where('accessories', array('id_category' => $id_category))->result_array();

    foreach ($get_materials as $key => $value) {
      $ArrUpdate[$key]['id'] = $value['id'];
      $ArrUpdate[$key]['tgl_dibutuhkan'] = $tanggal;
    }

    $this->db->trans_start();
    $this->db->update_batch('accessories', $ArrUpdate, 'id');
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

  public function detail_planning($so_number = null)
  {
    $header     = $this->db
      ->select('a.*, b.due_date, c.customer_name as nm_customer')
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
      ->select('a.*, b.max_stok, b.min_stok, b.nm_barang_stok')
      ->join('ms_barang_stok b', 'a.id_material=b.id_barang_stok', 'left')
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
      'detail' => $detail
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
      ->select('a.*, b.max_stok, b.min_stok, b.nm_barang_stok')
      ->join('ms_barang_stok b', 'a.id_material=b.id_barang_stok', 'left')
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
      'detail' => $detail
    ];

    $this->template->title('Edit PR - ' . $so_number);
    $this->template->render('edit_planning', $data);
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
    }

    $get_pr = $this->db->get_where('material_planning_base_on_produksi', ['so_number' => $so_number])->row();

    
    $this->db->trans_start();
    $this->db->update('material_planning_base_on_produksi', ['no_rev' => ($get_pr->no_rev + 1)], ['so_number' => $so_number]);
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

}
