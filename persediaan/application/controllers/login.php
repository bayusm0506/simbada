<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Login_model', '', TRUE);
		$this->load->library('recaptcha');
	}
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman absen,
	 * jika tidak akan meload halaman login
	 */

	
	public function index()
	{
		if($this->auth->is_logged_in() == false)
		{
			$this->load->view('login/login_view');
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

			$captcha_answer = $this->input->post('g-recaptcha-response');

			// Verify user's answer
			// $response = $this->recaptcha->verifyResponse($captcha_answer);

			// Processing ...
			// if ($response['success']) {
			if (1==1) {
				$success  	= $this->auth->do_login($username,$password,$tahun);
				if($success)
				{
					redirect('adminweb');
				}
				else
				{
					$this->session->set_flashdata('message', 'Maaf, username dan atau password Anda salah, Atau Account anda sedang diblokir, silahkan Hubungi administrator untuk mengaktifkan kembali!!');
					redirect('login/index');	
				}
			} else {
			   	$this->session->set_flashdata('message', 'Silahkan checklist \'SAYA BUKAN ROBOT\' ');
				redirect('login/index');
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