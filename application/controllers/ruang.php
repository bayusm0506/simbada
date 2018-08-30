<?php

class ruang extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Ta_ruang_model', '', TRUE);
		$this->load->model('Ta_upb_model', '', TRUE);
		$this->load->model('Kibb_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = 'Parameter | Data Ruang';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman ruang,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		
		$this->auth->restrict();
		if ($this->session->userdata('lvl') == 03){
			$this->dataruang();
		}else{
			$this->get_data_upb();
		}
	}
	
	
	/**
	 * Tampilkan semua data skpd
	 */
	function get_data_upb()
	{
		$data['form_cari']	= site_url('ruang/cari');
		$data['link_kib']	= site_url('ruang/listupb');
	
		$data['header'] 	= "DATA SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']			= $this->Sub_unit_model->sub_unit();
		$data['link'] = array('link_add' => anchor('ruang/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('ruang/upb');
	
		$data['header'] 	= "DATA UPB ".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']			= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('ruang/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data ruang
	 */
	function upb($bidang='',$unit='',$sub='',$upb='')
	{
		if ($bidang == '' || $unit == '' || $sub == '' || $upb == ''){
			redirect('adminweb/home', 'refresh');
		}else{
			$this->session->set_userdata('addKd_Bidang', $bidang);
			$this->session->set_userdata('addKd_Unit', $unit);
			$this->session->set_userdata('addKd_Sub', $sub);
			$this->session->set_userdata('addKd_UPB', $upb);
		}
			
		$q 		= $this->input->get('q', TRUE);
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();		
		$data['title'] 		= $this->title;

		if($this->session->userdata('tahun')){
			$data['judul'] 		= "Data Ruang Tahun ".$this->session->userdata('tahun');
		}else{
			$data['judul'] 		= "Data Ruang Tahun ".$this->session->userdata('tahun_anggaran');
		}

		$data['option_q'] 	= range_year(2010,date("Y"));

		$this->session->set_userdata('curl', current_url());
	
		if (empty($q) && empty($s)){
			$like= '';
		}elseif ($q == 'all'){
			$like = "all";
		}elseif (empty($q)){
			$like = array('Nm_Ruang' => $s);
		}else{
			$like = array($q => $s);
		}
		
		$nmupb				= $this->Ref_upb_model->nama_upb($bidang,$unit,$sub,$upb);
		$data['query']		= $this->Ta_ruang_model->now_data_ruang($bidang,$unit,$sub,$upb,$like);
		$data['header'] 	= "Data Ruang";
		
		
		$data['link'] = array('link_add' => anchor('ruang/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/ruang/ruang',$data);
	}
	
	
	/**
	 * Tampilkan semua data kir
	 */
	function kir($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='',$kd_ruang='')
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
		
		$nmupb                 = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$nm_ruang              = $this->Ta_ruang_model->nama_ruang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang);
		$data['query']         = $this->Kibb_model->get_page($this->limit, $offset, $where, $like2." AND aktif <> NULL AND Kd_Ruang = $kd_ruang");
		$num_rows              = $this->Kibb_model->count_kib($where,$like2." AND aktif <> NULL AND Kd_Ruang = $kd_ruang")->Jumlah;
		$total                 = $this->Kibb_model->count_kib($where,$like2." AND aktif <> NULL AND Kd_Ruang = $kd_ruang")->Total;
		$harga_total           = number_format($total,0,",",".");
		
		$data['header']        = ' Ruang | '.strtolower(!empty($nm_ruang)).' | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_cari']     = current_URL();
		
		$data['option_q']      = array(''=>'- pilih pencarian -','Nm_Aset5'=>'Nama Aset','Alamat'=>'Alamat/Lokasi','Luas_M2'=>'Luas','Sertifikat_Tanggal'=>'Tanggal Sertifikat','Sertifikat_Nomor'=>'Nomor Sertifikat','Harga'=>'Harga','Keterangan'=>'Keterangan','all'=>'Semua Data');
		
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
			$data['message'] = 'Tidak ditemukan data KIR !';
		}		
		
		$this->template->load('template','adminweb/ruang/kir',$data);
	}
	
	
	public function set()
	{
			$session_data = array(
				'tahun' 		=> $this->input->post('tahun')
			);
			
			$this->session->set_userdata($session_data);
			header('location:'.$this->session->userdata('curl'));
	}
	
	/**
	 * Tampilkan semua data ruang
	 */
	function dataruang()
	{

		$q 		= $this->input->get('q', TRUE);
		$s 		= $this->input->get('s', TRUE);	
		$bidang = $this->session->userdata('kd_bidang');
		$unit 	= $this->session->userdata('kd_unit');
		$sub 	= $this->session->userdata('kd_sub_unit');
		$upb 	= $this->session->userdata('kd_upb');
		
		$nmupb				= $this->Ref_upb_model->nama_upb($bidang,$unit,$sub,$upb);
		$data['form_cari']	= current_URL();		
		$data['title'] 		= $this->title;

		$data['option_q'] = array(''=>'- Pilih -','Nm_Ruang'=>'Nama Ruangan');
	
		if (empty($q) && empty($s)){
			$like= '';
		}elseif ($q == 'all'){
			$like = "all";
		}elseif (empty($q)){
			$like = array('Nm_Ruang' => $s);
		}else{
			$like = array($q => $s);
		}
		
		$nmupb				= $this->Ref_upb_model->nama_upb($bidang,$unit,$sub,$upb);
		$data['query']		= $this->Ta_ruang_model->now_data_ruang($bidang,$unit,$sub,$upb,$like);
		$data['header'] 	= "Data Ruang | ".$nmupb;
		
		
		$data['link'] = array('link_add' => anchor('ruang/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/ruang/ruang',$data);
	}
	
	
	/**
	 * Pindah ke halaman tambah ruang
	 */
	function add()
	{		
		

		if ($this->session->userdata('lvl') == 03){
			$data['default']['Kd_Bidang'] = $this->session->userdata('kd_bidang');
			$data['default']['Kd_Unit']   = $this->session->userdata('kd_unit');
			$data['default']['Kd_Sub']    = $this->session->userdata('kd_sub_unit');
			$data['default']['Kd_UPB']    = $this->session->userdata('kd_upb');
		}else{
			$data['default']['Kd_Bidang'] = $this->session->userdata('addKd_Bidang');
			$data['default']['Kd_Unit']   = $this->session->userdata('addKd_Unit');
			$data['default']['Kd_Sub']    = $this->session->userdata('addKd_Sub');
			$data['default']['Kd_UPB']    = $this->session->userdata('addKd_UPB');
		}
		
		if($this->session->userdata('tahun')){
			$tahun = $this->session->userdata('tahun');
		}else{
			$tahun = $this->session->userdata('tahun_anggaran');
		}
		$data['Tahun']    = $tahun;
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Ruang > Tambah Data';
		$data['form_action']	= site_url('ruang/add_process');
		$data['link'] 			= array('link_back' => anchor('ruang','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;
		
		$data['lastruang'] 		= $this->Ta_ruang_model->get_last_ruang($this->session->userdata('addKd_Bidang'),
																		$this->session->userdata('addKd_Unit'),
																		$this->session->userdata('addKd_Sub'),
																		$this->session->userdata('addKd_UPB'),$tahun);
		$this->template->load('template','adminweb/ruang/ruang_addform',$data);
	}
	
	
	/**
	 * Proses tambah data kiba
	 */
	function add_process()
	{

			$ruang = array(
						'Tahun'					=> $this->input->post('Tahun'),
						'Kd_Prov'				=> $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'			=> $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'				=> $this->input->post('Kd_Bidang'),
						'Kd_Unit'				=> $this->input->post('Kd_Unit'),
						'Kd_Sub'				=> $this->input->post('Kd_Sub'),
						'Kd_UPB'				=> $this->input->post('Kd_UPB'),
						'Kd_Ruang'				=> $this->input->post('Kd_Ruang'),
						'Nm_Ruang'				=> $this->input->post('Nm_Ruang'));
			
			
						
			$sql = $this->Ta_ruang_model->add($ruang);
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Ruang Berhasil ditambah!');
			}else{		
				$this->session->set_flashdata('message', 'Satu data Ruang Gagal ditambah!');
				}
				
				redirect('ruang/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
	}
	
	/**
	 * Pindah ke halaman update ruang
	 */
	function update($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Ruang > Update';
		$data['form_action']	= site_url('ruang/update_process');
		$data['link'] 			= array('link_back' => anchor('ruang','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;


		$jumlah = $this->Ta_ruang_model->get_ruang_by_id($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$ruang = $this->Ta_ruang_model->get_ruang_by_id($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang)->row();
												  
				$this->session->set_userdata('Kd_Ruang', $ruang->Kd_Ruang);

				$data['default']['Kd_Bidang'] = $ruang->Kd_Bidang;
				$data['default']['Kd_Unit']   = $ruang->Kd_Unit;
				$data['default']['Kd_Sub']    = $ruang->Kd_Sub;
				$data['default']['Kd_UPB']    = $ruang->Kd_UPB;
				$data['default']['Kd_Ruang']  = $ruang->Kd_Ruang;
				$data['default']['Nm_Ruang']  = $ruang->Nm_Ruang;
								
				$this->template->load('template','adminweb/ruang/ruang_updateform',$data);				
		}else{
			echo "<center>tidak ada data</center>";	
		}
	}
	
	
	/**
	 * Proses update data ruang
	 */
	function update_process()
	{
		
			$ruang = array(
						'Kd_Prov'     => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota' => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'   => $this->input->post('Kd_Bidang'),
						'Kd_Unit'     => $this->input->post('Kd_Unit'),
						'Kd_Sub'      => $this->input->post('Kd_Sub'),
						'Kd_UPB'      => $this->input->post('Kd_UPB'),
						'Kd_Ruang'    => $this->input->post('Kd_Ruang'),
						'Nm_Ruang'    => $this->input->post('Nm_Ruang')
					);

			$kd_bidang = $this->input->post('Kd_Bidang');
			$kd_unit   = $this->input->post('Kd_Unit');
			$kd_sub    = $this->input->post('Kd_Sub');
			$kd_upb    = $this->input->post('Kd_UPB');

			if($this->session->userdata('tahun')){
				$tahun = $this->session->userdata('tahun');
			}else{
				$tahun = $this->session->userdata('tahun_anggaran');
			}

			$kdruang   = $this->session->userdata('Kd_Ruang');		
			

			$sql = $this->Ta_ruang_model->update($kd_bidang,$kd_unist,$kd_sub,$kd_upb,$kdruang,$tahun,$ruang);
		
			if ($sql){
				echo "<script>alert('Data Umum berhasil diupdate!');javascript:history.go(-2)</script>";
			}else{
				echo "<script>alert('Data Umum Gagal diupdate!');javascript:history.back()</script>";
				}
			
	}
	
	
	/**
	 * Tampilkan data ruang untuk operator
	 */
	function get_upb()
	{
		$bidang = $this->session->userdata('kd_bidang');
		$unit   = $this->session->userdata('kd_unit');
		$sub    = $this->session->userdata('kd_sub_unit');
		$upb    = $this->session->userdata('kd_upb');
		$tahun  = $this->session->userdata('tahun_anggaran');
			
		$this->session->set_userdata('addKd_Bidang', $bidang);
		$this->session->set_userdata('addKd_Unit', $unit);
		$this->session->set_userdata('addKd_Sub', $sub);
		$this->session->set_userdata('addKd_UPB', $upb);
			
		$q 		= $this->input->get('q', TRUE);
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();		
		$data['title'] 		= $this->title;

		$data['option_q'] = array(''=>'- Pilih -','Nm_Ruang'=>'Nama Ruangan');
	
		if (empty($q) && empty($s)){
			$like= '';
		}elseif ($q == 'all'){
			$like = "all";
		}elseif (empty($q)){
			$like = array('Nm_Ruang' => $s);
		}else{
			$like = array($q => $s);
		}
		
		$nmupb				= $this->Ref_upb_model->nama_upb($bidang,$unit,$sub,$upb);
		$data['query']		= $this->Ta_ruang_model->now_data_ruang($bidang,$unit,$sub,$upb,$like);
		$data['header'] 	= "Data Ruang";
		
		
		$data['link'] = array('link_add' => anchor('ruang/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/ruang/ruang',$data);
	}
	

	/**
	 * Menghapus dengan ajax post
	 */
	function hapus($tahun,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang){
	
		if ($this->session->userdata('lvl') == 03){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('kd_upb');
		}
					
		$sql = $this->Ta_ruang_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang,$tahun);

		if ($sql){
			$this->session->set_flashdata('message', 'Data berhasil dihapus!');
		}else{		
			$this->session->set_flashdata('message', 'Data gagal dihapus!');
		}
		redirect($this->session->userdata('curl'));	
	}

	function import()
	{
		$bidang =  $this->session->userdata('addKd_Bidang');
		$unit   =  $this->session->userdata('addKd_Unit');
		$sub    =  $this->session->userdata('addKd_Sub');
		$upb    =  $this->session->userdata('addKd_UPB');
		
		$kb     =  $this->session->userdata('kd_bidang');
		$ku     =  $this->session->userdata('kd_unit');
		$ks     =  $this->session->userdata('kd_sub_unit');
		$kupb   =  $this->session->userdata('kd_upb');
		$where  = "";

		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = " AND (Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= " AND (Kd_Unit = $unit)";
			}
			if($sub){
				$where .= " AND (Kd_Sub = $sub)";
			}
			if($upb){
				$where .= " AND (Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){

			$where .= " AND (Kd_Bidang = $kb)";
			$where .= " AND (Kd_Unit = $ku)";
			$where .= " AND (Kd_Sub = $ks)";

			if($upb){
				$where .= " AND (Kd_UPB = $upb)";
			}
		}else{
			$where .= " AND (Kd_Bidang = $kb)";
			$where .= " AND (Kd_Unit = $ku)";
			$where .= " AND (Kd_Sub = $ks)";
			$where .= " AND (Kd_UPB = $kupb)";
		}
			
			if($this->session->userdata('tahun')){
				$tahun = $this->session->userdata('tahun');
			}else{
				$tahun = $this->session->userdata('tahun_anggaran');
			}

			$row = "SELECT $tahun ,Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Ruang,Nm_Ruang,Nm_Pngjwb,Nip_Pngjwb,Jbt_Pngjwb,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Kd_Pemilik
					FROM Ta_Ruang WHERE Tahun = (SELECT MAX(Tahun) FROM Ta_Ruang WHERE 1=1 $where) $where";

			$cek = $this->db->query($row)->num_rows();
			if ($cek < 1 ){
				$this->session->set_flashdata('message', 'Tidak ada data yang diimport, silahkan tambah ruangan!');
				redirect('ruang/add');
			}
			
			$sql = "INSERT INTO Ta_Ruang (Tahun,Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Ruang,Nm_Ruang,Nm_Pngjwb,Nip_Pngjwb,Jbt_Pngjwb,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Kd_Pemilik)
					SELECT $tahun ,Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Ruang,Nm_Ruang,Nm_Pngjwb,Nip_Pngjwb,Jbt_Pngjwb,Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Kd_Pemilik
					FROM Ta_Ruang WHERE Tahun = (SELECT MAX(Tahun) FROM Ta_Ruang WHERE 1=1 $where) $where";
						
			
			// echo $sql; exit();

			$sql = $this->db->query($sql);
			
			if ($sql){
				$this->session->set_flashdata('message', 'Data berhasil diimport!');
			}else{		
				$this->session->set_flashdata('message', 'Data gagal diimport!');
			}
			redirect($this->session->userdata('curl'));
	}

}

/* End of file ruang.php */
/* Location: ./system/application/controllers/ruang.php */