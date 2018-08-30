<?php

class Kibe extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('KIB_E',VIEW))
		    $this->general->no_access();
		$this->auth->clean_session('KIB_E');
		$this->load->model('Kibe_model', '', TRUE);
		$this->load->model('Ta_upb_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Ta_ruang_model', '', TRUE);  
	  	$this->load->model('Model_chain', '', TRUE);  
	  	$this->load->model('Ref_penyusutan_model', '', TRUE); 
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = ' KIB E. Aset tetap lainya';
	
	/**
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('lvl') == 03){
			$this->upb();
		}else{
			$this->get_data_upb();
		}
	}
	
	
	/**
	 * Tampilkan semua data skpd
	 */
	function get_data_upb()
	{
		$data['form_cari']	= site_url('kibe/cari');
		$data['link_kib']	= site_url('kibe/listupb');
		
		$data['header'] 	= "Pilih data SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']		= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('kibe/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('kibe/upb');
		
		$data['header'] 	= "Pilih data UPB".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('kibe/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data kibe
	 */
	function upb($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if ($this->session->userdata('lvl') != '03'){
			$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		}else{
			$kd_bidang 	=  $this->session->userdata('kd_bidang');
			$kd_unit 	=  $this->session->userdata('kd_unit');
			$kd_sub 	=  $this->session->userdata('kd_sub_unit');
			$kd_upb 	=  $this->session->userdata('kd_upb');
		}
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		
		$kb       =  $this->session->userdata('kd_bidang');
		$ku       =  $this->session->userdata('kd_unit');
		$ks       =  $this->session->userdata('kd_sub_unit');
		$kupb     =  $this->session->userdata('kd_upb');
		$thn      =  $this->session->userdata('tahun_anggaran');
		
		
		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Pembukuan LIKE '%$thn%'";
			$like2 	= "AND Tgl_Pembukuan LIKE '%$thn%'";
			$judul 	= "tahun pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$like2 	= "";
			$judul 	= "Semua Data KIB E. Aset tetap lainya";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "WHERE $q LIKE '%$s%'";
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "WHERE $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			$where = "a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query'] 		= $this->Kibe_model->get_page($this->limit, $offset, $where, $like2." AND aktif is not null");
		$num_rows 			= $this->Kibe_model->count_kib($where,$like2." AND aktif is not null")->Jumlah;
		$total 				= $this->Kibe_model->count_kib($where,$like2." AND aktif is not null")->Total;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab2'] 		= site_url('kibe/waiting/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3'] 		= site_url('kibe/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['riwayat'] 	= site_url('kibe/riwayat/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] 	= pencarian_KIBE();
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?';
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data KIB E !';
		}		
		$this->template->load('template','adminweb/kibe/kibe',$data);
	}
	
	
	/**
	 * Tampilkan semua data kibe
	 */
	function skpd($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
	
		$q 		= $this->session->userdata('q');
		$s 		= $this->session->userdata('s');	
		$tanggal1 		= $this->session->userdata('tanggal1');
		$tanggal2 		= $this->session->userdata('tanggal2');	
		
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		
		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Pembukuan LIKE '%$thn%'";
			$like2 	= "AND Tgl_Pembukuan LIKE '%$thn%'";
			$judul 	= "tahun pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$like2 	= "";
			$judul 	= "Semua Data KIB E. Aset tetap lainya";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "WHERE $q LIKE '%$s%'";
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "WHERE $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			$where = "a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks ";
		}else{
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND  a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$nmupb				= $this->Sub_unit_model->nama_skpd($kd_bidang,$kd_unit,$kd_sub);
		$data['query'] 		= $this->Kibe_model->get_page($this->limit, $offset, $where, $like2);
		$num_rows 			= $this->Kibe_model->count_kib($where,$like2)->Jumlah;
		$total 				= $this->Kibe_model->count_kib($where,$like2)->Total;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' Seluruh UPB | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_skpd']	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab1'] 		= site_url('kibe/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab2'] 		= site_url('kibe/waiting/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']   = pencarian_KIBE();
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?';
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data KIB E  yang belum di periksa!';
		}		
		$this->template->load('template','adminweb/kibe/kibe_skpd',$data);
	}
	
	
	/**
	 * Tampilkan semua data kibe
	 */
	function waiting($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if ($this->session->userdata('lvl') != '03'){
			$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		}else{
			$kd_bidang 	=  $this->session->userdata('kd_bidang');
			$kd_unit 	=  $this->session->userdata('kd_unit');
			$kd_sub 	=  $this->session->userdata('kd_sub_unit');
			$kd_upb 	=  $this->session->userdata('kd_upb');
		}
	
		$q 		= $this->session->userdata('q');
		$s 		= $this->session->userdata('s');	
		$tanggal1 		= $this->session->userdata('tanggal1');
		$tanggal2 		= $this->session->userdata('tanggal2');	
		
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		
		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Pembukuan LIKE '%$thn%'";
			$like2 	= "AND Tgl_Pembukuan LIKE '%$thn%'";
			$judul 	= "tahun pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$like2 	= "";
			$judul 	= "Semua Data KIB E. Aset tetap lainya";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "WHERE $q LIKE '%$s%'";
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "WHERE $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			$where = "a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query'] 		= $this->Kibe_model->get_page($this->limit, $offset, $where, $like2." AND aktif is null");
		$num_rows 			= $this->Kibe_model->count_kib($where,$like2." AND aktif is null")->Jumlah;
		$total 				= $this->Kibe_model->count_kib($where,$like2." AND aktif is null")->Total;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' Belum di verifikasi | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_waiting']= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab1'] 		= site_url('kibe/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3'] 		= site_url('kibe/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']   = pencarian_KIBE(); 
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?';
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data KIB E  yang belum di periksa!';
		}		
		$this->template->load('template','adminweb/kibe/kibe_nonaktif',$data);
	}

	function addriwayat($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('KIB_E',ADD))
		    $this->general->no_access();

		$get = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Tidak ada data!");
			
		/*$this->session->set_userdata($get);*/

		$tahun 		= $this->session->userdata('tahun_anggaran');

		$this->session->set_userdata('rKd_Prov',$get['Kd_Prov']);
		$this->session->set_userdata('rKd_Kab_Kota',$get['Kd_Kab_Kota']);
		$this->session->set_userdata('rKd_Bidang',$get['Kd_Bidang']);
		$this->session->set_userdata('rKd_Unit',$get['Kd_Unit']);
		$this->session->set_userdata('rKd_Sub',$get['Kd_Sub']);
		$this->session->set_userdata('rKd_UPB',$get['Kd_UPB']);
		$this->session->set_userdata('rKd_Aset1',$get['Kd_Aset1']);
		$this->session->set_userdata('rKd_Aset2',$get['Kd_Aset2']);
		$this->session->set_userdata('rKd_Aset3',$get['Kd_Aset3']);
		$this->session->set_userdata('rKd_Aset4',$get['Kd_Aset4']);
		$this->session->set_userdata('rKd_Aset5',$get['Kd_Aset5']);
		$this->session->set_userdata('rNo_Register',$get['No_Register']);
		$this->session->set_userdata('rHarga',$get['LastHarga']);

		$data['title']        = $this->title;
		$data['option_bidang'] = $this->Model_chain->getBidangList();
		$data['form_action']  = site_url('kibe/save_riwayat');
		$data['link']         = array('link_back' => anchor('kibe','kembali', array('class' => 'back')));
		
		$data['option_q']     = $this->Model_chain->getRiwayatList();
		
		$data['option_ruang'] = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun); 
		
		$nmupb                = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['namaaset']     = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		
		$data['header']       = $this->title.' ('.$nmupb.')';
		$this->template->load('template','adminweb/kibe/kibe_addriwayat',array_merge($data,$get));
	}

	/**
	 * Proses tambah data riwayat
	 */
	function save_riwayat()
	{

		$last_id = $this->last_Kd_Id($this->session->userdata('rKd_Prov'),
									$this->session->userdata('rKd_Kab_Kota'),
									$this->session->userdata('rKd_Bidang'),
									$this->session->userdata('rKd_Unit'),
									$this->session->userdata('rKd_Sub'),
									$this->session->userdata('rKd_UPB'),
									$this->session->userdata('rKd_Aset1'),
									$this->session->userdata('rKd_Aset2'),
									$this->session->userdata('rKd_Aset3'),
									$this->session->userdata('rKd_Aset4'),
									$this->session->userdata('rKd_Aset5'),
									$this->session->userdata('rNo_Register'));


		if($this->input->post('Masa_Manfaat') == 2){ /*2. tidak bertambah*/
			$mf = 0;
		}else{
			$mf = kapitalisasi($this->session->userdata('rKd_Aset1'),
							   $this->session->userdata('rKd_Aset2'),
							   $this->session->userdata('rKd_Aset3'),
							   $this->session->userdata('rHarga'),
							   $this->input->post('Harga'));
		}


		$arr = array(
					'Kd_Riwayat'  => $this->input->post('Kd_Riwayat'),
					'Kd_Id'       => $last_id,
					'Kd_Prov'     => $this->session->userdata('rKd_Prov'),
					'Kd_Kab_Kota' => $this->session->userdata('rKd_Kab_Kota'),
					'Kd_Bidang'   => $this->session->userdata('rKd_Bidang'),
					'Kd_Unit'     => $this->session->userdata('rKd_Unit'),
					'Kd_Sub'      => $this->session->userdata('rKd_Sub'),
					'Kd_UPB'      => $this->session->userdata('rKd_UPB'),
					'Kd_Pemilik'  => $this->session->userdata('rKd_Pemilik'),
					'Kd_Aset1'    => $this->session->userdata('rKd_Aset1'),
					'Kd_Aset2'    => $this->session->userdata('rKd_Aset2'),
					'Kd_Aset3'    => $this->session->userdata('rKd_Aset3'),
					'Kd_Aset4'    => $this->session->userdata('rKd_Aset4'),
					'Kd_Aset5'    => $this->session->userdata('rKd_Aset5'),
					'No_Register' => $this->session->userdata('rNo_Register'),
					'Tgl_Dokumen' => $this->input->post('Tgl_Dokumen'),
					'No_Dokumen'  => $this->input->post('No_Dokumen'),
					'Kondisi'     => $this->input->post('Kondisi'),
					'Harga'       => $this->input->post('Harga'),
					'Kd_Ruang'    => $this->input->post('Kd_Ruang'),
					'Tgl_Mulai'   => $this->input->post('Tgl_Mulai'),
					'Tgl_Selesai' => $this->input->post('Tgl_Selesai'),
					'Masa_Manfaat'=> $mf,
					'Keterangan'  => $this->input->post('Keterangan'),
					'Kd_Prov1'     => $this->session->userdata('rKd_Prov'),
					'Kd_Kab_Kota1' => $this->session->userdata('rKd_Kab_Kota'),
					'Kd_Bidang1'   =>  $this->input->post('kd_bidang'),
					'Kd_Unit1'     =>  $this->input->post('kd_unit'),
					'Kd_Sub1'      =>  $this->input->post('kd_sub_unit'),
					'Kd_UPB1'      =>  $this->input->post('kd_upb'),
					'Log_User'    => $this->session->userdata('username'),
					'Log_entry'   => date("Y-m-d H:i:s"));
			
			
			/*print_r($arr); exit();*/

			$sql = $this->Kibe_model->add_riwayat($arr);
			
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB berhasil ditambah!, Silahkan tunggu verifikasi oleh admin');
				redirect($this->session->userdata('last_url'));
			}else{		
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB Gagal ditambah!');
				redirect($this->session->userdata('last_url'));
				}	
	}

	function riwayat($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('RIWAYAT_KIB_C',VIEW))
		    $this->general->no_access();

		if ($this->session->userdata('lvl') != '03'){
			$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		}else{
			$kd_bidang 	=  $this->session->userdata('kd_bidang');
			$kd_unit 	=  $this->session->userdata('kd_unit');
			$kd_sub 	=  $this->session->userdata('kd_sub_unit');
			$kd_upb 	=  $this->session->userdata('kd_upb');
		}
	
		$r        = $this->session->userdata('riwayat');
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		
		$kb       =  $this->session->userdata('kd_bidang');
		$ku       =  $this->session->userdata('kd_unit');
		$ks       =  $this->session->userdata('kd_sub_unit');
		$kupb     =  $this->session->userdata('kd_upb');
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND Tgl_Dokumen BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Riwayat KIB C. Gedung dan Bangunan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_Pembukuan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		if (!empty($r)){
			$like 	.= " AND a.Kd_Riwayat = '{$r}'";
			$judul 	.= " Filter Riwayat";
		}

		/*print_r($like); exit();*/

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		
		$nmupb               = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query']       = $this->Kibe_model->get_riwayat($this->limit, $offset, $where, $like);
		$num_rows            = $this->Kibe_model->total_riwayat($where,$like)->Jumlah;
		$harga_total         = $this->Kibe_model->total_riwayat($where,$like)->Harga;
		
		$data['header']      = ' Riwayat '.$this->title.' '.$nmupb.' | Total Harga = Rp.'.rp($harga_total).',-';
		$data['jml_waiting'] = $num_rows;
		
		$data['offset']      = $offset;
		$data['form_cari']   = current_URL();
		$data['form_action'] = site_url('kibe/set');
		$data['form_filter'] = site_url('kibe/filter');
		
		$data['tab1']        = site_url('kibb/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3']        = site_url('kibb/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']    =  pencarian_KIBE();
		
		$data['riwayat']     = $this->Model_chain->getRiwayatList();
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?';
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data riwayat data!';
		}		
		$this->template->load('template','adminweb/kibe/kibe_riwayat',$data);
	}
	
	public function set()
	{
			$session_data = array(
				'q'				=> $this->input->post('q'),
				's'				=> $this->input->post('s'),
				'tanggal1'		=> $this->input->post('tanggal1'),
				'tanggal2' 		=> $this->input->post('tanggal2')
			);
			
			$this->session->set_userdata($session_data);
			header('location:'.$this->session->userdata('curl'));
	}
	
	
	function select_q(){
			
			$cek = $this->input->post('q');
			if($cek == "Sertifikat_Tanggal"){
				$this->load->view('adminweb/form/sertifikat');
			}elseif($cek == "Harga"){
				$this->load->view('adminweb/form/harga');
			}else{
				echo "<input type='text' placeholder='Isi disini' name='s' id='cari' class='input-medium'>";
			}

		
	}
	
	
	/**
	 * Pindah ke halaman update kibe
	 */
	function lihat($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB E > Update';
		$data['form_action']	= site_url('kibe/update_process');
		$data['link'] 			= array('link_back' => anchor('kibe','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;

		
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();
		$tahun = $this->session->userdata('tahun_anggaran');
		$data['option_ruang'] 	= $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
		
		$jumlah = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$this->session->set_userdata('last_url', current_url());
			$kibe = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row();
												  
			$namaaset	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
			
			$ruang = $this->Ta_ruang_model->get_ruang_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kibe->Kd_Ruang)->row();											  

				$this->session->set_userdata('Kd_Aset1', $kibe->Kd_Aset1);
				$this->session->set_userdata('Kd_Aset2', $kibe->Kd_Aset2);
				$this->session->set_userdata('Kd_Aset3', $kibe->Kd_Aset3);
				$this->session->set_userdata('Kd_Aset4', $kibe->Kd_Aset4);
				$this->session->set_userdata('Kd_Aset5', $kibe->Kd_Aset5);
				$this->session->set_userdata('No_Register', $kibe->No_Register);
				
				$data['default']['Kd_Prov']       		= $kibe->Kd_Prov;
				$data['default']['Kd_Kab_Kota']   		= $kibe->Kd_Kab_Kota;
				$data['default']['Kd_Bidang'] 			= $kibe->Kd_Bidang;
				$data['default']['Kd_Unit'] 			= $kibe->Kd_Unit;
				$data['default']['Kd_Sub'] 				= $kibe->Kd_Sub;
				$data['default']['Kd_UPB'] 				= $kibe->Kd_UPB;
				
				$data['default']['Kd_Aset1'] 			= $kibe->Kd_Aset1;
				$data['default']['Kd_Aset2'] 			= $kibe->Kd_Aset2;
				$data['default']['Kd_Aset3'] 			= $kibe->Kd_Aset3;
				$data['default']['Kd_Aset4'] 			= $kibe->Kd_Aset4;
				$data['default']['Kd_Aset5'] 			= $kibe->Kd_Aset5;
				$data['default']['Nm_Aset5'] 			= $namaaset;
				$data['default']['No_Register'] 		= $kibe->No_Register;
				$data['default']['Kd_Ruang'] 			= isset($ruang->Nm_Ruang) ? $ruang->Nm_Ruang : 'tidak ada ruangan';  
				$data['default']['Kd_Pemilik'] 			= $kibe->Kd_Pemilik;
				$data['default']['Judul'] 				= $kibe->Judul;
				$data['default']['Pencipta'] 			= $kibe->Pencipta;
				$data['default']['Bahan'] 				= $kibe->Bahan;
				$data['default']['Ukuran'] 				= $kibe->Ukuran;
				$data['default']['Tgl_Perolehan'] 		= $kibe->Tgl_Perolehan;
				$data['default']['Tgl_Pembukuan'] 		= $kibe->Tgl_Pembukuan;
				$data['default']['Asal_usul'] 			= $kibe->Asal_usul;
				$data['default']['Kondisi'] 			= $kibe->Kondisi;
				$data['default']['Harga']            	= $kibe->Harga;
				$data['default']['LastHarga']        	= $kibe->LastHarga;
				$data['default']['Keterangan'] 			= $kibe->Keterangan;
				$data['default']['Nilai_Sisa'] 			= $kibe->Nilai_Sisa;
				$data['default']['Masa_Manfaat'] 		= $kibe->Masa_Manfaat; 
				
				$data['query'] 		= $this->Kibe_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
								
				$data['riwayat'] 	= $this->Kibe_model->get_riwayat_barang($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);

				$this->template->load('template','adminweb/kibe/kibe_lihat',$data);				
		}else{
			echo "tidak ada data";	
		}
	}

	/**
	 * post ajax operasional data
	 */
	function operasional(){
			$kd_prov	 =  $this->session->userdata('kd_prov');
			$kd_kab_kota =  $this->session->userdata('kd_kab_kota');
			
			$Kd_KA	=  $this->input->post('Kd_KA');
			if ($Kd_KA == '0'){
				$data = array('Kd_KA' => "1");
			}else{
				$data = array('Kd_KA' => '0');
			}
			
			$kd_bidang   = $this->input->post('kd_bidang');
			$kd_unit     = $this->input->post('kd_unit');
			$kd_sub      = $this->input->post('kd_sub');
			$kd_upb      = $this->input->post('kd_upb');	
			
			$kd_aset1    = $this->input->post('kd_aset1');
			$kd_aset2    = $this->input->post('kd_aset2');
			$kd_aset3    = $this->input->post('kd_aset3');
			$kd_aset4    = $this->input->post('kd_aset4');
			$kd_aset5    = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
			$this->Kibe_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data);	
		
	}
	
	function export(){		
		
		$q 				= $this->session->userdata('q');
		$s 				= $this->session->userdata('s');	
		$tanggal1 		= $this->session->userdata('tanggal1');
		$tanggal2 		= $this->session->userdata('tanggal2');	
		$kd_bidang 		= $this->session->userdata('addKd_Bidang');
		$kd_unit 		= $this->session->userdata('addKd_Unit');
		$kd_sub			= $this->session->userdata('addKd_Sub');
		$kd_upb			= $this->session->userdata('addKd_UPB');
		$curl			= $this->session->userdata('curl');
		
		
		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like2 	= "AND Tgl_Pembukuan LIKE '%$thn%'";
			$judul 	= "tahun pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like2 	= "AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like2 	= "";
			$judul 	= "Semua Data KIB E. Aset tetap lainya";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}

		$where = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
		$where2 = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub";
		
		$d['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
		
		$arg = explode('/', $curl);
		if ($arg[5] == 'skpd' ) {
			$d['data_view'] = $this->db->query("select * from Ta_KIB_E inner join Ref_Rek_Aset5 on 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
a.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
a.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
a.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
a.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where2 $like2");			
		}elseif ($arg[5] == 'upb' ) {
			$d['data_view'] = $this->db->query("select * from Ta_KIB_E inner join Ref_Rek_Aset5 on 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
a.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
a.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
a.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
a.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like2 AND aktif is not null");			
		}else{
			$d['data_view'] = $this->db->query("select * from Ta_KIB_E inner join Ref_Rek_Aset5 on 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
a.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
a.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
a.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
a.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like2 AND aktif is null");	
			}
		
		$this->load->view('adminweb/kibe/export',$d);


	}
	
	function last_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('No_Id');
			$array_keys_values = $this->db->get_where('Ta_FotoE',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
			'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
       		foreach ($array_keys_values->result() as $data) {
					 $result=$data->No_Id+1;
				}
        return $result;
	}

	function last_Kd_Id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('Kd_Id');
			$array_keys_values = $this->db->get_where('Ta_KIBER',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
			'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
       		foreach ($array_keys_values->result() as $data) {
					 $result=$data->Kd_Id+1;
				}
        return $result;
	}
	
	
	public function upload()
	{
		if(isset($_FILES['image']))
		{
			
			$data	= $_FILES['image'];
			$total 	= count($data['name']);
			$data2	= array();
			for($i=0; $i<$total; $i++)
			{
				$data2[]=array(
					'name'=>$data['name'][$i],
					'type'=>$data['type'][$i],
					'tmp_name'=>$data['tmp_name'][$i],
					'error'=>$data['error'][$i],
					'size'=>$data['size'][$i],
				);
			}
			
			$no=0;
			foreach($data2 as $row)
			{
				$config['upload_path'] = './assets/uploads_kibe';
				$config['allowed_types'] = 'gif|jpg|png|bmp';
				$this->load->library('multi_upload', $config);
				if($this->multi_upload->do_upload($data2[$no]))
				{
					$image_data = $this->multi_upload->data();
					
					$foto = array(
						'Kd_Prov'				=> $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'			=> $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'				=> $this->input->post('Kd_Bidang'),
						'Kd_Unit'				=> $this->input->post('Kd_Unit'),
						'Kd_Sub'				=> $this->input->post('Kd_Sub'),
						'Kd_UPB'				=> $this->input->post('Kd_UPB'),
						'Kd_Aset1'				=> $this->input->post('Kd_Aset1'),
						'Kd_Aset2'				=> $this->input->post('Kd_Aset2'),
						'Kd_Aset3'				=> $this->input->post('Kd_Aset3'),
						'Kd_Aset4'				=> $this->input->post('Kd_Aset4'),
						'Kd_Aset5'				=> $this->input->post('Kd_Aset5'),
						'No_Register'			=> $this->input->post('No_Register'),
						'No_Id'					=> $this->last_id($this->session->userdata('kd_prov'),
																$this->session->userdata('kd_kab_kota'),
																$this->input->post('Kd_Bidang'),
																$this->input->post('Kd_Unit'),
																$this->input->post('Kd_Sub'),
																$this->input->post('Kd_UPB'),
																$this->input->post('Kd_Aset1'),
																$this->input->post('Kd_Aset2'),
																$this->input->post('Kd_Aset3'),
																$this->input->post('Kd_Aset4'),
																$this->input->post('Kd_Aset5'),
																$this->input->post('No_Register')),
						'Foto_Aset'				=> '',
						'Nama_foto'				=> $image_data['file_name'],
						'Keterangan'			=> $this->input->post('Keterangan'),
						'Kd_Kecamatan'			=> 15,
						'Kd_Desa'				=> 1,
						'Log_User'				=> $this->session->userdata('username'),
						'Log_entry'				=> date("Y-m-d"));
						$this->Kibe_model->insert($foto);
						
					echo img(array(
						'src'=>base_url("assets/uploads_kibe/$image_data[file_name]"),
						'width'=>179,
						'style'=>'margin:10px; padding:10px; background:#bbb'
					)); 
				}
				$no++;
			}
		}else{
			echo "Silahkan pilih foto Terlebih dahulu !";	
		}
	}
	
	function hapus_gambar(){
		
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1 = $this->input->post('kd_aset1');
			$kd_aset2 = $this->input->post('kd_aset2');
			$kd_aset3 = $this->input->post('kd_aset3');
			$kd_aset4 = $this->input->post('kd_aset4');
			$kd_aset5 = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			$no_id = $this->input->post('no_id');

    $row = $this->db->get_where('Ta_FotoE',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id))->row();

     unlink("./assets/uploads_kibe/$row->Nama_foto");

    $this->db->delete('Ta_FotoE', array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id));

}
	
		
	/**
	 * Hapus data KIB E
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('KIB_E','remove'))
		    $this->general->no_access();

		$this->Kibe_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('kibe');
	}
	
	/**
	 * Menghapus dengan ajax post
	 */
	function ajax_hapus(){

		if(!$this->general->privilege_check('KIB_E','remove'))
		    $this->general->no_access();
		
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
			
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Kibe_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								 $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}


function hapus_riwayat(){
		if(!$this->general->privilege_check('RIWAYAT_KIB_E',REMOVE))
		    $this->general->no_access();

		$kd_prov	 =  $this->input->post('Kd_Prov');
		$kd_kab_kota =  $this->input->post('Kd_Kab_Kota');

		$kd_riwayat  = $this->input->post('Kd_Riwayat');
		$kd_id       = $this->input->post('Kd_Id');

		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
		$kd_aset1x   = $this->input->post('kd_aset1');
		$kd_aset2x   = $this->input->post('kd_aset2');
		$kd_aset3x   = $this->input->post('kd_aset3');
		$kd_aset4x   = $this->input->post('kd_aset4');
		$kd_aset5x   = $this->input->post('kd_aset5');
		$no_register = $this->input->post('no_register');

		$this->Kibe_model->hapus_riwayat($kd_riwayat,$kd_id,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								 $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}
	
	
/* pindah skpd */
function pindahskpd()
	{
		$kd_prov	=  $this->session->userdata('kd_prov');
		$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
			$register 	= $this->Kibe_model->get_last_noregister($this->input->post('tkd_bidang'),$this->input->post('tkd_unit'),$this->input->post('tkd_sub'),$this->input->post('tkd_upb'),$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);

	$kibe = array(
			'Kd_Bidang'				=> $this->input->post('tkd_bidang'),
			'Kd_Unit'				=> $this->input->post('tkd_unit'),
			'Kd_Sub'				=> $this->input->post('tkd_sub'),
			'Kd_UPB'				=> $this->input->post('tkd_upb'),
			'No_Register'			=> $register);
		

		$this->Kibe_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								  $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register,$kibe);	

	}
	
	/**
	 * post ajax aktifkan data
	 */
	function aktifkan(){
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
			$data = array('aktif'			=> $this->session->userdata('id_user'));
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
			
			$kd_aset1 = $this->input->post('kd_aset1');
			$kd_aset2 = $this->input->post('kd_aset2');
			$kd_aset3 = $this->input->post('kd_aset3');
			$kd_aset4 = $this->input->post('kd_aset4');
			$kd_aset5 = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Kibe_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
									 $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data);	
		}
	}
	
	
	/**
	 * Pindah ke halaman tambah kibe
	 */
	function add()
	{	
		if(!$this->general->privilege_check('KIB_E',ADD))
		    $this->general->no_access();

		$data['default']['Kd_Bidang'] = $this->session->userdata('addKd_Bidang');
		$data['default']['Kd_Unit']   = $this->session->userdata('addKd_Unit');
		$data['default']['Kd_Sub']    = $this->session->userdata('addKd_Sub');
		$data['default']['Kd_UPB']    = $this->session->userdata('addKd_UPB');
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB E > Tambah Data';
		$data['form_action']	= site_url('kibe/add_process');
		$data['link'] 			= array('link_back' => anchor('kibe','kembali', array('class' => 'back'))
										);
										
		$nmupb				= $this->Ref_upb_model->nama_upb($this->session->userdata('addKd_Bidang'),
															 $this->session->userdata('addKd_Unit'),
															 $this->session->userdata('addKd_Sub'),
															 $this->session->userdata('addKd_UPB'));

		$data['header'] 		= $this->title.' ('.$nmupb.')';
		
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();
		$kd_prov 	= $this->session->userdata('kd_prov'); 
		$kd_kab 	= $this->session->userdata('kd_kab_kota');  
		$kd_bidang 	= $this->session->userdata('addKd_Bidang');  
		$kd_unit 	= $this->session->userdata('addKd_Unit');  
		$kd_sub 	= $this->session->userdata('addKd_Sub');  
		$kd_upb 	= $this->session->userdata('addKd_UPB');  
		$tahun 		= $this->session->userdata('tahun_anggaran');
		$data['option_ruang']   = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun); 
		$this->template->load('template','adminweb/kibe/kibe_addform',$data);
	}
	
	/**
	 * Proses tambah data kibe
	 */
	function add_process()
	{		
			$this->auth->restrict();
			$jp 	= $this->input->post('jmlperc');
			$reg 	= $this->input->post('No_Register');
			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			if ($d){
				$masa_manfaat 	= $d->Masa_Manfaat;
			}else{
				$masa_manfaat 	= 0;
			}
			$metode			= 1;
			if ($jp !=""){
						$no=0;	
						for($i=0; $i<$jp; $i++){
						$kibe = array(
									'Kd_Prov'				=> $this->session->userdata('kd_prov'),
									'Kd_Kab_Kota'			=> $this->session->userdata('kd_kab_kota'),
									'Kd_Bidang'				=> $this->input->post('Kd_Bidang'),
									'Kd_Unit'				=> $this->input->post('Kd_Unit'),
									'Kd_Sub'				=> $this->input->post('Kd_Sub'),
									'Kd_UPB'				=> $this->input->post('Kd_UPB'),
									'Kd_Pemilik'			=> $this->input->post('Kd_Pemilik'),
									'Kd_Aset1'				=> $this->input->post('kd_aset1'),
									'Kd_Aset2'				=> $this->input->post('kd_aset2'),
									'Kd_Aset3'				=> $this->input->post('kd_aset3'),
									'Kd_Aset4'				=> $this->input->post('kd_aset4'),
									'Kd_Aset5'				=> $this->input->post('kd_aset5'),
									'No_Register'			=> $reg,
									'Kd_Ruang'				=> $this->input->post('Kd_Ruang'),
									'Tgl_Perolehan'			=> $this->input->post('Tgl_Perolehan'),
									'Tgl_Pembukuan'			=> $this->input->post('Tgl_Pembukuan'),
									'Judul'					=> $this->input->post('Judul'),
									'Pencipta'				=> $this->input->post('Pencipta'),
									'Bahan'					=> $this->input->post('Bahan'),
									'Ukuran'				=> $this->input->post('Ukuran'),
									'Asal_usul'				=> $this->input->post('Asal_usul'),
									'Kondisi'				=> $this->input->post('Kondisi'),
									'Harga'					=> $this->input->post('Harga'),
									'Keterangan'			=> $this->input->post('Keterangan'),
									'Nilai_Sisa'			=> $this->input->post('Nilai_Sisa'),
									'Masa_Manfaat'			=> $masa_manfaat,
									'Kd_Penyusutan'			=> $metode,
									'Kd_Data'				=> 1,
									'Kd_KA'         		=> $this->input->post('Kd_KA'),
									'Log_User'				=> $this->session->userdata('username'));
							$sql = $this->Kibe_model->add($kibe);
							$reg++;
							$no++;
						}

						if ($sql){
							$this->session->set_flashdata('message', $no.' data KIB E berhasil ditambah!');
							redirect('kibe/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
						}else{
							$this->session->set_flashdata('message', 'Satu data KIB E Gagal ditambah!');
							redirect('kibe/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
							}

						}else{
						$kibe = array(
									'Kd_Prov'				=> $this->session->userdata('kd_prov'),
									'Kd_Kab_Kota'			=> $this->session->userdata('kd_kab_kota'),
									'Kd_Bidang'				=> $this->input->post('Kd_Bidang'),
									'Kd_Unit'				=> $this->input->post('Kd_Unit'),
									'Kd_Sub'				=> $this->input->post('Kd_Sub'),
									'Kd_UPB'				=> $this->input->post('Kd_UPB'),
									'Kd_Pemilik'			=> $this->input->post('Kd_Pemilik'),
									'Kd_Aset1'				=> $this->input->post('kd_aset1'),
									'Kd_Aset2'				=> $this->input->post('kd_aset2'),
									'Kd_Aset3'				=> $this->input->post('kd_aset3'),
									'Kd_Aset4'				=> $this->input->post('kd_aset4'),
									'Kd_Aset5'				=> $this->input->post('kd_aset5'),
									'No_Register'			=> $this->input->post('No_Register'),
									'Kd_Ruang'				=> $this->input->post('Kd_Ruang'),
									'Tgl_Perolehan'			=> $this->input->post('Tgl_Perolehan'),
									'Tgl_Pembukuan'			=> $this->input->post('Tgl_Pembukuan'),
									'Kd_Ruang'				=> $this->input->post('Kd_Ruang'),
									'Tgl_Perolehan'			=> $this->input->post('Tgl_Perolehan'),
									'Tgl_Pembukuan'			=> $this->input->post('Tgl_Pembukuan'),
									'Judul'					=> $this->input->post('Judul'),
									'Pencipta'				=> $this->input->post('Pencipta'),
									'Bahan'					=> $this->input->post('Bahan'),
									'Ukuran'				=> $this->input->post('Ukuran'),
									'Asal_usul'				=> $this->input->post('Asal_usul'),
									'Kondisi'				=> $this->input->post('Kondisi'),
									'Harga'					=> $this->input->post('Harga'),
									'Keterangan'			=> $this->input->post('Keterangan'),
									'Nilai_Sisa'			=> $this->input->post('Nilai_Sisa'),
									'Masa_Manfaat'			=> $masa_manfaat,
									'Kd_Penyusutan'			=> $metode,
									'Kd_Data'				=> 1,
									'Kd_KA'         		=> $this->input->post('Kd_KA'),
									'Log_User'				=> $this->session->userdata('username'),
									'Log_entry'				=> date("Y-m-d H:i:s"));
									
							$sql = $this->Kibe_model->add($kibe);

							if ($sql){
								$this->session->set_flashdata('message', 'Satu data KIB E berhasil ditambah!, Silahkan tunggu verifikasi oleh admin');
								redirect('kibe/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
							}else{
								$this->session->set_flashdata('message', 'Satu data KIB E Gagal ditambah!');
								redirect('kibe/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
								}

						}
					
	}
	
	/**
	 * Pindah ke halaman update kibe
	 */
	function update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('KIB_E',EDIT))
		    $this->general->no_access();

		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB E > Update';
		$data['form_action']	= site_url('kibe/update_process');
		$data['link'] 			= array('link_back' => anchor('kibe','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;

		$tahun = $this->session->userdata('tahun_anggaran');  
		$data['option_ruang'] = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);     
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();

		$jumlah = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$kibe = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row();
												  
			$namaaset	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);											  

				$this->session->set_userdata('Kd_Aset1', $kibe->Kd_Aset1);  
				$this->session->set_userdata('Kd_Aset2', $kibe->Kd_Aset2);  
				$this->session->set_userdata('Kd_Aset3', $kibe->Kd_Aset3);  
				$this->session->set_userdata('Kd_Aset4', $kibe->Kd_Aset4);  
				$this->session->set_userdata('Kd_Aset5', $kibe->Kd_Aset5);  
				$this->session->set_userdata('No_Register', $kibe->No_Register);   
			 
				$data['default']['Kd_Bidang']     = $kibe->Kd_Bidang;
				$data['default']['Kd_Unit']       = $kibe->Kd_Unit;
				$data['default']['Kd_Sub']        = $kibe->Kd_Sub;
				$data['default']['Kd_UPB']        = $kibe->Kd_UPB;
				
				$data['default']['Kd_Aset1']      = $kibe->Kd_Aset1;
				$data['default']['Kd_Aset2']      = $kibe->Kd_Aset2;
				$data['default']['Kd_Aset3']      = $kibe->Kd_Aset3;
				$data['default']['Kd_Aset4']      = $kibe->Kd_Aset4;
				$data['default']['Kd_Aset5']      = $kibe->Kd_Aset5;
				$data['default']['Nm_Aset5']      = $namaaset;
				$data['default']['No_Register']   = $kibe->No_Register;
				$data['default']['Kd_Ruang']      = $kibe->Kd_Ruang;
				$data['default']['Kd_Pemilik']    = $kibe->Kd_Pemilik;
				$data['default']['Judul']         = $kibe->Judul;
				$data['default']['Pencipta']      = $kibe->Pencipta;
				$data['default']['Bahan']         = $kibe->Bahan;
				$data['default']['Ukuran']        = $kibe->Ukuran;
				$data['default']['Tgl_Perolehan'] = $kibe->Tgl_Perolehan;
				$data['default']['Tgl_Pembukuan'] = $kibe->Tgl_Pembukuan;
				$data['default']['Asal_usul']     = $kibe->Asal_usul;
				$data['default']['Kondisi']       = $kibe->Kondisi;
				$data['default']['Harga']         = $kibe->Harga;
				$data['default']['Keterangan']    = $kibe->Keterangan;
				$data['default']['Nilai_Sisa']    = $kibe->Nilai_Sisa;
				$data['default']['Masa_Manfaat']  = $kibe->Masa_Manfaat;

				$data['default']['Kd_KA']  		  = $kibe->Kd_KA; 
								
				$this->template->load('template','adminweb/kibe/kibe_updateform',$data);				
		}else{
			redirect('kibe');	
		}
	}
	
	/**
	 * Proses update data kibe
	 */
	function update_process()
	{

			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			if ($d){
				$masa_manfaat 	= $d->Masa_Manfaat;
			}else{
				$masa_manfaat 	= 0;
			}

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
			$kibe = array(
								'Kd_Prov'       => $this->session->userdata('kd_prov'),
								'Kd_Kab_Kota'   => $this->session->userdata('kd_kab_kota'),
								'Kd_Bidang'     => $this->input->post('Kd_Bidang'),
								'Kd_Unit'       => $this->input->post('Kd_Unit'),
								'Kd_Sub'        => $this->input->post('Kd_Sub'),
								'Kd_UPB'        => $this->input->post('Kd_UPB'),
								'Kd_Pemilik'    => $this->input->post('Kd_Pemilik'),
								'Kd_Aset1'      => $this->input->post('kd_aset1'),
								'Kd_Aset2'      => $this->input->post('kd_aset2'),
								'Kd_Aset3'      => $this->input->post('kd_aset3'),
								'Kd_Aset4'      => $this->input->post('kd_aset4'),
								'Kd_Aset5'      => $this->input->post('kd_aset5'),
								'No_Register'   => $this->input->post('No_Register'),
								'Kd_Ruang'      => $this->input->post('Kd_Ruang'),
								'Tgl_Perolehan' => $this->input->post('Tgl_Perolehan'),
								'Tgl_Pembukuan' => $this->input->post('Tgl_Pembukuan'),
								'Kd_Ruang'      => $this->input->post('Kd_Ruang'),
								'Tgl_Perolehan' => $this->input->post('Tgl_Perolehan'),
								'Tgl_Pembukuan' => $this->input->post('Tgl_Pembukuan'),
								'Judul'         => $this->input->post('Judul'),
								'Pencipta'      => $this->input->post('Pencipta'),
								'Bahan'         => $this->input->post('Bahan'),
								'Ukuran'        => $this->input->post('Ukuran'),
								'Asal_usul'     => $this->input->post('Asal_usul'),
								'Kondisi'       => $this->input->post('Kondisi'),
								'Harga'         => $this->input->post('Harga'),
								'Keterangan'    => $this->input->post('Keterangan'),
								'Nilai_Sisa'    => $this->input->post('Nilai_Sisa'),
								'Masa_Manfaat'  => $masa_manfaat,
								'aktif'         => NULL,
								'Kd_KA'         => $this->input->post('Kd_KA'),
								'Log_User'      => $this->session->userdata('username'),
								'Log_entry'     => date("Y-m-d H:i:s"));

			$kd_bidang		= $this->input->post('Kd_Bidang');
			$kd_unit		= $this->input->post('Kd_Unit');
			$kd_sub			= $this->input->post('Kd_Sub');
			$kd_upb			= $this->input->post('Kd_UPB');
			
			$kd_aset1 		= $this->session->userdata('Kd_Aset1');
			$kd_aset2 		= $this->session->userdata('Kd_Aset2');
			$kd_aset3 		= $this->session->userdata('Kd_Aset3');
			$kd_aset4 		= $this->session->userdata('Kd_Aset4');
			$kd_aset5 		= $this->session->userdata('Kd_Aset5');
			$no_register 	= $this->session->userdata('No_Register');
			$curl 			= $this->session->userdata('curl');
						
			$sql = $this->Kibe_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,
												$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kibe);	
			if ($sql){
				$this->session->set_flashdata('message', 'Data Berhasil Diubah!');
				redirect($curl);
			}else{		
				$this->session->set_flashdata('message', 'Data Gagal Diubah!');
				redirect($curl);
				}
				 
	}
	
	function lookup()
	{
		$page  = $this->input->get('page');
		$limit = $this->input->get('rows');
		$sidx  = $this->input->get('sidx');
		$sord  = $this->input->get('sord');
		
		if(!$sidx) $sidx=1;
		
		
		$where = ""; 
		$searchField 	= isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchString 	= isset($_GET['searchString']) ? $_GET['searchString'] : false;
		if ($_GET['_search'] == 'true') {
			$where = "$searchField like '%$searchString%'";
		}
	        		
		$count = $this->Ref_rek_aset5_model->count_kibe($where);
		
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		
		$data1 = $this->Ref_rek_aset5_model->get_kibe($where, $sidx, $sord, $limit, $start)->result();
	
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		$i=0;
		foreach($data1 as $line){
		$responce->rows[$i]['Kd_Aset1']  = $line->Kd_Aset1;
		$responce->rows[$i]['cell'] 	 = array('',$line->Kd_Aset1,$line->Kd_Aset2,$line->Kd_Aset3,$line->Kd_Aset4,$line->Kd_Aset5,$line->Nm_Aset5);
		$i++;
		}
		echo json_encode($responce);
	}
	
	
	
	function json(){
        $keyword = $this->input->post('term');
        $data['response'] = 'false'; 
        $query = $this->Ref_rek_aset5_model->json_kibe($keyword); 
        if( ! empty($query) )
        {
            $data['response'] = 'true'; 
            $data['message'] = array(); 
            foreach( $query as $row )
            {
                $data['message'][] = array( 
                                        'id1'=>$row->Kd_Aset1,
										'id2'=>$row->Kd_Aset2,
										'id3'=>$row->Kd_Aset3,
										'id4'=>$row->Kd_Aset4,
										'id5'=>$row->Kd_Aset5,
                                        'value' => $row->Nm_Aset5,
                                        ''
                                     ); 
            }
        }
        if('IS_AJAX')
        {
            echo json_encode($data);
            
        }
        else
        {
            $this->load->view('kibe/index',$data); 
        }
	}
	
	
	/**
	 * Mendapatan Nomor Register
	 */
	function register(){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			
       
$num_rows = $this->Kibe_model->get_last_noregister($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);		
		
			echo "<input type=text name='No_Register' size=5 value='$num_rows' id='No_Register' readonly='readonly' class='required input-mini'>";
			}
	
	/**
	 * Cek apakah No Register Jika Ada yang sama
	 */
	function cek_register(){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_registerx = $this->input->post('no_register');
			
			$num_rows = $this->Kibe_model->cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_registerx)->num_rows();
			echo $num_rows;
			}
	
	/**
	 * Cek apakah $id_kibe valid, agar tidak ganda
	 */
	function valid_no_register($no_register)
	{
		if ($this->Kibe_model->no_register($no_register) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "kibe dengan Kode $id_kibe sudah terdaftar");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $id_kibe valid, agar tidak ganda. Hanya untuk proses update data kibe
	 */
	function cek_register2()
	{
			$kd_bidang		= $this->session->userdata('kd_bidang');
			$kd_unit		= $this->session->userdata('kd_unit');
			$kd_sub			= $this->session->userdata('kd_sub_unit');
			$kd_upb			= $this->session->userdata('kd_upb');
			$kd_aset1 		= $this->session->userdata('Kd_Aset1');
			$kd_aset2 		= $this->session->userdata('Kd_Aset2');
			$kd_aset3 		= $this->session->userdata('Kd_Aset3');
			$kd_aset4 		= $this->session->userdata('Kd_Aset4');
			$kd_aset5 		= $this->session->userdata('Kd_Aset5');
			$no_register 	= $this->session->userdata('No_Register');
			
			$new_id			= $this->input->post('No_Register');
				
		if ($new_id === $no_register)
		{
			return TRUE;
		}
		else
		{
if($this->Kibe_model->valid_no_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register) === TRUE)
			{
				$this->form_validation->set_message('valid_no_register2', "kibe dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
	
/* usul penghapusan */
function usul_hapus(){
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Kibe_model->usul_hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}
		
	/* usul penggunaan */
function usul_guna(){
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Kibe_model->usul_guna($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}


	function cetak($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

		$get = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

   	    $data['title']	= "sample";


	    $data['nama_upb']	= $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();

	    $data['foto'] 	  	= $this->Kibe_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);

	    $get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

	    $data['nm_aset']	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/kibe/cetak',array_merge($get,$data,$get_ttd));
	}

	function last_id_hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

		$this->db->select_MAX('Kd_Id');
		$array_keys_values = $this->db->get_where('Ta_KIBEHapus',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
   		foreach ($array_keys_values->result() as $data) {
				 $result=$data->Kd_Id+1;
			}
        return $result;
	}

	function add_usul_hapus(){
		$data['form_action']   = site_url('kibe/usul_hapus');
		$data['option_alasan'] = $this->Chain_model->getAlasanList();
		$this->load->view("adminweb/chain/usulhapus",$data);
	}

	/* usul penghapusan */
	function proses_usul_hapus(){
			$kd_prov    =  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1    = $this->input->post('kd_aset1');
			$kd_aset2    = $this->input->post('kd_aset2');
			$kd_aset3    = $this->input->post('kd_aset3');
			$kd_aset4    = $this->input->post('kd_aset4');
			$kd_aset5    = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');

			$this->session->set_userdata('No_UP',$this->input->post('No_UP'));
			$this->session->set_userdata('Tgl_UP',$this->input->post('Tgl_UP'));

			$last_id = $this->last_id_hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);

			$arr_data = array(
						'Kd_Prov'     => $kd_prov,
						'Kd_Kab_Kota' => $kd_kab_kota,
						'Kd_Bidang'   => $kd_bidang,
						'Kd_Unit'     => $kd_unit,
						'Kd_Sub'      => $kd_sub,
						'Kd_UPB'      => $kd_upb,
						'Kd_Aset1'    => $kd_aset1,
						'Kd_Aset2'    => $kd_aset2,
						'Kd_Aset3'    => $kd_aset3,
						'Kd_Aset4'    => $kd_aset4,
						'Kd_Aset5'    => $kd_aset5,
						'No_Register' => $no_register,
						'Kd_Id'       => $last_id,
						'No_UP'       => $this->input->post('No_UP'),
						'Tgl_UP'      => $this->input->post('Tgl_UP'),
						'Kd_Alasan'   => $this->input->post('Kd_Alasan'),
						'Ket_Alasan'  => $this->input->post('Ket_Alasan'),
						'Status'      => 1,
						'Log_User'    => $this->session->userdata('username'),
						'Log_entry'   => date("Y-m-d H:i:s"));
			
			$sql = $this->Kibe_model->insert_usul_penghapusan($arr_data);	
			if ($sql)
				return true;
	}


function add_pindah_skpd(){
	if(!$this->general->privilege_check('RIWAYAT_KIB_E',ADD))
		    $this->general->no_access();
	$data['form_action']   = site_url('kibd/pindah_skpd');
	$data['option_bidang'] = $this->Model_chain->getBidangList();
	$this->load->view("adminweb/chain/pindah_skpd",$data); 

}	

function proses_pindah_skpd(){
			$kd_prov    =  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
		
			$kd_aset1         = $this->input->post('kd_aset1');
			$kd_aset2         = $this->input->post('kd_aset2');
			$kd_aset3         = $this->input->post('kd_aset3');
			$kd_aset4         = $this->input->post('kd_aset4');
			$kd_aset5         = $this->input->post('kd_aset5');
			$no_register      = $this->input->post('no_register');
			
			$tgl_pembukuan    = $this->input->post('tgl_dokumen');
			$no_dokumen       = $this->input->post('no_dokumen');
			$kd_bidang_tujuan = $this->input->post('tkd_bidang');
			$kd_unit_tujuan   = $this->input->post('tkd_unit');
			$kd_sub_tujuan    = $this->input->post('tkd_sub');
			$kd_upb_tujuan    = $this->input->post('tkd_upb'); 

			$last_id  = $this->last_Kd_Id($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
			$get      = $this->Kibe_model->getInfoKIB($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
			$last_reg = $this->Kibe_model->get_last_noregister($this->input->post('tkd_bidang'),$this->input->post('tkd_unit'),$this->input->post('tkd_sub'),$this->input->post('tkd_upb'),$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
			$dari     = getInfoUPB($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb);
			$arr_data = array(
						'Kd_Prov'       => $get->Kd_Prov,
						'Kd_Kab_Kota'   => $get->Kd_Kab_Kota,
						'Kd_Bidang'     => $kd_bidang_tujuan,
						'Kd_Unit'       => $kd_unit_tujuan,
						'Kd_Sub'        => $kd_sub_tujuan,
						'Kd_UPB'        => $kd_upb_tujuan,
						'Kd_Pemilik'    => $get->Kd_Pemilik,
						'Kd_Aset1'      => $get->Kd_Aset1,
						'Kd_Aset2'      => $get->Kd_Aset2,
						'Kd_Aset3'      => $get->Kd_Aset3,
						'Kd_Aset4'      => $get->Kd_Aset4,
						'Kd_Aset5'      => $get->Kd_Aset5,
						'No_Register'   => $last_reg,
						'Tgl_Perolehan' => $get->Tgl_Perolehan,
						'Tgl_Pembukuan' => $tgl_pembukuan,
						'Judul'         => $get->Judul,
						'Pencipta'      => $get->Pencipta,
						'Bahan'         => $get->Bahan,
						'Ukuran'        => $get->Ukuran,
						'Asal_usul'     => "Penyerahan",
						'Kondisi'       => $get->Kondisi,
						'Harga'         => $get->Harga,
						'Keterangan'    => $get->Keterangan.' - Penyerahan dari : '.$dari,
						'Nilai_Sisa'    => $get->Nilai_Sisa,
						'Masa_Manfaat'  => $get->Masa_Manfaat,
						'Kd_Penyusutan' => $get->Kd_Penyusutan,
						'Kd_Data'       => $get->Kd_Data,
						'Kd_KA'         => $get->Kd_KA,
						'Log_User'      => $this->session->userdata('username'),
						'Log_entry'     => date("Y-m-d H:i:s"));

			$this->Kibe_model->add($arr_data);

			$sql = "INSERT INTO Ta_KIBER (No_Urut,Kd_Riwayat,Kd_Id,Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Kd_Pemilik,Tgl_Dokumen,No_Dokumen,Tgl_Perolehan,Tgl_Pembukuan,Kondisi,Harga,Masa_Manfaat,Keterangan,Kd_Prov1,Kd_Kab_Kota1,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,No_Register1,Log_User,Log_entry,Tgl_Mulai,Tgl_Selesai)
					SELECT No_Urut,Kd_Riwayat,Kd_Id,Kd_Prov,Kd_Kab_Kota,$kd_bidang_tujuan,$kd_unit_tujuan,$kd_sub_tujuan,$kd_upb_tujuan,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,$last_reg, Kd_Pemilik,Tgl_Dokumen,No_Dokumen,Tgl_Perolehan,Tgl_Pembukuan,Kondisi,Harga,Masa_Manfaat,Keterangan,Kd_Prov1,Kd_Kab_Kota1,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,No_Register1,Log_User,Log_entry,Tgl_Mulai,Tgl_Selesai
					FROM Ta_KIBER WHERE Kd_Prov = '{$kd_prov}' AND Kd_Kab_Kota = '{$kd_kab_kota}' AND Kd_Bidang = '{$kd_bidang}' AND Kd_Unit = '{$kd_unit}' AND Kd_Sub = '{$kd_sub}' AND Kd_UPB = '{$kd_upb}' AND Kd_Aset1 = '{$kd_aset1}' AND Kd_Aset2 = '{$kd_aset2}' AND Kd_Aset3 = '{$kd_aset3}' AND Kd_Aset4 = '{$kd_aset4}' AND Kd_Aset5 = '{$kd_aset5}' AND No_Register = '{$no_register}'
					AND Kd_Riwayat <> 3";

			$this->db->query($sql);

			$arr = array(
					'Kd_Riwayat'   => 3,
					'Kd_Id'        => $last_id,
					'Kd_Prov'      => $kd_prov,
					'Kd_Kab_Kota'  => $kd_kab_kota,
					'Kd_Bidang'    => $kd_bidang,
					'Kd_Unit'      => $kd_unit,
					'Kd_Sub'       => $kd_sub,
					'Kd_UPB'       => $kd_upb,
					'Kd_Pemilik'   => 11,
					'Kd_Aset1'     => $kd_aset1,
					'Kd_Aset2'     => $kd_aset2,
					'Kd_Aset3'     => $kd_aset3,
					'Kd_Aset4'     => $kd_aset4,
					'Kd_Aset5'     => $kd_aset5,
					'No_Register'  => $no_register,
					'Tgl_Dokumen'  => $tgl_pembukuan,
					'No_Dokumen'   => $no_dokumen,
					'Kd_Prov1'     => $kd_prov,
					'Kd_Kab_Kota1' => $kd_kab_kota,
					'Kd_Bidang1'   => $kd_bidang_tujuan,
					'Kd_Unit1'     => $kd_unit_tujuan,
					'Kd_Sub1'      => $kd_sub_tujuan,
					'Kd_UPB1'      => $kd_upb_tujuan,
					'No_Register1' => $last_reg,
					'Log_User'     => $this->session->userdata('username'),
					'Log_entry'    => date("Y-m-d H:i:s"));

			$this->Kibe_model->add_riwayat($arr);

	}


}

/* End of file kibe.php */
/* Location: ./system/application/controllers/kibe.php */