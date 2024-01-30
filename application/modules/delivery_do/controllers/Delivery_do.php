<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author Hikmat Aolia
 * @copyright Copyright (c) 2023, Hikmat Aolia
 *
 * This is controller for Master Employee
 */

class Delivery_do extends Admin_Controller
{
    protected $viewPermission     = 'DO.View';
    protected $addPermission      = 'DO.Add';
    protected $managePermission = 'DO.Manage';
    protected $deletePermission = 'DO.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Delivery_do/Delivery_do_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('DO');
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
        $sql = "
            SELECT *, DATE_FORMAT(tgl_create_do,'%d %M %Y') AS do_date, DATE_FORMAT(tgl_create_so,'%d %M %Y') AS so_date
            FROM
                ms_penawaran
            WHERE
                1=1 AND sts_do != '' AND id_do IS NOT NULL AND (
                    nm_cust LIKE '%" . $string . "%' OR
                    id_do LIKE '%" . $string . "%' OR
                    DATE_FORMAT(tgl_create_do,'%d %M %Y') LIKE '%" . $string . "%' OR
                    id_quote LIKE '%" . $string . "%' OR
                    DATE_FORMAT(tgl_create_so,'%d %M %Y')
                )
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id_penawaran',
            1 => 'id_do',
            2 => "DATE_FORMAT(tgl_create_do,'%d %M %Y')",
            3 => "id_quote",
            4 => "DATE_FORMAT(tgl_create_so,'%d %M %Y')"
        );

        // $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
        $sql .= " ORDER BY id_do DESC ";
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

            // $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_do'] . '"><i class="fa fa-edit"></i></button>';
            // $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';

            $print_do = '<a href="' . base_url('delivery_do/print_do/' . $row['id_do']) . '" class="btn btn-sm btn-info" target="_blank"><div class="i fa fa-print"></div></a>';



            $this->db->select('IF(SUM(a.qty) > 0 AND SUM(a.qty) IS NOT NULL, SUM(a.qty), 0) AS all_qty, IF(SUM(a.weight) > 0 AND SUM(a.weight) IS NOT NULL, SUM(a.weight), 0) AS all_weight');
            $this->db->from('ms_detail_do a');
            $this->db->where('a.id_do', $row['id_do']);
            $ttl_do_sended = $this->db->get()->row();

            $this->db->select('IF(SUM(a.qty) > 0 AND SUM(a.qty) IS NOT NULL, SUM(a.qty), 0) AS so_qty, IF(SUM(a.weight) > 0 AND SUM(a.weight) IS NOT NULL, SUM(a.weight), 0) AS so_weight');
            $this->db->from('ms_penawaran_detail a');
            $this->db->where('a.id_penawaran', $row['id_penawaran']);
            $ttl_so_qty = $this->db->get()->row();

            if ($ttl_do_sended->all_qty == 0) {
                $sts = '<div class="badge badge-danger">Not Sent</div>';
            } else {
                if ($ttl_do_sended->all_qty !== $ttl_so_qty->so_qty) {
                    $sts = '<div class="badge badge-warning text-light">Sent ' . number_format($ttl_do_sended->all_qty / $ttl_so_qty->so_qty * 100, 2) . '%</div>';
                } else {
                    $sts = '<div class="badge badge-success">Sent 100%</div>';
                    $edit = '';
                }
            }

            $buttons     = $print_do . ' ' . $edit;

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['nm_cust'];
            $nestedData[]  = $row['id_do'];
            $nestedData[]  = $row['do_date'];
            $nestedData[]  = $row['id_quote'];
            $nestedData[]  = $row['so_date'];
            $nestedData[]  = $sts;
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
        $this->template->title('Delivery Order');
        $this->template->render('index');
    }

    public function edit($id)
    {
        $this->auth->restrict($this->managePermission);
        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_do' => $id])->row();

        $this->db->select('a.*, b.unit_nm, c.nm_packaging, IF(d.qty_asli > 0 AND d.qty_asli IS NOT NULL, d.qty_asli, 0) AS stock_aktual');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product', 'left');
        $this->db->join('master_packaging c', 'c.id = b.packaging', 'left');
        $this->db->join('ms_stock_product d', 'd.id_product = a.id_product', 'left');
        $this->db->where('a.id_penawaran', $get_penawaran->id_penawaran);
        $get_penawaran_detail = $this->db->get()->result();

        $get_sended_do = $this->db->get_where('ms_detail_do', ['id_do' => $id])->result();

        $this->template->set([
            'penawaran' => $get_penawaran,
            'list_penawaran_detail' => $get_penawaran_detail,
            'sended_do' => $get_sended_do
        ]);
        $this->template->render('form');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_do' => $post['id_do']])->row();
        $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $get_penawaran->id_penawaran])->result();

        $stock_sts = 1;

        $max_not = 1;

        $this->db->trans_begin();
        foreach ($get_penawaran_detail as $penawaran_detail) :

            $qty = $post['deliver_' . $penawaran_detail->id];
            $weight = ($post['deliver_' . $penawaran_detail->id] * $penawaran_detail->konversi);

            $this->db->select('IF(SUM(a.qty_asli) > 0 AND SUM(a.qty_asli) IS NOT NULL, SUM(a.qty_asli), 0) AS ttl_qty');
            $this->db->from('ms_stock_product a');
            $this->db->where('a.id_product', $penawaran_detail->id_product);
            $get_ttl_stock_aktual = $this->db->get()->row();

            $this->db->select('SUM(a.weight) AS booking_stock');
            $this->db->from('ms_penawaran_detail a');
            $this->db->join('ms_penawaran b', 'b.id_penawaran = a.id_penawaran');
            $this->db->where('a.id_product', $penawaran_detail->id_product);
            $this->db->where('b.sts !=', 'loss');
            $this->db->where('b.sts_close_do', '');
            $this->db->where('b.id_do !=', $post['id_do']);
            $get_booking_stock = $this->db->get()->row();

            $this->db->select('IF(SUM(a.qty) > 0 AND SUM(a.qty) IS NOT NULL, SUM(a.qty), 0) AS qty_delivered');
            $this->db->from('ms_detail_do a');
            $this->db->where('a.id_product', $penawaran_detail->id_product);
            $this->db->where('a.id_do', $post['id_do']);
            $get_delivered_qty = $this->db->get()->row();

            $free_stock = ($get_ttl_stock_aktual->ttl_qty - $get_booking_stock->booking_stock);
            if ($free_stock < $weight) {
                $stock_sts = 0;
            }

            if (($qty + $get_delivered_qty->qty_delivered) > $penawaran_detail->qty) {
                $max_not = 0;
            }

            if ($stock_sts == 1) {
                $this->db->insert('ms_detail_do', [
                    'id' => $this->Delivery_do_model->generate_id(),
                    'id_print_do' => $this->Delivery_do_model->generate_id_print_do(),
                    'id_do' => $post['id_do'],
                    'id_product' => $penawaran_detail->id_product,
                    'nm_product' => $penawaran_detail->nm_product,
                    'qty' => $post['deliver_' . $penawaran_detail->id],
                    'weight' => ($post['deliver_' . $penawaran_detail->id] * $penawaran_detail->konversi),
                    'tgl_kirim' => $post['deliver_date'],
                    'dibuat_oleh' => $this->auth->user_id(),
                    'dibuat_tgl' => date('Y-m-d')
                ]);

                $this->db->update(
                    'ms_stock_product',
                    [
                        'qty_asli' => ($get_ttl_stock_aktual->ttl_qty - $weight)
                    ],
                    [
                        'id_product' => $penawaran_detail->id_product
                    ]
                );
            }
        endforeach;

        if ($this->db->trans_status() === FALSE || $stock_sts == '0' || $max_not == '0') {
            $this->db->trans_rollback();
            $status = 0;
            if ($stock_sts == 0) {
                $msg = 'Sorry, there are items whose stock exceeds the available free stock !';
            } else if ($max_not == 0) {
                $msg = 'Sorry, the product delivery value exceeds qty order !';
            } else {
                $msg = 'Sorry, Delivery data has not been updated, please try again !';
            }
        } else {
            $this->db->trans_commit();
            $status = 1;
            $msg = 'Success, Delivery data has been updated !';
        }

        echo json_encode([
            'status' => $status,
            'msg' => $msg
        ]);
    }

    public function print_do($id_do)
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Print DO');

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_do' => $id_do])->row();

        $this->db->select('a.*, b.nama_mandarin, c.nm_packaging, d.nama as kode_warna');
        $this->db->from('ms_penawaran_detail a');
        $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product', 'left');
        $this->db->join('master_packaging c', 'c.id = b.packaging', 'left');
        $this->db->join('ms_product_category2 d', 'd.id_category2 = b.id_category2', 'left');
        $this->db->where('a.id_penawaran', $get_penawaran->id_penawaran);
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

        $this->template->set([
            'data_penawaran' => $get_penawaran,
            'data_penawaran_detail' => $get_penawaran_detail,
            'pic_phone' => $pic_phone
        ]);
        $this->template->render('view');
    }
}
