<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Price_ref_barang_stok extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Barang_Stok.View';
  protected $addPermission    = 'Barang_Stok.Add';
  protected $managePermission = 'Barang_Stok.Manage';
  protected $deletePermission = 'Barang_Stok.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Price_ref_barang_stok/Price_ref_barang_stok_model'
    ));
    $this->template->title('Price Reference Barang Stok');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');
    $listData = $this->Price_ref_barang_stok_model->get_data();

    $data = [
      'result' =>  $listData
    ];

    history("View index price reference barang stok");
    $this->template->set($data);
    $this->template->title('Price Reference >> Barang Stok');
    $this->template->render('index');
  }

  public function add($id = null)
  {
    if (empty($id)) {
      $this->auth->restrict($this->addPermission);
    } else {
      $this->auth->restrict($this->managePermission);
    }
    if ($this->input->post()) {
      $post = $this->input->post();

      $id                   = $post['id'];
      $action_app           = $post['action_app'];
      $status_reject        = $post['status_reject'];


      $price_ref_use_after          = str_replace(',', '', $post['price_ref_use_after']);
      $price_ref_expired_use_after  = $post['price_ref_expired_use_after'];

      $getPurchase = $this->db->get_where('ms_barang_stok', ['id_barang_stok' => $id])->row();
      print_r($getPurchase);
      exit;

      if ($action_app == '1') {
        $dataProcess = [
          'price_ref'             => $getPurchase->price_ref_new,
          'price_ref_high'        => $getPurchase->price_ref_high_new,
          'price_ref_date'        => $getPurchase->price_ref_new_date,
          'price_ref_expired'     => $getPurchase->price_ref_new_expired,

          'price_ref_new'             => NULL,
          'price_ref_high_new'        => NULL,
          'price_ref_new_date'        => NULL,
          'price_ref_new_expired'     => NULL,

          'price_ref_use'         => $price_ref_use_after,
          'price_ref_date_use'    => date('Y-m-d'),
          'price_ref_expired_use'   => $price_ref_expired_use_after,

          'status_reject'   => $status_reject,
          'status_app'      => 'N',
          'app_by'          => $this->auth->user_id(),
          'app_date'        => date("Y-m-d H:i:s")
        ];
      } else {
        $dataProcess = [
          'status_reject'   => $status_reject,
          'status_app'      => 'N',
          'app_by'          => $this->auth->user_id(),
          'app_date'        => date("Y-m-d H:i:s")
        ];
      }

      $this->db->trans_start();
      $this->db->where('id_barang_stok', $id);
      $this->db->update('ms_barang_stok', $dataProcess);
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        print_r($this->db->display_errors());
        exit;
        $this->db->trans_rollback();
        $status  = array(
          'pesan'    => 'Failed process data!',
          'status'  => 0
        );
      } else {
        $this->db->trans_commit();
        $status  = array(
          'pesan'    => 'Success process data!',
          'status'  => 1
        );
        history("Update price reference barang stok: " . $id);
      }
      echo json_encode($status);
    } else {
      $listData = $this->db->get_where('ms_barang_stok', array('id_barang_stok' => $id))->result();

      $data = [
        'listData' => $listData,
      ];
      $this->template->set($data);
      $this->template->render('add');
    }
  }
}
