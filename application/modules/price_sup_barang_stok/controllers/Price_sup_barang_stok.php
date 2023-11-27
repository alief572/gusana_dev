<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Price_sup_barang_stok extends Admin_Controller
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
      'Price_sup_barang_stok/Price_sup_barang_stok_model'
    ));
    $this->template->title('Price Suppliers Barang Stok');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');

    $listData = $this->Price_sup_barang_stok_model->get_data();

    $data = [
      'result' =>  $listData
    ];

    history("View index price from supplier barang stok");
    $this->template->set($data);
    $this->template->title('Price From Supplier >> Barang Stok');
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

      $id                 = $post['id'];
      $price_ref_new_idr      = str_replace(',', '', $post['price_ref_new_idr']);
      $price_ref_new_usd      = str_replace(',', '', $post['price_ref_new_usd']);
      $price_ref_new_rmb      = str_replace(',', '', $post['price_ref_new_rmb']);
      $price_ref_high_new_idr      = str_replace(',', '', $post['price_ref_high_new_idr']);
      $price_ref_high_new_usd      = str_replace(',', '', $post['price_ref_high_new_usd']);
      $price_ref_high_new_rmb      = str_replace(',', '', $post['price_ref_high_new_rmb']);
      $price_ref_expired  = $post['price_ref_expired'];
      $note               = $post['note'];

      $dataProcess1 = [
        'price_ref_new_idr'  => $price_ref_new_idr,
        'price_ref_new_usd'  => $price_ref_new_usd,
        'price_ref_new_rmb'  => $price_ref_new_rmb,
        'price_ref_high_new_idr'  => $price_ref_high_new_idr,
        'price_ref_high_new_usd'  => $price_ref_high_new_usd,
        'price_ref_high_new_rmb'  => $price_ref_high_new_rmb,
        'price_ref_new_expired'  => $price_ref_expired,
        'price_ref_new_date'  => date('Y-m-d'),
        'note'  => $note,
        'status_app'  => 'Y',
        'app_by'    => $this->auth->user_id(),
        'app_date'  => date("Y-m-d H:i:s")
      ];

      //UPLOAD DOCUMENT
      $dataProcess2 = [];
      if (!empty($_FILES['photo']["tmp_name"])) {
        $target_dir     = "uploads/prs_barang_stok/";
        $target_dir_u   = "./uploads/prs_barang_stok/";
        $name_file      = 'evidence-stok-' . $id . "-" . date('Ymdhis');
        $target_file    = $target_dir . basename($_FILES['photo']["name"]);
        $name_file_ori  = basename($_FILES['photo']["name"]);
        $imageFileType  = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $nama_upload    = $target_dir_u . $name_file . "." . $imageFileType;

        // if($imageFileType == 'pdf' OR $imageFileType == 'jpeg' OR $imageFileType == 'jpg'){

        $terupload = move_uploaded_file($_FILES['photo']["tmp_name"], $nama_upload);
        $link_url      = $target_dir . $name_file . "." . $imageFileType;

        $dataProcess2  = array('upload_file' => $link_url);
        // }
      }

      $dataProcess = array_merge($dataProcess1, $dataProcess2);

      // print_r($dataProcess);
      // exit;

      $this->db->trans_start();
      $this->db->where('id_barang_stok', $id);
      $this->db->update('ms_barang_stok', $dataProcess);
      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
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
        history("Update price supplier barang stok: " . $id);
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

  public function get_kurs(){
    $get_kurs_usd = $this->Price_sup_barang_stok_model->get_kurs_usd();
    $get_kurs_rmb = $this->Price_sup_barang_stok_model->get_kurs_rmb();

    $hasil = [
      'kurs_usd' => $get_kurs_usd,
      'kurs_rmb' => $get_kurs_rmb
    ];

    echo json_encode($hasil);
  }
}
