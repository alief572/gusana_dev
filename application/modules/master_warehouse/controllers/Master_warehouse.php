<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Master_warehouse extends Admin_Controller
{

    protected $viewPermission     = 'Master_Warehouse.View';
    protected $addPermission      = 'Master_Warehouse.Add';
    protected $managePermission = 'Master_Warehouse.Manage';
    protected $deletePermission = 'Master_Warehouse.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Master_warehouse/Master_warehouse_model',
            'Aktifitas/aktifitas_model'
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Manage Data Warehouse');
        $this->template->page_icon('fa fa-building-o');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        // $this->template->set('results', $warehouse);
        $this->template->title('Master Warehouse');
        $this->template->render('index');
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

        $string = $this->db->escape_like_str($search);
        $sql = "SELECT * FROM m_warehouse WHERE 1=1 AND (
            id LIKE '%" . $string . "%' 
            OR warehouse_nm LIKE '%" . $string . "%'
        )";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id',
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

            $warehouse_type = [
                1 => 'Material',
                2 => 'Production',
                3 => 'Finish Goods'
            ];

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['warehouse_nm'];
            $nestedData[]  = $warehouse_type[$row['warehouse_type']];
            // $nestedData[]  = $row['email'];
            // $nestedData[]  = $row['address'];
            // $nestedData[]  = $status[$row['status']];
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

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        $warehouse = $this->db->query('SELECT MAX(id) AS id FROM m_warehouse')->row();
        $id_warehouse = ($warehouse->id);
        $id_warehouse++;
        $data = [
            'id_warehouse' => $id_warehouse
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $warehouse = $this->db->get_where('m_warehouse', array('id' => $id))->row();
        $data = [
            'id_warehouse' => $id,
            'warehouse' => $warehouse
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->viewPermission);
        $warehouse = $this->db->get_where('m_warehouse', array('id' => $id))->row();

        $warehouse_type = [
            1 => 'Material',
            2 => 'Production',
            3 => 'Finish Goods'
        ];

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Warehouse Data " . $warehouse->id . " - " . $warehouse->warehouse_nm;
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

        $data = [
            'id_warehouse' => $id,
            'warehouse' => $warehouse,
            'warehouse_type' => $warehouse_type[$warehouse->warehouse_type]
        ];
        $this->template->set($data);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $data = $post;
        $data['id'] = ($post['id'] ?: null);
        $data['warehouse_nm'] = ($post['warehouse_nm'] ?: null);
        $data['warehouse_type'] = ($post['warehouse_type'] ?: null);
        $data['dibuat_oleh'] = $this->auth->user_id();
        $data['dibuat_tgl'] = date("Y-m-d H:i:s");

        $num_warehouse = $this->db->query("SELECT warehouse_nm FROM m_warehouse WHERE id = '" . $post['id'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num_warehouse > 0) {
            $this->db->update('m_warehouse', $data, ['id' => $post['id']]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Update Warehouse Data " . $data['id'] . " - " . $data['nm_warehouse'];
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
            $this->db->insert('m_warehouse', $data);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Insert New Warehouse Data " . $data['id'] . " - " . $data['nm_warehouse'];
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
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed save data Warehouse.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Warehouse " . $data['id'] . ", Warehouse name : " . $data['nm_warehouse'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Warehouse.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Warehouse " . $data['id'] . ", Warehouse name : " . $data['nm_warehouse'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('m_warehouse', ['id' => $id])->row_array();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete Warehouse Data " . $data['id'] . " - " . $data['nm_warehouse'];
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

        $this->db->trans_begin();
        $sql = $this->db->delete('m_warehouse', ['id' => $id]);
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
                'msg'        => "Failed delete data Warehouse. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Warehouse.',
                'status'    => 1
            );
            $keterangan     = "Delete data Warehouse " . $data['id'] . ", Warehouse name : " . $data['nm_warehouse'];
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
