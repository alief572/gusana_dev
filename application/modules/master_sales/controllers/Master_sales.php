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

class Master_sales extends Admin_Controller
{
    protected $viewPermission     = 'Master_Sales.View';
    protected $addPermission      = 'Master_Sales.Add';
    protected $managePermission = 'Master_Sales.Manage';
    protected $deletePermission = 'Master_Sales.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Master_sales/Master_sales_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Divisi');
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
            SELECT a.*, b.kode_nama, b.kode_angka
            FROM
                employees a
                LEFT JOIN ms_kode_sales b ON b.id_sales = a.id
            WHERE
                1=1 AND (
                    a.name LIKE '%".$string."%' OR
                    b.kode_nama LIKE '%".$string."%' OR
                    b.kode_angka LIKE '%".$string."%'
                )
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id',
            1 => 'id'
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

            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
            $buttons     = $edit;

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['name'];
            $nestedData[]  = $row['kode_nama'];
            $nestedData[]  = $row['kode_angka'];
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
        $this->template->title('Master Kode Sales');
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
        $this->db->select('a.*, b.kode_nama, b.kode_angka');
        $this->db->from('employees a');
        $this->db->join('ms_kode_sales b', 'b.id_sales = a.id', 'left');
        $this->db->where(['a.id' => $id]);
        $get_data_sales = $this->db->get()->row();

        $this->template->set([
            'data_sales' => $get_data_sales
        ]);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->managePermission);
        $divisi = $this->db->get_where('m_divisi', array('id' => $id))->row();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Divisi Data " . $divisi->id . " - " . $divisi->divisi;
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

        $this->db->trans_begin();

        $check_kode_sales = $this->db->get_where('ms_kode_sales', ['id_sales' => $post['id_sales']])->num_rows();
        if($check_kode_sales > 0){
            $this->db->update('ms_kode_sales', ['kode_nama' => $post['kode_nama'], 'kode_angka' => $post['kode_angka'], 'diubah_oleh' => $this->auth->user_id(), 'diubah_tgl' => date('Y-m-d H:i:s')], ['id_sales' => $post['id_sales']]);
        }else{
            $this->db->insert('ms_kode_sales', [
                'id_sales' => $post['id_sales'],
                'nama_sales' => $post['nama_sales'],
                'kode_nama' => $post['kode_nama'],
                'kode_angka' => $post['kode_angka'],
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date('Y-m-d H:i:s')
            ]);
        }

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Insert New Kode Sales Data " . $post['id_sales'] . " - " . $post['nama_sales'].', Kode Nama : '. $post['kode_nama'].', Kode Angka : '. $post['kode_angka'];
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

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed save data Divisi.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Kode Sales " . $post['id_sales'] . ", Nama Sales : " . $post['nama_sales'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_sales'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Kode Sales.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Kode Sales " . $post['id_sales'] . ", Nama Sales : " . $post['nama_sales'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $post['id_sales'];
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

        $desc = "Delete Divisi Data " . $data['id'] . " - " . $data['divisi'];
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
                'msg'        => "Failed delete data Divisi. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Divisi.',
                'status'    => 1
            );
            $keterangan     = "Delete data Divisi " . $data['id'] . ", Divisi : " . $data['divisi'];
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
