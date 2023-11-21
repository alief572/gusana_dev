<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Kurs extends Admin_Controller
{

    protected $viewPermission     = 'Master_Kurs.View';
    protected $addPermission      = 'Master_Kurs.Add';
    protected $managePermission = 'Master_Kurs.Manage';
    protected $deletePermission = 'Master_Kurs.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Kurs/Kurs_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Master Kurs');
        $this->template->page_icon('fa fa-building-o');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Master Kurs');
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
        $sql = "SELECT * FROM ms_kurs WHERE 1=1 AND (curr_to_idr LIKE '%" . $string . "%' OR kurs LIKE '%" . $string . "%')";

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

            $month = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = date("d F Y", strtotime($row['tgl_periode_awal']));
            $nestedData[]  = date("d F Y", strtotime($row['tgl_periode_akhir']));
            $nestedData[]  = $row['curr_to_idr'];
            $nestedData[]  = number_format($row['kurs'],2);
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
        $mata_uang = $this->db->query("SELECT a.* FROM mata_uang a WHERE a.kode != 'IDR' ")->result();
        $ms_kurs = $this->db->query('SELECT MAX(id) AS id FROM ms_kurs')->row();
        $id_kurs = ($ms_kurs->id_kurs);
        $id_kurs++;
        $data = [
            'id_kurs' => $id_kurs,
            'mata_uang' => $mata_uang
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $this->auth->restrict($this->viewPermission);
        $ms_kurs = $this->db->get_where('ms_kurs',['id' => $id])->row();
        $mata_uang = $this->db->query("SELECT a.* FROM mata_uang a WHERE a.kode != 'IDR' ")->result();
        $data = [
            'id_kurs' => $id,
            'ms_kurs' => $ms_kurs,
            'mata_uang' => $mata_uang
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->viewPermission);
        $ms_kurs = $this->db->get_where('ms_kurs', array('id' => $id))->row();
        $month = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $data = [
            'id_kurs' => $id,
            'ms_kurs' => $ms_kurs
        ];
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Kurs Data " . $ms_kurs->id . " - " . $ms_kurs->curr_to_idr;
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

        $data = $post;
        $data['id'] = ($post['id_kurs'] ?: null);
        $data['curr_to_idr'] = ($post['curr_idr_to'] ?: null);
        $data['kurs'] = ($post['curr_to_idr'] ? str_replace(",","",$post['curr_to_idr']) : null);

        $num_kurs = $this->db->query("SELECT curr_to_idr FROM ms_kurs WHERE id = '" . $post['id_kurs'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num_kurs > 0) {
            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Update Kurs Data " . $data['id'] . " - " . $data['curr_idr_to'];
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

            $data['diubah_oleh'] = $this->auth->user_id();
            $data['diubah_tgl'] = date("Y-m-d H:i:s");
            $this->db->update('ms_kurs', [
                'curr_to_idr' => $this->input->post('curr_idr_to'),
                'kurs' => str_replace(",","",$this->input->post('curr_to_idr')),
                'tgl_periode_awal' => $this->input->post('tgl_periode_awal'),
                'tgl_periode_akhir' => $this->input->post('tgl_periode_akhir'),
                'diubah_oleh' => $this->auth->user_id(),
                'diubah_tgl' => date("Y-m-d H:i:s")
            ], ['id' => $post['id_kurs']]);
        } else {
            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Insert New Kurs Data " . $data['id'] . " - " . $data['curr_idr_to'];
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


            $this->db->insert('ms_kurs', [
                'curr_to_idr' => $this->input->post('curr_idr_to'),
                'kurs' => str_replace(",","",$this->input->post('curr_to_idr')),
                'tgl_periode_awal' => $this->input->post('tgl_periode_awal'),
                'tgl_periode_akhir' => $this->input->post('tgl_periode_akhir'),
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date("Y-m-d H:i:s")
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed save data Kurs.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Kurs " . $data['id'] . ", Kurs IDR - ".$post['curr_idr_to']." : " . $post['curr_to_idr'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Kurs.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Kurs " . $data['id'] . " Kurs IDR - ".$post['curr_idr_to']." : " . $post['curr_to_idr'];
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
        $data = $this->db->get_where('ms_kurs', ['id' => $id])->row_array();

        $this->db->trans_begin();
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete Kurs Data " . $data['id'] . " - " . $data['curr_to_idr'];
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

        $sql = $this->db->delete('ms_kurs', ['id' => $id]);

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
                'msg'        => "Failed delete data Unit. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Unit.',
                'status'    => 1
            );
            $keterangan     = "Delete data Unit " . $data['id'] . ", Curr : " . $data['curr_to_idr'];
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
