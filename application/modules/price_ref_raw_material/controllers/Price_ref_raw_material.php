<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Price_ref_raw_material extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Raw_Material.View';
  protected $addPermission    = 'Raw_Material.Add';
  protected $managePermission = 'Raw_Material.Manage';
  protected $deletePermission = 'Raw_Material.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Price_ref_raw_material/Price_ref_raw_material_model'
    ));
    $this->template->title('Price Reference Raw Material');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');

    $listData = $this->Price_ref_raw_material_model->get_data();

    $data = [
      'result' =>  $listData
    ];

    history("View index price reference raw materials");
    $this->template->set($data);
    $this->template->title('Price Reference >> Raw Materials');
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

      $this->db->trans_begin();

      $id                   = $post['id'];
      $action_app           = $post['action_app'];
      $status_reject        = $post['status_reject'];

      $price_ref_use_after_idr          = str_replace(',', '', $post['price_ref_use_after_idr']);
      $price_ref_use_after_usd          = str_replace(',', '', $post['price_ref_use_after_usd']);
      $price_ref_use_after_rmb          = str_replace(',', '', $post['price_ref_use_after_rmb']);
      $price_ref_expired_use_after  = $post['price_ref_expired_use_after'];

      $getPurchase = $this->db->get_where('ms_inventory_category3', ['id_category3' => $id])->row();

      if ($action_app == '1') {
        $dataProcess = [
          'price_ref_idr'             => $getPurchase->price_ref_new_idr,
          'price_ref_usd'             => $getPurchase->price_ref_new_usd,
          'price_ref_rmb'             => $getPurchase->price_ref_new_rmb,
          'price_ref_high_idr'        => $getPurchase->price_ref_high_new_idr,
          'price_ref_high_usd'        => $getPurchase->price_ref_high_new_usd,
          'price_ref_high_rmb'        => $getPurchase->price_ref_high_new_rmb,
          'price_ref_date'        => $getPurchase->price_ref_new_date,
          'price_ref_expired'     => $getPurchase->price_ref_new_expired,

          'price_ref_new_idr'             => NULL,
          'price_ref_new_usd'             => NULL,
          'price_ref_new_rmb'             => NULL,
          'price_ref_high_new_idr'        => NULL,
          'price_ref_high_new_usd'        => NULL,
          'price_ref_high_new_rmb'        => NULL,
          'price_ref_new_date'        => NULL,
          'price_ref_new_expired'     => NULL,

          'price_ref_use_idr'         => $price_ref_use_after_idr,
          'price_ref_use_usd'         => $price_ref_use_after_usd,
          'price_ref_use_rmb'         => $price_ref_use_after_rmb,
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

      // $this->db->trans_start();
     
      $this->db->where('id_category3', $id)->update('ms_inventory_category3', $dataProcess);
      // $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE) {
        print_r($this->db->error());
        exit;
        // $this->db->trans_rollback();
        // $status  = array(
        //   'pesan'    => 'Failed process data!',
        //   'status'  => 0
        // );
      } else {
        $this->db->trans_commit();
        $status  = array(
          'pesan'    => 'Success process data!',
          'status'  => 1
        );
        // history("Update price supplier raw material: " . $id);
      }
      echo json_encode($status);
    } else {
      $listData = $this->db->get_where('ms_inventory_category3', array('id_category3' => $id))->result();
      // echo '<pre>'; 
      // print_r($listData);
      // echo'</pre>';
      // exit;
      $data = [
        'listData' => $listData,
      ];
      $this->template->set($data);
      $this->template->render('add');
    }
  }

  public function get_kurs(){
    $get_kurs_usd = $this->Price_ref_raw_material_model->get_kurs_usd();
    $get_kurs_rmb = $this->Price_ref_raw_material_model->get_kurs_rmb();

    $hasil = [
      'kurs_usd' => $get_kurs_usd,
      'kurs_rmb' => $get_kurs_rmb
    ];

    echo json_encode($hasil);
  }
}
