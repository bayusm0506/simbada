<?php

class Dataumum extends CI_Controller {
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
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = 'Parameter | Data Umum UPB';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman dataumum,
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
		$data['form_cari']	= site_url('dataumum/cari');
		$data['link_kib']	= site_url('dataumum/listupb');
	
		$data['header'] 	= "DATA SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']			= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('dataumum/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('dataumum/upb');
	
		$data['header'] 	= "DATA UPB ".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']			= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('dataumum/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data dataumum
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
		$tahun = $this->session->userdata('tahun_anggaran');
			
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Umum > Update';
		$data['form_action']	= site_url('dataumum/update_process');
		$data['link'] 			= array('link_back' => anchor('dataumum','kembali', array('class' => 'back'))
										);
										
		$nmupb				= $this->Ref_upb_model->nama_upb($bidang,$unit,$sub,$upb);								
		$data['header'] 	= "Data Umum ".$nmupb;
		$jumlah = $this->Ta_upb_model->get_data_umum($bidang,$unit,$sub,$upb,$tahun)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$dataumum = $this->Ta_upb_model->get_data_umum($bidang,$unit,$sub,$upb,$tahun)->row();
				
				$data['default']['Tahun'] 				= $dataumum->Tahun;
				$data['default']['Kd_Bidang'] 			= $dataumum->Kd_Bidang;
				$data['default']['Kd_Unit'] 			= $dataumum->Kd_Unit;
				$data['default']['Kd_Sub'] 				= $dataumum->Kd_Sub;
				$data['default']['Kd_UPB'] 				= $dataumum->Kd_UPB;				
				$data['default']['Nm_Pimpinan'] 		= $dataumum->Nm_Pimpinan;
				$data['default']['Nip_Pimpinan'] 		= $dataumum->Nip_Pimpinan;
				$data['default']['Jbt_Pimpinan'] 		= $dataumum->Jbt_Pimpinan;
				$data['default']['Nm_Pengurus'] 		= $dataumum->Nm_Pengurus;
				$data['default']['Nip_Pengurus'] 		= $dataumum->Nip_Pengurus;
				$data['default']['Jbt_Pengurus'] 		= $dataumum->Jbt_Pengurus;
				$data['default']['Nm_Penyimpan'] 		= $dataumum->Nm_Penyimpan;
				$data['default']['Nip_Penyimpan'] 		= $dataumum->Nip_Penyimpan;
				$data['default']['Jbt_Penyimpan'] 		= $dataumum->Jbt_Penyimpan;
				$data['default']['Alamat'] 				= $dataumum->Alamat;
								
				$this->template->load('template','adminweb/dataumum/dataumum',$data);				
		}else{
				$data['default']['Tahun'] 				= $tahun;
				$data['default']['Kd_Bidang'] 			= $this->session->userdata('addKd_Bidang');
				$data['default']['Kd_Unit'] 			= $this->session->userdata('addKd_Unit');
				$data['default']['Kd_Sub'] 				= $this->session->userdata('addKd_Sub');
				$data['default']['Kd_UPB'] 				= $this->session->userdata('addKd_UPB');
			
				$this->template->load('template','adminweb/dataumum/dataumum',$data);
		}
	}
	
	
	/**
	 * Proses update data dataumum
	 */
	function update_process()
	{
		
					$dataumum = array(
						'Tahun'         => $this->input->post('Tahun'),
						'Kd_Prov'       => $this->session->userdata('kd_prov'),
						'Kd_Kab_Kota'   => $this->session->userdata('kd_kab_kota'),
						'Kd_Bidang'     => $this->input->post('Kd_Bidang'),
						'Kd_Unit'       => $this->input->post('Kd_Unit'),
						'Kd_Sub'        => $this->input->post('Kd_Sub'),
						'Kd_UPB'        => $this->input->post('Kd_UPB'),
						'Nm_Pimpinan'   => $this->input->post('Nm_Pimpinan'),
						'Nip_Pimpinan'  => $this->input->post('Nip_Pimpinan'),
						'Jbt_Pimpinan'  => $this->input->post('Jbt_Pimpinan'),
						'Nm_Pengurus'   => $this->input->post('Nm_Pengurus'),
						'Nip_Pengurus'  => $this->input->post('Nip_Pengurus'),
						'Jbt_Pengurus'  => $this->input->post('Jbt_Pengurus'),
						'Nm_Penyimpan'  => $this->input->post('Nm_Penyimpan'),
						'Nip_Penyimpan' => $this->input->post('Nip_Penyimpan'),
						'Jbt_Penyimpan' => $this->input->post('Jbt_Penyimpan'),
						'Alamat'        => $this->input->post('Alamat')
						);

			$kd_bidang		= $this->input->post('Kd_Bidang');
			$kd_unit		= $this->input->post('Kd_Unit');
			$kd_sub			= $this->input->post('Kd_Sub');
			$kd_upb			= $this->input->post('Kd_UPB');
			$tahun 			= $this->session->userdata('tahun_anggaran');

			$jumlah 		= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->num_rows();
		
		if ($jumlah > 0){			
			$sql = $this->Ta_upb_model->update($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun,$dataumum);
		}else{
			$sql = $this->Ta_upb_model->add($dataumum);
		}
		
		if ($sql){
			echo "<script>alert('Data Umum berhasil diupdate!');javascript:history.back()</script>";
		}else{
			echo "<script>alert('Data Umum Gagal diupdate!');javascript:history.back()</script>";
		}
			
				 
	}
	
	
	/**
	 * Tampilkan data dataumum untuk operator
	 */
	function get_upb()
	{
		$bidang = $this->session->userdata('kd_bidang');
		$unit   = $this->session->userdata('kd_unit');
		$sub    = $this->session->userdata('kd_sub_unit');
		$upb    = $this->session->userdata('kd_upb');
		$tahun  = $this->session->userdata('tahun_anggaran');
			
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Umum > Update';
		$data['form_action']	= site_url('dataumum/update_process');
		$data['link'] 			= array('link_back' => anchor('dataumum','kembali', array('class' => 'back'))
										);
										
		$nmupb				= $this->Ref_upb_model->nama_upb($bidang,$unit,$sub,$upb);								
		$data['header'] 	= "Data Umum ".$nmupb;
		$jumlah = $this->Ta_upb_model->get_data_umum($bidang,$unit,$sub,$upb,$tahun)->num_rows();
																				  										  
												  
		if ($jumlah > 0){
			$dataumum = $this->Ta_upb_model->get_data_umum($bidang,$unit,$sub,$upb,$tahun)->row();
				
				$data['default']['Tahun'] 				= $dataumum->Tahun;
				$data['default']['Kd_Bidang'] 			= $dataumum->Kd_Bidang;
				$data['default']['Kd_Unit'] 			= $dataumum->Kd_Unit;
				$data['default']['Kd_Sub'] 				= $dataumum->Kd_Sub;
				$data['default']['Kd_UPB'] 				= $dataumum->Kd_UPB;				
				$data['default']['Nm_Pimpinan'] 		= $dataumum->Nm_Pimpinan;
				$data['default']['Nip_Pimpinan'] 		= $dataumum->Nip_Pimpinan;
				$data['default']['Jbt_Pimpinan'] 		= $dataumum->Jbt_Pimpinan;
				$data['default']['Nm_Pengurus'] 		= $dataumum->Nm_Pengurus;
				$data['default']['Nip_Pengurus'] 		= $dataumum->Nip_Pengurus;
				$data['default']['Jbt_Pengurus'] 		= $dataumum->Jbt_Pengurus;
				$data['default']['Nm_Penyimpan'] 		= $dataumum->Nm_Penyimpan;
				$data['default']['Nip_Penyimpan'] 		= $dataumum->Nip_Penyimpan;
				$data['default']['Jbt_Penyimpan'] 		= $dataumum->Jbt_Penyimpan;
				$data['default']['Alamat'] 				= $dataumum->Alamat;
								
				$this->template->load('template','adminweb/dataumum/dataumum',$data);				
		}else{
				$data['default']['Tahun'] 				= $tahun;
				$data['default']['Kd_Bidang'] 			= $this->session->userdata('addKd_Bidang');
				$data['default']['Kd_Unit'] 			= $this->session->userdata('addKd_Unit');
				$data['default']['Kd_Sub'] 				= $this->session->userdata('addKd_Sub');
				$data['default']['Kd_UPB'] 				= $this->session->userdata('addKd_UPB');
			
				$this->template->load('template','adminweb/dataumum/dataumum',$data);
		}
	}
	
	
		
	/**
	 * Hapus data Kib a
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$this->Ta_upb_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('dataumum');
	}
	
	/**
	 * Menghapus dengan ajax post
	 */
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
		
			$kd_aset1x = $this->input->post('kd_aset1');
			$kd_aset2x = $this->input->post('kd_aset2');
			$kd_aset3x = $this->input->post('kd_aset3');
			$kd_aset4x = $this->input->post('kd_aset4');
			$kd_aset5x = $this->input->post('kd_aset5');
			$no_register = $this->input->post('no_register');
			
		$this->Ta_upb_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_register);	
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
	        		
		$count = $this->Ta_upb_model->count_buku($where);
		
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		
		$data1 = $this->Ta_upb_model->get_buku($where, $sidx, $sord, $limit, $start)->result();
	
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
        $query = $this->Ref_rek_aset5_model->json_dataumum($keyword);
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
            $this->load->view('dataumum/index',$data); 
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
			
       
$num_rows = $this->Ta_upb_model->get_last_noregister($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x);		
		
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
			
			$num_rows = $this->Ta_upb_model->cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1x,$kd_aset2x,$kd_aset3x,$kd_aset4x,$kd_aset5x,$no_registerx)->num_rows();
			echo $num_rows;
			}
	
	/**
	 * Cek apakah $id_dataumum valid, agar tidak ganda
	 */
	function valid_no_register($no_register)
	{
		if ($this->Ta_upb_model->no_register($no_register) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "dataumum dengan Kode $id_dataumum sudah terdaftar");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $id_dataumum valid, agar tidak ganda. Hanya untuk proses update data dataumum
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
if($this->Ta_upb_model->valid_no_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register) === TRUE)
			{
				$this->form_validation->set_message('valid_no_register2', "dataumum dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
}

/* End of file dataumum.php */
/* Location: ./system/application/controllers/dataumum.php */