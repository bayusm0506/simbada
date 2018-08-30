<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_penyusutan_model extends CI_Model{
	
	var $table = 'Ref_Penyusutan';
	
	function get_umur($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5)
	{
		
		$this->db->select('Metode,Umur');
		/*$this->db->where('Tahun', $this->session->userdata('tahun_anggaran'));*/
		$this->db->from($this->table);
		$result = $this->db->get();
     
        return $result;
	}

	/*update 16-05-2015 */
	function get_masa_manfaat($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5)
	{
		
		$this->db->select('Masa_Manfaat');
		/*$this->db->where('Tahun', $this->session->userdata('tahun_anggaran'));*/
		$this->db->where('Kd_Aset1',$kd_aset1);
		$this->db->where('Kd_Aset2',$kd_aset2);
		$this->db->where('Kd_Aset3',$kd_aset3);
		$this->db->where('Kd_Aset4',$kd_aset4);
		$this->db->where('Kd_Aset5',$kd_aset5);	
		$this->db->from("Ref_Masa_Manfaat");
		$result = $this->db->get();
     
        return $result;
	}
	
}