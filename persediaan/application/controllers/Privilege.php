<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privilege extends CI_Controller{
	
	public function __construct(){
		
		parent::__construct();
		if(!$this->session->userdata('login'))redirect('login');
		$this->load->model('privilege_model');
		if(!$this->general->privilege_check('JABATAN',VIEW))
		    $this->general->no_access();
	}
	
    private function _render($view,$data = array()){
	    $this->template->load('template',$view,$data);
	}

	private function _get_jabatan_by_id($id){
	    
	    $jab = $this->db->select('jabatan')->where('id',$id)->get('jabatan_user')->row();
	    if($jab)
	        return $jab->jabatan;
	        
	    return '';
	}

	public function proses(){
		if(!$this->general->privilege_check('JABATAN',EDIT))
			$this->general->no_access();
		
	    $id = $this->uri->segment(3);
	    $jabatan = $this->_get_jabatan_by_id($id);
	    $box = $this->_add_box();
	    if($this->privilege_model->check_privilege($id))
	        $box = $this->_edit_box($id);

		$data['header']      =	'Otoritas Pengguna Aplikasi ';
		$data['form_action'] = 	site_url('privilege/save');	
		$data['id']          =	$id;
		$data['tr']          =	$box;
		$data['jabatan']     =	$jabatan;
	    $this->_render('adminweb/jabatan/add_privilege',$data);
	}
	
	private function _add_box(){
	
	    $module = $this->privilege_model->modul();
		$arr_action = array('view','add','edit','remove','cetak');
		$tr = '';
		foreach($module as $r){
		    
		    if(isset($r['induk']) and $r['induk']){
		    
		        $tr .= '<tr>';
		    
		        $tr .= '<td><b>'.$r['name'].'</b></td>';
		        $tr .= '<td align="center">
					            <input id="View" value="1" type="checkbox" name="data['.$r['const'].'][view]"/>
				            <td colspan="4"></td>';
		       
		        $tr .= '</tr>';
		    }
		    else{
		        $tr .= '<tr>';
		        
		            $tr .= '<td><b>'.$r['name'].'</b></td>';
		            foreach($arr_action as $act){
		                
		                $tr .= '<td align="center">
					                <input id="'.ucwords($act).'" value="1" type="checkbox" name="data['.$r['const'].']['.$act.']"/>
				                </td>';
		            }
		        $tr .= '</tr>';
		    }
		    
		    if(isset($r['anak'])){
		    
		        foreach($r['anak'] as $k){
		        
		            $tr .= '<tr>';
		                $nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                $tr .= '<td>'.$nbsp.$k['name'].'</td>';
		                foreach($arr_action as $act){
		            
		                    $tr .= '<td align="center">
					                    <input id="'.ucwords($act).'" value="1" type="checkbox" name="data['.$k['const'].']['.$act.']"/>
				                    </td>';
		                }
		            
		            $tr .= '</tr>';
		        }
		    }
		    
		    
		}
		
		return $tr;
	}
	
	public function _edit_box($id){

	    $module = $this->privilege_model->modul();
		$arr_action = array('view','add','edit','remove','cetak');
		$tr = '';
		foreach($module as $r){
		   
		   $role = $this->privilege_model->get_role($r['const'], $id);
		   
		   if(isset($r['induk']) and $r['induk']){
		    
		        $tr .= '<tr>';
		    
		        $tr .= '<td><b>'.$r['name'].'</b></td>';
		        $is_checked  = ($role['view']==1) ? 'checked="checked"' : '';
		        $tr .= '<td align="center">
					        <input id="View" '.$is_checked.' value="1" type="checkbox" name="data['.$r['const'].'][view]"/>
				            <td colspan="4"></td>';
		        $tr .= '</tr>';
		    }
		    else{
		    
		        $tr .= '<tr>';
		        
		            $tr .= '<td><b>'.$r['name'].'</b></td>';
		            foreach($arr_action as $act){
		                
		                $is_checked2 = '';
	                    if(isset($role[$act]) and $role[$act]==1){
	                     
	                        $is_checked2 = 'checked="checked"';
	                    }
		                $tr .= '<td align="center">
					                <input id="'.ucwords($act).'" '.$is_checked2.' value="1" type="checkbox" name="data['.$r['const'].']['.$act.']"/>
				                </td>';
		            }
		        $tr .= '</tr>';
		    }
		    if(isset($r['anak'])){
		    
		        foreach($r['anak'] as $k){
		            
		            $role2 = $this->privilege_model->get_role($k['const'], $id);
		            
		            $tr .= '<tr>';
		                $nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		                $tr .= '<td>'.$nbsp.$k['name'].'</td>';
		                foreach($arr_action as $act){
		                    
		                    $is_checked3 = '';
		                    if(isset($role2[$act]) and $role2[$act]==1){
		                     
		                        $is_checked3 = 'checked="checked"';
		                    }
		                    $tr .= '<td align="center">
					                    <input id="'.ucwords($act).'" '.$is_checked3.' value="1" type="checkbox" name="data['.$k['const'].']['.$act.']"/>
				                    </td>';
		                }
		            
		            $tr .= '</tr>';
		        }
		    }
		    
		    
		}
		
		return $tr;
	}
	
	public function save(){
		
		$data = $this->input->post(null,true);
		$this->privilege_model->save($data);
		redirect('jabatan');
	}
	

}
