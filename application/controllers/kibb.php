<?php
class Kibb extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('KIB_B',VIEW))
		    $this->general->no_access();
		$this->auth->clean_session('KIB_B');
		$this->load->model('Kibb_model', '', TRUE);
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
	var $title = ' KIB B. Peralatan & Mesin';
	
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
		$data['form_cari']	= site_url('kibb/cari');
		$data['link_kib']	= site_url('kibb/listupb');
		
		$data['header'] 	= "Pilih data SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']		= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('kibb/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('kibb/upb');
		
		$data['header'] 	= "Pilih data UPB".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('kibb/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data kibb
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
			$judul 	= "Semua Data KIB B. Peralatan & Mesin";

		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "WHERE $q LIKE '%$s%'";
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "WHERE $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";
		}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset = 0;	
		}else{
			$offset = $page;
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
		$kd_prov               = $this->session->userdata('kd_prov'); 
		$kd_kab                = $this->session->userdata('kd_kab_kota');  
		$kd_bidang             = $this->session->userdata('addKd_Bidang');  
		$kd_unit               = $this->session->userdata('addKd_Unit');  
		$kd_sub                = $this->session->userdata('addKd_Sub');  
		$kd_upb                = $this->session->userdata('addKd_UPB');  
		$tahun                 = $this->session->userdata('tahun_anggaran');
		$data['option_ruang']  = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query'] 		= $this->Kibb_model->get_page($this->limit, $offset, $where, $like2." AND aktif is not null");
		$num_rows 			= $this->Kibb_model->count_kib($where,$like2." AND aktif is not null")->Jumlah;
		$total 				= $this->Kibb_model->count_kib($where,$like2." AND aktif is not null")->Total;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab2'] 		= site_url('kibb/waiting/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3'] 		= site_url('kibb/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['riwayat'] 	= site_url('kibb/riwayat/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']   = pencarian_KIBB(); 
		
		if ($num_rows > 0)
		{
			$config['base_url']          = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}else{
			$data['message'] = 'Tidak ditemukan data KIB B !';
		}		
		$this->template->load('template','adminweb/kibb/kibb',$data);
	}
	
	
	/**
	 * Tampilkan semua data kibb
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
			$judul 	= "Semua Data KIB B. Peralatan & Mesin";
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
			$where = "Ta_KIB_B.Kd_Bidang=$kd_bidang AND Ta_KIB_B.Kd_Unit=$kd_unit AND Ta_KIB_B.Kd_Sub=$kd_sub";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "Ta_KIB_B.Kd_Bidang=$kb AND Ta_KIB_B.Kd_Unit=$ku AND Ta_KIB_B.Kd_Sub=$ks ";
		}else{
			$where = "Ta_KIB_B.Kd_Bidang=$kb AND Ta_KIB_B.Kd_Unit=$ku AND Ta_KIB_B.Kd_Sub=$ks AND  Ta_KIB_B.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$nmupb				= $this->Sub_unit_model->nama_skpd($kd_bidang,$kd_unit,$kd_sub);
		$data['query'] 		= $this->Kibb_model->get_page($this->limit, $offset, $where, $like2);
		$num_rows 			= $this->Kibb_model->count_kib($where,$like2)->Jumlah;
		$total 				= $this->Kibb_model->count_kib($where,$like2)->Total;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' Seluruh UPB | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_skpd']	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab1'] 		= site_url('kibb/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab2'] 		= site_url('kibb/waiting/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] 	= pencarian_KIBB();
		
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
			$data['message'] = 'Tidak ditemukan data KIB B  yang belum di periksa!';
		}		
		$this->template->load('template','adminweb/kibb/kibb_skpd',$data);
	}
	
	
	/**
	 * Tampilkan semua data kibb
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
			$judul 	= "Semua Data KIB B. Peralatan & Mesin";
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
		$data['query'] 		= $this->Kibb_model->get_page($this->limit, $offset, $where, $like2." AND aktif is null");
		$num_rows 			= $this->Kibb_model->count_kib($where,$like2." AND aktif is null")->Jumlah;
		$total 				= $this->Kibb_model->count_kib($where,$like2." AND aktif is null")->Total;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' Belum di verifikasi | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_waiting']= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab1'] 		= site_url('kibb/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3'] 		= site_url('kibb/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] 	= pencarian_KIBB();
		
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
			$data['message'] = 'Tidak ditemukan data KIB B  yang belum di periksa!';
		}		
		$this->template->load('template','adminweb/kibb/kibb_nonaktif',$data);
	}


	/**
	 * Tampilkan semua data kibb
	 */
	function riwayat($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('RIWAYAT_KIB_B',VIEW))
		    $this->general->no_access();

		if ($this->session->userdata('lvl') != '03'){
			$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		}else{
			$kd_bidang 	=  $this->session->userdata('kd_bidang');
			$kd_unit 	=  $this->session->userdata('kd_unit');
			$kd_sub 	=  $this->session->userdata('kd_sub_unit');
			$kd_upb 	=  $this->session->userdata('kd_upb');
		}
	
		$r 		= $this->session->userdata('riwayat');
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
			$like 	= "AND Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND Tgl_Dokumen BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Riwayat KIB B. Peralatan dan Mesin";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_Pembukuan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		if (!empty($r)){
			$like 	.= " AND Ta_KIBBR.Kd_Riwayat = '{$r}'";
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
			$where = "AND Ta_KIBBR.Kd_Bidang=$kd_bidang AND Ta_KIBBR.Kd_Unit=$kd_unit AND Ta_KIBBR.Kd_Sub=$kd_sub AND Ta_KIBBR.Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND Ta_KIBBR.Kd_Bidang=$kb AND Ta_KIBBR.Kd_Unit=$ku AND Ta_KIBBR.Kd_Sub=$ks AND Ta_KIBBR.Kd_UPB=$kd_upb";
		}else{
			$where = "AND Ta_KIBBR.Kd_Bidang=$kb AND Ta_KIBBR.Kd_Unit=$ku AND Ta_KIBBR.Kd_Sub=$ks AND Ta_KIBBR.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query'] 		= $this->Kibb_model->get_riwayat($this->limit, $offset, $where, $like);
		$num_rows 			= $this->Kibb_model->count_riwayat($where,$like)->num_rows();
		$total 				= $this->Kibb_model->total_riwayat($where,$like);
		$harga_total		= number_format($total,0,",",".");
		
		$data['header']      = ' Riwayat '.$this->title.' '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_waiting'] = $num_rows;
		
		$data['offset']      = $offset;
		$data['form_cari']   = current_URL();
		$data['form_action']	= site_url('kibb/set');
		$data['form_filter']	= site_url('kibb/filter');
		
		$data['tab1']        = site_url('kibb/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3']        = site_url('kibb/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kib'] 	 	 = site_url('kibb/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);

	
		$data['option_q']    =  pencarian_KIBB();

		$data['riwayat'] 	= $this->Model_chain->getRiwayatList();
		
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
		$this->template->load('template','adminweb/kibb/kibb_riwayat',$data);
	}

	/**
	 * Pindah ke halaman tambah riwayat
	 */
	function addriwayat($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('RIWAYAT_KIB_B',ADD))
		    $this->general->no_access();

		$get = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
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
		$data['form_action']  = site_url('kibb/save_riwayat');
		$data['link']         = array('link_back' => anchor('kibb','kembali', array('class' => 'back')));
		
		$data['option_q']     = $this->Model_chain->getRiwayatList();
		
		$data['option_ruang'] = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun); 
		
		$nmupb                = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['namaaset']     = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		
		$data['header']       = $this->title.' ('.$nmupb.')';
		$this->template->load('template','adminweb/kibb/kibb_addriwayat',array_merge($data,$get));
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
					'Kd_Riwayat'   => $this->input->post('Kd_Riwayat'),
					'Kd_Id'        => $last_id,
					'Kd_Prov'      => $this->session->userdata('rKd_Prov'),
					'Kd_Kab_Kota'  => $this->session->userdata('rKd_Kab_Kota'),
					'Kd_Bidang'    => $this->session->userdata('rKd_Bidang'),
					'Kd_Unit'      => $this->session->userdata('rKd_Unit'),
					'Kd_Sub'       => $this->session->userdata('rKd_Sub'),
					'Kd_UPB'       => $this->session->userdata('rKd_UPB'),
					'Kd_Pemilik'   => $this->session->userdata('rKd_Pemilik'),
					'Kd_Aset1'     => $this->session->userdata('rKd_Aset1'),
					'Kd_Aset2'     => $this->session->userdata('rKd_Aset2'),
					'Kd_Aset3'     => $this->session->userdata('rKd_Aset3'),
					'Kd_Aset4'     => $this->session->userdata('rKd_Aset4'),
					'Kd_Aset5'     => $this->session->userdata('rKd_Aset5'),
					'No_Register'  => $this->session->userdata('rNo_Register'),
					'Tgl_Dokumen'  => $this->input->post('Tgl_Dokumen'),
					'No_Dokumen'   => $this->input->post('No_Dokumen'),
					'Kondisi'      => $this->input->post('Kondisi'),
					'Harga'        => $this->input->post('Harga'),
					'Kd_Ruang'     => $this->input->post('Kd_Ruang'),
					'Tgl_Mulai'    => $this->input->post('Tgl_Mulai'),
					'Tgl_Selesai'  => $this->input->post('Tgl_Selesai'),
					'Masa_Manfaat' => $mf,
					'Keterangan'   => $this->input->post('Keterangan'),
					'Kd_Prov1'     => $this->session->userdata('rKd_Prov'),
					'Kd_Kab_Kota1' => $this->session->userdata('rKd_Kab_Kota'),
					'Kd_Bidang1'   => $this->input->post('kd_bidang'),
					'Kd_Unit1'     => $this->input->post('kd_unit'),
					'Kd_Sub1'      => $this->input->post('kd_sub_unit'),
					'Kd_UPB1'      => $this->input->post('kd_upb'),
					'Log_User'     => $this->session->userdata('username'),
					'Log_entry'    => date("Y-m-d H:i:s"));
			
			
			/*print_r($arr); exit();*/

			$sql = $this->Kibb_model->add_riwayat($arr);
			
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB berhasil ditambah!, Silahkan tunggu verifikasi oleh admin');
				redirect($this->session->userdata('last_url'));
			}else{		
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB Gagal ditambah!');
				redirect($this->session->userdata('last_url'));
				}	
	}
	
	public function set()
	{

			$session_data = array(
				'q'        => $this->input->post('q'),
				's'        => clean_text($this->input->post('s')),
				'tanggal1' => $this->input->post('tanggal1'),
				'tanggal2' => $this->input->post('tanggal2')
			);
			$this->session->set_userdata($session_data);

			header('location:'.$this->session->userdata('curl'));
	}

	public function filter()
	{
			$session_data = array(
				'riwayat'  => $this->input->post('riwayat')
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
	 * Pindah ke halaman update kibb
	 */
	function lihat($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'kibb > Update';
		$data['form_action']	= site_url('kibb/update_process');
		$data['link'] 			= array('link_back' => anchor('kibb','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;
		
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();

		$jumlah = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$this->session->set_userdata('last_url', current_url());
				$kibb = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
													  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row();
													  
				$namaaset	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
													  

				$this->session->set_userdata('Kd_Aset1', $kibb->Kd_Aset1);
				$this->session->set_userdata('Kd_Aset2', $kibb->Kd_Aset2);
				$this->session->set_userdata('Kd_Aset3', $kibb->Kd_Aset3);
				$this->session->set_userdata('Kd_Aset4', $kibb->Kd_Aset4);
				$this->session->set_userdata('Kd_Aset5', $kibb->Kd_Aset5);
				$this->session->set_userdata('No_Register', $kibb->No_Register);
				
				$data['default']['Kd_Prov']       = $kibb->Kd_Prov;
				$data['default']['Kd_Kab_Kota']   = $kibb->Kd_Kab_Kota;				
				$data['default']['Kd_Bidang']     = $kibb->Kd_Bidang;  
				$data['default']['Kd_Unit']       = $kibb->Kd_Unit;  
				$data['default']['Kd_Sub']        = $kibb->Kd_Sub;  
				$data['default']['Kd_UPB']        = $kibb->Kd_UPB;    
				$data['default']['Kd_Aset1']      = $kibb->Kd_Aset1;  
				$data['default']['Kd_Aset2']      = $kibb->Kd_Aset2;  
				$data['default']['Kd_Aset3']      = $kibb->Kd_Aset3;  
				$data['default']['Kd_Aset4']      = $kibb->Kd_Aset4;  
				$data['default']['Kd_Aset5']      = $kibb->Kd_Aset5;  
				$data['default']['Nm_Aset5']      = $namaaset;  
				$data['default']['No_Register']   = $kibb->No_Register;  
				$data['default']['Kd_Ruang']      = $kibb->Kd_Ruang;
				$data['default']['Kd_Pemilik']    = $kibb->Kd_Pemilik;  
				$data['default']['Merk']          = $kibb->Merk;  
				$data['default']['Type']          = $kibb->Type;  
				$data['default']['Bahan']         = $kibb->Bahan;  
				$data['default']['Tgl_Perolehan'] = $kibb->Tgl_Perolehan;  
				$data['default']['Tgl_Pembukuan'] = $kibb->Tgl_Pembukuan; 
				
				$data['default']['Nomor_Pabrik']  = $kibb->Nomor_Pabrik; 
				$data['default']['Nomor_Rangka']  = $kibb->Nomor_Rangka; 
				$data['default']['Nomor_Mesin']   = $kibb->Nomor_Mesin; 
				$data['default']['Nomor_Polisi']  = $kibb->Nomor_Polisi; 
				$data['default']['Nomor_BPKB']    = $kibb->Nomor_BPKB;
				$data['default']['Pemakai']       = $kibb->Pemakai; 
				
				$data['default']['Asal_usul']     = $kibb->Asal_usul;  
				$data['default']['CC']            = $kibb->CC;  
				$data['default']['Kondisi']       = $kibb->Kondisi;  
				$data['default']['Harga']         = $kibb->Harga;
				$data['default']['LastHarga']     = $kibb->LastHarga;  
				$data['default']['Keterangan']    = $kibb->Keterangan;  
				$data['default']['Nilai_Sisa']    = $kibb->Nilai_Sisa;  
				$data['default']['Masa_Manfaat']  = $kibb->Masa_Manfaat; 
				
				$data['query'] 		= $this->Kibb_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
								
				$data['riwayat'] 	= $this->Kibb_model->get_riwayat_barang($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);

				$this->template->load('template','adminweb/kibb/kibb_lihat',$data);				
		}else{
			echo "tidak ada data";	
		}
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
			$judul 	= "Semua Data KIB B. Peralatan & Mesin";
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
			$d['data_view'] = $this->db->query("select * from Ta_KIB_B inner join Ref_Rek_Aset5 on 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
Ta_KIB_B.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIB_B.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIB_B.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where2 $like2");			
		}elseif ($arg[5] == 'upb' ) {
			$d['data_view'] = $this->db->query("select * from Ta_KIB_B inner join Ref_Rek_Aset5 on 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
Ta_KIB_B.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIB_B.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIB_B.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like2 AND aktif is not null");			
		}else{
			$d['data_view'] = $this->db->query("select * from Ta_KIB_B inner join Ref_Rek_Aset5 on 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
Ta_KIB_B.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIB_B.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIB_B.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like2 AND aktif is null");	
			}
		
		$this->load->view('adminweb/kibb/export',$d);


	}
	
	function last_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('No_Id');
			$array_keys_values = $this->db->get_where('Ta_FotoB',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
			'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
       		foreach ($array_keys_values->result() as $data) {
					 $result=$data->No_Id+1;
				}
        return $result;
	}

	function last_Kd_Id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('Kd_Id');
			$array_keys_values = $this->db->get_where('Ta_KIBBR',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
			'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
       		foreach ($array_keys_values->result() as $data) {
					 $result=$data->Kd_Id+1;
				}
        return $result;
	}

	function last_id_hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('Kd_Id');
			$array_keys_values = $this->db->get_where('Ta_KIBBHapus',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
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
					'name'     =>$data['name'][$i],
					'type'     =>$data['type'][$i],
					'tmp_name' =>$data['tmp_name'][$i],
					'error'    =>$data['error'][$i],
					'size'     =>$data['size'][$i],
				);
			}
			
			$no=0;
			foreach($data2 as $row)
			{
				$config['upload_path']   = './assets/uploads_kibb';
				$config['allowed_types'] = 'gif|jpg||jpeg|png|bmp';
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
						$this->Kibb_model->insert($foto);
						
					echo img(array(
						'src'=>base_url("assets/uploads_kibb/$image_data[file_name]"),
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

    $row = $this->db->get_where('Ta_FotoB',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id))->row();

     unlink("./assets/uploads_kibb/$row->Nama_foto");

    $this->db->delete('Ta_FotoB', array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id));

}
	
		
	/**
	 * Hapus data KIB B
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('KIB_B','remove'))
		    $this->general->no_access();

		$this->Kibb_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('kibb');
	}
	
	/**
	 * Menghapus dengan ajax post
	 */
	function ajax_hapus(){

			if(!$this->general->privilege_check('KIB_B',REMOVE))
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
			
		$this->Kibb_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								 $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}

	function hapus_riwayat(){
		if(!$this->general->privilege_check('RIWAYAT_KIB_B',REMOVE))
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

		$this->Kibb_model->hapus_riwayat($kd_riwayat,$kd_id,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								 $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}


function add_pindah_ruang(){
	$kd_prov              = $this->session->userdata('kd_prov'); 
	$kd_kab               = $this->session->userdata('kd_kab_kota');  
	$kd_bidang            = $this->session->userdata('addKd_Bidang');  
	$kd_unit              = $this->session->userdata('addKd_Unit');  
	$kd_sub               = $this->session->userdata('addKd_Sub');  
	$kd_upb               = $this->session->userdata('addKd_UPB');  
	$tahun                = $this->session->userdata('tahun_anggaran');
	$data['option_ruang'] = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
	
	$data['form_action']  = site_url('kibb/proses_pindah_ruang');
	$this->load->view("adminweb/chain/pindah_ruang",$data); 

}	

	/* pindah ruang */
function proses_pindah_ruang(){
	
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


			$kibb = array(
					'Kd_Ruang'	=> $this->input->post('kd_ruang')
					);
		

			$this->Kibb_model->update_kib($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kibb);	

	}


function form_edit_spec($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){
	
	$get = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();

	$tahun                = $this->session->userdata('tahun_anggaran');
	$data['option_ruang'] = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
	
	$this->load->view("adminweb/kibb/form_edit_spec",array_merge($data,$get)); 

}	

	/* pindah ruang */
function proses_edit_spec(){
	
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


			$arr = array(
					'Kd_Ruang'     => $this->input->post('Kd_Ruang'),
					'Kd_Pemilik'   => $this->input->post('Kd_Pemilik'),
					'Merk'         => $this->input->post('Merk'),
					'Type'         => $this->input->post('Type'),
					'CC'           => $this->input->post('CC'),
					'Bahan'        => $this->input->post('Bahan'),
					'Nomor_Pabrik' => $this->input->post('Nomor_Pabrik'),
					'Nomor_Rangka' => $this->input->post('Nomor_Rangka'),
					'Nomor_Mesin'  => $this->input->post('Nomor_Mesin'),
					'Nomor_Polisi' => $this->input->post('Nomor_Polisi'),
					'Nomor_BPKB'   => $this->input->post('Nomor_BPKB'),
					'Keterangan'   => $this->input->post('Keterangan')
					);

			$this->Kibb_model->update_kib($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$arr);	

	}
	
	
	/* usul penggunaan */
function usul_guna(){
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
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Kibb_model->usul_guna($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}
	
	/**
	 * post ajax aktifkan data
	 */
	function aktifkan(){
			$kd_prov	 =  $this->session->userdata('kd_prov');
			$kd_kab_kota =  $this->session->userdata('kd_kab_kota');
			
			$data = array('aktif' => $this->session->userdata('id_user'));

			$kd_bidang   =  $this->input->post('kd_bidang');
			$kd_unit     =  $this->input->post('kd_unit');
			$kd_sub      =  $this->input->post('kd_sub');
			$kd_upb      =  $this->input->post('kd_upb');	
			
			$kd_aset1    = $this->input->post('kd_aset1');
			$kd_aset2    = $this->input->post('kd_aset2');
			$kd_aset3    = $this->input->post('kd_aset3');
			$kd_aset4    = $this->input->post('kd_aset4');
			$kd_aset5    = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		if ($this->session->userdata('lvl') == 01){
			
			$this->Kibb_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
									 	 $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data);	
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
				$data = array('Kd_KA'			=> "1");
			}else{
				$data = array('Kd_KA'			=> '0');
			}
			
			$kd_bidang   =  $this->input->post('kd_bidang');
			$kd_unit     =  $this->input->post('kd_unit');
			$kd_sub      =  $this->input->post('kd_sub');
			$kd_upb      =  $this->input->post('kd_upb');	
			
			$kd_aset1    = $this->input->post('kd_aset1');
			$kd_aset2    = $this->input->post('kd_aset2');
			$kd_aset3    = $this->input->post('kd_aset3');
			$kd_aset4    = $this->input->post('kd_aset4');
			$kd_aset5    = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Kibb_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
									 $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data);	
		
	}
	
	
	/**
	 * Pindah ke halaman tambah kibb
	 */
	function add()
	{	
		if(!$this->general->privilege_check('KIB_B',ADD))
		    $this->general->no_access();

		$data['default']['Kd_Bidang'] 			= $this->session->userdata('addKd_Bidang');
		$data['default']['Kd_Unit'] 			= $this->session->userdata('addKd_Unit');
		$data['default']['Kd_Sub'] 				= $this->session->userdata('addKd_Sub');
		$data['default']['Kd_UPB'] 				= $this->session->userdata('addKd_UPB');
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB B > Tambah Data';
		$data['form_action']	= site_url('kibb/add_process');
		$data['link'] 			= array('link_back' => anchor('kibb','kembali', array('class' => 'back'))
										);
										
		$nmupb					= $this->Ref_upb_model->nama_upb($this->session->userdata('addKd_Bidang'),
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
		$this->template->load('template','adminweb/kibb/kibb_addform',$data);
	}
	
	/**
	 * Proses tambah data kibb
	 */
	function add_process()
	{	
			$this->auth->restrict();
			$jp  = $this->input->post('jmlperc');
			$reg = $this->input->post('No_Register');
			$d   =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			$masa_manfaat 	= $d->Masa_Manfaat;
			$metode			= 1;
			if ($jp !=""){
						$no=0;	
						for($i=0; $i<$jp; $i++){
						$kibb = array(
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
									'No_Register'   => $reg,
									'Kd_Ruang'      => $this->input->post('Kd_Ruang'),
									'Tgl_Perolehan' => $this->input->post('Tgl_Perolehan'),
									'Tgl_Pembukuan' => $this->input->post('Tgl_Pembukuan'),
									'Merk'          => $this->input->post('Merk'),
									'Type'          => $this->input->post('Type'),
									'Bahan'         => $this->input->post('Bahan'),
									'Nomor_Pabrik'  => $this->input->post('Nomor_Pabrik'),
									'Nomor_Rangka'  => $this->input->post('Nomor_Rangka'),
									'Nomor_Mesin'   => $this->input->post('Nomor_Mesin'),
									'Nomor_BPKB'    => $this->input->post('Nomor_BPKB'),
									'Nomor_Polisi'  => $this->input->post('Nomor_Polisi'),
									'Asal_usul'     => $this->input->post('Asal_usul'),
									'CC'            => $this->input->post('CC'),
									'Kondisi'       => $this->input->post('Kondisi'),
									'Harga'         => $this->input->post('Harga'),
									'Keterangan'    => $this->input->post('Keterangan'),
									'Nilai_Sisa'    => $this->input->post('Nilai_Sisa'),
									'Masa_Manfaat'  => $masa_manfaat,
									'Kd_Penyusutan' => $metode,
									'Kd_Data'       => 1,
									'Kd_KA'         => $this->input->post('Kd_KA'),
									'Pemakai'       => $this->input->post('Pemakai'),
									'Jumlah_Roda'   => $this->input->post('Jumlah_Roda'),
									'Log_User'      => $this->session->userdata('username'),
									'Log_entry'     => date("Y-m-d H:i:s"));
								
							// var_dump($kibb); exit();	

							$sql = $this->Kibb_model->add($kibb);
							$reg++;
							$no++;
						}
						
						if ($sql){
							$this->session->set_flashdata('message', $no.' data KIB B berhasil ditambah!');
							redirect('kibb/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
						}else{
							$this->session->set_flashdata('message', 'Satu data KIB B Gagal ditambah!');
							redirect('kibb/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
							}
						
						}else{
						$kibb = array(
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
									'Merk'          => $this->input->post('Merk'),
									'Type'          => $this->input->post('Type'),
									'Bahan'         => $this->input->post('Bahan'),
									'Nomor_Pabrik'  => $this->input->post('Nomor_Pabrik'),
									'Nomor_Rangka'  => $this->input->post('Nomor_Rangka'),
									'Nomor_Mesin'   => $this->input->post('Nomor_Mesin'),
									'Nomor_BPKB'    => $this->input->post('Nomor_BPKB'),
									'Nomor_Polisi'  => $this->input->post('Nomor_Polisi'),
									'Asal_usul'     => $this->input->post('Asal_usul'),
									'CC'            => $this->input->post('CC'),
									'Kondisi'       => $this->input->post('Kondisi'),
									'Harga'         => $this->input->post('Harga'),
									'Keterangan'    => $this->input->post('Keterangan'),
									'Nilai_Sisa'    => NULL,
									'Masa_Manfaat'  => $masa_manfaat,
									'Kd_Penyusutan' => $metode,
									'Kd_Data'       => 1,
									'Kd_KA'         => $this->input->post('Kd_KA'),
									'Pemakai'       => $this->input->post('Pemakai'),
									'Jumlah_Roda'   => $this->input->post('Jumlah_Roda'),
									'Log_User'      => $this->session->userdata('username'),
									'Log_entry'     => date("Y-m-d H:i:s"));
							
							// var_dump($kibb); exit();	

							$sql = $this->Kibb_model->add($kibb);
							if ($sql){
								$this->session->set_flashdata('message', 'Satu data KIB B berhasil ditambah!, Silahkan tunggu verifikasi oleh admin');
								redirect('kibb/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
							}else{
								$this->session->set_flashdata('message', 'Satu data KIB B Gagal ditambah!');
								redirect('kibb/waiting/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
								}
			}
					
	}
	
	/**
	 * Pindah ke halaman update kibb
	 */
	function update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{

		if(!$this->general->privilege_check('KIB_B',EDIT))
		    $this->general->no_access();

		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB B > Update';
		$data['form_action']	= site_url('kibb/update_process');
		$data['link'] 			= array('link_back' => anchor('kibb','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;

		$tahun = $this->session->userdata('tahun_anggaran');  
		$data['option_ruang'] = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);     
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();

		$jumlah = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->num_rows();

		if ($jumlah > 0){
			$kibb = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row();
												  
			$namaaset	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);											  

			$this->session->set_userdata('Kd_Aset1', $kibb->Kd_Aset1);  
			$this->session->set_userdata('Kd_Aset2', $kibb->Kd_Aset2);  
			$this->session->set_userdata('Kd_Aset3', $kibb->Kd_Aset3);  
			$this->session->set_userdata('Kd_Aset4', $kibb->Kd_Aset4);  
			$this->session->set_userdata('Kd_Aset5', $kibb->Kd_Aset5);  
			$this->session->set_userdata('No_Register', $kibb->No_Register);   
			
			$data['default']['Kd_Bidang']     = $kibb->Kd_Bidang;  
			$data['default']['Kd_Unit']       = $kibb->Kd_Unit;  
			$data['default']['Kd_Sub']        = $kibb->Kd_Sub;  
			$data['default']['Kd_UPB']        = $kibb->Kd_UPB;    
			$data['default']['Kd_Aset1']      = $kibb->Kd_Aset1;  
			$data['default']['Kd_Aset2']      = $kibb->Kd_Aset2;  
			$data['default']['Kd_Aset3']      = $kibb->Kd_Aset3;  
			$data['default']['Kd_Aset4']      = $kibb->Kd_Aset4;  
			$data['default']['Kd_Aset5']      = $kibb->Kd_Aset5;  
			$data['default']['Nm_Aset5']      = $namaaset;  
			$data['default']['No_Register']   = $kibb->No_Register;  
			$data['default']['Kd_Ruang']      = $kibb->Kd_Ruang;  
			$data['default']['Kd_Pemilik']    = $kibb->Kd_Pemilik;  
			$data['default']['Merk']          = $kibb->Merk;  
			$data['default']['Type']          = $kibb->Type;
			
			$data['default']['Nomor_Pabrik']  = $kibb->Nomor_Pabrik;
			$data['default']['Nomor_Rangka']  = $kibb->Nomor_Rangka;
			$data['default']['Nomor_Mesin']   = $kibb->Nomor_Mesin;
			$data['default']['Nomor_BPKB']    = $kibb->Nomor_BPKB;
			$data['default']['Nomor_Polisi']  = $kibb->Nomor_Polisi;
			
			
			$data['default']['Bahan']         = $kibb->Bahan;  
			$data['default']['Tgl_Perolehan'] = $kibb->Tgl_Perolehan;  
			$data['default']['Tgl_Pembukuan'] = $kibb->Tgl_Pembukuan;  
			$data['default']['Asal_usul']     = $kibb->Asal_usul;  
			$data['default']['CC']            = $kibb->CC;  
			$data['default']['Kondisi']       = $kibb->Kondisi;  
			$data['default']['Harga']         = $kibb->Harga;
			$data['default']['Pemakai']       = $kibb->Pemakai;  
			$data['default']['Jumlah_Roda']   = $kibb->Jumlah_Roda;
			$data['default']['Keterangan']    = $kibb->Keterangan;  
			$data['default']['Nilai_Sisa']    = $kibb->Nilai_Sisa;  
			$data['default']['Masa_Manfaat']  = $kibb->Masa_Manfaat;

			$data['default']['Kd_KA']  		  = $kibb->Kd_KA;    
								
				$this->template->load('template','adminweb/kibb/kibb_updateform',$data);				
		}else{
			redirect('kibb');	
		}
	}
	
	/**
	 * Proses update data kibb
	 */
	function update_process()
	{
			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			$masa_manfaat 	= $d->Masa_Manfaat;
			
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
			$kibb = array(
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
					'Merk'          => $this->input->post('Merk'),
					'Type'          => $this->input->post('Type'),
					'Bahan'         => $this->input->post('Bahan'),
					'Nomor_Pabrik'  => $this->input->post('Nomor_Pabrik'),
					'Nomor_Rangka'  => $this->input->post('Nomor_Rangka'),
					'Nomor_Mesin'   => $this->input->post('Nomor_Mesin'),
					'Nomor_BPKB'    => $this->input->post('Nomor_BPKB'),
					'Nomor_Polisi'  => $this->input->post('Nomor_Polisi'),
					'Asal_usul'     => $this->input->post('Asal_usul'),
					'CC'            => $this->input->post('CC'),
					'Kondisi'       => $this->input->post('Kondisi'),
					'Harga'         => $this->input->post('Harga'),
					'Pemakai'       => $this->input->post('Pemakai'),
					'Jumlah_Roda'   => $this->input->post('Jumlah_Roda'),
					'Keterangan'    => $this->input->post('Keterangan'),
					'Nilai_Sisa'    => $this->input->post('Nilai_Sisa'),
					'Masa_Manfaat'  => $masa_manfaat,
					'aktif'  		=> NULL,
					'Kd_KA'    		=> $this->input->post('Kd_KA'),
					'Log_User'      => $this->session->userdata('username'),
					'Log_entry'     => date("Y-m-d H:i:s"));

					$kd_bidang   = $this->input->post('Kd_Bidang');
					$kd_unit     = $this->input->post('Kd_Unit');
					$kd_sub      = $this->input->post('Kd_Sub');
					$kd_upb      = $this->input->post('Kd_UPB');
					
					$kd_aset1    = $this->session->userdata('Kd_Aset1');
					$kd_aset2    = $this->session->userdata('Kd_Aset2');
					$kd_aset3    = $this->session->userdata('Kd_Aset3');
					$kd_aset4    = $this->session->userdata('Kd_Aset4');
					$kd_aset5    = $this->session->userdata('Kd_Aset5');
					$no_register = $this->session->userdata('No_Register');
					$curl        = $this->session->userdata('curl');
						
			$sql = $this->Kibb_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,
												$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kibb);	
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
	        		
		$count = $this->Ref_rek_aset5_model->count_kibb($where);
		
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		
		$data1 = $this->Ref_rek_aset5_model->get_kibb($where, $sidx, $sord, $limit, $start)->result();
	
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
        $query = $this->Ref_rek_aset5_model->json_kibb($keyword); 
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
            $this->load->view('kibb/index',$data); 
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
			
       
$num_rows = $this->Kibb_model->get_last_noregister($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);		
		
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
			
			$num_rows = $this->Kibb_model->cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_registerx)->num_rows();
			echo $num_rows;
			}
	
	/**
	 * Cek apakah $id_kibb valid, agar tidak ganda
	 */
	function valid_no_register($no_register)
	{
		if ($this->Kibb_model->no_register($no_register) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "kibb dengan Kode $id_kibb sudah terdaftar");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $id_kibb valid, agar tidak ganda. Hanya untuk proses update data kibb
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
if($this->Kibb_model->valid_no_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register) === TRUE)
			{
				$this->form_validation->set_message('valid_no_register2', "kibb dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}


	function cetak($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

		$get = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

   	    $data['title']	= "sample";


	    $data['nama_upb']	= $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();

	    $data['foto'] 	  	= $this->Kibb_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);

	    $get_ttd = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row_array();
		if(!$get_ttd)
			show_error("Silahkan isi terlebih dahulu data penanggung jawab UPB");

	    $data['nm_aset']	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/kibb/cetak',array_merge($get,$data,$get_ttd));
	}


function add_usul_hapus(){
	$data['form_action']   = site_url('kibb/usul_hapus');
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
			
						
			$sql = $this->Kibb_model->insert_usul_penghapusan($arr_data);	
			if ($sql)
				return true;
	}


function add_pindah_upb(){
	if(!$this->general->privilege_check('RIWAYAT_KIB_B',ADD))
		    $this->general->no_access();
	$data['form_action']   = site_url('kibb/proses_pindah_upb');
	$data['option_bidang'] = $this->Model_chain->getBidangList();
	$this->load->view("adminweb/chain/pindah_upb",$data); 

}


function proses_pindah_upb(){
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
			
			$kd_bidang_tujuan = $this->input->post('tkd_bidang');
			$kd_unit_tujuan   = $this->input->post('tkd_unit');
			$kd_sub_tujuan    = $this->input->post('tkd_sub');
			$kd_upb_tujuan    = $this->input->post('tkd_upb'); 

			// $last_id  = $this->last_Kd_Id($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
			$last_reg = $this->Kibb_model->get_last_noregister($kd_bidang_tujuan,$kd_unit_tujuan,$kd_sub_tujuan,$kd_upb_tujuan,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
			$dari     = getInfoUPB($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb);
			$kibb = array(
				'Kd_Bidang'   => $kd_bidang_tujuan,
				'Kd_Unit'     => $kd_unit_tujuan,
				'Kd_Sub'      => $kd_sub_tujuan,
				'Kd_UPB'      => $kd_upb_tujuan,
				'No_Register' => $last_reg,
				'Kd_Ruang'    => null,
				'Keterangan'  => $get->Keterangan." - pindah dari UPB ".$dari
				);

			// var_dump($no_register); exit();
		
			$this->Kibb_model->update_kib($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kibb);
	}

function add_pindah_skpd(){
	if(!$this->general->privilege_check('RIWAYAT_KIB_B',ADD))
		    $this->general->no_access();
	$data['form_action']   = site_url('kibb/pindah_skpd');
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
			$get      = $this->Kibb_model->getInfoKIB($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
			$last_reg = $this->Kibb_model->get_last_noregister($this->input->post('tkd_bidang'),$this->input->post('tkd_unit'),$this->input->post('tkd_sub'),$this->input->post('tkd_upb'),$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
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
						'Merk'          => $get->Merk,
						'Type'          => $get->Type,
						'Bahan'         => $get->Bahan,
						'Nomor_Pabrik'  => $get->Nomor_Pabrik,
						'Nomor_Rangka'  => $get->Nomor_Rangka,
						'Nomor_Mesin'   => $get->Nomor_Mesin,
						'Nomor_BPKB'    => $get->Nomor_BPKB,
						'Nomor_Polisi'  => $get->Nomor_Polisi,
						'Asal_usul'     => "Penyerahan",
						'CC'            => $get->CC,
						'Kondisi'       => $get->Kondisi,
						'Harga'         => $get->Harga,
						'Keterangan'    => $get->Keterangan.' - Penyerahan dari : '.$dari,
						'Nilai_Sisa'    => $get->Nilai_Sisa,
						'Masa_Manfaat'  => $get->Masa_Manfaat,
						'Kd_Penyusutan' => $get->Kd_Penyusutan,
						'Kd_Data'       => $get->Kd_Data,
						'Kd_KA'         => $get->Kd_KA,
						'Pemakai'       => $get->Pemakai,
						'Jumlah_Roda'   => $get->Jumlah_Roda,
						'Log_User'      => $this->session->userdata('username'),
						'Log_entry'     => date("Y-m-d H:i:s"));
			

											
			$this->Kibb_model->add($arr_data);

			$sql = "INSERT INTO Ta_KIBBR (No_Urut,Kd_Riwayat,Kd_Id,Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Kd_Pemilik,Tgl_Dokumen,No_Dokumen,Tgl_Perolehan,Tgl_Pembukuan,Kondisi,Harga,Masa_Manfaat,Keterangan,Kd_Prov1,Kd_Kab_Kota1,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,No_Register1,Log_User,Log_entry,Tgl_Mulai,Tgl_Selesai)
					SELECT No_Urut,Kd_Riwayat,Kd_Id,Kd_Prov,Kd_Kab_Kota,$kd_bidang_tujuan,$kd_unit_tujuan,$kd_sub_tujuan,$kd_upb_tujuan,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,$last_reg, Kd_Pemilik,Tgl_Dokumen,No_Dokumen,Tgl_Perolehan,Tgl_Pembukuan,Kondisi,Harga,Masa_Manfaat,Keterangan,Kd_Prov1,Kd_Kab_Kota1,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,No_Register1,Log_User,Log_entry,Tgl_Mulai,Tgl_Selesai
					FROM Ta_KIBBR WHERE Kd_Prov = '{$kd_prov}' AND Kd_Kab_Kota = '{$kd_kab_kota}' AND Kd_Bidang = '{$kd_bidang}' AND Kd_Unit = '{$kd_unit}' AND Kd_Sub = '{$kd_sub}' AND Kd_UPB = '{$kd_upb}' AND Kd_Aset1 = '{$kd_aset1}' AND Kd_Aset2 = '{$kd_aset2}' AND Kd_Aset3 = '{$kd_aset3}' AND Kd_Aset4 = '{$kd_aset4}' AND Kd_Aset5 = '{$kd_aset5}' AND No_Register = '{$no_register}'
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

			$this->Kibb_model->add_riwayat($arr);

	}

	function update_riwayat($kd_riwayat,$kd_id,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('RIWAYAT_KIB_B',EDIT))
		    $this->general->no_access();

		$data['title']       = $this->title;
		$data['h2_title']    = 'RIWAYAT KIB B > Update';
		$data['form_action'] = site_url('kibb/update_riwayat_process');
		$data['link']        = array('link_back' => anchor('Kibb','kembali', array('class' => 'back'))
		);
		$data['option_q']    = $this->Model_chain->getRiwayatList();
		
		$nmupb               = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['namaaset']    = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		
		$data['header']      = $this->title.' ('.$nmupb.')';

		$dat = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$dat)
	        show_error("Tidak ada data!");
	    
	    // print_r($dat); exit();

	    $data['HargaKoreksi']      = $dat['HargaKoreksi'];

		$get = $this->Kibb_model->get_kibbr_by_id($kd_riwayat,$kd_id,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Tidak ada data!");

		$tahun 		= $this->session->userdata('tahun_anggaran');

		$this->session->set_userdata('rKd_Riwayat',$get['Kd_Riwayat']);
		$this->session->set_userdata('rKd_Id',$get['Kd_Id']);
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
		$this->session->set_userdata('rKd_Pemilik',$get['Kd_Pemilik']);
		$this->session->set_userdata('rHarga',$dat['HargaKoreksi']);

		$this->template->load('template','adminweb/kibb/kibb_updateriwayat',array_merge($data,$get));

	}


	function update_riwayat_process()
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


		// echo $this->input->post('Masa_Manfaat'); exit();

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
					'Kd_Riwayat'     => $this->input->post('Kd_Riwayat'),
					'Kd_Id'          => $last_id,
					'Kd_Prov'        => $this->session->userdata('rKd_Prov'),
					'Kd_Kab_Kota'    => $this->session->userdata('rKd_Kab_Kota'),
					'Kd_Bidang'      => $this->session->userdata('rKd_Bidang'),
					'Kd_Unit'        => $this->session->userdata('rKd_Unit'),
					'Kd_Sub'         => $this->session->userdata('rKd_Sub'),
					'Kd_UPB'         => $this->session->userdata('rKd_UPB'),
					'Kd_Pemilik'     => $this->session->userdata('rKd_Pemilik'),
					'Kd_Aset1'       => $this->session->userdata('rKd_Aset1'),
					'Kd_Aset2'       => $this->session->userdata('rKd_Aset2'),
					'Kd_Aset3'       => $this->session->userdata('rKd_Aset3'),
					'Kd_Aset4'       => $this->session->userdata('rKd_Aset4'),
					'Kd_Aset5'       => $this->session->userdata('rKd_Aset5'),
					'No_Register'    => $this->session->userdata('rNo_Register'),
					'Tgl_Dokumen'    => $this->input->post('Tgl_Dokumen'),
					'No_Dokumen'     => $this->input->post('No_Dokumen'),
					'Kondisi'        => $this->input->post('Kondisi'),
					'Harga'          => $this->input->post('Harga'),
					// 'Kd_Sub_Riwayat' => $this->input->post('Kd_Sub_Riwayat'),
					'Tgl_Mulai'      => $this->input->post('Tgl_Mulai'),
					'Tgl_Selesai'    => $this->input->post('Tgl_Selesai'),
					'Masa_Manfaat'   => $mf,
					'Keterangan'     => $this->input->post('Keterangan'),
					'Kd_Prov1'       => $this->session->userdata('rKd_Prov'),
					'Kd_Kab_Kota1'   => $this->session->userdata('rKd_Kab_Kota'),
					'Kd_Bidang1'     => $this->input->post('kd_bidang'),
					'Kd_Unit1'       => $this->input->post('kd_unit'),
					'Kd_Sub1'        => $this->input->post('kd_sub_unit'),
					'Kd_UPB1'        => $this->input->post('kd_upb'),
					'Log_User'       => $this->session->userdata('username'),
					'Log_entry'      => date("Y-m-d H:i:s"));
			
			
			// print_r($arr); exit();

			$sql = $this->Kibb_model->update_riwayat($arr);

			/*var_dump($sql); exit();*/
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB berhasil diupdate!, Silahkan tunggu verifikasi oleh admin');
				redirect($this->session->userdata('last_url'));
			}else{		
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB Gagal diupdate!');
				redirect($this->session->userdata('last_url'));
				}
				 
	}



}

/* End of file kibb.php */
/* Location: ./system/application/controllers/kibb.php */