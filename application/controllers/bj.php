<?php

class Bj extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('PENGADAAN',VIEW))
		    $this->general->no_access();

		$this->auth->clean_session('PENGADAAN');
		$this->load->model('Pengadaan_bj_model', '', TRUE);
		$this->load->model('Ta_ruang_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
		$this->load->model('Kiba_model', '', TRUE);
		$this->load->model('Kibb_model', '', TRUE);
		$this->load->model('Kibc_model', '', TRUE);
		$this->load->model('Kibd_model', '', TRUE);
		$this->load->model('Kibe_model', '', TRUE);
		$this->load->model('Kibf_model', '', TRUE);
		$this->load->model('Lainnya_model', '', TRUE);
		$this->load->model('Ref_penyusutan_model', '', TRUE); 
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
	}

	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10;
	var $title = ' Data Pengadaan Belanja Jasa';

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
		$data['form_cari']	= site_url('bj/cari');
		$data['link_kib']	= site_url('bj/listupb');

		$data['header'] 	= "Pilih data SKPD";

		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');

		$data['query']		= $this->Sub_unit_model->sub_unit();

		$data['link'] = array('link_add' => anchor('bj/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}


	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);

		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('bj/upb');

		$data['header'] 	= "Pilih data UPB".$s;

		$data['title'] 		= $this->title;

		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');

		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);

		$this->template->load('template','adminweb/listupb/upb',$data);
	}



	/**
	 * Tampilkan semua data pengadaan kib a
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
			$like 	= " AND Tahun =  '{$thn}'";
			$judul 	= "Tahun ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= " AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Pengadaan Barang & Jasa";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	= " AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	= "";
				$judul 	= "Semua data pengadaan";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= " AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= " AND $q LIKE '%$s%' AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}

		$page		= $this->input->get('per_page', TRUE);

		$this->session->set_userdata('curl', current_url());

		if(empty($page)){
			$offset		= 0;
		}else{
			$offset		= $page;
		}


		if ($this->session->userdata('lvl') == 01){
			if ($q=='all_skpd'){
			$where = " AND a.Kd_Prov=2";
			}else{
			$where = " AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
			}

		}elseif ($this->session->userdata('lvl') == 02){
			$where = " AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = " AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}

		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;


		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);

		// print_r($kd_sub); exit();

		$data['option_bidang'] = $this->Model_chain->getBidangList();
		
		
		$nmupb                 = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query']         = $this->Pengadaan_bj_model->get_page($this->limit, $offset, $where, $like)->result();
		$num_rows              = $this->Pengadaan_bj_model->count_page($where,$like)->Jumlah;
		$total                 = $this->Pengadaan_bj_model->count_page($where,$like)->Total;
		$harga_total           = number_format($total,0,",",".");
		
		$data['header']        = $this->title.' | '.$nmupb.' | Total Harga = Rp.'.$harga_total.',-';
		$data['jumlah']        = $num_rows;
		
		$data['offset']        = $offset;
		$data['form_cari']     = current_URL();
		
		$data['option_q']      = pencarian_BJ();

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
			$data['message'] = 'Tidak ditemukan data pengadaan!';
		}
		$this->template->load('template','adminweb/bj/data',$data);
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
	 * tambah pengadaan
	 */
	function add()
	{
		if(!$this->general->privilege_check('PENGADAAN',ADD))
		    $this->general->no_access();
		
		$data['mode'] 		 = "add";
		$data['title']       = $this->title;
		$data['h2_title']    = 'Pengadaan Barang & Jasa > Tambah Data';
		$data['form_action'] = site_url('bj/add_process');

		$this->template->load('template','adminweb/bj/f_pengadaan',$data);
	}

	/**
	 * Proses tambah data pengadaan
	 */
	function add_process()
	{
			
			$data = $this->input->post(NULL, TRUE);
			
			// print_r($data); exit();
			$sql = $this->Pengadaan_bj_model->save($data);

			if ($sql){
				$this->session->set_flashdata('message', 'Satu data pengadaan berhasil ditambah !');
				redirect('bj/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
			}else{
				$this->session->set_flashdata('message', 'Satu data pengadaan berhasil ditambah !');
				redirect('bj/upb/'.$this->input->post('Kd_Bidang').'/'.$this->input->post('Kd_Unit').'/'
										.$this->input->post('Kd_Sub').'/'.$this->input->post('Kd_UPB'));
				}
	}

	/**
	 * Pindah ke halaman update pengadaan
	 */
	function update($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
	{

		if(!$this->general->privilege_check('PENGADAAN',EDIT))
		    $this->general->no_access();

		$data['mode'] 			= "update";
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengadaan Barang & Jasa > Update Data';
		$data['form_action']	= site_url('bj/update_process');
		$data['header'] 		= $this->title;

		$jumlah = $this->Pengadaan_bj_model->get_data_by_id($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)->num_rows();


		if ($jumlah > 0){
			$data['get'] = $this->Pengadaan_bj_model->get_data_by_id($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)->row();

			$this->session->set_userdata('Tahun', $tahun);
			$this->session->set_userdata('Kd_Prov', $kd_prov);
			$this->session->set_userdata('Kd_Kab_Kota', $kd_kab);
			$this->session->set_userdata('Kd_Bidang', $kd_bidang);
			$this->session->set_userdata('Kd_Unit', $kd_unit);
			$this->session->set_userdata('Kd_Sub', $kd_sub);
			$this->session->set_userdata('Kd_UPB', $kd_upb);
			$this->session->set_userdata('No_ID', $no_id);

			$this->template->load('template','adminweb/bj/f_pengadaan',$data);
		}else{
			redirect('bj');
		}
	}

	/**
	 * Proses update data pengadaan
	 */
	function update_process()
	{
			$data = $this->input->post(NULL, TRUE);
			
			$tahun       = $this->session->userdata('Tahun');
			$kd_prov     = $this->session->userdata('Kd_Prov');
			$kd_kab_kota = $this->session->userdata('Kd_Kab_Kota');
			$kd_bidang   = $this->session->userdata('Kd_Bidang');
			$kd_unit     = $this->session->userdata('Kd_Unit');
			$kd_sub      = $this->session->userdata('Kd_Sub');
			$kd_upb      = $this->session->userdata('Kd_UPB');
			$no_id       = $this->session->userdata('No_ID');

			$sql = $this->Pengadaan_bj_model->update($tahun,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$data);

			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Kontrak berhasil diupdate !');
				redirect('bj/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
			}else{
				$this->session->set_flashdata('message', 'Satu data Kontrak berhasil diupdate !');
				redirect('bj/upb/'.$kd_bidang.'/'.$kd_unit.'/'.$kd_sub.'/'.$kd_upb);
				}

	}

	/**
	 * Hapus data 
	 */
	function delete($tahun,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id){

		// if(!$this->general->privilege_check('KIB_B',REMOVE))
		//     $this->general->no_access();

		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $kd_bidang;
			$kd_unit	=  $kd_unit;
			$kd_sub		=  $kd_sub;
			$kd_upb		=  $kd_upb;	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $kd_upb;
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
			
		$this->Pengadaan_bj_model->delete($tahun,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id);
		echo json_encode(array("status" => TRUE));
	}



}

/* End of file pengadaan.php */
/* Location: ./system/application/controllers/pengadaan.php */