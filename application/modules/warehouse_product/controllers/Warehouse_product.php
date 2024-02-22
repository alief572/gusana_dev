<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Warehouse_product extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Warehouse_Product.View';
  protected $addPermission    = 'Warehouse_Product.Add';
  protected $managePermission = 'Warehouse_Product.Manage';
  protected $deletePermission = 'Warehouse_Product.Delete';

  public function __construct()
  {
    parent::__construct();

    $this->load->library(array('Mpdf', 'upload', 'Image_lib'));
    $this->load->model(array(
      'Warehouse_product/warehouse_product_model'
    ));
    $this->template->title('Manage Data Supplier');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');
  }

  //==========================================================================================================
  //============================================STOCK=========================================================
  //==========================================================================================================

  public function index()
  {
    $this->auth->restrict($this->viewPermission);

    $data = $this->warehouse_product_model->get_product_stock();
    $this->template->set('results', [
      'list_product_stock' => $data
    ]);
    $this->template->title('Stock Product');
    $this->template->render('index');
  }

  public function history_product()
  {
    $id_category3 = $this->input->post('id_category3');

    $get_data_stock_product = $this->warehouse_product_model->get_product_stock($id_category3);

    // print_r($get_data_stock_product);
    // exit;

    $this->template->set('results', [
      'data_stock_product' => $get_data_stock_product
    ]);
    $this->template->render('view');
  }
}
