<?php

class Kibf extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('KIB_F',VIEW))
		    $this->general->no_access();
		$this->auth->clean_session('KIB_F');
		
		$this->load->model('Kibc_model', '', TRUE);	
		$this->load->model('Ref_penyusutan_model', '', TRUE);
		
		$this->load->model('Kibf_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = ' KIB F. Konstruksi dalam pengerjaan';
	
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
		$data['form_cari']	= site_url('kibf/cari');
		$data['link_kib']	= site_url('kibf/listupb');
		
		$data['header'] 	= "Pilih data SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']		= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('kibf/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('kibf/upb');
		
		$data['header'] 	= "Pilih data UPB".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('kibf/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data kibf
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
			$judul 	= "Semua Data KIB F. Konstruksi dalam pengerjaan";
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
		$data['query'] 		= $this->Kibf_model->get_page($this->limit, $offset, $where, $like2." AND aktif is not null");
		$num_rows 			= $this->Kibf_model->count_kib($where,$like2." AND aktif is not null");
		$total 				= $this->Kibf_model->total_kib($where,$like2." AND aktif is not null");
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab2'] 		= site_url('kibf/waiting/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3'] 		= site_url('kibf/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] = array(''=>'- pilih pencarian -','Nm_Aset5'=>'Nama Aset','Alamat'=>'Alamat/Lokasi','Luas_M2'=>'Luas','Sertifikat_Tanggal'=>'Tanggal Sertifikat','Sertifikat_Nomor'=>'Nomor Sertifikat','Harga'=>'Harga','Keterangan'=>'Keterangan','all'=>'Semua Data');
		
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
			$data['message'] = 'Tidak ditemukan data KIB F !';
		}		
		$this->template->load('template','adminweb/kibf/kibf',$data);
	}
	
	
	/**
	 * Tampilkan semua data kibf
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
			$judul 	= "Semua Data KIB F. Konstruksi dalam pengerjaan";
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
			$where = "a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$nmupb				= $this->Sub_unit_model->nama_skpd($kd_bidang,$kd_unit,$kd_sub);
		$data['query'] 		= $this->Kibf_model->get_page($this->limit, $offset, $where, $like2);
		$num_rows 			= $this->Kibf_model->count_kib($where,$like2);
		$total 				= $this->Kibf_model->total_kib($where,$like2);
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' Seluruh UPB | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_skpd']	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab1'] 		= site_url('kibf/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab2'] 		= site_url('kibf/waiting/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] = array(''=>'- pilih pencarian -','Nm_Aset5'=>'Nama Aset','Alamat'=>'Alamat/Lokasi','Luas_M2'=>'Luas','Sertifikat_Tanggal'=>'Tanggal Sertifikat','Sertifikat_Nomor'=>'Nomor Sertifikat','Harga'=>'Harga','Keterangan'=>'Keterangan','all'=>'Semua Data');
		
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
			$data['message'] = 'Tidak ditemukan data KIB F!';
		}		
		$this->template->load('template','adminweb/kibf/kibf_skpd',$data);
	}
	
	
	/**
	 * Tampilkan semua data kibf
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
			$judul 	= "Semua Data KIB F. Konstruksi dalam pengerjaan";
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
		$data['query'] 		= $this->Kibf_model->get_page($this->limit, $offset, $where, $like2." AND aktif is null");
		$num_rows 			= $this->Kibf_model->count_kib($where,$like2." AND aktif is null");
		$total 				= $this->Kibf_model->total_kib($where,$like2." AND aktif is null");
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' Belum di verifikasi | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jml_waiting']= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['tab1'] 		= site_url('kibf/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['tab3'] 		= site_url('kibf/skpd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] = array(''=>'- pilih pencarian -','Nm_Aset5'=>'Nama Aset','Alamat'=>'Alamat/Lokasi','Luas_M2'=>'Luas','Sertifikat_Tanggal'=>'Tanggal Sertifikat','Sertifikat_Nomor'=>'Nomor Sertifikat','Harga'=>'Harga','Keterangan'=>'Keterangan','all'=>'Semua Data');
		
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
			$data['message'] = 'Tidak ditemukan data KIB F  yang belum di periksa!';
		}		
		$this->template->load('template','adminweb/kibf/kibf_nonaktif',$data);
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
	 * Pindah ke halaman update kibf
	 */
	function lihat($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$data['title'] 			= $this->title;
		$data['form_action']	= site_url('kibf/update_process');
		$data['header'] 		= $this->title;

		$jumlah = $this->Kibf_model->get_kibf_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->num_rows();
																				  										  									  
		if ($jumlah > 0){
			$this->session->set_userdata('last_url', current_url());
			$get = $this->Kibf_model->get_kibf_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
			
			$this->session->set_userdata('Kd_Aset1',$get['Kd_Aset1']);
			$this->session->set_userdata('Kd_Aset2',$get['Kd_Aset2']);
			$this->session->set_userdata('Kd_Aset3',$get['Kd_Aset3']);
			$this->session->set_userdata('Kd_Aset4',$get['Kd_Aset4']);
			$this->session->set_userdata('Kd_Aset5',$get['Kd_Aset5']);
			$this->session->set_userdata('No_Register',$get['No_Register']);
			
			$data['query'] 		= $this->Kibf_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
			
			$data['riwayat'] 	= $this->Kibf_model->get_riwayat_barang($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
			
			$this->template->load('template','adminweb/kibf/kibf_lihat',array_merge($data,$get));	
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
			$judul 	= "Semua Data KIB F. Konstruksi dalam pengerjaan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Perolehan BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}

		$where = "a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
		$where2 = "a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub";
		$where3 = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub";
		
		$d['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
		
		$arg = explode('/', $curl);
		if ($arg[5] == 'skpd' ) {
			$d['data_view'] = $this->db->query("select * from Ta_KIB_F inner join Ref_Rek_Aset5 on 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
a.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
a.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
a.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
a.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5
LEFT JOIN Ta_KIB_A ON a.Kd_Tanah1=Ta_KIB_A.Kd_Aset1
AND a.Kd_Tanah2=Ta_KIB_A.Kd_Aset2
AND a.Kd_Tanah3=Ta_KIB_A.Kd_Aset3
AND a.Kd_Tanah4=Ta_KIB_A.Kd_Aset4
AND a.Kd_Tanah5=Ta_KIB_A.Kd_Aset5
AND a.Kode_Tanah=Ta_KIB_A.No_Register WHERE a.Kd_Bidang=8");			
		}elseif ($arg[5] == 'upb' ) {
			$d['data_view'] = $this->db->query("select *,Ta_KIB_A.Luas_M2 from Ta_KIB_F inner join Ref_Rek_Aset5 on 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
a.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
a.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
a.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
a.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5
LEFT JOIN Ta_KIB_A ON a.Kd_Tanah1=Ta_KIB_A.Kd_Aset1
AND a.Kd_Tanah2=Ta_KIB_A.Kd_Aset2
AND a.Kd_Tanah3=Ta_KIB_A.Kd_Aset3
AND a.Kd_Tanah4=Ta_KIB_A.Kd_Aset4
AND a.Kd_Tanah5=Ta_KIB_A.Kd_Aset5
AND a.Kode_Tanah=Ta_KIB_A.No_Register WHERE $where $like2 AND aktif is not null");			
		}else{
			$d['data_view'] = $this->db->query("select *,Ta_KIB_A.Luas_M2 from Ta_KIB_F inner join Ref_Rek_Aset5 on 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
a.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
a.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
a.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
a.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5
LEFT JOIN Ta_KIB_A ON a.Kd_Tanah1=Ta_KIB_A.Kd_Aset1
AND a.Kd_Tanah2=Ta_KIB_A.Kd_Aset2
AND a.Kd_Tanah3=Ta_KIB_A.Kd_Aset3
AND a.Kd_Tanah4=Ta_KIB_A.Kd_Aset4
AND a.Kd_Tanah5=Ta_KIB_A.Kd_Aset5
AND a.Kode_Tanah=Ta_KIB_A.No_Register WHERE $where $like2 AND aktif is null");	
			}
		
		$this->load->view('adminweb/kibf/export',$d);


	}
	
	function last_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('No_Id');
			$array_keys_values = $this->db->get_where('Ta_FotoC',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
			'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
       		foreach ($array_keys_values->result() as $data) {
					 $result=$data->No_Id+1;
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
				$config['upload_path'] = './assets/uploads_kibf';
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
						$this->Kibf_model->insert($foto);
						
					echo img(array(
						'src'=>base_url("assets/uploads_kibf/$image_data[file_name]"),
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

    $row = $this->db->get_where('Ta_FotoC',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id))->row();

     unlink("./assets/uploads_kibf/$row->Nama_foto");

    $this->db->delete('Ta_FotoC', array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id));

}
	
		
	/**
	 * Hapus data Kib a
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('KIB_F','remove'))
		    $this->general->no_access();

		$this->Kibf_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('Kibf');
	}
	
	/**
	 * Menghapus dengan ajax post
	 */
	function ajax_hapus(){

		if(!$this->general->privilege_check('KIB_F','remove'))
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
			
		$this->Kibf_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}
	
function hapus_riwayat(){
		if(!$this->general->privilege_check('RIWAYAT_KIB_F',REMOVE))
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

		$this->Kibf_model->hapus_riwayat($kd_riwayat,$kd_id,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
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
			
			$register 	= $this->Kibf_model->get_last_noregister($this->input->post('tkd_bidang'),$this->input->post('tkd_unit'),$this->input->post('tkd_sub'),$this->input->post('tkd_upb'),$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);

	$kibf = array(
			'Kd_Bidang'				=> $this->input->post('tkd_bidang'),
			'Kd_Unit'				=> $this->input->post('tkd_unit'),
			'Kd_Sub'				=> $this->input->post('tkd_sub'),
			'Kd_UPB'				=> $this->input->post('tkd_upb'),
			'No_Register'			=> $register);
		

		$this->Kibf_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								  $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register,$kibf);	

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
			
		$this->Kibf_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
									 $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data);	
		}
	}
	
	
	/**
	 * Pindah ke halaman tambah kibf
	 */
	function add()
	{	
		if(!$this->general->privilege_check('KIB_F',ADD))
		    $this->general->no_access();

		$data['default']['Kd_Bidang'] 			= $this->session->userdata('addKd_Bidang');
		$data['default']['Kd_Unit'] 			= $this->session->userdata('addKd_Unit');
		$data['default']['Kd_Sub'] 				= $this->session->userdata('addKd_Sub');
		$data['default']['Kd_UPB'] 				= $this->session->userdata('addKd_UPB');
		
		$nmupb	= $this->Ref_upb_model->nama_upb($this->session->userdata('addKd_Bidang'),
															 $this->session->userdata('addKd_Unit'),
															 $this->session->userdata('addKd_Sub'),
															 $this->session->userdata('addKd_UPB'));
	
		$data['title'] 			= $this->title;
		$data['header'] 		= $this->title.' ('.$nmupb.')';
		$data['h2_title'] 		= 'KIB F > Tambah Data | '.$nmupb;
		
		$data['form_action']	= site_url('kibf/add_process');
		$data['link'] 			= array('link_back' => anchor('kibf','kembali', array('class' => 'back'))
										);
		
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();
		$this->template->load('template','adminweb/kibf/kibf_addform',$data);
	}
	
	/**
	 * Proses tambah data kibf
	 */
	function add_process()
	{
			$data = array(
						'Kd_Prov'          => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'      => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'        => $this->input->post('Kd_Bidang'),
						'Kd_Unit'          => $this->input->post('Kd_Unit'),
						'Kd_Sub'           => $this->input->post('Kd_Sub'),
						'Kd_UPB'           => $this->input->post('Kd_UPB'),
						'Kd_Aset1'         => $this->input->post('kd_aset1'),
						'Kd_Aset2'         => $this->input->post('kd_aset2'),
						'Kd_Aset3'         => $this->input->post('kd_aset3'),
						'Kd_Aset4'         => $this->input->post('kd_aset4'),
						'Kd_Aset5'         => $this->input->post('kd_aset5'),
						'No_Register'      => $this->input->post('No_Register'),
						'Kd_Pemilik'       => $this->input->post('Kd_Pemilik'),
						
						'Tgl_Perolehan'    => $this->input->post('Tgl_Perolehan'),
						'Tgl_Pembukuan'    => $this->input->post('Tgl_Pembukuan'),
						'Bertingkat_Tidak' => $this->input->post('Bertingkat_Tidak'),
						'Beton_tidak'      => $this->input->post('Beton_tidak'),
						'Panjang'          => $this->input->post('Panjang'),
						'Lebar'            => $this->input->post('Lebar'),
						'Luas_Lantai'      => $this->input->post('Luas_Lantai'),
						'Lokasi'           => $this->input->post('Lokasi'),
						'Dokumen_Tanggal'  => $this->input->post('Dokumen_Tanggal'),
						'Dokumen_Nomor'    => $this->input->post('Dokumen_Nomor'),
						'Status_Tanah'     => $this->input->post('Status_Tanah'),
						
						'Asal_usul'        => $this->input->post('Asal_usul'),
						'Kondisi'          => $this->input->post('Kondisi'),
						'Harga'            => $this->input->post('Harga'),
						
						'Keterangan'       => $this->input->post('Keterangan'),
						'Log_User'         => $this->session->userdata('username'),
						'Log_entry'        => date("Y-m-d H:i:s"),
						'ID_KDP'           => md5(time().rand())
					);

						
				// print_r($data); exit();
			
				$sql = $this->Kibf_model->add($data);
			
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data KIB F berhasil ditambah!, Silahkan tunggu verifikasi oleh admin');
				redirect('kibf/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB F Gagal ditambah!');
				redirect('kibf/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
				}	
	}
	
	/**
	 * Pindah ke halaman update kibf
	 */
	function update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('KIB_F',EDIT))
		    $this->general->no_access();

		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB F > Update';
		$data['form_action']	= site_url('kibf/update_process');
		$data['link'] 			= array('link_back' => anchor('Kibf','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;

		
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();

		$get = $this->Kibf_model->get_kibf_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
			show_error("Tidak ada data");

		$this->session->set_userdata('Kd_Aset1', $get['Kd_Aset1']);
		$this->session->set_userdata('Kd_Aset2', $get['Kd_Aset2']);
		$this->session->set_userdata('Kd_Aset3', $get['Kd_Aset3']);
		$this->session->set_userdata('Kd_Aset4', $get['Kd_Aset4']);
		$this->session->set_userdata('Kd_Aset5', $get['Kd_Aset5']);
		$this->session->set_userdata('No_Register', $get['No_Register']);
												  
		$data['namaaset']	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);											  
		
		$this->template->load('template','adminweb/kibf/kibf_updateform',array_merge($data,$get));
	}
	
	/**
	 * Proses update data kibf
	 */
	function update_process()
	{
			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab_kota=  $this->session->userdata('kd_kab_kota');
			
			$kibf = array(
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
						'Tgl_Perolehan'			=> $this->input->post('Tgl_Perolehan'),
						'Tgl_Pembukuan'			=> $this->input->post('Tgl_Pembukuan'),
						'Bertingkat_Tidak' 		=> $this->input->post('Bertingkat_Tidak'),
						'Beton_tidak' 			=> $this->input->post('Beton_tidak'),
						'Luas_Lantai' 			=> $this->input->post('Luas_Lantai'),
						'Lokasi' 				=> $this->input->post('Lokasi'),
						'Dokumen_Tanggal'		=> $this->input->post('Dokumen_Tanggal'),
						'Dokumen_Nomor' 		=> $this->input->post('Dokumen_Nomor'),
						'Status_Tanah' 			=> $this->input->post('Status_Tanah'),
						'Asal_usul'				=> $this->input->post('Asal_usul'),
						'Kondisi' 				=> $this->input->post('Kondisi'),
						'Harga'					=> $this->input->post('Harga'),
						'Keterangan'			=> $this->input->post('Keterangan'),
						'Log_User'				=> $this->session->userdata('username'),
						
						// 'ID_KDP'           		=> md5(time().rand()
							
						'Log_entry'				=> date("Y-m-d H:i:s")
					);

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
						
			$sql = $this->Kibf_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,
												$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kibf);	
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
	        		
		$count = $this->Ref_rek_aset5_model->count_kibc($where);
		
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		
		$data1 = $this->Ref_rek_aset5_model->get_kibc($where, $sidx, $sord, $limit, $start)->result();
	
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
        $query = $this->Ref_rek_aset5_model->json_kibf($keyword); 
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
            $this->load->view('kibf/index',$data); 
        }
	}
	
	
	/**
	 * Mendapatan Nomor Register
	 */
	function register(){
			$kd_bidang = $this->input->post('kd_bidang');
			$kd_unit   = $this->input->post('kd_unit');
			$kd_sub    = $this->input->post('kd_sub');
			$kd_upb    = $this->input->post('kd_upb');
			
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			
       
$num_rows = $this->Kibf_model->get_last_noregister($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);		
		
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
			
			$num_rows = $this->Kibf_model->cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_registerx)->num_rows();
			echo $num_rows;
			}
	
	/**
	 * Cek apakah $id_Kibf valid, agar tidak ganda
	 */
	function valid_no_register($no_register)
	{
		if ($this->Kibf_model->no_register($no_register) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "kibf dengan Kode $id_Kibf sudah terdaftar");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $id_Kibf valid, agar tidak ganda. Hanya untuk proses update data kibf
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
if($this->Kibf_model->valid_no_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register) === TRUE)
			{
				$this->form_validation->set_message('valid_no_register2', "kibf dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}

	function addriwayat($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('RIWAYAT_KIB_F',ADD))
		    $this->general->no_access();

		$get = $this->Kibf_model->get_kibf_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
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
		$this->session->set_userdata('rKd_Pemilik',$get['Kd_Pemilik']);
		$this->session->set_userdata('rHarga',$get['LastHarga']);

		$data['title']        = $this->title;
		$data['option_bidang'] = $this->Model_chain->getBidangList();
		$data['form_action']  = site_url('kibf/save_riwayat');
		
		$data['option_q']     = $this->Model_chain->getRiwayatList_F();
		
		$nmupb                = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['namaaset']     = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		
		$data['header']       = $this->title.' ('.$nmupb.')';
		$this->template->load('template','adminweb/kibf/kibf_addriwayat',array_merge($data,$get));
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

		$data = array(
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
							'Harga'       => $this->input->post('Harga'),
							'Keterangan'  => $this->input->post('Keterangan'),
							'Log_User'    => $this->session->userdata('username'),
							'Log_entry'   => date("Y-m-d H:i:s")
					);
			
			
			// print_r($arr); exit();

			$sql = $this->Kibf_model->add_riwayat($data);

			/*var_dump($sql); exit();*/
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB berhasil ditambah!, Silahkan tunggu verifikasi oleh admin');
				redirect($this->session->userdata('last_url'));
			}else{		
				$this->session->set_flashdata('message', 'Satu data Riwayat KIB Gagal ditambah!');
				redirect($this->session->userdata('last_url'));
				}
	}


	/*start*/
	function reklas($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$register)
	{

		if(!$this->general->privilege_check('KIB_F',VIEW))
		    $this->general->no_access();

		$get = $this->Kibf_model->get_kibf_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$register)->row_array();
		if(!$get)
	        show_error("Tidak ada data!");

		$data['title'] 			= "Posting Data KIB";
		$data['h2_title'] 		= 'KIB F > POSTING DATA KIB';
		$this->session->set_userdata('ID_KDP', $get['ID_KDP']);
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
		$this->session->set_userdata('rHarga',$get['LastHarga']);

		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();

		if($get['Kd_Aset1'] == 1){
			$view = "adminweb/kibf/posting/posting_kiba";
		}elseif($get['Kd_Aset1'] == 2){
			$view = "adminweb/kibf/posting/posting_kibb";
		}elseif($get['Kd_Aset1'] == 3){
			$view = "adminweb/kibf/posting/posting_kibc";
		}elseif($get['Kd_Aset1'] == 4){
			$view = "adminweb/kibf/posting/posting_kibd";
		}elseif($get['Kd_Aset1'] == 5){
			$view = "adminweb/kibf/posting/posting_kibe";
		}elseif($get['Kd_Aset1'] == 6){
			$view = "adminweb/kibf/posting/posting_kibf";
		}elseif($get['Kd_Aset1'] == 7){
			$view = "adminweb/kibf/posting/posting_kibl";
		}else{
			show_error("Tidak ada data!");
		}

		$this->template->load('template',$view,array_merge($data,$get));
	}

	function kiba_process()
	{


			$jp 	= $this->input->post('jmlperc');
			$reg 	= $this->input->post('No_Register');
			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			$masa_manfaat 	= $d->Masa_Manfaat;
			$metode			= 1;

			$no=0;	
			for($i=0; $i<$jp; $i++){
			$kiba = array(
						'Kd_Prov'            => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'        => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'          => $this->session->userdata('addKd_Bidang'),
						'Kd_Unit'            => $this->session->userdata('addKd_Unit'),
						'Kd_Sub'             => $this->session->userdata('addKd_Sub'),
						'Kd_UPB'             => $this->session->userdata('addKd_UPB'),
						'Kd_Pemilik'         => $this->input->post('Kd_Pemilik'),
						'Kd_Aset1'           => $this->input->post('kd_aset1'),
						'Kd_Aset2'           => $this->input->post('kd_aset2'),
						'Kd_Aset3'           => $this->input->post('kd_aset3'),
						'Kd_Aset4'           => $this->input->post('kd_aset4'),
						'Kd_Aset5'           => $this->input->post('kd_aset5'),
						'No_Register'        => $reg,
						'Tgl_Perolehan'      => $this->input->post('Tgl_Perolehan'),
						'Tgl_Pembukuan'      => $this->input->post('Tgl_Pembukuan'),
						'Alamat'             => $this->input->post('Alamat'),
						'Hak_Tanah'          => $this->input->post('Hak_Tanah'),
						'Sertifikat_Tanggal' => $this->input->post('Sertifikat_Tanggal'),
						'Sertifikat_Nomor'   => $this->input->post('Sertifikat_Nomor'),
						'Penggunaan'         => $this->input->post('Penggunaan'),
						'Asal_usul'          => $this->input->post('Asal_usul'),
						'Harga'              => $this->input->post('Harga'),
						'Keterangan'         => $this->input->post('Keterangan'),
						'Luas_M2'            => $this->input->post('Luas_M2'),
						'No_SP2D'         	 => $this->input->post('No_SP2D'),
						'Kd_Data'            => 1,
						'Kd_KA'              => 1,
						'Log_User'           => $this->session->userdata('username'),
						'Log_entry'          => date("Y-m-d H:i:s"));
						
			$sql = $this->Kiba_model->add($kiba);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB A berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Kibf_model->set_unpos(
							$this->session->userdata('kd_prov'),
							$this->session->userdata('kd_kab_kota'),
							$this->session->userdata('addKd_Bidang'),
							$this->session->userdata('addKd_Unit'),
							$this->session->userdata('addKd_Sub'),
							$this->session->userdata('addKd_UPB'),
							$this->input->post('kd_aset1'),
							$this->input->post('kd_aset2'),
							$this->input->post('kd_aset3'),
							$this->input->post('kd_aset4'),
							$this->input->post('kd_aset5'),
							$this->session->userdata('No_ID'));
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB A Gagal ditambah!');
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}


	function kibb_process()
	{	

			$jp 	= $this->input->post('jmlperc');
			$reg 	= $this->input->post('No_Register');
			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			$masa_manfaat 	= $d->Masa_Manfaat;
			$metode			= 1;

			$no=0;	
			for($i=0; $i<$jp; $i++){
			$kibb = array(
						'Kd_Prov'       => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'   => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'     => $this->session->userdata('addKd_Bidang'),
						'Kd_Unit'       => $this->session->userdata('addKd_Unit'),
						'Kd_Sub'        => $this->session->userdata('addKd_Sub'),
						'Kd_UPB'        => $this->session->userdata('addKd_UPB'),
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
						'Masa_Manfaat'  => $masa_manfaat,
						'Kd_Penyusutan' => $metode,
						'Kd_Data'       => 1,
						'Kd_KA'         => 1,
						'Pemakai'       => $this->input->post('Pemakai'),
						'Jumlah_Roda'   => $this->input->post('Jumlah_Roda'),
						'Log_User'      => $this->session->userdata('username'),
						'Log_entry'     => date("Y-m-d H:i:s"));

				// print_r($kibb); exit();

				$sql = $this->Kibb_model->add($kibb);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB B berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Kibf_model->set_unpos(
							$this->session->userdata('kd_prov'),
							$this->session->userdata('kd_kab_kota'),
							$this->session->userdata('addKd_Bidang'),
							$this->session->userdata('addKd_Unit'),
							$this->session->userdata('addKd_Sub'),
							$this->session->userdata('addKd_UPB'),
							$this->input->post('kd_aset1'),
							$this->input->post('kd_aset2'),
							$this->input->post('kd_aset3'),
							$this->input->post('kd_aset4'),
							$this->input->post('kd_aset5'),
							$this->session->userdata('No_ID'));
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB B Gagal ditambah!');
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
					
	}


	function kibc_process()
	{

			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'), $this->input->post('kd_aset2'), $this->input->post('kd_aset3'), $this->input->post('kd_aset4'), $this->input->post('kd_aset5'))->row(); $masa_manfaat 	= $d->Masa_Manfaat;
			$metode			= 1;

			$kibc = array(
						'Kd_Prov'          => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'      => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'        => $this->session->userdata('addKd_Bidang'),
						'Kd_Unit'          => $this->session->userdata('addKd_Unit'),
						'Kd_Sub'           => $this->session->userdata('addKd_Sub'),
						'Kd_UPB'           => $this->session->userdata('addKd_UPB'),
						'Kd_Pemilik'       => $this->input->post('Kd_Pemilik'),
						'Kd_Aset1'         => $this->input->post('kd_aset1'),
						'Kd_Aset2'         => $this->input->post('kd_aset2'),
						'Kd_Aset3'         => $this->input->post('kd_aset3'),
						'Kd_Aset4'         => $this->input->post('kd_aset4'),
						'Kd_Aset5'         => $this->input->post('kd_aset5'),
						'No_Register'      => $this->input->post('No_Register'),
						'Tgl_Perolehan'    => $this->input->post('Tgl_Perolehan'),
						'Tgl_Pembukuan'    => $this->input->post('Tgl_Pembukuan'),
						'Bertingkat_Tidak' => $this->input->post('Bertingkat_Tidak'),
						'Beton_tidak'      => $this->input->post('Beton_tidak'),
						'Luas_Lantai'      => $this->input->post('Luas_Lantai'),
						'Lokasi'           => $this->input->post('Lokasi'),
						'Dokumen_Tanggal'  => $this->input->post('Dokumen_Tanggal'),
						'Dokumen_Nomor'    => $this->input->post('Dokumen_Nomor'),
						'Status_Tanah'     => $this->input->post('Status_Tanah'),
						'Asal_usul'        => $this->input->post('Asal_usul'),
						'Kondisi'          => $this->input->post('Kondisi'),
						'Harga'            => $this->input->post('Harga'),
						'Masa_Manfaat'     => $masa_manfaat,
						'Kd_Penyusutan'    => $metode,
						'Keterangan'       => $this->input->post('Keterangan')." [source of KDP]",
						'Kd_Data'          => 1,
						'Kd_KA'            => 1,
						'Log_User'         => $this->session->userdata('username'),
						'Log_entry'        => date("Y-m-d H:i:s"),
						'ID_KDP'		   => $this->session->userdata('ID_KDP'));

			// print_r($kibc); exit();
						
			$sql = $this->Kibc_model->add($kibc);

			$last_id = $this->last_Kd_Id($this->session->userdata('rKd_Prov'), $this->session->userdata('rKd_Kab_Kota'), $this->session->userdata('rKd_Bidang'), $this->session->userdata('rKd_Unit'), $this->session->userdata('rKd_Sub'), $this->session->userdata('rKd_UPB'), $this->session->userdata('rKd_Aset1'), $this->session->userdata('rKd_Aset2'), $this->session->userdata('rKd_Aset3'), $this->session->userdata('rKd_Aset4'), $this->session->userdata('rKd_Aset5'), $this->session->userdata('rNo_Register'));
			$arr = array(
						'Kd_Riwayat'  => 19,
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
						'Tgl_Dokumen' => $this->input->post('Tgl_Pembukuan'),
						'No_Dokumen'  => "RECLASS BY SYSTEM",
						'Keterangan'  => "direklas Ke KIB C",
						'Log_User'    => $this->session->userdata('username'),
						'Log_entry'   => date("Y-m-d H:i:s"));
			
				$this->Kibf_model->add_riwayat($arr);

			if ($sql){
				$this->session->set_flashdata('message','data berhasil diposting ke KIB C !,Silahkan tunggu verifikasi oleh admin');
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'data gagal diposting ke KIB F!');
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	function kibd_process()	{


			$jp 	= $this->input->post('jmlperc');
			$reg 	= $this->input->post('No_Register');
			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			$masa_manfaat 	= $d->Masa_Manfaat;
			$metode			= 1;

			$no=0;	
			for($i=0; $i<$jp; $i++){
			$kibd = array(
						'Kd_Prov'          => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'      => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'       => $this->session->userdata('addKd_Bidang'),
						'Kd_Unit'         => $this->session->userdata('addKd_Unit'),
						'Kd_Sub'          => $this->session->userdata('addKd_Sub'),
						'Kd_UPB'          => $this->session->userdata('addKd_UPB'),
						'Kd_Pemilik'      => $this->input->post('Kd_Pemilik'),
						'Kd_Aset1'        => $this->input->post('kd_aset1'),
						'Kd_Aset2'        => $this->input->post('kd_aset2'),
						'Kd_Aset3'        => $this->input->post('kd_aset3'),
						'Kd_Aset4'        => $this->input->post('kd_aset4'),
						'Kd_Aset5'        => $this->input->post('kd_aset5'),
						'No_Register'     => $reg,
						'Kd_Pemilik'      => $this->input->post('Kd_Pemilik'),
						'Tgl_Perolehan'   => $this->input->post('Tgl_Perolehan'),
						'Konstruksi'      => $this->input->post('Konstruksi'),
						'Panjang'         => $this->input->post('Panjang'),
						'Lebar'           => $this->input->post('Lebar'),
						'Luas'            => $this->input->post('Luas'),
						'Lokasi'          => $this->input->post('Lokasi'),
						'Dokumen_Tanggal' => $this->input->post('Dokumen_Tanggal'),
						'Dokumen_Nomor'   => $this->input->post('Dokumen_Nomor'),
						'Status_Tanah'    => $this->input->post('Status_Tanah'),
						'Kd_Tanah1'       => $this->input->post('Kd_Tanah1'),
						'Kd_Tanah2'       => $this->input->post('Kd_Tanah2'),
						'Kd_Tanah3'       => $this->input->post('Kd_Tanah3'),
						'Kd_Tanah4'       => $this->input->post('Kd_Tanah4'),
						'Kd_Tanah5'       => $this->input->post('Kd_Tanah5'),
						'Kode_Tanah'      => $this->input->post('Kode_Tanah'),
						'Asal_usul'       => $this->input->post('Asal_usul'),
						'Kondisi'         => $this->input->post('Kondisi'),
						'Harga'           => $this->input->post('Harga'),
						'Nilai_Sisa'      => $this->input->post('Nilai_Sisa'),
						'Keterangan'      => $this->input->post('Keterangan'),
						'Tgl_Pembukuan'   => $this->input->post('Tgl_Pembukuan'),
						'Masa_Manfaat'    => $masa_manfaat,
						'Kd_Penyusutan'   => $metode,
						'Kd_Data'         => 1,
						'Kd_KA'           => 1,
						'Log_User'        => $this->session->userdata('username'),
						'Log_entry'       => date("Y-m-d H:i:s"));
						
			$sql = $this->Kibd_model->add($kibd);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB D berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Kibf_model->set_unpos(
							$this->session->userdata('kd_prov'),
							$this->session->userdata('kd_kab_kota'),
							$this->session->userdata('addKd_Bidang'),
							$this->session->userdata('addKd_Unit'),
							$this->session->userdata('addKd_Sub'),
							$this->session->userdata('addKd_UPB'),
							$this->input->post('kd_aset1'),
							$this->input->post('kd_aset2'),
							$this->input->post('kd_aset3'),
							$this->input->post('kd_aset4'),
							$this->input->post('kd_aset5'),
							$this->session->userdata('No_ID'));
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB D Gagal ditambah!');
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	function kibe_process()
	{


			$jp 	= $this->input->post('jmlperc');
			$reg 	= $this->input->post('No_Register');
			$d =  $this->Ref_penyusutan_model->get_masa_manfaat($this->input->post('kd_aset1'),
																$this->input->post('kd_aset2'),
																$this->input->post('kd_aset3'),
																$this->input->post('kd_aset4'),
																$this->input->post('kd_aset5'))->row();
			$masa_manfaat 	= $d->Masa_Manfaat;
			$metode			= 1;

			$no=0;	
			for($i=0; $i<$jp; $i++){
			$kibe = array(
							'Kd_Prov'       => $this->session->userdata('kd_prov'),
							'Kd_Kab_Kota'   => $this->session->userdata('kd_kab_kota'),
							'Kd_Bidang'     => $this->session->userdata('addKd_Bidang'),
							'Kd_Unit'       => $this->session->userdata('addKd_Unit'),
							'Kd_Sub'        => $this->session->userdata('addKd_Sub'),
							'Kd_UPB'        => $this->session->userdata('addKd_UPB'),
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
							'Judul'         => $this->input->post('Judul'),
							'Pencipta'      => $this->input->post('Pencipta'),
							'Bahan'         => $this->input->post('Bahan'),
							'Ukuran'        => $this->input->post('Ukuran'),
							'Asal_usul'     => $this->input->post('Asal_usul'),
							'Kondisi'       => $this->input->post('Kondisi'),
							'Harga'         => $this->input->post('Harga'),
							'Keterangan'    => $this->input->post('Keterangan'),
							'Masa_Manfaat'  => $masa_manfaat,
							'Kd_Penyusutan' => $metode,
							'Kd_Data'       => 1,
							'Kd_KA'         => 1,
							'Log_User'      => $this->session->userdata('username'));
				
				$sql = $this->Kibe_model->add($kibe);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB E berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Kibf_model->set_unpos(
							$this->session->userdata('kd_prov'),
							$this->session->userdata('kd_kab_kota'),
							$this->session->userdata('addKd_Bidang'),
							$this->session->userdata('addKd_Unit'),
							$this->session->userdata('addKd_Sub'),
							$this->session->userdata('addKd_UPB'),
							$this->input->post('kd_aset1'),
							$this->input->post('kd_aset2'),
							$this->input->post('kd_aset3'),
							$this->input->post('kd_aset4'),
							$this->input->post('kd_aset5'),
							$this->session->userdata('No_ID'));
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB E Gagal ditambah!');
				redirect('kibf/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}
	/*end*/

	function last_Kd_Id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('Kd_Id');
			$array_keys_values = $this->db->get_where('Ta_KIBFR',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
			'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
       		foreach ($array_keys_values->result() as $data) {
					 $result=$data->Kd_Id+1;
				}
        return $result;
	}

}

/* End of file kibf.php */
/* Location: ./system/application/controllers/kibf.php */