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

        $sql .= " ORDER BY a.id_do DESC ";
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

            $view = '<button type="button" class="btn btn-sm btn-success view" data-toggle="tooltip" title="Update" data-id="' . $row['id_do'] . '"><i class="fa fa-list"></i></button>';
            $update = '<button type="button" class="btn btn-sm btn-warning text-light update_status" data-toggle="toolptip" title="Closing DO" data-id="' . $row['id_do'] . '"><i class="fa fa-check"></i></button>';

            $valid_bukti_kirim = 1;

            $this->db->select('COUNT(a.id_do) AS all_do_proof');
            $this->db->from('ms_detail_do a');
            $this->db->where('a.id_do', $row['id_do']);
            $check_all_deliver = $this->db->get()->row();

            $this->db->select('a.upload_bukti_kirim');
            $this->db->from('ms_detail_do a');
            $this->db->where('a.id_do', $row['id_do']);
            $this->db->where('a.upload_bukti_kirim !=', '');
            $check_bukti_kirim = $this->db->get()->result();

            // print_r($check_all_deliver->all_do_proof . ' ' . count($check_bukti_kirim));
            // exit;

            if ($check_all_deliver->all_do_proof == count($check_bukti_kirim)) {
                if (count($check_bukti_kirim) > 0) {
                    foreach ($check_bukti_kirim as $check) :

                        if ($valid_bukti_kirim == 1) {
                            if (!file_exists(str_replace('/uploads', 'uploads', $check->upload_bukti_kirim))) {
                                $valid_bukti_kirim = 0;
                            } else {
                                if ($check->upload_bukti_kirim == '') {
                                    $valid_bukti_kirim = 0;
                                }
                            }
                        }
                    endforeach;
                } else {
                    $valid_bukti_kirim = 0;
                }
            } else {
                $valid_bukti_kirim = 0;
            }

            if ($valid_bukti_kirim != 1) {
                $update = '';
            }

            if ($row['sts_close_do'] == 'close') {
                $sts = '<div class="badge badge-success">Sent</div>';
                $update = '';
            } else {
                $sts = '<div class="badge badge-warning text-light">On Process</div>';
            }

            $this->db->select('SUM(a.weight) AS qty_sended_do');
            $this->db->from('ms_detail_do a');
            $this->db->where('a.id_do', $row['id_do']);
            $check_sended_do = $this->db->get()->row();

            $this->db->select('SUM(a.weight) AS all_weight_order');
            $this->db->from('ms_penawaran_detail a');
            $this->db->where('a.id_penawaran', $row['id_penawaran']);
            $check_weight_order = $this->db->get()->row();

            if ($check_sended_do->qty_sended_do == $check_weight_order->all_weight_order) {
                $buttons     = $view . ' ' . $update;

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

        $link_bukti = '<a href="' . base_url($get_detail_do[0]->upload_bukti_kirim) . '" class="btn btn-sm btn-info" target="_blank">Download Evidence</a>';
        if ($get_detail_do[0]->upload_bukti_kirim == '') {
            $link_bukti = '';
        }
        if (!file_exists(str_replace('/uploads', 'uploads', $get_detail_do[0]->upload_bukti_kirim))) {
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
                            ';

        if ($get_penawaran->sts_close_do !== 'close') {
            $hasil = $hasil . '<input type="file" name="upload_bukti_kirim" id="" class="form-control form-control-sm">';
        }

        $hasil = $hasil . '
                            ' . $link_bukti . '
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
            'hasil' => $hasil,
            'close_do' => $get_penawaran->sts_close_do
        ]);
    }

    public function save()
    {
        $this->auth->restrict($this->addPermission);
        $session = $this->session->userdata('app_session');

        $post = $this->input->post();

        $config['upload_path'] = './uploads/bukti_kirim_do/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = 10000; // Maximum file size in kilobytes (2MB).
        $config['encrypt_name'] = TRUE; // Encrypt the uploaded file's name.
        $config['remove_spaces'] = TRUE; // Remove spaces from the file name.

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_bukti_kirim')) {
            $upload_file = 'Upload Error';
        } else {
            $upload_file = $this->upload->data();
            $upload_file = '/uploads/bukti_kirim_do/' . $upload_file['file_name'];
        }

        $this->db->trans_begin();

        $this->db->update('ms_detail_do', [
            'upload_bukti_kirim' => $upload_file
        ], [
            'id_print_do' => $post['id_print_do']
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $valid = 0;
            $msg = 'Sorry, Delivery Proof not uploaded !';
        } else {
            $this->db->trans_commit();
            $valid = 1;
            $msg = 'Success, Delivery Proof has been uploaded !';
        }

        echo json_encode([
            'status' => $valid,
            'msg' => $msg
        ]);
    }

    public function update_status($id_do)
    {
        $this->db->trans_begin();
        $this->db->update('ms_penawaran', ['sts_close_do' => 'close'], ['id_do' => $id_do]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $valid = 0;
            $msg = 'Sorry, DO Status has not been updated, please try again !';
        } else {
            $this->db->trans_commit();
            $valid = 1;
            $msg = 'Success, DO status has been updated to Closed !';
        }

        echo json_encode([
            'status' => $valid,
            'msg' => $msg
        ]);
    }
}
