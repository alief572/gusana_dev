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

class Monitoring_do extends Admin_Controller
{
    protected $viewPermission     = 'Monitoring_DO.View';
    protected $addPermission      = 'Monitoring_DO.Add';
    protected $managePermission = 'Monitoring_DO.Manage';
    protected $deletePermission = 'Monitoring_DO.Delete';
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('upload', 'Image_lib', 'user_agent', 'uri'));
        $this->load->model(array(
            'Monitoring_do/Monitoring_do_model',
            'Aktifitas/aktifitas_model',
        ));
        $this->load->helper(['url', 'json']);
        $this->template->title('Monitoring_do');
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
            SELECT a.*
            FROM
                ms_penawaran a
            WHERE
                1=1 AND a.sts_do = 'do_created' AND (
                    a.nm_cust LIKE '%" . $string . "%' OR
                    a.id_do LIKE '%" . $string . "%' OR
                    a.id_quote LIKE '%" . $string . "%'
                )
        ";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'id_penawaran',
            1 => 'nm_cust',
            2 => 'id_do',
            3 => 'id_quote'
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

            $view = '<button type="button" class="btn btn-sm btn-info view" data-toggle="tooltip" title="View" data-id="' . $row['id_do'] . '"><i class="fa fa-list"></i></button>';
            $update = '<button type="button" class="btn btn-sm btn-warning text-light update_status" data-toggle="toolptip" title="Update Status" data-id="' . $row['id_do'] . '"><i class="fa fa-plus"></i></button>';

            $valid_bukti_kirim = 1;

            $this->db->select('a.upload_bukti_kirim');
            $this->db->from('ms_detail_do a');
            $this->db->where('a.id_do', $row['id_do']);
            $this->db->where('a.upload_bukti_kirim !=', '');
            $check_bukti_kirim = $this->db->get()->result();

            foreach ($check_bukti_kirim as $check) :
                if ($valid_bukti_kirim == 1) {
                    if (!file_exists(base_url($check['upload_bukti_kirim'])) && $check['upload_bukti_kirim'] !== '') {
                        $valid_bukti_kirim = 0;
                    } else {
                        if ($check['upload_bukti_kirim'] == '') {
                            $valid_bukti_kirim = 0;
                        }
                    }
                }
            endforeach;

            if ($valid_bukti_kirim !== '1') {
                $update = '';
            }

            $buttons     = $view . ' ' . $update;

            if ($row['sts_monitor_do'] == 'close') {
                $sts = '<div class="badge badge-danger">Close</div>';
            } else {
                $sts = '<div class="badge badge-warning text-light">On Process</div>';
            }

            $nestedData   = array();
            $nestedData[]  = $nomor;
            $nestedData[]  = $row['nm_cust'];
            $nestedData[]  = $row['id_do'];
            $nestedData[]  = $row['id_quote'];
            $nestedData[]  = $sts;
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
        $this->template->title('Monitoring DO');
        $this->template->render('index');
    }

    public function view($id)
    {
        $this->auth->restrict($this->managePermission);

        $this->db->select('a.id_print_do, a.id_do, a.tgl_kirim');
        $this->db->from('ms_detail_do a');
        $this->db->where('a.id_do', $id);
        $this->db->group_by('a.id_print_do');
        $get_list_deliver = $this->db->get()->result();

        $this->template->set([
            'list_delivery' => $get_list_deliver
        ]);
        $this->template->render('view');
    }

    public function show_delivery_detail()
    {
        $post = $this->input->post();

        $get_penawaran = $this->db->get_where('ms_penawaran', ['id_do' => $post['id_do']])->row();

        $get_detail_do = $this->db->get_where('ms_detail_do', ['id_print_do' => $post['id_print_do']])->result();

        $link_bukti = '<a href="' . base_url($get_detail_do[0]->upload_bukti_kirim) . '" class="btn btn-sm btn-info" target="_blank">Download Delivery Proof</a>';
        if ($get_detail_do[0]->upload_bukti_kirim == '' || !file_exists($get_detail_do[0]->upload_bukti_kirim)) {
            $link_bukti = '';
        }

        $hasil = '
            <hr>
            <div class="col-12">
                <input type="hidden" name="id_do" value="' . $post['id_do'] . '">
                <input type="hidden" name="id_print_do" value="' . $post['id_print_do'] . '">
                <h3>Delivery Order</h3>
                <table class="w-100">
                    <tr>
                        <th>No DO</th>
                        <th>:</th>
                        <td>' . $post['id_do'] . '</td>
                    </tr>
                    <tr>
                        <th>No Print DO</th>
                        <th>:</th>
                        <td>' . $post['id_print_do'] . '</td>
                    </tr>
                    <tr>
                        <th>DO Date</th>
                        <th>:</th>
                        <td>' . date('d F Y', strtotime($get_detail_do[0]->tgl_kirim)) . '</td>
                    </tr>
                    <tr>
                        <th>Upload Delivery Proof</th>
                        <th>:</th>
                        <td>
                            <input type="file" name="upload_bukti_kirim" id="" class="form-control form-control-sm"> ' . $link_bukti . '
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-12" style="margin-top:15px;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center" style="width:150px;">Product Name</th>
                            <th class="text-center">Qty Order</th>
                            <th class="text-center">Packing</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Qty Sent</th>
                        </tr>
                    </thead>
                    <tbody>
                    ';

        $no = 1;
        foreach ($get_detail_do as $detail_do) :
            $penawaran_detail = $this->db->get_where('ms_penawaran_detail', ['id_penawaran' => $get_penawaran->id_penawaran, 'id_product' => $detail_do->id_product])->row();

            $hasil = $hasil . '
                <tr>
                    <td class="text-center">' . $no . '</td>
                    <td class="text-center">' . $detail_do->nm_product . '</td>
                    <td class="text-center">' . number_format($penawaran_detail->qty, 2) . '</td>
                    <td class="text-center">' . number_format($penawaran_detail->konversi, 2) . '</td>
                    <td class="text-center">' . number_format($penawaran_detail->weight, 2) . '</td>
                    <td class="text-center">' . number_format($detail_do->qty, 2) . '</td>
                </tr>
            ';

            $no++;
        endforeach;

        $hasil = $hasil . '
                    </tbody>
                </table>
            </div>
        ';

        echo json_encode([
            'hasil' => $hasil
        ]);
    }
}
