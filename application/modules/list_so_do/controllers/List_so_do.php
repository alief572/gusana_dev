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

class List_so_do extends Admin_Controller
{
    protected $viewPermission     = 'List_SO_DO.View';
    protected $addPermission      = 'List_SO_DO.Add';
    protected $managePermission = 'List_SO_DO.Manage';
    protected $deletePermission = 'List_SO_DO.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'List_so_do/List_so_do_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('List_so_do');
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
            SELECT *, DATE_FORMAT(tgl_penawaran, '%d %M %Y') AS quote_date
            FROM
                ms_penawaran
            WHERE
                1=1 AND sts = 'so_created' AND id_quote <> '' AND (
                    id_quote LIKE '%" . $string . "%' OR
                    nm_cust LIKE '%" . $string . "%' OR
                    nm_marketing LIKE '%" . $string . "%' OR
                    nilai_penawaran LIKE '%" . $string . "%' OR
                    DATE_FORMAT(tgl_penawaran, '%d %M %Y') LIKE '%" . $string . "%'
                )
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id_quote',
            1 => 'id_quote'
        );

        $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
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

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id_quote'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_quote'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_quote'] . '"><i class="fa fa-trash"></i></button>';

            $create_ppb = '<button type="button" class="btn btn-success btn-sm create_ppb" data-toggle="tooltip" title="Outgoing Goods Request" data-id="' . $row['id_quote'] . '"><i class="fa fa-plus"></i></button>';

            $buttons     = $create_ppb;
            if ($row['sts_ppb'] == 'ppb_created') {
                $buttons = '';
            }

            if ($row['sts_ppb'] !== 'ppb_created') {
                $sts = '<div class="badge badge-warning text-light">Request Not Created</div>';
            } else {
                $sts = '<div class="badge badge-success">Request Created</div>';
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['id_quote'];
            $nestedData[]  = $row['nm_cust'];
            $nestedData[]  = $row['nm_marketing'];
            $nestedData[]  = number_format($row['nilai_penawaran'], 2);
            $nestedData[]  = $row['quote_date'];
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
        $this->template->title('List SO');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        $divisi = $this->db->query('SELECT MAX(id) AS id_divisi FROM m_divisi')->row();
        $id_divisi = ($divisi->id_divisi);
        $id_divisi++;
        $data = [
            'id_divisi' => $id_divisi
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $this->auth->restrict($this->managePermission);
        $divisi = $this->db->get_where('m_divisi', array('id' => $id))->row();

        $this->template->set([
            'id_divisi' => $id,
            'divisi' => $divisi
        ]);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->managePermission);
        $divisi = $this->db->get_where('m_divisi', array('id' => $id))->row();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View List_so_do Data " . $divisi->id . " - " . $divisi->divisi;
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

        $this->template->set([
            'id_divisi' => $id,
            'divisi' => $divisi
        ]);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $id_ppb = $this->List_so_do_model->generate_id();

        $this->db->trans_begin();

        $this->db->update('ms_penawaran', [
            'id_ppb' => $id_ppb,
            'sts_ppb' => 'ppb_created',
            'tgl_create_ppb' => date('Y-m-d'),
            'create_ppb_user' => $this->auth->user_id()
        ], [
            'id_quote' => $post['id_quote']
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = 0;
            $msg = 'Sorry, please try again !';
        } else {
            $this->db->trans_commit();
            $status = 1;
            $msg = 'Success, request has been created !';
        }

        echo json_encode([
            'status' => $status,
            'msg' => $msg
        ]);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('m_divisi', ['id' => $id])->row_array();

        $this->db->trans_begin();
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete List_so_do Data " . $data['id'] . " - " . $data['divisi'];
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
                'msg'        => "Failed delete data List_so_do. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data List_so_do.',
                'status'    => 1
            );
            $keterangan     = "Delete data List_so_do " . $data['id'] . ", List_so_do : " . $data['divisi'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function create_ppb($id)
    {
        $this->auth->restrict($this->managePermission);

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_quote' => $id])->row();

        $get_penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $get_penawaran->id_penawaran])->result();

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
        $this->template->render('create_ppb');
    }
}
