<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Product_price extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Product_Price.View';
	protected $addPermission  	= 'Product_Price.Add';
	protected $managePermission = 'Product_Price.Manage';
	protected $deletePermission = 'Product_Price.Delete';

	public function __construct()
	{
		parent::__construct();

		// $this->load->library(array('Mpdf'));
		$this->load->model(array(
			'Product_price/Product_price_model'
		));
		$this->load->helper(['json']);
		$this->template->title('Bill Of Material');
		$this->template->page_icon('fa fa-building-o');

		date_default_timezone_set('Asia/Bangkok');
	}

	//========================================================BOM

	public function index()
	{
		$this->auth->restrict($this->viewPermission);
		$session = $this->session->userdata('app_session');
		$this->template->page_icon('fa fa-users');
		$product_price 	= $this->db->select('MAX(update_date) AS updated_date')->get('product_price')->result();
		$last_update 	= "Last Update: " . date('d-M-Y H:i:s', strtotime($product_price[0]->updated_date));
		$data = [
			'product_lv1' => array(),
			'last_update' => $last_update
		];

		history("View index product price");
		$this->template->title('Product Price');
		$this->template->render('index', $data);
	}

	public function data_side_product_price()
	{
		$this->Product_price_model->get_json_product_price();
	}

	public function detail_costing()
	{
		// $this->auth->restrict($this->viewPermission);
		$no_bom 	= $this->uri->segment(3);
		// print_r($no_bom);
		// exit;
		$product_price 		= $this->db->get_where('product_price', array('no_bom' => $no_bom, 'deleted_date' => NULL))->result_array();
		// echo '<pre>'; 
		// print_r($product_price);
		// echo'</pre>';
		$costing_rate = $this->db->get_where('costing_rate', array('deleted_date' => NULL))->result_array();

		//Material
		$header 			= $this->db->get_where('ms_bom', array('id' => $no_bom))->result();
		$detail   			= $this->db->get_where('ms_bom_detail_material', ['id_bom' => $no_bom])->result_array();
		$product    		= $this->db->get('ms_product_category3')->result();

		$data = [
			'no_bom' => $no_bom,
			'dataList' => $costing_rate,
			'product_price' => $product_price,
			'header' => $header,
			'detail' => $detail,
			'product' => $product,
			'GET_LEVEL4' => get_inventory_lv4(),
			'GET_ACC' => get_accessories(),
			'GET_PRICE_REF' => get_price_ref()
		];
		$this->template->title('Costing Rate');
		$this->template->render('detail_costing', $data);
	}

	public function detail_material()
	{
		// $this->auth->restrict($this->viewPermission);
		$no_bom 			= $this->input->post('no_bom');

		$header 			= $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();
		$detail   			= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'default'))->result_array();
		$detail_additive   	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'additive'))->result_array();
		$detail_topping   	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'topping'))->result_array();
		$detail_accessories = $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'accessories'))->result_array();
		$detail_flat_sheet 	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'flat sheet'))->result_array();
		$detail_end_plate 	= $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'end plate'))->result_array();
		$detail_ukuran_jadi = $this->db->get_where('bom_detail', array('no_bom' => $no_bom, 'category' => 'ukuran jadi'))->result_array();
		$product    		= $this->product_price_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'));

		$data = [
			'header' => $header,
			'detail' => $detail,
			'detail_additive' => $detail_additive,
			'detail_topping' => $detail_topping,
			'detail_accessories' => $detail_accessories,
			'detail_flat_sheet' => $detail_flat_sheet,
			'detail_end_plate' => $detail_end_plate,
			'detail_ukuran_jadi' => $detail_ukuran_jadi,
			'product' => $product,
			'GET_LEVEL4' => get_inventory_lv4(),
			'GET_ACC' => get_accessories(),
			'GET_PRICE_REF' => get_price_ref()
		];
		$this->template->render('detail_bom_material', $data);
	}

	public function update_product_price()
	{
		$session = $this->session->userdata('app_session');
		$dateTime 	= date('Y-m-d H:i:s');
		$id_user	= $session['id_user'];

		$SQL 	= "SELECT a.* FROM ms_bom a WHERE a.id_product LIKE 'P%'";
		$result = $this->db->query($SQL)->result_array();

		$dateTime 	= date('Y-m-d H:i:s');
		$date 		= date('YmdHis');

		$GET_RATE_COSTING = get_rate_costing_rate();
		$GET_RATE_MAN_POWER = $this->db->order_by('id', 'desc')->get('rate_man_power')->result();
		$GET_LEVEL4 = get_inventory_lv4();
		$GET_LEVEL4_PRODUCT = get_product_lv4();
		$GET_PRICE_REF = get_price_ref();
		$GET_MACHINE_PRODUCT = get_machine_product();
		$GET_MOLD_PRODUCT = get_mold_product();
		$GET_MACHINE_RATE = get_rate_machine();
		$GET_MOLD_RATE = get_rate_mold();
		$GET_CYCLETIME = get_total_time_cycletime();

		$ArrHeader = [];
		$ArrDetailDefault = [];
		$ArrDetailAdditive = [];
		$ArrDetailAdditiveCustom = [];
		$ArrDetailTopping = [];
		$ArrDetailToppingCustom = [];
		// print_r($result);
		// exit;
		foreach ($result as $key => $value) {
			$no_bom = $value['id'];
			$kode 	= $date . '-' . $no_bom;

			$detail	= $this->db->get_where('ms_bom_detail_material')->result_array();

			$BERAT_MINUS = 0;
			if (!empty($detail_additive)) {
				foreach ($detail_additive as $val => $valx) {
					$detail_custom    = $this->db->get_where('bom_detail_custom', array('no_bom_detail' => $valx['no_bom_detail'], 'category' => 'additive'))->result();
					$PENGURANGAN_BERAT = 0;
					foreach ($detail_custom as $valx2) {
						$PENGURANGAN_BERAT += $valx2->weight * $valx2->persen / 100;
					}
					$BERAT_MINUS += $PENGURANGAN_BERAT;
				}
			}

			$TOTAL_PRICE_ALL = 0;
			$TOTAL_BERAT_BERSIH = 0;
			//default
			if (!empty($detail)) {
				// print_r($detail);
				// exit;
				foreach ($detail as $val => $valx) {
					$val++;

					$code_lv2		= (!empty($GET_LEVEL4[$valx['id_category1']]['id_category1'])) ? $GET_LEVEL4[$valx['id_category1']]['id_category1'] : '-';
					$price_ref      = (!empty($GET_PRICE_REF[$valx['id_category1']]['price_ref'])) ? $GET_PRICE_REF[$valx['id_category1']]['price_ref'] : 0;
					// $get_material_price_ref = $this->Product_price_model->get_material_price_ref();
					$nm_category = strtolower(get_name('ms_inventory_category1', 'nama', 'id_category1', $code_lv2));
					$berat_pengurang_additive = ($nm_category == 'resin') ? $BERAT_MINUS : 0;

					// print_r($valx);
					// exit;

					$berat_bersih = $valx['weight'] - $berat_pengurang_additive;
					$total_price = $berat_bersih * $price_ref;
					$TOTAL_PRICE_ALL += $total_price;
					$TOTAL_BERAT_BERSIH += $berat_bersih;
					$UNIQ = $val . '-' . $key;
					$ArrDetailDefault[$UNIQ]['kode'] 			=  $kode;
					$ArrDetailDefault[$UNIQ]['category'] 		=  $valx['id_category1'];
					$ArrDetailDefault[$UNIQ]['no_bom'] 			=  $valx['id'];
					// $ArrDetailDefault[$UNIQ]['no_bom_detail'] 	=  $valx['no_bom_detail'];
					$ArrDetailDefault[$UNIQ]['code_material'] 	=  $valx['id_category1'];
					// $ArrDetailDefault[$UNIQ]['weight'] 			=  $valx['weight'];
					// $ArrDetailDefault[$UNIQ]['persen'] 			=  $valx['persen'];
					// $ArrDetailDefault[$UNIQ]['persen_add'] 		=  $valx['persen_add'];
					// $ArrDetailDefault[$UNIQ]['length'] 			=  $valx['length'];
					// $ArrDetailDefault[$UNIQ]['width'] 			=  $valx['width'];
					// $ArrDetailDefault[$UNIQ]['qty'] 				=  $valx['qty'];
					// $ArrDetailDefault[$UNIQ]['m2'] 				=  $valx['m2'];
					// $ArrDetailDefault[$UNIQ]['file_upload'] 		=  $valx['file_upload'];
					$ArrDetailDefault[$UNIQ]['berat_bersih'] 		= $berat_bersih;
					$ArrDetailDefault[$UNIQ]['price_ref'] 			= $price_ref;
					$ArrDetailDefault[$UNIQ]['total_price'] 			= $total_price;
				}
			}

			// echo '<pre>'.print_r($value).'</pre>';
			// exit;

			$code_level4 = $value['id_product'];
			$ArrHeader[$key]['kode'] 				= $kode;
			$ArrHeader[$key]['no_bom'] 				= $no_bom;
			$ArrHeader[$key]['code_lv1'] 			= $GET_LEVEL4_PRODUCT[$code_level4]['id_type'];
			$ArrHeader[$key]['product_type'] 		= NULL;
			$ArrHeader[$key]['code_lv2'] 			= $GET_LEVEL4_PRODUCT[$code_level4]['id_category1'];
			$ArrHeader[$key]['product_category'] 	= NULL;
			$ArrHeader[$key]['code_lv3'] 			= $GET_LEVEL4_PRODUCT[$code_level4]['id_category2'];
			$ArrHeader[$key]['product_jenis'] 		= NULL;
			$ArrHeader[$key]['code_lv4'] 			= $GET_LEVEL4_PRODUCT[$code_level4]['id_category3'];
			$ArrHeader[$key]['product_master'] 		= $GET_LEVEL4_PRODUCT[$code_level4]['nama'];
			$ArrHeader[$key]['berat_material'] 		= $TOTAL_BERAT_BERSIH;

			$ArrHeader[$key]['update_by'] 			= $id_user;
			$ArrHeader[$key]['update_date'] 		= $dateTime;
			$ArrHeader[$key]['deleted_by'] 			= NULL;
			$ArrHeader[$key]['deleted_date'] 		= NULL;


			$cycletimeMaster 	= (!empty($GET_CYCLETIME[$code_level4]['ct_manpower'])) ? $GET_CYCLETIME[$code_level4]['ct_manpower'] : 0;
			$cycletimeMesin 	= (!empty($GET_CYCLETIME[$code_level4]['ct_machine'])) ? $GET_CYCLETIME[$code_level4]['ct_machine'] : 0;
			$rate_cycletime 	= 0;
			$rate_cycletime_mch 	= 0;
			if ($cycletimeMaster > 0) {
				$rate_cycletime 		= $cycletimeMaster / 60;
				$rate_cycletime_mch 	= $cycletimeMesin / 60;
			}
			$rate_manpower 		= $GET_RATE_MAN_POWER[0]->upah_per_jam;

			$kode_mesin = (!empty($GET_MACHINE_PRODUCT[$code_level4])) ? $GET_MACHINE_PRODUCT[$code_level4] : 0;
			$kode_mold = (!empty($GET_MOLD_PRODUCT[$code_level4])) ? $GET_MOLD_PRODUCT[$code_level4] : 0;

			$rate_depresiasi 	= (!empty($GET_MACHINE_RATE[$kode_mesin]['biaya_mesin'])) ? $GET_MACHINE_RATE[$kode_mesin]['biaya_mesin'] : 0;
			$rate_mould 		= (!empty($GET_MOLD_RATE[$kode_mold]['biaya_mesin'])) ? $GET_MOLD_RATE[$kode_mold]['biaya_mesin'] : 0;

			// if('P423000121' == $code_level4){
			// 	echo $kode_mesin.'<br>';
			// 	echo $rate_depresiasi; exit;
			// }

			$persen_indirect 	= $GET_RATE_COSTING[3];
			$persen_consumable 	= $GET_RATE_COSTING[6];
			$persen_packing 	= $GET_RATE_COSTING[7];
			$persen_enginnering = $GET_RATE_COSTING[9];
			$persen_foh 		= $GET_RATE_COSTING[10];
			$persen_fin_adm 	= $GET_RATE_COSTING[11];
			$persen_mkt_sales 	= $GET_RATE_COSTING[12];
			$persen_interest 	= $GET_RATE_COSTING[13];
			$persen_profit 		= $GET_RATE_COSTING[14];
			$persen_allowance 	= $GET_RATE_COSTING[18];

			//1 material
			$cost_material 	= $TOTAL_PRICE_ALL;
			//2 man power
			$direct_labour	= $rate_cycletime * $rate_manpower;
			$indirect 		= $direct_labour * $persen_indirect / 100;
			$cost_man_power = $direct_labour + $indirect;
			//3 machine mould consumable
			$machine 	= $rate_cycletime_mch * $rate_depresiasi;
			$mould 		= $rate_cycletime_mch * $rate_mould;
			$consumable = $cost_material * ($persen_consumable / 100);
			$cost_mesin	= $machine + $mould + $consumable;
			//4 logistik
			$packing 		= ($cost_material + $cost_man_power + $cost_mesin) * $persen_packing / 100;
			$transport		= 0;
			$cost_logistik 	= $packing + $transport;

			$cost_enginnering 	= ($cost_material + $cost_man_power + $cost_mesin) * $persen_enginnering / 100;
			$cost_foh 			= ($cost_material + $cost_man_power + $cost_mesin + $cost_logistik + $cost_enginnering) * $persen_foh / 100;
			$cost_fin_adm 		= ($cost_material + $cost_man_power + $cost_mesin + $cost_logistik + $cost_enginnering) * $persen_fin_adm / 100;
			$cost_mkt_sales 	= ($cost_material + $cost_man_power + $cost_mesin + $cost_logistik + $cost_enginnering) * $persen_mkt_sales / 100;
			$cost_interest 		= ($cost_material + $cost_man_power + $cost_mesin + $cost_logistik + $cost_enginnering + $cost_foh + $cost_fin_adm + $cost_mkt_sales) * $persen_interest / 100;
			$cost_profit 		= ($cost_material + $cost_man_power + $cost_mesin + $cost_logistik + $cost_enginnering + $cost_foh + $cost_fin_adm + $cost_mkt_sales + $cost_interest) * $persen_profit / 100;
			$bottom_price 		= ($cost_material + $cost_man_power + $cost_mesin + $cost_logistik + $cost_enginnering + $cost_foh + $cost_fin_adm + $cost_mkt_sales + $cost_interest + $cost_profit);
			$factor_kompetitif	= 1;
			$bottom_selling		= $bottom_price * $factor_kompetitif;
			$nego_allowance		= $bottom_selling * ($persen_allowance / 100);
			$price_final		= $bottom_selling + $nego_allowance;

			$ArrHeader[$key]['rate_cycletime'] 			= $rate_cycletime;
			// $ArrHeader[$key]['rate_cycletime_machine'] 	= $rate_cycletime_mch;
			$ArrHeader[$key]['rate_man_power_usd'] 		= $rate_manpower;
			$ArrHeader[$key]['rate_man_power_idr'] 	= $GET_RATE_MAN_POWER[0]->upah_per_jam;
			$ArrHeader[$key]['rate_depresiasi'] 	= $rate_depresiasi;
			$ArrHeader[$key]['rate_mould'] 			= $rate_mould;
			$ArrHeader[$key]['cost_material'] 		= $cost_material;
			$ArrHeader[$key]['cost_persen_indirect'] 	= $persen_indirect;
			$ArrHeader[$key]['cost_persen_consumable'] 	= $persen_consumable;
			$ArrHeader[$key]['cost_persen_packing'] 	= $persen_packing;
			$ArrHeader[$key]['cost_persen_enginnering']	= $persen_enginnering;
			$ArrHeader[$key]['cost_persen_foh'] 		= $persen_foh;
			$ArrHeader[$key]['cost_persen_fin_adm'] 	= $persen_fin_adm;
			$ArrHeader[$key]['cost_persen_mkt_sales'] 	= $persen_mkt_sales;
			$ArrHeader[$key]['cost_persen_interest'] 	= $persen_interest;
			$ArrHeader[$key]['cost_persen_profit'] 		= $persen_profit;
			$ArrHeader[$key]['cost_bottom_price'] 		= $bottom_price;
			$ArrHeader[$key]['cost_factor_kompetitif']	= $factor_kompetitif;
			$ArrHeader[$key]['cost_nego_allowance'] 	= $persen_allowance;
			$ArrHeader[$key]['cost_price_final'] 		= $price_final;

			$ArrHeader[$key]['price_material'] 			= $cost_material;
			$ArrHeader[$key]['price_man_power'] 		= $cost_man_power;
			$ArrHeader[$key]['price_total'] 			= $price_final;
			$ArrHeader[$key]['cost_direct_labout'] 		= $direct_labour;
			$ArrHeader[$key]['cost_indirect'] 			= $indirect;
			$ArrHeader[$key]['cost_machine'] 			= $machine;
			$ArrHeader[$key]['cost_mould'] 				= $mould;
			$ArrHeader[$key]['cost_consumable'] 		= $consumable;
			$ArrHeader[$key]['cost_packing'] 			= $packing;
			$ArrHeader[$key]['cost_transport'] 			= $transport;
			$ArrHeader[$key]['cost_enginnering'] 		= $cost_enginnering;
			$ArrHeader[$key]['cost_foh'] 				= $cost_foh;
			$ArrHeader[$key]['cost_fin_adm'] 			= $cost_fin_adm;
			$ArrHeader[$key]['cost_mkt_sales'] 			= $cost_mkt_sales;
			$ArrHeader[$key]['cost_interest'] 			= $cost_interest;
			$ArrHeader[$key]['cost_profit'] 			= $cost_profit;
			$ArrHeader[$key]['cost_bottom_selling'] 	= $bottom_selling;
			$ArrHeader[$key]['cost_allowance'] 			= $nego_allowance;
		}


		// echo '<pre>';
		// print_r($ArrHeader);
		// print_r($ArrDetailDefault);
		// print_r($ArrDetailAdditive);
		// print_r($ArrDetailAdditiveCustom);
		// print_r($ArrDetailTopping);
		// exit;

		$ArrUpdate = [
			'deleted_by' => $id_user,
			'deleted_date' => $dateTime
		];

		$this->db->trans_begin();
		if (!empty($ArrHeader)) {
			$this->db->update('product_price', $ArrUpdate);

			$this->db->insert_batch('product_price', $ArrHeader);
		}
		if (!empty($ArrDetailDefault)) {
			$this->db->insert_batch('product_price_bom_detail', $ArrDetailDefault);
		}
		if (!empty($ArrDetailAdditive)) {
			$this->db->insert_batch('product_price_bom_detail', $ArrDetailAdditive);
		}
		if (!empty($ArrDetailAdditiveCustom)) {
			$this->db->insert_batch('product_price_bom_detail_custom', $ArrDetailAdditiveCustom);
		}
		if (!empty($ArrDetailTopping)) {
			$this->db->insert_batch('product_price_bom_detail', $ArrDetailTopping);
		}
		if (!empty($ArrDetailToppingCustom)) {
			$this->db->insert_batch('product_price_bom_detail_custom', $ArrDetailToppingCustom);
		}
		// $this->db->trans_complete();

		print_r($this->db->error()['message']);
		if ($this->db->trans_status() === FALSE) {
			exit;
			$this->db->trans_rollback();
			$status	= array(
				'pesan'		=> 'Failed process data!',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$status	= array(
				'pesan'		=> 'Success process data!',
				'status'	=> 1
			);
			history('Update product price');
		}
		echo json_encode($status);
	}

	public function detail_machine_mold()
	{
		$id_product = $this->input->post('id_product');
		$tanda 		= $this->input->post('tanda');
		$cost 		= $this->input->post('cost');
		$header = $this->db->get_where('cycletime_header', array('id_product' => $id_product, 'deleted_date' => NULL))->result();
		// print_r($header);
		$title = ($tanda == 'machine') ? 'Machine' : 'Mold';
		$data = [
			'id_product' => $id_product,
			'header' => $header,
			'tanda' => $tanda,
			'title' => $title,
			'cost' => $cost,
		];
		$this->template->render('detail_machine_mold', $data);
	}





























	public function add()
	{
		if ($this->input->post()) {
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			// print_r($data);
			// exit;
			$session 		  = $this->session->userdata('app_session');
			$Detail 	    = $data['Detail'];
			$Ym					  = date('ym');
			$no_bom        = $data['no_bom'];
			$no_bomx        = $data['no_bom'];
			$check_p			  = "SELECT * FROM bom_header WHERE id_product ='" . $data['id_product'] . "' ";
			$num_p		= $this->db->query($check_p)->num_rows();
			// if($num_p < 1){
			$created_by   = 'updated_by';
			$created_date = 'updated_date';
			$tanda        = 'Insert ';
			if (empty($no_bomx)) {
				//pengurutan kode
				$srcMtr			  = "SELECT MAX(no_bom) as maxP FROM bom_header WHERE no_bom LIKE 'BOM" . $Ym . "%' ";
				$numrowMtr		= $this->db->query($srcMtr)->num_rows();
				$resultMtr		= $this->db->query($srcMtr)->result_array();
				$angkaUrut2		= $resultMtr[0]['maxP'];
				$urutan2		  = (int)substr($angkaUrut2, 7, 3);
				$urutan2++;
				$urut2			  = sprintf('%03s', $urutan2);
				$no_bom	      = "BOM" . $Ym . $urut2;

				$created_by   = 'created_by';
				$created_date = 'created_date';
				$tanda        = 'Update ';
			}

			$ArrHeader		= array(
				'no_bom'			    => $no_bom,
				'id_product'	    => $data['id_product'],
				'variant_product'	    => $data['variant_product'],
				'waste_product'	    => str_replace(',', '', $data['waste_product']),
				'waste_setting'	    => str_replace(',', '', $data['waste_setting']),
				$created_by	    => $session['id_user'],
				$created_date	  => date('Y-m-d H:i:s')
			);

			$ArrDetail	= array();
			$ArrDetail2	= array();
			foreach ($Detail as $val => $valx) {
				$urut				= sprintf('%03s', $val);
				$ArrDetail[$val]['no_bom'] 			 = $no_bom;
				$ArrDetail[$val]['no_bom_detail'] = $no_bom . "-" . $urut;
				$ArrDetail[$val]['code_material'] 		 = $valx['code_material'];
				$ArrDetail[$val]['weight'] 	 = str_replace(',', '', $valx['weight']);
			}

			// print_r($ArrHeader);
			// print_r($ArrDetail);
			// exit;

			$this->db->trans_start();
			if (empty($no_bomx)) {
				$this->db->insert('bom_header', $ArrHeader);
			}
			if (!empty($no_bomx)) {
				$this->db->where('no_bom', $no_bom);
				$this->db->update('bom_header', $ArrHeader);
			}

			if (!empty($ArrDetail)) {
				$this->db->delete('bom_detail', array('no_bom' => $no_bom));
				$this->db->insert_batch('bom_detail', $ArrDetail);
			}
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=> 'Save gagal disimpan ...',
					'status'	=> 0
				);
			} else {
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=> 'Save berhasil disimpan. Thanks ...',
					'status'	=> 1
				);
				history($tanda . " BOM " . $no_bom);
			}
			// }
			// else{
			//   $Arr_Data	= array(
			//     'pesan'		=>'Product sudah digunakan .',
			//     'status'	=> 0
			//   );
			// }

			echo json_encode($Arr_Data);
		} else {
			$session  = $this->session->userdata('app_session');
			$no_bom 	  = $this->uri->segment(3);
			$header   = $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();
			$detail   = $this->db->get_where('bom_detail', array('no_bom' => $no_bom))->result_array();
			$product    = $this->product_price_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'));
			$material    = $this->product_price_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));

			// print_r($header);
			// exit;
			$data = [
				'header' => $header,
				'detail' => $detail,
				'product' => $product,
				'material' => $material
			];
			$this->template->set('results', $data);
			$this->template->title('Add Bill Of Materials');
			$this->template->page_icon('fa fa-edit');
			$this->template->render('add', $data);
		}
	}


	public function detail()
	{
		// $this->auth->restrict($this->viewPermission);
		$no_bom 	= $this->input->post('no_bom');
		$header = $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();
		$detail = $this->db->get_where('bom_detail', array('no_bom' => $no_bom))->result_array();
		$product    = $this->product_price_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'product'));
		// print_r($header);
		$data = [
			'header' => $header,
			'detail' => $detail,
			'product' => $product,
			'GET_LEVEL4' => get_inventory_lv4(),
		];
		$this->template->set('results', $data);
		$this->template->render('detail', $data);
	}

	public function get_add()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$material    = $this->product_price_model->get_data_where_array('new_inventory_4', array('deleted_date' => NULL, 'category' => 'material'));
		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='header_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<select name='Detail[" . $id . "][code_material]' class='chosen_select form-control input-sm inline-blockd material'>";
		$d_Header .= "<option value='0'>Select Material Name</option>";
		foreach ($material as $valx) {
			$d_Header .= "<option value='" . $valx->code_lv4 . "'>" . strtoupper($valx->nama) . "</option>";
		}
		$d_Header .= 		"</select>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][weight]' class='form-control input-md autoNumeric4 qty' placeholder='Weight'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add Material'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Material</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function hapus()
	{
		$data = $this->input->post();
		$session 		= $this->session->userdata('app_session');
		$no_bom  = $data['id'];

		$ArrHeader		= array(
			'deleted'			  => "Y",
			'deleted_by'	  => $session['id_user'],
			'deleted_date'	=> date('Y-m-d H:i:s')
		);

		$this->db->trans_start();
		$this->db->where('no_bom', $no_bom);
		$this->db->update('bom_header', $ArrHeader);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=> 'Save gagal disimpan ...',
				'status'	=> 0
			);
		} else {
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=> 'Save berhasil disimpan. Thanks ...',
				'status'	=> 1
			);
			history("Delete data BOM " . $no_bom);
		}

		echo json_encode($Arr_Data);
	}

	public function excel_report_all_bom()
	{
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();

		$tableHeader 	= tableHeader();
		$mainTitle 		= mainTitle();
		$tableBodyCenter = tableBodyCenter();
		$tableBodyLeft 	= tableBodyLeft();
		$tableBodyRight = tableBodyRight();

		$sheet 		= $objPHPExcel->getActiveSheet();

		$product    = $this->db
			->select('a.*, b.nama AS nm_product')
			->order_by('a.no_bom', 'desc')
			->join('new_inventory_4 b', 'a.id_product=b.code_lv4', 'left')
			->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'standard'))
			->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(6);
		$sheet->setCellValue('A' . $Row, 'BOM HEAD TO HEAD');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;
		$NextRow = $NewRow + 1;

		$sheet->setCellValue('A' . $NewRow, 'No');
		$sheet->getStyle('A' . $NewRow . ':A' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('A' . $NewRow . ':A' . $NextRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$sheet->setCellValue('B' . $NewRow, 'Product Name');
		$sheet->getStyle('B' . $NewRow . ':B' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('B' . $NewRow . ':B' . $NextRow);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('C' . $NewRow, 'Variant');
		$sheet->getStyle('C' . $NewRow . ':C' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('C' . $NewRow . ':C' . $NextRow);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$sheet->setCellValue('D' . $NewRow, 'Total Weight');
		$sheet->getStyle('D' . $NewRow . ':D' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('D' . $NewRow . ':D' . $NextRow);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		$sheet->setCellValue('E' . $NewRow, 'Waste Product (%)');
		$sheet->getStyle('E' . $NewRow . ':E' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('E' . $NewRow . ':E' . $NextRow);
		$sheet->getColumnDimension('E')->setAutoSize(true);

		$sheet->setCellValue('F' . $NewRow, 'Waste Setting/Cleaning (%)');
		$sheet->getStyle('F' . $NewRow . ':F' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('F' . $NewRow . ':F' . $NextRow);
		$sheet->getColumnDimension('F')->setAutoSize(true);

		if ($product) {
			$awal_row	= $NextRow;
			$no = 0;
			foreach ($product as $key => $row_Cek) {
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$nomor	= $no;
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $nomor);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$nm_product	= $row_Cek['nm_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $nm_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$variant_product	= $row_Cek['variant_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $variant_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$SUM_WEIGHT = $this->db->query("SELECT SUM(weight) AS berat FROM bom_detail WHERE no_bom = '" . $row_Cek['no_bom'] . "' ")->result();
				$awal_col++;
				$status_date	= number_format($SUM_WEIGHT[0]->berat, 4);
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $status_date);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyRight);

				$awal_col++;
				$waste_product	= $row_Cek['waste_product'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $waste_product);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$waste_setting	= $row_Cek['waste_setting'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $waste_setting);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);
			}
		}

		$sheet->setTitle('BOM');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="bom.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function excel_report_all_bom_detail()
	{
		$kode_bom = $this->uri->segment(3);
		set_time_limit(0);
		ini_set('memory_limit', '1024M');

		$this->load->library("PHPExcel");
		$objPHPExcel	= new PHPExcel();

		$tableHeader 	= tableHeader();
		$mainTitle 		= mainTitle();
		$tableBodyCenter = tableBodyCenter();
		$tableBodyLeft 	= tableBodyLeft();
		$tableBodyRight = tableBodyRight();

		$sheet 		= $objPHPExcel->getActiveSheet();

		$sql = "
  			SELECT
  				a.id_product,
				a.variant_product,
          		b.code_material,
          		b.weight,
				c.nama AS nm_product
  			FROM
  				bom_header a 
				LEFT JOIN bom_detail b ON a.no_bom = b.no_bom
				LEFT JOIN new_inventory_4 c ON a.id_product = c.code_lv4
  		    WHERE 
				a.no_bom = '" . $kode_bom . "' 
				AND b.no_bom = '" . $kode_bom . "'
				AND a.category = 'standard'
  			ORDER BY
  				b.id ASC
  		";
		$product    = $this->db->query($sql)->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(3);
		$sheet->setCellValue('A' . $Row, 'BOM HEAD TO HEAD DETAIL');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;

		$sheet->setCellValue('A' . $NewRow, $product[0]['nm_product']);
		$sheet->getStyle('A' . $NewRow . ':C' . $NewRow)->applyFromArray($tableBodyLeft);
		$sheet->mergeCells('A' . $NewRow . ':C' . $NewRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$NewRow	 = $NewRow + 1;
		$NextRow = $NewRow;

		$sheet->setCellValue('A' . $NewRow, $product[0]['variant_product']);
		$sheet->getStyle('A' . $NewRow . ':C' . $NewRow)->applyFromArray($tableBodyLeft);
		$sheet->mergeCells('A' . $NewRow . ':C' . $NewRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$NewRow	 = $NewRow + 2;
		$NextRow = $NewRow;

		$sheet->setCellValue('A' . $NewRow, 'No');
		$sheet->getStyle('A' . $NewRow . ':A' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('A' . $NewRow . ':A' . $NextRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$sheet->setCellValue('B' . $NewRow, 'Material Name');
		$sheet->getStyle('B' . $NewRow . ':B' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('B' . $NewRow . ':B' . $NextRow);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('C' . $NewRow, 'Total Weight');
		$sheet->getStyle('C' . $NewRow . ':C' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('C' . $NewRow . ':C' . $NextRow);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		if ($product) {
			$awal_row	= $NextRow;
			$no = 0;
			foreach ($product as $key => $row_Cek) {
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $no);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$status_date	= strtoupper(get_name('new_inventory_4', 'nama', 'code_lv4', $row_Cek['code_material']));
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $status_date);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);

				$awal_col++;
				$status_date	= number_format($row_Cek['weight'], 4);
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols . $awal_row, $status_date);
				$sheet->getStyle($Cols . $awal_row)->applyFromArray($tableBodyLeft);
			}
		}


		$sheet->setTitle('List BOM DETAIL');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="bom-detail-' . $kode_bom . '.xls"');
		//unduh file
		$objWriter->save("php://output");
	}
}
