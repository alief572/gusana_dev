<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author CokesHome
 * @copyright Copyright (c) 2015, CokesHome
 *
 * This is controller for Users Management
 */

class Setting extends Admin_Controller
{

    /**
     * Load the models, library, etc
     *
     *
     */

    //Permissions
    protected $viewPermission   = "Setting.View";
    protected $addPermission    = "Setting.Add";
    protected $managePermission = "Setting.Manage";
    protected $deletePermission = "Setting.Delete";

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('users');
        $this->load->model(array(
            'users/Users_model',
            'users/Groups_model',
            'users/User_groups_model',
            'users/Permissions_model',
            'users/User_permissions_model',
            // 'Cabang/Cabang_model',
        ));

        $this->template->page_icon('fa fa-users');
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
        $where = " AND `status` = '$status'";

        $string = $this->db->escape_like_str($search);
        $sql = "SELECT *,(@row_number:=@row_number + 1) AS num
        FROM users, (SELECT @row_number:=0) as temp WHERE 1=1 $where  
        AND ( id_user LIKE '%$string%'
        OR full_name LIKE '%$string%'
        OR `address` LIKE '%$string%'
        OR city LIKE '%$string%'
        OR phone LIKE '%$string%'
        OR `status` LIKE '%$string%'  
            )";

        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'num',
            1 => 'id_user',
            2 => 'full_name',
            3 => 'address',
            4 => 'city',
            5 => 'phone',
            6 => 'status',
        );

        $sql .= " ORDER BY " . $columns_order_by[$column] . " " . $dir . " ";
        $sql .= " LIMIT " . $start . " ," . $length . " ";
        $query  = $this->db->query($sql);

        $data  = array();
        $urut1  = 1;
        $urut2  = 0;

        $status = [
            '1' => '<span class="bg-info tx-white pd-5 tx-11 tx-bold rounded-5">Active</span>',
            '0' => '<span class="bg-danger tx-white pd-5 tx-11 tx-bold rounded-5">Inactive</span>',
        ];

        /* Button */

        foreach ($query->result_array() as $row) {
            $buttons = '';
            $total_data     = $totalData;
            $start_dari     = $start;
            $asc_desc       = $dir;
            if ($asc_desc == 'asc') {
                $nomor = ($total_data - $start_dari) - $urut2;
            }
            if ($asc_desc == 'desc') {
                $nomor = $urut1 + $start_dari;
            }

            $edit           = "<a href='" . base_url('users/setting/edit/' . $row['id_user']) . "' class='btn btn-success view btn-sm' data-bs-toggle='tooltip' title='Edit Data'><i class='fa fa-edit' aria-hidden='true'></i></a>";
            $permission     = "<a href='" . base_url('users/setting/permission/' . $row['id_user']) . "' data-id='" . $row['id_user'] . "' class='btn btn-indigo btn-sm' data-bs-toggle='tooltip' title='Edit Permission'><i class='fa fa-user-shield' aria-hidden='true'></i></a>";
            if ($row['status'] == '1') {
                $buttons = '-';
                if ($row['id_user'] != 1) {
                    $buttons = $edit . "&nbsp;" . $permission;
                }
            } else if ($row['status'] == '0') {
                $buttons = $edit;
            } else {
                $buttons = $edit;
            }

            $nestedData   = array();
            $nestedData[]  = "<div class='text-center'>" . $nomor . "</div>";
            // $nestedData[]  = "<div><a href='javascript:void(0)' data-id='" . $row['id_user'] . "' class='btn-link view'>" . $row['id_user'] . "</a></div>";
            $nestedData[]  = "<div class='text-dark'>" . $row['username'] . "</div>";
            $nestedData[]  = "<div class='tx-bold text-dark'>" . $row['full_name'] . "</div>";
            $nestedData[]  = "<div class=''>" . $row['email'] . "</div>";
            $nestedData[]  = "<div class=''>" . $row['address'] . "</div>";
            $nestedData[]  = "<div class=''>" . ($row['city']) . "</div>";
            $nestedData[]  = "<div class=''>" . ($row['phone']) . "</div>";
            $nestedData[]  = "<div class='text'>" . $status[$row['status']] . "</div>";
            $nestedData[]  = "<div class='text-center'>" . $buttons . "</div>";
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

        if (isset($_POST['delete']) && has_permission($this->deletePermission)) {
            $checked = $this->input->post('checked');

            if (is_array($checked) && count($checked)) {
                $result = FALSE;
                $sukses = 0;
                foreach ($checked as $pid) {
                    $result      = $this->users_model->delete($pid);

                    if ($result) {
                        $keterangan = "SUKSES, hapus data user dengan ID : " . $pid;
                        $status     = 1;

                        $sukses++;
                    } else {
                        $keterangan = "GAGAL, hapus data user dengan ID : " . $pid;
                        $status     = 0;
                    }

                    $nm_hak_akses   = $this->deletePermission;
                    $kode_universal = $pid;
                    $jumlah         = 1;
                    $sql            = $this->db->last_query();

                    simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
                }

                if ($result) {
                    $this->template->set_message(count($sukses) . ' ' . lang('users_del_success') . '.', 'success');
                } else {
                    $this->template->set_message(lang('users_del_fail') . $this->users_model->error, 'danger');
                }
            } else {
                $this->template->set_message(lang('danger'), 'danger');
            }

            unset($_POST['delete']);
        }

        // // Pagination
        // $this->load->library('pagination');

        // if (isset($_POST['table_search'])) {
        //     $search = isset($_POST['table_search']) ? $this->input->post('table_search') : '';
        // } else {
        //     $search = isset($_GET['search']) ? $this->input->get('search') : '';
        // }

        // $filter = "";
        // if ($search != "") {
        //     $filter = "?search=" . $search;
        // }

        // $search2 = $this->db->escape_str($search);

        // $where = "users.deleted = 0
        //             AND (`username` LIKE '%$search2%'
        //             OR `full_name` LIKE '%$search2%'
        //             OR `users`.`address` LIKE '%$search2%'
        //             OR `users`.`city` LIKE '%$search2%'
        //             OR `users`.`phone` LIKE '%$search2%'
        //            )";

        // $total = $this->users_model
        //     ->where($where)
        //     ->count_all();

        // $offset = $this->input->get('per_page');

        // $limit = $this->config->item('list_limit');

        // $this->pager['base_url']            = current_url() . $filter;
        // $this->pager['total_rows']          = $total;
        // $this->pager['per_page']            = $limit;
        // $this->pager['page_query_string']   = TRUE;

        // $this->pagination->initialize($this->pager);

        // $data = $this->users_model->select("users.*")
        //     ->where($where)
        //     ->order_by('full_name', 'ASC')
        //     ->limit($limit, $offset)->find_all();

        // $this->template->set('results', $data);
        // $this->template->set('search', $search);

        // $this->template->title(lang('users_manage_title'));
        // $this->template->set("numb", $offset + 1);
        $this->template->render('list');
    }

    public function create()
    {
        $this->auth->restrict($this->addPermission);

        if (isset($_POST['save'])) {
            if ($this->save_user()) {
                $this->template->set_message(lang('users_create_success'), 'success');
                redirect('users/setting');
            }
        }

        // $cabang = $this->Cabang_model->find_all();
        // $this->template->set('cabang', $cabang);
        $this->template->title(lang('users_new_title'));
        $this->template->page_icon('fa fa-user');
        $this->template->render('users_form');
    }

    public function edit($id = 0)
    {
        $this->auth->restrict($this->managePermission);

        if ($id == 0 || is_numeric($id) == FALSE) {
            $this->template->set_message(lang('users_invalid_id'), 'danger');
            redirect('users/setting');
        }

        if (isset($_POST['save'])) {
            if ($this->save_user("update", $id)) {
                $this->template->set_message(lang('users_edit_success'), 'success');
                redirect('users/setting');
            }
        }

        $data = $this->Users_model->find($id);

        if ($data) {
            if ($data->deleted == 1) {
                $this->template->set_message(lang('users_already_deleted'), 'danger');
                redirect('users/setting');
            }
        }

        //$cabang = $this->Cabang_model->find_all();
        //$this->template->set('cabang', $cabang);
        $this->template->set('data', $data);
        $this->template->title(lang('users_edit_title'));
        $this->template->page_icon('fa fa-user');
        $this->template->render('users_form');
    }

    public function permission($id = 0)
    {
        $this->auth->restrict($this->managePermission);

        if ($id == 0 || is_numeric($id) == FALSE || $id == 1) {
            $this->template->set_message(lang('users_invalid_id'), 'danger');
            redirect('users/setting');
        }

        if (isset($_POST['save'])) {
            if ($this->save_permission($id)) {
                $this->template->set_message(lang('users_permission_edit_success'), 'success');
            }
        }

        //User data
        $data = $this->users_model->find($id);

        if ($data) {
            if ($data->deleted == 1) {
                $this->template->set_message(lang('users_already_deleted'), 'danger');
                redirect('users/setting');
            }
        }
        //All Permission
        $permissions = $this->Permissions_model
            ->order_by("nm_permission", "ASC")
            ->find_all();

        $auth_permissions = $this->get_auth_permission($id);

        $rows   = array();
        $header = array();
        $tmp    = array();
        if ($permissions) {
            //table Header
            foreach ($permissions as $key => $pr) {
                $x = explode(".", $pr->nm_permission);
                if (!in_array($x[1], $header)) {
                    $header[] = $x[1];
                    $tmp[$x[1]] = 0;
                }
            }
            //Temporary value
            foreach ($permissions as $key2 => $pr) {
                $x = explode(".", $pr->nm_permission);
                $rows[$x[0]] = $tmp;
            }
            //Actual value
            foreach ($permissions as $key3 => $pr) {
                $x = explode(".", $pr->nm_permission);
                //Rows
                $rows[$x[0]][$x[1]] = array('nm' => $pr->nm_menu, 'perm_id' => $pr->id_permission, 'action_name' => $x[1], 'is_role_permission' => (isset($auth_permissions[$pr->id_permission]->is_role_permission) && $auth_permissions[$pr->id_permission]->is_role_permission == 1) ? 1 : '', 'value' => (isset($auth_permissions[$pr->id_permission]) ? 1 : 0));
            }
        }

        $this->template->set('data', $data);
        $this->template->set('header', $header);
        $this->template->set('permissions', $rows);

        $this->template->title(lang('users_edit_perm_title'));
        $this->template->page_icon('fa fa-shield');
        $this->template->render('user_permissions');
    }

    protected function save_permission($id_user = 0)
    {
        if ($id_user == 0 || $id_user == "") {
            $this->template->set_message(lang('users_invalid_id'), 'danger');
            return FALSE;
        }

        $id_permissions = $this->input->post('id_permissions');

        $insert_data = array();
        if ($id_permissions) {
            foreach ($id_permissions as $key => $idp) {
                $insert_data[] = array(
                    'id_user' => $id_user,
                    'id_permission' => $idp
                );
            }
        }

        //Delete Fisrt All Previous user permission
        $result = $this->User_permissions_model->delete_where(array('id_user' => $id_user));

        //Insert New one
        if ($insert_data) {
            $result = $this->User_permissions_model->insert_batch($insert_data);
        }

        if ($result === FALSE) {
            $this->template->set_message(lang('users_permission_edit_fail'), 'danger');
            return FALSE;
        }

        unset($_POST['save']);

        return $result;
    }

    protected function get_auth_permission($id = 0)
    {
        $role_permissions = $this->users_model->select("permissions.*")
            ->join("user_groups", "users.id_user = user_groups.id_user")
            ->join("group_permissions", "user_groups.id_group = group_permissions.id_group")
            ->join("permissions", "group_permissions.id_permission = permissions.id_permission")
            ->where("users.id_user", $id)
            ->find_all();

        $user_permissions = $this->users_model->select("permissions.*")
            ->join("user_permissions", "users.id_user = user_permissions.id_user")
            ->join("permissions", "user_permissions.id_permission = permissions.id_permission")
            ->where("users.id_user", $id)
            ->find_all();

        $merge = array();
        if ($role_permissions) {
            foreach ($role_permissions as $key => $rp) {
                if (!isset($merge[$rp->id_permission])) {
                    $rp->is_role_permission = 1;
                    $merge[$rp->id_permission] = $rp;
                }
            }
        }

        if ($user_permissions) {
            foreach ($user_permissions as $key => $up) {
                if (!isset($merge[$up->id_permission])) {
                    $up->is_role_permission = 0;
                    $merge[$up->id_permission] = $up;
                }
            }
        }

        return $merge;
    }

    protected function save_user($type = 'insert', $id = 0)
    {
        if ($type == "insert") {
            $extra_rule = "|unique[users.username]";
            $rule_email = "|unique[users.email]";
        } else {
            $_POST['id_user'] = $id;
            $extra_rule = "|unique[users.username, users.id_user]";
            $rule_email = "|unique[users.email, users.id_user]";
        }

        $this->form_validation->set_rules('username', 'Username', 'required' . $extra_rule);
        if ($type == "insert") {
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('re-password', 'Password', 'required|matches[password]');
        } else {
            if (isset($_POST['password']) && $_POST['password']) {
                $extra_rule = "|unique[users.username]";
                $this->form_validation->set_rules('password', 'Password', 'required');
                $this->form_validation->set_rules('re-password', 'Confirm Password', 'required|matches[password]');
            }
        }

        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email' . $rule_email);
        $this->form_validation->set_rules('full_name', 'Full Name User', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run($this) === FALSE) {
            $this->template->set_message(validation_errors(), 'danger');
            return FALSE;
        }

        $username   = $this->input->post('username');
        $password   = $this->input->post('password');
        $email      = $this->input->post('email');
        $full_name  = $this->input->post('full_name');
        $address    = $this->input->post('address');
        $city       = $this->input->post('city');
        $phone      = $this->input->post('phone');
        $status     = $this->input->post('status');
        // $kdcab      = $this->input->post('kdcab');

        /**
         * This code will benchmark your server to determine how high of a cost you can
         * afford. You want to set the highest cost that you can without slowing down
         * you server too much. 8-10 is a good baseline, and more is good if your servers
         * are fast enough. The code below aims for ≤ 50 milliseconds stretching time,
         * which is a good baseline for systems handling interactive logins.
         */
        $timeTarget = 0.05; // 50 milliseconds

        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        //End finding cost

        $options = [
            'cost' => $cost,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
        ];

        $password = password_hash($password, PASSWORD_BCRYPT, $options);

        if ($type == 'insert') {
            $data_insert = array(
                'username'      => $username,
                'email'         => $email,
                'full_name'     => $full_name,
                'address'       => $address,
                'city'          => $city,
                'phone'         => $phone,
                'ip'            => $this->input->ip_address(),
                'status'        => $status,
                // 'kdcab'     => $kdcab
            );
            if (isset($_POST['password']) && $_POST['password']) {
                $data_insert['password'] = $password;
            }

            $result = $this->Users_model->insert($data_insert);

            if ($result) {
                //Get Default user group
                $dt_group = $this->Groups_model->find_by(array('st_default' => 1));
                if ($dt_group) {
                    $id_group = $dt_group->id_group;

                    $insert_group = array(
                        'id_user' => $result,
                        'id_group' => $id_group
                    );

                    $this->user_groups_model->insert($insert_group);
                }

                return TRUE;
            } else {
                $this->template->set_message(lang('users_create_fail') . $this->users_model->error, 'danger');
                return FALSE;
            }
        } else {
            $data_insert = array(
                'username'  => $username,
                'email'     => $email,
                'full_name' => $full_name,
                'address'   => $address,
                'city'      => $city,
                'phone'     => $phone,
                'ip'        => $this->input->ip_address(),
                'status'    => $status,
                // 'kdcab'     => $kdcab
            );

            if (isset($_POST['password']) && $_POST['password']) {
                $data_insert['password'] = $password;
            }

            $result = $this->users_model->update($id, $data_insert);

            if ($result) {
                return TRUE;
            } else {
                $this->template->set_message(lang('users_edit_fail') . $this->users_model->error, 'danger');
                return FALSE;
            }
        }
    }

    public function default_select($val)
    {
        return $val == "" ? FALSE : TRUE;
    }
}
