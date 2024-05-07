<?php

// if (!defined('BASEPATH/')) {
//     exit('No direct script access allowed');
// }

/*
 * @author Hikmat Aolia
 * @copyright Copyright (c) 2023, Hikmat Aolia
 *
 * This is controller for Master Employee
 */

class Penawaran extends Admin_Controller
{
    protected $viewPermission     = 'Penawaran.View';
    protected $addPermission      = 'Penawaran.Add';
    protected $managePermission = 'Penawaran.Manage';
    protected $deletePermission = 'Penawaran.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Penawaran/Penawaran_model',
            'Request_develop/Request_develop_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Penawaran Harga (报价)');
        $this->template->page_icon('fas fa-user-tie');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function getData()
    {
        $requestData    = $_REQUEST;
        $status         = $requestData['status'];
        $search         = $requestData['search']['value'];
        $column         = $requestData['order'][0]['column'];
        $dir            = $requestData['order'][0]['dir'];
        $start          = $requestData['start'];
        $length         = $requestData['length'];

        $where = "";
        // $where = " AND `status` <> 'D'";

        $string = $this->db->escape_like_str($search);
        $sql = "SELECT *
        FROM ms_penawaran WHERE 1=1 AND (sts NOT IN ('loss', 'so_created') OR sts IS NULL)
        AND (
            id_penawaran LIKE '%$string%' OR
            id_quote LIKE '%$string%' OR
            nm_cust LIKE '%$string%' OR
            nm_marketing LIKE '%$string%' OR
            nilai_penawaran LIKE '%$string%' OR
            tgl_penawaran LIKE '%$string%' OR
            revisi LIKE '%$string%' OR
            keterangan LIKE '%$string%'
        )";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id_penawaran',
            1 => 'id_penawaran'
        );

        $sql .= " ORDER BY dibuat_tgl DESC ";
        $sql .= " LIMIT " . $start . " ," . $length . " ";
        $query  = $this->db->query($sql);


        $data  = array();
        $urut1  = 1;
        $urut2  = 0;



        foreach ($query->result_array() as $row) {
            $buttons = '';
            $total_data     = $totalData;
            $start_dari     = $start;
            $asc_desc       = $dir;
            if (
                $asc_desc == 'asc'
            ) {
                $nomor = $urut1 + $start_dari;
            }
            if (
                $asc_desc == 'desc'
            ) {
                $nomor = ($total_data - $start_dari) - $urut2;
            }

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-eye"></i></button>';

            $edit         = '<a href="penawaran/add/' . str_replace('/', '-', $row['id_penawaran']) . '" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>';

            $revisi         = '<a href="javascript:void(0);" class="btn btn-success btn-sm revisi" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '" data-toggle="tooltip" title="Revisi"><i class="fa fa-edit"></i></a>';

            $delete     = '<button type="button" class="btn btn-danger btn-sm btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-trash"></i></button>';

            $print = '<button type="button" class="btn btn-info btn-sm print_penawaran" data-toggle="tooltip" title="Print" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-print"></i></button>';

            $send = '<button type="button" class="btn btn-success btn-sm send_penawaran" data-toggle="tooltip" title="Send" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-arrow-right"></i></button>';

            $loss = '<button type="button" class="btn btn-danger btn-sm btn-sm loss_penawaran" data-toggle="tooltip" title="Loss" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-minus"></i></button>';

            $request = '<a href="penawaran/request_approval/' . str_replace('/', '-', $row['id_penawaran']) . '" class="btn btn-warning btn-sm request_approval" data-toggle="tooltip" title="Request Approval" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-user"></i></a>';

            $create_so =  '<button type="button" class="btn btn-warning btn-sm btn-sm create_so" data-toggle="tooltip" title="Create SO" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-plus"></i></button>';

            $print_po = '<button type="button" class="btn btn-sm btn-warning print_po" data-toggle="tooltip" title="Print PO" data-id="' . str_replace('/', '-', $row['id_penawaran']) . '"><i class="fa fa-print"></i></button>';

            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete . "&nbsp;" . $print . "&nbsp;" . $send . "&nbsp;" . $request . "&nbsp;" . $print_po;

            if ($row['sts'] == '' || $row['sts'] == null || $row['sts'] == 'rejected') {
                $buttons = $edit . ' ' . $request . ' ' . $print;
            }
            if ($row['sts'] == 'approved') {
                $buttons = $print . ' ' . $view . ' ' . $send;
            }
            if ($row['sts'] == 'request_approval') {
                $buttons = $view;
            }
            if ($row['sts'] == 'send') {
                $buttons = $revisi . ' ' . $print . ' ' . $create_so . ' ' . $loss;
            }

            $status = '';
            if ($row['sts'] == 'send') {
                $status = '<div class="badge badge-success text-light"><span>Sent <br> (' . date('d F Y', strtotime($row['send_date'])) . ')</span></div>';
            } else if ($row['sts'] == 'request_approval') {
                $status = '<div class="badge badge-info">Request Approval</div>';
            } else if ($row['sts'] == 'approved') {
                $status = '<div class="badge badge-success text-light">Approved</div>';
            } else if ($row['sts'] == 'rejected') {
                $status = '<div class="badge badge-danger text-light">Reject</div>';
            } else {
                $status = '<div class="badge badge-warning text-light">Draft</div>';
            }

            if ($row['id_quote'] !== null && $row['id_quote'] !== '') {
                $id = $row['id_quote'];
            } else {
                $id = $row['id_penawaran'];
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $id;
            $nestedData[]  = $row['nm_cust'];
            $nestedData[]  = $row['nm_marketing'];
            $nestedData[]  = number_format($row['nilai_penawaran']);
            $nestedData[]  = date('d F Y', strtotime($row['tgl_penawaran']));
            $nestedData[]  = ($row['sts'] == 'approved' || $row['sts'] == 'rejected' || $row['sts'] == 'send') ? $row['keterangan_app'] : $row['keterangan'];
            $nestedData[]  = $row['revisi'];
            $nestedData[]  = $status;
            $nestedData[]  = $buttons;
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

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Quotation | 报价');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);

        $id_penawaran = $this->uri->segment(3);
        if ($this->uri->segment(3) == 'new') {
            $id_penawaran = $this->auth->user_id();
        } else {
            // $id_penawaran = str_replace('-', '/', $id_penawaran);
            // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);
        }

        $get_cust = $this->db->get_where('customers', ['deleted_by' => null])->result();
        $get_produk = $this->db->get_where('ms_product_category3', ['aktif' => 1])->result();
        $get_sales = $this->db->get_where('employees', ['deleted_at' => null])->result();

        $this->db->select('a.*, b.nama_mandarin');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product', 'left');
        $this->db->where('a.id_penawaran', $id_penawaran);
        $this->db->order_by('a.id ASC');
        $get_penawaran_detail = $this->db->get()->result();

        $this->db->select('SUM(a.total_harga) AS ttl_harga');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id_penawaran]);
        $get_total_harga = $this->db->get()->row();

        $check_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_penawaran])->num_rows();

        if ($check_penawaran < 1) {
            $data = [
                'list_customer' => $get_cust,
                'list_produk' => $get_produk,
                'list_sales' => $get_sales,
                'list_penawaran_detail' => $get_penawaran_detail,
                'total_harga' => $get_total_harga->ttl_harga,
                'id_penawaran' => $id_penawaran
            ];
        } else {
            $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_penawaran])->row();

            $list_pic_cust = '';
            $get_pic_cust = $this->db->get_where('customer_pic', ['id_customer' => $get_penawaran->id_cust])->result();


            $data = [
                'list_customer' => $get_cust,
                'list_produk' => $get_produk,
                'list_sales' => $get_sales,
                'list_penawaran_detail' => $get_penawaran_detail,
                'total_harga' => $get_total_harga->ttl_harga,
                'id_penawaran' => $id_penawaran,
                'data_penawaran' => $get_penawaran,
                'list_pic' => $get_pic_cust
            ];
        }


        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $this->auth->restrict($this->managePermission);
        $id = str_replace('-', '/', $id);

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id])->row();
        $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $id])->result();

        $get_cust = $this->db->get_where('customers', ['deleted_by' => null])->result();
        $get_produk = $this->db->get_where('ms_product_category3', ['aktif' => 1])->result();
        $get_sales = $this->db->get_where('employees', ['deleted_at' => null])->result();

        $get_pic = $this->db->get('customer_pic')->result();

        $this->db->select('SUM(a.total_harga) AS ttl_harga');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id]);
        $get_total_harga = $this->db->get()->row();

        $this->template->set([
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail,
            'list_customer' => $get_cust,
            'list_produk' => $get_produk,
            'list_sales' => $get_sales,
            'list_pic' => $get_pic,
            'list_penawaran_detail' => $get_penawaran_detail,
            'total_harga' => $get_total_harga->ttl_harga
        ]);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->managePermission);
        // $id = str_replace('-', '/', $id);
        // $id = str_replace('SP/', 'SP-', $id);

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id])->row();
        // echo '<pre>';
        // print_r($id);
        // echo'</pre>';
        // exit;
        $this->db->select('a.*, b.nama_mandarin, c.nm_packaging');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product', 'left');
        $this->db->join('master_packaging c', 'c.id = b.packaging', 'left');
        $this->db->where('a.id_penawaran', $id);
        $get_penawaran_detail = $this->db->get()->result();

        $this->template->set([
            'id_penawaran' => $id,
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail
        ]);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $session = $this->session->userdata('app_session');

        $post = $this->input->post();

        $valid_sales = 1;
        $valid_create_so = 1;

        $this->db->trans_begin();
        if (isset($post['loss_penawaran'])) {
            $id_penawaran = $post['id_penawaran'];

            $this->db->update('ms_penawaran', ['sts' => 'loss', 'loss_date' => date('Y-m-d H:i:s'), 'keterangan_app' => $post['keterangan_loss']], ['id_penawaran' => $id_penawaran]);
        } else if (isset($post['create_so'])) {

            $id_penawaran = $post['id_penawaran'];
            // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);

            $config['upload_path'] = './uploads/po'; //path folder
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf|xlsx|xls'; //type yang dapat diakses bisa anda sesuaikan
            $config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
            $config['encrypt_name'] = FALSE; // Encrypt the uploaded file's name.
            $config['remove_spaces'] = FALSE; // Remove spaces from the file name.

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('upload_po')) {
                $data_upload_po = 'Upload Error';
            } else {
                $data_upload_po = $this->upload->data();
                $data_upload_po = '/uploads/po/' . $data_upload_po['file_name'];
            }

            if (!$this->upload->do_upload('upload_penawaran_deal')) {
                $data_upload_penawaran_deal = 'Upload Error';
            } else {
                $data_upload_penawaran_deal = $this->upload->data();
                $data_upload_penawaran_deal = '/uploads/po/' . $data_upload_penawaran_deal['file_name'];
            }

            if ($data_upload_po == 'Upload Error' && $data_upload_penawaran_deal == 'Upload Error') {
                $valid_create_so = 0;
            } else {

                $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $id_penawaran])->result();
                foreach ($get_penawaran_detail as $penawaran_detail) :
                    $get_stock = $this->db->get_where('ms_stock_product', ['id_product' => $penawaran_detail->id_product])->row();

                    $this->db->select('a.id_product_refer');
                    $this->db->from('ms_product_category3 a');
                    $this->db->where('a.id_category3', $penawaran_detail->id_product);
                    $get_refer_product = $this->db->get()->row();


                    if ($get_refer_product->id_product_refer !== '') {
                        $get_stock = $this->db->get_where('ms_stock_product', ['id_product' => $get_refer_product->id_product_refer])->row();

                        if (count($get_stock) > 0) {
                            $stock = $get_stock->qty_asli;
                        } else {
                            $stock = 0;
                        }
                    } else {
                        if (count($get_stock) > 0) {
                            $stock = $get_stock->qty_asli;
                        } else {
                            $stock = 0;
                        }
                    }

                // $this->db->update('ms_stock_product', ['qty_asli' => ($stock - $penawaran_detail->weight)], ['id_product' => $penawaran_detail->id_product]);
                endforeach;

                $this->db->update('ms_penawaran', [
                    'deliver_date' => $post['deliver_date'],
                    'upload_po' => $data_upload_po,
                    'upload_penawaran_deal' => $data_upload_penawaran_deal,
                    'sts' => 'so_created',
                    'tgl_create_so' => date('Y-m-d')
                ], [
                    'id_penawaran' => $id_penawaran
                ]);
            }
        } else {
            $check_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $post['id_penawaran']])->num_rows();

            $get_marketing = $this->db->get_where('employees', ['id' => $post['sales_marketing']])->row();
            $get_customer = $this->db->get_where('customers', ['id_customer' => $post['customer']])->row();
            $get_pic_cust = $this->db->get_where('customer_pic', ['id' => $post['pic_cust']])->row();

            $this->db->select('SUM(a.total_harga) AS ttl_harga');
            $this->db->from('ms_penawaran_detail a');
            $this->db->where(['a.id_penawaran' => $post['id_penawaran']]);
            $get_total_harga = $this->db->get()->row();

            $nilai_disc = 0;
            // if ($post['disc_val'] > 0 && $post['disc_val'] !== '') {
            //     $nilai_disc = $post['disc_val'];
            // }
            // if ($post['disc_per'] > 0 && $post['disc_per'] !== '') {
            //     $nilai_disc = ($get_total_harga->ttl_harga * $post['disc_per'] / 100);
            // }

            $ppn_num = (($get_total_harga->ttl_harga + str_replace(',', '', $post['biaya_pengiriman']) - ($nilai_disc)) * $post['persen_ppn'] / 100);

            $grand_total = ($get_total_harga->ttl_harga + str_replace(',', '', $post['biaya_pengiriman']) - $nilai_disc + $ppn_num);

            $valid_stock = 1;

            if ($check_penawaran > 0) {
                $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $post['id_penawaran']])->row();
                $revisi = $get_penawaran->revisi + 1;

                if ($post['req_approval'] == 1) {
                    $this->db->update('ms_penawaran', [
                        'id_marketing' => $post['sales_marketing'],
                        'nm_marketing' => $get_marketing->name,
                        'id_cust' => $post['customer'],
                        'nm_cust' => $get_customer->customer_name,
                        'id_pic_cust' => $post['pic_cust'],
                        'nm_pic_cust' => $get_pic_cust->name,
                        'nilai_penawaran' => $get_total_harga->ttl_harga,
                        'tgl_penawaran' => $post['tgl_penawaran'],
                        'ppn_type' => $post['ppn_type'],
                        'total' => $get_total_harga->ttl_harga,
                        'ppn_persen' => $post['persen_ppn'],
                        'ppn_num' => $ppn_num,
                        'biaya_pengiriman' => str_replace(',', '', $post['biaya_pengiriman']),
                        'grand_total' => $grand_total,
                        'sts' => 'request_approval',
                        'keterangan' => $post['keterangan'],
                        'keterangan_request_approval' => $post['keterangan_request_approval'],
                        'deliver_type' => $post['deliver_type'],
                        'address_cust' => $post['address_cust'],
                        'dari_tmp' => $post['dari_tmp'],
                        'ke_tmp' => $post['ke_tmp'],
                        'diubah_oleh' => $this->auth->user_id(),
                        'diubah_tgl' => date('Y-m-d H:i:s')
                    ], [
                        'id_penawaran' => $post['id_penawaran']
                    ]);
                } else {
                    if ($get_penawaran->sts == 'reject') {
                        $this->db->update('ms_penawaran', [
                            'id_marketing' => $post['sales_marketing'],
                            'nm_marketing' => $get_marketing->name,
                            'id_cust' => $post['customer'],
                            'nm_cust' => $get_customer->customer_name,
                            'id_pic_cust' => $post['pic_cust'],
                            'nm_pic_cust' => $get_pic_cust->name,
                            'nilai_penawaran' => $get_total_harga->ttl_harga,
                            'tgl_penawaran' => $post['tgl_penawaran'],
                            'ppn_type' => $post['ppn_type'],
                            'total' => $get_total_harga->ttl_harga,
                            'disc_num' => $post['disc_val'],
                            'disc_persen' => $post['disc_per'],
                            'nilai_disc' => $nilai_disc,
                            'ppn_persen' => $post['persen_ppn'],
                            'ppn_num' => $ppn_num,
                            'biaya_pengiriman' => str_replace(',', '', $post['biaya_pengiriman']),
                            'grand_total' => $grand_total,
                            'deliver_type' => $post['deliver_type'],
                            'address_cust' => $post['address_cust'],
                            'dari_tmp' => $post['dari_tmp'],
                            'ke_tmp' => $post['ke_tmp'],
                            'sts' => '',
                            'keterangan' => $post['keterangan'],
                            'diubah_oleh' => $this->auth->user_id(),
                            'diubah_tgl' => date('Y-m-d H:i:s')
                        ], [
                            'id_penawaran' => $post['id_penawaran']
                        ]);
                    } else {
                        if ($get_penawaran->sts == 'send') {
                            $this->db->update('ms_penawaran', [
                                'id_marketing' => $post['sales_marketing'],
                                'nm_marketing' => $get_marketing->name,
                                'id_cust' => $post['customer'],
                                'nm_cust' => $get_customer->customer_name,
                                'id_pic_cust' => $post['pic_cust'],
                                'nm_pic_cust' => $get_pic_cust->name,
                                'nilai_penawaran' => $get_total_harga->ttl_harga,
                                'tgl_penawaran' => $post['tgl_penawaran'],
                                'ppn_type' => $post['ppn_type'],
                                'total' => $get_total_harga->ttl_harga,
                                'ppn_persen' => $post['persen_ppn'],
                                'ppn_num' => $ppn_num,
                                'biaya_pengiriman' => str_replace(',', '', $post['biaya_pengiriman']),
                                'grand_total' => $grand_total,
                                'deliver_type' => $post['deliver_type'],
                                'address_cust' => $post['address_cust'],
                                'dari_tmp' => $post['dari_tmp'],
                                'ke_tmp' => $post['ke_tmp'],
                                'revisi' => ($get_penawaran->revisi + 1),
                                'keterangan' => $post['keterangan'],
                                'diubah_oleh' => $this->auth->user_id(),
                                'diubah_tgl' => date('Y-m-d H:i:s')
                            ], [
                                'id_penawaran' => $post['id_penawaran']
                            ]);
                        } else {
                            $this->db->update('ms_penawaran', [
                                'id_marketing' => $post['sales_marketing'],
                                'nm_marketing' => $get_marketing->name,
                                'id_cust' => $post['customer'],
                                'nm_cust' => $get_customer->customer_name,
                                'id_pic_cust' => $post['pic_cust'],
                                'nm_pic_cust' => $get_pic_cust->name,
                                'nilai_penawaran' => $get_total_harga->ttl_harga,
                                'tgl_penawaran' => $post['tgl_penawaran'],
                                'ppn_type' => $post['ppn_type'],
                                'total' => $get_total_harga->ttl_harga,
                                'ppn_persen' => $post['persen_ppn'],
                                'ppn_num' => $ppn_num,
                                'biaya_pengiriman' => str_replace(',', '', $post['biaya_pengiriman']),
                                'grand_total' => $grand_total,
                                'deliver_type' => $post['deliver_type'],
                                'address_cust' => $post['address_cust'],
                                'dari_tmp' => $post['dari_tmp'],
                                'ke_tmp' => $post['ke_tmp'],
                                'keterangan' => $post['keterangan'],
                                'diubah_oleh' => $this->auth->user_id(),
                                'diubah_tgl' => date('Y-m-d H:i:s')
                            ], [
                                'id_penawaran' => $post['id_penawaran']
                            ]);
                        }
                    }
                }


                // Logging
                $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

                $desc = "Update Penawaran Data - " . $post['id_penawaran'];
                $device_name = $this->agent->mobile(); // Returns the mobile device name
                if ($this->agent->is_browser()) {
                    $device_name = $this->agent->browser(); // Returns the browser name
                } elseif ($this->agent->is_robot()) {
                    $device_name = $this->agent->robot(); // Returns the robot/crawler name
                } elseif ($this->agent->is_mobile()) {
                    $device_name = $this->agent->mobile(); // Returns the mobile device name
                } else {
                    $device_name = 'Unidentified Device';
                }

                $id_user = $this->auth->user_id();
                $id_menu = $get_menu->id;
                $nm_menu = $get_menu->title;
                $device_type = $this->agent->platform();
                $os_type = $this->agent->browser();
                log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);
            } else {

                $kode_sales = '';

                $this->db->select('a.kode_angka, a.kode_nama');
                $this->db->from('ms_kode_sales a');
                $this->db->where('a.id_sales =', $post['sales_marketing']);
                $this->db->where('a.kode_nama !=', '');
                $this->db->where('a.kode_nama !=', null);
                $this->db->where('a.kode_angka !=', '');
                $this->db->where('a.kode_angka !=', null);
                $check_kode_sales = $this->db->get()->num_rows();

                // echo '<pre>'; 
                // print_r($check_kode_sales);
                // echo'</pre>';
                // exit;

                if ($check_kode_sales > 0) {

                    // echo '<pre>'; 
                    // print_r($check_kode_sales);
                    // echo'</pre>';
                    // exit;

                    $this->db->select('a.kode_angka, a.kode_nama');
                    $this->db->from('ms_kode_sales a');
                    $this->db->where('a.id_sales =', $post['sales_marketing']);
                    $this->db->where('a.kode_nama !=', '');
                    $this->db->where('a.kode_nama !=', null);
                    $this->db->where('a.kode_angka !=', '');
                    $this->db->where('a.kode_angka !=', null);
                    $get_kode_sales = $this->db->get()->row();



                    $get_kode_sales = $this->db->get_where('ms_kode_sales', ['id_sales' => $post['sales_marketing']])->row();
                    $get_customer = $this->db->query('SELECT * FROM customers WHERE id_customer = "'.$post['customer'].'" ORDER BY id_customer ASC')->row_array();

                    $get_nm_customer = $this->db->get_where('customers', ['id_customer' => $post['customer']])->row();

                    $this->db->select('a.*');
                    $this->db->from('ms_penawaran a');
                    $this->db->where('id_cust', $post['customer']);
                    $this->db->where('id_marketing', $post['sales_marketing']);
                    $this->db->where('id_penawaran LIKE', '%' . date('Ymd', strtotime($post['tgl_penawaran'])) . '%');
                    $num_pesanan = $this->db->get()->num_rows();

                    $jum_pesanan = sprintf('%02s', ($num_pesanan + 1));

                    // print_r($jum_pesanan);
                    // exit;

                    $kode_cust = $get_customer['kode_customer'];
                   


                    $id_quote = 'G' . $get_kode_sales->kode_angka . '-' . sprintf('%03s', $kode_cust) . '-' . $jum_pesanan . '-' . date('Ymd', strtotime($post['tgl_penawaran']));

                    $this->db->insert('ms_penawaran', [
                        'id_penawaran' => $id_quote,
                        'id_quote' => $id_quote,
                        'id_ppb' => $id_quote,
                        'id_penawaran' => $id_quote,
                        'id_marketing' => $post['sales_marketing'],
                        'nm_marketing' => $get_marketing->name,
                        'id_cust' => $post['customer'],
                        'nm_cust' => $get_nm_customer->customer_name,
                        'id_pic_cust' => $post['pic_cust'],
                        'nm_pic_cust' => $get_pic_cust->name,
                        'nilai_penawaran' => $get_total_harga->ttl_harga,
                        'tgl_penawaran' => $post['tgl_penawaran'],
                        'ppn_type' => $post['ppn_type'],
                        'total' => $get_total_harga->ttl_harga,
                        'ppn_persen' => $post['persen_ppn'],
                        'ppn_num' => $ppn_num,
                        'grand_total' => $grand_total,
                        'deliver_type' => $post['deliver_type'],
                        'address_cust' => $post['address_cust'],
                        'dari_tmp' => $post['dari_tmp'],
                        'ke_tmp' => $post['ke_tmp'],
                        'keterangan' => $post['keterangan'],
                        'biaya_pengiriman' => str_replace(',', '', $post['biaya_pengiriman']),
                        'dibuat_oleh' => $this->auth->user_id(),
                        'dibuat_tgl' => date('Y-m-d H:i:s')
                    ]);

                    $get_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $this->auth->user_id()])->result();
                    foreach ($get_detail as $detail) :
                        $check_stock = $this->db->get_where('ms_stock_product', ['id_product' => $detail->id_product])->num_rows();
                        if ($check_stock > 0 && $valid_stock == 1) {
                            $get_stock = $this->db->get_where('ms_stock_product', ['id_product' => $detail->id_product])->row();
                            if ($get_stock->qty_asli >= $detail->weight) {
                                // $qty_akhir = ($get_stock->qty_asli - $detail->weight);
                                // $this->db->update('ms_stock_product', ['qty_asli' => $qty_akhir], ['id_product' => $detail->id_product]);
                            } else {
                                // $valid_stock = 0;
                            }
                        }
                    endforeach;

                    $this->db->update('ms_penawaran_detail', ['id_penawaran' => $id_quote], ['id_penawaran' => $this->auth->user_id()]);

                    // Logging
                    $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

                    $desc = "Insert New Penawaran Data - " . $id_quote;
                    $device_name = $this->agent->mobile(); // Returns the mobile device name
                    if ($this->agent->is_browser()) {
                        $device_name = $this->agent->browser(); // Returns the browser name
                    } elseif ($this->agent->is_robot()) {
                        $device_name = $this->agent->robot(); // Returns the robot/crawler name
                    } elseif ($this->agent->is_mobile()) {
                        $device_name = $this->agent->mobile(); // Returns the mobile device name
                    } else {
                        $device_name = 'Unidentified Device';
                    }

                    $id_user = $this->auth->user_id();
                    $id_menu = $get_menu->id;
                    $nm_menu = $get_menu->title;
                    $device_type = $this->agent->platform();
                    $os_type = $this->agent->browser();
                    log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);
                } else {
                    $valid_sales = 0;
                }
            }
        }

        // if ($this->db->trans_status() === FALSE || $valid_stock !== 1) {
        if ($this->db->trans_status() === FALSE || $valid_sales !== 1 || $valid_create_so !== 1) {
            $this->db->trans_rollback();
            $msg = 'Failed save data Penawaran.  Please try again.';
            // if ($valid_stock !== 1) {
            //     $msg = 'Maaf, ada stock barang yang kurang dari permintaan.';
            // }
            if ($valid_sales !== 1) {
                $msg = 'Maaf, sales yang anda pilih belum diinput kode sales nya !';
            }
            if (isset($valid_create_so) && $valid_create_so !== 1) {
                $msg = 'Maaf, mohon pastikan anda sudah upload bukti PO atau Penawaran Deal nya !';
            }
            $return    = array(
                'msg'        => $msg,
                'status'    => 0
            );
            $keterangan     = "FAILED save data Penawaran " . (isset($kode_pn) ? $kode_pn : $post['id_penawaran']);

            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = (isset($kode_pn) ? $kode_pn : $post['id_penawaran']);
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();

            $msg = 'Success Save data Penawaran.';
            if (isset($post['req_approval']) && $post['req_approval'] == 1) {
                $msg = 'Request Approval success';
            }
            if (isset($post['loss_penawaran'])) {
                $msg = 'Selamat, penawaran telah masuk ke Loss Penawaran';
            }
            if (isset($post['create_so'])) {
                $msg = 'Selamat, Create SO telah berhasil';
            }
            $return    = array(
                'msg'        => $msg,
                'status'    => 1
            );
            $keterangan     = "Selamat, penawaran telah masuk ke Loss Penawaran " . (isset($kode_pn) ? $kode_pn : $post['id_penawaran']);
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = (isset($kode_pn) ? $kode_pn : $post['id_penawaran']);
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('m_divisi', ['id' => $id])->row_array();

        $this->db->trans_begin();
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete Penawaran Data " . $data['id'] . " - " . $data['divisi'];
        $device_name = $this->agent->mobile(); // Returns the mobile device name
        if ($this->agent->is_browser()) {
            $device_name = $this->agent->browser(); // Returns the browser name
        } elseif ($this->agent->is_robot()) {
            $device_name = $this->agent->robot(); // Returns the robot/crawler name
        } elseif ($this->agent->is_mobile()) {
            $device_name = $this->agent->mobile(); // Returns the mobile device name
        } else {
            $device_name = 'Unidentified Device';
        }

        $id_user = $this->auth->user_id();
        $id_menu = $get_menu->id;
        $nm_menu = $get_menu->title;
        $device_type = $this->agent->platform();
        $os_type = $this->agent->browser();
        log_history($id_user, $id_menu, $nm_menu, $device_name, $_SERVER['REMOTE_ADDR'], $desc);

        $sql = $this->db->delete('m_divisi', ['id' => $id]);
        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Penawaran. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Penawaran.',
                'status'    => 1
            );
            $keterangan     = "Delete data Penawaran " . $data['id'] . ", Penawaran : " . $data['divisi'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function customer()
    {
        $customer = $this->input->post('customer');

        $get_cust = $this->db->get_where('customers', ['id_customer' => $customer])->row();

        $no_telp_cust = $get_cust->telephone;
        $address_cust = $get_cust->address;

        $list_pic_cust = array();
        $list_pic_cust[] = '<option value="">- PIC Customer -</option>';
        $get_pic_cust = $this->db->get_where('customer_pic', ['id_customer' => $customer])->result();
        foreach ($get_pic_cust as $pic_cust) {
            $list_pic_cust[] = '<option value="' . $pic_cust->id . '">' . $pic_cust->name . '</option>';
        }

        echo json_encode([
            'list_pic_cust' => $list_pic_cust,
            'no_telp_cust' => $no_telp_cust,
            'address_cust' => $address_cust
        ]);
    }

    public function get_produk_detail()
    {
        $produk = $this->input->post('produk');

        $get_produk = $this->db->select('a.*, a.curing_agent id_curing_agent, b.nm_packaging, c.nama as ral_code, d.nama curing_agent');
        $get_produk = $this->db->from('ms_product_category3 a');
        $get_produk = $this->db->join('master_packaging b', 'b.id = a.packaging', 'left');
        $get_produk = $this->db->join('ms_product_category2 c', 'c.id_category2 = a.id_category2', 'left');
        $get_produk = $this->db->join('ms_product_category3 d', 'd.id_category3 = a.curing_agent', 'left');
        $get_produk = $this->db->where(['a.id_category3' => $produk]);
        $get_produk = $this->db->group_by('a.id_category3');
        $get_produk = $this->db->get()->row();

        $get_lot_size = $this->db->select('a.qty_hopper, a.id');
        $get_lot_size = $this->db->from('ms_bom a');
        $get_lot_size = $this->db->where(['a.id_product' => $produk]);
        $get_lot_size = $this->db->order_by('a.qty_hopper', 'desc');
        $get_lot_size = $this->db->get()->result();
        if (count($get_lot_size) < 1) {
            // $get_produk = $this->db->get_where('ms_product_category3', ['id_category3' => $produk])->row();

            $get_lot_size = $this->db->select('a.qty_hopper, a.id');
            $get_lot_size = $this->db->from('ms_bom a');
            $get_lot_size = $this->db->where(['a.id_product' => $get_produk->id_product_refer]);
            $get_lot_size = $this->db->order_by('a.qty_hopper', 'desc');
            $get_lot_size = $this->db->get()->result();
        }

        $list_lot_size = array();
        foreach ($get_lot_size as $lot_size) :
            $list_lot_size[] = '<option value="' . $lot_size->id . '">' . $lot_size->qty_hopper . '</option>';
        endforeach;

        $check_product_set = $this->db->get_where('ms_product_category3', ['id_category3' => $produk])->row();

        if ($check_product_set->curing_agent !== '') {
            $num_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $check_product_set->id_product_refer])->num_rows();
        } else {
            $num_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $produk])->num_rows();
        }

        if ($num_free_stock < 1) {
            $free_stock = 0;
        } else {
            if ($check_product_set->curing_agent !== '') {
                $get_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $check_product_set->id_product_refer])->row();
                $get_booking_stock = $this->db->query('
                    SELECT
                        SUM(a.weight) AS ttl_booking_stock
                    FROM
                        ms_penawaran_detail a 
                        LEFT JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran
                    WHERE
                        a.id_product = "' . $produk . '" AND
                        b.sts = "so_created" AND
                        b.sts_do IS NULL
                ')->row();
            } else {
                $get_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $produk])->row();
                $get_booking_stock = $this->db->query('
                    SELECT
                        SUM(a.weight) AS ttl_booking_stock
                    FROM
                        ms_penawaran_detail a 
                        LEFT JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran
                    WHERE
                        a.id_product = "' . $produk . '" AND
                        b.sts = "so_created" AND
                        b.sts_do IS NULL
                ')->row();
            }

            $free_stock = (($get_free_stock->qty_asli - $get_booking_stock->ttl_booking_stock) / $get_produk->konversi);
        }

        $this->db->select('SUM(a.qty) AS ttl_booking');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_penawaran b', 'b.id_penawaran = a.id_penawaran');
        $this->db->where('a.id_product', $produk);
        $this->db->where('b.sts =', 'so_created');
        $this->db->where('b.sts_do !=', 'do_created');
        $booking_stock = $this->db->get()->row();

        $free_stock -= $booking_stock->ttl_booking;

        $konversi = $get_produk->konversi;
        // if ($get_produk->id_curing_agent !== '') {
        //     $get_curing_agent = $this->db->query('SELECT konversi FROM ms_product_category3 WHERE id_category3 = "' . $get_produk->id_curing_agent . '"')->row();

        //     // print_r($get_produk->id_curing_agent);
        //     // exit;

        //     $konversi += $get_curing_agent->konversi;
        // }

        echo json_encode([
            'list_lot_size' => $list_lot_size,
            'kode_produk' => $get_produk->product_code,
            'konversi' => $konversi,
            'spesifikasi_kemasan' => $konversi . ' ' . $get_produk->unit_nm,
            'ral_code' => $get_produk->ral_code,
            'free_stock' => $free_stock,
            'unit_nm' => $get_produk->nm_packaging,
            'supporting_curing_agent' => $get_produk->curing_agent,
            'curing_agent_pack_spec' => $get_produk->curing_agent_konversi . ' Kg'
        ]);
    }

    public function hitung_harga_satuan()
    {
        $qty_detail = $this->input->post('qty_detail');
        $produk_detail = $this->input->post('produk_detail');
        $lot_size_detail = $this->input->post('lot_size_detail');

        $price_list = 0;
        $check_biaya_bom = $this->db->get_where('ms_bom', ['id_product' => $produk_detail, 'id' => $lot_size_detail, 'sts_price_list' => 1])->num_rows();
        if ($check_biaya_bom > 0) {
            $get_biaya_bom = $this->db->get_where('ms_bom', ['id_product' => $produk_detail, 'id' => $lot_size_detail, 'sts_price_list' => 1])->row();
            $price_list = $get_biaya_bom->price_list;
        }

        echo json_encode(['price_list' => number_format($price_list)]);
    }

    public function get_harga_satuan()
    {
        $produk_detail = $this->input->post('produk_detail');
        $lot_size_detail = $this->input->post('lot_size_detail');

        $harga_satuan = 0;
        $check_harga_satuan = $this->db->get_where('ms_bom', ['id_product' => $produk_detail, 'id' => $lot_size_detail, 'sts_price_list' => 1])->num_rows();
        if ($check_harga_satuan) {
            $get_harga_satuan = $this->db->get_where('ms_bom', ['id_product' => $produk_detail, 'id' => $lot_size_detail, 'sts_price_list' => 1])->row();
            $harga_satuan = $get_harga_satuan->price_list;
        }

        echo json_encode([
            'harga_satuan' => number_format($harga_satuan)
        ]);
    }

    public function hitung_weight()
    {
        $produk_detail = $this->input->post('produk_detail');
        $lot_size_detail = $this->input->post('lot_size_detail');
        $qty_detail = $this->input->post('qty');

        $get_produk = $this->db->get_where('ms_product_category3', ['id_category3' => $produk_detail])->row();
        $konversi = $get_produk->konversi;
        if ($get_produk->curing_agent !== '') {
            $get_curing_agent = $this->db->query('SELECT konversi FROM ms_product_category3 WHERE id_category3 = "' . $get_produk->curing_agent . '"')->row();

            // print_r($get_produk->id_curing_agent);
            // exit;

            $konversi += $get_curing_agent->konversi;
        }

        $weight = ($qty_detail * $konversi);

        echo json_encode(['weight' => $weight, 'weight_form' => number_format($weight)]);
    }

    public function hitung_total_harga()
    {
        $produk_detail = $this->input->post('produk_detail');
        $lot_size_detail = $this->input->post('lot_size_detail');
        $qty_detail = $this->input->post('qty');
        $harga_satuan = $this->input->post('harga_satuan');

        $get_produk = $this->db->get_where('ms_product_category3', ['id_category3' => $produk_detail])->row();

        $konversi = $get_produk->konversi;
        if ($get_produk->curing_agent !== '') {
            $get_curing_agent = $this->db->query('SELECT konversi FROM ms_product_category3 WHERE id_category3 = "' . $get_produk->curing_agent . '"')->row();

            // print_r($get_produk->id_curing_agent);
            // exit;

            $konversi += $get_curing_agent->konversi;
        }

        $total_harga = ($harga_satuan * ($qty_detail * $konversi));

        echo json_encode(['total_harga' => $total_harga, 'total_harga2' => number_format($total_harga)]);
    }

    public function add_penawaran_detail()
    {
        $id_penawaran = $this->input->post('id_penawaran');
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);
        $produk_detail = $this->input->post('produk_detail');
        $lot_size_detail = $this->input->post('lot_size_detail');
        $qty_detail = $this->input->post('qty_detail');

        $get_produk = $this->db->select('a.*, b.nm_packaging, c.nama as ral_code');
        $get_produk = $this->db->from('ms_product_category3 a');
        $get_produk = $this->db->join('master_packaging b', 'b.id = a.packaging', 'left');
        $get_produk = $this->db->join('ms_product_category2 c', 'c.id_category2 = a.id_category2', 'left');
        $get_produk = $this->db->where(['a.id_category3' => $produk_detail]);
        $get_produk = $this->db->get()->row();

        $get_qty_hopper = $this->db->select('a.qty_hopper');
        $get_qty_hopper = $this->db->from('ms_bom a');
        $get_qty_hopper = $this->db->where(['a.id' => $lot_size_detail]);
        $get_qty_hopper = $this->db->get()->row();

        $valid_stock = 1;

        $free_stock = 0;

        $check_stock = $this->db->select('a.qty_asli');
        $check_stock = $this->db->from('ms_stock_product a');
        $check_stock = $this->db->where(['id_product' => $produk_detail]);
        $check_stock = $this->db->get()->num_rows();
        if ($check_stock > 0) {
            $get_free_stock = $this->db->select('a.qty_asli');
            $get_free_stock = $this->db->from('ms_stock_product a');
            $get_free_stock = $this->db->where(['id_product' => $produk_detail]);
            $get_free_stock = $this->db->get()->row();
            $free_stock = ($get_free_stock->qty_asli / $get_produk->konversi);
        }

        // if ($free_stock < $qty_detail) {
        //     $valid_stock = 0;
        // }

        $price_list = $this->input->post('harga_satuan');

        $code_pen_det = $this->db->query("SELECT MAX(id) as max_id FROM ms_penawaran_detail WHERE id LIKE '%PD/" . date('Y') . "/" . date('m') . "/" . date('d') . "%'")->row();
        $kodeBarang = $code_pen_det->max_id;
        $urutan = (int) substr($kodeBarang, 14, 6);
        $urutan++;
        $tahun = date('Y/m/d/');
        $huruf = "PD/";
        $kode_pd = $huruf . $tahun . sprintf("%06s", $urutan);

        $this->db->trans_begin();

        $this->db->insert('ms_penawaran_detail', [
            'id' => $kode_pd,
            'id_penawaran' => $id_penawaran,
            'id_product' => $produk_detail,
            'nm_product' => $get_produk->nama,
            'kode_product' => $get_produk->product_code,
            'konversi' => $get_produk->konversi,
            'packaging' => $get_produk->nm_packaging,
            'ral_code' => $get_produk->ral_code,
            'qty' => $qty_detail,
            'weight' => ($qty_detail * $get_produk->konversi),
            'harga_satuan' => $price_list,
            'total_harga' => ($price_list * ($qty_detail * $get_produk->konversi)),
            'free_stock' => $free_stock,
            'lot_size' => $get_qty_hopper->qty_hopper,
            'dibuat_oleh' => $this->auth->user_id(),
            'dibuat_tgl' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->trans_status() === FALSE || $valid_stock !== 1) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $hasil = array();

        $this->db->select('a.*');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['id_penawaran' => $id_penawaran]);
        $get_penawaran_detail = $this->db->get()->result();

        $ttl_harga = 0;

        $x = 1;
        foreach ($get_penawaran_detail as $penawaran_detail) :
            $hasil[] = '
                <tr>
                    <td class="text-center">' . $x . '</td>
                    <td>' . $penawaran_detail->nm_product . '</td>
                    <td>' . $penawaran_detail->lot_size . '</td>
                    <td>' . $penawaran_detail->kode_product . '</td>
                    <td>' . $penawaran_detail->konversi . ' ' . $penawaran_detail->packaging . '</td>
                    <td>' . $penawaran_detail->ral_code . '</td>
                    <td class="text-right">' . number_format($penawaran_detail->qty) . '</td>
                    <td class="text-right">' . number_format($penawaran_detail->weight) . '</td>
                    <td class="text-right">
                        <input type="text" name="harga_satuan_' . str_replace('/', '-', $penawaran_detail->id) . '" id="" class="form-control form-control-sm autonum harga_satuan" data-id="' . str_replace('/', '-', $penawaran_detail->id) . '" value="' . number_format($penawaran_detail->harga_satuan) . '">
                    </td>
                    <td class="text-right">' . number_format($penawaran_detail->free_stock) . '</td>
                    <td class="text-right">' . number_format($penawaran_detail->total_harga) . '</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm del_detail" data-id="' . str_replace('/', '-', $penawaran_detail->id) . '">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            ';
            $x++;

            $ttl_harga += $penawaran_detail->total_harga;
        endforeach;

        echo json_encode([
            'hasil' => $hasil,
            'valid_stock' => $valid_stock,
            'ttl_harga' => $ttl_harga
        ]);
    }

    public function hitung_disc()
    {
        $id_penawaran = $this->input->post('id_penawaran');
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);
        // $disc_val = $this->input->post('disc_val');
        // $disc_type = $this->input->post('disc_type');

        $this->db->select('SUM(a.total_harga) AS ttl_harga');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id_penawaran]);
        $get_ttl_harga = $this->db->get()->row();

        $nilai_disc = 0;
        // if ($disc_type == "val") {
        //     $nilai_disc = $disc_val;
        // }
        // if ($disc_type == "per") {
        //     $nilai_disc = ($get_ttl_harga->ttl_harga * $disc_val / 100);
        // }

        echo json_encode(['nilai_disc' => $nilai_disc, 'nilai_disc2' => number_format($nilai_disc)]);
    }

    public function hitung_all()
    {
        $id_penawaran = $this->input->post('id_penawaran');
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $disc_val = $this->input->post('disc_val');
        // $disc_per = $this->input->post('disc_per');
        $persen_ppn = $this->input->post('persen_ppn');
        $ppn_type = $this->input->post('ppn_type');
        $biaya_pengiriman = $this->input->post('biaya_pengiriman');

        $this->db->select('SUM(a.total_harga) AS ttl_harga');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id_penawaran]);
        $get_ttl_harga = $this->db->get()->row();

        $total_harga = $get_ttl_harga->ttl_harga;

        $nilai_disc = 0;
        // if ($disc_val > 0 && $disc_val !== '') {
        //     $nilai_disc = $disc_val;
        // }
        // if ($disc_per > 0 && $disc_per !== '') {
        //     $nilai_disc = ($get_ttl_harga->ttl_harga * $disc_per / 100);
        // }

        $total_after_disc = ($get_ttl_harga->ttl_harga + $biaya_pengiriman - $nilai_disc);

        $nilai_ppn = (($total_after_disc * $persen_ppn / 100));
        if ($ppn_type !== '1') {
            $nilai_ppn = 0;
        }

        $total_grand_total = ($total_after_disc + $nilai_ppn);

        echo json_encode([
            'total_harga' => $total_harga,
            'total_harga2' => number_format($total_harga),
            'nilai_disc' => $nilai_disc,
            'nilai_disc2' => number_format($nilai_disc),
            'total_after_disc' => $total_after_disc,
            'total_after_disc2' => number_format($total_after_disc),
            'nilai_ppn' => $nilai_ppn,
            'nilai_ppn2' => number_format($nilai_ppn),
            'total_grand_total' => $total_grand_total,
            'total_grand_total2' => number_format($total_grand_total)
        ]);
    }

    public function del_detail()
    {
        $id = $this->input->post('id');
        $id = str_replace('-', '/', $id);
        $id_penawaran = $this->input->post('id_penawaran');
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);

        $this->db->trans_begin();

        $this->db->delete('ms_penawaran_detail', ['id' => $id]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        $hasil = array();

        $this->db->select('a.*');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id_penawaran]);
        $get_penawaran_detail = $this->db->get()->result();

        $ttl_harga = 0;

        $x = 1;
        foreach ($get_penawaran_detail as $penawaran_detail) :
            $hasil[] = '
                <tr>
                    <td class="text-center">' . $x . '</td>
                    <td>' . $penawaran_detail->nm_product . '</td>
                    <td>' . $penawaran_detail->lot_size . '</td>
                    <td>' . $penawaran_detail->kode_product . '</td>
                    <td>' . $penawaran_detail->konversi . ' ' . $penawaran_detail->packaging . '</td>
                    <td>' . $penawaran_detail->ral_code . '</td>
                    <td class="text-right">' . number_format($penawaran_detail->qty) . '</td>
                    <td class="text-right">' . number_format($penawaran_detail->weight) . '</td>
                    <td class="text-right">
                        <input type="text" name="harga_satuan_' . str_replace('/', '-', $penawaran_detail->id) . '" id="" class="form-control form-control-sm autonum harga_satuan" data-id="' . str_replace('/', '-', $penawaran_detail->id) . '" value="' . number_format($penawaran_detail->harga_satuan) . '">
                    </td>
                    <td class="text-right">' . number_format($penawaran_detail->free_stock) . '</td>
                    <td class="text-right">' . number_format($penawaran_detail->total_harga) . '</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger del_detail" data-id="' . str_replace('/', '-', $penawaran_detail->id) . '">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            ';
            $x++;

            $ttl_harga += $penawaran_detail->total_harga;
        endforeach;

        echo json_encode([
            'hasil' => $hasil,
            'ttl_harga' => $ttl_harga
        ]);
    }

    public function request_approval($id)
    {
        $this->auth->restrict($this->managePermission);
        // $id = str_replace('-', '/', $id);
        // $id = str_replace('SP/', 'SP-', $id);

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id])->row();
        $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $id])->result();

        $get_cust = $this->db->get_where('customers', ['deleted_by' => null])->result();
        $get_produk = $this->db->get_where('ms_product_category3', ['aktif' => 1])->result();
        $get_sales = $this->db->get_where('employees', ['deleted_at' => null])->result();

        $get_pic = $this->db->get('customer_pic')->result();

        $this->db->select('SUM(a.total_harga) AS ttl_harga');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id]);
        $get_total_harga = $this->db->get()->row();

        $get_cust = $this->db->get_where('customers', ['deleted_by' => null])->result();
        $get_produk = $this->db->get_where('ms_product_category3', ['aktif' => 1])->result();
        $get_sales = $this->db->get_where('employees', ['deleted_at' => null])->result();

        $this->db->select('a.*, b.nama_mandarin');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product', 'left');
        $this->db->where('a.id_penawaran', $id);
        $get_penawaran_detail = $this->db->get()->result();

        $this->db->select('SUM(a.total_harga) AS ttl_harga');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['a.id_penawaran' => $id]);
        $get_total_harga = $this->db->get()->row();

        $this->template->set([
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail,
            'list_customer' => $get_cust,
            'list_produk' => $get_produk,
            'list_sales' => $get_sales,
            'list_pic' => $get_pic,
            'list_penawaran_detail' => $get_penawaran_detail,
            'total_harga' => $get_total_harga->ttl_harga,
            'request_approval' => 1,
            'id_penawaran' => $id
        ]);
        $this->template->render('form');
    }

    public function save_product()
    {
        $post = $this->input->post();

        $id_detail = str_replace('-', '/', $post['id_detail']);

        $id_penawaran = $this->input->post('id_penawaran');
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);
        if ($id_penawaran == 'new') {
            $id_penawaran = $this->auth->user_id();
        }
        $produk_detail = $this->input->post('produk_detail');
        $lot_size_detail = $this->input->post('lot_size_detail');
        $qty_detail = $this->input->post('qty');
        $keterangan = $this->input->post('keterangan');
        $unit = $this->input->post('unit');
        $curing_agent_pack_spec = $this->input->post('curing_agent_pack_spec');
        $supporting_curing_agent = $this->input->post('supporting_curing_agent');

        $get_produk = $this->db->select('a.*, b.nm_packaging, c.nama as ral_code');
        $get_produk = $this->db->from('ms_product_category3 a');
        $get_produk = $this->db->join('master_packaging b', 'b.id = a.packaging', 'left');
        $get_produk = $this->db->join('ms_product_category2 c', 'c.id_category2 = a.id_category2', 'left');
        $get_produk = $this->db->where(['a.id_category3' => $produk_detail]);
        $get_produk = $this->db->get()->row();

        $konversi = $get_produk->konversi;
        if ($get_produk->curing_agent !== '') {
            $get_curing_agent = $this->db->get_where('ms_product_category3', ['id_category3' => $get_produk->curing_agent])->row();

            $konversi += $get_curing_agent->konversi;
        }

        $this->db->select('a.nama');
        $this->db->from('ms_product_category3 a');
        $this->db->where('a.nama =', $supporting_curing_agent);
        $check_curing_agent = $this->db->get()->num_rows();
        if ($check_curing_agent > 0) {
            $this->db->select('a.nama');
            $this->db->from('ms_product_category3 a');
            $this->db->where('a.nama =', $supporting_curing_agent);
            $get_curing_agent = $this->db->get()->row();
            $nm_curing_agent = $get_curing_agent->nama;
        } else {
            $nm_curing_agent = '';
        }

        $get_qty_hopper = $this->db->select('a.qty_hopper');
        $get_qty_hopper = $this->db->from('ms_bom a');
        $get_qty_hopper = $this->db->where(['a.id' => $lot_size_detail]);
        $get_qty_hopper = $this->db->get()->row();

        $valid_stock = 1;

        $free_stock = 0;



        $price_list = $this->input->post('harga_satuan');
        $price_list = str_replace(',', '', $price_list);

        $code_pen_det = $this->db->query("SELECT MAX(id) as max_id FROM ms_penawaran_detail WHERE id LIKE '%PD/" . date('Y') . "/" . date('m') . "/" . date('d') . "%'")->row();
        $kodeBarang = $code_pen_det->max_id;
        $urutan = (int) substr($kodeBarang, 14, 6);
        $urutan++;
        $tahun = date('Y/m/d/');
        $huruf = "PD/";
        $kode_pd = $huruf . $tahun . sprintf("%06s", $urutan);
        if ($post['id_detail'] !== 'new' && $post['id_detail'] !== '') {
            $kode_pd = $post['id_detail'];
        }

        $this->db->trans_begin();

        if ($post['id_detail'] !== "") {
            // echo '1';
            // exit;
            if (count($get_qty_hopper) > 0) {
                $this->db->update('ms_penawaran_detail', [
                    'id_product' => $produk_detail,
                    'nm_product' => $get_produk->nama,
                    'kode_product' => $get_produk->product_code,
                    'konversi' => $konversi,
                    'packaging' => $get_produk->nm_packaging,
                    'ral_code' => $get_produk->ral_code,
                    'qty' => $qty_detail,
                    'weight' => ($qty_detail * $konversi),
                    'harga_satuan' => $price_list,
                    'total_harga' => ($price_list * ($qty_detail * $konversi)),
                    'lot_size' => $get_qty_hopper->qty_hopper,
                    'keterangan' => $keterangan,
                    'id_curing_agent' => $supporting_curing_agent,
                    'nm_curing_agent' => $nm_curing_agent,
                    'package_spec_curing_agent' => $curing_agent_pack_spec,
                ], [
                    'id' => $id_detail
                ]);
            } else {
                $this->db->update('ms_penawaran_detail', [
                    'id_product' => $produk_detail,
                    'nm_product' => $get_produk->nama,
                    'kode_product' => $get_produk->product_code,
                    'konversi' => $konversi,
                    'packaging' => $get_produk->nm_packaging,
                    'ral_code' => $get_produk->ral_code,
                    'qty' => $qty_detail,
                    'weight' => ($qty_detail * $konversi),
                    'harga_satuan' => $price_list,
                    'total_harga' => ($price_list * ($qty_detail * $konversi)),
                    'keterangan' => $keterangan,
                    'id_curing_agent' => $supporting_curing_agent,
                    'nm_curing_agent' => $nm_curing_agent,
                    'package_spec_curing_agent' => $curing_agent_pack_spec
                ], [
                    'id' => $id_detail
                ]);
            }
        } else {
            if (count($get_qty_hopper) > 0) {
                $this->db->insert('ms_penawaran_detail', [
                    'id' => $kode_pd,
                    'id_penawaran' => $id_penawaran,
                    'id_product' => $produk_detail,
                    'nm_product' => $get_produk->nama,
                    'kode_product' => $get_produk->product_code,
                    'konversi' => $konversi,
                    'packaging' => $get_produk->nm_packaging,
                    'ral_code' => $get_produk->ral_code,
                    'qty' => $qty_detail,
                    'weight' => ($qty_detail * $konversi),
                    'harga_satuan' => $price_list,
                    'total_harga' => ($price_list * ($qty_detail * $konversi)),
                    'free_stock' => $free_stock,
                    'lot_size' => $get_qty_hopper->qty_hopper,
                    'keterangan' => $keterangan,
                    'id_curing_agent' => $supporting_curing_agent,
                    'nm_curing_agent' => $nm_curing_agent,
                    'package_spec_curing_agent' => $curing_agent_pack_spec,
                    'dibuat_oleh' => $this->auth->user_id(),
                    'dibuat_tgl' => date('Y-m-d H:i:s')
                ]);
            } else {
                $this->db->insert('ms_penawaran_detail', [
                    'id' => $kode_pd,
                    'id_penawaran' => $id_penawaran,
                    'id_product' => $produk_detail,
                    'nm_product' => $get_produk->nama,
                    'kode_product' => $get_produk->product_code,
                    'konversi' => $konversi,
                    'packaging' => $get_produk->nm_packaging,
                    'ral_code' => $get_produk->ral_code,
                    'qty' => $qty_detail,
                    'weight' => ($qty_detail * $konversi),
                    'harga_satuan' => $price_list,
                    'total_harga' => ($price_list * ($qty_detail * $konversi)),
                    'free_stock' => $free_stock,
                    'keterangan' => $keterangan,
                    'id_curing_agent' => $supporting_curing_agent,
                    'nm_curing_agent' => $nm_curing_agent,
                    'package_spec_curing_agent' => $curing_agent_pack_spec,
                    'dibuat_oleh' => $this->auth->user_id(),
                    'dibuat_tgl' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $valid = 1;
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $valid = 0;

            $msg = 'Sorry, please try again !';
        } else {
            $this->db->trans_commit();

            $msg = 'Your product has been added !';
        }

        $hasil = array();

        $this->db->select('a.*');
        $this->db->from('ms_penawaran_detail a');
        $this->db->where(['id_penawaran' => $id_penawaran]);
        $get_penawaran_detail = $this->db->get()->result();

        echo json_encode([
            'valid' => $valid,
            'hasil' => $hasil,
            'msg' => $msg
        ]);
    }

    public function edit_detail()
    {
        $post = $this->input->post();

        $this->db->select('a.*, b.unit_nm, c.nm_packaging');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product', 'left');
        $this->db->join('master_packaging c', 'c.id = b.packaging', 'left');
        $this->db->where('a.id', str_replace('-', '/', str_replace(',', '', $post['id'])));
        $get_penawaran_detail = $this->db->get()->row();

        $id_product = $get_penawaran_detail->id_product;

        $this->db->select('a.*');
        $this->db->from('ms_bom a');
        $this->db->where('a.id_product', $id_product);
        $get_list_lot_size = $this->db->get()->result();

        if (count($get_list_lot_size) < 1) {
            $get_refer_code = $this->db->get_where('ms_product_category3', ['id_category3' => $id_product])->row();

            $this->db->select('a.*');
            $this->db->from('ms_bom a');
            $this->db->where('a.id_product', $get_refer_code->id_product_refer);
            $get_list_lot_size = $this->db->get()->result();
        }

        $this->db->select('a.*');
        $this->db->from('ms_bom a');
        $this->db->where(['a.id_product' => $id_product, 'qty_hopper' => $get_penawaran_detail->lot_size]);
        $get_lot_size = $this->db->get()->row();

        if (count($get_lot_size) < 1) {
            $get_refer_code = $this->db->get_where('ms_product_category3', ['id_category3' => $id_product])->row();

            $this->db->select('a.*');
            $this->db->from('ms_bom a');
            $this->db->where(['a.id_product' => $get_refer_code->id_product_refer, 'qty_hopper' => $get_penawaran_detail->lot_size]);
            $get_lot_size = $this->db->get()->row();
        }


        $list_lot_size = '';
        foreach ($get_list_lot_size as $lot_size) :
            $list_lot_size .= '<option value="' . $lot_size->id . '">' . $lot_size->qty_hopper . '</option>';
        endforeach;

        $get_produk = $this->db->get_where('ms_product_category3', ['id_category3' => $id_product])->row();

        $num_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $id_product])->num_rows();

        $check_product_set = $this->db->get_where('ms_product_category3', ['id_category3' => $id_product])->row();

        if ($check_product_set->curing_agent !== '') {
            $num_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $check_product_set->id_product_refer])->num_rows();
        } else {
            $num_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $id_product])->num_rows();
        }

        if ($num_free_stock < 1) {
            $free_stock = 0;
        } else {
            if ($check_product_set->curing_agent !== '') {
                $get_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $check_product_set->id_product_refer])->row();
                $get_booking_stock = $this->db->query('
                    SELECT
                        SUM(a.weight) AS ttl_booking_stock
                    FROM
                        ms_penawaran_detail a 
                        LEFT JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran
                    WHERE
                        a.id_product = "' . $check_product_set->id_product_refer . '" AND
                        b.sts = "so_created" AND
                        b.sts_do IS NULL
                ')->row();
            } else {
                $get_free_stock = $this->db->get_where('ms_stock_product', ['id_product' => $id_product])->row();
                $get_booking_stock = $this->db->query('
                    SELECT
                        SUM(a.weight) AS ttl_booking_stock
                    FROM
                        ms_penawaran_detail a 
                        LEFT JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran
                    WHERE
                        a.id_product = "' . $id_product . '" AND
                        b.sts = "so_created" AND
                        b.sts_do IS NULL
                ')->row();
            }

            $free_stock = (($get_free_stock->qty_asli - $get_booking_stock->ttl_booking_stock) / $get_produk->konversi);
        }

        $this->db->select('SUM(a.qty) AS ttl_booking');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_penawaran b', 'b.id_penawaran = a.id_penawaran');
        $this->db->where('a.id_product', $id_product);
        $this->db->where('b.sts =', 'so_created');
        $this->db->where('b.sts_do !=', 'do_created');
        $booking_stock = $this->db->get()->row();

        $free_stock -= $booking_stock->ttl_booking;

        echo json_encode([
            'id' => str_replace('/', '-', $get_penawaran_detail->id),
            'id_product' => $get_penawaran_detail->id_product,
            'supporting_curing_agent' => $get_penawaran_detail->nm_curing_agent,
            'curing_agent_pack_spec' => $get_penawaran_detail->package_spec_curing_agent,
            'qty' => $get_penawaran_detail->qty,
            'ral_code' => $get_penawaran_detail->ral_code,
            'product_code' => $get_penawaran_detail->kode_product,
            'unit' => $get_penawaran_detail->nm_packaging,
            'packaging_spec' => $get_penawaran_detail->konversi . ' ' . $get_penawaran_detail->nm_packaging,
            'weight' => $get_penawaran_detail->weight,
            'list_lot_size' => $list_lot_size,
            'lot_size_detail' => ((isset($get_lot_size->id)) ? $get_lot_size->id : ''),
            'harga_satuan' => $get_penawaran_detail->harga_satuan,
            'total_harga' => $get_penawaran_detail->total_harga,
            'keterangan' => $get_penawaran_detail->keterangan,
            'free_stock' => $free_stock,
            'request_produksi' => ($free_stock - $get_penawaran_detail->qty)
        ]);
    }

    public function refresh_table_product()
    {
        $id_penawaran = $this->input->post('id_penawaran');
        if ($id_penawaran == 'new' || $id_penawaran == '' || $id_penawaran == null) {
            $id_penawaran = $this->auth->user_id();
        } else {
            // $id_penawaran = str_replace('-', '/', $id_penawaran);
            // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);
        }

        // print_r($id_penawaran);
        // exit;

        $check_penawaran = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $id_penawaran])->num_rows();
        if ($check_penawaran > 0) {
            $data_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_penawaran])->row();

            $hasil = '
                <thead>
                    <tr>
                        <th class="text-center">
                            <span>
                                序号 <br>
                                No.
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                产品名称 <br>
                                Product Name
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                产品型号 <br>
                                Product Code
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                数量 <br>
                                Qty
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                重量 <br>
                                Weight (Kg)
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                单价 <br>
                                Harga Satuan (Rp/Kg)
                            </span>
                        </th>
                        <th class="text-center">
                            <span>
                                金额 <br>
                                Total Harga
                            </span>
                        </th>
                        <th class="text-center">
                            <span>备注 <br> Keterangan</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="list_detail_penawaran">
            ';
            $get_penawaran_detail = $this->db->query("SELECT a.* FROM ms_penawaran_detail a WHERE a.id_penawaran = '" . $id_penawaran . "' ORDER BY a.id ASC")->result();

            $x = 0;
            $ttl_harga = 0;
            foreach ($get_penawaran_detail as $penawaran_detail) : $x++;

                $this->db->select('a.nama_mandarin');
                $this->db->from('ms_product_category3 a');
                $this->db->where('a.id_category3', $penawaran_detail->id_product);
                $get_nama_mandarin = $this->db->get()->row();

                $hasil = $hasil . '<tr>
                    <td class="text-center">' . $x . '</td>
                    <td class="text-center">
                        <span class="text-danger">' . $get_nama_mandarin->nama_mandarin . '</span> <br> ' . $penawaran_detail->nm_product . '
                    </td>
                    <td class="text-center">' . $penawaran_detail->kode_product . '</td>
                    <td class="text-center">' . number_format($penawaran_detail->qty) . '</td>
                    <td class="text-center">' . number_format($penawaran_detail->weight) . '</td>
                    <td class="text-center">
                        ' . number_format($penawaran_detail->harga_satuan) . '
                    </td>
                    <td class="text-center">' . number_format($penawaran_detail->total_harga) . '</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-warning text-light edit_detail" data-id="' . str_replace('/', '-', $penawaran_detail->id) . '">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger del_detail" data-id="' . str_replace('/', '-', $penawaran_detail->id) . '">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>';
                $ttl_harga += $penawaran_detail->total_harga;
            endforeach;

            $readonly_disc_val = '';
            $readonly_disc_persen = '';

            if (isset($data_penawaran)) {
                if ($data_penawaran->disc_persen > 0) {
                    $readonly_disc_val = 'readonly';
                }
            }
            if (isset($data_penawaran)) {
                if ($data_penawaran->disc_num > 0) {
                    $readonly_disc_persen = 'readonly';
                }
            }

            $disc_val = 0;
            $disc_persen = 0;
            $nilai_disc = 0;

            $nilai_after_disc = 0;
            $nilai_ppn = 0;

            $biaya_pengiriman = 0;
            $dari_tmp = '';
            $ke_tmp = '';
            if (isset($data_penawaran)) {
                $disc_val = $data_penawaran->disc_num;
                $disc_persen = $data_penawaran->disc_persen;
                if ($disc_val > 0) {
                    $nilai_disc = ($disc_val);
                }
                if ($disc_persen > 0) {
                    $nilai_disc = ($ttl_harga * $disc_persen / 100);
                }

                $biaya_pengiriman = $data_penawaran->biaya_pengiriman;

                $nilai_after_disc = ($ttl_harga + $biaya_pengiriman - $nilai_disc);

                if ($data_penawaran->ppn_type == 1) {
                    $nilai_ppn = ($nilai_after_disc * 11 / 100);
                }


                $dari_tmp = $data_penawaran->dari_tmp;
                $ke_tmp = $data_penawaran->ke_tmp;
            }

            $hasil = $hasil . '</tbody>';
            $hasil = $hasil . '
            <tbody>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-left" colspan="3">Subtotal <span class="text-danger">(小计)</span></td>
                    <td class="text-right total_all_harga">' . number_format($ttl_harga) . '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="">Biaya Pengiriman <span class="text-danger">(交付成本)</span></td>
                    <td class="">
                        <input type="text" name="biaya_pengiriman" id="" class="form-control  text-right biaya_pengiriman autonum" placeholder="Input Biaya Pengiriman" value="' . $biaya_pengiriman . '">
                    </td>
                    <td>
                        <input type="text" name="dari_tmp" id="" class="form-control" value="' . $dari_tmp . '" placeholder="- Dari -">
                    </td>
                    <td>
                        <input type="text" name="ke_tmp" id="" class="form-control" value="' . $ke_tmp . '" placeholder="- Ke -">
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-center">Discount <span class="text-danger">(折扣)</span></td>
                    <td>
                        <table border="0">
                            <tr>
                                <td>
                                    <input type="text" name="disc_val" id="" class="form-control  text-right autonum disc_input disc_val" data-disc_type="val" placeholder="Input Disc Value (Rp)" ' . $readonly_disc_val . ' value="' . $disc_val . '">
                                </td>
                                <td>(Rp)</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table border="0">
                            <tr>
                                <td>
                                    <input type="number" name="disc_per" id="" class="form-control  text-right disc_input disc_per" data-disc_type="per" step="0.01" placeholder="Input Percent Disc (%)" ' . $readonly_disc_persen . ' value="' . $disc_persen . '">
                                </td>
                                <td>%</td>
                            </tr>
                        </table>
                        
                    </td>
                    <td class="text-right disc_harga">
                        ' . number_format($nilai_disc) . '
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="" colspan="3">Price After Discount <span class="text-danger">(折扣后价格)</span></td>
                    <td class="text-right total_after_disc">
                        ' . number_format($nilai_after_disc) . '
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="">PPN</td>
                    <td class="" colspan="2">
                        <input type="number" name="persen_ppn" id="" class="form-control  text-right persen_ppn" placeholder="Input PPN Percent" value="11" readonly>
                    </td>
                    <td class="text-right nilai_ppn">
                        ' . number_format($nilai_ppn) . '
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="" colspan="3">
                        <span style="font-weight:bold;">Grand Total <span class="text-danger">(总计)</span></span>
                    </td>
                    <td class="text-right total_grand_total">
                        ' . number_format($ttl_harga - $nilai_disc + $nilai_ppn + $biaya_pengiriman) . '
                    </td>
                </tr>
            </tbody>
            ';
        } else {
            $hasil = '
            <thead>
            <tr>
                <th class="text-center">
                    <span>
                        序号 <br>
                        No.
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        产品名称 <br>
                        Product Name
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        产品型号 <br>
                        Product Code
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        数量 <br>
                        Qty
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        重量 <br>
                        Weight (Kg)
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        单价 <br>
                        Harga Satuan (Rp/Kg)
                    </span>
                </th>
                <th class="text-center">
                    <span>
                        金额 <br>
                        Total Harga
                    </span>
                </th>
                <th class="text-center">
                    <span>备注 <br> Keterangan</span>
                </th>
            </tr>
        </thead>
        <tbody class="list_detail_penawaran">
        </tbody>
        <tbody>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-left" colspan="3">Subtotal <span class="text-danger">(小计)</span></td>
                    <td class="text-right total_all_harga">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="">Biaya Pengiriman <span class="text-danger">(交付成本)</span></td>
                    <td class="">
                        <input type="text" name="biaya_pengiriman" id="" class="form-control  text-right biaya_pengiriman autonum" placeholder="Input Biaya Pengiriman" value="">
                    </td>
                    <td>
                        <input type="text" name="dari_tmp" id="" class="form-control" value="" placeholder="- Dari -">
                    </td>
                    <td>
                        <input type="text" name="ke_tmp" id="" class="form-control" value="" placeholder="- Ke -">
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-center">Discount <span class="text-danger">(折扣)</span></td>
                    <td>
                        <table border="0">
                            <tr>
                                <td>
                                    <input type="text" name="disc_val" id="" class="form-control  text-right autonum disc_input disc_val" data-disc_type="val" placeholder="Input Disc Value (Rp)"  value="">
                                </td>
                                <td>(Rp)</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table border="0">
                            <tr>
                                <td>
                                    <input type="number" name="disc_per" id="" class="form-control  text-right disc_input disc_per" data-disc_type="per" step="0.01" placeholder="Input Percent Disc (%)"  value="">
                                </td>
                                <td>%</td>
                            </tr>
                        </table>
                        
                    </td>
                    <td class="text-right disc_harga">
                      
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="" colspan="3">Price After Discount <span class="text-danger">(折扣后价格)</span></td>
                    <td class="text-right total_after_disc">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="">PPN</td>
                    <td class="" colspan="2">
                        <input type="number" name="persen_ppn" id="" class="form-control  text-right persen_ppn" placeholder="Input PPN Percent" value="11" readonly>
                    </td>
                    <td class="text-right nilai_ppn">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="" colspan="3">
                        <span style="font-weight:bold;">Grand Total <span class="text-danger">(总计)</span></span>
                    </td>
                    <td class="text-right total_grand_total">
                        
                    </td>
                </tr>
            </tbody>
            ';
        }

        echo json_encode([
            'hasil' => $hasil
        ]);
    }

    public function print_penawaran($id_penawaran)
    {
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);

        $get_penawaran = $this->db->query("SELECT * FROM ms_penawaran WHERE id_penawaran = '" . $id_penawaran . "' OR id_quote = '" . $id_penawaran . "'")->row();
        // $get_penawaran_detail = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_penawaran])->result();

        $this->db->select('a.*, c.nama_mandarin, c.unit_nm, d.nm_packaging, e.nama_mandarin as mandarin_ral_code');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.curing_agent = a.id_product', 'left');
        $this->db->join('ms_product_category3 c', 'c.id_category3 = a.id_product', 'left');
        $this->db->join('master_packaging d', 'd.id = c.packaging', 'left');
        $this->db->join('ms_product_category2 e', 'e.id_category2 = c.id_category2', 'left');
        $this->db->where('a.id_penawaran =', $id_penawaran);
        $this->db->group_by('a.id_product');
        $get_penawaran_detail = $this->db->get()->result();

        $pic_phone = '';
        $this->db->select('a.phone_number');
        $this->db->from('customer_pic a');
        $this->db->where('a.id =', $get_penawaran->id_pic_cust);
        $check_pic_phone = $this->db->get()->num_rows();
        if ($check_pic_phone > 0) {
            $this->db->select('a.phone_number');
            $this->db->from('customer_pic a');
            $this->db->where('a.id =', $get_penawaran->id_pic_cust);
            $get_pic_phone = $this->db->get()->row();
            $pic_phone = $get_pic_phone->phone_number;
        }



        $this->auth->restrict($this->viewPermission);
        $this->template->title('Print Penawaran');
        $this->template->set([
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail,
            'pic_phone' => $pic_phone
        ]);
        $this->template->render('print_penawaran');
    }

    public function print_po_cust($id_penawaran){
        // $id_penawaran = str_replace('-', '/', $id_penawaran);
        // $id_penawaran = str_replace('SP/', 'SP-', $id_penawaran);

        $get_penawaran = $this->db->query("SELECT * FROM ms_penawaran WHERE id_penawaran = '" . $id_penawaran . "' OR id_quote = '" . $id_penawaran . "'")->row();
        // $get_penawaran_detail = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_penawaran])->result();

        $this->db->select('a.*, c.nama_mandarin, c.unit_nm, d.nm_packaging, e.nama_mandarin as mandarin_ral_code');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.curing_agent = a.id_product', 'left');
        $this->db->join('ms_product_category3 c', 'c.id_category3 = a.id_product', 'left');
        $this->db->join('master_packaging d', 'd.id = c.packaging', 'left');
        $this->db->join('ms_product_category2 e', 'e.id_category2 = c.id_category2', 'left');
        $this->db->where('a.id_penawaran =', $id_penawaran);
        $this->db->group_by('a.id_product');
        $get_penawaran_detail = $this->db->get()->result();

        $pic_phone = '';
        $this->db->select('a.phone_number');
        $this->db->from('customer_pic a');
        $this->db->where('a.id =', $get_penawaran->id_pic_cust);
        $check_pic_phone = $this->db->get()->num_rows();
        if ($check_pic_phone > 0) {
            $this->db->select('a.phone_number');
            $this->db->from('customer_pic a');
            $this->db->where('a.id =', $get_penawaran->id_pic_cust);
            $get_pic_phone = $this->db->get()->row();
            $pic_phone = $get_pic_phone->phone_number;
        }



        $this->auth->restrict($this->viewPermission);
        $this->template->title('Print Penawaran');
        $this->template->set([
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail,
            'pic_phone' => $pic_phone
        ]);
        $this->template->render('print_po_cust');
    }

    public function send_penawaran()
    {
        $post = $this->input->post();

        $id = $post['id'];

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id])->row();

        $get_kode_sales = $this->db->get_where('ms_kode_sales', ['id_sales' => $get_penawaran->id_marketing])->row();
        $get_customer = $this->db->get_where('customers', ['deleted_by' => null])->result();

        $this->db->select('a.*');
        $this->db->from('ms_penawaran a');
        $this->db->where('id_cust', $get_penawaran->id_cust);
        $this->db->where('sts', 'approved');
        $this->db->where('id_quote LIKE', '%' . date('Ymd') . '%');
        $num_pesanan = $this->db->get()->num_rows();

        $jum_pesanan = sprintf('%02s', ($num_pesanan + 1));

        // print_r($jum_pesanan);
        // exit;

        $kode_cust = '';
        $x = 1;
        foreach ($get_customer as $customer) :
            if ($customer->id_customer == $get_penawaran->id_cust) {
                $kode_cust = $x;
            }
            $x++;
        endforeach;


        $id_quote = 'G' . $get_kode_sales->kode_angka . $kode_cust . $jum_pesanan . date('Ymd');

        $this->db->trans_begin();

        $this->db->update('ms_penawaran', ['sts' => 'send', 'send_date' => date('Y-m-d H:i:s')], ['id_penawaran' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = 'Maaf, Penawaran gagal terkirim !';
            $valid = 0;
        } else {
            $this->db->trans_commit();
            $msg = 'Selamat, penawaran telah terkirim !';
            $valid = 1;
        }

        echo json_encode([
            'status' => $valid,
            'msg' => $msg
        ]);
    }

    public function revisi()
    {
        $post = $this->input->post();

        $id = $post['id'];

        $this->db->select('a.revisi');
        $this->db->from('ms_penawaran a');
        $this->db->where('a.id_penawaran', $id);
        $get_revisi = $this->db->get()->row();

        $valid = 1;

        // $this->db->trans_begin();

        // $this->db->update('ms_penawaran', ['revisi' => ($get_revisi->revisi + 1)], ['id_penawaran' => $id]);

        // if ($this->db->trans_status() === FALSE) {
        //     $this->db->trans_rollback();
        //     $valid = 0;
        // } else {
        //     $this->db->trans_commit();
        //     $valid = 1;
        // }

        echo json_encode([
            'valid' => $valid
        ]);
    }

    public function loss_penawaran($id)
    {
        // $post = $this->input->post();

        $id_pen = $id;
        // $id_pen = str_replace('SP/', 'SP-', $id_pen);

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_pen])->row();
        $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $id_pen])->result();

        $this->template->set([
            'id_penawaran' => $id,
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail
        ]);

        $this->template->render('loss');
    }
    public function create_so($id)
    {
        // $post = $this->input->post();

        $id_pen = $id;
        // $id_pen = str_replace('-', '/', $id);
        // $id_pen = str_replace('SP/', 'SP-', $id_pen);

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_penawaran' => $id_pen])->row();

        $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $id_pen])->result();

        $id_quote = $get_penawaran->id_penawaran;
        if ($get_penawaran->id_quote !== null && $get_penawaran->id_quote !== '') {
            $id_quote = $get_penawaran->id_quote;
        }

        $this->template->set([
            'id_penawaran' => $id,
            'id_quote' => $id_quote,
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail
        ]);

        $this->template->render('create_so');
    }

    public function ppn_autoload()
    {
        $this->db->select('a.id_penawaran, a.biaya_pengiriman');
        $this->db->from('ms_penawaran a');
        $this->db->where('a.ppn_type', '1');
        $get_all_id_penawaran = $this->db->get()->result();

        $this->db->trans_begin();

        foreach ($get_all_id_penawaran as $penawaran) :
            $this->db->select('SUM(a.total_harga) AS ttl_harga');
            $this->db->from('ms_penawaran_detail a');
            $this->db->where('a.id_penawaran', $penawaran->id_penawaran);
            $get_ttl_harga = $this->db->get()->row();

            $nilai_ppn = (($get_ttl_harga->ttl_harga - $penawaran->nilai_disc + $penawaran->biaya_pengiriman) * 11 / 100);

            $this->db->update('ms_penawaran', [
                'ppn_num' => $nilai_ppn,
                'grand_total' => ($penawaran->total - $penawaran->nilai_disc + $penawaran->biaya_pengiriman + $nilai_ppn)
            ], ['id_penawaran' => $penawaran->id_penawaran]);
        endforeach;

        $this->db->trans_commit();
    }

    public function editInventory($id)
    {
        $id_data     = $this->input->post('id');
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $this->template->page_icon('fa fa-pencil');

        $post = $this->input->post();

        $deleted = '0';
        $product_type = $this->Request_develop_model->get_data('ms_product_type');
        $id_supplier = $this->Request_develop_model->get_data('master_supplier', 'status', '1');
        $supplier = $this->Request_develop_model->get_data('master_supplier', 'status', '1');
        $packaging = $this->Request_develop_model->get_data('master_packaging');
        $unit = $this->Request_develop_model->get_data('m_unit');
        $product_master = $this->Request_develop_model->get_data('ms_product_category3');
        $product_jenis = $this->Request_develop_model->get_data('ms_product_category2');
        $product_category = $this->db->get('ms_product_category1')->result();
        $kategori_fg = $this->Request_develop_model->get_data('kategori_finish_goods');
        $inv_lv_1 = "";

        $get_request_develop = $this->db->get_where('ms_request_develop', ['id' => $id])->row();

        $get_product_category = $this->db->get_where('ms_product_category1', ['id_type' => $get_request_develop->id_type])->result();
        $get_product_jenis = $this->db->get_where('ms_product_category2', ['id_category1' => $get_request_develop->id_category1])->result();

        $this->db->select('a.*');
        $this->db->from('ms_product_category3 a');
        $this->db->where_in('a.id_type', ['P231100013', 'P240100015']);
        $list_curing_agent = $this->db->get()->result();


        $data = [
            'product_type' => $product_type,
            'product_category' => $product_category,
            'product_jenis' => $product_jenis,
            'id_supplier' => $id_supplier,
            'supplier' => $supplier,
            'packaging' => $packaging,
            'unit' => $unit,
            'inventory_4' => $product_master,
            'inventory_3' => $product_jenis,
            'inventory_2' => $product_category,
            'id_category3' => $this->auth->user_id(),
            'inv_lv_1' => $inv_lv_1,
            'kategori_fg' => $kategori_fg,
            'request_develop' => $get_request_develop,
            'product_category' => $get_product_category,
            'product_jenis' => $get_product_jenis,
            'list_curing_agent' => $list_curing_agent
        ];



        $this->template->set('results', $data);
        $this->template->title('Edit Inventory');
        $this->template->render('edit_inventory');
    }
    public function copyInventory($id)
    {
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $this->template->page_icon('fa fa-pencil');
        $deleted = '0';
        $inven = $this->Request_develop_model->getedit($id);
        $komposisiold = $this->Request_develop_model->get_data('child_inven_compotition', 'id_category3', $id);
        $komposisi = $this->Request_develop_model->kompos($id);
        $dimensiold = $this->Request_develop_model->get_data('child_inven_dimensi', 'id_category3', $id);
        $dimensi = $this->Request_develop_model->dimensy($id);
        $supl = $this->Request_develop_model->supl($id);
        $inventory_1 = $this->Request_develop_model->get_data('ms_product_type', 'deleted', $deleted);
        $inventory_2 = $this->Request_develop_model->get_data('ms_product_category1', 'deleted', $deleted);
        $inventory_3 = $this->Request_develop_model->get_data('ms_product_category2', 'deleted', $deleted);
        $maker = $this->Request_develop_model->get_data('negara');
        $id_bentuk = $this->Request_develop_model->get_data('ms_bentuk');
        $id_supplier = $this->Request_develop_model->get_data('master_supplier');
        $id_surface = $this->Request_develop_model->get_data('ms_surface');
        $dt_suplier = $this->Request_develop_model->get_data('child_inven_suplier', 'id_category3', $id);
        $data = [
            'inventory_1' => $inventory_1,
            'inventory_2' => $inventory_2,
            'inventory_3' => $inventory_3,
            'komposisi' => $komposisi,
            'dimensi' => $dimensi,
            'id_bentuk' => $id_bentuk,
            'inven' => $inven,
            'maker' => $maker,
            'supl' => $supl,
            'id_surface' => $id_surface,
            'id_supplier' => $id_supplier,
            'dt_suplier' => $dt_suplier
        ];
        $this->template->set('results', $data);
        $this->template->title('Add Inventory');
        $this->template->render('copy_inventory');
    }
    public function viewInventory($id)
    {
        $id_data     = $this->input->post('id');
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $this->template->page_icon('fa fa-pencil');

        $post = $this->input->post();

        $deleted = '0';
        $product_type = $this->Request_develop_model->get_data('ms_product_type');
        $id_supplier = $this->Request_develop_model->get_data('master_supplier', 'status', '1');
        $supplier = $this->Request_develop_model->get_data('master_supplier', 'status', '1');
        $packaging = $this->Request_develop_model->get_data('master_packaging');
        $unit = $this->Request_develop_model->get_data('m_unit');
        // $product_master = $this->Request_develop_model->get_data('ms_product_category3');
        $product_jenis = $this->Request_develop_model->get_data('ms_product_category2');
        $product_category = $this->db->get('ms_product_category1')->result();
        $kategori_fg = $this->Request_develop_model->get_data('kategori_finish_goods');
        $inv_lv_1 = "";

        $get_request_develop = $this->db->get_where('ms_request_develop', ['id' => $id])->row();

        $get_product_category = $this->db->get_where('ms_product_category1', ['id_type' => $get_request_develop->id_type])->result();
        $get_product_jenis = $this->db->get_where('ms_product_category2', ['id_category1' => $get_request_develop->id_category1])->result();

        $get_product_master = $this->db->get_where('ms_product_category3', ['id_request' => $id])->row();

        $this->db->select('a.*');
        $this->db->from('ms_product_category3 a');
        $this->db->where_in('a.id_type', ['P231100013', 'P240100015']);
        $list_curing_agent = $this->db->get()->result();


        $data = [
            'product_type' => $product_type,
            'id_supplier' => $id_supplier,
            'supplier' => $supplier,
            'packaging' => $packaging,
            'unit' => $unit,
            'inventory_3' => $product_jenis,
            'inventory_2' => $product_category,
            'id_category3' => $this->auth->user_id(),
            'inv_lv_1' => $inv_lv_1,
            'kategori_fg' => $kategori_fg,
            'request_develop' => $get_request_develop,
            'product_category' => $get_product_category,
            'product_jenis' => $get_product_jenis,
            'list_curing_agent' => $list_curing_agent,
            'product_master' => $get_product_master
        ];



        $this->template->set('results', $data);
        $this->template->title('Add Inventory');
        $this->template->render('view_inventory');
    }
    public function viewBentuk($id)
    {
        $this->auth->restrict($this->viewPermission);
        $id     = $this->input->post('id');
        $bentuk = $this->db->get_where('ms_bentuk', array('id_bentuk' => $id))->result();
        $dimensi = $this->Bentuk_model->getDimensi($id);
        $data = [
            'bentuk' => $bentuk,
            'dimensi' => $dimensi,
        ];
        $this->template->set('results', $data);
        $this->template->render('view_bentuk');
    }


    public function addInventory()
    {
        $id_data     = $this->input->post('id');
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $this->template->page_icon('fa fa-pencil');

        $post = $this->input->post();

        $deleted = '0';
        $product_type = $this->Request_develop_model->get_data('ms_product_type');
        $id_supplier = $this->Request_develop_model->get_data('master_supplier', 'status', '1');
        $supplier = $this->Request_develop_model->get_data('master_supplier', 'status', '1');
        $packaging = $this->Request_develop_model->get_data('master_packaging');
        $unit = $this->Request_develop_model->get_data('m_unit');
        $product_master = $this->Request_develop_model->get_data('ms_product_category3');
        $product_jenis = $this->Request_develop_model->get_data('ms_product_category2');
        $product_category = $this->db->get('ms_product_category1')->result();
        $kategori_fg = $this->Request_develop_model->get_data('kategori_finish_goods');
        $inv_lv_1 = "";

        $this->db->select('a.*');
        $this->db->from('ms_product_category3 a');
        $this->db->where_in('a.id_type', ['P231100013', 'P240100015']);
        $list_curing_agent = $this->db->get()->result();




        $data = [
            'product_type' => $product_type,
            'product_category' => $product_category,
            'product_jenis' => $product_jenis,
            'id_supplier' => $id_supplier,
            'supplier' => $supplier,
            'packaging' => $packaging,
            'unit' => $unit,
            'inventory_4' => $product_master,
            'inventory_3' => $product_jenis,
            'inventory_2' => $product_category,
            'id_category3' => $this->auth->user_id(),
            'inv_lv_1' => $inv_lv_1,
            'kategori_fg' => $kategori_fg,
            'list_curing_agent' => $list_curing_agent
        ];

        $this->template->set('results', $data);
        $this->template->title('Request New Product');

        $this->template->render('request_new_product');
    }

    public function delDetail()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->input->post('id');
        // print_r($id);
        // exit();
        $data = [
            'deleted'         => '1',
            'deleted_by'     => $this->auth->user_id()
        ];

        $this->db->trans_begin();
        $this->db->where('id_dimensi', $id)->update("ms_dimensi", $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status    = array(
                'pesan'        => 'Gagal Save Item. Thanks ...',
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $status    = array(
                'pesan'        => 'Success Save Item. Thanks ...',
                'status'    => 1
            );
        }

        echo json_encode($status);
    }

    public function deleteInventory()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->input->post('id');

        $this->db->trans_begin();
        $this->db->delete("ms_request_develop", ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status    = array(
                'pesan'        => 'Gagal Save Item. Thanks ...',
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $status    = array(
                'pesan'        => 'Success Save Item. Thanks ...',
                'status'    => 1
            );
        }

        echo json_encode($status);
    }
    function get_inven2()
    {
        $inventory_1 = $_GET['inventory_1'];
        $data = $this->Request_develop_model->level_2($inventory_1);
        echo "<select id='inventory_2' name='inventory_2' class='form-control onchange='get_inv3()'  input-sm select2'>";
        echo "<option value=''>-- Pilih Product Category --</option>";
        foreach ($data as $key => $st) :
            echo "<option value='" . $st->id_category1 . "' set_select('inventory_2', $st->id_category1, isset($data->id_category1) && $data->id_category1 == $st->id_category1)>$st->nama
                    </option>";
        endforeach;
        echo "</select>";
    }

    function get_namainven2()
    {
        $inventory_1 = $_POST['inventory_1'];

        $data = $this->db->query("SELECT * from ms_product_category2 WHERE id_category2 = '" . $inventory_1 . "'")->result_array();

        echo $data->nama;
    }
    function get_inven3()
    {
        $inventory_2 = $_GET['inventory_2'];
        $data = $this->db->get_where('ms_product_category2', ['id_category1' => $inventory_2])->result();

        // print_r($data);
        // exit();
        echo "<select id='inventory_3' name='inventory_3' class='form-control input-sm select2'>";
        echo "<option value=''>-- Pilih Product Jenis --</option>";
        foreach ($data as $key => $st) :
            echo "<option value='" . $st->id_category2 . "' set_select('inventory_3', $st->id_category2, isset($data->id_category2) && $data->id_category2 == $st->id_category2)>$st->nama
                    </option>";
        endforeach;
        echo "</select>";
    }
    public function saveNewInventory()
    {
        $this->auth->restrict($this->addPermission);
        $session = $this->session->userdata('app_session');

        $post = $this->input->post();

        $config['upload_path'] = './uploads/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|doc|docx|xls|xlsx|ppt|pptx|pdf|rar|zip|vsd'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
        $config['encrypt_name'] = FALSE; // Encrypt the uploaded file's name.
        $config['remove_spaces'] = FALSE; // Remove spaces from the file name.

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $code = $this->Request_develop_model->generate_id();

        $get_product_type = $this->db->get_where('ms_product_type', ['id_type' => $post['nm_type']])->row();
        $get_product_category1 = $this->db->get_where('ms_product_category1', ['id_category1' => $post['nm_category1']])->row();
        $get_product_category2 = $this->db->get_where('ms_product_category2', ['id_category2' => $post['nm_category2']])->row();



        $this->db->trans_begin();
        $numb1 = 0;
        //$head = $_POST['hd1'];
        $this->db->insert('ms_request_develop', [
            'id' => $code,
            'nm_type' => $post['nm_type'],
            'nm_category1' => $post['nm_category1'],
            'nm_category2' => $post['nm_category2'],
            'packaging' => $post['jenis_packaging'],
            'konversi' => $post['spek_packaging'],
            'keterangan' => $post['keterangan'],
            'unit_nm' => $post['unit'],
            'aplikasi_penggunaan_cat' => $post['aplikasi_penggunaan_cat'],
            'water_resistance' => $post['water_resistance'],
            'weather_uv_resistance' => $post['weather_uv_resistance'],
            'corrosion_resistance' => $post['corrosion_resistance'],
            'heat_resistance' => $post['heat_resistance'],
            'daya_rekat' => $post['daya_rekat'],
            'lama_pengeringan' => $post['lama_pengeringan'],
            'permukaan' => $post['permukaan'],
            'anti_jamur_lumut' => $post['anti_jamur_lumut'],
            'mudah_dibersihkan' => $post['mudah_dibersihkan'],
            'anti_bakteri' => $post['anti_bakteri'],
            'daya_tahan_gesekan' => $post['daya_tahan_gesekan'],
            'anti_slip' => $post['anti_slip'],
            'fire_resistance' => $post['fire_resistance'],
            'ketahanan_bahan_kimia' => $post['ketahanan_bahan_kimia'],
            'dibuat_oleh' => $this->auth->user_id(),
            'dibuat_tgl' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status    = array(
                'pesan'        => 'Gagal Save Item. Thanks ...',
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $status    = array(
                'pesan'        => 'Success Save Item. Thanks ...',
                'status'    => 1
            );
        }

        echo json_encode($status);
    }
    public function saveEditInventory()
    {
        $this->auth->restrict($this->addPermission);
        $session = $this->session->userdata('app_session');
        $code = $this->Request_develop_model->generate_id();

        $post = $this->input->post();

        $this->db->trans_begin();

        $get_product_type = $this->db->get_where('ms_product_type', ['id_type' => $post['nm_type']])->row();
        $get_product_category = $this->db->get_where('ms_product_category1', ['id_category1' => $post['nm_category1']])->row();
        $get_product_jenis = $this->db->get_where('ms_product_category2', ['id_category2' => $post['nm_category2']])->row();

        if ($post['req_action'] !== "") {
            if ($post['req_action'] == '1') {
                $config['upload_path'] = './uploads/'; //path folder
                $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf'; //type yang dapat diakses bisa anda sesuaikan
                $config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
                $config['encrypt_name'] = FALSE; // Encrypt the uploaded file's name.
                $config['remove_spaces'] = FALSE; // Remove spaces from the file name.

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('pds')) {
                    $data = 'Upload Error';
                } else {
                    $data = $this->upload->data();
                    $data = '/uploads/' . $data['file_name'];
                }

                $codee = $this->Request_develop_model->generate_idd();

                $get_unit = $this->db->get_where('m_unit', ['id_unit' => $this->input->post('unit')])->row();

                $id_refer = $this->input->post('refer_product');
                $nm_refer = '';
                $check_refer = $this->db->get_where('ms_product_category3', ['id_category3' => $this->input->post('refer_product')])->num_rows();
                if ($check_refer > 0) {
                    $get_refer = $this->db->get_where('ms_product_category3', ['id_category3' => $this->input->post('refer_product')])->row();
                    $nm_refer = $get_refer->nama;
                }

                $this->db->insert('ms_product_category3', [
                    'id_category3' => $codee,
                    'id_type' => $this->input->post('inventory_1'),
                    'id_category1' => $this->input->post('inventory_2'),
                    'id_category2' => $this->input->post('inventory_3'),
                    'nama' => $this->input->post('nm_lv_4'),
                    'nama_mandarin' => $this->input->post('nm_lv_4_mandarin'),
                    'curing_agent' => $this->input->post('curing_agent'),
                    'curing_agent_konversi' => $this->input->post('curing_agent_konversi'),
                    'moq' => $this->input->post('moq'),
                    'packaging' => $this->input->post('packaging'),
                    'konversi' => $this->input->post('konversi'),
                    'unit_id' => $this->input->post('unit'),
                    'unit_nm' => $get_unit->nm_unit,
                    'product_code' => $this->input->post('product_code'),
                    'max_stok' => $this->input->post('max_stok'),
                    'min_stok' => $this->input->post('min_stok'),
                    'dim_length' => $this->input->post('dim_length'),
                    'dim_width' => $this->input->post('dim_width'),
                    'dim_height' => $this->input->post('dim_height'),
                    'cbm' => $this->input->post('cbm'),
                    'dim_tabung_r' => $this->input->post('dim_tabung_r'),
                    'dim_tabung_t' => $this->input->post('dim_tabung_t'),
                    'cbm_tabung' => $this->input->post('cbm_tabung'),
                    'pds' => $data,
                    'aktif' => 1,
                    'aplikasi_penggunaan_cat' => $this->input->post('aplikasi_penggunaan_cat'),
                    'water_resistance' => $this->input->post('water_resistance'),
                    'weather_uv_resistance' => $this->input->post('weather_uv_resistance'),
                    'corrosion_resistance' => $this->input->post('corrosion_resistance'),
                    'heat_resistance' => $this->input->post('heat_resistance'),
                    'daya_rekat' => $this->input->post('daya_rekat'),
                    'lama_pengeringan' => $this->input->post('lama_pengeringan'),
                    'permukaan' => $this->input->post('permukaan'),
                    'anti_jamur_lumut' => $this->input->post('anti_jamur_lumut'),
                    'mudah_dibersihkan' => $this->input->post('mudah_dibersihkan'),
                    'anti_bakteri' => $this->input->post('anti_bakteri'),
                    'daya_tahan_gesekan' => $this->input->post('daya_tahan_gesekan'),
                    'anti_slip' => $this->input->post('anti_slip'),
                    'fire_resistance' => $this->input->post('fire_resistance'),
                    'ketahanan_bahan_kimia' => $this->input->post('ketahanan_bahan_kimia'),
                    'id_product_refer' => $id_refer,
                    'nm_product_refer' => $nm_refer,
                    'id_request' => $code,
                    'dibuat_oleh' => $this->auth->user_id(),
                    'dibuat_tgl' => date("Y-m-d H:i:s")
                ]);

                $this->db->update('ms_request_develop', [
                    'id_type' => $post['nm_type'],
                    'nm_type' => $get_product_type->nama,
                    'id_category1' => $post['nm_category1'],
                    'nm_category1' => $get_product_category->nama,
                    'id_category2' => $post['nm_category2'],
                    'nm_category2' => $get_product_jenis->nama,
                    'packaging' => $post['jenis_packaging'],
                    'konversi' => $post['spek_packaging'],
                    'unit_nm' => $post['unit_sales'],
                    'diubah_oleh' => $this->auth->user_id(),
                    'diubah_tgl' => date('Y-m-d H:i:s'),
                    'sts' => 'approved',
                ], [
                    'id' => $post['id']
                ]);
            } else {
                $this->db->update('ms_request_develop', [
                    'id_type' => $post['nm_type'],
                    'nm_type' => $get_product_type->nama,
                    'id_category1' => $post['nm_category1'],
                    'nm_category1' => $get_product_category->nama,
                    'id_category2' => $post['nm_category2'],
                    'nm_category2' => $get_product_jenis->nama,
                    'packaging' => $post['jenis_packaging'],
                    'konversi' => $post['spek_packaging'],
                    'unit_nm' => $post['unit_sales'],
                    'diubah_oleh' => $this->auth->user_id(),
                    'diubah_tgl' => date('Y-m-d H:i:s'),
                    'sts' => 'rejected',
                ], [
                    'id' => $post['id']
                ]);
            }
        } else {

            $this->db->update('ms_request_develop', [
                'id_type' => $post['nm_type'],
                'nm_type' => $get_product_type->nama,
                'id_category1' => $post['nm_category1'],
                'nm_category1' => $get_product_category->nama,
                'id_category2' => $post['nm_category2'],
                'nm_category2' => $get_product_jenis->nama,
                'packaging' => $post['jenis_packaging'],
                'konversi' => $post['spek_packaging'],
                'diubah_oleh' => $this->auth->user_id(),
                'diubah_tgl' => date('Y-m-d H:i:s')
            ], [
                'id' => $post['id']
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status    = array(
                'pesan'        => 'Gagal Save Item. Thanks ...',
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $msg = 'Success Save Item. Thanks ...';
            if ($post['req_action'] !== "") {
                if ($post['req_action'] == "1") {
                    $msg = 'Success Approve Request Development !';
                } else {
                    $msg = 'Success Reject Request Development !';
                }
            }
            $status    = array(
                'pesan'        => $msg,
                'status'    => 1
            );
        }

        echo json_encode($status);
    }
}
