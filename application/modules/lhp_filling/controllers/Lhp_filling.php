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

class Lhp_filling extends Admin_Controller
{
    protected $viewPermission     = 'LHP_Filling.View';
    protected $addPermission      = 'LHP_Filling.Add';
    protected $managePermission = 'LHP_Filling.Manage';
    protected $deletePermission = 'LHP_Filling.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Lhp_filling/Lhp_filling_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Lhp_filling');
        $this->template->page_icon('fas fa-user-tie');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function create_spk()
    {
        $id_so = $this->input->post('id_so');
        $id_proses = $this->input->post('id_proses');
        // $batch_ke = $this->input->post('batch_ke');

        $get_so = $this->db->get_where('ms_so', ['id_so' => $id_so])->row();
        $get_product = $this->db->get_where('ms_product_category3', ['id_category3' => $get_so->id_product])->row();

        $code_spk = $this->db->query("SELECT MAX(id_spk) as max_id_spk FROM ms_create_spk WHERE id_spk LIKE '%SPK-M-" . date('Ymd') . "-%' AND id_proses = '-'")->row();
        $kodeBarang = $code_spk->max_id_spk;
        $urutan = (int) substr($kodeBarang, 15, 5);
        $urutan++;
        $tahun = date('Ymd');
        $huruf = "SPK-M-";
        $kode_spk = $huruf . $tahun . '-' . sprintf("%05s", $urutan);

        $get_max_batch_ke = $this->db->query("SELECT MAX(batch_ke) AS max_batch_ke FROM ms_create_spk WHERE id_so = '" . $id_so . "' AND id_proses = '-'")->row();
        $batch_ke = ($get_max_batch_ke->max_batch_ke + 1);

        $this->db->trans_begin();

        $this->db->insert('ms_create_spk', [
            'id_spk' => $kode_spk,
            'id_so' => $id_so,
            'id_product' => $get_product->id_category3,
            'tipe' => 'SPK Material',
            'tgl_tarik' => date("Y-m-d H:i:s"),
            'batch_ke' => $batch_ke,
            'id_proses' => $id_proses,
            'ditarik_oleh' => $this->auth->user_id()
        ]);

        // $this->db->update('ms_so', ['released' => $batch_ke], ['id_so' => $id_so]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $return    = array(
                'msg'        => 'Maaf, SPK Mixing gagal dibuat',
                'status'    => 0,
                'id_spk' => $kode_spk
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Selamat, SPK Mixing telah berhasil terbit',
                'status'    => '-',
                'id_spk'    => $kode_spk
            );
        }
        echo json_encode($return);
    }

    public function get_free_material()
    {
        $id_spk = $this->input->post('id_spk');

        $hasil = array();
        $hasil[] = '<option value="">- Material Category -</option>';

        $get_free_material = $this->db->query('SELECT a.*, b.id_category1 as id_material FROM ms_inventory_category1 a LEFT JOIN ms_spk_material_tambahan b ON b.id_category1 = a.id_category1 WHERE a.deleted = "0" AND b.id_category1 IS NULL GROUP BY a.id_category1')->result();

        foreach ($get_free_material as $free_material) {
            $hasil[] = '<option value="' . $free_material->id_category1 . '">' . $free_material->nama . '</option>';
        }

        echo json_encode(['hasil' => $hasil]);
    }

    public function add_material_tambahan()
    {
        $id_spk = $this->input->post('id_spk');
        $material_tambahan = $this->input->post('material_tambahan');
        $jumlah_material_tambahan = $this->input->post('jumlah_material_tambahan');
        $alasan_material_tambahan = $this->input->post('alasan_material_tambahan');

        $get_spk = $this->db->get_where('ms_create_spk', ['id_spk' => $id_spk])->row();
        $get_material1 = $this->db->get_where('ms_inventory_category1', ['id_category1' => $material_tambahan])->row();

        $code_mt_spk = $this->db->query("SELECT MAX(id) as max_id FROM ms_spk_material_tambahan WHERE id LIKE '%SPK-MT/" . date('Y') . "/" . date('m') . "/" . date('d') . "%'")->row();
        $kodeBarang = $code_mt_spk->max_id;
        $urutan = (int) substr($kodeBarang, 18, 6);
        $urutan++;
        $tahun = date('Y/m/d/');
        $huruf = "SPK-MT/";
        $kode_mt_spk = $huruf . $tahun . sprintf("%06s", $urutan);

        $this->db->trans_begin();

        $data_input = [
            'id' => $kode_mt_spk,
            'id_spk' => $id_spk,
            'id_so' => $get_spk->id_so,
            'id_proses' => '-',
            'id_category1' => $material_tambahan,
            'nama_material' => $get_material1->nama,
            'jumlah' => $jumlah_material_tambahan,
            'alasan' => $alasan_material_tambahan,
            'dibuat_oleh' => $this->auth->user_id(),
            'dibuat_tgl' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('ms_spk_material_tambahan', $data_input);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $hasil = array();
        } else {
            $this->db->trans_commit();

            $get_material_tambahan = $this->db->get_where('ms_spk_material_tambahan', ['id_spk' => $id_spk, 'id_proses' => '-'])->result();

            $hasil = array();
            $x = 1;
            foreach ($get_material_tambahan as $material_tambahan) {
                $hasil[] = '
                    <tr>
                        <td class="text-center">' . $x . '</td>
                        <td class="text-center">' . $material_tambahan->nama_material . '</td>
                        <td class="text-center">' . number_format($material_tambahan->jumlah) . '</td>
                        <td class="text-center"></td>
                        <td class="text-center">' . $material_tambahan->alasan . '</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger del_material_tambahan" data-id="' . $material_tambahan->id . '" data-id_spk="' . $id_spk . '">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                ';
                $x++;
            }
        }

        echo json_encode(['hasil' => $hasil]);
    }

    public function del_material_tambahan()
    {
        $id = $this->input->post('id');
        $id_spk = $this->input->post('id_spk');

        $this->db->trans_begin();

        $this->db->delete('ms_spk_material_tambahan', ['id' => $id]);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $hasil = array();
        } else {
            $this->db->trans_commit();

            $hasil = array();
            $get_material_tambahan = $this->db->get_where('ms_spk_material_tambahan', ['id_spk' => $id_spk, 'id_proses' => '-'])->result();

            $hasil = array();
            $x = 1;
            foreach ($get_material_tambahan as $material_tambahan) {
                $hasil[] = '
                    <tr>
                        <td class="text-center">' . $x . '</td>
                        <td class="text-center">' . $material_tambahan->nama_material . '</td>
                        <td class="text-center">' . number_format($material_tambahan->jumlah) . '</td>
                        <td class="text-center"></td>
                        <td class="text-center">' . $material_tambahan->alasan . '</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger del_material_tambahan" data-id="' . $material_tambahan->id . '" data-id_spk="' . $id_spk . '">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                ';
                $x++;
            }
        }

        echo json_encode(['hasil' => $hasil]);
    }

    public function save_data()
    {
        $id_spk = $this->input->post('id_spk');
        $id_so = $this->input->post('id_so');

        $aktual_qty = $this->input->post('aktual_qty');
        $sisa_produk = $this->input->post('sisa_produk');

        $berat_aktual1 = $this->input->post('berat_aktual1');
        $berat_aktual2 = $this->input->post('berat_aktual2');
        $berat_aktual3 = $this->input->post('berat_aktual3');
        $berat_aktual4 = $this->input->post('berat_aktual4');
        $berat_aktual5 = $this->input->post('berat_aktual5');

        $this->db->trans_begin();

        $this->db->update('ms_create_spk', [
            'product_aktual_qty' => $aktual_qty,
            'sisa_produk' => $sisa_produk,
            'berat_aktual_1' => $berat_aktual1,
            'berat_aktual_2' => $berat_aktual2,
            'berat_aktual_3' => $berat_aktual3,
            'berat_aktual_4' => $berat_aktual4,
            'berat_aktual_5' => $berat_aktual5
        ], [
            'id_spk' => $id_spk,
            'id_so' => $id_so,
            'id_proses' => '-'
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function closing_spk()
    {
        $id_spk = $this->input->post('id_spk');
        $id_so = $this->input->post('id_so');
        $id_proses = $this->input->post('id_proses');
        $aktual_qty = $this->input->post('aktual_qty');

        $this->db->select('a.*, e.id_category1, e.nama as nama_material, d.weight, IF(f.qty_aktual IS NOT NULL, f.qty_aktual, 0) as aktual_qty, g.qty_stock');
        $this->db->from('ms_create_spk a');
        $this->db->join('ms_so b', 'b.id_so = a.id_so', 'left');
        $this->db->join('ms_bom c', 'c.id = b.id_bom', 'left');
        $this->db->join('ms_bom_detail_material d', 'd.id_bom = b.id_bom', 'left');
        $this->db->join('ms_inventory_category1 e', 'e.id_category1 = d.id_category1', 'left');
        $this->db->join('ms_lhp_aktual_qty f', 'f.id_material = e.id_category1', 'left');
        $this->db->join('ms_stock_material_production g', 'g.id_category1 = e.id_category1', 'left');
        $this->db->where([
            'a.id_spk' => $id_spk,
            'a.id_so' => $id_so,
            'd.id_proses' => $id_proses
        ]);
        $this->db->group_by('d.id_category1');
        $get_data = $this->db->get()->result();

        $this->db->select('a.*, IF(c.qty_stock IS NOT NULL AND c.qty_stock > 0, c.qty_stock, 0) as stock_material');
        $this->db->from('ms_spk_material_tambahan a');
        $this->db->join('ms_inventory_category1 b', 'b.id_category1 = a.id_category1');
        $this->db->join('ms_stock_material_production c', 'c.id_category1 = a.id_category1', 'left');
        $this->db->where([
            'a.id_spk' => $id_spk,
            'a.id_so' => $id_so,
            'a.id_proses' => $id_proses
        ]);
        $this->db->group_by('a.id_category1');

        $get_data_material_tambahan = $this->db->get()->result();

        $this->db->trans_begin();

        $valid = 1;

        $get_so = $this->db->get_where('ms_so', ['id_so' => $id_so])->row();

        $get_product = $this->db->get_where('ms_product_category3', ['id_category3' => $get_so->id_product])->row();

        $check_stock_product = $this->db->get_where('ms_stock_product', ['id_product' => $get_so->id_product])->num_rows();
        if ($check_stock_product < 1) {
            $this->db->insert('ms_stock_product', [
                'id_product' => $get_so->id_product,
                'qty_asli' => ($aktual_qty * $get_product->konversi),
                'dibuat_oleh' => $this->auth->user_id(),
                'dibuat_tgl' => date('Y-m-d H:i:s')
            ]);
        } else {
            $get_stock = $this->db->get_where('ms_stock_product', ['id_product' => $get_so->id_product])->row();

            $this->db->update('ms_stock_product', [
                'qty_asli' => ($get_stock->qty_asli + ($aktual_qty * $get_product->konversi)),
                'diubah_oleh' => $this->auth->user_id(),
                'diubah_tgl' => date('Y-m-d H:i:s')
            ], [
                'id_product' => $get_so->id_product
            ]);
        }

        $msg = 'Selamat, LHP telah di closing !';

        $this->db->insert('ms_closing_lhp', [
            'id_spk' => $id_spk,
            'id_so' => $id_so,
            'id_proses' => '-',
            'sts' => 'closing',
            'dibuat_oleh' => $this->auth->user_id(),
            'dibuat_tgl' => date("Y-m-d H:i:s")
        ]);

        if ($this->db->trans_status() === FALSE || $valid !== 1) {
            $this->db->trans_rollback();
            $msg = 'Maaf, ada material yang kurang dari aktual stock !';

            $hasil = [
                'status' => $valid,
                'msg' => $msg
            ];
        } else {


            $this->db->trans_commit();

            $hasil = [
                'status' => $valid,
                'msg' => $msg
            ];
        }

        echo json_encode($hasil);
    }

    public function print_checksheet($id_spk)
    {
        $get_data_spk = $this->db->get_where('ms_create_spk', ['id_spk' => $id_spk, 'id_proses' => '-'])->row();
        $get_so = $this->db->get_where('ms_so', ['id_so' => $get_data_spk->id_so])->row();
        $get_bom = $this->db->get_where('ms_bom', ['id' => $get_so->id_bom])->row();
        $get_bom_detail = $this->db->query("
            SELECT 
                a.*,
                b.nama,
                IF(c.custom_weight IS NOT NULL, c.custom_weight, a.weight) AS berat 
            FROM 
                ms_bom_detail_material a 
                JOIN ms_inventory_category1 b ON b.id_category1 = a.id_category1
                LEFT JOIN ms_spk_weight_custom c ON c.id_material = a.id_category1 AND c.id_spk = '" . $id_spk . "' AND c.id_proses = '-' 
            WHERE 
                a.id_bom = '" . $get_so->id_bom . "' AND 
                a.id_proses = '-'")
            ->result();
        $get_product = $this->db->get_where('ms_product_category3', ['id_category3' => $get_data_spk->id_product])->row();

        $get_material_category1 = $this->db->query('SELECT a.* FROM ms_inventory_category1 a LEFT JOIN ms_spk_material_tambahan b ON b.id_category1 = a.id_category1 AND b.id_spk = "' . $id_spk . '" WHERE a.deleted = "0" GROUP BY a.id_category1')->result();

        $get_material_tambahan = $this->db->get_where('ms_spk_material_tambahan', ['id_spk' => $id_spk, 'id_proses' => '-'])->result();

        $get_product_so = $this->db->query("SELECT b.konversi, b.packaging, b.propose FROM ms_create_spk a JOIN ms_so b ON b.id_so = a.id_so WHERE a.id_spk = '" . $id_spk . "' AND a.id_so = '" . $get_data_spk->id_so . "'")->row();

        $closing_spk = 0;
        $check_closing_spk = $this->db->get_where('ms_closing_lhp', ['id_spk' => $id_spk, 'id_so' => $get_data_spk->id_so, 'id_proses' => '-'])->num_rows();
        if ($check_closing_spk > 0) {
            $closing_spk = 1;
        }

        $data = [
            'data_spk' => $get_data_spk,
            'data_product' => $get_product,
            'data_so' => $get_so,
            'data_bom' => $get_bom,
            'data_detail_bom' => $get_bom_detail,
            'list_material_category' => $get_material_category1,
            'data_material_tambahan' => $get_material_tambahan,
            'data_product_so' => $get_product_so,
            'closing_spk' => $closing_spk
        ];

        // echo '<pre>';  
        // print_r($data);
        // echo'</pre>';
        // exit;

        $this->auth->restrict($this->viewPermission);
        $this->template->set('results', $data);
        $this->template->title('Print Checksheet Filling');
        $this->template->render('print_checksheet');
    }

    public function print_checksheet_real($id_spk)
    {
        $get_data_spk = $this->db->get_where('ms_create_spk', ['id_spk' => $id_spk, 'id_proses' => '-'])->row();
        $get_so = $this->db->get_where('ms_so', ['id_so' => $get_data_spk->id_so])->row();
        $get_bom = $this->db->get_where('ms_bom', ['id' => $get_so->id_bom])->row();
        $get_bom_detail = $this->db->query("
            SELECT 
                a.*,
                b.nama,
                IF(c.custom_weight IS NOT NULL, c.custom_weight, a.weight) AS berat 
            FROM 
                ms_bom_detail_material a 
                JOIN ms_inventory_category1 b ON b.id_category1 = a.id_category1
                LEFT JOIN ms_spk_weight_custom c ON c.id_material = a.id_category1 AND c.id_spk = '" . $id_spk . "' AND c.id_proses = '-' 
            WHERE 
                a.id_bom = '" . $get_so->id_bom . "' AND 
                a.id_proses = '-'")
            ->result();
        $get_product = $this->db->get_where('ms_product_category3', ['id_category3' => $get_data_spk->id_product])->row();

        $get_material_category1 = $this->db->query('SELECT a.* FROM ms_inventory_category1 a LEFT JOIN ms_spk_material_tambahan b ON b.id_category1 = a.id_category1 AND b.id_spk = "' . $id_spk . '" WHERE a.deleted = "0" GROUP BY a.id_category1')->result();

        $get_material_tambahan = $this->db->get_where('ms_spk_material_tambahan', ['id_spk' => $id_spk, 'id_proses' => '-'])->result();

        $get_user = $this->db->query("SELECT b.full_name, b.username FROM ms_create_spk a LEFT JOIN users b ON b.id_user = a.ditarik_oleh WHERE a.id_spk = '" . $id_spk . "' AND a.id_so = '" . $get_data_spk->id_so . "'")->row();

        $no_cetak = $get_data_spk->batch_ke . '/' . date('d-m-Y') . '/' . $get_user->username;

        $data = [
            'data_spk' => $get_data_spk,
            'data_product' => $get_product,
            'data_so' => $get_so,
            'data_bom' => $get_bom,
            'data_detail_bom' => $get_bom_detail,
            'list_material_category' => $get_material_category1,
            'data_material_tambahan' => $get_material_tambahan,
            'data_user' => $get_user,
            'no_cetak' => $no_cetak
        ];

        // echo '<pre>';  
        // print_r($data);
        // echo'</pre>';
        // exit;

        $this->auth->restrict($this->viewPermission);
        $this->template->set('results', $data);
        $this->template->title('Print Checksheet Mixing');
        $this->template->render('print_checksheet_real');
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
                b.nama as nm_product,
                e.nm_packaging
            FROM
                ms_so a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
                JOIN ms_bom c ON c.id_product = a.id_product
                JOIN ms_bom_detail_material d ON d.id_bom = c.id
                LEFT JOIN master_packaging e ON e.id = b.packaging
            WHERE
                1=1 AND (SELECT COUNT(aa.id_spk) AS count_spk FROM ms_create_spk aa WHERE aa.id_so = a.id_so AND aa.id_proses = '-') > 0 AND (
                    a.id_so LIKE '%" . $string . "%' OR
                    b.nama LIKE '%" . $string . "%' OR
                    e.nm_packaging LIKE '%" . $string . "%' OR
                    b.konversi LIKE '%" . $string . "%' OR
                    a.due_date LIKE '%" . $string . "%' OR
                    a.released LIKE '%" . $string . "%' OR
                    a.sisa_so LIKE '%" . $string . "%'
                )
            GROUP BY a.id_so
            ORDER BY a.id_so DESC
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id',
            1 => 'id'
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

            $num_create_spk = $this->db->query('SELECT IF(batch_ke IS NOT NULL, MAX(batch_ke), 0) AS max_batch_ke FROM ms_create_spk WHERE id_so = "' . $row['id_so'] . '" AND id_proses = "1"')->row();

            // echo '   <pre>';
            // print_r($num_create_spk);
            // echo'</pre>';
            // exit;

            $get_create_spk = $this->db->query('SELECT IF(batch_ke IS NOT NULL, MAX(batch_ke), 0) AS max_batch_ke FROM ms_create_spk WHERE id_so = "' . $row['id_so'] . '" AND id_proses = "1"')->row();

            $get_bom = $this->db->get_where('ms_bom', ['id' => $row['id_bom']])->row();

            // $view         = '<button type="button" class="btn btn-primary btn-sm view" data-toggle="tooltip" title="View" data-id="' . $row['id'] . '"><i class="fa fa-eye"></i></button>';
            // $edit         = '<button type="button" class="btn btn-success btn-sm edit" data-toggle="tooltip" title="Edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>';
            $delete     = '<button type="button" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="Delete" data-id="' . $row['id_so'] . '"><i class="fa fa-trash"></i></button>';
            if ($num_create_spk->max_batch_ke > 0) {
                $delete     = '';
            }
            $create_spk_btn = '<button type="button" class="btn btn-primary btn-sm create_spk" data-id_so="' . $row['id_so'] . '" data-id_proses="1" data-batch_ke="' . ($get_create_spk->max_batch_ke + 1) . '">Create SPK</button>';
            if (($get_create_spk->max_batch_ke) == (($row['propose'] / $get_bom->qty_hopper))) {
                $create_spk_btn = '';
            }
            $btn_list_spk = '';
            if ($num_create_spk->max_batch_ke > 0) {
                $btn_list_spk = '<button type="button" class="btn btn-info btn-sm list_spk" data-id_so="' . str_replace("/", "-", $row['id_so']) . '" data-id_proses="1" data-batch_ke="' . ($get_create_spk->max_batch_ke + 1) . '">List SPK</button>';
            }
            $buttons     = $btn_list_spk;
            // $buttons = '';



            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['id_so'];
            $nestedData[]  = $row['nm_product'];
            $nestedData[]  = $row['nm_packaging'];
            $nestedData[]  = $row['propose'];
            $nestedData[]  = ($row['propose'] / $get_bom->qty_hopper);
            $nestedData[]  = ($get_create_spk->max_batch_ke);
            $nestedData[]  = (($row['propose'] / $get_bom->qty_hopper) - $get_create_spk->max_batch_ke);
            $nestedData[]  = date('d/m/Y', strtotime($row['due_date']));
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
        $this->template->title('Menu SPK');
        $this->template->render('index');
    }

    public function add()
    {
        $this->auth->restrict($this->viewPermission);
        // $data = array();
        $data = $this->db->query("
            SELECT
                a.*,
                b.moq,
                b.nama as nm_product
            FROM
                ms_so a
                LEFT JOIN ms_product_category3 b ON b.id_category3 = a.id_product
            WHERE
                sisa_so > 0
        ")->result();

        $array_spk_batch = array();

        foreach ($data as $list_data) {
            $get_product_batch = $this->db->get_where('ms_bom', ['id_product' => $list_data->id_product])->result();

            $array_spk_batch[$list_data->id_product] = $get_product_batch;
        }

        // echo '<pre>'; 
        // print_r($array_spk_batch);
        // echo'</pre>';
        // exit;

        $this->template->set([
            'results' => $data,
            'spk_batch' => $array_spk_batch
        ]);
        $this->template->render('form');
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
                'msg'        => "Failed delete data Lhp_filling. Please try again. " . $errMsg,
                'status'    => 0
            );
        } else {
            $this->db->trans_commit();
            $return    = array(
                'msg'        => 'Delete data Lhp_filling.',
                'status'    => '-'
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

    public function list_spk($id_so)
    {
        $this->auth->restrict($this->viewPermission);
        $data = array();

        $list_data = $this->db->select('a.id_spk, a.tgl_tarik, b.full_name, IF(c.id IS NOT NULL, 1, 0) AS badge_sts');
        $list_data = $this->db->from('ms_create_spk a');
        $list_data = $this->db->join('users b', 'b.id_user = a.ditarik_oleh', 'left');
        $list_data = $this->db->join('ms_closing_lhp c', 'c.id_spk = a.id_spk AND c.id_so = a.id_so AND c.id_proses = a.id_proses', 'left');
        $list_data = $this->db->where(['a.id_so' => str_replace('-', '/', $id_so), 'a.id_proses' => '-']);
        $list_data = $this->db->group_by('a.id_spk');
        $list_data = $this->db->get()->result();

        $get_data = [
            'list_spk' => $list_data
        ];

        $this->template->set($get_data);
        $this->template->render('list_spk');
    }
}
