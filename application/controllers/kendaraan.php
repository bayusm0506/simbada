<?php

class Kendaraan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('Kendaraan_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Ta_ruang_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = 'KIB B | Kendaraan';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman kendaraan,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		
		$this->auth->restrict();
		if ($this->session->userdata('lvl') == 03){
			$this->get_upb();
		}else{
			$this->get_data_upb();
		}
	}
	
	
	/**
	 * Tampilkan semua data skpd
	 */
	function get_data_upb()
	{
		$this->auth->restrict();
		$data['form_cari']	= site_url('kendaraan/cari');
		$data['link_kib']	= site_url('kendaraan/listupb');
	
		$data['header'] 	= "DATA SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']			= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('kendaraan/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$this->auth->restrict();
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('kendaraan/upb');
	
		$data['header'] 	= "DATA UPB ".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']			= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('kendaraan/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan data kendaraan untuk operator
	 */
	function get_upb()
	{
		$this->auth->restrict();
		$data['title'] 		= $this->title;
		$data['sidebar'] 	= "0";
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$q 			= $this->input->get('q', TRUE);
		$s			= $this->input->get('s', TRUE);
		$page		= $this->input->get('per_page', TRUE);
		
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
		
		if ($this->session->userdata('lvl') == 01){
			$where = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "Kd_Bidang=$kb AND Kd_Unit=$ku AND Kd_Sub=$ks AND Kd_UPB=$kd_upb";
		}else{
			$where = "Kd_Bidang=$kb AND Kd_Unit=$ku AND Kd_Sub=$ks AND Kd_UPB=$kupb";
		}
	
		if (empty($q) && empty($s)){
			$like= "Tgl_Pembukuan LIKE '%$thn%'";
		}elseif ($q == 'all'){
			$like = "";
		}elseif (empty($q)){
			$like = "Nm_Aset5 LIKE '%$s%'";
		}else{
			$like = "$q LIKE '%$s%'";	
		}
		
		$nmupb				= $this->Ref_upb_model->nama_upb($kb,$ku,$ks,$kupb);
		$data['query'] 		= $this->Kendaraan_model->get_page($this->limit, $offset, $where, $like);
		$num_rows 			= $this->Kendaraan_model->count_kib($where,$like)->num_rows();
		$total 				= $this->Kendaraan_model->total_kib($where,$like);
		$harga_total		= number_format($total,0,",",".");
		$data['header'] 	= $this->title.' ('.$nmupb.') | Jumlah data = '.$num_rows.' Unit | Total Harga = Rp.'.$harga_total.',-';
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['option_q'] = array(''=>'- Pilih -','Nm_Aset5'=>'Nama Barang','Tgl_Perolehan'=>'Tahun Perolehan','Tgl_Pembukuan'=>'Tahun Pembukuan','Kondisi'=>'Kondisi','Harga'=>'harga','Nomor_Pabrik'=>'Nomor_Pabrik','Nomor_Rangka'=>'Nomor_Rangka','Nomor_Mesin'=>'Nomor_Mesin','Nomor_Polisi'=>'Nomor_Polisi','Nomor_BPKB'=>'Nomor_BPKB','Keterangan'=>'Keterangan','all'=>'Semua Data');	
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?q='.$q.'&s='.$s;
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data KIB B !';
		}		
		$data['link'] = array('link_add' => anchor('kendaraan/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/kendaraan/kendaraan',$data);
	}
	
	
	/**
	 * Tampilkan semua data kendaraan
	 */
	function upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)
	{
		$this->auth->restrict();
		$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['title'] 		= $this->title;
		$data['sidebar'] 	= "0";
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		$q 			= $this->input->get('q', TRUE);
		$s			= $this->input->get('s', TRUE);
		$page		= $this->input->get('per_page', TRUE);
		
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
		
		if ($this->session->userdata('lvl') == 01){
			$where = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "Kd_Bidang=$kb AND Kd_Unit=$ku AND Kd_Sub=$ks AND Kd_UPB=$kd_upb";
		}else{
			$where = "Kd_Bidang=$kb AND Kd_Unit=$ku AND Kd_Sub=$ks AND Kd_UPB=$kupb";
		}
	
		if (empty($q) && empty($s)){
			$like= "Tgl_Pembukuan LIKE '%$thn%'";
		}elseif ($q == 'all'){
			$like = "";
		}elseif (empty($q)){
			$like = "Nm_Aset5 LIKE '%$s%'";
		}else{
			$like = "$q LIKE '%$s%'";	
		}
		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query'] 		= $this->Kendaraan_model->get_page($this->limit, $offset, $where, $like);
		$num_rows 			= $this->Kendaraan_model->count_kib($where,$like)->num_rows();
		$total 				= $this->Kendaraan_model->total_kib($where,$like);
		$harga_total		= number_format($total,0,",",".");
		$data['header'] 	= $this->title.' ('.$nmupb.') | Jumlah data = '.$num_rows.' Unit | Total Harga = Rp.'.$harga_total.',-';
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();
		
		$data['option_q'] = array(''=>'- Pilih -','Nm_Aset5'=>'Nama Barang','Tgl_Perolehan'=>'Tahun Perolehan','Tgl_Pembukuan'=>'Tahun Pembukuan','Kondisi'=>'Kondisi','Harga'=>'harga','Nomor_Pabrik'=>'Nomor_Pabrik','Nomor_Rangka'=>'Nomor_Rangka','Nomor_Mesin'=>'Nomor_Mesin','Nomor_Polisi'=>'Nomor_Polisi','Nomor_BPKB'=>'Nomor_BPKB','Keterangan'=>'Keterangan','all'=>'Semua Data');
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?q='.$q.'&s='.$s;
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan data KIB B !';
		}		
		$data['link'] = array('link_add' => anchor('kendaraan/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/kendaraan/kendaraan',$data);
	}
	
	
		
	/**
	 * Hapus data Kib a
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$this->auth->restrict();
		$this->kendaraan_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('kendaraan');
	}
	
	/**
	 * Menghapus dengan ajax post
	 */
	function ajax_hapus(){
			$this->auth->restrict();
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
			
		$this->Kendaraan_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
	}
	
	
	/**
	 * Pindah ke halaman tambah kendaraan
	 */
	function add()
	{		
		$this->auth->restrict();
		$data['default']['Kd_Bidang'] 			= $this->session->userdata('addKd_Bidang');
		$data['default']['Kd_Unit'] 			= $this->session->userdata('addKd_Unit');
		$data['default']['Kd_Sub'] 				= $this->session->userdata('addKd_Sub');
		$data['default']['Kd_UPB'] 				= $this->session->userdata('addKd_UPB');
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'KIB B > Tambah Data';
		$data['form_action']	= site_url('kendaraan/add_process');
		$data['link'] 			= array('link_back' => anchor('kendaraan','kembali', array('class' => 'back'))
										);
		$nmupb				= $this->Ref_upb_model->nama_upb($this->session->userdata('addKd_Bidang'),
															 $this->session->userdata('addKd_Unit'),
															 $this->session->userdata('addKd_Sub'),
															 $this->session->userdata('addKd_UPB'));
		$data['header'] 		= $this->title.' ('.$nmupb.')';
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();
			
		$kd_prov	=  $this->session->userdata('kd_prov');
		$kd_kab		=  $this->session->userdata('kd_kab_kota');
		$kd_bidang	=  $this->session->userdata('addKd_Bidang');
		$kd_unit	=  $this->session->userdata('addKd_Unit');
		$kd_sub		=  $this->session->userdata('addKd_Sub');
		$kd_upb		=  $this->session->userdata('addKd_UPB');
		$tahun		=  $this->session->userdata('tahun_anggaran');
		
		$data['option_ruang'] 	= $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
		
		$this->template->load('template','adminweb/kendaraan/kendaraan_addform',$data);
	}
	
	
	function pindahskpd()
	{
		$this->auth->restrict();
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
			
			$register 	= $this->Kendaraan_model->get_last_noregister($this->input->post('tkd_bidang'),$this->input->post('tkd_unit'),$this->input->post('tkd_sub'),$this->input->post('tkd_upb'),$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);


	$kendaraan = array(
			'Kd_Bidang'				=> $this->input->post('tkd_bidang'),
			'Kd_Unit'				=> $this->input->post('tkd_unit'),
			'Kd_Sub'				=> $this->input->post('tkd_sub'),
			'Kd_UPB'				=> $this->input->post('tkd_upb'),
			'No_Register'			=> $register);
			
		$this->Kendaraan_model->pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register,$kendaraan);	

	}
	
	/**
	 * Proses tambah data kendaraan
	 */
	function add_process()
	{	
						$this->auth->restrict();
						$kendaraan = array(
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
									'Merk'					=> $this->input->post('Merk'),
									'Type'					=> $this->input->post('Type'),
									'Bahan'					=> $this->input->post('Bahan'),
									'Asal_usul'				=> $this->input->post('Asal_usul'),
									'CC'					=> $this->input->post('CC'),
									'Kondisi'				=> $this->input->post('Kondisi'),
									'Harga'					=> $this->input->post('Harga'),
									'Keterangan'			=> $this->input->post('Keterangan'),
									'Nilai_Sisa'			=> $this->input->post('Nilai_Sisa'),
									'Masa_Manfaat'			=> 5,
									'Kd_Penyusutan'			=> 1,
									'Kd_Data'				=> 1,
									'Kd_KA'					=> 'False',
									'Log_User'				=> $this->session->userdata('username'),
									);
									
						$sql = $this->Kendaraan_model->add($kendaraan);
						$this->session->set_flashdata('message', 'Data Berhasil ditambah!');
							redirect('kendaraan/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));

			
						if ($sql == false){		
							$this->session->set_flashdata('message', 'Data Gagal ditambah!');
							redirect('kendaraan/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
							}
					
	}
	
	/**
	 * Pindah ke halaman update kendaraan
	 */
	function update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$this->auth->restrict();
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Kendaraan > Update';
		$data['form_action']	= site_url('kendaraan/update_process');
		$data['link'] 			= array('link_back' => anchor('kendaraan','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;

		
		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();
		
		$jumlah = $this->Kendaraan_model->get_kendaraan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$kendaraan = $this->Kendaraan_model->get_kendaraan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row();
												  
			$namaaset	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);											  
				$this->session->set_userdata('Kd_Aset1', $kendaraan->Kd_Aset1);
				$this->session->set_userdata('Kd_Aset2', $kendaraan->Kd_Aset2);
				$this->session->set_userdata('Kd_Aset3', $kendaraan->Kd_Aset3);
				$this->session->set_userdata('Kd_Aset4', $kendaraan->Kd_Aset4);
				$this->session->set_userdata('Kd_Aset5', $kendaraan->Kd_Aset5);
				$this->session->set_userdata('No_Register', $kendaraan->No_Register);
				
				$data['default']['Kd_Bidang'] 			= $kendaraan->Kd_Bidang;
				$data['default']['Kd_Unit'] 			= $kendaraan->Kd_Unit;
				$data['default']['Kd_Sub'] 				= $kendaraan->Kd_Sub;
				$data['default']['Kd_UPB'] 				= $kendaraan->Kd_UPB;
				
				$data['default']['Kd_Aset1'] 			= $kendaraan->Kd_Aset1;
				$data['default']['Kd_Aset2'] 			= $kendaraan->Kd_Aset2;
				$data['default']['Kd_Aset3'] 			= $kendaraan->Kd_Aset3;
				$data['default']['Kd_Aset4'] 			= $kendaraan->Kd_Aset4;
				$data['default']['Kd_Aset5'] 			= $kendaraan->Kd_Aset5;
				$data['default']['Nm_Aset5'] 			= $namaaset;
				$data['default']['No_Register'] 		= $kendaraan->No_Register;
				$data['default']['Kd_Pemilik'] 			= $kendaraan->Kd_Pemilik;
				$data['default']['Merk'] 				= $kendaraan->Merk;
				$data['default']['Type'] 				= $kendaraan->Type;
				$data['default']['Bahan'] 				= $kendaraan->Bahan;
				$data['default']['Nomor_Pabrik'] 		= $kendaraan->Nomor_Pabrik;
				$data['default']['Nomor_Rangka']		= $kendaraan->Nomor_Rangka;
				$data['default']['Nomor_Mesin']			= $kendaraan->Nomor_Mesin;
				$data['default']['Nomor_Polisi'] 		= $kendaraan->Nomor_Polisi;
				$data['default']['Nomor_BPKB'] 			= $kendaraan->Nomor_BPKB;
				$data['default']['Tgl_Perolehan'] 		= $kendaraan->Tgl_Perolehan;
				$data['default']['Tgl_Pembukuan'] 		= $kendaraan->Tgl_Pembukuan;
				$data['default']['Asal_usul'] 			= $kendaraan->Asal_usul;
				$data['default']['CC'] 					= $kendaraan->CC;
				$data['default']['Kondisi'] 			= $kendaraan->Kondisi;
				$data['default']['Harga'] 				= $kendaraan->Harga;
				$data['default']['Keterangan'] 			= $kendaraan->Keterangan;
				$data['default']['Nilai_Sisa'] 			= $kendaraan->Nilai_Sisa;
				$data['default']['Masa_Manfaat'] 		= $kendaraan->Masa_Manfaat;
								
				$this->template->load('template','adminweb/kendaraan/kendaraan_updateform',$data);				
		}else{
			echo "tidak ada data";	
		}
	}
	
	/**
	 * Proses update data kendaraan
	 */
	function update_process()
	{
			$this->auth->restrict();
			$kendaraan = array(
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
						'Merk'					=> $this->input->post('Merk'),
						'Type'					=> $this->input->post('Type'),
						'Bahan'					=> $this->input->post('Bahan'),
						'Asal_usul'				=> $this->input->post('Asal_usul'),
						'CC'					=> $this->input->post('CC'),
						'Kondisi'				=> $this->input->post('Kondisi'),
						'Harga'					=> $this->input->post('Harga'),
						'Keterangan'			=> $this->input->post('Keterangan'),
						'Nilai_Sisa'			=> $this->input->post('Nilai_Sisa'),
						'Masa_Manfaat'			=> $this->input->post('Masa_Manfaat'));

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
						
			$sql = $this->Kendaraan_model->update($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kendaraan);
			
			if ($sql){
				$this->session->set_flashdata('message', 'Data Berhasil Diubah!');
							redirect('kendaraan/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
			}else{		
				$this->session->set_flashdata('message', 'Data Gagal Diubah!');
							redirect('kendaraan/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
				}
				 
	}
	
	function lookup()
	{
		$this->auth->restrict();
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
	        		
		$count = $this->Ref_rek_aset5_model->count_kendaraan($where);
		
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		
		$data1 = $this->Ref_rek_aset5_model->get_kendaraan($where, $sidx, $sord, $limit, $start)->result();
	
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
        $query = $this->Ref_rek_aset5_model->json_kendaraan($keyword); 
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
            $this->load->view('kendaraan/index',$data); 
        }
	}
	
	
	/**
	 * Mendapatan Nomor Register Terakhir
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
			
       
$num_rows = $this->Kendaraan_model->get_last_noregister($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);		
		
			echo "<input type=text name='No_Register' size=5 value='$num_rows' id='No_Register' readonly='readonly'>";
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
			
			$num_rows = $this->kendaraan_model->cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_registerx)->num_rows();
			echo $num_rows;
			}
	
	/**
	 * Cek apakah $id_kendaraan valid, agar tidak ganda
	 */
	function valid_no_register($no_register)
	{
		if ($this->kendaraan_model->no_register($no_register) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "kendaraan dengan Kode $id_kendaraan sudah terdaftar");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $id_kendaraan valid, agar tidak ganda. Hanya untuk proses update data kendaraan
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
if($this->kendaraan_model->valid_no_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register) === TRUE)
			{
				$this->form_validation->set_message('valid_no_register2', "kendaraan dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
}

/* End of file kendaraan.php */
/* Location: ./system/application/controllers/kendaraan.php */