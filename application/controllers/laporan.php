<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');

class Laporan extends CI_Controller {
	
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		$this->load->model('User_model', '', TRUE);
		$this->load->model('Ta_upb_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Kiba_model', '', TRUE);
		$this->load->model('Kibb_model', '', TRUE);
		$this->load->model('Kibc_model', '', TRUE);
		$this->load->model('Kibd_model', '', TRUE);
		$this->load->model('Kibe_model', '', TRUE);
		$this->load->model('Kibf_model', '', TRUE);
		$this->load->model('Lainnya_model', '', TRUE);
		$this->load->model('Pengadaan_model', '', TRUE);
		$this->load->model('Pengadaan_bj_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
		$this->load->model('Ta_ruang_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);

		$this->load->model('Rkbu_model', '', TRUE);
		$this->load->model('Rkpbu_model', '', TRUE);

		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
	}
	
	var $title = "Laporan";
	
	function index()
	{
			redirect('laporan/kib');
	}
	
	/* laporan kib a */
	function kiba()
	{

		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');

		$output 	= $this->input->post('output');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
			
		$data['jumlah'] = $this->Kiba_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kiba']   = $this->Kiba_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);

		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/laporankiba',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/laporankiba',array_merge($data,$get_ttd));
		}

	}
	

	/* laporan kib b */
	function kibb()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');

		$output 	= $this->input->post('output');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
			
		$data['jumlah'] = $this->Kibb_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibb']   = $this->Kibb_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/laporankibb',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/laporankibb',array_merge($data,$get_ttd));
		}
	}
	
	/* laporan kib c */
	function kibc()
	{
		$kd_bidang = $this->input->post('kd_bidang');
		$kd_unit   = $this->input->post('kd_unit');
		$kd_sub    = $this->input->post('kd_sub_unit');
		$kd_upb    = $this->input->post('kd_upb');
		$kondisi   = $this->input->post('kondisi');
		
		$output    = $this->input->post('output');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
			
		$data['jumlah'] = $this->Kibc_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibc']   = $this->Kibc_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);	
		
		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/laporankibc',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/laporankibc',array_merge($data,$get_ttd));
		}
	}
	
	/* laporan kib d */
	function kibd()
	{
		$kd_bidang = $this->input->post('kd_bidang');
		$kd_unit   = $this->input->post('kd_unit');
		$kd_sub    = $this->input->post('kd_sub_unit');
		$kd_upb    = $this->input->post('kd_upb');
		$kondisi   = $this->input->post('kondisi');
		
		$output    = $this->input->post('output');

		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
			
		$data['jumlah'] = $this->Kibd_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibd']   = $this->Kibd_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/laporankibd',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/laporankibd',array_merge($data,$get_ttd));
		}
	}
	
	/* laporan kib e */
	function kibe()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');

		$output    = $this->input->post('output');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
			
		$data['jumlah'] = $this->Kibe_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibe']   = $this->Kibe_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/laporankibe',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/laporankibe',array_merge($data,$get_ttd));
		}
	}
	
	
	/* laporan kib f */
	function kibf()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');

		$output    = $this->input->post('output');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
			
		$data['jumlah'] = $this->Kibf_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibf']   = $this->Kibf_model->laporan_kib($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/laporankibf',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/laporankibf',array_merge($data,$get_ttd));
		}
	}
	
	
	/* data laporan kib */
	function kib()
	{
		if(!$this->general->privilege_check('KIB',VIEW))
		    $this->general->no_access();
		$data['header'] 	= "CETAK LAPORAN KARTU INVENTARIS BARANG";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/kib',$data);
	}
	
	/* data laporan kir */
	function kir()
	{
		if(!$this->general->privilege_check('KIR',VIEW))
		    $this->general->no_access();
		$data['header'] 	= "CETAK LAPORAN KARTU INVENTARIS RUANG";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		
		$kd_prov	=  $this->session->userdata('kd_prov');
		$kd_kab		=  $this->session->userdata('kd_kab_kota');
		$kd_bidang	=  $this->session->userdata('addKd_Bidang');
		$kd_unit	=  $this->session->userdata('addKd_Unit');
		$kd_sub		=  $this->session->userdata('addKd_Sub');
		$kd_upb		=  $this->session->userdata('addKd_UPB');
		$tahun		=  $this->session->userdata('tahun_anggaran');
		
		/* $this->auth->cek_dataruang($kd_bidang,$kd_unit,$kd_sub,$kd_upb); */
		
		$data['option_ruang'] 	= $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
		
		$this->template->load('template','adminweb/laporan/kir',$data);
	}
	
	/* data laporan cetakkir */
	function cetakkir()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');

		$data['title'] 		= "KARTU INVENTARIS RUANG";

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal  = $this->input->post('tahunawal');
		$tahunakhir = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($tahunawal));
		}
		
		$tahun           = $this->session->userdata('tahun_anggaran');
		
		$ruang           = $this->input->post('kd_ruang');
		$perc            = $this->input->post('perc');
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if($perc != 'pilih') {
			$like = " AND (Tgl_Pembukuan BETWEEN '$tahunawal' AND '$tahunakhir') AND Kd_Ruang = '$ruang'";
			$data['periode'] = "TAHUN ANGGARAN \n ".$awal." s/d ".date('d M Y', strtotime($tahunakhir));
		}else{
			$like = " AND Kd_Ruang = '$ruang'";
			$data['periode'] = "";
		}

		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		$data['ta_ruang'] 		= $this->Ta_ruang_model->ruang_kir($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$ruang,$tahun)->row();
			
		$data['jumlah'] = $this->Kibb_model->laporan_kir($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kir']    = $this->Kibb_model->laporan_kir($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/laporankir',array_merge($data,$get_ttd));
	}
	
	/* data laporan kir */
	function manajemen()
	{
		$data['header'] 	= "CETAK LAPORAN";
		$data['title'] 		= $this->title;
		$data['form_cari']	= site_url('kiba');
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/manajemen',$data);
	}

	/* cetak laporan rekap mutasi */
	function rekap_mutasi()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$this->session->set_userdata('awal',date('d M Y', strtotime('-1 day', strtotime($tahunawal))));
		$this->session->set_userdata('akhir',date('d M Y', strtotime($this->input->post('tahunakhir'))));
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		// $data['totala']			= $this->Kiba_model->total_rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->row();
		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		// $data['totalb']			= $this->Kibb_model->total_rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->row();
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		// $data['totalc']			= $this->Kibc_model->total_rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->row();
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		// $data['totald']			= $this->Kibd_model->total_rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->row();
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		// $data['totale']			= $this->Kibe_model->total_rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->row();
		/* KIB F */
		$data['jumlahf'] 		= $this->Kibf_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		/* Aset Lainya */
		$data['jumlahl'] 		= $this->Lainnya_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir)->num_rows();
		$data['kibl'] 			= $this->Lainnya_model->rekap_mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir);
		
		$this->load->view('adminweb/laporan/inventaris/rekap_mutasi',array_merge($data,$get_ttd));
	}

	/* cetak laporan mutasi */
	function mutasi()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB B */
		$data['jumlah'] 		= $this->Kibb_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB F */
		$data['jumlahf'] 		= $this->Kibf_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* Aset Lainnya */
		$data['jumlahl'] 		= $this->Lainnya_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibl'] 			= $this->Lainnya_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);

		$this->load->view('adminweb/laporan/laporanmutasi',array_merge($data,$get_ttd));
	}

	function mutasi_rinci()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB B */
		$data['jumlah'] 		= $this->Kibb_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB F */
		$data['jumlahf'] 		= $this->Kibf_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* Aset Lainnya */
		$data['jumlahl'] 		= $this->Lainnya_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibl'] 			= $this->Lainnya_model->mutasi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);

		$this->load->view('adminweb/laporan/laporanmutasi',array_merge($data,$get_ttd));
	}
	
	
	/* data laporan buku induk */
	function bukuinduk()
	{
		$data['header'] 	= "CETAK LAPORAN BUKU INDUK";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/bukuinduk',$data);
	}
	
	
	/* cetak laporan rekap inventaris */
	function rinventaris()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal		= $this->input->post('tahunawal');
		$tahunakhir		= $this->input->post('tahunakhir');
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun			= date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']	=  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] 		= $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
		$cek_upb		 		= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->num_rows();
		if ($cek_upb > 0){
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row();
		}else{
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row();
		}
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->kiba_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->kiba_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totala']			= $this->Kiba_model->total_kiba_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->kibb_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->kibb_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalb']			= $this->Kibb_model->total_kibb_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->kibc_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->kibc_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalc']			= $this->Kibc_model->total_kibc_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->kibd_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->kibd_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totald']			= $this->Kibd_model->total_kibd_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->kibe_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->kibe_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totale']			= $this->Kibe_model->total_kibe_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB F */
		$data['jumlahf'] 		= $this->Kibf_model->kibf_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->kibf_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalf']			= $this->Kibf_model->total_kibf_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB ASET LAINYA */
		$data['jumlah_lainnya'] = $this->Lainnya_model->lainnya_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kib_lainnya'] 	= $this->Lainnya_model->lainnya_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_lainnya']	= $this->Lainnya_model->total_lainnya_rinventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* Hitung Total */
		//$data['total']			= $data['totala']->Harga + $data['totalb']->Total + $data['totalc']->Total + $data['totald']->Total + $data['totale']->Harga + $data['totalf']->Harga;
		$this->load->view('adminweb/laporan/laporaninrekapventaris',$data);
	}
	
	/* cetak laporan rekap perkodebarang */
	function rincian_barang()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->rincian_barang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->rincian_barang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->rincian_barang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->rincian_barang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->rincian_barang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB F */
		$data['kibf'] = $this->Kibf_model->rincian_barang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		/* KIB Lainya */
		// $data['non_operasioanl'] 	= $this->Lainnya_model->rekap_neraca_nonop($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2)->row();
		// $data['kib_lainnya'] 		= $this->Lainnya_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2)->row();
		
		$this->load->view('adminweb/laporan/inventaris/rincian_barang',array_merge($data,$get_ttd));
	}
		
	
	/* cetak laporan lampiran sk penghapusan */
	function lampiran_sk_hapus()
	{
		$data['title']    = $this->title;
		$kd_bidang        =  $this->input->post('kd_bidang');
		$kd_unit          =  $this->input->post('kd_unit');
		$kd_sub           =  $this->input->post('kd_sub_unit');
		$kd_upb           =  $this->input->post('kd_upb');
		
		$tahunawal        = $this->input->post('tahunawal');
		$tahunakhir       = $this->input->post('tahunakhir');
		$data['periode']  = date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun            = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']  =  $this->input->post('tanggal');
		
		$like             = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$cek_upb          = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		if ($cek_upb > 0)
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
		}
		else
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
		}
		
		$data['jumlah_skhapus_kiba'] 	= $this->Kiba_model->laporan_skhapus($like)->num_rows(); 
		$data['skhapus_kiba'] 			= $this->Kiba_model->laporan_skhapus($like); 
		$data['total_skhapus_kiba']		= $this->Kiba_model->total_skhapus($like);
		
		$data['jumlah_skhapus_kibb'] 	= $this->Kibb_model->laporan_skhapus($like)->num_rows(); 
		$data['skhapus_kibb'] 			= $this->Kibb_model->laporan_skhapus($like); 
		$data['total_skhapus_kibb']		= $this->Kibb_model->total_skhapus($like);
		
		$data['jumlah_skhapus_kibc'] 	= $this->Kibc_model->laporan_skhapus($like)->num_rows(); 
		$data['skhapus_kibc'] 			= $this->Kibc_model->laporan_skhapus($like); 
		$data['total_skhapus_kibc']		= $this->Kibc_model->total_skhapus($like);
		
		$data['jumlah_skhapus_kibd'] 	= $this->Kibd_model->laporan_skhapus($like)->num_rows(); 
		$data['skhapus_kibd'] 			= $this->Kibd_model->laporan_skhapus($like); 
		$data['total_skhapus_kibd']		= $this->Kibd_model->total_skhapus($like);
		
		$data['jumlah_skhapus_kibe'] 	= $this->Kibe_model->laporan_skhapus($like)->num_rows(); 
		$data['skhapus_kibe'] 			= $this->Kibe_model->laporan_skhapus($like); 
		$data['total_skhapus_kibe']		= $this->Kibe_model->total_skhapus($like);
		
		$data['total']			= $data['total_skhapus_kiba'] + $data['total_skhapus_kibb'] + $data['total_skhapus_kibc'] + $data['total_skhapus_kibd']
								  + $data['total_skhapus_kibe'];
		$this->load->view('adminweb/laporan/gabungan_pemda/sk_hapus',$data);
	}
	
	/* cetak laporan usulan penggunaan */
	function usul_guna()
	{
		
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal		= $this->input->post('tahunawal');
		$tahunakhir		= $this->input->post('tahunakhir');
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun			= date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']	=  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] 		= $this->Ref_upb_model->get_nama_upb(1,$kd_unit,$kd_sub,$kd_upb)->row();
		$cek_upb		 		= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->num_rows();
		if ($cek_upb > 0){
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row();
		}else{
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row();
		}
		
		$data['jumlah_usulguna_kiba'] 	= $this->Kiba_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['usulguna_kiba'] 			= $this->Kiba_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_usulguna_kiba']	= $this->Kiba_model->total_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		
		$data['jumlah_usulguna_kibb'] 	= $this->Kibb_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['usulguna_kibb'] 			= $this->Kibb_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_usulguna_kibb']	= $this->Kibb_model->total_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['jumlah_usulguna_kibc'] 	= $this->Kibc_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['usulguna_kibc'] 			= $this->Kibc_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_usulguna_kibc']	= $this->Kibc_model->total_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['jumlah_usulguna_kibd'] 	= $this->Kibd_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['usulguna_kibd'] 			= $this->Kibd_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_usulguna_kibd']	= $this->Kibd_model->total_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['jumlah_usulguna_kibe'] 	= $this->Kibe_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['usulguna_kibe'] 			= $this->Kibe_model->laporan_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_usulguna_kibe']	= $this->Kibe_model->total_usulguna($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		
		$data['total']			= $data['total_usulguna_kiba'] + $data['total_usulguna_kibb'] + $data['total_usulguna_kibc'] + $data['total_usulguna_kibd'] + $data['total_usulguna_kibe'];
		$this->load->view('adminweb/laporan/usul_guna',$data);
	}
	
	/* cetak laporan lampiran sk penggunaan */
	function lampiran_sk_guna()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal		= $this->input->post('tahunawal');
		$tahunakhir		= $this->input->post('tahunakhir');
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun			= date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']	=  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$cek_upb = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		if ($cek_upb > 0)
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
		}
		else
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
		}
		
		$data['jumlah_skguna_kiba'] 	= $this->Kiba_model->laporan_skguna($like)->num_rows(); 
		$data['skguna_kiba'] 			= $this->Kiba_model->laporan_skguna($like); 
		$data['total_skguna_kiba']		= $this->Kiba_model->total_skguna($like);
		
		$data['jumlah_skguna_kibb'] 	= $this->Kibb_model->laporan_skguna($like)->num_rows(); 
		$data['skguna_kibb'] 			= $this->Kibb_model->laporan_skguna($like); 
		$data['total_skguna_kibb']		= $this->Kibb_model->total_skguna($like);
		
		$data['jumlah_skguna_kibc'] 	= $this->Kibc_model->laporan_skguna($like)->num_rows(); 
		$data['skguna_kibc'] 			= $this->Kibc_model->laporan_skguna($like); 
		$data['total_skguna_kibc']		= $this->Kibc_model->total_skguna($like);
		
		$data['jumlah_skguna_kibd'] 	= $this->Kibd_model->laporan_skguna($like)->num_rows(); 
		$data['skguna_kibd'] 			= $this->Kibd_model->laporan_skguna($like); 
		$data['total_skguna_kibd']		= $this->Kibd_model->total_skguna($like);
		
		$data['jumlah_skguna_kibe'] 	= $this->Kibe_model->laporan_skguna($like)->num_rows(); 
		$data['skguna_kibe'] 			= $this->Kibe_model->laporan_skguna($like); 
		$data['total_skguna_kibe']		= $this->Kibe_model->total_skguna($like);
		
		$data['total']			= $data['total_skguna_kiba'] + $data['total_skguna_kibb']+$data['total_skguna_kibc']+$data['total_skguna_kibd']+$data['total_skguna_kibe'];
		$this->load->view('adminweb/laporan/gabungan_pemda/sk_guna',$data);
	}
	
	
	
	/* data laporan gabungan Pemda */
	function gabungan()
	{
		$this->auth->allow(01);
		$data['header'] 	= "CETAK LAPORAN GABUNGAN PEMDA";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/gabungan',$data);
	}
	
	/* cetak laporan buku induk inventaris */
	function bukuindukinventaris()
	{
		$data['title'] 		= $this->title;
		
		$tahunawal		= $this->input->post('tahunawal');
		$tahunakhir		= $this->input->post('tahunakhir');
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun			= date('Y', strtotime($this->input->post('tahunakhir')));
		$data['tanggal']	=  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->kiba_buku_induk($like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->kiba_buku_induk($like);
		$data['totala']			= $this->Kiba_model->total_kiba_buku_induk($like)->row();
		
		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->kibb_buku_induk($like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->kibb_buku_induk($like);
		$data['totalb']			= $this->Kibb_model->total_kibb_buku_induk($like)->row();
		
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->kibc_buku_induk($like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->kibc_buku_induk($like);
		$data['totalc']			= $this->Kibc_model->total_kibc_buku_induk($like)->row();
		
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->kibd_buku_induk($like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->kibd_buku_induk($like);
		$data['totald']			= $this->Kibd_model->total_kibd_buku_induk($like)->row();
		
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->kibe_buku_induk($like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->kibe_buku_induk($like);
		$data['totale']			= $this->Kibe_model->total_kibe_buku_induk($like)->row();
		
		/* KIB F */
		$data['jumlahf'] 		= $this->Kibf_model->kibf_buku_induk($like)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->kibf_buku_induk($like);
		$data['totalf']			= $this->Kibf_model->total_kibf_buku_induk($like)->row();
		
		/* Hitung Total */
		$data['jumlah']			= $data['totala']->Jumlah+$data['totalb']->Jumlah+$data['totalc']->Jumlah+
								  $data['totald']->Jumlah+$data['totale']->Jumlah+$data['totalf']->Jumlah;
		
		/* Hitung Total */
		$data['total']			= $data['totala']->Harga+$data['totalb']->Harga+$data['totalc']->Harga+
								  $data['totald']->Harga+$data['totale']->Harga+$data['totalf']->Harga;
			
		
		$this->load->view('adminweb/laporan/gabungan_pemda/lapbininvent',$data);
	}

	/* cetak laporan rekap semua */
	function rekap_semua()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR ASET KONDISI ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* start */
		$skpd 							= $this->Sub_unit_model->get_sub_unit($kd_bidang,$kd_unit);
		$i=0;
		$nomor=1;
		foreach ($skpd->result() as $line){

			$upb 							= $this->Ref_upb_model->get_upb($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'');
			
			$kd1 = sprintf ("%02u", $line->Kd_Bidang);
			$kd2 = sprintf ("%02u", $line->Kd_Unit);
			$kd3 = sprintf ("%02u", $line->Kd_Sub);
			$kode = $kd1.'.'.$kd2.'.'.$kd3;

			$jumlah_a = $this->Kiba_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_b = $this->Kibb_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_c = $this->Kibc_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_d = $this->Kibd_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_e = $this->Kibe_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_f = $this->Kibf_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_l = $this->Lainnya_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$total    = $jumlah_a['Harga'] + $jumlah_b['Harga'] + $jumlah_c['Harga'] + $jumlah_d['Harga'] + $jumlah_e['Harga'] + $jumlah_f['Harga'] + $jumlah_l['Harga'];

			$responce[$i]['cell'] 	= array($nomor,$kode,$line->Nm_Sub_Unit,rp($jumlah_a['Harga']),rp($jumlah_b['Harga']),rp($jumlah_c['Harga']),rp($jumlah_d['Harga']),rp($jumlah_e['Harga']),rp($jumlah_f['Harga']),rp($jumlah_l['Harga']),rp($total));

			if($upb->num_rows() > 0 ){

				foreach ($upb->result() as $row){
						$kd1 = sprintf ("%02u", $row->Kd_Bidang);
						$kd2 = sprintf ("%02u", $row->Kd_Unit);
						$kd3 = sprintf ("%02u", $row->Kd_Sub);
						$kd4 = sprintf ("%02u", $row->Kd_UPB);
						$kode = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4;

						$jumlah_a = $this->Kiba_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$jumlah_b = $this->Kibb_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$jumlah_c = $this->Kibc_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$jumlah_d = $this->Kibd_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$jumlah_e = $this->Kibe_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$jumlah_f = $this->Kibf_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$jumlah_l = $this->Lainnya_model->rekap_skpd($row->Kd_Bidang,$row->Kd_Unit,$row->Kd_Sub,$row->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
						$total    = $jumlah_a['Harga'] + $jumlah_b['Harga'] + $jumlah_c['Harga'] + $jumlah_d['Harga'] + $jumlah_e['Harga'] + $jumlah_f['Harga'] + $jumlah_l['Harga'];

						$responce[++$i]['cell'] = array("",$kode,"    ".$row->Nm_UPB,rp($jumlah_a['Harga']),rp($jumlah_b['Harga']),rp($jumlah_c['Harga']),rp($jumlah_d['Harga']),rp($jumlah_e['Harga']),rp($jumlah_f['Harga']),rp($jumlah_l['Harga']),rp($total));
				}

			}		

			$i++;
			$nomor++;
		}
		$data['jumlah'] = $i;
		$data['response'] = $responce;
	
		/* end */
		$this->load->view('adminweb/laporan/rekap/rekap_semua',array_merge($data,$get_ttd));
	}
	
	/* cetak laporan rekap gabungan */
	function rekap_skpd()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR ASET KONDISI ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* start */
		$skpd 							= $this->Sub_unit_model->get_unit($kd_bidang,$kd_unit);
		$data['jumlah'] 				= $this->Sub_unit_model->get_unit($kd_bidang,$kd_unit)->num_rows();
		$i=0;
		$nomor=1;
		foreach ($skpd->result() as $line){
			$jumlah_a 				= $this->Kiba_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_b 				= $this->Kibb_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_c 				= $this->Kibc_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_d 				= $this->Kibd_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_e 				= $this->Kibe_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_f 				= $this->Kibf_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_l 				= $this->Lainnya_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,'','',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$responce[$i]['cell'] 	= array($nomor,$line->Kd_Bidang,$line->Kd_Unit,'',$line->Nm_Unit,$jumlah_a['Harga'],$jumlah_b['Harga'],$jumlah_c['Harga'],$jumlah_d['Harga'],$jumlah_e['Harga'],$jumlah_f['Harga'],$jumlah_l['Harga']);
			$i++;
			$nomor++;
		}
		$data['response'] = $responce;
	
		/* end */
		$this->load->view('adminweb/laporan/rekap/rekap_skpd',array_merge($data,$get_ttd));
	}


	/* cetak laporan rekap gabungan */
	function rekap_upb()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR ASET KONDISI ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* start */
		// $upb 							= $this->Ref_upb_model->upb($kd_bidang,$kd_unit,$kd_sub,'');
		// $data['jumlah'] 				= $this->Ref_upb_model->upb($kd_bidang,$kd_unit,$kd_sub,'')->num_rows();

		$upb 							= $this->Ref_upb_model->get_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['jumlah'] 				= $this->Ref_upb_model->get_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->num_rows();

		$i=0;
		$nomor=1;
		foreach ($upb->result() as $line){
			$jumlah_a 				= $this->Kiba_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_b 				= $this->Kibb_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_c 				= $this->Kibc_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_d 				= $this->Kibd_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_e 				= $this->Kibe_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_f 				= $this->Kibf_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Kd_UPB,$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$jumlah_l 				= $this->Lainnya_model->rekap_skpd($line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,'',$tahunawal,$tahunakhir,$like,$kondisi)->row_array();
			$responce[$i]['cell'] 	= array($nomor,$line->Kd_Bidang,$line->Kd_Unit,$line->Kd_Sub,$line->Nm_UPB,$jumlah_a['Harga'],$jumlah_b['Harga'],$jumlah_c['Harga'],$jumlah_d['Harga'],$jumlah_e['Harga'],$jumlah_f['Harga'],$jumlah_l['Harga']);
			$i++;
			$nomor++;
		
		}
		$data['response'] = $responce;
	
		/* end */
		$this->load->view('adminweb/laporan/rekap/rekap_upb',array_merge($data,$get_ttd));
	}
	
	 /* cetak laporan buku induk Kib A induk */
    function kibainduk(){
	$this->auth->restrict(); 
	$data['title'] = $this->title; 
	
	$this->auth->cek_indukKibA();
	$tahunawal		= $this->input->post('tahunawal');
	$tahunakhir		= $this->input->post('tahunakhir');
	$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
	$tahun 		= date('Y', strtotime($this->input->post('tahunakhir'))); 
	$data['tanggal'] = $this->input->post('tanggal'); 
	
	$like 		= "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
	$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row(); 
	$cek_upb 	= $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
        
	if ($cek_upb > 0)
	{
		$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
	}
	else
	{ 
		$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
	} 
		$data['total']  = $this->Kiba_model->total_laporan_all_kiba($like);
		$data['jumlah'] = $this->Kiba_model->laporan_all_kiba($like)->num_rows(); 
		$data['kiba']   = $this->Kiba_model->laporan_all_kiba($like); 
		$this->load->view('adminweb/laporan/gabungan_pemda/laporankiba_unit',$data); 

    }
/* cetak laporan buku induk Kib B induk */
 function kibbinduk(){
     $this->auth->restrict();
     $data['title'] = $this->title;
     $this->auth->cek_indukKibB();
	
     $tahunawal 	= $this->input->post('tahunawal');
     $tahunakhir 	= $this->input->post('tahunakhir');
	 $data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
     $tahun 		= date('Y', strtotime($this->input->post('tahunakhir')));
     $data['tanggal'] = $this->input->post('tanggal');
     $like 			= "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
	
     $data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
    
     $cek_upb = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
     
     if ($cek_upb > 0)
     {
         $data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
     }
     else
     {
         $data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
     }
     
     $data['total'] 	= $this->Kibb_model->total_laporan_all_kibb($like);
     $data['jumlah'] 	= $this->Kibb_model->laporan_all_kibb($like)->num_rows();
     $data['kibb'] 		= $this->Kibb_model->laporan_all_kibb($like); 
     $this->load->view('adminweb/laporan/gabungan_pemda/laporankibb_unit',$data);
}

    /* cetak laporan buku induk Kib C induk */
    function kibcinduk()
    {
        $this->auth->restrict(); 
		$data['title'] = $this->title;
        $this->auth->cek_indukKibC();
	
        $tahunawal = $this->input->post('tahunawal'); 
		$tahunakhir = $this->input->post('tahunakhir'); 
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun = date('Y', strtotime($this->input->post('tahunakhir'))); 
		$data['tanggal'] = $this->input->post('tanggal'); 
		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row(); 
		
		$cek_upb = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		if ($cek_upb > 0)
		{
			$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
		}
		else
		{ 
			$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
		} 
		 $data['total'] 	= $this->Kibc_model->total_laporan_all_kibc($like);
		 $data['jumlah'] 	= $this->Kibc_model->laporan_all_kibc($like)->num_rows();
		 $data['kibc'] 		= $this->Kibc_model->laporan_all_kibc($like); 
		 $this->load->view('adminweb/laporan/gabungan_pemda/laporankibc_unit',$data);
    }

    /* cetak laporan buku induk Kib D induk */
    function kibdinduk()
    {
        $this->auth->restrict();
        $data['title'] = $this->title;
        $this->auth->cek_indukKibD();
	
        $tahunawal = $this->input->post('tahunawal'); 
		$tahunakhir = $this->input->post('tahunakhir'); 
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun = date('Y', strtotime($this->input->post('tahunakhir'))); 
		$data['tanggal'] = $this->input->post('tanggal'); 
		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row(); 
		
		$cek_upb = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		if ($cek_upb > 0)
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
		}
		else
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
		} 
		
		$data['total'] 	= $this->Kibd_model->total_laporan_all_kibd($like);
		$data['jumlah'] = $this->Kibd_model->laporan_all_kibd($like)->num_rows(); 
		$data['kibd'] 	= $this->Kibd_model->laporan_all_kibd($like); 
		$this->load->view('adminweb/laporan/gabungan_pemda/laporankibd_unit',$data); 

}

    /* cetak laporan buku induk Kib E induk */
    function kibeinduk()
    {
        $this->auth->restrict(); 
		$data['title'] = $this->title;
        $this->auth->cek_indukKibD();
	
        $tahunawal = $this->input->post('tahunawal'); 
		$tahunakhir = $this->input->post('tahunakhir'); 
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun = date('Y', strtotime($this->input->post('tahunakhir'))); 
		$data['tanggal'] = $this->input->post('tanggal'); 
	
        $like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
		/*$like = "Alamat like '%Jl. Stadion%'";*/ 
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$cek_upb = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		if ($cek_upb > 0)
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
		}
		else
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
		}
		$data['total'] 	= $this->Kibe_model->total_laporan_all_kibe($like)->row();
		$data['jumlah'] = $this->Kibe_model->laporan_all_kibe($like)->num_rows(); 
		$data['kibe'] 	= $this->Kibe_model->laporan_all_kibe($like); 
		$this->load->view('adminweb/laporan/gabungan_pemda/laporankibe_unit',$data); 
    }
    
    /* cetak laporan buku induk Kib F induk */
    function kibfinduk()
    {
        $this->auth->restrict(); 
		$data['title'] = $this->title;
        $this->auth->cek_indukKibF();
	
        $tahunawal = $this->input->post('tahunawal'); 
		$tahunakhir = $this->input->post('tahunakhir'); 
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun = date('Y', strtotime($this->input->post('tahunakhir'))); 
		$data['tanggal'] = $this->input->post('tanggal'); 
		
		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$cek_upb = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		if ($cek_upb > 0)
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($tahun)->row(); 
		}
		else
		{
				$data['ta_upb'] = $this->Ta_upb_model->get_all_data_umum($this->session->userdata('tahun_anggaran'))->row(); 
		}
			
		$data['total']  = $this->Kibf_model->total_laporan_all_kibf($like);
		$data['jumlah'] = $this->Kibf_model->laporan_all_kibf($like)->num_rows(); 
		$data['kibf']   = $this->Kibf_model->laporan_all_kibf($like); 
		$this->load->view('adminweb/laporan/gabungan_pemda/laporankibf_unit',$data); 
    }
	
	function rekapmutasiinduk()
    {
       $this->auth->restrict(); 
		$data['title'] = $this->title; 
		 
		$tahunawal = $this->input->post('tahunawal'); 
		$tahunakhir = $this->input->post('tahunakhir');
		 
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode']  = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun            = date('Y', strtotime($this->input->post('tahunakhir'))); 
		$data['tanggal']  = $this->input->post('tanggal'); 
		$bulan            = array("January","Pebruary","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember"); 
		$day              = date("d")-0; $month = date("m")-1; $bulan = $bulan[$month]; $thn = date("Y"); 
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$this->session->set_userdata('awal',date('d M Y', strtotime('-1 day', strtotime($tahunawal)))); 
		$this->session->set_userdata('akhir',date('d M Y', strtotime($this->input->post('tahunakhir')))); 
		$like             = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
	
	/* KIB A */ 
        $data['jumlaha'] = $this->Kiba_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->num_rows();
        $data['kiba'] = $this->Kiba_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir);
        $data['totala'] = $this->Kiba_model->total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->row();

        /* KIB B */ 
        $data['jumlahb'] = $this->Kibb_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->num_rows();
        $data['kibb'] = $this->Kibb_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir);
        $data['totalb'] = $this->Kibb_model->total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->row(); 
	   
	/* KIB C */ 

        $data['jumlahc'] = $this->Kibc_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->num_rows();
        $data['kibc'] = $this->Kibc_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir);
        $data['totalc'] = $this->Kibc_model->total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->row();
        
        /* KIB D */
        $data['jumlahd'] = $this->Kibd_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->num_rows();
        $data['kibd'] = $this->Kibd_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir);
        $data['totald'] = $this->Kibd_model->total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->row();
	  
	/* KIB E */
        $data['jumlahe'] = $this->Kibe_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->num_rows();
        $data['kibe'] = $this->Kibe_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir);
        $data['totale'] = $this->Kibe_model->total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->row();
	   
	/* KIB F */
        $data['jumlahf'] = $this->Kibf_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->num_rows();
        $data['kibf'] = $this->Kibf_model->rekap_mutasi_induk($like,$tahunawal,$tahunakhir);
        $data['totalf'] = $this->Kibf_model->total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir)->row();
		    
		
		/* Hitung Total awal*/
        $data['jumhargaawal'] = $data['totala']->Jumlah_awal + $data['totalb']->Jumlah_awal + $data['totalc']->Jumlah_awal +
                                $data['totald']->Jumlah_awal + $data['totale']->Jumlah_awal + $data['totalf']->Jumlah_awal;
	 
        /* Hitung harga total awal*/
        $data['tothargaawal'] = $data['totala']->Harga_awal + $data['totalb']->Harga_awal + $data['totalc']->Harga_awal +
                                $data['totald']->Harga_awal + $data['totale']->Harga_awal + $data['totalf']->Harga_awal;
	  
		/* Hitung Total tambah*/
        $data['jumhargatambah'] = $data['totala']->Jumlah_tambah + $data['totalb']->Jumlah_tambah + $data['totalc']->Jumlah_tambah +
								  $data['totald']->Jumlah_tambah + $data['totale']->Jumlah_tambah + $data['totalf']->Jumlah_tambah;
        
        /* Hitung harga total tambah*/
        $data['tothargatambah'] = $data['totala']->Harga_tambah + $data['totalb']->Harga_tambah +
                                  $data['totalc']->Harga_tambah + $data['totald']->Harga_tambah + $data['totale']->Harga_tambah + $data['totalf']->Harga_tambah;
        
        /* Hitung Total akhir*/
        $data['jumhargaakhir'] = $data['totala']->Jumlah + $data['totalb']->Jumlah + $data['totalc']->Jumlah + $data['totald']->Jumlah +
                                 $data['totale']->Jumlah + $data['totalf']->Jumlah;
        
        /* Hitung harga total akhir*/
        $data['tothargaakhir'] = $data['totala']->Harga + $data['totalb']->Harga + $data['totalc']->Harga + $data['totald']->Harga +
                                 $data['totale']->Harga + $data['totalf']->Harga;
        /* Hitung Total */  		
		$this->load->view('adminweb/laporan/gabungan_pemda/laporanrekapmutasi',$data);
    }
	
	/* cetak laporan mutasi */ 
    function rekapmutasibaranginduk() 
    { 
        $this->auth->restrict();
        $data['title'] = $this->title;
		
		
        $tahunawal = $this->input->post('tahunawal'); 
        $tahunakhir = $this->input->post('tahunakhir'); 
        if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir'))); 		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		
        $tahun = date('Y', strtotime($this->input->post('tahunakhir'))); $data['tanggal'] = $this->input->post('tanggal'); 
        $like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
        
        /* KIB A */ 
        $data['jumlaha'] = $this->Kiba_model->kiba_mutasi_barang($like)->num_rows(); 
        $data['kiba'] = $this->Kiba_model->kiba_mutasi_barang($like); 
        $data['totala'] = $this->Kiba_model->total_kiba_mutasi_barang($like)->row(); 
        
        /* KIB B */ 
        $data['jumlah'] = $this->Kibb_model->kibb_mutasi_barang($like)->num_rows(); 
        $data['kibb'] = $this->Kibb_model->kibb_mutasi_barang($like);
        $data['totalb'] = $this->Kibb_model->total_kibb_mutasi_barang($like)->row();  
        
        /* KIB C */ 
        $data['jumlahc'] = $this->Kibc_model->kibc_mutasi_barang($like)->num_rows(); 
        $data['kibc'] = $this->Kibc_model->kibc_mutasi_barang($like); 
        $data['totalc'] = $this->Kibc_model->total_kibc_mutasi_barang($like)->row(); 
        
        /* KIB D */
        $data['jumlahd'] = $this->Kibd_model->kibd_mutasi_barang($like)->num_rows();
        $data['kibd'] = $this->Kibd_model->kibd_mutasi_barang($like); 
        $data['totald'] = $this->Kibd_model->total_kibd_mutasi_barang($like)->row();  
        
        /* KIB E */ 
        $data['jumlahe'] = $this->Kibe_model->kibe_mutasi_barang($like)->num_rows();
        $data['kibe'] = $this->Kibe_model->kibe_mutasi_barang($like); 
        $data['totale'] = $this->Kibe_model->total_kibe_mutasi_barang($like)->row();  
        
        /* KIB F */ 
        $data['jumlahf'] = $this->Kibf_model->kibf_mutasi_barang($like)->num_rows(); 
        $data['kibf'] = $this->Kibf_model->kibf_mutasi_barang($like); 
        $data['totalf'] = $this->Kibf_model->total_kibf_mutasi_barang($like)->row();  
       
	     /* Hitung Total akhir*/
        $data['jumlah'] = $data['totala']->Jumlah + $data['totalb']->Jumlah + $data['totalc']->Jumlah + $data['totald']->Jumlah +
                                 $data['totale']->Jumlah + $data['totalf']->Jumlah;
        
        /* Hitung harga total akhir*/
        $data['total'] = $data['totala']->Harga + $data['totalb']->Harga + $data['totalc']->Harga + $data['totald']->Harga +
                                 $data['totale']->Harga + $data['totalf']->Harga;
								 
        $this->load->view('adminweb/laporan/gabungan_pemda/laporanmutasi',$data); 
        
    }
	
	 /* cetak laporan rekap inventaris */ 
    function rinventarisinduk() 
    {
        $this->auth->restrict();
		$data['title']    = $this->title;
		$tahunawal        = $this->input->post('tahunawal');
		$tahunakhir       = $this->input->post('tahunakhir');
		$data['periode']  = date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$tahun            = date('Y', strtotime($this->input->post('tahunakhir')));
		$data['tanggal']  = $this->input->post('tanggal');
		$like             = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'"; 
 	 
		/* KIB A */
		$data['jumlaha'] = $this->Kiba_model->kiba_rinventaris_rekap($like)->num_rows(); 
		$data['kiba']    = $this->Kiba_model->kiba_rinventaris_rekap($like); 
		$data['totala']  = $this->Kiba_model->total_kiba_rinventaris_rekap($like)->row();
		
		/* KIB B */
		$data['jumlahb'] = $this->Kibb_model->kibb_rinventaris_rekap($like)->num_rows(); 
		$data['kibb']    = $this->Kibb_model->kibb_rinventaris_rekap($like);
		$data['totalb']  = $this->Kibb_model->total_kibb_rinventaris_rekap($like)->row();
		
		/* KIB C */
		$data['jumlahc'] = $this->Kibc_model->kibc_rinventaris_rekap($like)->num_rows();
		$data['kibc']    = $this->Kibc_model->kibc_rinventaris_rekap($like);
		$data['totalc']  = $this->Kibc_model->total_kibc_rinventaris_rekap($like)->row();
		
		/* KIB D */
		$data['jumlahd'] = $this->Kibd_model->kibd_rinventaris_rekap($like)->num_rows();
		$data['kibd']    = $this->Kibd_model->kibd_rinventaris_rekap($like);
		$data['totald']  = $this->Kibd_model->total_kibd_rinventaris_rekap($like)->row();
		
		/* KIB E */ 
		$data['jumlahe'] = $this->Kibe_model->kibe_rinventaris_rekap($like)->num_rows();
		$data['kibe']    = $this->Kibe_model->kibe_rinventaris_rekap($like);
		$data['totale']  = $this->Kibe_model->total_kibe_rinventaris_rekap($like)->row();
		
		/* KIB F */
		$data['jumlahf'] = $this->Kibf_model->kibf_rinventaris_rekap($like)->num_rows();
		$data['kibf']    = $this->Kibf_model->kibf_rinventaris_rekap($like);
		$data['totalf']  = $this->Kibf_model->total_kibf_rinventaris_rekap($like)->row();
        
        
        /* Hitung Total */
        $data['total'] = $data['totala']->Harga + $data['totalb']->Harga + $data['totalc']->Harga + $data['totald']->Harga + $data['totale']->Harga + $data['totalf']->Harga;
        $this->load->view('adminweb/laporan/gabungan_pemda/laporanrekapinventaris',$data); 
} 

	/* 13-05-2015 Kendaraan_induk */
	function kendaraan_induk(){
		$data['title']    = $this->title;
		$kd_bidang        =  $this->input->post('kd_bidang');
		$kd_unit          =  $this->input->post('kd_unit');
		$kd_sub           =  $this->input->post('kd_sub_unit');
		$kd_upb           =  $this->input->post('kd_upb');
		
		$tahunawal        = $this->input->post('tahunawal');
		$tahunakhir       = $this->input->post('tahunakhir');
		$data['periode']  = date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun            = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']  =  $this->input->post('tanggal');
		
		$like             = "AND Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$cek_upb          = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		$data['jumlah_kendaraan'] = $this->Kibb_model->laporan_kendaraan('','','','',$like)->num_rows();
		$data['kendaraan']        = $this->Kibb_model->laporan_kendaraan('','','','',$like);
		$data['count']            = $this->Kibb_model->total_kendaraan('','','','',$like)->row();
		$this->load->view('adminweb/laporan/gabungan_pemda/kendaraan',$data);
	}

	/* 13-05-2015 daftar Kendaraan */
	function daftar_kendaraan(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR KENDARAAN ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->daftar_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->daftar_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
	
		$this->load->view('adminweb/laporan/inventaris/daftar_kendaraan',array_merge($data,$get_ttd));
	}

	function rekap_kendaraan(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR KENDARAAN ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B KENDARAAN RODA 2 */
		$data['jumlahkendaraan_roda_2'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi)->num_rows();
		$data['kendaraan_roda_2']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi);
		/* KIB B KENDARAAN RODA 3 */
		$data['jumlahkendaraan_roda_3'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,3,$kondisi)->num_rows();
		$data['kendaraan_roda_3']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,3,$kondisi);
		/* KIB B KENDARAAN RODA 4 */
		$data['jumlahkendaraan_roda_4'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,4,$kondisi)->num_rows();
		$data['kendaraan_roda_4']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,4,$kondisi);
		/* KIB B KENDARAAN RODA 6 */
		$data['jumlahkendaraan_roda_6'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,6,$kondisi)->num_rows();
		$data['kendaraan_roda_6']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,6,$kondisi);
		/* KIB B KENDARAAN RODA 8 */
		$data['jumlahkendaraan_roda_8'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,8,$kondisi)->num_rows();
		$data['kendaraan_roda_8']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,8,$kondisi);
		/* KIB B KENDARAAN RODA 10 */
		$data['jumlahkendaraan_roda_10'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,10,$kondisi)->num_rows();
		$data['kendaraan_roda_10']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,10,$kondisi);

		$this->load->view('adminweb/laporan/inventaris/rekap_kendaraan',array_merge($data,$get_ttd));
	}

	/* cetak laporan rekap kendaraan */
	function rkendaraan()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal		= $this->input->post('tahunawal');
		$tahunakhir		= $this->input->post('tahunakhir');
		$data['periode']		= date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun			= date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']	=  $this->input->post('tanggal');

		$like = "AND Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] 		= $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
		$cek_upb		 		= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->num_rows();
		if ($cek_upb > 0){
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row();
		}else{
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row();
		}
		
		/* KIB B KENDARAAN RODA 2 */
		$data['jumlahkendaraan_roda_2'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->num_rows();
		$data['kendaraan_roda_2']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2);
		$data['totalkendaraan_roda_2']  = $this->Kibb_model->total_rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->row();
		/* KIB B KENDARAAN RODA 3 */
		$data['jumlahkendaraan_roda_3'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,3)->num_rows();
		$data['kendaraan_roda_3']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,3);
		$data['totalkendaraan_roda_3']  = $this->Kibb_model->total_rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,3)->row();
		/* KIB B KENDARAAN RODA 4 */
		$data['jumlahkendaraan_roda_4'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,4)->num_rows();
		$data['kendaraan_roda_4']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,4);
		$data['totalkendaraan_roda_4']  = $this->Kibb_model->total_rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,4)->row();
		/* KIB B KENDARAAN RODA 6 */
		$data['jumlahkendaraan_roda_6'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,6)->num_rows();
		$data['kendaraan_roda_6']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,6);
		$data['totalkendaraan_roda_6']  = $this->Kibb_model->total_rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,6)->row();
		/* KIB B KENDARAAN RODA 8 */
		$data['jumlahkendaraan_roda_8'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,8)->num_rows();
		$data['kendaraan_roda_8']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,8);
		$data['totalkendaraan_roda_8']  = $this->Kibb_model->total_rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,8)->row();
		/* KIB B KENDARAAN RODA 10 */
		$data['jumlahkendaraan_roda_10'] = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,10)->num_rows();
		$data['kendaraan_roda_10']       = $this->Kibb_model->rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,10);
		$data['totalkendaraan_roda_10']  = $this->Kibb_model->total_rekap_kendaraan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,10)->row();

		$this->load->view('adminweb/laporan/laporanrekapkendaraan',$data);
	}

	/* cetak laporan rekap penyusutan_induk */
	function penyusutan_induk()
	{
		$data['title']    = $this->title;
		$kd_bidang        =  $this->input->post('kd_bidang');
		$kd_unit          =  $this->input->post('kd_unit');
		$kd_sub           =  $this->input->post('kd_sub_unit');
		$kd_upb           =  $this->input->post('kd_upb');
		
		$tahunawal        = $this->input->post('tahunawal');
		$tahunakhir       = $this->input->post('tahunakhir');
		$data['periode']  = date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun            = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal']  =  $this->input->post('tanggal');
		
		$like             = "AND Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_all_upb()->row();
		$cek_upb          = $this->Ta_upb_model->get_all_data_umum($tahun)->num_rows();
		
		$data['jumlah_penyusutan']      = $this->Kibb_model->laporan_penyusutan('','','','',$like)->num_rows();
		$data['penyusutan']             = $this->Kibb_model->laporan_penyusutan('','','','',$like);
		
		$data['jumlah_penyusutan_kibc'] = $this->Kibc_model->laporan_penyusutan_kibc('','','','',$like)->num_rows();
		$data['penyusutan_kibc']        = $this->Kibc_model->laporan_penyusutan_kibc('','','','',$like);
		
		$data['jumlah_penyusutan_kibd'] = $this->Kibd_model->laporan_penyusutan_kibd('','','','',$like)->num_rows();
		$data['penyusutan_kibd']        = $this->Kibd_model->laporan_penyusutan_kibd('','','','',$like);
		
		$this->load->view('adminweb/laporan/gabungan_pemda/penyusutan',$data);
	}

	/* 13-05-2015 penyusutan */
	function penyusutan(){
		$data['title'] = $this->title;
		$kd_bidang     =  $this->input->post('kd_bidang');
		$kd_unit       =  $this->input->post('kd_unit');
		$kd_sub        =  $this->input->post('kd_sub_unit');
		$kd_upb        =  $this->input->post('kd_upb');
		
		$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "AND Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
		$cek_upb          = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->num_rows();
		if ($cek_upb > 0){
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row();
		}else{
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row();
		}
		
		$data['jumlah_penyusutan']      = $this->Kibb_model->laporan_penyusutan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['penyusutan']             = $this->Kibb_model->laporan_penyusutan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		
		$data['jumlah_penyusutan_kibc'] = $this->Kibc_model->laporan_penyusutan_kibc($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['penyusutan_kibc']        = $this->Kibc_model->laporan_penyusutan_kibc($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		
		$data['jumlah_penyusutan_kibd'] = $this->Kibd_model->laporan_penyusutan_kibd($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['penyusutan_kibd']        = $this->Kibd_model->laporan_penyusutan_kibd($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$this->load->view('adminweb/laporan/penyusutan',$data);
	}
	/*end 2015*/


	/* data laporan buku induk */
	function akuntansi()
	{
		if(!$this->general->privilege_check('LAPORAN_AKUNTANSI',VIEW))
		    $this->general->no_access();
		
		$data['header'] 	= "CETAK LAPORAN AKUNTANSI";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/akuntansi/data',$data);
	}


	/* cetak laporan neraca */
	function neraca()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND !empty($kd_upb)){
			$data['nama_upb'] 	= $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();
		}else{
			$data['nama_upb']	= array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
		}

		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND !empty($kd_upb)){
			$data['ttd_pengurus'] = TRUE;
		}else{
			$data['ttd_pengurus'] = FALSE;
		}

		$data['ta_upb'] = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row();


		/* KIB A */
		$data['jumlah_kiba'] 	= $this->Kiba_model->neraca_kiba($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->neraca_kiba($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totala']			= $this->Kiba_model->total_neraca_kiba($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();

		/* KIB B */
		$data['jumlah_kibb'] 	= $this->Kibb_model->neraca_kibb($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->neraca_kibb($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalb']			= $this->Kibb_model->total_neraca_kibb($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();

		/* KIB C */
		$data['jumlah_kibc'] 	= $this->Kibc_model->neraca_kibc($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->neraca_kibc($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalc']			= $this->Kibc_model->total_neraca_kibc($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();

		/* KIB D */
		$data['jumlah_kibd'] 	= $this->Kibd_model->neraca_kibd($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->neraca_kibd($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totald']			= $this->Kibd_model->total_neraca_kibd($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();

		/* KIB E */
		$data['jumlah_kibe'] 	= $this->Kibe_model->neraca_kibe($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->neraca_kibe($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totale']			= $this->Kibe_model->total_neraca_kibe($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();

		/* KIB F */
		$data['jumlah_kibf'] 	= $this->Kibf_model->neraca_kibf($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->neraca_kibf($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalf']			= $this->Kibf_model->total_neraca_kibf($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
				
		/* Hitung Total */
        $data['total'] = 123;	
		
		$this->load->view('adminweb/laporan/akuntansi/neraca',$data);
	}


	/* cetak laporan rekap_neraca */
	function rekap_neraca()
	{
		$data['title'] = $this->title;
		$kd_bidang     = $this->input->post('kd_bidang');
		$kd_unit       = $this->input->post('kd_unit');
		$kd_sub        = $this->input->post('kd_sub_unit');
		$kd_upb        = $this->input->post('kd_upb');
		
		$output        = $this->input->post('output');
		
		$tahunawal     = $this->input->post('tahunawal');
		$tahunakhir    = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB F */
		$data['kibf'] = $this->Kibf_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		/* KIB Lainya */
		$data['non_operasioanl'] 	= $this->Lainnya_model->rekap_neraca_nonop($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2)->row();
		$data['kib_lainnya'] 		= $this->Lainnya_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2)->row();

		if($output == "xls"){
			$this->load->view('adminweb/laporan/akuntansi/excel/rekap_neraca',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/akuntansi/rekap_neraca',array_merge($data,$get_ttd));
		}
	}



	/* cetak laporan rincian_neraca */
	function rincian_neraca()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->rincian_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->rincian_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->rincian_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->rincian_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->rincian_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB F */
		$data['kibf'] = $this->Kibf_model->rincian_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		/* KIB Lainya */
		$data['non_operasioanl'] 	= $this->Lainnya_model->rekap_neraca_nonop($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2)->row();
		$data['kib_lainnya'] 		= $this->Lainnya_model->rekap_neraca($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2)->row();
		
		$this->load->view('adminweb/laporan/akuntansi/rincian_neraca',array_merge($data,$get_ttd));
	}

	/* cetak laporan rincian_neraca */
	function rincian_susut()
	{
		$data['title'] = $this->title;
		$kd_bidang     =  $this->input->post('kd_bidang');
		$kd_unit       =  $this->input->post('kd_unit');
		$kd_sub        =  $this->input->post('kd_sub_unit');
		$kd_upb        =  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal     = $this->input->post('tahunawal');
		$tahunakhir    = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB F */
		$data['kibf'] = $this->Kibf_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		/* KIB Lainya */
		$data['kib_lainnya'] = $this->Lainnya_model->rincian_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		$this->load->view('adminweb/laporan/akuntansi/rincian_susut',array_merge($data,$get_ttd));
	}


	/* data laporan Inventarisasi */
	function inventarisasi()
	{
		if(!$this->general->privilege_check('LAPORAN_INVENTARIS',VIEW))
		    $this->general->no_access();

		$data['header'] 	= "LAPORAN INVENTARIS";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/inventaris/data',$data);
	}

	/* cetak laporan laporankondisi */
	function laporankondisi()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$output    = $this->input->post('output');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR ASET KONDISI ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlahb']     = $this->Kibb_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibb']        = $this->Kibb_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		/* KIB C */
		$data['jumlahc']     = $this->Kibc_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibc']        = $this->Kibc_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		/* KIB D */
		$data['jumlahd']     = $this->Kibd_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibd']        = $this->Kibd_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		/* KIB E */
		$data['jumlahe']     = $this->Kibe_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibe']        = $this->Kibe_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		// /* KIB LAINYA */
		// $data['jumlahl']     = $this->Lainnya_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		// $data['kib_lainnya'] = $this->Lainnya_model->kib_kondisi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		
		if($output == "xls"){
			$this->load->view('adminweb/laporan/excel/inventaris/kondisi',array_merge($data,$get_ttd));
		}else{
			$this->load->view('adminweb/laporan/inventaris/kondisi',array_merge($data,$get_ttd));
		}
	}


	/* cetak laporan asetnonoperasional */
	function asetnonoperasional()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 	 = "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);

		/* KIB B */
		$data['jumlah'] 		= $this->Kibb_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB L */
		$data['jumlahl'] 		= $this->Lainnya_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibl'] 			= $this->Lainnya_model->non_operasional($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/inventaris/non_operasional',array_merge($data,$get_ttd));
	}

	/* cetak laporan asettidakberwujud */
	function asettidakberwujud()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND Kondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND Kondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND Kondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND Kondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR ASET TIDAK BERWUJUD ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlah'] 		= $this->Lainnya_model->kib_tdk_berwujud($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,$kondisi)->num_rows();
		$data['kib'] 			= $this->Lainnya_model->kib_tdk_berwujud($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,$kondisi);

		$this->load->view('adminweb/laporan/inventaris/tidak_berwujud',array_merge($data,$get_ttd));
	}

	/* cetak laporan rekap inventaris */
	function rekap_inventaris()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND Kondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB A */
		$data['jumlaha'] = $this->Kiba_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kiba']    = $this->Kiba_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		/* KIB B */
		$data['jumlahb'] = $this->Kibb_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like.$kond)->num_rows();
		$data['kibb']    = $this->Kibb_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like.$kond);
		/* KIB C */
		$data['jumlahc'] = $this->Kibc_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like.$kond)->num_rows();
		$data['kibc']    = $this->Kibc_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like.$kond);
		/* KIB D */
		$data['jumlahd'] = $this->Kibd_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibd']    = $this->Kibd_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB E */
		$data['jumlahe'] = $this->Kibe_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibe']    = $this->Kibe_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		/* KIB F */
		$data['jumlahf'] = $this->Kibf_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibf']    = $this->Kibf_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB ASET LAINYA */
		$data['jumlahl'] = $this->Lainnya_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibl']    = $this->Lainnya_model->rekap_inventaris($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/inventaris/rekap_inventaris',array_merge($data,$get_ttd));
	}


	/* cetak laporan buku inventaris */
	function bi()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 	 = "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);

		/* KIB B */
		$data['jumlah'] 		= $this->Kibb_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB F */
		$data['jumlahf'] 		= $this->Kibf_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibf'] 			= $this->Kibf_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		/* KIB F */
		$data['jumlahl'] 		= $this->Lainnya_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kibl'] 			= $this->Lainnya_model->bi($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/inventaris/buku_inventaris',array_merge($data,$get_ttd));
	}


	/* cetak laporan ekstrakomptabel */
	function ekstra()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		$data['title'] 		= "DAFTAR BARANG EKSTRAKOMPTABEL ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi);
		// $data['totalb']			= $this->Kibb_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,1,$kondisi)->row();
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi);
		// $data['totalc']			= $this->Kibc_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,1,$kondisi)->row();
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi);
		// $data['totald']			= $this->Kibd_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,1,$kondisi)->row();
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,1,$kondisi);
		// $data['totale']			= $this->Kibe_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,1,$kondisi)->row();
		
		$this->load->view('adminweb/laporan/akuntansi/ekstra',array_merge($data,$get_ttd));
	}

	/* cetak laporan intrakomptabel */
	function intra()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		$data['title'] 		= "DAFTAR BARANG INTRAKOMPTABEL ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi);
		// $data['totalb']			= $this->Kibb_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2,$kondisi)->row();
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi);
		// $data['totalc']			= $this->Kibc_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2,$kondisi)->row();
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi);
		// $data['totald']			= $this->Kibd_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2,$kondisi)->row();
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,2,$kondisi);
		// $data['totale']			= $this->Kibe_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2,$kondisi)->row();
		
		$this->load->view('adminweb/laporan/akuntansi/intra',array_merge($data,$get_ttd));
	}

	/* cetak laporan intrakomptabel */
	// function intra()
	// {
	// 	$kd_bidang	=  $this->input->post('kd_bidang');
	// 	$kd_unit	=  $this->input->post('kd_unit');
	// 	$kd_sub		=  $this->input->post('kd_sub_unit');
	// 	$kd_upb		=  $this->input->post('kd_upb');
	// 	$kondisi	=  $this->input->post('kondisi');
		
	// 	$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
	// 	$data['title'] 		= "DAFTAR BARANG INTRAKOMPTABEL ";

	// 	//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
	// 	$tahunawal       = $this->input->post('tahunawal');
	// 	$tahunakhir      = $this->input->post('tahunakhir');
	// 	if($tahunawal <= '1969-01-01'){
	// 		$awal = " 0 Tahun";
	// 	}else{
	// 		$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
	// 	}
	// 	$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
	// 	$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
	// 	$data['tanggal'] =  $this->input->post('tanggal');

	// 	$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
	// 	if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
	// 		$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
	// 		$data['ttd_pengurus'] = TRUE;
	// 		$data['skpd'] = TRUE;
	// 	}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
	// 		$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
	// 		$data['ttd_pengurus'] = TRUE;
	// 		$data['skpd'] = FALSE;
	// 	}else{
	// 		$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
	// 		$data['ttd_pengurus'] = FALSE;
	// 	}

	// 	$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
	// 	if(!$get_ttd)
	// 		show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

	// 	/* KIB A */
	// 	$data['jumlaha'] 		= $this->Kiba_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->num_rows();
	// 	$data['kiba'] 			= $this->Kiba_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2);
	// 	$data['totala']			= $this->Kiba_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->row();
	// 	/* KIB B */
	// 	$data['jumlahb'] 		= $this->Kibb_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->num_rows();
	// 	$data['kibb'] 			= $this->Kibb_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2);
	// 	$data['totalb']			= $this->Kibb_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->row();
	// 	/* KIB C */
	// 	$data['jumlahc'] 		= $this->Kibc_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->num_rows();
	// 	$data['kibc'] 			= $this->Kibc_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2);
	// 	$data['totalc']			= $this->Kibc_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->row();
	// 	/* KIB D */
	// 	$data['jumlahd'] 		= $this->Kibd_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->num_rows();
	// 	$data['kibd'] 			= $this->Kibd_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2);
	// 	$data['totald']			= $this->Kibd_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->row();
	// 	/* KIB E */
	// 	$data['jumlahe'] 		= $this->Kibe_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->num_rows();
	// 	$data['kibe'] 			= $this->Kibe_model->kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2);
	// 	$data['totale']			= $this->Kibe_model->total_kib_eksint($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like,2)->row();
		
	// 	$this->load->view('adminweb/laporan/akuntansi/intra',array_merge($data,$get_ttd));
	// }


	/* cetak laporan Daftar pemeliharaan */
	function pemeliharaan()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		/* KIB A */
		$data['jumlaha'] 		= $this->Kiba_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kiba'] 			= $this->Kiba_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totala']			= $this->Kiba_model->total_pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB B */
		$data['jumlah'] 		= $this->Kibb_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalb']			= $this->Kibb_model->total_pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totalc']			= $this->Kibc_model->total_pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totald']			= $this->Kibd_model->total_pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['totale']			= $this->Kibe_model->total_pemeliharaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		
		
		$this->load->view('adminweb/laporan/inventaris/pemeliharaan',array_merge($data,$get_ttd));
	}
	

	function kibb_susut(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd']         = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd']         = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah']  = $this->Kibb_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kib']     = $this->Kibb_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
	$this->load->view('adminweb/laporan/akuntansi/kibb_susut',array_merge($data,$get_ttd));
	}


	function kibc_susut(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah']  = $this->Kibc_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kib']     = $this->Kibc_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/akuntansi/kibc_susut',array_merge($data,$get_ttd));
	}

	function kibd_susut(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah']  = $this->Kibd_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kib']     = $this->Kibd_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/akuntansi/kibd_susut',array_merge($data,$get_ttd));
	}

	function kibe_susut(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah']  = $this->Kibe_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kib']     = $this->Kibe_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/akuntansi/kibe_susut',array_merge($data,$get_ttd));
	}

	function lainnya_susut(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah']  = $this->Lainnya_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['kib']     = $this->Lainnya_model->kib_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		
		$this->load->view('adminweb/laporan/akuntansi/lainnya_susut',array_merge($data,$get_ttd));
	}

	function rekap_susut(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = thn($this->input->post('tahunakhir'));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		$date = strtotime($this->input->post('tahunakhir'));
		$this->session->set_userdata('awal',"1-1-".date('Y', strtotime('+ 1 year', $date)));
		$this->session->set_userdata('akhir',date('d-m-Y', strtotime($this->input->post('tahunakhir'))));
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['kibb']        = $this->Kibb_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$data['kibc']        = $this->Kibc_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$data['kibd']        = $this->Kibd_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$data['kibe']        = $this->Kibe_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$data['kib_lainnya'] = $this->Lainnya_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->row();
		
		$this->load->view('adminweb/laporan/akuntansi/rekap_susut',array_merge($data,$get_ttd));
	}
	
	function proses_rekon(){
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$title = ($kondisi==3) ? "RUSAK BERAT" : (($kondisi == 2) ? "KURANG BAIK" : "BAIK");
		$data['title'] 		= "ASET KONDISI ".$title;

		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";
		if($kondisi){
			$kond = " AND LastKondisi = '{$kondisi}'";
		}else{
			$kond = "";
		}
		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		$date = strtotime($this->input->post('tahunakhir'));
		$this->session->set_userdata('awal',"1-1-".date('Y', strtotime('+ 1 year', $date)));
		$this->session->set_userdata('akhir',date('d-m-Y', strtotime($this->input->post('tahunakhir'))));
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['kibb']     = $this->Kibb_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['kibc']     = $this->Kibc_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['kibd']     = $this->Kibd_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['kibe']     = $this->Kibe_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);

		$data['kib_lainnya']     = $this->Lainnya_model->rekap_susut($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->row();
		
		$this->load->view('adminweb/laporan/akuntansi/proses_rekon',array_merge($data,$get_ttd));
	}


	/* data laporan penghapusan */
	function penghapusan()
	{
		if(!$this->general->privilege_check('LAPORAN_INVENTARIS',VIEW))
		    $this->general->no_access();
		
		$data['header']        = "LAPORAN PENGHAPUSAN";
		$data['title']         = $this->title;
		$data['option_bidang'] = $this->Model_chain->getBidangList();
		$data['option_sk']     = $this->Model_chain->getSKList();
		$this->template->load('template','adminweb/laporan/penghapusan/data',$data);
	}

	/* cetak laporan usulan penghapusan */
	function usul_penghapusan()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if ($kondisi == 1){
			$title   = "KONDISI BAIK";
			$kondisi = " AND lastKondisi = '1'";
		}else if ($kondisi == 2){
			$title   = "KONDISI KURANG BAIK";
			$kondisi = " AND lastKondisi = '2'";
		}else if ($kondisi == 3){
			$title   = "KONDISI RUSAK BERAT";
			$kondisi = " AND lastKondisi = '3'";
		}else if ($kondisi == 4){
			$title   = "KONDISI BAIK & KURANG BAIK";
			$kondisi = " AND lastKondisi <> '3'";
		}else{
			$title   = "";
			$kondisi = "";
		}
		
		$data['title'] 		= "DAFTAR USULAN PENGHAPUSAN ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->kib_usul_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$kondisi);
				
		$this->load->view('adminweb/laporan/penghapusan/usul_hapus',array_merge($data,$get_ttd));
	}

	function sk_penghapusan()
	{
		$kd_bidang  =  $this->input->post('kd_bidang');
		$kd_unit    =  $this->input->post('kd_unit');
		$kd_sub     =  $this->input->post('kd_sub_unit');
		$kd_upb     =  $this->input->post('kd_upb');
		$no_sk      =  $this->input->post('No_SK');
		
		$tahunawal  = $this->input->post('tahunawal');
		$tahunakhir = $this->input->post('tahunakhir');

		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		if (!empty($no_sk)){
			$title  = "NO SK : ".strtoupper($no_sk);
			$no_sk 	= " AND No_SK = '{$no_sk}'";
		}else{
			$title 	= "";
			$no_sk 	= "";
		}
		
		$data['title'] = "DAFTAR PENGHAPUSAN BARANG ".$title;

		$like = " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

		/* KIB B */
		$data['jumlahb'] 		= $this->Kibb_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk)->num_rows();
		$data['kibb'] 			= $this->Kibb_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk);
		/* KIB C */
		$data['jumlahc'] 		= $this->Kibc_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk)->num_rows();
		$data['kibc'] 			= $this->Kibc_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk);
		/* KIB D */
		$data['jumlahd'] 		= $this->Kibd_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk)->num_rows();
		$data['kibd'] 			= $this->Kibd_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk);
		/* KIB E */
		$data['jumlahe'] 		= $this->Kibe_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk)->num_rows();
		$data['kibe'] 			= $this->Kibe_model->kib_sk_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like,$no_sk);
				
		$this->load->view('adminweb/laporan/penghapusan/sk_hapus',array_merge($data,$get_ttd));
	}

	/* data laporan rekap */
	function rekap()
	{
		if(!$this->general->privilege_check('KIB',VIEW))
		    $this->general->no_access();

		$data['header'] 	= "CETAK REKAP LAPORAN INVENTARIS BARANG";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/rekap',$data);
	}


	/* cetak laporan akm_hapus */
	function akm_hapus()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->akm_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->akm_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->akm_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->akm_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->akm_hapus($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		$this->load->view('adminweb/laporan/akuntansi/akm_hapus',array_merge($data,$get_ttd));
	}

	/* cetak laporan akm_pindah */
	function akm_pindah()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		$this->load->view('adminweb/laporan/akuntansi/akm_pindah',array_merge($data,$get_ttd));
	}

	/* cetak laporan akm_rb */
	function akm_rb()
	{
		$data['title'] 		= $this->title;
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		//$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";

		$like2 = "BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");


		/* KIB A */
		$data['kiba'] = $this->Kiba_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB B */
		$data['kibb'] = $this->Kibb_model->akm_rb($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB C */
		$data['kibc'] = $this->Kibc_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB D */
		$data['kibd'] = $this->Kibd_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);
		
		/* KIB E */
		$data['kibe'] = $this->Kibe_model->akm_pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like2);

		$this->load->view('adminweb/laporan/akuntansi/akm_pindah',array_merge($data,$get_ttd));
	}


	/* data laporan perencanaan */
	function perencanaan()
	{
		if(!$this->general->privilege_check('KIB',VIEW))
		    $this->general->no_access();
		$data['header'] 	= "CETAK LAPORAN PERENCANAAN DAN PENGADAAN";
		$data['title'] 		= $this->title;
		$data['option_bidang']	= $this->Model_chain->getBidangList();
		$this->template->load('template','adminweb/laporan/perencanaan/data',$data);
	}

	function rkbu()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkbu_model->laporan_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkbu_model->laporan_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/rkbu',array_merge($data,$get_ttd));
	}

	function rkpbu()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkpbu_model->laporan_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkpbu_model->laporan_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/rkpbu',array_merge($data,$get_ttd));
	}


	function usul_rkbu()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkbu_model->laporan_usul_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkbu_model->laporan_usul_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/usul_rkbu',array_merge($data,$get_ttd));
	}

	function telaah_rkbmd()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkbu_model->laporan_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkbu_model->laporan_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/telaah_rkbmd',array_merge($data,$get_ttd));
	}

	function rkbmd()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkbu_model->laporan_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkbu_model->laporan_rkbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/rkbmd',array_merge($data,$get_ttd));
	}

	function telaah_rkpbmd()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkpbu_model->laporan_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkpbu_model->laporan_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/telaah_rkpbmd',array_merge($data,$get_ttd));
	}

	function usul_rkpbu()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkpbu'] 	= $this->Rkpbu_model->laporan_usul_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkpbu'] 			= $this->Rkpbu_model->laporan_usul_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/usul_rkpbu',array_merge($data,$get_ttd));
	}

	function rkpbmd()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_rkbu'] 	= $this->Rkpbu_model->laporan_usul_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like)->num_rows();
		$data['rkbu'] 			= $this->Rkpbu_model->laporan_usul_rkpbu($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahunawal,$tahunakhir,$like);
		$this->load->view('adminweb/laporan/perencanaan/rkpbmd',array_merge($data,$get_ttd));
	}

	/* cetak laporan daftar pengadaan */
	function pengadaan()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_pengadaan'] 	= $this->Pengadaan_model->laporan_pengadaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['pengadaan'] 			= $this->Pengadaan_model->laporan_pengadaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$this->load->view('adminweb/laporan/perencanaan/pengadaan',array_merge($data,$get_ttd));
	}

	function pengadaan_bj()
	{
		$kd_bidang	=  $this->input->post('kd_bidang');
		$kd_unit	=  $this->input->post('kd_unit');
		$kd_sub		=  $this->input->post('kd_sub_unit');
		$kd_upb		=  $this->input->post('kd_upb');
		$kondisi	=  $this->input->post('kondisi');
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		if($tahunawal <= '1969-01-01'){
			$awal = " 0 Tahun";
		}else{
			$awal = date('d M Y', strtotime($this->input->post('tahunawal')));
		}
		$data['periode'] = $awal." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "";

		$like .= " BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		if(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub) AND empty($kd_upb) ){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = TRUE;
		}elseif(!empty($kd_bidang) AND !empty($kd_unit) AND !empty($kd_sub)){
			$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();			
			$data['ttd_pengurus'] = TRUE;
			$data['skpd'] = FALSE;
		}else{
			$data['nama_upb']     = array( "Nm_bidang" => NM_PEMDA, "Nm_unit" => "DPRD", "Nm_sub_unit" => "DPRD", "Kd_Prov" => 2, "Kd_Kab_Kota" => 14, "Kd_Bidang" => 1, "Kd_Unit" => 1, "Kd_Sub" => 1, "Kd_UPB" => 1, "Nm_UPB" => "DPRD");
			$data['ttd_pengurus'] = FALSE;
		}

		$get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");
		
		$data['jumlah_pengadaan'] 	= $this->Pengadaan_bj_model->laporan_pengadaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['pengadaan'] 			= $this->Pengadaan_bj_model->laporan_pengadaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$this->load->view('adminweb/laporan/perencanaan/pengadaan_bj',array_merge($data,$get_ttd));
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */