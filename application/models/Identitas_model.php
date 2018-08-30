<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Identitas_model extends CI_Model{

    var $table = 'tb_identitas';
	

    public function delete($id){
        
        $get_img  = $this->db->select('logo')
                                ->where('id_identitas',$id)->get('tb_identitas')->row_array();
        
         if(!empty($get_img['logo'])){
                    unlink('./assets/img/real/'.$get_img['logo']);
                    unlink('./assets/img/thumbnail/thumb_'.$get_img['logo']);
            }
        return $this->db->delete('identitas',array('id_identitas'=>$id));
    }
	
     public function update($data){
                
      $arr = array(
            'nama_website'   => $data['nama_website'],
            'alamat_website' => $data['alamat_website'],
            'meta_deskripsi' => $data['meta_deskripsi'],
            'meta_keyword'   => $data['meta_keyword'],
            'alamat'         => $data['alamat'],
            'telp'           => $data['telp'],
            'email'          => $data['email'],
        );              
        
        if(isset($data['logo'])){
                $arr['logo'] = $data['logo'];
        }
        
       return $this->db->update($this->table,$arr);
       
    }    
    
    
}
