<?php

class Kebijakan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		$this->load->model('Kebijakan_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = ' Data Kebijakan';
	
	/**
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		$this->get_data();
	}
	
	
	/**
	 * Tampilkan semua data skpd
	 */
	function get_data()
	{
		$data['form_cari']	= site_url('pemanfaatan/cari');
		$data['link_kib']	= site_url('pemanfaatan/listupb');
		
		$data['header'] 	= " Data Kebijakan Akuntansi";
		
		if($this->session->userdata('tahun')){
			$data['judul'] 		= "Kebijakan Akuntansi Tahun ".$this->session->userdata('tahun');
		}else{
			$data['judul'] 		= "Kebijakan Akuntansi Tahun ".$this->session->userdata('tahun_anggaran');
		}
		$data['option_q'] 	= range_year(2010,date("Y"));
		
		$data['link'] = array('link_add' => anchor('pemanfaatan/add/','tambah data', array('class' => ADD)));

		$page		= $this->input->get('per_page', TRUE);
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset = 0;	
		}else{
			$offset = $page;
		}

		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();


		if($this->session->userdata('tahun')){
			$like = $this->session->userdata('tahun');
		}else{
			$like = $this->session->userdata('tahun_anggaran');
		}

		$data['query'] 		= $this->Kebijakan_model->get_page($this->limit, $offset,$like);

		$this->template->load('template','adminweb/kebijakan/data',$data);
	}

	/**
	 * tambah pemanfaatan
	 */
	function add()
	{		
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Kebijakan > Tambah Data';
		$data['form_action']	= site_url('kebijakan/add_process');
		$data['link'] 			= array('link_back' => anchor('kebijakan','kembali', array('class' => 'back'))
										);							
		$data['header'] 		= ' | ';
		
		$this->template->load('template','adminweb/kebijakan/kebijakan_addform',$data);
	}
	
	function add_process(){
	
	    $data = $this->input->post(null,true);
	    if($this->Kebijakan_model->save($data)){
				$this->session->set_flashdata('message', 'data kebijakan berhasil ditambah !');
				redirect('kebijakan');
	    }else{
	        show_error("Error occured, please try again");
	    }
	}

	function update($tahun,$kd_aset){

		$get  = $this->Kebijakan_model->get_data_id($tahun,$kd_aset);
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

	    $session_data = array(
				'Tahun'    => $get['Tahun'],
				'Kd_Aset1' => $get['Kd_Aset1']
			);

		$this->session->set_userdata($session_data);

		$data['title']          = $this->title;
		$data['form_action']    = site_url('kebijakan/update_process');

		$data['h2_title']       = 'Kebijakan > Ubah Data ';
	    $this->template->load('template','adminweb/kebijakan/kebijakan_updateform',array_merge($data,$get));

	}


	function update_process(){
	
	    $data = $this->input->post(null,true);
	    if($this->Kebijakan_model->update($data)){
				$this->session->set_flashdata('message', 'data kebijakan berhasil diubah !');
				redirect('kebijakan');
	    }else{
	        
	        show_error("Error occured, please try again");
	    }
	}

	
	
	public function set()
	{
			$session_data = array(
				'tahun' 		=> $this->input->post('tahun')
			);
			
			$this->session->set_userdata($session_data);
			header('location:'.$this->session->userdata('curl'));
	}

	function delete(){
			$Tahun    = $this->input->post('Tahun');
			$Kd_Aset1 = $this->input->post('Kd_Aset1');
			
			$this->Kebijakan_model->delete($Tahun,$Kd_Aset1);
	}
	
	
}

/* End of file pemanfaatan.php */
/* Location: ./system/application/controllers/pemanfaatan.php */