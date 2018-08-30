<?php

class User extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('USER',VIEW))
		    $this->general->no_access();
		$this->load->model('User_model', '', TRUE);
		$this->load->model('Model_chain', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = 'USER';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman User,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		$this->auth->restrict();
		if ($this->session->userdata('lvl') == 03){
			$this->get_data();
		}else{
			$this->get_data_user();
		}
	}
	
	/**
	 * Tampilkan semua data Admin
	 */
	function get_data_user()
	{
		$this->auth->restrict();
		$q 		= $this->input->get('q', TRUE);
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();		
		$data['title'] 		= $this->title;

		$data['option_q'] = array(''=>'- Pilih -','Nm_UPB'=>'Nama UPB','username'=>'Username','nama_lengkap'=>'Nama Lengkap',
		'no_telp'=>'No Telp','blokir'=>'Blokir','lvl'=>'Level');
	
	
		if (empty($q) && empty($s)){
			$like= '';
		}elseif (empty($q)){
			$like = array('Nm_UPB' => $s);
		}else{
			$like = array($q => $s);
		}
		
		$data['query']		= $this->User_model->get_data_user($like);
		$data['header'] 	= "Data User";
		
		
		$data['link'] = array('link_add' => anchor('user/add/','tambah data', array('class' => ADD)));
		$this->template->load('template','adminweb/user/user',$data);
	}
	
	
	/**
	 * Pindah ke halaman tambah kiba
	 */
	function add()
	{	
		if(!$this->general->privilege_check('USER',ADD))
		    $this->general->no_access();

		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'User > Tambah Data';
		$data['form_action']	= site_url('user/add_process');
				
		$data['header'] 		= $this->title;
		$data['option_bidang']  = $this->Model_chain->getBidangList();
		
		$data['option_level']   = $this->Model_chain->getLevelList();
		
		$data['option_jabatan'] = $this->Model_chain->getJabatanList();
		
		$this->template->load('template','adminweb/user/user_addform',$data);
	}
	
	/**
	 * Proses tambah data user
	 */
	function add_process()
	{
			$id = $this->User_model->get_last_id();
			$user = array(
						'id_user'      => $id,
						'username'     => $this->input->post('username'),
						'password'     => md5($this->input->post('password')),
						'nama_lengkap' => $this->input->post('nama_lengkap'),
						'email'        => $this->input->post('email'),
						'no_telp'      => $this->input->post('no_telp'),
						'kd_prov'      => $this->session->userdata('kd_prov'),
						'kd_kab_kota'  => $this->session->userdata('kd_kab_kota'),
						'kd_bidang'    => $this->input->post('kd_bidang'),
						'kd_unit'      => $this->input->post('kd_unit'),
						'kd_sub_unit'  => $this->input->post('kd_sub_unit'),
						'kd_upb'       => $this->input->post('kd_upb'),
						'blokir'       => 'N',
						'lvl'          => $this->input->post('lvl'),
						'jabatan_id'   => $this->input->post('jabatan_id'));

			// print_r($user); exit();
			
			$sql = $this->User_model->add($user);

			// var_dump($sql); exit();
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data user berhasil ditambah!');
				redirect('user');
			}else{		
				$this->session->set_flashdata('message', 'Satu data user Gagal ditambah!');
				redirect('user');
				}	
	}
	
	
	/**
	 * Tampilkan data User
	 */
	function get_data()
	{
			
		$this->auth->restrict();
		$data['form_action']	= site_url('user/update_process');
		
		$data['header'] 	= "GANTI PASSWORD";
		
		$data['title'] 		= "Pengaturan User";
		
		$id_user			=  $this->session->userdata('id_user');
		
		$user = $this->User_model->get_data_user_by_id($id_user)->row();
		
		$data['default']['username'] 			= $user->username;
		$data['default']['password'] 			= $user->password;
		$data['default']['nama_lengkap'] 		= $user->nama_lengkap;
		$data['default']['email'] 				= $user->email;
		$data['default']['no_telp'] 			= $user->no_telp;
		
		$this->template->load('template','adminweb/user/user_updateform',$data);
	}
	
	/**
	 * Pindah ke halaman update user
	 */
	function update()
	{

		$x = $this->uri->segment(3);
		
		if(!empty($x)){
			$id = $x;
		}else{
			$id = $this->session->userdata('id_user');
		}

		$get  = $this->User_model->get_data_user_by_id($id)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");
	        
		$this->session->set_userdata('id_user_tmp', $get['id_user']); 

		$data['header'] 	= "GANTI PASSWORD";
		$data['title'] 		= "Pengaturan User";

		$data['option_bidang']  = $this->Model_chain->getBidangList();
		
		$data['option_level']   = $this->Model_chain->getLevelList();
		
		$data['option_jabatan'] = $this->Model_chain->getJabatanList();
		
		$data['form_action']	= site_url('user/update_process');
										
		$this->template->load('template','adminweb/user/user_updateform',array_merge($data,$get));
	}
	
	/**
	 * Proses update data kiba
	 */
	function update_process()
	{
			if ($this->input->post('newpassword2') != ""){
				$user = array(
						'username'     => $this->input->post('username'),
						'password'     => md5($this->input->post('newpassword2')),
						'nama_lengkap' => $this->input->post('nama_lengkap'),
						'email'        => $this->input->post('email'),
						'no_telp'      => $this->input->post('no_telp')
				);
			}else{
				$user = array(
						'username'     => $this->input->post('username'),
						'nama_lengkap' => $this->input->post('nama_lengkap'),
						'email'        => $this->input->post('email'),
						'no_telp'      => $this->input->post('no_telp')
				);		
			}

			$lvl = $this->input->post('lvl');
			if(!empty($lvl)){
	            $user['lvl'] = $this->input->post('lvl');
	        }
	        $jabatan_id = $this->input->post('jabatan_id');
	        if(!empty($jabatan_id)){
	            $user['jabatan_id'] = $this->input->post('jabatan_id');
	        }

			// print_r($user);exit();
			$sql = $this->User_model->update($this->session->userdata('id_user_tmp'),$user);
			
			if ($sql){
				$this->session->set_flashdata('message', 'Satu data User berhasil diupdate!');
				redirect('user');
			}else{		
				$this->session->set_flashdata('message', 'Satu data User Gagal diupdate!');
				redirect('user');
				}
	}
	
	/**
	 * Menghapus dengan ajax post
	 */
	function ajax_hapus(){
		$id_user = $this->input->post('id_user');
		$this->User_model->delete($id_user);	
	}
	
	/**
	 * Mengaktifkan sebuah user
	 */	
	function aktifkan($id_user)
	{
		$aktif = $this->User_model->aktif($id_user);
		if($aktif == TRUE)
		{
			$this->session->set_flashdata('message', 'Proses berhasil...');
			redirect('user');
		}
		else
		{	
			$this->session->set_flashdata('message', 'Proses gagal...');
			redirect('user');
		}
	}
	
	/**
	 * menonaktifkan sebuah semester
	 */	
	function nonaktifkan($id_user)
	{
		$nonaktif = $this->User_model->nonaktif($id_user);
		if($nonaktif == TRUE)
		{
			$this->session->set_flashdata('message', 'Proses berhasil...');
			redirect('user');
		}
		else
		{	
			$this->session->set_flashdata('message', 'Proses gagal...');
			redirect('user');
		}
	}	
	
	
}

/* End of file User.php */
/* Location: ./system/application/controllers/User.php */