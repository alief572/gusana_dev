<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Goods_release_order extends Admin_Controller
{
    protected $viewPermission     = 'Goods_Release_Order.View';
    protected $addPermission      = 'Goods_Release_Order.Add';
    protected $managePermission = 'Goods_Release_Order.Manage';
    protected $deletePermission = 'Goods_Release_Order.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Goods_release_order/Goods_release_order_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Goods_release_order');
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
            SELECT *, DATE_FORMAT(tgl_create_ppb, '%d %M %Y') AS ppb_date, DATE_FORMAT(tgl_create_so, '%d %M %Y') AS so_date
            FROM
                ms_penawaran
            WHERE
                1=1 AND sts_ppb = 'ppb_created' AND id_ppb IS NOT NULL AND (
                    nm_cust LIKE '%" . $string . "%' OR
                    id_ppb LIKE '%" . $string . "%' OR
                    DATE_FORMAT(tgl_create_ppb, '%d %M %Y') LIKE '%" . $string . "%' OR
                    id_quote LIKE '%" . $string . "%' OR
                    DATE_FORMAT(tgl_create_so, '%d %M %Y') LIKE '%" . $string . "%'
                )
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id_penawaran',
            1 => 'id_ppb',
            2 => 'DATE_FORMAT(tgl_create_ppb, "%d %M %Y")',
            3 => 'id_quote',
            4 => 'DATE_FORMAT(tgl_create_so, "%d %M %Y")',
        );

        // $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
        $sql .= " ORDER BY id_ppb DESC ";
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
            // $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
            // $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';

            $print_request = '<a href="' . base_url('goods_release_order/print_request/' . $row['id_ppb'] . '') . '" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-print"></i></a>';

            $create_do = '<button type="button" class="btn btn-sm btn-success create_do" data-toggle="tooltip" title="Create DO" data-id="' . $row['id_ppb'] . '"><i class="fa fa-plus"></i></button>';
            if ($row['sts_do'] == 'do_created') {
                $create_do = '';
            }

            $buttons     = $print_request . ' ' . $create_do;

            if ($row['sts_do'] == "do_created") {
                $sts = '<div class="badge badge-success">DO created</div>';
            } else {
                $sts = '<div class="badge badge-warning text-light">DO not created</div>';
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['nm_cust'];
            $nestedData[]  = $row['id_ppb'];
            $nestedData[]  = $row['ppb_date'];
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
        $this->template->title('Product Release Orders');
        $this->template->render('index');
    }

    public function print_request($id)
    {
        $this->auth->restrict($this->managePermission);
        $this->template->title('Goods Release Orders');

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_ppb' => $id])->row();
        $get_penawaran_detail = $this->db->query('SELECT a.*, c.nm_packaging FROM ms_penawaran_detail a LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product LEFT JOIN master_packaging c ON c.id = b.packaging WHERE a.id_penawaran = "' . $get_penawaran->id_penawaran . '"')->result();
        $data = [
            'penawaran' => $get_penawaran,
            'list_penawaran_detail' => $get_penawaran_detail
        ];
        $this->template->set($data);
        $this->template->render('print_request');
    }

    public function create_do()
    {
        $id = $this->input->post('id');

        $this->db->trans_begin();

        $this->db->update('ms_penawaran', [
            'id_do' => $this->Goods_release_order_model->generate_id(),
            'sts_do' => 'do_created',
            'tgl_create_do' => date('Y-m-d'),
            'create_do_user' => $this->auth->user_id()
        ], [
            'id_ppb' => $id
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $valid = 0;
            $msg = 'Sorry, DO not created !';
        } else {
            $this->db->trans_commit();
            $valid = 1;
            $msg = 'Success, DO has been created !';
        }

        echo json_encode([
            'status' => $valid,
            'msg' => $msg
        ]);
    }
}
