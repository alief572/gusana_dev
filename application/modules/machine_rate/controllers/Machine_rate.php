<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Machine_rate extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Rate_Machine.View';
  protected $addPermission    = 'Rate_Machine.Add';
  protected $managePermission = 'Rate_Machine.Manage';
  protected $deletePermission = 'Rate_Machine.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'Machine_rate/Machine_rate_model'
    ));
    $this->template->title('Manage Product Jenis');
    $this->template->page_icon('fa fa-building-o');
    $this->load->helper('json');

    date_default_timezone_set('Asia/Bangkok');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $session = $this->session->userdata('app_session');

    $this->template->page_icon('fa fa-users');

    $where = [
      'deleted_date' => NULL,
    ];
    $listData = $this->Machine_rate_model->get_data($where);

    $data = [
      'result' =>  $listData,
      'list_asset' =>  get_list_machine(),
      'list_satuan' =>  get_list_satuan()
    ];

    history("View index machine rate");
    $this->template->set($data);
    $this->template->title('Rate Machine');
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
        $last_by    => $this->auth->user_id(),
        $last_date  => date("Y-m-d H:i:s")
      ];

      // print_r($dataProcess);
      // exit;

      $this->db->trans_start();
      if (empty($id)) {
        $this->db->insert('rate_machine', $dataProcess);
      } else {
        $this->db->where('id', $id);
        $this->db->update('rate_machine', $dataProcess);
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
        history($label . " rate machine: " . $kd_mesin);
      }
      echo json_encode($status);
    } else {
      $listData   = $this->db->get_where('rate_machine', array('id' => $id))->result();
      $satuan     = $this->db->get('m_unit')->result();

      $data = [
        'listData' => $listData,
        'list_asset' =>  get_list_machine(),
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
      'deleted_by'     => $this->auth->user_id(),
      'deleted_date'   => date("Y-m-d H:i:s")
    ];

    $this->db->trans_begin();
    $this->db->where('id', $id)->update("rate_machine", $data);

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
      history("Delete rate machine : " . $id);
    }
    echo json_encode($status);
  }

  public function get_list_level1($id = null)
  {
    $code_lv1 = $this->input->post('code_lv1');
    $result  = get_list_inventory_lv2('product');

    if (!empty($result[$code_lv1])) {
      $option  = "<option value='0'>Select Product Category</option>";
      foreach ($result[$code_lv1] as $val => $valx) {
        $sel = ($id == $valx['code_lv2']) ? 'selected' : '';
        $option .= "<option value='" . $valx['code_lv2'] . "' " . $sel . ">" . strtoupper($valx['nama']) . "</option>";
      }
    } else {
      $option  = "<option value='0'>List Not Found</option>";
    }

    $ArrJson  = array(
      'option' => $option
    );
    // exit;
    echo json_encode($ArrJson);
  }

  public function get_list_level3($id = null)
  {
    $code_lv1 = $this->input->post('code_lv1');
    $code_lv2 = $this->input->post('code_lv2');
    $result  = get_list_inventory_lv3('product');

    if (!empty($result[$code_lv1][$code_lv2])) {
      $option  = "<option value='0'>Select Product Jenis</option>";
      foreach ($result[$code_lv1][$code_lv2] as $val => $valx) {
        $sel = ($id == $valx['code_lv3']) ? 'selected' : '';
        $option .= "<option value='" . $valx['code_lv3'] . "' " . $sel . ">" . strtoupper($valx['nama']) . "</option>";
      }
    } else {
      $option  = "<option value='0'>List Not Found</option>";
    }

    $ArrJson  = array(
      'option' => $option
    );
    // exit;
    echo json_encode($ArrJson);
  }

  public function get_list_level4_name()
  {
    $code_lv1 = $this->input->post('code_lv1');
    $code_lv2 = $this->input->post('code_lv2');
    $code_lv3 = $this->input->post('code_lv3');

    $get_level_1 =  get_list_inventory_lv1('product');
    $get_level_2 =  get_list_inventory_lv2('product');
    $get_level_3 =  get_list_inventory_lv3('product');

    $product_type     = (!empty($get_level_1[$code_lv1]['nama'])) ? $get_level_1[$code_lv1]['nama'] : '';
    $product_category = (!empty($get_level_2[$code_lv1][$code_lv2]['nama'])) ? $get_level_2[$code_lv1][$code_lv2]['nama'] : '';
    $product_jenis     = (!empty($get_level_3[$code_lv1][$code_lv2][$code_lv3]['nama'])) ? $get_level_3[$code_lv1][$code_lv2][$code_lv3]['nama'] : '';


    $ArrJson  = array(
      'nama' => strtoupper($product_type . " " . $product_category . "; " . $product_jenis)
    );
    // exit;
    echo json_encode($ArrJson);
  }
}
