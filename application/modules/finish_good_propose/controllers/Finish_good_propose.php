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

class Finish_good_propose extends Admin_Controller
{
    protected $viewPermission     = 'Finish_Good.View';
    protected $addPermission      = 'Finish_Good.Add';
    protected $managePermission = 'Finish_Good.Manage';
    protected $deletePermission = 'Finish_Good.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Finish_good_propose/Finish_good_propose_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Finish_good_propose');
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
            SELECT a.*,
                b.nm_packaging,
                IF(c.qty_asli > 0, c.qty_asli, 0) as qty_asli,
                IF(a.moq > 0, a.moq, 0) as moq
            FROM
                ms_product_category3 a 
                LEFT JOIN master_packaging b ON b.id = a.packaging
                LEFT JOIN ms_stock_product c ON c.id_product = a.id_category3
            WHERE (1=1 AND (SELECT COUNT(aa.id_so) AS count_so FROM ms_so aa WHERE aa.id_product = a.id_category3 AND aa.batch <> aa.released) < 1) AND (
                a.nama LIKE '%" . $string . "%' OR
                a.konversi LIKE '%" . $string . "%' OR
                a.min_stok LIKE '%" . $string . "%' OR
                a.moq LIKE '%" . $string . "%' OR
                b.nm_packaging LIKE '%" . $string . "%' 
            ) GROUP BY a.id_category3
        ";

        $totalData = $this->db->query("SELECT a.id_category3 FROM ms_product_category3 a WHERE (SELECT COUNT(aa.id_so) AS count_so FROM ms_so aa WHERE aa.id_product = a.id_category3 AND aa.batch <> aa.released) < 1")->num_rows();
        $totalFiltered = $this->db->query("SELECT a.id_category3 FROM ms_product_category3 a WHERE (SELECT COUNT(aa.id_so) AS count_so FROM ms_so aa WHERE aa.id_product = a.id_category3 AND aa.batch <> aa.released) < 1")->num_rows();

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




        // $nomor = 1;
        foreach ($query->result_array() as $row) {
            $check_data = $this->db->query('SELECT id_so FROM ms_so WHERE id_product = "' . $row['id_category3'] . '" AND batch <> released')->row();

            if (!empty($check_data)) {
            } else {

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
                // $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
                // $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
                // $buttons     = $view . "&nbsp;" . $edit . "&nbsp;" . $delete;
                $buttons = '';

                $propose = 0;
                // if ($row['qty_asli'] < $row['min_stok']) {
                //     $propose = $row['moq'];
                // }

                $get_booking_stock = $this->db->query('
                    SELECT
                        SUM(a.qty) AS ttl_booking_stock
                    FROM
                        ms_penawaran_detail a
                        JOIN ms_penawaran b ON b.id_penawaran = a.id_penawaran 
                    WHERE
                        a.id_product = "' . $row['id_category3'] . '" AND
                        b.sts = "so_created"
                ')->row();

                $nestedData   = array();
                $nestedData[]  = '<input type="checkbox" name="finish_goods_nm_' . $row['id_category3'] . '" class="pilih pilih_' . $row['id_category3'] . '" value="' . $row['id_category3'] . '" data-id_category3="' . $row['id_category3'] . '" data-qty_asli="' . $row['qty_asli'] . '" data-moq="' . $row['moq'] . '" data-min_stok="' . $row['min_stok'] . '">';
                $nestedData[]  = $nomor;
                $nestedData[]  = $row['nama'];
                $nestedData[]  = $row['nm_packaging'];
                $nestedData[]  = number_format($row['konversi']);
                $nestedData[]  = number_format($row['qty_asli']);
                $nestedData[]  = number_format($row['qty_asli'] / $row['konversi']);
                $nestedData[]  = number_format($get_booking_stock->ttl_booking_stock);
                $nestedData[]  = number_format(($row['qty_asli'] / $row['konversi']) - $get_booking_stock->ttl_booking_stock);
                $nestedData[]  = number_format($row['min_stok']);
                $nestedData[]  = $row['moq'];
                $nestedData[]  = '<input type="number" class="form-control form-control-sm propose_val propose_' . $row['id_category3'] . '" value="' . $propose . '" readonly>';
                $nestedData[]  = $buttons;
                $data[] = $nestedData;
                $urut1++;
                $urut2++;
                // $nomor++;
            }
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
        $this->template->title('Finish Goods');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        $data = array();

        $list_id_category3 = $this->input->post('list_id_category3');
        // echo '<pre>'; 
        // print_r($list_id_category3);
        // echo'</pre>';
        // exit;

        $code_so = $this->db->query("SELECT MAX(id_so) as max_id_so FROM ms_so WHERE id_so LIKE '%SO/" . date('Y') . "/" . date('m') . "/" . date('d') . "%'")->row();
        $kodeBarang = $code_so->max_id_so;
        $urutan = (int) substr($kodeBarang, 14, 6);
        // $urutan++;
        $tahun = date('Y/m/d/');
        $huruf = "SO/";
        $kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

        $kump_id_category3 = array();

        $x = 1;
        foreach ($list_id_category3 as $category3) {

            $kodeBarang2 = $kodecollect;
            $urutan = (int) substr($kodeBarang2, 14, 6);
            $urutan++;
            $tahun = date('Y/m/d/');
            $huruf = "SO/";
            $kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

            $get_query = $this->db->query("SELECT a.*, b.nm_packaging, c.qty_asli FROM ms_product_category3 a LEFT JOIN master_packaging b ON b.id = a.packaging LEFT JOIN ms_stock_product c ON c.id_product = a.id_category3 WHERE a.id_category3 = '" . $category3['id_category3'] . "' ")->row();

            $kump_id_category3[] = $category3['id_category3'];

            $get_bom_product = $this->db->get_where('ms_bom', ['id_product' => $category3['id_category3']])->result();
            $list_bom_product = '';

            foreach ($get_bom_product as $bom_product) :
                $list_bom_product .= '<option value="' . $bom_product->id . '">' . $bom_product->qty_hopper . '</option>';
            endforeach;

            $data[] = '
                <tr>
                    <td class="text-center">' . $x . '</td>
                    <td class="text-center">
                        ' . $get_query->nama . '
                        <input type="hidden" name="nm_product_so_' . $category3['id_category3'] . '" value="' . $get_query->nama . '">
                    </td>
                    <td class="text-center">
                        ' . $get_query->nm_packaging . '
                        <input type="hidden" name="product_packaging_' . $category3['id_category3'] . '" value="' . $get_query->nm_packaging . '">
                    </td>
                    <td class="text-center">' . number_format($get_query->konversi, 2) . '</td>
                    <td class="text-center">' . number_format($get_query->qty_asli, 2) . '</td>
                    <td class="text-center">' . number_format(($get_query->qty_asli / $get_query->konversi), 2) . '</td>
                    <td class="text-center">' . number_format(0, 2) . '</td>
                    <td class="text-center">' . number_format(($get_query->qty_asli / $get_query->konversi), 2) . '</td>
                    <td class="text-center">' . number_format($get_query->min_stok, 2) . '</td>
                    <td class="text-center">' . $get_query->moq . '</td>
                    <td class="text-center">
                        ' . number_format($category3['propose'], 2) . '
                        <input type="hidden" name="propose_val_' . $category3['id_category3'] . '" value="' . $category3['propose'] . '">
                    </td>
                    <td class="text-center">
                        <select class="form-control form-control-sm" name="bom_' . $category3['id_category3'] . '" required>
                            <option value="">- Lot Size (Kg) -</option>
                            ' . $list_bom_product . '
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="date" name="due_date_' . $category3['id_category3'] . '" id="" class="form-control form-control-sm" min="' . date('Y-m-d') . '" required>
                    </td>
                </tr>
            ';

            $x++;
        }


        // echo '<pre>'; 
        // print_r($data);
        // echo'</pre>';
        // exit;

        $this->template->set([
            'data_table' => $data,
            'list_id_category3' => $kump_id_category3
        ]);
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

        $desc = "View Finish_good_propose Data " . $divisi->id . " - " . $divisi->divisi;
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

        $val_propose = 1;

        $this->db->trans_begin();
        $list_id_category3 = explode(",", $this->input->post('list_id_category3'));
        foreach ($list_id_category3 as $category3) {

            if ($post['propose_val_' . $category3] < 1) {
                $val_propose = 0;
            }

            if ($post['bom_' . $category3] == '') {
                $val_propose = 3;
            }

            if ($post['bom_' . $category3]) {
                $this->db->select('a.qty_hopper');
                $this->db->from('ms_bom a');
                $this->db->where('id', $post['bom_' . $category3]);
                $get_lot_size = $this->db->get()->row();

                if ($post['propose_val_' . $category3] < $get_lot_size->qty_hopper) {
                    $val_propose = 2;
                }
            }
        }

        if ($val_propose == 1) {
            foreach ($list_id_category3 as $category3) {
                $code_so = $this->db->query("SELECT MAX(id_so) as max_id_so FROM ms_so WHERE id_so LIKE '%SO/" . date('Y') . "/" . date('m') . "/" . date('d') . "%'")->row();
                $kodeBarang = $code_so->max_id_so;
                $urutan = (int) substr($kodeBarang, 14, 6);
                $urutan++;
                $tahun = date('Y/m/d/');
                $huruf = "SO/";
                $kode_so = $huruf . $tahun . sprintf("%06s", $urutan);

                $id_so = $kode_so;
                $nm_product_so = $this->input->post('nm_product_so_' . $category3);
                $propose_val = $this->input->post('propose_val_' . $category3);
                $product_packaging = $this->input->post('product_packaging_' . $category3);
                $due_date = $this->input->post('due_date_' . $category3);
                $bom = $this->input->post('bom_' . $category3);

                $get_product = $this->db->get_where('ms_product_category3', ['id_category3' => $category3])->row();
                $get_bom = $this->db->get_where('ms_bom', ['id' => $bom])->row();

                $this->db->insert('ms_so', [
                    'id_so' => $id_so,
                    'id_product' => $category3,
                    'packaging' => $product_packaging,
                    'konversi' => $get_product->konversi,
                    'propose' => $propose_val,
                    'due_date' => $due_date,
                    'released' => 0,
                    'sisa_so' => $propose_val,
                    'id_bom' => $bom,
                    'batch' => ($propose_val / $get_bom->qty_hopper),
                    'dibuat_oleh' => $this->auth->user_id(),
                    'dibuat_tgl' => date('Y-m-d H:i:s')
                ]);

                // Logging
                $get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();

                $desc = "New SO Data " . $id_so . " - " . $nm_product_so;
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
        }

        if ($this->db->trans_status() === FALSE || $val_propose !== 1) {
            $this->db->trans_rollback();
            if ($val_propose == 0) {
                $msg = 'Maaf, mohon pastikan semua kolom propose sudah terisi';
            } else if ($val_propose == 2) {
                $msg = 'Maaf, pastikan nilai propose tidak dibawah nilai lot size';
            } else if ($val_propose == 3) {
                $msg = 'Maaf, pastikan kolom Lot Size sudah terisi semua';
            } else {
                $msg = 'Failed to create Product SO.  Please try again.';
            }
            $return    = array(
                'msg'        => $msg,
                'status'    => 0
            );
            $keterangan     = "Failed to create Product SO";
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = '';
            $jumlah         = 1;
            $sql            = $this->db->last_query();
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Success Save data Finish_Good.',
                'status'    => 1
            );
            $keterangan     = "Success create product SO";
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = '';
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

        $desc = "Delete Finish_good_propose Data " . $data['id'] . " - " . $data['divisi'];
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
                'msg'        => "Failed delete data Finish_Good. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Finish_Good.',
                'status'    => 1
            );
            $keterangan     = "Delete data Finish_good_propose " . $data['id'] . ", Finish_good_propose : " . $data['divisi'];
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
