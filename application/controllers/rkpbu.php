<?php

class Rkpbu extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		// if(!$this->general->privilege_check('rkpbu',VIEW))
		//     $this->general->no_access();

		$this->auth->clean_session('rkpbu');
		$this->load->model('Ref_prokeg', '', TRUE);
		$this->load->model('Rkpbu_model', '', TRUE);
		$this->load->model('Ta_ruang_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Ref_SSH_model', '', TRUE);
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
	var $title = ' Data RKPBU';

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
		$data['form_cari']	= site_url('rkpbu/cari');
		$data['link_kib']	= site_url('rkpbu/listupb');

		$data['header'] 	= "Pilih data SKPD";

		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');

		$data['query']		= $this->Sub_unit_model->sub_unit();

		$data['link'] = array('link_add' => anchor('rkpbu/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}


	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);

		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('rkpbu/upb');

		$data['header'] 	= "Pilih data UPB".$s;

		$data['title'] 		= $this->title;

		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');

		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);

		$this->template->load('template','adminweb/listupb/upb',$data);
	}

	/**
	 * Tampilkan semua data RKPBU kib a
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
			$like 	= "WHERE (Tgl_Kontrak LIKE '%$thn%' OR Tgl_Kuitansi LIKE '%$thn%')";
			$like2 	= "AND (Tgl_Kontrak LIKE '%$thn%' OR Tgl_Kuitansi LIKE '%$thn%')";
			$judul 	= "Tahun ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$like2 	= "";
			$judul 	= "Semua rkpbu";
		}elseif ($q=='all_skpd'){
			if(!empty($tanggal1) AND !empty($tanggal2)){
				$like 	= "WHERE Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
				$like2 	= "AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
				$judul 	= "Semua Data usulan SKPD Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
			}else{
				$like 	= "";
				$like2 	= "";
				$judul 	= "Semua data RKPBU";
				}
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "WHERE $q LIKE '%$s%'";
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "WHERE $q LIKE '%$s%' AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}

		$page		= $this->input->get('per_page', TRUE);

		$this->session->set_userdata('curl', current_url());

		if(empty($page)){
			$offset		= 0;
		}else{
			$offset		= $page;
		}


		if ($this->session->userdata('lvl') == 01){
			$where = "AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Tahun=$thn";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Tahun=$thn";
		}else{
			$where = "AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Tahun=$thn";
		}

		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;


		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);

		// print_r($kd_sub); exit();

		$data['option_bidang'] = $this->Model_chain->getBidangList();


		$nmupb             = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$get               = $this->Rkpbu_model->get_page($this->limit, $offset, $where, $like2);
		$data['query']     = $get['data'];
		$num_rows          = $get['total'];

		$data['header']    = $this->title.' | '.$nmupb.' | Jumlah Data = '.rp($num_rows).'';
		$data['jumlah']    = $num_rows;

		$data['offset']    = $offset;
		$data['form_cari'] = current_URL();

		$data['option_q']  = pencarian_RKPBU();

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
			$data['message'] = 'Tidak ditemukan data RKPBU!';
		}
		$this->template->load('template','adminweb/rkpbu/rkpbu',$data);
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
	 * Pindah ke halaman update rkpbu
	 */
	function lihat($kontrak)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'rkpbu > Rincian';
		$data['form_action']	= site_url('rkpbu/update_process');
		$data['link'] 			= array('link_back' => anchor('Penghapusan','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;
		$data['judul'] 			= "Rincian rkpbu";
		$kon = $kontrak.'==';
		$n_kontrak = base64_decode($kon);

		$page		= $this->input->get('per_page', TRUE);

		$this->session->set_userdata('curl', current_url());

		if(empty($page)){
			$offset		= 0;
		}else{
			$offset		= $page;
		}

		$rkpbu 	= $this->Rkpbu_model->get_rkpbu_by_id($n_kontrak)->row();
		$num_rows 	= $this->Rkpbu_model->get_rincian($n_kontrak)->num_rows();

		$nmupb				= "";
		$data['query'] 		= $this->Rkpbu_model->get_rincian($n_kontrak);
		$num_rows 			= $num_rows ;
		$total 				= 55;
		$nilai_spk			= number_format($rkpbu->Nilai,0,",",".");

		$data['header'] 	= ' No Kontrak : '.$rkpbu->No_Kontrak.' | Tanggal : '.tgl_indo($rkpbu->Tgl_Kontrak).' | Nilai SPK : Rp. '.$nilai_spk;
		$data['No_Kontrak'] = $rkpbu->No_Kontrak;

		$data['jumlah'] 	= $num_rows;
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();

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
			$data['message'] = 'Tidak ditemukan rincian data RKPBU!';
		}

		$this->template->load('template','adminweb/rkpbu/rkpbu_lihat',$data);
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
			$like2 	= "AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like2 	= "";
			$judul 	= "Semua Data usulan rkpbu KIB A. Tanah";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Kontrak BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}

		$where = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
		$where2 = "Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub";

		$d['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();

		$arg = explode('/', $curl);
		if ($arg[5] == 'skpd' ) {
			$d['data_view'] = $this->db->query("select * from Tb_rkpbu inner join Ref_Rek_Aset5 on
Tb_rkpbu.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Tb_rkpbu.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND
Tb_rkpbu.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Tb_rkpbu.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Tb_rkpbu.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where2 $like2");
		}elseif ($arg[5] == 'upb' ) {
			$d['data_view'] = $this->db->query("select * from Tb_rkpbu inner join Ref_Rek_Aset5 on
Tb_rkpbu.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Tb_rkpbu.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND
Tb_rkpbu.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Tb_rkpbu.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Tb_rkpbu.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like2 AND aktif <> NULL");
		}else{
			$d['data_view'] = $this->db->query("select * from Tb_rkpbu inner join Ref_Rek_Aset5 on
Tb_rkpbu.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Tb_rkpbu.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND
Tb_rkpbu.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Tb_rkpbu.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Tb_rkpbu.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like2 AND aktif = NULL");
			}

		$this->load->view('adminweb/rkpbu/export',$d);


	}

	function last_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

			$this->db->select_MAX('No_Id');
			$array_keys_values = $this->db->get_where('Ta_FotoA',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,
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
				$config['upload_path'] = './assets/uploads_rkpbu';
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
						$this->Rkpbu_model->insert($foto);

					echo img(array(
						'src'=>base_url("assets/uploads_rkpbu/$image_data[file_name]"),
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

    $row = $this->db->get_where('Ta_FotoA',array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id))->row();

     unlink("./assets/uploads_rkpbu/$row->Nama_foto");

    $this->db->delete('Ta_FotoA', array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Id' => $no_id));

}


	/**
	 * Hapus data Kib a
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		if(!$this->general->privilege_check('rkpbu',REMOVE))
		    $this->general->no_access();

		$this->Rkpbu_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('Penghapusan');
	}

	/**
	 * Menghapus dengan ajax post
	 */
	function ajax_hapus(){
			$tahun       =  $this->input->post('Tahun');
			$kd_prov     =  $this->input->post('Kd_Prov');
			$kd_kab_kota =  $this->input->post('Kd_Kab_Kota');

		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $this->input->post('Kd_Bidang');
			$kd_unit	=  $this->input->post('Kd_Unit');
			$kd_sub		=  $this->input->post('Kd_Sub');
			$kd_upb		=  $this->input->post('Kd_UPB');
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->input->post('Kd_UPB');
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}

			$kd_aset1x = $this->input->post('Kd_Aset1');
			$kd_aset2x = $this->input->post('Kd_Aset2');
			$kd_aset3x = $this->input->post('Kd_Aset3');
			$kd_aset4x = $this->input->post('Kd_Aset4');
			$kd_aset5x = $this->input->post('Kd_Aset5');
			$kd_aset5x = $this->input->post('Kd_Aset5');
			$kd_aset6x = $this->input->post('Kd_Aset6');
			$no_id     = $this->input->post('No_ID');

		$this->Rkpbu_model->hapus($tahun,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$kd_aset6x,$no_id);
	}

	/**
	 * Menghapus dengan rincian
	 */
	function hapus_rincian(){
			$No_Kontrak = $this->input->post('No_Kontrak');
			$No_ID = $this->input->post('No_ID');
			$this->Rkpbu_model->hapus_rincian($No_Kontrak,$No_ID);
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

		$this->Rkpbu_model->sm_update($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
									 $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data);
		}
	}


	/**
	 * tambah rkpbu
	 */
	function add()
	{
		// if(!$this->general->privilege_check('rkpbu',ADD))
		//     $this->general->no_access();

		$kd_bidang = $data['Kd_Bidang'] = $this->session->userdata('addKd_Bidang');
		$kd_unit   = $data['Kd_Unit']  	= $this->session->userdata('addKd_Unit');
		$kd_sub    = $data['Kd_Sub']  	= $this->session->userdata('addKd_Sub');
		$kd_upb    = $data['Kd_UPB']  	= $this->session->userdata('addKd_UPB');

		$data['title']          = $this->title;
		$data['h2_title']       = 'RKPBU > Tambah Data';
		$data['form_action']    = site_url('rkpbu/add_process');
		$data['link']           = array('link_back' => anchor('rkpbu','kembali', array('class' => 'back'))
		);
		
		$data['option_program'] = $this->Rkpbu_model->ProgramList($kd_bidang,$kd_unit,$kd_sub);
		
		$nmupb                  = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['header']         = $nmupb;

		$this->template->load('template','adminweb/rkpbu/rkpbu_addform',$data);
	}

	/**
	 * Proses tambah data RKPBU
	 */
	function add_process()
	{
			// echo $this->input->post('Kd_Bidang'); exit();
			$data = $this->input->post(NULL, TRUE);
			
			// print_r($data); exit();
			$sql = $this->Rkpbu_model->save($data);

			if ($sql){
				$this->session->set_flashdata('message', 'Satu data RKPBU berhasil ditambah !');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
			}else{
				$this->session->set_flashdata('message', 'Satu data RKPBU berhasil ditambah !');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	/**
	 * Pindah ke halaman update rkpbu
	 */
	function update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_id)
	{

		// if(!$this->general->privilege_check('rkpbu',EDIT))
		//     $this->general->no_access();

		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'RKPBU > Update';
		$data['form_action']	= site_url('rkpbu/update_process');
		$data['link'] 			= array('link_back' => anchor('rkpbu','kembali', array('class' => 'back'))
										);
		$data['header'] 		= $this->title;

		$jumlah = $this->Rkpbu_model->get_rkpbu_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_id)->num_rows();


		if ($jumlah > 0){
			$get = $this->Rkpbu_model->get_rkpbu_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_id)->row_array();

			
			$data['option_program']  = $this->Rkpbu_model->ProgramList($kd_bidang,$kd_unit,$kd_sub);
			$data['option_kegiatan'] = $this->Rkpbu_model->KegiatanList($kd_bidang,$kd_unit,$kd_sub,$get['Kd_Prog']);
			
			$this->session->set_userdata('tmp_Tahun', $get['Tahun']);
			$this->session->set_userdata('tmp_Kd_Prov', $get['Kd_Prov']);
			$this->session->set_userdata('tmp_Kd_Kab_Kota', $get['Kd_Kab_Kota']);
			$this->session->set_userdata('tmp_Kd_Bidang', $get['Kd_Bidang']);
			$this->session->set_userdata('tmp_Kd_Unit', $get['Kd_Unit']);
			$this->session->set_userdata('tmp_Kd_Sub', $get['Kd_Sub']);
			$this->session->set_userdata('tmp_Kd_UPB', $get['Kd_UPB']);
			$this->session->set_userdata('tmp_Kd_Aset1', $get['Kd_Aset1']);
			$this->session->set_userdata('tmp_Kd_Aset2', $get['Kd_Aset2']);
			$this->session->set_userdata('tmp_Kd_Aset3', $get['Kd_Aset3']);
			$this->session->set_userdata('tmp_Kd_Aset4', $get['Kd_Aset4']);
			$this->session->set_userdata('tmp_Kd_Aset5', $get['Kd_Aset5']);
			$this->session->set_userdata('tmp_Kd_Aset6', $get['Kd_Aset6']);
			$this->session->set_userdata('tmp_No_ID', $get['No_ID']);

			$this->template->load('template','adminweb/rkpbu/rkpbu_updateform',array_merge($get,$data) );
		}else{
			redirect('rkpbu');
		}
	}

	/**
	 * Proses update data RKPBU
	 */
	function update_process()
	{
			$data = $this->input->post(NULL, TRUE);
			
			$sql = $this->Rkpbu_model->update($data);

			if ($sql){
				$this->session->set_flashdata('message', 'Satu data RKPBU berhasil diubah !');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
			}else{
				$this->session->set_flashdata('message', 'Satu data RKPBU berhasil diubah !');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
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

		$count = $this->Ref_rek_aset5_model->count_all($where);

		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;

		$data1 = $this->Ref_rek_aset5_model->get_all($where, $sidx, $sord, $limit, $start)->result();

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


	/*
	function json(){

        $keyword = $this->input->post('term');
        $data['response'] = 'false';
        $query = $this->Ref_rek_aset5_model->json_aset_exist($keyword);
        // print_r($query); die;
        if( ! empty($query) )
        {
			$data['response'] = 'true';
			$data['message']  = array();
            foreach( $query->result() as $row )
            {
                $data['message'][] = array(
                                        'id1'=>$row->Kd_Aset1,
										'id2'=>$row->Kd_Aset2,
										'id3'=>$row->Kd_Aset3,
										'id4'=>$row->Kd_Aset4,
										'id5'=>$row->Kd_Aset5,
										'id6'=>$row->No_Register,
										'harga'=>nilai($row->Harga),
                                        'value' => $row->Nm_Aset5.' ('.$row->No_Register.') - '.clean_text($row->Spesifikasi).' Tahun : '.$row->Tahun.', Harga : '.rp($row->Harga),
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
            $this->load->view('rkpbu/index',$data);
        }
	}

	*/
	function json(){
        $keyword = $this->input->post('term');
        $data['response'] = 'false';
        $query = $this->Ref_SSH_model->json_all($keyword);
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
										'id6'=>1,
										'harga'=>0,
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
            $this->load->view('rkpbu/index',$data);
        }
	}


	/**
	 * Mendapatan No ID
	 */
	function no_id(){
			$kd_bidang	=  $this->input->post('kd_bidang');
			$kd_unit	=  $this->input->post('kd_unit');
			$kd_sub		=  $this->input->post('kd_sub');
			$kd_upb		=  $this->input->post('kd_upb');

			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$kd_aset6x = $this->input->post('kd_aset6');


			$num_rows = $this->Rkpbu_model->get_last_noid($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$kd_aset6x);

			echo "<input type=text name='No_ID' size=5 value='$num_rows' id='No_ID' readonly='readonly' class='required input-mini'>";
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

			$num_rows = $this->Rkpbu_model->cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_registerx)->num_rows();
			echo $num_rows;
			}

	/**
	 * Cek apakah $id_Penghapusan valid, agar tidak ganda
	 */
	function valid_no_register($no_register)
	{
		if ($this->Rkpbu_model->no_register($no_register) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "rkpbu dengan Kode $id_Penghapusan sudah terdaftar");
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/**
	 * Cek apakah $id_Penghapusan valid, agar tidak ganda. Hanya untuk proses update data RKPBU
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
if($this->Rkpbu_model->valid_no_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register) === TRUE)
			{
				$this->form_validation->set_message('valid_no_register2', "rkpbu dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}


	function posting($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_id)
	{
		if(!$this->general->privilege_check('rkpbu',VIEW))
		    $this->general->no_access();

		$get = $this->Rkpbu_model->get_rkpbu_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_id)->row_array();
		if(!$get)
	        show_error("Tidak ada data!");


	    // if($get['Kd_Posting'] == 1){
	    // 	$this->session->set_flashdata('message', "Data Sudah Pernah Di Posting");
	    // 	redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
	    // }

		$data['title'] 			= "Posting Data KIB";
		$data['h2_title'] 		= 'rkpbu > POSTING DATA KIB';
		$data['link'] 			= array('link_back' => anchor('kibb','kembali', array('class' => 'back'))
										);

		$this->session->set_userdata('No_ID', $get['No_ID']);

		$nmupb          = $this->Ref_upb_model->nama_upb($get['Kd_Bidang'],$get['Kd_Unit'],$get['Kd_Sub'],$get['Kd_UPB']);
		$data['header'] = $this->title.' ('.$nmupb.')';

		$data['option_pemilik'] = $this->Pemilik_model->PemilikList();
		$kd_prov 	= $this->session->userdata('kd_prov');
		$kd_kab 	= $this->session->userdata('kd_kab_kota');
		$kd_bidang 	= $this->session->userdata('addKd_Bidang');
		$kd_unit 	= $this->session->userdata('addKd_Unit');
		$kd_sub 	= $this->session->userdata('addKd_Sub');
		$kd_upb 	= $this->session->userdata('addKd_UPB');
		$tahun 		= $this->session->userdata('tahun_anggaran');

		$data['option_ruang']   = $this->Ta_ruang_model->RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun);
		
		if($get['Kd_Aset1'] == 1){
			$view = "adminweb/rkpbu/posting_kiba";
		}elseif($get['Kd_Aset1'] == 2){
			$view = "adminweb/rkpbu/posting_kibb";
		}elseif($get['Kd_Aset1'] == 3){
			$view = "adminweb/rkpbu/posting_kibc";
		}elseif($get['Kd_Aset1'] == 4){
			$view = "adminweb/rkpbu/posting_kibd";
		}elseif($get['Kd_Aset1'] == 5){
			$view = "adminweb/rkpbu/posting_kibe";
		}elseif($get['Kd_Aset1'] == 6){
			$view = "adminweb/rkpbu/posting_kibf";
		}elseif($get['Kd_Aset1'] == 7){
			$view = "adminweb/rkpbu/posting_kibl";
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
				$sql = $this->Rkpbu_model->set_unpos(
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
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB A Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
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
				$sql = $this->Rkpbu_model->set_unpos(
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
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB B Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
					
	}


	function kibc_process()
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
						'No_Register'      => $reg,
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
						'Keterangan'       => $this->input->post('Keterangan'),
						'No_SP2D'          => $this->input->post('No_SP2D'),
						'Kd_Data'          => 1,
						'Kd_KA'            => 1,
						'Log_User'         => $this->session->userdata('username'),
						'Log_entry'        => date("Y-m-d H:i:s"));
						
			$sql = $this->Kibc_model->add($kibc);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB C berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Rkpbu_model->set_unpos(
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
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB C Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	function kibd_process()
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
			$kibd = array(
						'Kd_Prov'         => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'     => $this->session->userdata('kd_kab_kota'),
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
						'Asal_usul'       => $this->input->post('Asal_usul'),
						'Kondisi'         => $this->input->post('Kondisi'),
						'Harga'           => $this->input->post('Harga'),
						'Keterangan'      => $this->input->post('Keterangan'),
						'Tgl_Pembukuan'   => $this->input->post('Tgl_Pembukuan'),
						'Masa_Manfaat'    => $masa_manfaat,
						'Kd_Penyusutan'   => $metode,
						'Kd_Data'         => 1,
						'Kd_KA'           => 1,
						'Log_User'        => $this->session->userdata('username'),
						'Log_entry'       => date("Y-m-d H:i:s"));
			
			// print_r($kibd); exit();	
			$sql = $this->Kibd_model->add($kibd);

			// echo $this->db->_error_message(); exit();
				
				$reg++;
				$no++;
			}
			

			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB D berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Rkpbu_model->set_unpos(
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

				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB D Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
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
				$sql = $this->Rkpbu_model->set_unpos(
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
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB E Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	function kibf_process()
	{


			$jp 	= $this->input->post('jmlperc');
			$reg 	= $this->input->post('No_Register');
			
			$no=0;	
			for($i=0; $i<$jp; $i++){
			$kibf = array(
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
						'No_Register'      => $reg,
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
						
				$sql = $this->Kibf_model->add($kibf);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data KIB F berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Rkpbu_model->set_unpos(
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
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data KIB F Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	function kibl_process()
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
			$lainnya = array(
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
							'Nilai_Sisa'    => $this->input->post('Nilai_Sisa'),
							'Masa_Manfaat'  => $masa_manfaat,
							'Kd_Penyusutan' => $metode,
							'Kd_Data'       => 1,
							'Kd_KA'         => 1,
							'Log_User'      => $this->session->userdata('username'));
						
			$sql = $this->Lainnya_model->add($lainnya);
				
				$reg++;
				$no++;
			}
			
			if ($sql){
				$this->session->set_flashdata('message', $no.' data Aset lainya berhasil ditambah!,Silahkan tunggu verifikasi oleh admin');
				$sql = $this->Rkpbu_model->set_unpos(
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
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));		
			}else{		
				$this->session->set_flashdata('message', 'Satu data  Aset lainya Gagal ditambah!');
				redirect('rkpbu/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}

	function json_rekening(){
        $keyword = $this->input->post('term');
        $data['response'] = 'false';
        $query = $this->Ref_SSH_model->rekening_bm($keyword);
        if( ! empty($query) )
        {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach( $query as $row )
            {
                $data['message'][] = array(
                                        'id1'=>$row->Kd_Rek_1,
										'id2'=>$row->Kd_Rek_2,
										'id3'=>$row->Kd_Rek_3,
										'id4'=>$row->Kd_Rek_4,
										'id5'=>$row->Kd_Rek_5,
                                        'value' => $row->Nm_Rek_5,
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
            $this->load->view('rkpbu/index',$data);
        }
	}

}

/* End of file rkpbu.php */
/* Location: ./system/application/controllers/rkpbu.php */