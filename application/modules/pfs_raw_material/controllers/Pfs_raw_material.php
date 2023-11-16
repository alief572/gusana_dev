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

class Pfs_raw_material extends Admin_Controller
{
    protected $viewPermission     = 'Raw_Material.View';
    protected $addPermission      = 'Raw_Material.Add';
    protected $managePermission = 'Raw_Material.Manage';
    protected $deletePermission = 'Raw_Material.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib'));
        $this->load->model(array(
            'Pfs_raw_material/Pfs_raw_material_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->template->title('Price Reference Raw Material');
        $this->template->page_icon('fas fa-user-tie');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Price Reference Raw Material');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        $departemen = $this->db->query('SELECT MAX(id_departemen) AS max_id FROM m_departemen')->row();
        $id_departemen = ($departemen->max_id);
        $id_departemen++;
        $data = [
            'id_departemen' => $id_departemen
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $get_inven_4 = $this->Pfs_raw_material_model->get_inven_4($id);
        $data = [
            'inventory_4' => $get_inven_4
        ];
        $this->template->set('results', $data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $departemen = $this->db->get_where('m_departemen', array('id_departemen' => $id))->row();
        $data = [
            'id_departemen' => $id,
            'departemen' => $departemen
        ];
        $this->template->set($data);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $config['upload_path'] = './uploads/prs_raw_material/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|doc|docx|xls|xlsx|ppt|pptx|pdf|rar|zip|vsd'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
        $config['encrypt_name'] = FALSE; // Encrypt the uploaded file's name.
        $config['remove_spaces'] = FALSE; // Remove spaces from the file name.

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_price_ref_evidence')) {
            $data = 'Upload Error';
        } else {
            $data = $this->upload->data();
            $data = '/uploads/prs_raw_material/' . $data['file_name'];
        }

        $data_inven_4 = $this->Pfs_raw_material_model->get_inven_4($post['id_category3']);

        $lower_price_before = $post['lower_price_before'];
        if ($post['lower_price_before'] == "" || $post['lower_price_before'] == null || $post['lower_price_before'] == 0) {
            $lower_price_before = $post['lower_price_after'];
        } else {
            if ($data_inven_4->lower_price_after > 0) {
                $lower_price_before = $data_inven_4->lower_price_after;
            }
        }

        $lower_price_after = $post['lower_price_after'];
        if ($post['lower_price_before'] == "" || $post['lower_price_before'] == null || $post['lower_price_before'] == 0) {
            $lower_price_after = '';
        }

        $higher_price_before = $post['higher_price_before'];
        if ($post['higher_price_before'] == "" || $post['higher_price_before'] == null || $post['higher_price_before'] == 0) {
            $higher_price_before = $post['higher_price_after'];
        } else {
            if ($data_inven_4->higher_price_after > 0) {
                $higher_price_before = $data_inven_4->higher_price_after;
            }
        }

        $higher_price_after = $post['higher_price_after'];
        if ($post['higher_price_before'] == "" || $post['higher_price_before'] == null || $post['higher_price_before'] == 0) {
            $higher_price_after = '';
        }

        $expired_before = date("Y-m-d", strtotime("+" . $post['expired_time'] . " months"));
        if ($data_inven_4->expired_after !== "" && $data_inven_4->expired_after !== null && $data_inven_4->expired_after !== "0000-00-00") {
            $expired_before = $data_inven_4->expired_after;
        } else {
            if ($data_inven_4->expired_before !== "" && $data_inven_4->expired_before !== null && $data_inven_4->expired_before !== "0000-00-00") {
                $expired_before = $data_inven_4->expired_before;
            }
        }

        $expired_after = '';
        if ($data_inven_4->expired_before !== "" && $data_inven_4->expired_before !== null) {
            $expired_after = date("Y-m-d", strtotime("+" . $post['expired_time'] . " month"));
        }

        $data = [
            'lower_price_before' => $lower_price_before,
            'lower_price_after' => $lower_price_after,
            'higher_price_before' => $higher_price_before,
            'higher_price_after' => $higher_price_after,
            'tgl_update_price_ref' => date("Y-m-d"),
            'expired_before' => $expired_before,
            'expired_after' => $expired_after,
            'expired_time' => $post['expired_time'],
            'file_price_ref_evidence' => $data,
            'note_price_ref' => $post['note_price_ref']
        ];

        $data_log = [
            'id' => $this->Pfs_raw_material_model->generate_id_log(),
            'id_category3' => $post['id_category3'],
            'lower_price_before_from' => $data_inven_4->lower_price_before,
            'lower_price_before_to' => $lower_price_before,
            'lower_price_after_from' => $data_inven_4->lower_price_after,
            'lower_price_after_to' => $lower_price_after,
            'higher_price_before_from' => $data_inven_4->higher_price_before,
            'higher_price_before_to' => $higher_price_before,
            'higher_price_after_from' => $data_inven_4->higher_price_after,
            'higher_price_after_to' => $higher_price_after,
            'expired_before_from' => $data_inven_4->expired_before,
            'expired_before_to' => $expired_before,
            'expired_after_from' => $data_inven_4->expired_after,
            'expired_after_to' => $expired_after,
            'made_by' => $this->auth->user_id(),
            'made_on' => date("Y-m-d H:i:s")
        ];

        $this->db->update('ms_inventory_category3', $data, ['id_category3' => $post['id_category3']]);

        // $this->db->insert('ms_price_ref_log', $data_log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed save data Price Reference Raw Material.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Price Reference Raw Material " . $post['id_category3'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_category3'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Price Reference Raw Material.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Price Reference Raw Material " . $post['id_category3'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_category3'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('m_departemen', ['id_departemen' => $id])->row_array();

        $this->db->trans_begin();
        $sql = $this->db->delete('m_departemen', ['id_departemen' => $id]);
        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->deletePermission;
            $kode_universal = $data['id_departemen'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Departemen. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Departemen.',
                'status'    => 1
            );
            $keterangan     = "Delete data Departemen " . $data['id_departemen'] . ", Departemen name : " . $data['nm_departemen'];
            $status         = 1;
            $nm_hak_akses   = $this->deletePermission;
            $kode_universal = $data['id_departemen'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
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
        FROM ms_inventory_category3 WHERE 1=1  
        AND (
            material_code LIKE '%" . $string . "%' OR 
            nama LIKE '%" . $string . "%' OR
            lower_price_before LIKE '%" . $string . "%' OR
            lower_price_after LIKE '%" . $string . "%' OR
            higher_price_before LIKE '%" . $string . "%' OR
            higher_price_after LIKE '%" . $string . "%' OR
            expired_before LIKE '%" . $string . "%' OR
            expired_after LIKE '%" . $string . "%'
        )";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'num',
            1 => 'material_code',
            2 => 'nama',
            3 => 'lower_price_before',
            4 => 'lower_price_after',
            5 => 'higher_price_before',
            6 => 'higher_price_after',
            7 => 'expired_before',
            8 => 'expired_after',
            9 => 'status_update'
        );

        // $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
        $sql .= " LIMIT " . $start . " ," . $length . " ";
        $query  = $this->db->query($sql);


        $data  = array();
        $urut1  = 1;
        $urut2  = 0;

        $status = [
            '0' => '<span class="bg-danger tx-white pd-5 tx-11 tx-bold rounded-5">Inactive</span>',
            '1' => '<span class="bg-info tx-white pd-5 tx-11 tx-bold rounded-5">Active</span>',
        ];

        $empType = [
            'Tetap' => '<span class="bg-indigo tx-white pd-5 tx-11 tx-bold rounded-5">Tetap</span>',
            'Kontrak' => '<span class="bg-success tx-white pd-5 tx-11 tx-bold rounded-5">Kontrak</span>',
        ];

        $gender = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];

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

            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_category3'] . '"><i class="fa fa-edit"></i></button>';
            $download_file_evidence = '';
            // print_r(base_url() . $row['file_price_ref_evidence']);
            // exit;
            if (file_exists('./'.$row['file_price_ref_evidence']) && $row['file_price_ref_evidence'] !== "" && $row['file_price_ref_evidence'] !== null) {
                $download_file_evidence = '<a href=".' . $row['file_price_ref_evidence'] . '" class="btn btn-sm btn-primary">
                    <i class="fa fa-download"></i>
                </a>';
            }
            $buttons     = $edit . ' ' . $download_file_evidence;

            if ($row['status_price_ref'] == 1) {
                $status = '<div class="badge badge-info text-light">Waiting Approval</div>';
            } elseif ($row['status_price_ref'] == 2) {
                $status = '<div class="badge badge-success text-light">Oke</div>';
            } elseif ($row['status_price_ref'] == 3) {
                $status = '<div class="badge badge-danger text-light">Reject</div>';
            } else {
                $status = '<div class="badge badge-warning text-light">Not Set</div>';
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['material_code'];
            $nestedData[]  = $row['nama'];
            $nestedData[]  = number_format($row['lower_price_before'], 2);
            $nestedData[]  = number_format($row['lower_price_after'], 2);
            $nestedData[]  = number_format($row['higher_price_before'], 2);
            $nestedData[]  = number_format($row['higher_price_after'], 2);
            $nestedData[]  = $row['expired_before'];
            $nestedData[]  = $row['expired_after'];
            $nestedData[]  = $status;
            $nestedData[]  = $row['alasan_price_ref_reject'];
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
}
