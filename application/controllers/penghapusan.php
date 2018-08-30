<?php

class Penghapusan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();

		if(!$this->general->privilege_check('PENGHAPUSAN',VIEW))
		    $this->general->no_access();

		$this->load->model('Penghapusan_model', '', TRUE);

		$this->load->model('Kibar_model', '', TRUE);
		$this->load->model('Kibbr_model', '', TRUE);
		$this->load->model('Kibcr_model', '', TRUE);
		$this->load->model('Kibdr_model', '', TRUE);
		$this->load->model('Kiber_model', '', TRUE);
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
	var $title = ' Data Usulan Penghapusan';
	
	/**
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('lvl') == 03){
			$this->kiba();
		}else{
			$this->get_data_upb();
		}
	}
	
	
	/**
	 * Tampilkan semua data skpd
	 */
	function get_data_upb()
	{
		$data['form_cari']	= site_url('penghapusan/cari');
		$data['link_kib']	= site_url('penghapusan/listupb');
		
		$data['header'] 	= "Pilih data SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']		= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('penghapusan/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('penghapusan/kiba');
		
		$data['header'] 	= "Pilih data UPB".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		$this->template->load('template','adminweb/listupb/upb',$data);
	}


	/**
	 * Tampilkan semua data kir
	 */
	function sk()
	{
		if(!$this->general->privilege_check('SK_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

		$q 		= $this->input->get('q', TRUE);
		$s 		= $this->input->get('s', TRUE);	

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		$data['form_cari']	= current_URL();		
		$data['title'] 		= $this->title;

		$data['option_q'] = array(''=>'- Pilih -');


	
	
		if (empty($q) && empty($s)){
			$like= '';
		}elseif (empty($q)){
			$like = array('Nm_UPB' => $s);
		}else{
			$like = array($q => $s);
		}

		$sql = $this->Penghapusan_model->getSK_Penghapusan($this->limit, $offset, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];
		
		$data['judul'] 	= $this->title;
		
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
			$data['message'] = 'Tidak ditemukan SK penghapusan!';
		}		
		$this->template->load('template','adminweb/penghapusan/sk',$data);
	}

	function add_sk()
	{	
		if(!$this->general->privilege_check('SK_PENGHAPUSAN',ADD))
		    $this->general->no_access();

		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Penghapusan > Tambah SK';
		$data['form_action']	= site_url('penghapusan/add_process');
				
		$data['header'] 		= $this->title;
		
		$this->template->load('template','adminweb/penghapusan/sk_addform',$data);
	}

	function add_process()
	{
			$arr_data = array(
						'Tahun'      => $this->input->post('Tahun'),
						'No_SK'      => $this->input->post('No_SK'),
						'Tgl_SK'     => $this->input->post('Tgl_SK'),
						'Keterangan' => $this->input->post('Keterangan'));

			// print_r($user); exit();
			
			$sql = $this->Penghapusan_model->add($arr_data);

			// var_dump($sql); exit();
			
			if ($sql){
				$this->session->set_flashdata('message', 'SK Penghapusan berhasil ditambah!');
				redirect('penghapusan/sk');
			}else{		
				$this->session->set_flashdata('message', 'SK Penghapusan Gagal ditambah!');
				redirect('penghapusan/sk');
				}	
	}

	function update($sk)
	{
		if(!$this->general->privilege_check('SK_PENGHAPUSAN',EDIT))
		    $this->general->no_access();

		$id = decrypt_url($sk);

		$get  = $this->Penghapusan_model->get_sk_by_id($id)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");
	        
		$this->session->set_userdata('id_sk_tmp', $get['No_SK']); 
		
		$data['header']      = "UPDATE SK PENGHAPUSAN";
		$data['title']       = "SK Penghapusan";
		
		$data['form_action'] = site_url('penghapusan/update_process');

		$this->template->load('template','adminweb/penghapusan/sk_updateform',array_merge($data,$get));
	}

	function update_process()
	{
		$data = $this->input->post(null,true);

		$sql = $this->Penghapusan_model->update($data);
		
		if ($sql){
			$this->session->set_flashdata('message', 'SK Penghapusan berhasil diupdate!');
			redirect('penghapusan');
		}else{		
			$this->session->set_flashdata('message', 'SK Penghapusan Gagal diupdate!');
			redirect('penghapusan');
			}
	}

	function rincian_kiba($sk){

		$id = decrypt_url($sk);
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND a.Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Entry ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Penghapusan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		$this->session->set_userdata('sk_tmp', $id);
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}

		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		$like 	.= " AND No_SK = '{$id}'";
		
		if ($this->session->userdata('lvl') == 01){
			$where = "";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title']         = $this->title;
		$data['judul']         = $judul;
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$no_sk                 = $id;
		
		$sql                   = $this->Penghapusan_model->getRincian_KIBA($this->limit, $offset, $where, $like);
		
		$data['query']         = $sql['data'];
		
		$num_rows              = $sql['total'];

		$data['header']        = " Penghapusan".' | No SK . '.$no_sk;
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_action']   = site_url('penghapusan/set');
		
		$data['option_q']      = pencarian_KIBA();
		
		if ($num_rows > 0)
		{
			$config['base_url']           = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination']          = $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data penghapusan KIB A !';
		}		
		$this->template->load('template','adminweb/penghapusan/rincian_kiba',$data);
	}	

	function rincian_kibb($sk){

		$id = decrypt_url($sk);
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND a.Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Entry ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Penghapusan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		$this->session->set_userdata('sk_tmp', $id);
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}

		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		$like 	.= " AND No_SK = '{$id}'";
		
		if ($this->session->userdata('lvl') == 01){
			$where = "";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title']         = $this->title;
		$data['judul']         = $judul;
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$no_sk                 = $id;
		
		$sql                   = $this->Penghapusan_model->getRincian_KIBB($this->limit, $offset, $where, $like);
		
		$data['query']         = $sql['data'];
		
		$num_rows              = $sql['total'];

		$data['header']        = " Penghapusan".' | No SK . '.$no_sk;
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_action']   = site_url('penghapusan/set');
		
		$data['option_q']      = pencarian_KIBBHapus();
		
		if ($num_rows > 0)
		{
			$config['base_url']           = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination']          = $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data penghapusan KIB B !';
		}		
		$this->template->load('template','adminweb/penghapusan/rincian_kibb',$data);
	}

	function rincian_kibc($sk){

		$id = decrypt_url($sk);
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND a.Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Entry ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Penghapusan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		$this->session->set_userdata('sk_tmp', $id);
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}

		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		$like 	.= " AND No_SK = '{$id}'";
		
		if ($this->session->userdata('lvl') == 01){
			$where = "";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title']         = $this->title;
		$data['judul']         = $judul;
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$no_sk                 = $id;
		
		$sql                   = $this->Penghapusan_model->getRincian_KIBC($this->limit, $offset, $where, $like);
		
		$data['query']         = $sql['data'];
		
		$num_rows              = $sql['total'];

		$data['header']        = " Penghapusan".' | No SK . '.$no_sk;
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_action']   = site_url('penghapusan/set');
		
		$data['option_q']      = pencarian_KIBC();
		
		if ($num_rows > 0)
		{
			$config['base_url']           = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination']          = $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data penghapusan KIB C !';
		}		
		$this->template->load('template','adminweb/penghapusan/rincian_kibc',$data);
	}

	function rincian_kibd($sk){

		$id = decrypt_url($sk);
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND a.Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Entry ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Penghapusan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		$this->session->set_userdata('sk_tmp', $id);
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}

		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		$like 	.= " AND No_SK = '{$id}'";
		
		if ($this->session->userdata('lvl') == 01){
			$where = "";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title']         = $this->title;
		$data['judul']         = $judul;
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$no_sk                 = $id;
		
		$sql                   = $this->Penghapusan_model->getRincian_KIBD($this->limit, $offset, $where, $like);
		
		$data['query']         = $sql['data'];
		
		$num_rows              = $sql['total'];

		$data['header']        = " Penghapusan".' | No SK . '.$no_sk;
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_action']   = site_url('penghapusan/set');
		
		$data['option_q']      = pencarian_KIBC();
		
		if ($num_rows > 0)
		{
			$config['base_url']           = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination']          = $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data penghapusan KIB D !';
		}		
		$this->template->load('template','adminweb/penghapusan/rincian_kibd',$data);
	}


	function rincian_kibe($sk){

		$id = decrypt_url($sk);
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND a.Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Entry ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Penghapusan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		$this->session->set_userdata('sk_tmp', $id);
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}

		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		$like 	.= " AND No_SK = '{$id}'";
		
		if ($this->session->userdata('lvl') == 01){
			$where = "";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title']         = $this->title;
		$data['judul']         = $judul;
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$no_sk                 = $id;
		
		$sql                   = $this->Penghapusan_model->getRincian_KIBE($this->limit, $offset, $where, $like);
		
		$data['query']         = $sql['data'];
		
		$num_rows              = $sql['total'];

		$data['header']        = " Penghapusan".' | No SK . '.$no_sk;
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_action']   = site_url('penghapusan/set');
		
		$data['option_q']      = pencarian_KIBE();
		
		if ($num_rows > 0)
		{
			$config['base_url']           = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination']          = $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data penghapusan KIB E !';
		}		
		$this->template->load('template','adminweb/penghapusan/rincian_kibe',$data);
	}

	function rincian_al($sk){

		$id = decrypt_url($sk);
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		$thn      =  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND a.Log_entry LIKE '%$thn%'";
			$judul 	= "Tahun Entry ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Penghapusan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Usulan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	
		}

		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		$this->session->set_userdata('sk_tmp', $id);
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}

		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		
		$like 	.= " AND No_SK = '{$id}'";
		
		if ($this->session->userdata('lvl') == 01){
			$where = "";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title']         = $this->title;
		$data['judul']         = $judul;
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$no_sk                 = $id;
		
		$sql                   = $this->Penghapusan_model->getRincian_KIBL($this->limit, $offset, $where, $like);
		
		$data['query']         = $sql['data'];
		
		$num_rows              = $sql['total'];

		$data['header']        = " Penghapusan".' | No SK . '.$no_sk;
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_action']   = site_url('penghapusan/set');
		
		$data['option_q']      = pencarian_KIBL();
		
		if ($num_rows > 0)
		{
			$config['base_url']           = current_URL().'?';
			$config['total_rows']        = $num_rows;
			$config['per_page']          = $this->limit;
			$config['uri_segment']       = $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination']          = $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data penghapusan AL !';
		}		
		$this->template->load('template','adminweb/penghapusan/rincian_al',$data);
	}	
	
	/**
	 * Tampilkan semua data penghapusan kib a
	 */
	function kiba($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

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
		
		$like 	= "AND Status <> '2' ";

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP LIKE '%$thn%'";
			$judul 	= "Tahun usulan penghapusan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	.= "";
			$judul 	= "Semua Data usulan penghapusan KIB A. Tanah";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	.= "";
				$judul 	= "Semua Data usulan penghapusan KIB A. Tanah di SKPD";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	.= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	.= "AND $q LIKE '%$s%' AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = "AND a.Kd_Prov=2";
			}else{
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$sk_tmp = $this->session->userdata('sk_tmp');
		$data['sk_tmp'] 	= ! empty($sk_tmp) ? $sk_tmp : ''; 
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$sql = $this->Penghapusan_model->getRincian_KIBA($this->limit, $offset, $where, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];

		$total 				= 2;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb;
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['kibb'] 		= site_url('penghapusan/kibb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibc'] 		= site_url('penghapusan/kibc/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibd'] 		= site_url('penghapusan/kibd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibe'] 		= site_url('penghapusan/kibe/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['lainnya']    = site_url('penghapusan/lainnya/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] 	= pencarian_KIBA();

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
			$data['message'] = 'Tidak ditemukan data usulan penghapusan KIB A !';
		}		
		$this->template->load('template','adminweb/penghapusan/kiba',$data);
	}
	
	
	/**
	 * Tampilkan semua data penghapusan kib b
	 */
	function kibb($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

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
		
		$like 	= "AND Status <> '2' ";

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP LIKE '%$thn%'";
			$judul 	= "Tahun usulan penghapusan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	.= "";
			$judul 	= "Semua Data usulan penghapusan KIB B. Peralatan dan Mesin";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	.= "";
				$judul 	= "Semua Data usulan penghapusan KIB B. Peralatan & Mesin di SKPD";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	.= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	.= "AND $q LIKE '%$s%' AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = "AND a.Kd_Prov=2";
			}else{
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$sk_tmp = $this->session->userdata('sk_tmp');
		$data['sk_tmp'] 	= ! empty($sk_tmp) ? $sk_tmp : ''; 
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$sql = $this->Penghapusan_model->getRincian_KIBB($this->limit, $offset, $where, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];

		$total 				= 2;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb;
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['kiba'] 		= site_url('penghapusan/kiba/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibc'] 		= site_url('penghapusan/kibc/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibd'] 		= site_url('penghapusan/kibd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibe'] 		= site_url('penghapusan/kibe/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['lainnya']    = site_url('penghapusan/lainnya/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q'] = array(''=>'- pilih pencarian -','Nm_Aset5'=>'Nama Aset','Alamat'=>'Alamat/Lokasi','Luas_M2'=>'Luas','Sertifikat_Tanggal'=>'Tanggal Sertifikat','Sertifikat_Nomor'=>'Nomor Sertifikat','Harga'=>'Harga','Keterangan'=>'Keterangan','all'=>'Semua Data','all_skpd'=>'Seluruh Data SKPD dan UPB');
		
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
			$data['message'] = 'Tidak ditemukan data usulan penghapusan KIB B !';
		}		
		$this->template->load('template','adminweb/penghapusan/kibb',$data);
	}
	
	/**
	 * Tampilkan semua data penghapusan kib c
	 */
	function kibc($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

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
		
		$like 	= "AND Status <> '2' ";

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP LIKE '%$thn%'";
			$judul 	= "Tahun usulan penghapusan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	.= "";
			$judul 	= "Semua Data usulan penghapusan KIB C. Gedung dan Bangunan";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	.= "";
				$judul 	= "Semua Data usulan penghapusan  KIB C. Gedung dan Bangunan di SKPD";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	.= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	.= "AND $q LIKE '%$s%' AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = "AND a.Kd_Prov=2";
			}else{
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$sk_tmp = $this->session->userdata('sk_tmp');
		$data['sk_tmp'] 	= ! empty($sk_tmp) ? $sk_tmp : ''; 
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$sql = $this->Penghapusan_model->getRincian_KIBC($this->limit, $offset, $where, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];

		$total 				= 2;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb;
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['kiba'] 		= site_url('penghapusan/kiba/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibb'] 		= site_url('penghapusan/kibb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibd'] 		= site_url('penghapusan/kibd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibe'] 		= site_url('penghapusan/kibe/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['lainnya']    = site_url('penghapusan/lainnya/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']  = pencarian_KIBC();
		
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
			$data['message'] = 'Tidak ditemukan data usulan penghapusan KIB C !';
		}		
		$this->template->load('template','adminweb/penghapusan/kibc',$data);
	}
	
	/**
	 * Tampilkan semua data penghapusan kib d
	 */
	function kibd($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

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
		
		$like 	= "AND Status <> '2' ";

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP LIKE '%$thn%'";
			$judul 	= "Tahun usulan penghapusan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	.= "";
			$judul 	= "Semua Data usulan penghapusan KIB D. Jalan, Irigasi dan Jaringan";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	.= "";
				$judul 	= "Semua Data usulan penghapusan  KIB D. Jalan, Irigasi dan Jaringan di SKPD";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	.= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	.= "AND $q LIKE '%$s%' AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		
		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = "AND a.Kd_Prov=2";
			}else{
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$sk_tmp = $this->session->userdata('sk_tmp');
		$data['sk_tmp'] 	= ! empty($sk_tmp) ? $sk_tmp : ''; 
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$sql = $this->Penghapusan_model->getRincian_KIBD($this->limit, $offset, $where, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];

		$total 				= 2;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb;
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['kiba'] 		= site_url('penghapusan/kiba/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibb'] 		= site_url('penghapusan/kibb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibc'] 		= site_url('penghapusan/kibc/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibe'] 		= site_url('penghapusan/kibe/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['lainnya']    = site_url('penghapusan/lainnya/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']  = pencarian_KIBC();
		
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
			$data['message'] = 'Tidak ditemukan data usulan penghapusan KIB D !';
		}		
		$this->template->load('template','adminweb/penghapusan/kibd',$data);
	}
	
	/**
	 * Tampilkan semua data penghapusan kib e
	 */
	function kibe($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

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
		
		$like 	= "AND Status <> '2' ";

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP LIKE '%$thn%'";
			$judul 	= "Tahun usulan penghapusan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	.= "";
			$judul 	= "Semua Data usulan penghapusan KIB E. Aset Tetap Lainnya";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	.= "";
				$judul 	= "Semua Data usulan penghapusan  KIB E. Aset Tetap Lainnya di SKPD";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	.= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	.= "AND $q LIKE '%$s%' AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = "AND a.Kd_Prov=2";
			}else{
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$sk_tmp = $this->session->userdata('sk_tmp');
		$data['sk_tmp'] 	= ! empty($sk_tmp) ? $sk_tmp : ''; 
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$sql = $this->Penghapusan_model->getRincian_KIBE($this->limit, $offset, $where, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];

		$total 				= 2;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb;
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['kiba'] 		= site_url('penghapusan/kiba/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibb'] 		= site_url('penghapusan/kibb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibc'] 		= site_url('penghapusan/kibc/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibd'] 		= site_url('penghapusan/kibd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['lainnya']    = site_url('penghapusan/lainnya/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']  = pencarian_KIBE();
		
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
			$data['message'] = 'Tidak ditemukan data usulan penghapusan KIB E !';
		}		
		$this->template->load('template','adminweb/penghapusan/kibe',$data);
	}

	/**
	 * Tampilkan semua data penghapusan kib e
	 */
	function lainnya($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',VIEW))
		    $this->general->no_access();

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
		
		$like 	= "AND Status <> '2' ";

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP LIKE '%$thn%'";
			$judul 	= "Tahun usulan penghapusan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	.= "";
			$judul 	= "Semua Data usulan penghapusan Aset Lainnya";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	.= "AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	.= "";
				$judul 	= "Semua Data usulan penghapusan  Aset Lainnya di SKPD";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	.= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	.= "AND $q LIKE '%$s%' AND a.Tgl_UP BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal usulan penghapusan ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = "AND a.Kd_Prov=2";
			}else{
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$sk_tmp = $this->session->userdata('sk_tmp');
		$data['sk_tmp'] 	= ! empty($sk_tmp) ? $sk_tmp : ''; 
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$sql = $this->Penghapusan_model->getRincian_KIBL($this->limit, $offset, $where, $like);

		$data['query'] 		= $sql['data'];

		$num_rows 			= $sql['total'];

		$total 				= 2;
		$harga_total		= number_format($total,0,",",".");
		
		$data['header'] 	= $this->title.' | '.$nmupb;
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['kiba'] 		= site_url('penghapusan/kiba/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibb'] 		= site_url('penghapusan/kibb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibc'] 		= site_url('penghapusan/kibc/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibd'] 		= site_url('penghapusan/kibd/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		$data['kibe'] 		= site_url('penghapusan/kibe/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
		
		$data['option_q']  = pencarian_KIBL();
		
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
			$data['message'] = 'Tidak ditemukan data usulan penghapusan Aset Lainnya !';
		}		
		$this->template->load('template','adminweb/penghapusan/lainnya',$data);
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

	function hapus_usul_a(){

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',REMOVE))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			
		$this->db->delete("Ta_KIBAHapus", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'Kd_Id' => $kd_id));
	}

	function proses_usul_a()
	{

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',ADD))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			$sk_tmp      = $this->session->userdata('sk_tmp');
			$status      = $this->input->post('status');

			if($status==3){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}elseif($status==1){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}else{

				$arr_data = array(
							'No_SK'     => $sk_tmp,
							'Status'    => $status);
			}

		 $this->Penghapusan_model->proses_usul_a($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$arr_data);	
	}

	function hapus_usul_b(){

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',REMOVE))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			
		$this->db->delete("Ta_KIBBHapus", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'Kd_Id' => $kd_id));
	}

	function proses_usul_b()
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',ADD))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			$sk_tmp      = $this->session->userdata('sk_tmp');
			$status      = $this->input->post('status');

			if($status==3){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}elseif($status==1){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}else{

				$arr_data = array(
							'No_SK'     => $sk_tmp,
							'Status'    => $status);
			}

		 $this->Penghapusan_model->proses_usul_b($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$arr_data);	
	}

	function hapus_usul_c(){

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',REMOVE))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			
		$this->db->delete("Ta_KIBCHapus", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'Kd_Id' => $kd_id));
	}

	function proses_usul_c()
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',ADD))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			$sk_tmp      = $this->session->userdata('sk_tmp');
			$status      = $this->input->post('status');

			if($status==3){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}elseif($status==1){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}else{

				$arr_data = array(
							'No_SK'     => $sk_tmp,
							'Status'    => $status);
			}

		 $this->Penghapusan_model->proses_usul_c($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$arr_data);	
	}

	function hapus_usul_d(){

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',REMOVE))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			
		$this->db->delete("Ta_KIBDHapus", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'Kd_Id' => $kd_id));
	}

	function proses_usul_d()
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',ADD))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			$sk_tmp      = $this->session->userdata('sk_tmp');
			$status      = $this->input->post('status');

			if($status==3){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}elseif($status==1){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}else{

				$arr_data = array(
							'No_SK'     => $sk_tmp,
							'Status'    => $status);
			}

		 $this->Penghapusan_model->proses_usul_d($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$arr_data);	
	}

	function hapus_usul_e(){

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',REMOVE))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			
		$this->db->delete("Ta_KIBEHapus", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'Kd_Id' => $kd_id));
	}

	function proses_usul_e()
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',ADD))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			$sk_tmp      = $this->session->userdata('sk_tmp');
			$status      = $this->input->post('status');

			if($status==3){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}elseif($status==1){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}else{

				$arr_data = array(
							'No_SK'     => $sk_tmp,
							'Status'    => $status);
			}

		 $this->Penghapusan_model->proses_usul_e($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$arr_data);	
	}

	function proses_usul_l()
	{
		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',ADD))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			$sk_tmp      = $this->session->userdata('sk_tmp');
			$status      = $this->input->post('status');

			if($status==3){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}elseif($status==1){

				$arr_data = array(
							'No_SK'     => null,
							'Status'    => $status);	
			}else{

				$arr_data = array(
							'No_SK'     => $sk_tmp,
							'Status'    => $status);
			}

		 $this->Penghapusan_model->proses_usul_l($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$arr_data);	
	}

	function hapus_usul_l(){

		if(!$this->general->privilege_check('USUL_PENGHAPUSAN',REMOVE))
		    $this->general->no_access();

			$kd_prov	=  $this->session->userdata('kd_prov');
			$kd_kab		=  $this->session->userdata('kd_kab_kota');
			
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
			$kd_id       = $this->input->post('kd_id');
			
		$this->db->delete("Ta_KILHapus", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
		'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'Kd_Id' => $kd_id));
	}

}

/* End of file penghapusan.php */
/* Location: ./system/application/controllers/penghapusan.php */