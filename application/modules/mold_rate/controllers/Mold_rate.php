<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Mold_rate extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Mold_Rate.View';
  protected $addPermission    = 'Mold_Rate.Add';
  protected $managePermission = 'Mold_Rate.Manage';
  protected $deletePermission = 'Mold_Rate.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Mold_rate/Mold_rate_model'
    ));
    $this->template->title('Manage Product Jenis');
    $this->template->page_icon('fa fa-building-o');
    $this->load->helper('json');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');

    $where = [
      'deleted_date' => NULL,
    ];
    $listData = $this->Mold_rate_model->get_data($where);

    $data = [
      'result' =>  $listData,
      'list_asset' =>  get_list_mould(),
      'list_satuan' =>  get_list_satuan()
    ];

    history("View index mold rate");
    $this->template->set($data);
    $this->template->title('Rate Mold');
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

      $id         = $post['id'];
      $kd_mesin   = $post['kd_mesin'];
      $kapasitas   = $post['kapasitas'];
      $id_unit   = $post['id_unit'];
      $harga_mesin       = str_replace(',', '', $post['harga_mesin']);
      $est_manfaat       = str_replace(',', '', $post['est_manfaat']);
      $depresiasi_bulan       = str_replace(',', '', $post['depresiasi_bulan']);
      $used_hour_month       = str_replace(',', '', $post['used_hour_month']);
      $biaya_mesin       = str_replace(',', '', $post['biaya_mesin']);

      $last_by    = (!empty($id)) ? 'updated_by' : 'created_by';
      $last_date  = (!empty($id)) ? 'updated_date' : 'created_date';
      $label      = (!empty($id)) ? 'Edit' : 'Add';

      $dataProcess = [
        'kd_mesin'  => $kd_mesin,
        'kapasitas'  => $kapasitas,
        'id_unit'  => $id_unit,
        'harga_mesin'  => $harga_mesin,
        'est_manfaat'      => $est_manfaat,
        'depresiasi_bulan'  => $depresiasi_bulan,
        'used_hour_month'  => $used_hour_month,
        'biaya_mesin'  => $biaya_mesin,
        $last_by    => $this->id_user,
        $last_date  => $this->datetime
      ];

      // print_r($dataProcess);
      // exit;

      $this->db->trans_start();
      if (empty($id)) {
        $this->db->insert('rate_mold', $dataProcess);
      } else {
        $this->db->where('id', $id);
        $this->db->update('rate_mold', $dataProcess);
      }
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
        history($label . " rate mold: " . $kd_mesin);
      }
      echo json_encode($status);
    } else {
      $listData   = $this->db->get_where('rate_mold', array('id' => $id))->result();
      $satuan     = $this->db->get_where('m_unit')->result();

      $data = [
        'listData' => $listData,
        'list_asset' =>  get_list_mould(),
        'satuan' => $satuan
      ];
      $this->template->set($data);
      $this->template->render('add');
    }
  }

  public function delete()
  {
    $this->auth->restrict($this->deletePermission);

    $id = $this->input->post('id');
    $data = [
      'deleted_by'     => $this->id_user,
      'deleted_date'   => $this->datetime
    ];

    $this->db->trans_begin();
    $this->db->where('id', $id)->update("rate_mold", $data);

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
      history("Delete rate mold : " . $id);
    }
    echo json_encode($status);
  }
}
