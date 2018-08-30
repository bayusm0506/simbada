<?php
/**
 * Help Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Help extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * Memeriksa Help state, jika dalam keadaan login akan menampilkan halaman Help,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if($this->auth->is_logged_in() == TRUE)
		{
			// Load view
		$data['header'] 	= "ABOUT SIMDO V.1 Beta";
					
		$this->template->set('title','ABOUT SIMDO V.1 Beta');
		$this->template->load('template','adminweb/help/help',$data);
		}
		else
		{
			redirect('login');
		}
		
	}
	
}
// END Help Class

/* End of file Help.php */
/* Location: ./system/application/controllers/Help.php */