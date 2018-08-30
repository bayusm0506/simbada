<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jabatan_model extends CI_Model{

    
    public function save($data){
        
        $arr = array(
        
            'jabatan'    => $data['jabatan'],
            'keterangan' => $data['keterangan']
        );       
        
        return $this->db->insert('jabatan_user',$arr);
    }
    public function update($data){
        
        $arr = array(
        
            'jabatan' => $data['jabatan'],
            'keterangan' => $data['keterangan']
        );       
        
        return $this->db->update('jabatan_user',$arr,array('id'=>$this->session->userdata('id_jabatan_tmp')));
    }
    public function get_data(){
    
        $sql = "SELECT * FROM jabatan_user WHERE 1=1 ";        
        return $this->db->query($sql);
        
    }
    
    
}
