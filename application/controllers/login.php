<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Login_model', '', TRUE);
		$this->load->library('recaptcha');
		$this->load->helper(array('captcha'));
	}
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman absen,
	 * jika tidak akan meload halaman login
	 */

	
	public function index()
	{

		$config_captcha = array(
		    'img_path'  => './captcha/',
		    'img_url'  => base_url().'captcha/',
		    'img_width'  => '150',
		    'img_height' => 38,
		    'font_path'  => './captcha/Cracked Code.ttf',
		    'word'       => strtoupper(getRandomString()),
		    'border' => 0, 
		    'expiration' => 7200
		   );
		
		// create captcha image
		$cap         = create_captcha($config_captcha);
		
		// store image html code in a variable
		$data['img'] = $cap['image'];
		
		// store the captcha word in a session
		$this->session->set_userdata('mycaptcha', $cap['word']);
		   

		if($this->auth->is_logged_in() == false)
		{
			$this->load->view('login/login_view',$data);
		}
		else
		{
			redirect('adminweb');
		}
	}
	
	/**
	 * Memproses login
	 */
	function proses_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');


		
		if ($this->form_validation->run() == TRUE)
		{	
			$username 	= $this->input->post('username');
			$password 	= $this->input->post('password');
			$tahun 	  	= $this->input->post('tahun');

			$capth    = strtoupper( $this->input->post('kode_aman'));

			if($this->session->userdata('mycaptcha')!=$capth){
				$this->session->set_flashdata('message', 'Kesalahan pada kode keamanan !!');
				redirect('login/index/error');
			}else{
				$success  	= $this->auth->do_login($username,$password,$tahun);
				if($success){
					// $this->db->update('tb_users',array('last_login'=>date('Y-m-d H:i:s')),array('id_user'=> $this->session->userdata('id_user')));
					redirect('adminweb/home');
				}else{
					$this->session->set_flashdata('message', 'Maaf, username dan atau password Anda salah, Atau Account anda sedang diblokir, silahkan Hubungi administrator untuk mengaktifkan kembali!!');
					redirect('login/index/error');
				}
			}		

		}
		else
		{
			$this->load->view('login/login_view');
		}
	}
	
	
	/**
	 * Memproses logout
	 */
	function logout()
	{
		$this->session->sess_destroy();
		redirect('login', 'refresh');
	}
	
}