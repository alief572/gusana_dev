<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Kategori_finish_goods extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf', 'upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Kategori_finish_goods/Kategori_finish_goods_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Manage Inventory 1');
        $this->template->page_icon('fa fa-building-o');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Kategori Finish Goods');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        $kategori_finish_goods = $this->db->query('SELECT MAX(id_kategori_finish_goods) AS id_kategori_finish_goods FROM kategori_finish_goods')->row();
        $id_kategori_finish_goods = ($kategori_finish_goods->id_kategori_finish_goods);
        $id_kategori_finish_goods++;
        $data = [
            'id_kategori_finish_goods' => $id_kategori_finish_goods
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $kategori_finish_goods = $this->db->get_where('kategori_finish_goods', array('id_kategori_finish_goods' => $id))->row();
        $data = [
            'id_kategori_finish_goods' => $id,
            'kategori_finish_goods' => $kategori_finish_goods
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->viewPermission);
        $kategori_finish_goods = $this->db->get_where('kategori_finish_goods', array('id_kategori_finish_goods' => $id))->row();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Kategori Finish Goods " . $kategori_finish_goods->id_kategori_finish_goods . " - " . $kategori_finish_goods->nm_kategori_finish_goods;
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
            'id_kategori_finish_goods' => $id,
            'kategori_finish_goods' => $kategori_finish_goods
        ];
        $this->template->set($data);
        $this->template->render('view');
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $num_kategori_finish_goods = $this->db->query("SELECT nm_kategori_finish_goods FROM kategori_finish_goods WHERE id_kategori_finish_goods = '" . $post['id_kategori_finish_goods'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num_kategori_finish_goods > 0) {
            $data = [
                'id_kategori_finish_goods' => ($post['id_kategori_finish_goods'] ?: null),
                'nm_kategori_finish_goods' => ($post['nm_kategori_finish_goods'] ?: null),
                'modified_by' => $this->auth->user_id(),
                'modified_on' => date('Y-m-d H:i:s')
            ];
            $this->db->update('kategori_finish_goods', $data, ['id_kategori_finish_goods' => $post['id_kategori_finish_goods']]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Update Kategori Finish Goods " . $data['id_kategori_finish_goods'] . " - " . $data['nm_kategori_finish_goods'];
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
            $data = [
                'id_kategori_finish_goods' => ($post['id_kategori_finish_goods'] ?: null),
                'nm_kategori_finish_goods' => ($post['nm_kategori_finish_goods'] ?: null),
                'created_by' => $this->auth->user_id(),
                'created_on' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('kategori_finish_goods', $data);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Insert New Kategori Finish Goods " . $data['id_kategori_finish_goods'] . " - " . $data['nm_kategori_finish_goods'];
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
                'msg'        => 'Failed save data Kategori Finish Goods.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Kategori Finish Goods " . $data['id_kategori_finish_goods'] . ", Kategori Finish Goods : " . $data['nm_kategori_finish_goods'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_kategori_finish_goods'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Kategori Finish Goods.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Kategori Finish Goods " . $data['id_kategori_finish_goods'] . ", Kategori Finish Goods name : " . $data['nm_kategori_finish_goods'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_kategori_finish_goods'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('kategori_finish_goods', ['id_kategori_finish_goods' => $id])->row_array();

        $this->db->trans_begin();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete Kategori Finish Goods " . $data['id_kategori_finish_goods'] . " - " . $data['nm_kategori_finish_goods'];
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

        $sql = $this->db->delete('kategori_finish_goods', ['id_kategori_finish_goods' => $id]);
        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_kategori_finish_goods'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Kategori Finish Goods. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Kategori Finish Goods.',
                'status'    => 1
            );
            $keterangan     = "Delete data Kategori Finish Goods " . $data['id_kategori_finish_goods'] . ", Kategori Finish Goods : " . $data['nm_kategori_finish_goods'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_kategori_finish_goods'];
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
        $sql = "SELECT *,(@row_number:=@row_number + 1) AS num FROM kategori_finish_goods, (SELECT @row_number:=0) as temp WHERE 1=1 AND (id_kategori_finish_goods LIKE '%" . $string . "%' OR nm_kategori_finish_goods LIKE '%" . $string . "%')";

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

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id_kategori_finish_goods'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_kategori_finish_goods'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_kategori_finish_goods'] . '"><i class="fa fa-trash"></i></button>';
            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['nm_kategori_finish_goods'];
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
