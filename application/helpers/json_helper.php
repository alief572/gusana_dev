<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");

function get_root3()
{
	return $_SERVER['DOCUMENT_ROOT'] . '/origa_live';
}

function whiteCenterBold()
{
	$styleArray = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		),
		'font' => array(
			'bold' => true,
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);
	return $styleArray;
}

function whiteRightBold()
{
	$styleArray = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		),
		'font' => array(
			'bold' => true,
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);
	return $styleArray;
}

function whiteCenter()
{
	$styleArray = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);
	return $styleArray;
}

function mainTitle()
{
	$styleArray = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'e0e0e0'),
		),
		'font' => array(
			'bold' => true,
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)
	);
	return $styleArray;
}

function tableHeader()
{
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'e0e0e0'),
		),
		'font' => array(
			'bold' => true,
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)
	);
	return $styleArray;
}

function tableBodyCenter()
{
	$styleArray = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);
	return $styleArray;
}

function tableBodyLeft()
{
	$styleArray = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);
	return $styleArray;
}

function tableBodyRight()
{
	$styleArray = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		)
	);
	return $styleArray;
}

//NEW
function get_list_inventory_lv1($category)
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('new_inventory_1', array('category' => $category, 'deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code_lv1']]['code_lv1'] 	= $value['code_lv1'];
		$ArrGetCategory[$value['code_lv1']]['nama'] 		= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_list_inventory_lv2($category)
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('new_inventory_2', array('category' => $category, 'deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']]['code_lv1'] 	= $value['code_lv1'];
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']]['code_lv2'] 	= $value['code_lv2'];
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']]['nama'] 		= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_list_inventory_lv3($category)
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('new_inventory_3', array('category' => $category, 'deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']][$value['code_lv3']]['code_lv1'] 	= $value['code_lv1'];
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']][$value['code_lv3']]['code_lv2'] 	= $value['code_lv2'];
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']][$value['code_lv3']]['code_lv3'] 	= $value['code_lv3'];
		$ArrGetCategory[$value['code_lv1']][$value['code_lv2']][$value['code_lv3']]['nama'] 		= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_inventory_lv4()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get('new_inventory_4')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code_lv4']]['code_lv1'] 	= $value['code_lv1'];
		$ArrGetCategory[$value['code_lv4']]['code_lv2'] 	= $value['code_lv2'];
		$ArrGetCategory[$value['code_lv4']]['code_lv3'] 	= $value['code_lv3'];
		$ArrGetCategory[$value['code_lv4']]['code_lv4'] 	= $value['code_lv4'];
		$ArrGetCategory[$value['code_lv4']]['nama'] 		= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_inventory_lv3()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get('new_inventory_3')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code_lv3']]['code_lv3'] 	= $value['code_lv3'];
		$ArrGetCategory[$value['code_lv3']]['nama'] 		= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_persen_additive()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get('bom_detail')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$key = $value['no_bom'] . '-' . $value['code_material'];
		$ArrGetCategory[$key]['persen']	= $value['persen'];
	}
	return $ArrGetCategory;
}

function get_accessories()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get('accessories')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['id']]['nama_full'] = $value['stock_name'] . ' ' . $value['brand'] . ' ' . $value['spec'];
	}
	return $ArrGetCategory;
}

function get_list_machine()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->group_by('kd_asset')->get_where('asset', array('deleted_date' => NULL, 'category' => '4'))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['kd_asset']]['kd_asset'] 	= $value['kd_asset'];
		$ArrGetCategory[$value['kd_asset']]['nm_asset'] 	= $value['nm_asset'];
	}
	return $ArrGetCategory;
}

function get_list_mould()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->group_by('kd_asset')->get_where('asset', array('deleted_date' => NULL, 'category' => '7'))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['kd_asset']]['kd_asset'] 	= $value['kd_asset'];
		$ArrGetCategory[$value['kd_asset']]['nm_asset'] 	= $value['nm_asset'];
	}
	return $ArrGetCategory;
}

function get_list_satuan()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get('m_unit')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['id_unit']] 	= $value['id_unit'];
	}
	return $ArrGetCategory;
}

function get_list_country()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->order_by('country_name', 'asc')->get('country')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['country_code']]['nama'] 	= $value['country_name'];
	}
	return $ArrGetCategory;
}

function get_list_country_all()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get('country_all')->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['iso3']]['nama'] 	= $value['name'];
	}
	return $ArrGetCategory;
}

function get_inventory_lv4_lv3()
{
	$CI = &get_instance();
	$listGetCategory4 = $CI->db->select('code_lv4 AS code, nama, "LEVEL 4" AS tanda')->get('new_inventory_4')->result_array();
	$listGetCategory3 = $CI->db->select('code_lv3 AS code, nama, "LEVEL 3" AS tanda')->get('new_inventory_3')->result_array();
	$listGetCategory	= array_merge($listGetCategory4, $listGetCategory3);

	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code']]['tanda'] 	= $value['tanda'];
		$ArrGetCategory[$value['code']]['code'] 	= $value['code'];
		$ArrGetCategory[$value['code']]['nama'] 	= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_list_supplier()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('new_supplier', array('deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['id']]['nama'] 	= $value['nama'];
	}
	return $ArrGetCategory;
}

function get_price_ref()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('new_inventory_4', array('deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code_lv4']]['price_ref'] 	= $value['price_ref_use'];
	}
	return $ArrGetCategory;
}

function get_rate_costing_rate()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('costing_rate', array('deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['code']] 	= $value['rate'];
	}
	return $ArrGetCategory;
}

function log_history($id_user, $id_menu, $nm_menu, $device_type, $os_type, $desc)
{
	$CI = &get_instance();
	$generate_id = $CI->db->query("SELECT MAX(id) AS max_id FROM ms_log_history WHERE id LIKE '%LOG-" . date('ymd') . "%'")->row();
	$kodeBarang = $generate_id->max_id;
	$urutan = (int) substr($kodeBarang, 12, 6);
	$urutan++;
	$tahun = date('ymd') . '-';
	$huruf = "LOG-";
	$kodecollect = $huruf . $tahun . sprintf("%06s", $urutan);

	// $CI->db->trans_begin();

	$CI->db->insert('ms_log_history', [
		'id' => $kodecollect,
		'id_user' => $id_user,
		'id_menu' => $id_menu,
		'nm_menu' => $nm_menu,
		'description' => $desc,
		'device_type' => $device_type,
		'ip_addr' => $os_type,
		'date_time' => date('Y-m-d H:i:s')
	]);

	// if ($CI->db->trans_status() === FALSE) {
	// 	$CI->db->trans_rollback();
	// } else {
	// 	$CI->db->trans_commit();
	// }

	// return $CI->db->trans_status();
}

function get_machine_product()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db
		->select('a.id_product,b.machine')
		->group_by('a.id_product')
		->join('cycletime_detail_detail b', 'a.id_time=b.id_time', 'join')
		->get_where('cycletime_header a', array('a.deleted_date' => NULL, 'b.machine !=' => NULL, 'b.machine !=' => '0'))
		->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['id_product']] 	= $value['machine'];
	}
	return $ArrGetCategory;
}

function get_mold_product()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db
		->select('a.id_product,b.mould')
		->group_by('a.id_product')
		->join('cycletime_detail_detail b', 'a.id_time=b.id_time', 'join')
		->get_where('cycletime_header a', array('a.deleted_date' => NULL, 'b.mould !=' => NULL, 'b.mould !=' => '0'))
		->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['id_product']] 	= $value['mould'];
	}
	return $ArrGetCategory;
}

function get_rate_machine()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('rate_machine', array('deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['kd_mesin']]['biaya_mesin'] 	= $value['biaya_mesin'];
	}
	return $ArrGetCategory;
}

function get_rate_mold()
{
	$CI = &get_instance();
	$listGetCategory = $CI->db->get_where('rate_mold', array('deleted_date' => NULL))->result_array();
	$ArrGetCategory = [];
	foreach ($listGetCategory as $key => $value) {
		$ArrGetCategory[$value['kd_mesin']]['biaya_mesin'] 	= $value['biaya_mesin'];
	}
	return $ArrGetCategory;
}
