<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Master_man_power_rate extends Admin_Controller
{

    // protected $viewPermission     = 'Packaging.View';
    // protected $addPermission      = 'Packaging.Add';
    // protected $managePermission = 'Packaging.Manage';
    // protected $deletePermission = 'Packaging.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Master_man_power_rate/Master_man_power_rate_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Master Komponen Man Power Rate');
        $this->template->page_icon('fa fa-building-o');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Master Kompnen Man Power Rate');
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
        $sql = "SELECT * FROM ms_komp_man_power_rate WHERE 1=1 AND (nm_komp LIKE '%" . $string . "%' OR std_val LIKE '%" . $string . "%')";

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

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

            $tipe = '';
            if ($row['tipe'] == 1) {
                $tipe = 'Salary Direct Man Power';
            }
            if ($row['tipe'] == 2) {
                $tipe = 'BPJS';
            }
            if ($row['tipe'] == 3) {
                $tipe = 'Biaya Lain-lain';
            }


            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['nm_komp'];
            $nestedData[]  = $row['std_val'];
            $nestedData[]  = $tipe;
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
        $komp = $this->db->query('SELECT MAX(id) AS max_id FROM ms_komp_man_power_rate')->row();
        $id_komp = ($komp->max_id);
        $id_komp++;
        $data = [
            'id_komp' => $id_komp
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $this->auth->restrict($this->viewPermission);
        $get_komp = $this->db->get_where('ms_komp_man_power_rate', ['id' => $id])->row();
        $data = [
            'id_komp' => $id,
            'komp' => $get_komp
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->viewPermission);
        $get_komp = $this->db->get_where('ms_komp_man_power_rate', ['id' => $id])->row();
        $tipe_komponen = '';
        if ($get_komp->tipe == 1) {
            $tipe_komponen = 'Salary Direct Man Power';
        }
        if ($get_komp->tipe == 2) {
            $tipe_komponen = 'BPJS';
        }
        if ($get_komp->tipe == 3) {
            $tipe_komponen = 'Biaya Lain-lain';
        }
        $data = [
            'id_komp' => $id,
            'komp' => $get_komp,
            'tipe_komponen' => $tipe_komponen
        ];

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Komponen Man Power Rate " . $get_komp->id . " - " . $get_komp->nm_komp;
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

        $this->template->set($data);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $num = $this->db->query("SELECT nm_komp FROM ms_komp_man_power_rate WHERE id = '" . $post['id_komp'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num > 0) {
            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Update Komponen Man Power Rate " . $post['id_komp'] . " - " . $post['nm_komp'];
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

            $this->db->update('ms_komp_man_power_rate', [
                'nm_komp' => $post['nm_komp'],
                'std_val' => $post['std_val'],
                'tipe' => $post['tipe'],
                'keterangan' => $post['keterangan'],
                'diubah_oleh' => $this->auth->user_id(),
                'diubah_tgl' => date("Y-m-d H:i:s")
            ], ['id' => $post['id_komp']]);
        } else {
            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Insert New Komponen Man Power Rate " . $post['id_komp'] . " - " . $post['nm_komp'];
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

            $this->db->insert('ms_komp_man_power_rate', [
                'nm_komp' => $post['nm_komp'],
                'std_val' => $post['std_val'],
                'tipe' => $post['tipe'],
                'keterangan' => $post['keterangan'],
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date("Y-m-d H:i:s")
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed save data Komponen.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Komponen " . $post['id_komp'] . ", Nama Komponen : " . $post['nm_komp'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_komp'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Komponen.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Komponen " . $post['id_komp'] . ", Nama Komponen : " . $post['nm_komp'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_komp'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id_komp = $this->input->post('id');
        $data = $this->db->get_where('ms_komp_man_power_rate', ['id' => $id_komp])->row_array();

        $this->db->trans_begin();
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete Komponen Man Power Rate " . $data['id'] . " - " . $data['nm_komp'];
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

        $sql = $this->db->delete('ms_komp_man_power_rate', ['id' => $id_komp]);

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
                'msg'        => "Failed delete data Komponen Man Power Rate. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Unit.',
                'status'    => 1
            );
            $keterangan     = "Delete data Komponen Man Power Rate " . $data['id'] . ", Nama Komponen : " . $data['nm_komp'];
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
