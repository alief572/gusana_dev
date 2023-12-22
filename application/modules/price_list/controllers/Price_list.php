<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Price_list extends Admin_Controller
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
            'Price_list/Price_list_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Manage Price_list');
        $this->template->page_icon('fa fa-building-o');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Price List');
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
        $sql = "
            SELECT a.*, b.nama, b.product_code, b.konversi, b.product_code, c.nm_packaging
            FROM
                ms_bom a
                JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                LEFT JOIN master_packaging c ON c.id = b.packaging
            WHERE
                1=1 AND a.sts_price_list = '1' AND (
                    b.product_code LIKE '%".$string."%' OR
                    b.nama LIKE '%".$string."%' OR
                    c.nm_packaging LIKE '%".$string."%' OR
                    a.qty_hopper LIKE '%".$string."%' OR
                    a.price_list LIKE '%".$string."%' OR
                    (a.price_list * b.konversi) LIKE '%".$string."%'
                )
        ";

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

            $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id_unit'] . '"><i class="fa fa-eye"></i></button>';
            $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id_unit'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_unit'] . '"><i class="fa fa-trash"></i></button>';
            $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['product_code'];
            $nestedData[]  = $row['nama'];
            $nestedData[]  = number_format($row['konversi'],2);
            $nestedData[]  = number_format($row['qty_hopper'],2);
            $nestedData[]  = number_format($row['price_list'],2);
            $nestedData[]  = number_format(($row['price_list'] * $row['konversi']),2);
            // $nestedData[]  = $row['email'];
            // $nestedData[]  = $row['address'];
            // $nestedData[]  = $status[$row['status']];
            // $nestedData[]  = $buttons;
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
        $unit = $this->db->query('SELECT MAX(id_unit) AS id_unit FROM m_unit')->row();
        $id_unit = ($unit->id_unit);
        $id_unit++;
        $data = [
            'id_unit' => $id_unit
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $this->auth->restrict($this->viewPermission);
        $unit = $this->db->get_where('m_unit', array('id_unit' => $id))->row();
        $data = [
            'id_unit' => $id,
            'unit' => $unit
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $this->auth->restrict($this->viewPermission);
        $unit = $this->db->get_where('m_unit', array('id_unit' => $id))->row();
        $data = [
            'id_unit' => $id,
            'unit' => $unit
        ];

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "View Price_list Data " . $unit->id_unit . " - " . $unit->nm_unit;
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
        $data['id_unit'] = ($post['id_unit'] ?: null);
        $data['nm_unit'] = ($post['nm_unit'] ?: null);
        $data['create_by'] = $this->auth->user_id();
        $data['create_date'] = date("Y-m-d H:i:s");

        $num_unit = $this->db->query("SELECT nm_unit FROM m_unit WHERE id_unit = '" . $post['id_unit'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num_unit > 0) {
            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Update Price_list Data " . $data['id_unit'] . " - " . $data['nm_unit'];
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

            $this->db->update('m_unit', $data, ['id_unit' => $post['id_unit']]);
        } else {
            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

            $desc = "Insert New Price_list Data " . $data['id_unit'] . " - " . $data['nm_unit'];
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

            $this->db->insert('m_unit', $data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Failed save data Price_list.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Price_list " . $data['id_unit'] . ", Price_list name : " . $data['nm_unit'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_unit'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Price_list.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data Price_list " . $data['id_unit'] . ", Price_list name : " . $data['nm_unit'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_unit'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id_unit = $this->input->post('id');
        $data = $this->db->get_where('m_unit', ['id_unit' => $id_unit])->row_array();

        $this->db->trans_begin();
        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

        $desc = "Delete Price_list Data " . $data['id_unit'] . " - " . $data['nm_unit'];
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

        $sql = $this->db->delete('m_unit', ['id_unit' => $id_unit]);

        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_unit'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Price_list. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Price_list.',
                'status'    => 1
            );
            $keterangan     = "Delete data Price_list " . $data['id_unit'] . ", Price_list : " . $data['nm_packaging'];
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $data['id_unit'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }
}
