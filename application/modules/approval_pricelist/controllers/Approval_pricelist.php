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

class Approval_pricelist extends Admin_Controller
{
    protected $viewPermission     = 'Approval_Pricelist.View';
    protected $addPermission      = 'Approval_Pricelist.Add';
    protected $managePermission = 'Approval_Pricelist.Manage';
    protected $deletePermission = 'Approval_Pricelist.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Approval_pricelist/Approval_pricelist_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Approval Price | 审批价格');
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
            SELECT
                a.*,
                b.nama, 
                b.nama_mandarin, 
                c.full_name
            FROM
                ms_bom a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                LEFT JOIN users c ON c.id_user = a.approve_by
            WHERE
                1=1 AND (
                    a.variant LIKE '%" . $string . "%' OR
                    b.nama LIKE '%" . $string . "%' OR
                    a.qty_hopper LIKE '%" . $string . "%' OR
                    a.price_list LIKE '%" . $string . "%' OR
                    a.expired_date LIKE '%" . $string . "%'
                )
            ORDER BY a.req_app DESC
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id',
            1 => 'id'
        );

        // $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
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

            $status = 'Not Set';
            if ($row['req_app'] == "1") {
                // if ($row['sts_price_list'] == 1) {
                //     $status = '<div class="badge badge-success">Approved</div>';
                // } else if ($row['sts_price_list'] == 2) {
                //     $status = '<div class="badge badge-danger">Reject</div>';
                // } else {
                //     $status = '<div class="badge badge-warning text-light">Waiting Approval</div>';
                // }
                $status = '<span>Not Set</span><br><div class="badge badge-warning text-light">Waiting Approval</div>';
            } else {
                if ($row['sts_price_list'] == 1) {
                    $status = '<span>Ok</span><br><div class="badge badge-success">Approved</div>';
                }
                if ($row['sts_price_list'] == 2) {
                    $status = '<span>No</span><br><div class="badge badge-danger">Reject</div>';
                }
            }

            $buttons     = '';
            if ($row['req_app'] == 1) {
                $buttons = '
                    <button type="button" class="btn btn-sm btn-primary btn_approve" data-id_bom="' . $row['id'] . '">Approve</button>
                ';
            } else {
                $buttons = '
                    <button type="button" class="btn btn-sm btn-info view" data-id_bom="' . $row['id'] . '">View</button>
                ';
            }

            if ($row['expired_date'] == "0000-00-00" || $row['expired_date'] == '') {
                $expired_date = '';
            } else {
                $expired_date = date('d F Y', strtotime($row['expired_date']));
            }

            $app_by = $row['full_name'];
            $reject_by = $row['full_name'];

            $app_date = '';
            $reject_date = '';
            if ($row['approve_date'] !== null) {
                $app_date = date('d F Y', strtotime($row['approve_date']));
                $reject_date = date('d F Y', strtotime($row['approve_date']));
            }

            if ($row['sts_price_list'] !== '1' && $row['sts_price_list'] !== '2') {
                $app_by = '';
                $reject_by = '';

                $app_date = '';
                $reject_date = '';
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['variant'];
            $nestedData[]  = $row['nama'];
            $nestedData[]  = $row['nama_mandarin'];
            $nestedData[]  = number_format($row['qty_hopper'], 2);
            $nestedData[]  = number_format($row['price_list'], 2);
            $nestedData[]  = $expired_date;
            $nestedData[]  = $status;
            if ($row['sts_price_list'] == 1) {
                $nestedData[]  = $app_by;
                $nestedData[]  = $app_date;
            } else {
                $nestedData[]  = $reject_by;
                $nestedData[]  = $reject_date;
            }
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
        $this->template->title('Approval Price | 审批价格');
        $this->template->render('index');
    }

    public function add($id_bom)
    {
        $this->auth->restrict($this->viewPermission);
        $data = array();

        $get_data = $this->db->query("
            SELECT 
                a.*,
                b.nama,
                c.cost_price_final
            FROM
                ms_bom a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                LEFT JOIN product_price c ON c.no_bom = a.id AND c.deleted_by IS NULL
            WHERE
                a.id = '" . $id_bom . "'
        ")->row();

        // echo '<pre>'; 
        // print_r($get_data);
        // echo'</pre>';
        // exit;

        $data = [
            'get_data' => $get_data
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

    public function view($id_bom)
    {
        $this->auth->restrict($this->managePermission);
        $get_data = $this->db->query("
            SELECT 
                a.*,
                b.nama,
                c.cost_price_final
            FROM
                ms_bom a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                LEFT JOIN product_price c ON c.no_bom = a.id
            WHERE
                a.id = '" . $id_bom . "'
        ")->row();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Price List" . $get_data->id_product . ' - ' . $get_data->nama;
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
            'get_data' => $get_data
        ]);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $get_product = $this->db->select('a.*, b.nama');
        $get_product = $this->db->from('ms_bom a');
        $get_product = $this->db->join('ms_product_category3 b', 'b.id_category3 = a.id_product');
        $get_product = $this->db->where(['a.id' => $post['id_bom']]);
        $get_product = $this->db->get()->row();

        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $this->db->trans_begin();

        if ($post['approval_action'] == 1) {
            $this->db->update('ms_bom', [
                'price_list' => str_replace(',', '', $post['price_approve']),
                'propose_price_list' => 0,
                'sts_price_list' => $post['approval_action'],
                'dist_price' => str_replace(',', '', $post['dist_price']),
                'req_app' => 0,
                'expired_date' => $post['expired_date'],
                'reason' => $post['reason'],
                'approve_by' => $this->auth->user_id(),
                'approve_date' => date('Y-m-d H:i:s')
            ], [
                'id' => $post['id_bom']
            ]);

            $desc = "Approve Price List" . $get_product->id_product . " - " . $get_product->nama;
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

            $this->db->insert('ms_log_app_price_list', [
                'id_bom' => $post['id_bom'],
                'id_product' => $post['id_product'],
                'product_costing' => str_replace(',', '', $post['product_costing']),
                'product_costing_per_kg' => str_replace(',', '', $post['product_costing_per_kg']),
                'price_list_before' => str_replace(',', '', $post['price_before']),
                'propose_price_list' => str_replace(',', '', $post['propose_price_list']),
                'app_type' => $post['approval_action'],
                'expired_date' => $post['expired_date'],
                'approve_by' => $this->auth->user_id(),
                'approve_date' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->db->update('ms_bom', [
                'price_list' => str_replace(',', '', $post['price_approve']),
                'propose_price_list' => 0,
                'sts_price_list' => $post['approval_action'],
                'req_app' => 0,
                'expired_date' => $post['expired_date'],
                'reason' => $post['reason'],
                'approve_by' => $this->auth->user_id(),
                'approve_date' => date('Y-m-d H:i:s')
            ], [
                'id' => $post['id_bom']
            ]);

            $desc = "Reject Price List" . $get_product->id_product . " - " . $get_product->nama;
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

            $this->db->insert('ms_log_app_price_list', [
                'id_bom' => $post('id_bom'),
                'id_product' => $post('id_product'),
                'product_costing' => str_replace(',', '', $post['product_costing']),
                'product_costing_per_kg' => str_replace(',', '', $post['product_costing_per_kg']),
                'price_list_before' => str_replace(',', '', $post['price_before']),
                'propose_price_list' => str_replace(',', '', $post['propose_price_list']),
                'app_type' => $post['approval_action'],
                'expired_date' => $post['expired_date'],
                'approve_by' => $this->auth->user_id(),
                'approve_date' => date('Y-m-d H:i:s')
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed Approval Pricelist.  Please try again.',
                'status'    => 0
            );
            $keterangan     = 'Failed Approval Pricelist.  Please try again.';
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_bom'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Approval Pricelist.',
                'status'    => 1
            );
            $keterangan     = 'Success Approval Pricelist.';
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_bom'];
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

        $desc = "Delete Approval Pricelist Data " . $data['id'] . " - " . $data['divisi'];
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
                'msg'        => "Failed delete data Approval Pricelist. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Approval Pricelist.',
                'status'    => 1
            );
            $keterangan     = "Delete data Approval Pricelist " . $data['id'] . ", Approval Pricelist : " . $data['divisi'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }
}
