<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chatting extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('User_model', '', TRUE);
		$this->auth->restrict();
	}
	
	public function index()
	{
		$q 		= $this->input->get('q', TRUE);
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();		
		$data['title'] 		= 'Chatting';
		$data['query']		= $this->User_model->get_all_user();
		$data['header'] 	= "Data User";
		
		$this->template->load('template','chat/user',$data);
	}
	
	function chat($me='', $you='')
    {
        $data['me']  = $me;
        $data['you'] = $you;
		$newdata = array(
				   'username' => 'sanjaya',
                   'me'  => $me,
                   'you'     => $you
               );

		$this->session->set_userdata($newdata);
        $this->load->view('chat/chatty', $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

