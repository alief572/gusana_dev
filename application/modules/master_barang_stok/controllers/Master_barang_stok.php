<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Master_barang_stok extends Admin_Controller
{

    protected $viewPermission     = 'Master_Barang_Stok.View';
    protected $addPermission      = 'Master_Barang_Stok.Add';
    protected $managePermission = 'Master_Barang_Stok.Manage';
    protected $deletePermission = 'Master_Barang_Stok.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Master_barang_stok/Master_barang_stok_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Manage Data Barang Stok');
        $this->template->page_icon('fa fa-building-o');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        // $this->template->set('results', $packaging);
        $this->template->title('Master Barang Stok');
        $this->template->render('index');
    }

    public function add()
    {
        $data = [
            'id_barang_stok' => $this->Master_barang_stok_model->generate_id(),
            'kategori_stok' => $this->Master_barang_stok_model->get_kategori_stok_all(),
            'unit' => $this->Master_barang_stok_model->get_common('m_unit'),
            'packaging' => $this->Master_barang_stok_model->get_common('master_packaging')
        ];
        $this->template->set('results', $data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $data = [
            'id_barang_stok' => $id,
            'kategori_stok' => $this->Master_barang_stok_model->get_kategori_stok_all(),
            'unit' => $this->Master_barang_stok_model->get_common('m_unit'),
            'packaging' => $this->Master_barang_stok_model->get_common('master_packaging'),
            'barang_stok' => $this->Master_barang_stok_model->get_common_by_id($id, 'id_barang_stok', 'ms_barang_stok')
        ];
        $this->template->set('results', $data);
        $this->template->render('form_edit');
    }

    public function view($id)
    {
        $barang_stok = $this->Master_barang_stok_model->get_common_by_id($id, 'id_barang_stok', 'ms_barang_stok');
        $data = [
            'id_barang_stok' => $id,
            'kategori_stok' => $this->Master_barang_stok_model->get_kategori_stok_all(),
            'unit' => $this->Master_barang_stok_model->get_common('m_unit'),
            'packaging' => $this->Master_barang_stok_model->get_common('master_packaging'),
            'barang_stok' => $barang_stok
        ];

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


        $desc = "View Barang Stok Data " . $id . " - " . $barang_stok->nm_barang_stok;
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

        $this->template->set('results', $data);
        $this->template->render('view');
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ms_barang_stok', ['id_barang_stok' => $id])->row_array();

        $this->db->trans_begin();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


        $desc = "Delete Barang Stok Data " . $id . " - " . $data['nm_barang_stok'];
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

        $sql = $this->db->delete('ms_barang_stok', ['id_barang_stok' => $id]);
        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_barang_stok'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Barang Stok. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Barang Stok.',
                'status'    => 1
            );
            $keterangan     = "Delete data Barang Stok " . $data['id_barang_stok'] . ", Barang Stok name : " . $data['nm_packaging'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_barang_stok'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);

        $num_packaging = $this->db->query("SELECT nm_barang_stok FROM ms_barang_stok WHERE id_barang_stok = '" . $this->input->post('id_barang_stok') . "'")->num_rows();

        $post = $this->input->post();

        $get_data_kategori_stok = $this->db->get_where('ms_kategori_stok', ['id_kategori_stok' => $post['kategori_stok']])->row();
        $get_data_packaging = $this->db->get_where('master_packaging', ['id' => $post['packaging']])->row();
        $get_data_unit = $this->db->get_where('m_unit', ['id_unit' => $post['unit']])->row();

        $status = $this->input->post('status');

        $this->db->trans_begin();
        if ($num_packaging > 0) {
            $data = array();
            $data = [
                'nm_kategori_stok' => $get_data_kategori_stok->nm_kategori_stok,
                'nm_barang_stok' => ($post['nm_barang_stok'] ?: null),
                'item_code' => ($post['item_code'] ?: null),
                'trade_name' => ($post['trade_name'] ?: null),
                'brand' => ($post['brand'] ?: null),
                'spek' => ($post['spek'] ?: null),
                'id_packaging' => ($post['packaging'] ?: null),
                'nm_packaging' => $get_data_packaging->nm_packaging,
                'unit_id' => ($post['unit'] ?: null),
                'unit_nm' => $get_data_unit->nm_unit,
                'konversi' => ($post['konversi'] ?: null),
                'max_stok' => ($post['max_stok'] ?: null),
                'min_stok' => ($post['min_stok'] ?: null),
                'status' => $status,
                'dibuat_oleh' => $this->auth->user_id,
                'dibuat_tgl' => date("Y-m-d H:i:s")
            ];
            $this->db->update('ms_barang_stok', $data, ['id_barang_stok' => $this->input->post('id_barang_stok')]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


            $desc = "Update Barang Stok Data " . $this->input->post('id_barang_stok') . " - " . $this->input->post('nm_barang_stok');
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
            $data = array();
            $data = [
                'id_barang_stok' => ($post['id_barang_stok'] ?: null),
                'id_kategori_stok' => ($post['kategori_stok'] ?: null),
                'nm_kategori_stok' => $get_data_kategori_stok->nm_kategori_stok,
                'nm_barang_stok' => ($post['nm_barang_stok'] ?: null),
                'item_code' => ($post['item_code'] ?: null),
                'trade_name' => ($post['trade_name'] ?: null),
                'brand' => ($post['brand'] ?: null),
                'spek' => ($post['spek'] ?: null),
                'id_packaging' => ($post['packaging'] ?: null),
                'nm_packaging' => $get_data_packaging->nm_packaging,
                'unit_id' => ($post['unit'] ?: null),
                'unit_nm' => $get_data_unit->nm_unit,
                'konversi' => ($post['konversi'] ?: null),
                'max_stok' => ($post['max_stok'] ?: null),
                'min_stok' => ($post['min_stok'] ?: null),
                'status' => $status,
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date("Y-m-d H:i:s")
            ];
            $this->db->insert('ms_barang_stok', $data);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


            $desc = "Insert New Barang Stok Data " . $this->input->post('id_barang_stok') . " - " . $this->input->post('nm_barang_stok');
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
                'msg'        => 'Failed save data Barang Stok.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Barang Stok " . $data['id_barang_stok'] . ", Barang Stok : " . $data['nm_barang_stok'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_barang_stok'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Barang Stok.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Barang Stok " . $data['id_barang_stok'] . ", Barang Stok : " . $data['nm_barang_stok'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_barang_stok'];
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

        $string = $this->db->escape_like_str($search);
        $sql = "SELECT a.*,b.full_name FROM ms_barang_stok a LEFT JOIN users b ON b.id_user = a.dibuat_oleh WHERE 1=1 AND 
        (
            a.id_barang_stok LIKE '%" . $string . "%' OR 
            a.nm_kategori_stok LIKE '%" . $string . "%' OR
            a.nm_barang_stok LIKE '%" . $string . "%' OR
            a.item_code LIKE '%" . $string . "%' OR
            a.trade_name LIKE '%" . $string . "%' OR
            a.brand LIKE '%" . $string . "%' OR
            a.spek LIKE '%" . $string . "%' OR
            a.nm_packaging LIKE '%" . $string . "%' OR
            a.unit_nm LIKE '%" . $string . "%'
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

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id_barang_stok'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_barang_stok'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_barang_stok'] . '"><i class="fa fa-trash"></i></button>';
            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

            if ($row['status'] == 1) {
                $status = '<div class="badge badge-primary">Aktif</div>';
            } else {
                $status = '<div class="badge badge-danger">Tidak aktif</div>';
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['item_code'];
            $nestedData[]  = $row['nm_barang_stok'];
            $nestedData[]  = $row['nm_kategori_stok'];
            $nestedData[]  = $row['trade_name'];
            $nestedData[]  = $row['brand'];
            $nestedData[]  = $row['spek'];
            $nestedData[]  = $row['full_name'];
            $nestedData[]  = date("d/m/Y", strtotime($row['dibuat_tgl']));
            $nestedData[]  = $status;
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
}
