<?php

class Pemanfaatan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		$this->load->model('Pemanfaatan_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
		$this->load->helper('sanjaya_helper');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = ' Data Pemanfaatan';
	
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
		$data['form_cari']	= site_url('pemanfaatan/cari');
		$data['link_kib']	= site_url('pemanfaatan/listupb');
		
		$data['header'] 	= "Pilih data SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']		= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('pemanfaatan/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('pemanfaatan/upb');
		
		$data['header'] 	= "Pilih data UPB".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data pemanfaatan kib a
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
		
		//print_r($q." - ".$s); exit();
		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "WHERE (Tgl_Dokumen LIKE '%$thn%')";
			$like2 	= "AND (Tgl_Dokumen LIKE '%$thn%')";
			$judul 	= "Tahun ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "WHERE Tgl_Dokumen BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND Tgl_Dokumen BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='Nama'){
			$like 	= "WHERE Nama_Pihak_1 LIKE '%$s%' OR Nama_Pihak_2 LIKE '%$s%'";
			$like2 	= "AND Nama_Pihak_1 LIKE '%$s%' OR Nama_Pihak_2 LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}elseif ($q=='Jabatan'){
			$like 	= "WHERE Jabatan_Pihak_1 LIKE '%$s%' OR Jabatan_Pihak_2 LIKE '%$s%'";
			$like2 	= "AND Jabatan_Pihak_1 LIKE '%$s%' OR Jabatan_Pihak_2 LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}elseif ($q=='all'){
			$like 	= "";
			$like2 	= "";
			$judul 	= "Semua Data pemanfaatan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "WHERE $q LIKE '%$s%'";
			$like2 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "WHERE $q LIKE '%$s%' AND Tgl_Dokumen BETWEEN '$tanggal1' AND '$tanggal2'";
			$like2 	= "AND $q LIKE '%$s%' AND Tgl_Dokumen BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";	}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset		= 0;	
		}else{
			$offset		= $page;
		}
		
		$data['title'] = $this->title;
		$data['judul'] = $judul;
		
			
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		
		$data['option_bidang'] = $this->Model_chain->getBidangList();	
		
		
		$nmupb             = $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query']     = $this->Pemanfaatan_model->get_page($this->limit, $offset,$like2);
		$num_rows          = $this->Pemanfaatan_model->count_kib($like)->num_rows();
		
		$data['header']    = $this->title.' | '.$nmupb;
		$data['jumlah']    = $num_rows;
		
		$data['offset']    = $offset;
		$data['form_cari'] = current_URL();
		
		$data['option_q'] = array(''=>'- pilih pencarian -','Jenis_Dokumen'=>'Jenis Dokumen','No_Dokumen'=>'Nomor Dokumen','Nama'=>'Nama','Jabatan'=>'Jabatan','Keterangan'=>'Keterangan','all'=>'Semua Data');
		
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
			$data['message'] = 'Tidak ditemukan data pemanfaatan!';
		}		

		$this->template->load('template','adminweb/pemanfaatan/pemanfaatan',$data);
	}

	/**
	 * tambah pemanfaatan
	 */
	function add()
	{		
		$data['default']['Kd_Bidang'] 			= $this->session->userdata('addKd_Bidang');
		$data['default']['Kd_Unit'] 			= $this->session->userdata('addKd_Unit');
		$data['default']['Kd_Sub'] 				= $this->session->userdata('addKd_Sub');
		$data['default']['Kd_UPB'] 				= $this->session->userdata('addKd_UPB');
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pemanfaatan > Tambah Data';
		$data['form_action']	= site_url('pemanfaatan/add_process');
		$data['link'] 			= array('link_back' => anchor('pemanfaatan','kembali', array('class' => 'back'))
										);
										
		$nmupb				= $this->Ref_upb_model->nama_upb($this->session->userdata('addKd_Bidang'),
															 $this->session->userdata('addKd_Unit'),
															 $this->session->userdata('addKd_Sub'),
															 $this->session->userdata('addKd_UPB'));								
		$data['header'] 		= $nmupb.' | ';
		
		$this->template->load('template','adminweb/pemanfaatan/pemanfaatan_addform',$data);
	}
	
	function add_process(){
	
	    $data = $this->input->post(null,true);
	    if($this->Pemanfaatan_model->save($data)){
				$this->session->set_flashdata('message', 'data pemanfaatan berhasil ditambah !');
				redirect('pemanfaatan/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
	    }else{
	        
	        show_error("Error occured, please try again");
	    }
	}

	function update_process(){
	
	    $data = $this->input->post(null,true);
	    if($this->Pemanfaatan_model->update($data)){
				$this->session->set_flashdata('message', 'data pemanfaatan berhasil diubah !');
				redirect('pemanfaatan/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
	    }else{
	        
	        show_error("Error occured, please try again");
	    }
	}

	function update($jenis,$id){
		$no = decrypt_url($id);

		//print_r($no); exit();

		$get  = $this->Pemanfaatan_model->get_data_id($jenis,$no);
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

	    $session_data = array(
				'No_Dokumen_tmp'    => $get['No_Dokumen'],
				'Jenis_Dokumen_tmp' => $get['Jenis_Dokumen'],
				'Kd_Prov_tmp'       => $get['Kd_Prov'],
				'Kd_Kab_Kota_tmp'   => $get['Kd_Kab_Kota'],
				'Kd_Bidang_tmp'     => $get['Kd_Bidang'],
				'Kd_Unit_tmp'       => $get['Kd_Unit'],
				'Kd_Sub_tmp'        => $get['Kd_Sub'],
				'Kd_UPB_tmp'        => $get['Kd_UPB'],
			);
			
		$this->session->set_userdata($session_data);

		$data['title']          = $this->title;
		$data['form_action']    = site_url('pemanfaatan/update_process');
		
		$nmupb                  = $this->Ref_upb_model->nama_upb($get['Kd_Bidang'], $get['Kd_Unit'], $get['Kd_Sub'], $get['Kd_UPB']); 
		
		$data['h2_title']       = 'Pemanfaatan > Ubah Data - '.$nmupb;
		$data['jumlah_rincian'] = $this->Pemanfaatan_model->get_rincian($get['No_Dokumen'])->num_rows();
		$data['rincian']        = $this->Pemanfaatan_model->get_rincian($get['No_Dokumen']);
	    $this->template->load('template','adminweb/pemanfaatan/pemanfaatan_updateform',array_merge($data,$get));

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

	function json(){
        $keyword = $this->input->post('term');
        $data['response'] = 'false'; 
        $query = $this->Pemanfaatan_model->json_all($keyword); 
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
										'id6'=>$row->No_Register,
                                        'value' => $row->Nm_Aset5.' ('.$row->No_Register.') tahun '.$row->Tahun,
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
            $this->load->view('pemanfaatan/index',$data); 
        }
	}

	function nomor(){
			$jenis_dokumen = $this->input->post('Jenis_Dokumen');
			$kd_bidang     = $this->session->userdata('addKd_Bidang');
			$kd_unit       = $this->session->userdata('addKd_Unit');
			$sub_unit      = $this->session->userdata('addKd_Sub');
			$kd_upb        = $this->session->userdata('addKd_UPB');
			
			echo $this->Pemanfaatan_model->get_last_momor($kd_bidang,$kd_unit,$sub_unit,$kd_upb,$jenis_dokumen);
			}

	function delete(){
			$no_dokumen  = decrypt_url($this->input->post('no_dokumen'));
			
			$this->Pemanfaatan_model->delete($no_dokumen);
	}
		
	function ajax_hapus(){
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
		
			$kd_aset1x   = $this->input->post('kd_aset1');
			$kd_aset2x   = $this->input->post('kd_aset2');
			$kd_aset3x   = $this->input->post('kd_aset3');
			$kd_aset4x   = $this->input->post('kd_aset4');
			$kd_aset5x   = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			$no_dokumen  = decrypt_url($this->input->post('no_dokumen'));
			
			$this->Pemanfaatan_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,
								 $kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register,$no_dokumen);
	}
	
	
	
}

/* End of file pemanfaatan.php */
/* Location: ./system/application/controllers/pemanfaatan.php */