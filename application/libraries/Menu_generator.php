<?php defined('BASEPATH') || exit('No direct script access allowed');

class Menu_generator
{
	protected $ci;
	protected $x; //Db Prefix
	protected $uri; //Current uri string
	protected $user_id;
	protected $is_admin;

	public function __construct()
	{
		$this->ci = &get_instance();

		$this->x = $this->ci->db->dbprefix;
		$this->uri = '/' . $this->ci->uri->uri_string() . '/';

		$this->ci->load->helper('app');
		//$this->ci->load->model('menus/users_model');
		$this->ci->load->library('users/auth');
		$this->user_id     = $this->ci->auth->user_id();
		$this->is_admin = $this->ci->auth->is_admin();
	}

	public function build_menus($type = 1)
	{
		$auth = $this->get_auth_permission($this->user_id);
		// echo '<pre>'; 
		// print_r($auth);
		// echo'</pre>';
		// exit;
		if (!$auth) {
			$auth = array(NULL);
		}

		if ($type == 1) {
			$menu = $this->ci->db->select("t1.*")
				->from("menus as t1")
				->join("menus as t2", "t1.id = t2.parent_id", "left")
				//->join("menus as t3","t2.id = t3.parent_id","left")
				->where("t1.parent_id", 0)
				->where("t1.group_menu", $type)
				->where("t1.status", 1)
				->group_by("t1.id")
				->order_by("t1.order", "ASC")
				->get()
				->result();

			$html = "<ul class='br-sideleft-menu'>
            			<li class='br-menu-item'>
							<a href='" . site_url() . "' class='br-menu-link " . check_class('dashboard', TRUE) . "'>
								<i class='menu-item-icon icon ion-ios-home-outline tx-24'></i> <span class='menu-item-label'>Dashboard</span>
							</a>
						</li>";

			// echo '<pre>'; 
			// print_r($auth);
			// echo'</pre>';
			// exit;
			// if (!$this->is_admin) {
			// 	print_r('0');
			// } else {
			// 	print_r('1');
			// }
			// exit;
			if (is_array($menu) && count($menu)) {
				foreach ($menu as $rw) {
					$id         = $rw->id;
					// echo $id."<br>";
					$title         = $rw->title;
					$link         = $rw->link;
					$icon         = $rw->icon;
					$target     = $rw->target;
					$submenu = $this->ci->db->select("t1.*")
						->from("menus as t1")
						->where("t1.parent_id", $id)
						->where("t1.group_menu", $type)
						->where("t1.status", 1);
					if (!$this->is_admin) {
						$submenu = $submenu->where_in("t1.permission_id", $auth);
					}
					$submenu = $submenu->group_by("t1.id")
						->order_by("t1.order", "ASC")
						->get()
						->result();
					//Jump to end_for point
					// echo '<pre>'; 
					// print_r($submenu);
					// echo '</pre>';
					// exit;
					if (count($submenu) == 0) {

						if ($link !== "#") {
							if (!in_array($rw->permission_id, $auth) && $this->is_admin === FALSE) {
								goto end_for;
							}
							$active = "";
							if (strpos($this->uri, '/' . $link . '/') !== FALSE) {
								$active = "active";
							}
							if ($link == "#") {
								$get_submenu1 = $this->ci->db->get_where('menus', ['parent_id' => $id])->result();
								foreach ($get_submenu1 as $submenu1) {
									if (strpos($this->uri, '/' . $submenu1->link . '/') !== FALSE) {
										$active = "active";
									}

									$get_submenu2 = $this->ci->db->get_where('menus', ['parent_id' => $submenu1->id])->result();
									foreach ($get_submenu2 as $submenu2) {
										if (strpos($this->uri, '/' . $submenu2->link . '/') !== FALSE) {
											$active = "active";
										}
									}
								}
							}

							$html .= "<li class='{$active}'><a href='" . ($link == '#' ? '#' : site_url($link)) . "' " . ($target == '_blank' ? "target='_blank'" : "") . "><i class='{$icon}'></i> <span>" . ucwords($title) . "</span></a></li>";
						}
						goto end_for;
					}

					$active = "";
					foreach ($submenu as $sub) {
						if (strpos($this->uri, '/' . $sub->link . '/') !== FALSE) {
							$active = "active";
							break;
						}
						if ($sub->link == "#") {
							$get_submenu1 = $this->ci->db->get_where('menus', ['parent_id' => $sub->id])->result();
							foreach ($get_submenu1 as $submenu1) {
								if (strpos($this->uri, '/' . $submenu1->link . '/') !== FALSE) {
									$active = "active";
								}

								$get_submenu2 = $this->ci->db->get_where('menus', ['parent_id' => $submenu1->id])->result();
								foreach ($get_submenu2 as $submenu2) {
									if (strpos($this->uri, '/' . $submenu2->link . '/') !== FALSE) {
										$active = "active";
									}
								}
							}
						}
					}

					$html .= "
            			  <li class='br-menu-item' >
                      <a href='#' class='br-menu-link with-sub {$active}'>
                        <i class='menu-item-icon icon ion-ios-photos-outline tx-20 " . $icon . "'></i>
                        <span class='menu-item-label'>" . ucwords($title) . "</span>
                      </a>
                      <ul class='br-menu-sub'>";

					// echo '<pre>'; 
					// print_r($submenu);
					// echo '</pre>';
					// exit;

					//Make Sub Menu
					// if($rw->id == 81){
					// 	echo '<pre>';
					// 	print_r($submenu);
					// 	echo '</pre>';
					// 	exit;
					// }
					foreach ($submenu as $sub) {
						$subid         = $sub->id;
						$subtitle     = $sub->title;
						$sublink     = $sub->link;
						$subicon     = $sub->icon;
						$subtarget     = $sub->target;
						$subtarget = "";
						if ($subtarget == '_blank') {
							$subtarget = "target='_blank'";
						}

						$submenusub = $this->ci->db->select("t1.*")
							->from("menus as t1")
							->where("t1.parent_id", $subid)
							->where("t1.group_menu", $type)
							->where("t1.status", 1);
						if (!$this->is_admin) {
							$submenusub = $submenusub->where_in("t1.permission_id", $auth);
						}
						$submenusub = $submenusub->group_by("t1.id")
							->order_by("t1.order", "ASC")
							->get()
							->result();
						// 	echo '<pre>'; 
						// print_r($submenusub);
						// echo '</pre>';
						// exit;
						//Jump to end_for point
						// echo '<pre>'; 
						// print_r(count($submenusub));
						// echo '</pre>';
						// exit;
						if (count($submenusub) == 0) {
							if ($sublink !== "#") {
								// if($rw->id == 81){
								// 	echo $subid."<br>";
								// }
								if (!in_array($sub->permission_id, $auth) && $this->is_admin === FALSE) {
									goto end_for_sub;
								}
								$active = "";
								if (strpos($this->uri, '/' . $sublink . '/') !== FALSE) {
									$active = "active";
								}
								$html .= "
								<li class='sub-item'>
									<a class='sub-link " . $active . "' href='" . ($sublink == '#' ? '#' : site_url($sublink)) . "'" . " " . $subtarget . ">
										" . ucwords($subtitle) . "
									</a>
								</li>";
							}
							goto end_for_sub;
						}
						$active = "";
						foreach ($submenusub as $subsub) {
							if (strpos($this->uri, '/' . $subsub->link . '/') !== FALSE) {
								$active = "active";
								break;
							}
						}

						$html .= "
	            			  <li class='br-menu-item'>
								<a href='#' class='br-menu-link with-sub {$active}'>
									<i class='menu-item-icon icon ion-ios-photos-outline tx-20 " . $subicon . "'></i>
									<span class='pmenu-item-label'>" . ucwords($subtitle) . "</span>
								</a>
								<ul class='br-menu-sub'>";
						//Make Sub Menu

						foreach ($submenusub as $subsub) {
							$subidsub         = $subsub->id;
							$subtitlesub     = $subsub->title;
							$sublinksub     = $subsub->link;
							$subiconsub     = $subsub->icon;
							$subtargetsub     = $subsub->target;
							$subtargetsub = "";
							if ($subtargetsub == '_blank') {
								$subtargetsub = "target='_blank'";
							}
							//Check current link
							if (strpos($this->uri, '/' . $sublinksub . '/') !== FALSE) {
								$active = "active";
							} else {
								$active = "";
							}
							$html .= "
							<li class='sub-item'>
								<a class='sub-link " . $active . "' href='" . ($sublinksub == '#' ? '#' : site_url($sublinksub)) . "'" . " " . $subtargetsub . ">
									" . ucwords($subtitlesub) . "
								</a>
							</li>";
						}

						$html .= "
							</ul>
						</li>";
						end_for_sub:
					}
					$html .= "
						</ul>
					</li>";

					//Jump Point
					end_for:
					//END FOREACH MENU
				}
				$html .= "
					</ul>";
				/*=================================================================================================================
===================================================================================================================
===================================================================================================================
*/
			}
		} else {
			//other menu
		}

		return $html;
	}

	public function build_menus_OLD($type = 1)
	{
		$auth = $this->get_auth_permission($this->user_id);
		if (!$auth) {
			$auth = array(NULL);
		}

		if ($type == 1) {
			$menu = $this->ci->db->select("t1.*")
				->from("menus as t1")
				->join("menus as t2", "t1.id = t2.parent_id", "left")
				//->join("menus as t3","t2.id = t3.parent_id","left")
				->where("t1.parent_id", 0)
				->where("t1.group_menu", $type)
				->where("t1.status", 1)
				->group_by("t1.id")
				->order_by("t1.order", "ASC")
				->get()
				->result();

			$html = "<ul class='sidebar-menu'>
							<li class='header'></li>
	                        <li class='" . check_class('dashboard', TRUE) . "'>
	                            <a href='" . site_url() . "'>
	                                <i class='fa fa-dashboard'></i> <span>Dashboard</span>
	                            </a>
	                        </li>";

			if (is_array($menu) && count($menu)) {
				foreach ($menu as $rw) {
					$id         = $rw->id;
					$title         = $rw->title;
					$link         = $rw->link;
					$icon         = $rw->icon;
					$target     = $rw->target;
					$submenu = $this->ci->db->select("t1.*")
						->from("menus as t1")
						->where("t1.parent_id", $id)
						->where("t1.group_menu", $type)
						->where("t1.status", 1);
					if (!$this->is_admin) {
						$submenu = $submenu->where_in("t1.permission_id", $auth);
					}
					$submenu = $submenu->group_by("t1.id")
						->group_by("t1.id")
						->order_by("t1.order", "ASC")
						->get()
						->result();
					//Jump to end_for point
					if (count($submenu) == 0) {
						if ($link !== "#") {
							if (!in_array($rw->permission_id, $auth) && $this->is_admin == FALSE) {
								goto end_for;
							}
							$active = "";
							if (strpos($this->uri, '/' . $link . '/') !== FALSE) {
								$active = "active";
							}
							$html .= "<li class='{$active}'><a href='" . ($link == '#' ? '#' : site_url($link)) . "' " . ($target == '_blank' ? "target='_blank'" : "") . "><i class='{$icon}'></i> <span>" . ucwords($title) . "</span></a></li>";
						}
						goto end_for;
					}
					$active = "";
					foreach ($submenu as $sub) {
						if (strpos($this->uri, '/' . $sub->link . '/') !== FALSE) {
							$active = "active";
							break;
						}
					}
					$html .= "
            			  <li class='treeview {$active}'>
                      <a href='#'>
                        <i class='" . $icon . "'></i>
                        <span>" . ucwords($title) . "</span>
                        <span class='pull-right-container'>
						            	<i class='fa fa-angle-left pull-right'></i>
						          	</span>
                      </a>
                      <ul class='treeview-menu'>";

					//Make Sub Menu
					foreach ($submenu as $sub) {
						$subid         = $sub->id;
						$subtitle     = $sub->title;
						$sublink     = $sub->link;
						$subicon     = $sub->icon;
						$subtarget     = $sub->target;
						$subtarget = "";
						if ($subtarget == '_blank') {
							$subtarget = "target='_blank'";
						}


						//Check current link
						if (strpos($this->uri, '/' . $sublink . '/') !== FALSE) {
							$active = "active";
						} else {
							$active = "";
						}
						$html .= "
						<li class='" . $active . "'>
							<a href='" . ($sublink == '#' ? '#' : site_url($sublink)) . "'" . " " . $subtarget . ">
								<i class='" . $subicon . "'></i>" . ucwords($subtitle) . "
							</a>
						</li>";
					}
					$html .= "
						</ul>
					</li>";

					//Jump Point
					end_for:
					//END FOREACH MENU
				}
				$html .= "
					</ul>";
				/*=================================================================================================================
===================================================================================================================
===================================================================================================================
*/
			}
		} else {
			//other menu
		}

		return $html;
	}

	protected function get_auth_permission($user_id = 0)
	{
		$role_permissions = $this->ci->users_model->select("permissions.id_permission")
			->join("user_groups", "users.id_user = user_groups.id_user")
			->join("group_permissions", "user_groups.id_group = group_permissions.id_group")
			->join("permissions", "group_permissions.id_permission = permissions.id_permission")
			->where("users.id_user", $user_id)
			->find_all();

		$user_permissions = $this->ci->users_model->select("permissions.id_permission")
			->join("user_permissions", "users.id_user = user_permissions.id_user")
			->join("permissions", "user_permissions.id_permission = permissions.id_permission")
			->where("users.id_user", $user_id)
			->find_all();

		$merge = array();
		if ($role_permissions) {
			foreach ($role_permissions as $key => $rp) {
				if (!in_array($rp->id_permission, $merge)) {
					$merge[] = $rp->id_permission;
				}
			}
		}

		if ($user_permissions) {
			foreach ($user_permissions as $key => $up) {
				if (!in_array($up->id_permission, $merge)) {
					$merge[] = $up->id_permission;
				}
			}
		}

		return $merge;
	}
}
