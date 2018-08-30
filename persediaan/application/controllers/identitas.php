<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Identitas extends CI_Controller{
	private $_mod_url;
	
	public function __construct(){
		
		parent::__construct();
		$this->auth->restrict();
		if(!$this->general->privilege_check('IDENTITAS',VIEW))
		    $this->general->no_access();
		$this->load->model('Identitas_model');
		$this->_mod_url = base_url().'adminweb/identitas';
		$this->load->helper('form');
	}
	
	private function _render($view,$data = array()){
	    $this->template->load('template',$view,$data);
	}

	
	public function index(){
	  
	    // if(!$this->general->privilege_check('IDENTITAS','edit'))
		   //  $this->general->no_access();
	    	    
	    $get = $this->db->get('tb_identitas')->row_array();
	    if(!$get)
	        show_404();
	        
		$this->session->set_userdata('id_identitas_tmp', $get['id_identitas']); 
	   
	    $data['title']			=	'Cpanel >> Identitas >> Edit Identitas';
	    $data['header']			=	'Edit Identitas Website';

		$data['form_action']	= 	site_url('identitas/update');
        $this->_render('adminweb/identitas/edit',array_merge($data,$get));
	}

	public function update(){
	   $data = $this->input->post(null,true);
	   /*print_r($data); exit();*/
	   if(!empty($_FILES['userfile']['name'])){
	   		$this->load->library('upload');
            $this->upload->initialize(array(
                'file_name'     => $_FILES['userfile']['name'],
                'upload_path'   => './asset/img',
                'allowed_types' => 'gif|jpg|jpeg|png',
                'max_size'      => '2000'
            ));
            
		    if ($this->upload->do_upload()){
				$info                  = $this->upload->data();
				$data['logo']          = $info['file_name'];
				/* PATH */
				$source                = "./asset/img/".$info['file_name'] ;
				$destination_thumb     = "./asset/img/thumbnail/" ;
				
				// Permission Configuration
				chmod($source, 0777) ;
				
				/* Resizing Processing */
				// Configuration Of Image Manipulation :: Static
				$this->load->library('image_lib') ;
				$img['image_library']  = 'GD2';
				$img['create_thumb']   = TRUE;
				$img['maintain_ratio'] = TRUE;
				
				/// Limit Width Resize
				$limit_thumb           = 300 ;
				
				// Size Image Limit was using (LIMIT TOP)
				$limit_use             = $info['image_width'] > $info['image_height'] ? $info['image_width'] : $info['image_height'] ;
				
				// Percentase Resize
				if ($limit_use > $limit_thumb) {
					$percent_thumb     = $limit_thumb / $limit_use ;
				}
				
				//// Making THUMBNAIL ///////
				$img['width']          = $limit_use > $limit_thumb ?  $info['image_width'] * $percent_thumb : $info['image_width'] ;
				$img['height']         = $limit_use > $limit_thumb ?  $info['image_height'] * $percent_thumb : $info['image_height'] ;
				
				// Configuration Of Image Manipulation :: Dynamic
				//$img['thumb_marker'] = '_thumb-'.floor($img['width']).'x'.floor($img['height']) ;
				$img['thumb_marker']   = '';
				$img['quality']        = '100%' ;
				$img['source_image']   = $source ;
				$img['new_image']      = $destination_thumb.'thumb_'.$info['file_name'] ;
				
				// Do Resizing
				$this->image_lib->initialize($img);
				$this->image_lib->resize();
				$this->image_lib->clear() ;
		    }else{
				$this->session->set_flashdata('message',$this->upload->display_errors());
				redirect('identitas');
			
		    }
	   }
   		$send = $this->Identitas_model->update($data);

	   	if($send){
	   		$this->session->set_flashdata('message', 'identitas berhasil diupdate!');
	      	redirect('identitas');
	   	}
	}
	
	
	public function unlink(){
	
	    $data = $this->input->post(null,true);
	    $this->db->update('tb_identitas',array('logo'=>''),array('id_identitas'=>$data['id_identitas']));
	    if(unlink('./assets/images/icons/'.$data['img'])){
	    	unlink('./assets/images/icons/thumbnail/thumb_'.$data['img']);
	        $this->db->update('tb_identitas',array('logo'=>''),array('id_identitas'=>$data['id_identitas']));
	       echo json_encode(array('status'=>true)); 
	    }
	   
	}
	
	public function delete($id){
	
	    if(!$this->general->privilege_check('IDENTITAS','remove'))
		    $this->general->no_access();
		
		$del = $this->Identitas_model->delete($id);
		
		if($del)
		    redirect('adminweb/identitas');
	}

}
  