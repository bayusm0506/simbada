<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jabatan extends CI_Controller{
	private $_mod_url;

	public function __construct(){
		
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('JABATAN',VIEW))
		    $this->general->no_access();
		$this->load->model('jabatan_model');
		$this->load->model('privilege_model');
		$this->_mod_url = base_url().'jabatan';
	}

	 private function _render($view,$data = array()){
	    $this->template->load('template',$view,$data);
	}
	
	public function index(){
	  
	    $data['title'] = 'Jabatan >> Cpanel';
	    $data['header']= 'Data Jabatan User';
    
		$data['query']   = $this->jabatan_model->get_data();

	    $this->template->load('template','adminweb/jabatan/jabatan',$data);
	}
	
	
	public function add(){
	    
	    if(!$this->general->privilege_check('JABATAN',ADD))
		    $this->general->no_access();
	    
		$data['title']       =	'Cpanel >> Tambah Jabatan';
		$data['header']      =	'Tambah Jabatan';
		$data['form_action'] = 	site_url('jabatan/save');	
        $this->_render('jabatan/add',$data);		
	    	   
	}
	
	public function edit(){
	    
	    if(!$this->general->privilege_check('JABATAN','edit'))
		    $this->general->no_access();
	    
	    $id  = $this->uri->segment(3);
	    $get = $this->db->get_where('jabatan_user',array('id'=>$id))->row_array();
	    if(!$get)
	        show_404();
	    
	    $readonly = '';
	    $this->session->set_userdata('id_jabatan_tmp', $get['id']); 
	    if($id==1 or $id==4)
	        $readonly="readonly";

	    $data['title']       =	'Cpanel >> Edit Jabatan';
		$data['header']      =	'Edit Jabatan';
		$data['readonly']	 =	$readonly;
		$data['id']	 		 =	$id;
		$data['row']	 	 =	$get;
		$data['form_action'] = 	site_url('jabatan/update');	
        $this->_render('adminweb/jabatan/edit',$data);	
	    	   
	}
	
	/*carefull here...*/
	public function delete(){
	    
	    if(!$this->general->privilege_check('JABATAN','remove'))
		    $this->general->no_access();
	    
	    $id = $this->uri->segment(3);
	    if($id==1)
	        show_error("Administrator Cannot be Removed");
	        	    
	    /*hapus smw yg berhubungan dgn jabatan*/
		$this->db->trans_begin();
		    $this->db->delete('jabatan_user',array('id'=>$id));
			$this->db->delete('user',array('jabatan_id'=>$id));
			$this->db->delete('akses_user',array('jabatan_id'=>$id));
		if($this->db->trans_status()==false){
			
			show_error("Error Occured, please repeat");
			echo json_encode(array('status'=>false,'msg'=>'error occured'));
		}else{
			
			$this->db->trans_commit();
			 redirect('jabatan');
		}
	}
	
	public function save(){
	    
	     $data = $this->input->post(null,true);
	     
	     $send = $this->jabatan_model->save($data);
	     if($send)
	        redirect('jabatan');
	}
	
	public function update(){
	    
	     $data = $this->input->post(null,true);
	     $send = $this->jabatan_model->update($data);
	     if($send)
	        redirect('jabatan');
	}
	
	private function _paging($total,$limit){
	
	    $config = array(
                
            'base_url'  => base_url().'jabatan/get_data/',
            'total_rows'=> $total, 
            'per_page'  => $limit,
			'uri_segment'=> 3
        
        );
        $this->pagination->initialize($config); 

        return $this->pagination->create_links();
	}
	
	
}
