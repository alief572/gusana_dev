<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Man_power_rate extends Admin_Controller
{
	//Permission
	protected $viewPermission 	= 'Man_Power_Rate.View';
	protected $addPermission  	= 'Man_Power_Rate.Add';
	protected $managePermission = 'Man_Power_Rate.Manage';
	protected $deletePermission = 'Man_Power_Rate.Delete';

	public function __construct()
	{
		parent::__construct();

		$this->load->library(['user_agent', 'uri']);
		$this->load->model(array(
			'Man_power_rate/Man_power_rate_model'
		));
		$this->load->helper(['url', 'json']);
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

		$data = [
			'gaji_pokok' => $this->db->get_where('ms_gaji_pokok',['id' => 1])->row(),
			'komp_sdmp' => $this->Man_power_rate_model->get_choose_komp_with_komp('1'),
			'komp_bpjs' => $this->Man_power_rate_model->get_choose_komp_with_komp('2'),
			'komp_bll' => $this->Man_power_rate_model->get_choose_komp_with_komp('3'),
		];


		history("View index man power rate");
		$this->template->set($data);
		$this->template->title('Man Power Rate');
		$this->template->render('detail');
	}

	public function data_side_bom()
	{
		$this->Man_power_rate_model->get_json_bom();
	}

	public function add()
	{
		$data = [
			'gaji_pokok' => $this->db->get_where('ms_gaji_pokok', ['id' => 1])->row(),
			'komp_sdmp' => $this->Man_power_rate_model->get_choose_komp_with_komp('1'),
			'komp_bpjs' =>$this->Man_power_rate_model->get_choose_komp_with_komp('2') ,
			'komp_bll' => $this->Man_power_rate_model->get_choose_komp_with_komp('3')
		];

		$this->template->set($data);
		$this->template->title('Man Power Rate');
		$this->template->render('add');
	}

	public function detail()
	{
		// $this->auth->restrict($this->viewPermission);
		$no_bom 	= $this->input->post('no_bom');
		$header = $this->db->get_where('bom_header', array('no_bom' => $no_bom))->result();
		$detail = $this->db->get_where('bom_detail', array('no_bom' => $no_bom))->result_array();
		// print_r($header);
		$data = [
			'header' => $header,
			'detail' => $detail
		];
		$this->template->set('results', $data);
		$this->template->render('detail', $data);
	}

	public function get_add()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='header_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][nama]' class='form-control input-md' placeholder='Nama'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][nilai]' class='form-control text-right input-md autoNumeric2 nilaiDirect summaryCal' placeholder='Nilai'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail[" . $id . "][keterangan]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add2()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='header2_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail2[" . $id . "][nama]' class='form-control input-md' placeholder='Nama'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail2[" . $id . "][nilai]' class='form-control text-right input-md autoNumeric2 nilaiBPJS summaryCal' placeholder='Nilai'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail2[" . $id . "][keterangan]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add2_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart2' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		echo json_encode(array(
			'header'			=> $d_Header,
		));
	}

	public function get_add3()
	{
		$id 	= $this->uri->segment(3);
		$no 	= 0;

		$d_Header = "";
		// $d_Header .= "<tr>";
		$d_Header .= "<tr class='header3_" . $id . "'>";
		$d_Header .= "<td align='center'>" . $id . "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail3[" . $id . "][nama]' class='form-control input-md' placeholder='Nama'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail3[" . $id . "][nilai]' class='form-control text-right input-md autoNumeric2 nilaiLain summaryCal' placeholder='Nilai'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail3[" . $id . "][keterangan]' class='form-control input-md' placeholder='Keterangan'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "<input type='text' name='Detail3[" . $id . "][harga_per_pcs]' class='form-control text-right input-md autoNumeric2' placeholder='Harga /Pcs'>";
		$d_Header .= "</td>";
		$d_Header .= "<td align='left'>";
		$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
		$d_Header .= "</td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add3_" . $id . "'>";
		$d_Header .= "<td align='center'></td>";
		$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart3' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add</button></td>";
		$d_Header .= "<td align='center'></td>";
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
			->select('a.*, a.additive_name AS nm_product')
			->order_by('a.no_bom', 'desc')
			->get_where('bom_header a', array('a.deleted_date' => NULL, 'a.category' => 'additive'))
			->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(4);
		$sheet->setCellValue('A' . $Row, 'BOM ADDITIVE');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;
		$NextRow = $NewRow + 1;

		$sheet->setCellValue('A' . $NewRow, 'No');
		$sheet->getStyle('A' . $NewRow . ':A' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('A' . $NewRow . ':A' . $NextRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$sheet->setCellValue('B' . $NewRow, 'Kegunaan Additive');
		$sheet->getStyle('B' . $NewRow . ':B' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('B' . $NewRow . ':B' . $NextRow);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('C' . $NewRow, 'Waste Product (%)');
		$sheet->getStyle('C' . $NewRow . ':C' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('C' . $NewRow . ':C' . $NextRow);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$sheet->setCellValue('D' . $NewRow, 'Waste Setting/Cleaning (%)');
		$sheet->getStyle('D' . $NewRow . ':D' . $NextRow)->applyFromArray($tableHeader);
		$sheet->mergeCells('D' . $NewRow . ':D' . $NextRow);
		$sheet->getColumnDimension('D')->setAutoSize(true);

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
		header('Content-Disposition: attachment;filename="bom-additive.xls"');
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
          		b.persen,
				a.additive_name AS nm_product
  			FROM
  				bom_header a 
				LEFT JOIN bom_detail b ON a.no_bom = b.no_bom
  		    WHERE 
				a.no_bom = '" . $kode_bom . "' 
				AND b.no_bom = '" . $kode_bom . "'
				AND a.category = 'additive'
  			ORDER BY
  				b.id ASC
  		";
		$product    = $this->db->query($sql)->result_array();

		$Row		= 1;
		$NewRow		= $Row + 1;
		$Col_Akhir	= $Cols	= getColsChar(3);
		$sheet->setCellValue('A' . $Row, 'BOM ADDITIVE DETAIL');
		$sheet->getStyle('A' . $Row . ':' . $Col_Akhir . $NewRow)->applyFromArray($mainTitle);
		$sheet->mergeCells('A' . $Row . ':' . $Col_Akhir . $NewRow);

		$NewRow	= $NewRow + 2;

		$sheet->setCellValue('A' . $NewRow, $product[0]['nm_product']);
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

		$sheet->setCellValue('C' . $NewRow, 'Pengurangan Resin (%)');
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
				$status_date	= number_format($row_Cek['persen'], 2);
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
		header('Content-Disposition: attachment;filename="bom-additive-detail-' . $kode_bom . '.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function upd_gaji_pokok()
	{
		$gaji_pokok = $this->input->post('gaji_pokok');

		$this->db->trans_begin();

		$this->db->update('ms_gaji_pokok', ['gaji_pokok' => $gaji_pokok]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$hasil_sdmp = '';
		$get_sdmp = $this->db->get_where('ms_komp_man_power_rate', ['tipe' => 1])->result();
		$x = 1;
		foreach ($get_sdmp as $list_sdmp) {
			$nominal = 0;
			if (isset($gaji_pokok) && $gaji_pokok > 0) {
				$nominal = ($gaji_pokok * ($list_sdmp->std_val / 100));
			}
			$hasil_sdmp = $hasil_sdmp . '
				<tr>
					<td class="text-center">' . $x . '</td>
					<td class="text-center">' . $list_sdmp->nm_komp . '</td>
					<td class="text-center">' . $list_sdmp->std_val . '%</td>
					<td class="text-center">' . number_format($nominal, 2) . '</td>
					<td class="text-center">' . $list_sdmp->keterangan . '</td>
				</tr>
			';

			$x++;
		}
	}

	public function ubah_periode_bulan()
	{
		$post = $this->input->post();

		$this->db->trans_begin();

		$this->db->update(
			'ms_komp_man_power_rate',
			['periode_bulan' => $post['periode_bulan']],
			['id' => $post['id']]
		);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();
		$get_komp = $this->db->get_where('ms_komp_man_power_rate', ['id' => $post['id']])->row();

		$desc = "Ubah Periode Bulan pada Komponen Man Power Rate " . $post['id'] . " - " . $get_komp->nm_komp;
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

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$hasil = '';
		$ttl = 0;

		$get_data = $this->db->get_where('ms_komp_man_power_rate', ['tipe' => 3])->result();
		$x = 1;
		foreach ($get_data as $list_hasil) {
			$nominal = 0;
			if ($list_hasil->periode_bulan > 0 && $list_hasil->std_val > 0 && $list_hasil->harga_pcs > 0) {
				$nominal = (($list_hasil->harga_pcs / $list_hasil->periode_bulan) * $list_hasil->std_val);
			}
			$hasil = $hasil . '
				<tr>
					<td class="text-center"><?= $x ?></td>
					<td class="text-center">' . $list_hasil->nm_komp . '</td>
					<td class="text-center">' . number_format($list_hasil->std_val, 2) . '</td>
					<td class="text-center">
						<input type="number" class="form-control form-control-sm text-right ubah_periode_bulan periode_bulan_' . $list_hasil->id . '" name="periode_bulan_' . $list_hasil->id . '" id="" value="' . $list_hasil->periode_bulan . '" data-id="' . $list_hasil->id . '">
					</td>
					<td class="text-center">' . number_format($nominal, 2) . '</td>
					<td class="text-center">' . $list_hasil->keterangan . '</td>
					<td class="text-center">
						<input type="text" name="harga_pcs_' . $list_hasil->id . '" id="" class="form-control form-control-sm text-right input_nominal ubah_harga_pcs harga_pcs_' . $list_hasil->id . '" value="' . $list_hasil->harga_pcs . '" data-id="' . $list_hasil->id . '">
					</td>
				</tr>
			';
			$ttl += $nominal;
			$x++;
		}

		$all_hasil = [
			'hasil' => $hasil,
			'ttl' => number_format($ttl, 2)
		];

		echo json_encode($all_hasil);
	}

	public function ubah_harga_pcs()
	{
		$post = $this->input->post();

		$this->db->trans_begin();

		$this->db->update(
			'ms_komp_man_power_rate',
			['harga_pcs' => $post['harga_pcs']],
			['id' => $post['id']]
		);

		// Logging
		$get_menu = $this->db->like('link', $this->uri->segment(1))->get('menus')->row();
		$get_komp = $this->db->get_where('ms_komp_man_power_rate', ['id' => $post['id']])->row();

		$desc = "Ubah Harga/Pcs pada Komponen Man Power Rate " . $post['id'] . " - " . $get_komp->nm_komp;
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

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$hasil = '';
		$ttl = 0;

		$get_data = $this->db->get_where('ms_komp_man_power_rate', ['tipe' => 3])->result();
		$x = 1;
		foreach ($get_data as $list_hasil) {
			$nominal = 0;
			if ($list_hasil->periode_bulan > 0 && $list_hasil->std_val > 0 && $list_hasil->harga_pcs > 0) {
				$nominal = (($list_hasil->harga_pcs / $list_hasil->periode_bulan) * $list_hasil->std_val);
			}
			$hasil = $hasil . '
				<tr>
					<td class="text-center"><?= $x ?></td>
					<td class="text-center">' . $list_hasil->nm_komp . '</td>
					<td class="text-center">' . number_format($list_hasil->std_val, 2) . '</td>
					<td class="text-center">
						<input type="number" class="form-control form-control-sm text-right ubah_periode_bulan periode_bulan_' . $list_hasil->id . '" name="periode_bulan_' . $list_hasil->id . '" id="" value="' . $list_hasil->periode_bulan . '" data-id="' . $list_hasil->id . '">
					</td>
					<td class="text-center">' . number_format($nominal, 2) . '</td>
					<td class="text-center">' . $list_hasil->keterangan . '</td>
					<td class="text-center">
						<input type="text" name="harga_pcs_' . $list_hasil->id . '" id="" class="form-control form-control-sm text-right input_nominal ubah_harga_pcs harga_pcs_' . $list_hasil->id . '" value="' . $list_hasil->harga_pcs . '" data-id="' . $list_hasil->id . '">
					</td>
				</tr>
			';
			$ttl += $nominal;
			$x++;
		}

		$all_hasil = [
			'hasil' => $hasil,
			'ttl' => number_format($ttl, 2)
		];

		echo json_encode($all_hasil);
	}

	public function ubah_kurs()
	{
		$kurs = $this->input->post('kurs');
		$ttl_all = $this->input->post('ttl_all');
		$gaji_pokok = $this->input->post('gaji_pokok');

		$this->db->trans_begin();

		$this->db->update('ms_gaji_pokok', ['kurs' => $kurs], ['id' => 1]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
		
		$upah_per_bulan_dollar = (($gaji_pokok + $ttl_all) / $kurs);
		$upah_per_jam_dollar = ((($gaji_pokok + $ttl_all) / $kurs) / 173);

		$data_return = [
			'upah_per_bulan_dollar' => $upah_per_bulan_dollar,
			'upah_per_jam_dollar' => $upah_per_jam_dollar
		];

		echo json_encode($data_return);
	}

	public function check_komp()
	{
		$gaji_pokok = $this->input->post('gaji_pokok');
		$komp = $this->input->post('komp');

		$get_data_komp = $this->db->get_where('ms_komp_man_power_rate', ['id' => $komp])->row();

		$nominal = 0;
		if (isset($gaji_pokok) && $gaji_pokok > 0) {
			$nominal = ($gaji_pokok * ($get_data_komp->std_val / 100));
		}

		$data = [
			'data' => $get_data_komp,
			'nominal' => $nominal
		];

		echo json_encode($data);
	}

	public function add_komp()
	{
		$tipe = $this->input->post('tipe');
		$gaji_pokok = $this->input->post('gaji_pokok');
		$standar = $this->input->post('standar');
		$nominal = $this->input->post('nominal');
		$nm_komp = $this->input->post('nm_komp');
		$keterangan = $this->input->post('keterangan');
		$harga_pcs = $this->input->post('harga_pcs');
		$periode_bulan = $this->input->post('periode_bulan');

		// $get_data_komp = $this->Man_power_rate_model->get_data_komp_by_id($nm_komp);

		$this->db->trans_begin();

		$this->db->insert('ms_choose_komp_man_power_rate', [
			'nm_komp' => $nm_komp,
			'standar' => $standar,
			'nominal' => $nominal,
			'keterangan' => $keterangan,
			'periode_bulan' => $periode_bulan,
			'harga_pcs' => $harga_pcs,
			'tipe' => $tipe,
			'dibuat_oleh' => $this->auth->user_id(),
			'dibuat_tgl' => date('Y-m-d H:i:s')
		]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$valid = 0;
		} else {
			$this->db->trans_commit();
			$valid = 1;
		}

		$hasil = '';
		$get_data = $this->Man_power_rate_model->get_choose_komp_by_tipe($tipe);
		$no = 1;
		foreach ($get_data as $list) {

			// $get_komp = $this->Man_power_rate_model->get_data_komp_by_id($list->id_komp);

			$hasil = $hasil . '
				<tr>
					<td class="text-center">' . $no . '</td>
					<td class="text-center">' . $list->nm_komp . '</td>
					<td class="text-center">' . $list->standar . '</td>
					<td class="text-center">' . number_format($list->nominal, 2) . '</td>
					<td>' . $list->keterangan . '</td>
				</tr>
			';

			$no++;
		}

		$data = [
			'hasil' => $hasil,
			'valid' => $valid
		];

		echo json_encode($data);
	}

	public function del_komp(){
		$id = $this->input->post('id');
		$tipe = $this->input->post('tipe');
		$gaji_pokok = $this->input->post('gaji_pokok');

		$this->db->trans_begin();

		$this->db->delete('ms_choose_komp_man_power_rate',['id' => $id]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$valid = 0;
		} else {
			$this->db->trans_commit();
			$valid = 1;
		}

		$hasil = '';
		$get_data = $this->Man_power_rate_model->get_choose_komp_by_tipe($tipe);
		$no = 1;
		foreach ($get_data as $list) {

			$get_komp = $this->Man_power_rate_model->get_data_komp_by_id($list->id_komp);

			$nominal = 0;
			if (isset($gaji_pokok) && $gaji_pokok > 0) {
				$nominal = ($gaji_pokok * ($get_komp->std_val / 100));
			}

			$hasil = $hasil . '
				<tr>
					<td class="text-center">' . $no . '</td>
					<td class="text-center">' . $list->nm_komp . '</td>
					<td class="text-center">' . $get_komp->std_val . '</td>
					<td class="text-center">' . number_format($nominal, 2) . '</td>
					<td>' . $list->keterangan . '</td>
				</tr>
			';

			$no++;
		}

		$data = [
			'hasil' => $hasil,
			'valid' => $valid
		];

		echo json_encode($data);
	}
}
