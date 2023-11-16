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

class Bom extends Admin_Controller
{
    protected $viewPermission     = 'Bom.View';
    protected $addPermission      = 'Bom.Add';
    protected $managePermission = 'Bom.Manage';
    protected $deletePermission = 'Bom.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Bom/Bom_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Bom');
        $this->template->page_icon('fas fa-user-tie');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function get_product_code()
    {
        $product_master = $this->input->post('product_master');

        $get_product_code = $this->Bom_model->get_product_master_by_id($product_master);

        echo json_encode(['product_code' => $get_product_code->product_code]);
    }

    public function get_material_category()
    {
        $id_material_type = $this->input->post('id_material_type');
        $getData = $this->db->get_where('ms_inventory_category2', ['id_type' => $id_material_type, 'deleted' => 0])->result();

        $hasil = '<option value="">- Material Jenis -</option>';
        foreach ($getData as $item) {
            $hasil = $hasil . '<option value="' . $item->id_category2 . '">' . $item->nama . '</option>';
        }

        echo $hasil;
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $this->template->title('Bom');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        $data = array();
        $data = [
            'id_bom' => $this->auth->user_id(),
            'product_master' => $this->Bom_model->get_product_master(),
            'proses' => $this->Bom_model->get_proses(),
            'material_category' => $this->Bom_model->get_material_category()
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function edit($id)
    {
        $get_bom = $this->Bom_model->get_bom_by_id($id);
        $get_bom_detail_material = $this->Bom_model->get_material_detail_by_id($id);
        $data = [
            'id_bom' => $id,
            'product_master' => $this->Bom_model->get_product_master(),
            'proses' => $this->Bom_model->get_proses(),
            'material_category' => $this->Bom_model->get_material_category(),
            'bom' => $get_bom,
            'detail_material' => $get_bom_detail_material
        ];
        $this->template->set($data);
        $this->template->render('form');
    }

    public function view($id)
    {
        $get_bom = $this->Bom_model->get_bom_by_id($id);
        $get_material_detail = $this->Bom_model->get_material_detail_by_id($id);
        $data = [
            'bom' => $get_bom,
            'detail_material' => $get_material_detail
        ];

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


        $desc = "View BOM Data " . $get_bom->id;
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

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $post = $this->input->post();

        $data = $post;

        $num_packaging = $this->db->query("SELECT id FROM ms_bom WHERE id = '" . $data['id_bom'] . "'")->num_rows();

        $this->db->trans_begin();
        if ($num_packaging > 0) {
            $id_bom = $post['id_bom'];
            $this->db->update('ms_bom', [
                'id_product' => $post['product_master'],
                'variant' => $post['product_code'],
                'qty_hopper' => $post['qty_hopper'],
                'waste_product' => $post['waste_product'],
                'waste_set_clean' => $post['waste_set_clean'],
                'diubah_oleh' => $this->auth->user_id(),
                'diubah_tgl' => date("Y-m-d H:i:s")
            ], ['id' => $post['id_bom']]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


            $desc = "Update BOM Data " . $data['id_bom'];
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
            $id_bom = $this->Bom_model->generate_id();
            $this->db->update('ms_bom_detail_material', ['id_bom' => $id_bom], ['id_bom' => $this->auth->user_id()]);
            $this->db->insert('ms_bom', [
                'id' => $id_bom,
                'id_product' => $post['product_master'],
                'variant' => $post['product_code'],
                'qty_hopper' => $post['qty_hopper'],
                'waste_product' => $post['waste_product'],
                'waste_set_clean' => $post['waste_set_clean'],
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date("Y-m-d H:i:s")
            ]);

            // Logging
            $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


            $desc = "Insert New BOM Data " . $id_bom;
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
                'msg'        => 'Failed save data Bom.  Please try again.',
                'status'    => 0
            );
            $keterangan     = "FAILED save data Bom " . $id_bom;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id_bom;
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data BOM.',
                'status'    => 1
            );
            $keterangan     = "SUCCESS save data BOM " . $id_bom;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id_bom;
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        }
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        echo json_encode($return);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ms_bom', ['id' => $id])->row_array();

        // Logging
        $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();


        $desc = "Delete BOM Data " . $id;
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
        $sql_detail_material = $this->db->delete('ms_bom_detail_material', ['id_bom' => $id]);
        $sql = $this->db->delete('ms_bom', ['id' => $id]);
        $errMsg = $this->db->error()['message'];
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $keterangan     = "FAILED " . $errMsg;
            $status         = 0;
            $nm_hak_akses   = $this->deletePermission;
            $kode_universal = $data['id'];
            $jumlah         = 1;
            $sql            = $this->db->last_query();
            $return    = array(
                'msg'        => "Failed delete data Bom. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Bom.',
                'status'    => 1
            );
            $keterangan     = "Delete data Bom " . $data['id'];
            $status         = 1;
            $nm_hak_akses   = $this->deletePermission;
            $kode_universal = $data['id'];
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
        $sql = "
            SELECT 
                a.*,
                b.nama as nm_product_master,
                d.full_name as nm_dibuat
            FROM 
                ms_bom a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                LEFT JOIN users d ON d.id_user = a.dibuat_oleh
            WHERE 1=1  
            AND (
                a.id_product LIKE '%" . $string . "%' OR
                a.variant LIKE '%" . $string . "%' OR
                a.dibuat_oleh LIKE '" . $string . "' OR
                b.nama LIKE '%" . $string . "%' OR
                d.full_name LIKE '%" . $string . "%'
            )
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'num',
            1 => 'id',
            2 => 'variant'
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

            $last_date = date("d/m/Y", strtotime($row['dibuat_tgl']));
            if ($row['diubah_tgl'] !== "" && $row['diubah_tgl'] !== null) {
                $last_date = date("d/m/Y", strtotime($row['diubah_tgl']));
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['nm_product_master'];
            $nestedData[]  = $row['variant'];
            $nestedData[]  = $row['qty_hopper'];
            $nestedData[]  = $row['waste_product'];
            $nestedData[]  = $row['waste_set_clean'];
            $nestedData[]  = $row['nm_dibuat'];
            $nestedData[]  = $last_date;
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

    public function add_detail_material()
    {
        $id_bom = $this->input->post('id_bom');
        $proses = $this->input->post('proses');
        $material_category = $this->input->post('material_category');
        $weight = $this->input->post('weight');

        $id = $this->Bom_model->generate_id_detail();

        $this->db->trans_begin();

        $this->db->insert('ms_bom_detail_material', [
            'id' => $id,
            'id_bom' => $id_bom,
            'id_proses' => $proses,
            'id_category1' => $material_category,
            'weight' => $weight
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = [
                'status' => 0
            ];
        } else {
            $this->db->trans_commit();
            $data = [
                'status' => 1
            ];
        }

        $hasil = '';
        $get_material_detail = $this->Bom_model->get_material_detail_by_id($id_bom);

        $n = 1;
        foreach ($get_material_detail as $material_detail) {
            $hasil = $hasil . '
            <tr>
                <td class="text-center">' . $n . '</td>
                <td class="text-center">' . $material_detail->nm_proses . '</td>
                <td class="text-center">' . $material_detail->material_category . '</td>
                <td class="text-center">' . $material_detail->weight . '</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger del_material_detail" data-id="' . $material_detail->id . '">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            ';
            $n++;
        }

        $data['hasil'] = $hasil;

        echo json_encode($data);
    }

    public function del_material_detail()
    {
        $id = $this->input->post('id');
        $id_bom = $this->input->post('id_bom');

        $this->db->trans_begin();

        $this->db->delete('ms_bom_detail_material', ['id' => $id]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $valid = 0;
        } else {
            $this->db->trans_commit();
            $valid = 1;
        }

        $hasil = '';
        $get_material_detail = $this->Bom_model->get_material_detail_by_id($id_bom);

        $n = 1;
        foreach ($get_material_detail as $material_detail) {
            $hasil = $hasil . '
            <tr>
                <td class="text-center">' . $n . '</td>
                <td class="text-center">' . $material_detail->nm_proses . '</td>
                <td class="text-center">' . $material_detail->material_category . '</td>
                <td class="text-center">' . $material_detail->weight . '</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger del_material_detail" data-id="' . $material_detail->id . '">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            ';
            $n++;
        }

        $data = [
            'valid' => $valid,
            'hasil' => $hasil
        ];

        echo json_encode($data);
    }
}
