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

class Menu_so extends Admin_Controller
{
    protected $viewPermission     = 'Menu_SO.View';
    protected $addPermission      = 'Menu_SO.Add';
    protected $managePermission = 'Menu_SO.Manage';
    protected $deletePermission = 'Menu_SO.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Menu_so/Menu_so_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Menu_so');
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
                b.nama as nm_product
            FROM
                ms_so a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
            WHERE
                1=1 AND (
                    a.id_so LIKE '%".$string."%' OR
                    b.nama LIKE '%".$string."%' OR
                    a.packaging LIKE '%".$string."%' OR
                    b.konversi LIKE '%".$string."%' OR
                    a.due_date LIKE '%".$string."%' OR
                    a.released LIKE '%".$string."%' OR
                    a.sisa_so LIKE '%".$string."%'
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

            // $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
            // $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_so'] . '"><i class="fa fa-trash"></i></button>';
            $buttons     = $delete;
            // $buttons = '';

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['id_so'];
            $nestedData[]  = $row['nm_product'];
            $nestedData[]  = $row['packaging'];
            $nestedData[]  = $row['konversi'];
            $nestedData[]  = $row['propose'];
            $nestedData[]  = date('d/m/Y',strtotime($row['due_date']));
            $nestedData[]  = $row['released'];
            $nestedData[]  = $row['sisa_so'];
            $nestedData[]  = '<input type="checkbox" name="pilih[]" value="'.$row['id_so'].'">';
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
        $this->template->title('Menu SO');
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

        $desc = "View Menu_so Data " . $divisi->id . " - " . $divisi->divisi;
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

        $data = $post;
        $data['id'] = ($post['id_divisi'] ?: null);
        $data['divisi'] = ($post['nama_divisi'] ?: null);
        $data['dibuat_oleh'] = $this->auth->user_id();
        $data['dibuat_tgl'] = date("Y-m-d H:i:s");

        $num_divisi = $this->db->query("SELECT divisi FROM m_divisi WHERE id = '" . $post['id_divisi'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num_divisi > 0) {
            $this->db->update('m_divisi', ['divisi' => $this->input->post('nama_divisi')], ['id' => $post['id_divisi']]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Update Menu_so Data " . $data['id'] . " - " . $data['divisi'];
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
            $this->db->insert('m_divisi', [
                'divisi' => $this->input->post('nama_divisi'),
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date("Y-m-d H:i:s")
            ]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Insert New Menu_so Data " . $data['id'] . " - " . $data['divisi'];
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
                'msg'        => 'Failed save data Menu_so.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Menu_so " . $data['id'] . ", Menu_so : " . $data['divisi'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Menu_so.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Menu_so " . $data['id'] . ", Menu_so : " . $data['divisi'];
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
        $data = $this->db->get_where('ms_so', ['id_so' => $id])->row_array();

        $this->db->trans_begin();
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete SO Data " . $data['id_so'];
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

        $sql = $this->db->delete('ms_so', ['id_so' => $id]);
        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_so'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Menu_so. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Menu_so.',
                'status'    => 1
            );
            $keterangan     = "Delete SO " . $data['id_so'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_so'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }
}
